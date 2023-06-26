<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantDetails extends Model
{
    protected $table = 'qris_merchant_details';
    public $timestamps = false;
       
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'ID',
        'MERCHANT_ID',
        'DOMAIN',
        'TAG',
        'MPAN',
        'MID',
        'CRITERIA'
    ];

}
