<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnergyGrossGenerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_gross_generations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('meter_id');
            $table->string('op_code', 10);
            $table->date('op_date');
            $table->double('export_start_kwh', 16, 8);
            $table->double('export_end_kwh', 16, 8);
            $table->double('import_start_kwh', 16, 8);
            $table->double('import_end_kwh', 16, 8);
            $table->double('export_start_kvarh', 16, 8);
            $table->double('export_end_kvarh', 16, 8);
            $table->double('import_start_kvarh', 16, 8);
            $table->double('import_end_kvarh', 16, 8);
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

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
        Schema::dropIfExists('energy_gross_generations');
    }
}
