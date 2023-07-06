<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class UserMerchant extends Model
{
    protected $table = 'user_has_merchant';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'USER_ID',
        'MERCHANT_ID',
    ];
}