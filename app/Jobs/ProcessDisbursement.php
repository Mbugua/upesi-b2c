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
        Log::debug('b2c result >>>'.\json_encode($b2c));
        if($b2c){
            // $notification=['converstation_id'=>$b2c,'transaction_id'=>$b2c,'disb_reference'=>$this->disbursement->reference];
            // DisbursementNotification::create($notification);
        }

        // $this->disbursement->save();

    }
}
