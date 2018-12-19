<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->text('sender_address');
            $table->integer('sender_city');

            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');
            $table->integer('receiver_city');

            $table->double('weight');
            $table->string('status');
            $table->string('payment_type')->default('Ap');
            $table->double('price');
            $table->string('pick_up_type');
            $table->integer('source_branch');
            $table->integer('dest_branch');

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
        Schema::dropIfExists('shipments');
    }
}
