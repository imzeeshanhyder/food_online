<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_items', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('menu_id');
			$table->string('name');
			$table->string('price');
			$table->string('image')->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('quantity');
			$table->boolean('quantity_per_day')->default(0);
            $table->tinyInteger('status')->default(1);
			$table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_items');
    }
}
