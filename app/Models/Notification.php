<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisbusrementNotification extends Model
{
    protected $table='notification';

    protected $fillable=[
        'disb_reference',
        'result_type',
        'result_code',
        'result_desc',
        'originator',
        'conversation_id',
        'transaction_id',
    ];
}
