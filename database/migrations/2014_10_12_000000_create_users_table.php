<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('profile_type_id');
            $table->string('full_name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('tel');
            $table->string('address');
            $table->string('page_name')->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->unsignedInteger('vehicle_type_id')->nullable();
            $table->unsignedInteger('wilaya_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
