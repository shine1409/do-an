<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('number');
            $table->bigInteger('seats');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
