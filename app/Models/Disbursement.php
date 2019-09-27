<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    protected $table = 'disbursement';

    protected $fillable=[
        'reference', 
        'amount',
        'paybill',//PartyA
        'msisdn',//PartyB\
        'remarks',
        'occassion',
    ];
}
