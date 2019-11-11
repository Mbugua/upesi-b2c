<?php

namespace App\Jobs;

use App\Models\Disbursement;
use App\Models\Notification;
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
        $disb_reference=$this->disbursement['reference'];
        //Process Disbursement
        Disbursement::create($this->disbursement);
        Log::info('process disbursement >> '.\json_encode($this->disbursement));
        $b2c=MpesaClient::b2cPaymentRequest((object) $this->disbursement);
        Log::info('check b2c con here >>'.\json_encode($b2c));
        $notificationData=(null != $b2c) ? $b2c :false;

        if(! (object) $notificationData && !$disb_reference ){
            Log::info('notificationData >>'.\json_encode($notificationData));
            exit('0');
        }

        $notification=['conversation_id'=>$notificationData->ConversationID,'originator'=>$notificationData->OriginatorConversationID,'disb_reference'=>$disb_reference];
        Log::info('disbursement notifiaction payload >> '.\json_encode($notificationData));
        $notify= Notification::create($notification);
        $notify->save();

        //update disbursement trx : status
        /**statuses :{ processing, failed, success} */

    }
}
