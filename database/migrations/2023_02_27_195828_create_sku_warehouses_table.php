<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->integer('quantity')->default(0);
            $table->foreign('sku_id')->references('id')->on('stock_keeping_units');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->softDeletes();
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
        Schema::dropIfExists('sku_warehouses');
    }
};
