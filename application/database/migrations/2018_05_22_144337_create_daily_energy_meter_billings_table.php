<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyEnergyMeterBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_energy_meter_billings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_plant_generation_id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('meter_id');
            $table->date('operation_date');
            $table->double('export_last_day_kwh', 16, 8);
            $table->double('export_to_day_kwh', 16, 8);
            $table->double('import_last_day_kwh', 16, 8);
            $table->double('import_to_day_kwh', 16, 8);
            $table->double('export_last_day_kvarh', 16, 8);
            $table->double('export_to_day_kvarh', 16, 8);
            $table->double('import_last_day_kvarh', 16, 8);
            $table->double('import_to_day_kvarh', 16, 8);
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

            $table->foreign('meter_id')
                  ->references('id')
                  ->on('meters')
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
        Schema::dropIfExists('daily_energy_meter_billings');
    }
}
