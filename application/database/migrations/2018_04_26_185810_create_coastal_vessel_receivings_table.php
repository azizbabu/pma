<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoastalVesselReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coastal_vessel_receivings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coastal_vessel_carring_id');
            $table->unsignedInteger('plant_id')->default(0);
            $table->string('cvr_number', 12)->unique();
            $table->date('cvr_date');
            $table->double('cvr_qty', 16, 8);
            $table->double('load_qty', 16, 8);
            $table->double('loss_qty', 16, 8)->default(0);
            $table->float('loss');
            $table->string('lighter_vessel_name')->nullable();
            $table->date('plant_receive_date')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('coastal_vessel_carring_id')
                  ->references('id')
                  ->on('coastal_vessel_carrings')
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
        Schema::dropIfExists('coastal_vessel_receivings');
    }
}
