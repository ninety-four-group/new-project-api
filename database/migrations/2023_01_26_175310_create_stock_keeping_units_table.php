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
        Schema::create('stock_keeping_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger("last_updated_user_id")->nullable();
            $table->integer('quantity');
            $table->decimal('price', $precision = 12, $scale = 2);
            $table->string('status')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('variation_id')->references('id')->on('variations');
            $table->foreign('last_updated_user_id')->references('id')->on('admins');
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
        Schema::dropIfExists('stock_keeping_units');
    }
};