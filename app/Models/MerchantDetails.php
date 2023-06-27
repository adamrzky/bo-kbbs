<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantDetails extends Model
{
    protected $table = 'QRIS_MERCHANT_DETAILS';
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
