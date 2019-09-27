<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Request\MpesaClient;

class DisbursementController extends Controller
{

    function disburse(Request $request){
    Log::info('Disbursement Request >>'. \json_encode($request->all()));
    $res=MpesaClient::requestB2C();
    return \response()->json([
            'response'=>[
                'status'=>'success',
                'data'=>[
                    'message'=>$res
                ]
            ]
        ]);
    }

}
