<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Advertiser\OrderController;
use App\Http\Controllers\Advertiser\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function(){
    Route::get('/advertiser/dashboard', [OrderController::class, 'index'])->name('advertiser.dashboard');
});

Route::get('/advertiser/dashboard', [WalletController::class, 'index'])->name('advertiser.dashboard');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/wallet/paypal', [WalletController::class, 'handlePayPalPayment'])->name('wallet.paypal');
Route::get('/wallet/paypal/success', [WalletController::class, 'handlePayPalSuccess'])->name('wallet.paypal.success');
Route::get('/wallet/paypal/cancel', [WalletController::class, 'handlePayPalCancel'])->name('wallet.paypal.cancel');
Route::post('/wallet/add-funds', [WalletController::class, 'addFunds'])->name('wallet.addFunds');
