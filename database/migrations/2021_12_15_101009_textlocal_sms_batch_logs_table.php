<?php

use GoApptiv\TextLocal\Services\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TextlocalSmsBatchLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('textlocal_sms_batch_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Account Relation
            $table->bigInteger('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('textlocal_accounts');

            $table->integer('total')->default(0);
            $table->integer('delivered')->nullable()->default(0);
            $table->enum('status', Constants::$statuses)->default(Constants::$PENDING);
            $table->string('comment')->nullable();

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
        Schema::dropIfExists('textlocal_sms_batch_logs');
    }
}
