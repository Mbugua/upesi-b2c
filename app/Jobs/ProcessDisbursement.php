<?php

namespace App\Jobs;
use App\Models\Disbursement;
use App\Http\Requests\MpesaClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDisbursement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $disbursement;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($disbursement)
    {
        $this->disbursement=$disbursement;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Process Disbursement
        Disbursement::create($this->disbursement);

        Log::info('Processing new deisbursment request >>'.json_encode($this->disbursement));
        $b2c=MpesaClient::b2cPaymentRequest((object) $this->disbursement);
        if($b2c){
            $notificationData=\json_decode($b2c);
            // Log::info('disbursement response >>'.$b2c);
            \var_dump($notificationData);
            Log::info('disbursement response >>'.$notificationData);
            // $notification=['converstation_id'=>$notificationData['ConversationID'],'originator'=>$notificationData['OriginatorConversationID'],'disb_reference'=>$this->disbursement->reference];
            // Log::info('disbursement notifiaction payload >>'.\json_encode($notificationData));
            //    $notify= DisbursementNotification::create($notification);
            //    $notify->save();
        }
        //disbursement status
        /** processing, failed, success */

    }
}
