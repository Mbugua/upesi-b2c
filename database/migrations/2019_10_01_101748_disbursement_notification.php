<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DisbursementNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('disb_reference','191')->unique();
            $table->string('result_type')->nullable();
            $table->string('result_code','6')->nullable();
            $table->string('result_desc')->nullable();
            $table->string('originator')->nullable();
            $table->string('conversation_id')->nullable();
            $table->string('transaction_id','32')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
         Schema::dropIfExists('notification');
    }
}
