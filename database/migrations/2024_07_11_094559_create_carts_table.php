<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('quantity');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
