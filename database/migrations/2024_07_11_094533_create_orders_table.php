<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->foreignId('voucher_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('code_order')->nullable();
            $table->text('note')->nullable();
            $table->string('status', 20)->default('Chưa thanh toán');
            $table->date('order_date')->nullable();
            $table->time('order_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
