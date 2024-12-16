<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 255);
            $table->bigInteger('discount');
            $table->bigInteger('number_use');
            $table->date('start_time');
            $table->date('end_time')->useCurrent();
            $table->text('describe')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
