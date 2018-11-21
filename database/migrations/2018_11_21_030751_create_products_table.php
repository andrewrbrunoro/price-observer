<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');

            $table->string('job');

            $table->string('url');

            $table->string('name')->nullable();

            $table->decimal('first_price', 10, 2)->default(0.00);
            $table->decimal('first_sale', 10, 2)->default(0.00);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('sale', 10, 2)->default(0.00);
            $table->decimal('percent_off', 10, 2)->default(0.00);
            $table->decimal('total_off', 10, 2)->default(0.00);

            $table->integer('times_read')->default(0);

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
        Schema::dropIfExists('products');
    }
}
