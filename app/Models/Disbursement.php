<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    protected $table = 'disbursement';

    protected $fillable=[
        'reference', //unique hash 
        'amount',
        'paybill',//PartyA
        'msisdn',//PartyB\
        'remarks',
        'occassion',
    ];
}
