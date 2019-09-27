<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class MpesaClient extends FormRequest
{
    /**
     * @param data
     */
    static function requestB2C(array $data=[]){
        $initiatorName=env('MPESA_B2C_INITIATORNAME');
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

        $crypted = base64_encode(openssl_public_encrypt(env("MPESA_SECURTIY_CREDENTIAL"),$crypttext));
        Log::info('securityCredential >>'.$crypted);
        return $crypted;
    }

}
