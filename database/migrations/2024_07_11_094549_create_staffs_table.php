<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffsTable extends Migration
{
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('image', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('position', 255);
            $table->bigInteger('salary');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staffs');
    }
}
