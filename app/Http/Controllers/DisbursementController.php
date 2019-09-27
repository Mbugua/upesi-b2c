<?php

namespace App\Http\Controllers;


use App\Http\Requests\MpesaClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class DisbursementController extends Controller
{

    function payment(Request $request){
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

    function notFound(){
        return \response()->json([
            'response'=>[
                'status'=>'failed',
                'data'=>[
                    'code'=>400,
                    'message'=>"Bad Request"
                ]
            ]
        ],400);
    }
}
