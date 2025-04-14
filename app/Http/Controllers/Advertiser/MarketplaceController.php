<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Collection;

set_time_limit(300);
class MarketplaceController extends Controller
{
    public function index(){
        


        $user = auth()->user();
        $wallet = Wallet::where('status', 'COMPLETED')->where('user_id', $user->id);
        // Calculate the total balance = all credits - all debits
        if($wallet){
            $totalBalance = Wallet::where('user_id', $user->id)
                ->selectRaw("SUM(CASE WHEN credit_debit = 'credit' THEN amount ELSE 0 END) - SUM(CASE WHEN credit_debit = 'debit' THEN amount ELSE 0 END) as balance")
                ->value('balance');

            // If user has no transactions yet, balance might be null, so default to 0
            $totalBalance = $totalBalance ?? 0; 
        }
        // Fetching data from the API
        // $api1Inventory = Http::withToken('PKvUIEnrIMSViaD3BbJ1qJleBMMRY1')->post('https://lp-latest.elsnerdev.com/api/fetch-inventory', [
        //     "page_no" => 1 
        // ])->json();
        return view('advertiser.marketplace.list', compact('totalBalance'));
    }

    public function addCart(){
      return 1;  
    }

    public function getData(Request $request){
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $page = ($start / $length) + 1;
    
        $search = $request->input('search.value');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $columns = $request->input('columns');
        $order = $request->input('order');

        $orderBy = 'da'; 
        $orderDirection = 'desc';

        if (!empty($order) && isset($columns)) {
            $orderColumnIndex = $order[0]['column'] ?? 0;
            $orderDirection = $order[0]['dir'] ?? 'desc';
            $orderBy = $columns[$orderColumnIndex]['data'] ?? 'da';
        }

        // get the filter values from the request
        $minDaFilter = $request->input('min_da_filter');
        $maxDaFilter = $request->input('max_da_filter');
        $categories = $request->input('category_filter', []);
        $minPriceFilter = $request->input('min_price_filter');
        $maxPriceFilter = $request->input('max_price_filter');
        $countryFilter = $request->input('country_filter', []);
        $languageFilter = $request->input('language_filter', []);
        $minAhrefFilter = $request->input('min_ahref_filter');
        $maxAhrefFilter = $request->input('max_ahref_filter');
        $minSemrushFilter = $request->input('min_semrush_filter');
        $maxSemrushFilter = $request->input('max_semrush_filter');
        $minDr = $request->input('min_dr');
        $maxDr = $request->input('max_dr');
        $tatFilter = $request->input('tat_filter', []);
        $minAuthorityFilter = $request->input('min_authority_filter');
        $maxAuthorityFilter = $request->input('max_authority_filter');
        $linkTypeFilter = $request->input('link_type_filter');

        //dd($request->all());
        
        if(is_array($tatFilter)){
            $tatFilter = implode(',', $tatFilter);
        }
        if(is_array($categories)){
            $categories = implode(',', $categories);
        }
        if(is_array($languageFilter)){
            $languageFilter = implode(',', $languageFilter);
        }
        if(is_array($countryFilter)){
            $countryFilter = implode(',', $countryFilter);
        }
        //dd($categories);
        // send the parameters to the external api
        $response = Http::withToken('PKvUIEnrIMSViaD3BbJ1qJleBMMRY1')->post('https://lp-latest.elsnerdev.com/api/fetch-inventory', [
            'draw' => $draw,
            'page_no' => $page,
            'per_page' => $length,
            'search' => $search,
            'order_by' => $orderBy,
            'order' => $orderDirection,
            'min_da_filter' => $minDaFilter,
            'max_da_filter' => $maxDaFilter,
            'category_filter' => $categories,
            'min_price_filter' => $minPriceFilter,
            'max_price_filter' => $maxPriceFilter,
            'country_filter' => $countryFilter,
            'language_filter' => $languageFilter,
            'min_ahref_filter' => $minAhrefFilter,
            'max_ahref_filter' => $maxAhrefFilter,
            'min_semrush_filter' => $minSemrushFilter,
            'max_semrush_filter' => $maxSemrushFilter,
            'min_dr' => $minDr,
            'max-dr' => $maxDr,
            'tat_filter' => $tatFilter,
            'min_authority_filter' => $minAuthorityFilter,
            'max_authority_filter' => $maxAuthorityFilter,
            'link_type_filter' => $linkTypeFilter,
        ]);
    
        $data = $response->json();
     
        $items = $data['data']['items'] ?? [];
        $totalRecords = $data['data']['total_records'] ?? count($items);
        //dd($items);
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $items
        ]);
    }    
            
}
