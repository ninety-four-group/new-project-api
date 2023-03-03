<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_keeping_units', function (Blueprint $table) {
            $table->id();
            $table->string('code')->uniqid();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger("last_updated_user_id")->nullable();
            $table->integer('quantity');
            $table->decimal('price', $precision = 12, $scale = 2);
            $table->string('status')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
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
