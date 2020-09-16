<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('birth_date');
            $table->string('occupation');
            $table->string('adress');
            $table->string('phone');
            $table->string('sub_parish')->nullable();
            $table->string('zone')->nullable();
            $table->string('congregation')->nullable();
            $table->string('house_number')->nullable();
            $table->string('heir_full_name');
            $table->string('heir_house_number');
            $table->string('heir_relation');
            $table->string('ref_1_id')->nullable();
            $table->string('ref_2_id')->nullable();
            $table->string('confirmed')->nullable()->comment('null means unapproved, 0 disapproved, 1 approved');
            $table->string('user_id')->nullable();
            $table->string('customer_id')->nullable();
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
        Schema::dropIfExists('registrations');
    }
}
