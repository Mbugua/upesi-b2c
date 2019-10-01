<?php

namespace App\Http\Controllers;


// use App\Http\Requests\MpesaClient;
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

        return \response()->json([
            'response'=>[
                'data'=>$request->all()
            ]
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
