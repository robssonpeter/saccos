<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code');
            $table->double('interest');
            $table->integer('max_recovery_months', false, false);
            $table->double('max_amount')->nullable();
            $table->double('loan_fee_percentage')->nullable();
            $table->double('insurance_fee_percentage')->nullable();
            $table->timestamps();
        });

        \App\Classes\Loan::Populate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_types');
    }
}
