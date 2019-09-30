<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class MpesaClient
{
    /**
     * @param data
     */
    static function requestB2C(){
        Log::info('request Payment >>');

        $initiatorName=env('MPESA_B2C_INITIATOR_NAME');
        $securityCredential=self::getSecurityCredentials(false);

        return $securityCredential;
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
        Log::info('securityCredential >>'.$crypted);
        return $crypted;
    }

}
