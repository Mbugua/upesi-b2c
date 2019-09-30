<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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

            $initiatorName=env('MPESA_B2C_INITIATOR_NAME');
            $securityCredential=self::getSecurityCredentials(false);
            $commandID=env("MPESA_B2C_COMMANDID");
            $amount=$data->amount;
            $partyA=env('MPESA_B2C_SHORTCODE');
            $partyB=$data->partyB;
            $remarks=$data->remarks;
            $occasion=$data->occasion;
            $queueTimeOutURL=env('MPESA_B2C_QUEUETIMEOUT_URL');
            $resultURL=env('MPESA_B2C_RESULT_URL');
            Log::debug('partyA'.$partyA);
            Log::debug('partyB'.$partyB);
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
    static function getSecurityCredentials($envMode=true){

		($envMode) ? $fopen=fopen(storage_path("certs/sandboxcert.cer"),"r")
            : $fopen=fopen(storage_path("certs/production.cer"),"r");

		$pub_key=fread($fopen,8192);
        fclose($fopen);
        $initiatorPass=env("MPESA_SECURTIY_CREDENTIAL");
        openssl_public_encrypt($initiatorPass,$crypttext,$pub_key);
        $crypted=\base64_encode($crypttext);
        return $crypted;
    }

}
