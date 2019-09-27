<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Request\MpesaClient;

class DisbursementController extends Controller
{

    function disburse(Request $request){

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
