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
        Schema::create('stock_keeping_unit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('remain_quantity');
            $table->integer('add_quantity');
            $table->foreign('sku_id')->references('id')->on('stock_keeping_units');
            $table->foreign('user_id')->references('id')->on('admins');
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
        Schema::dropIfExists('stock_keeping_unit_logs');
    }
};
