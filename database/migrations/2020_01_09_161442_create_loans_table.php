<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_key');
            $table->string('type');
            $table->string('rate')->comment('monthly rate in percentage');
            $table->string('recovery_months');
            $table->string('amount');
            $table->integer('accepted', false, false)->nullable()->comment('1 for accepted, 0 for rejected, null for pending');
            $table->date('disbursement_date')->nullable();
            $table->string('disbursement_method')->nullable();
            $table->integer('completed', false, false)->nullable();
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
        Schema::dropIfExists('loans');
    }
}
