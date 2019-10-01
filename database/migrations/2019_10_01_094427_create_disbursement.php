<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisbursement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('msisdn');
            $table->float('amount');
            $table->string('shortcode');
            $table->string('reference');
            $table->text('remarks');
            $table->text('occasion');
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
        Schema::dropIfExists('disbursement');
    }
}
