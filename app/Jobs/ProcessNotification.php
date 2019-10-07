<?php

namespace App\Jobs;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $notification;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $notify=Notification::updateOrCreate(
                                ['conversation_id' => $this->notification['conversation_id'],
                                'originator' => $this->notification['originator']],
                                ['result_type'=>$this->notification['result_type'],
                                 'result_desc'=>$this->notification['result_desc'],
                                 'transaction_id'=>$this->notification['transaction_id'],
                                 'result_code'=>$this->notification['result_code']
                ]);
            $notify->save();
    }
}
