<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Safaricom\Mpesa\Mpesa;

class MpesaClient
{
    /**
     * @param data
     */
    static function b2cPaymentRequest($data){
        Log::info('MpesaClient::b2cPaymentRequest >>'.\json_encode($data));
        try{
            $mpesa = new \Safaricom\Mpesa\Mpesa();
            $securityCredential=self::generateSC();
            $commandID=env("MPESA_B2C_COMMANDID") ?:'SalaryPayment';
            $amount=$data->amount;
            $partyA=env('MPESA_B2C_SHORTCODE') ?:$data->shortcode;
            $partyB=$data->msisdn;
            $remarks=$data->remarks;
            $occasion=$data->occasion;
            $queueTimeOutURL=env('MPESA_B2C_QUEUETIMEOUT_URL');
            $resultURL=env('MPESA_B2C_RESULT_URL');
            $initiatorName=env('MPESA_B2C_INITIATOR_NAME')?:$partyA;

            $b2cTransaction=$mpesa->b2c($initiatorName, $securityCredential, $commandID, $amount, $partyA, $partyB, $remarks, $queueTimeOutURL, $resultURL, $occasion);
            return $b2cTransaction;

        }catch(Exception $e){
            return $e;
        }
    }

    /**
     * Generate Security Credential token
     * by encrypting API password using the public cert
     * provided.
     *
     * @param envMode :sandox|production
     * return string
     */
    static function getSecurityCredentials(){
        $envMode =\env('MPESA_ENV');
        Log::info('<<< check envMode for cert >>>'.$envMode);
        ($envMode=='sandbox') ? $fopen=fopen(storage_path("certs/cert.cer"),"r")
            : $fopen=fopen(storage_path("certs/production.cer"),"r");

		$pub_key=fread($fopen,8192);
        fclose($fopen);
        $initiatorPass=env("MPESA_SECURTIY_CREDENTIAL");
        openssl_public_encrypt($initiatorPass,$crypttext,$pub_key);
        $crypted=\base64_encode($crypttext);
        Log::info("<<SecurityCredentials >>>".json_encode($crypted));
        return $crypted;
    }


    static function generateSC(){
        $envMode=\env('MPESA_ENV');
        $initiatorPass=\env("MPESA_SECURITY_CREDENTIAL");
        switch($envMode){
            case 'sandbox':
            $publicKey=\file_get_contents(\storage_path('certs/cert.cer'));
            \openssl_public_encrypt($initiatorPass,$encrypted,$publicKey,OPENSSL_PKCS1_PADDING);
            $sc = \base64_encode($encrypted);
            Log::info("<< sandbox SecurityCredentials >>>".json_encode($sc));
            break;
            case 'live':
            $publicKey=\file_get_contents(\storage_path('certs/production.cer'));
            \openssl_public_encrypt($initiatorPass,$encrypted,$publicKey,OPENSSL_PKCS1_PADDING);
            $sc = \base64_encode($encrypted);
            Log::info("<<live SecurityCredentials >>>".json_encode($sc));
            break;
            default:
            $sc = ['message'=>"Invalid env set"];
            break;
        }
    }
}
