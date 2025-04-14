<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\User;

class WalletController extends Controller
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
        return view('advertiser.dashboard', compact('user', 'totalBalance'));
    }
    public function addFunds(Request $request){
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'paymentMethod' => 'required|string|in:paypal,razorpay',
        ]);

        // You can store this as a pending transaction for admin to process later, or expand Razorpay here
        Wallet::create([
            'user_id'              => Auth::id(),
            'transaction_id'       => null,
            'transaction_reference'=> null,
            'order_type'           => 'add_fund_manual',
            'description'          => 'Add fund request submitted',
            'payment_status'       => 'pending',
            'payment_type'         => $request->paymentMethod,
            'credit_debit'         => 'credit',
            'amount'               => $request->amount,
            'total'                => $request->amount,
            'paypal_fee'           => null,
            'tax'                  => null,
            'order_id'             => null,
        ]);

        return redirect()->route('advertiser.dashboard')->with('success', 'Fund request submitted!');
    }
    public function handlePayPalPayment(Request $request){
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],

        ]);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->amount
                    ]
                ]
            ],
            "application_context" => [
                "return_url" => route('wallet.paypal.success'),
                "cancel_url" => route('wallet.paypal.cancel')
            ]
        ]);
        return redirect($order['links'][1]['href']);
    }

    public function handlePayPalSuccess(Request $request){
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal')); //to set api credentials to the variable
        $token = $provider->getAccessToken(); // to get the access token from PayPal Client.
        $provider->setAccessToken($token); // to set the access token from PayPal Client to the variable $provider.

        $response = $provider->capturePaymentOrder($request->token);

        if($response['status'] == 'COMPLETED'){
            $capture = $response['purchase_units'][0]['payments']['captures'][0]; //it is to store details we get from paypal response into the variable.
            $currentBalance = Wallet::where('user_id', Auth::id())
                ->selectRaw("SUM(CASE WHEN credit_debit = 'credit' THEN amount ELSE 0 END) - SUM(CASE WHEN credit_debit = 'debit' THEN amount ELSE 0 END) as balance")
                ->value('balance');

            // Add new credit amount
            $newBalance = $currentBalance + $capture['amount']['value'];
            Wallet::create([
                'user_id'              => Auth::id(),
                'transaction_id'       => $capture['id'],
                'transaction_reference'=> $response['id'],
                'order_type'           => 'add_fund',
                'description'          => 'Funds added via PayPal',
                'payment_status'       => $capture['status'],
                'payment_type'         => 'paypal',
                'credit_debit'         => 'credit',
                'amount'               => $capture['amount']['value'],
                'total'                => $newBalance,
                'paypal_fee'           => $capture['seller_receivable_breakdown']['paypal_fee']['value'] ?? null,
                'tax'                  => $capture['seller_receivable_breakdown']['tax_total']['value'] ?? null,
                'order_id'             => null
            ]);

            return redirect()->route('advertiser.dashboard')->with('success', 'Funds added successfully!');
        }elseif($response['status'] == 'PENDING'){
            // Handle pending payment
            Wallet::create([
                'user_id'              => Auth::id(),
                'transaction_id'       => $capture['id'],
                'transaction_reference'=> $response['id'],
                'order_type'           => 'add_fund',
                'description'          => 'Funds added via PayPal (Pending)',
                'payment_status'       => $capture['status'],
                'payment_type'         => 'paypal',
                'credit_debit'         => 'credit',
                'amount'               => $capture['amount']['value'],
                'total'                => null,
                'paypal_fee'           => null,
                'tax'                  => null,
                'order_id'             => null
            ]);

            return redirect()->route('advertiser.dashboard')->with('info', 'Payment is pending.');
        }
        return redirect()->route('advertiser.dashboard')->with('error', 'Payment failed');
    }
    public function handlePayPalCancel()
    {
        return redirect()->route('advertiser.dashboard')->with('error', 'Payment was cancelled.');
    }
}
