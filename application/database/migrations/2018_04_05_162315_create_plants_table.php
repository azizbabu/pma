<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->text('address')->nullable();
            $table->double('capacity', 16, 4);
            $table->double('stock', 16, 4)->default(0);
            
            $table->unsignedInteger('no_of_generating_unit');
            $table->date('code_date');
            $table->double('tank_dead_stock', 16, 4);
            $table->unsignedInteger('energy_meter_multification_factor');
            $table->unsignedInteger('hfo_storage_tank_number');
            $table->unsignedInteger('hfo_buffer_tank_number');
            $table->unsignedInteger('hfo_service_tank_number');
            $table->unsignedInteger('diesel_tank_number');
            $table->unsignedInteger('lube_oil_storage_tank_number');
            $table->unsignedInteger('lube_oil_maintenance_tank_number');
            $table->unsignedInteger('permissible_outage');
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
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
        Schema::dropIfExists('plants');
    }
}
