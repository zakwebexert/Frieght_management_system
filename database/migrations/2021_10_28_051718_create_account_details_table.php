<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_account_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('business_name');
            $table->string('trading_name')->nullable();
            $table->string('account_manager')->nullable();
            $table->string('account_status')->nullable();
            $table->string('account_code')->nullable();
            $table->string('industry')->nullable();
            $table->string('ABN')->nullable();
            $table->string('ACN')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('credit_limit')->nullable();
            $table->string('billing_method')->nullable();
            $table->string('review_date')->nullable();
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
        Schema::dropIfExists('account_details');
    }
}
