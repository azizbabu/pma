<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyEngineActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_engine_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_plant_generation_id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('engine_id');
            $table->date('operation_date');
            $table->string('activity_state', 50);
            $table->datetime('start_time');
            $table->datetime('stop_time');
            $table->time('diff_time');
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
        Schema::dropIfExists('daily_engine_activities');
    }
}
