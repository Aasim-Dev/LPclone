<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
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
        $users = Auth::user();
        $cartItems = Cart::where('user_id', $users->id)->get();
        $cartIds = Cart::where('user_id', $users->id)->where('status', 'cart')->pluck('website_id')->toArray();
        if($cartItems->isEmpty()){
            return view('advertiser.marketplace.list', compact('totalBalance', 'users'))->with('message', 'No items in cart');
        }else{
            return view('advertiser.cart.items', compact('totalBalance', 'cartItems', 'users', 'cartIds'));
        }
    }

    public function wishlistShow(Request $request){
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
        $cartItems = Cart::where('user_id', $user->id)->get();
        $cartIds = Cart::where('user_id', $user->id)->where('status', 'wishlist')->pluck('website_id')->toArray();
        if($cartItems->isEmpty()){
            return view('advertiser.marketplace.list' , compact('totalBalance', 'user'))->with('message', 'No items in cart');
        }else{
            return view('advertiser.cart.wishlist', compact('totalBalance', 'cartItems', 'cartIds'));
        }
    }

    public function destroy(Request $request){
        $request->validate([
            'website_id' => ['required', 'integer']
        ]);
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('website_id', $request->website_id)->first();
        if ($cart) {
            $cart->delete();
            //Call external API to remove
            Http::withHeaders([
                'Authorization' => 'Bearer 1SeaFvgwn6RoKKpdL2j2BEAxjwc2ze',
            ])->post('https://lp-latest.elsnerdev.com/api/cart/store', [
                'website_id' => $request->website_id,
                'action' => 'remove',
            ]);

            return response()->json([
                'status' => 'removed',
                'message' => 'Website removed from cart',
            ]);
        }
        return view('advertiser.marketplace.list')->with('message', 'No items in cart');
    }

    public function addCart(Request $request){
        $request->validate([
            'website_id' => 'required|integer',
        ]);

        $user = Auth::user();

        //Check if already in cart
        $existingCart = Cart::where('user_id', $user->id)
                            ->where('website_id', $request->website_id)
                            ->first();

        if ($existingCart) {
            $existingCart->delete();
            //Call external API to remove
            Http::withHeaders([
                'Authorization' => 'Bearer 1SeaFvgwn6RoKKpdL2j2BEAxjwc2ze',
            ])->post('https://lp-latest.elsnerdev.com/api/cart/store', [
                'website_id' => $request->website_id,
                'action' => 'remove',
            ]);

            return response()->json([
                'status' => 'removed',
                'message' => 'Website removed from cart',
            ]);
        }

        //Add to external cart API
        $apiResponse = Http::withHeaders([
            'Authorization' => 'Bearer 1SeaFvgwn6RoKKpdL2j2BEAxjwc2ze',
        ])->post('https://lp-latest.elsnerdev.com/api/cart/store', [
            'website_id' => $request->website_id,
            'action' => 'add',
        ]);

        if ($apiResponse->failed()) {
            return response()->json(['message' => 'Failed to sync with external API'], 500);
        }

        $cartIdApi = $apiResponse->json();
        $cartId = $cartIdApi['cart_id'][0] ?? null;
        //dd($cartId);
        $cart = Cart::create([
            'user_id' => $user->id,
            'website_id' => $request->website_id,
            'host_url' => $request->host_url,
            'da' => $request->da,
            'tat' => $request->tat,
            'semrush' => $request->semrush,
            'guest_post_price' => $request->guest_post_price,
            'linkinsertion_price' => $request->linkinsertion_price,
            'response_cart_id' => $cartId,
            'status' => 'cart',
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Website added to cart',
            'cart_id' => $cart->id,
            'response_cart_id' => $cartId,
        ]);
    }

    public function storeProvideContent(Request $request){
        
        $request->validate([
            'type' => 'string|in:provide_content',
            'language' => '|string',
            'special_instruction' => 'string',
            'website_id' => 'integer',
            'attachment' => 'file|mimes:doc,docx,pdf|max:2048'
        ]);

        $fileName = 'sample.docx'; 
        $filePath = public_path($fileName);
        //dd($filePath);
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found on server'], 404);
        }
    
        $response = Http::withToken('1SeaFvgwn6RoKKpdL2j2BEAxjwc2ze')
            ->attach(
                'attachment',
                file_get_contents($filePath),
                $fileName
            )
            ->post('https://lp-latest.elsnerdev.com/api/cart/gp-order-info', [
                'type' => $request->type,
                'language' => $request->language,
                'special_instruction' => $request->special_instruction,
                'website_id' => $request->website_id,
            ]);
    
        $cart = Cart::where('user_id', $request->user()->id)
                    ->where('website_id', $request->website_id)
                    ->first();

        if($cart){
            Cart::where('user_id', $request->user()->id)
                ->where('website_id', $request->website_id)
                ->update([
                            'type' => $request->type,
                            'language' => $request->language,
                            'special_instruction' => $request->special_instruction,
                            'attachment' => $fileName,
                        ]);
                    
            return response()->json([
                'message' => 'Content updated to cart successfully',
                'cart_id' => $cart->id
            ]);
        } else {
            return response()->json([
                'message' => 'Cart item not found',
                'cart_id' => null
            ], 404);
        }
    }

    public function linkInsertion(Request $request){
        $user = Auth::user();
        $request->validate([
          
            'website_id' => ['integer'],
        ]);

        $data = [
            'type' => 'link_insertion',
            'language' => 'English',
            'existing_post_url' => 'https://example.com/existing-post',
            'target_url' => 'https://example.com/target-url',
            'anchor_text' => 'Click here',
            'special_note' => 'Please avoid emails or phone numbers.',
            'website_id' => $request->website_id,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer 1SeaFvgwn6RoKKpdL2j2BEAxjwc2ze',   
        ])->post('https://lp-latest.elsnerdev.com/api/cart/li-order-info', $data);
        $cart = Cart::where('user_id', $request->user()->id)->where('website_id', $request->website_id)->first();
        if($cart){
            Cart::where('user_id', $request->user()->id)
                ->where('website_id', $request->website_id)
                ->update($data);
            return response()->json([
                'message' => 'Content updated to cart successfully',
                'cart_id' => $cart->id
            ]);
        }else {
            return response()->json([
                'message' => 'Cart item not found',
                'cart_id' => null
            ], 404);
        }
    }

    public function hireContent(Request $request){
        $request->validate([
            'website_id' => ['integer'],
        ]);

        $data = [
            'type' => 'guest_post',
            'language' => 'English',
            'title_suggestion' => 'Sample Title',
            'keywords' => 'keyword1, keyword2',
            'anchor_text' => 'click here',
            'country' => 'USA', 
            'word_count' => '1000',
            'category' => 'Technology',
            'reference_link' => 'https://example.com/reference',
            'target_url' => 'https://example.com/target-url',
            'special_note' => 'Please avoid emails or phone numbers.',
            'website_id' => $request->website_id,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer 1SeaFvgwn6RoKKpdL2j2BEAxjwc2ze', 
        ])->post('https://lp-latest.elsnerdev.com/api/cart/content-gp-order-info', $data);

        $cart = Cart::where('user_id', $request->user()->id)->where('website_id', $request->website_id)->first();
        if($cart){
            Cart::where('user_id', $request->user()->id)
                ->where('website_id', $request->website_id)
                ->update($data);
            return response()->json([
                'message' => 'Content updated to cart successfully',
                'cart_id' => $cart->id
            ]);
        }else {
            return response()->json([
                'message' => 'Cart item not found',
                'cart_id' => null
            ], 404);
        }
    }

    public function cartCount(){
        $user = Auth::user();
        $count = Cart::where('user_id', $user->id)->count();
        return response()->json(['count' => $count]);
    }  

    public function getCartWebsites() {
        $user = Auth::user();
        $cartIds = Cart::where('user_id', $user->id)->pluck('website_id')->toArray();
        return response()->json(['cart' => $cartIds]);
    }
    
}
