<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyEngineGrossGenerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_engine_gross_generations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_plant_generation_id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('engine_id');
            $table->date('operation_date');
            $table->double('last_day_gross_generation', 16, 8);
            $table->double('to_day_gross_generation', 16, 8);
            $table->double('fuel_consumption', 16, 8);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('daily_plant_generation_id')
                  ->references('id')
                  ->on('daily_plant_generations')
                  ->onDelete('cascade');

            $table->foreign('plant_id')
                  ->references('id')
                  ->on('plants')
                  ->onDelete('cascade');

            $table->foreign('engine_id')
                  ->references('id')
                  ->on('engines')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_engine_gross_generations');
    }
}
