<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'wallet';
    protected $fillable= [
        'user_id',
        'transaction_id',
        'transaction_reference',
        'order_type',
        'description',
        'payment_status',
        'payment_type',
        'credit_debit',
        'amount',
        'total',
        'paypal_fee',
        'tax',
        'order_id',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
