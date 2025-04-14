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
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.2.dir', 'desc');
        $columns = $request->input('columns');
        $orderBy = $columns[$orderColumnIndex]['data'] ?? 'created_at';
    
        // get the filter values from the request
        $minDaFilter = $request->input('min_da_filter');
        $maxDaFilter = $request->input('max_da_filter');
        $categoryFilter = $request->input('category_filter');
        $minPriceFilter = $request->input('min_price_filter');
        $maxPriceFilter = $request->input('max_price_filter');
        $dateFilter = $request->input('date_filter');
    
        // send the parameters to the external api
        $response = Http::withToken('PKvUIEnrIMSViaD3BbJ1qJleBMMRY1')->post('https://lp-latest.elsnerdev.com/api/fetch-inventory', [
            'draw' => intval($draw),
            'page_no' => $page,
            'per_page' => $length,
            'search' => $search,
            'order_by' => $orderBy,
            'order' => $orderDirection,
            'min_da_filter' => $minDaFilter,
            'max_da_filter' => $maxDaFilter,
            'category_filter' => $categoryFilter,
            'min_price_filter' => $minPriceFilter,
            'max_price_filter' => $maxPriceFilter,
            'date_filter' => $dateFilter,
        ]);
    
        $data = $response->json();
        //dd($data);
        $items = $data['data']['items'] ?? [];
        $totalRecords = $data['data']['total_records'] ?? count($items);
    
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $items
        ]);
    }    
            
}
