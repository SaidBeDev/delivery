<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('daira_id');
            $table->unsignedInteger('box_status_id')->default(1);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('assigned_user_id')->default(0);
            $table->string('full_name');
            $table->string('tel');
            $table->string('code');
            $table->string('address');
            $table->string('note')->nullable();
            $table->string('price');
            $table->string('total_price');
            $table->boolean('is_recieved')->default(false);
            $table->boolean('is_returned')->default(false);
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
        Schema::dropIfExists('boxes');
    }
}
