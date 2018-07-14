<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyPlantGenerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_plant_generations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plant_id');
            $table->date('operation_date');
            $table->float('plant_load_factor');
            $table->double('plant_fuel_consumption', 16, 8);
            $table->double('total_hfo_stock', 16, 8);
            $table->double('reference_lhv', 16, 8);
            $table->double('aux_boiler_hfo_consumption', 16, 8);
            $table->text('comments')->nullable();
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('plant_id')
                  ->references('id')
                  ->on('plants')
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
        Schema::dropIfExists('daily_plant_generations');
    }
}
