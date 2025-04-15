<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        return view('advertiser.dashboard', compact('user'));
    }
}
