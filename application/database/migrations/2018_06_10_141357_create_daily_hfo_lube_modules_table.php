<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyHfoLubeModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_hfo_lube_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_plant_generation_id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('engine_id');
            $table->date('operation_date');
            $table->double('hfo')->default(0);
            $table->double('lube_oil')->default(0);
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
        Schema::dropIfExists('daily_hfo_lube_modules');
    }
}
