<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('special_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2);
            $table->string('brand')->nullable();
            $table->string('qty')->default(0);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('taxes', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->string('url_key')->unique();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
