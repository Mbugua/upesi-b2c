<?php

namespace App\Http\Controllers;


use App\Jobs\ProcessNotification;
use App\Jobs\ProcessDisbursement;
use App\Models\Disbursement;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Hashids\Hashids;


class DisbursementController extends Controller
{
    /**
     * Payment Route
     * @param request
     */
    function disburse(Request $request){
        $hash=new Hashids('upesi-b2c-api');
        $shortcode=$request->shortcode ?: env('MPESA_B2C_SHORTCODE');
        $reference=$hash->encode(time(), $shortcode, intval($request->input('amount')), $request->input('msisdn'));
        $remark='upesi_loan_request_'.$request->input('msisdn');
        $occasion= 'upesi_disbx_'.$reference;
        $msisdn=$request->input('msisdn');
        	$length = strlen($msisdn);
			if($length != 12 && strpos($msisdn,'07') === 0 && $length == 10){
				$msisdn = "254".substr($msisdn,1);
			}
        $data= [
                'amount'=>$request->input('amount'),
                'msisdn'=>$msisdn,
                'remarks'=>$remark,
                'occasion'=>$occasion,
                'shortcode'=>$shortcode,
                'reference'=>$reference,
            ];

        //Queue payments
        ProcessDisbursement::dispatch($data)->onQueue('disbsursements')->delay(3);

            return \response()->json([
                    'response'=>[
                        'status'=>'success',
                        'message'=>'OK',
                        'code'=>200
                    ]
                    ],200);
    }

    /**
     * Result Route
     * Accepts a http POST request
     * @param request
     *
     */
    function result(Request $request){
        Log::info('Disbursement::result >>'.\json_encode($request->all()));
        $data=$request->Result;
        Log::debug('result >>>'.\json_encode($data['ResultType']));
        // $notification=['result_type'=>$data->Result['ResultType'],'result_code'=>$data->ResultCode,'result_desc'=>$data->ResultDesc,'transaction_id'=>$data->TransactionID];
        // {"ResultType":0,"ResultCode":2001,"ResultDesc":"The initiator information is invalid.","OriginatorConversationID":"8689-8822575-1","ConversationID":"AG_20191007_0000722022d0c21bfd6d","TransactionID":"NJ771HAKXL
        // ProcessNotification::dispatch($notification)->onQueue('disbursement_notification')->delay(4);
        return \response()->json([
            'response'=>['status'=>200,'message'=>'Ok']
        ],200);
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
