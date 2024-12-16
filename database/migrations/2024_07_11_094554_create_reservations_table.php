<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('note')->nullable();
            $table->bigInteger('seats')->nullable();
            $table->string('status', 20)->default('Chưa thanh toán');
            $table->date('reservation_date')->nullable();
            $table->time('reservation_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
