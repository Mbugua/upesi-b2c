<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{

    protected $table = 'disbursement';

    protected $fillable=[
        'reference',
        'amount',
        'shortcode',
        'msisdn',
        'remarks',
        'occasion',
    ];
}
