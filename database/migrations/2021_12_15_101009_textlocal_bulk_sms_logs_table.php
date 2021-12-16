<?php

use GoApptiv\TextLocal\Services\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TextlocalBulkSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('textlocal_bulk_sms_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Account Relation
            $table->bigInteger('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('textlocal_accounts');

            $table->string('textlocal_batch_id')->nullable();
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
        //
    }
}
