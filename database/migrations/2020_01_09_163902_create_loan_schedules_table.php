<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('loan_id');
            $table->string('customer_id');
            $table->date('expected_pay_date');
            $table->double('amount')->comment('monthly amount');
            $table->double('paid_amount')->comment('amount already repaid');
            $table->double('paid')->comment("1 means paid 0 unpaid");
            $table->double('interest');
            $table->double('opening_balance');
            $table->double('closing_balance');
            $table->integer('installment_number', false, false);
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
        Schema::dropIfExists('loan_schedules');
    }
}
