<?php

use GoApptiv\TextLocal\Services\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TextlocalSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('textlocal_sms_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_id')->unique();

            // Account Relation
            $table->bigInteger('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('textlocal_accounts');

            $table->string('mobile');
            $table->string('message');
            $table->string('sender');
            $table->enum('status', Constants::$statuses)->default(Constants::$PENDING);

            // Bulk SMS Relation
            $table->bigInteger('batch_id')->unsigned();
            $table->foreign('batch_id')->references('id')->on('textlocal_sms_batch_logs');

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
        Schema::dropIfExists('textlocal_sms_logs');
    }
}
