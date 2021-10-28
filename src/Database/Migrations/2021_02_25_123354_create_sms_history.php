<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mobile_number', 480);
            $table->string('message', 480)->nullable();
            $table->string('gateway', 60)->nullable();
            $table->tinyInteger('status', false, true)->default(0)->comment('0=request to send, 1 = send, 2 = failed');
            $table->string('api_response', 480)->nullable();
            $table->string('sms_submitted_id', 480)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('sms_history');
    }
}
