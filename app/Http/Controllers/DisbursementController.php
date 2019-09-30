<?php

namespace App\Http\Controllers;


use App\Http\Requests\MpesaClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class DisbursementController extends Controller
{
    /**
     * Payment Route
     * @param request
     */
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

    /**
     * Result Route
     * Accepts a http POST request
     * @param request
     *
     */
    function result(Request $request){
        Log::info('Disbursement::result >>'.\json_encode($request->all()));
    }

    /**
     * Timeout Route
     * Accepts a Http POST Request
     * @param request
     */
    function timeout(Request $request){
        Log::info('Disbursement::timeout >> '.\json_encode($request->all()));
        //To Do add to queue to resend.
    }

    /**
     * Generic fallback route
     *
     */
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
