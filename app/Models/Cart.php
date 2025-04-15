<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $fillable = [
        'user_id',
        'website_id',
        'host_url',
        'da',
        'tat',
        'semrush',
        'guest_post_price',
        'linkinsertion_price',
        'status',
        'response_cart_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
