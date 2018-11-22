<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProductWatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_product_watches', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->integer('product_id');

            $table->decimal('discount', 10, 2)
                ->default(0.00);

            $table->boolean('status')
                ->default(1);

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
        Schema::dropIfExists('user_product_watches');
    }
}
