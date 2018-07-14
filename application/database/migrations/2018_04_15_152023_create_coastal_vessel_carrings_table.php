<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoastalVesselCarringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coastal_vessel_carrings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coastal_vessel_id');
            $table->unsignedInteger('tank_id');
            $table->unsignedInteger('plant_id')->default(0);
            $table->string('code', 10)->unique();
            $table->date('carring_date');
            $table->date('loading_date');
            $table->double('invoice_quantity', 16, 8);
            $table->date('received_date');
            $table->double('received_quantity', 16, 8)->default(0);
            $table->double('waiting_quantity', 16, 8)->default(0);
            $table->float('transport_loss')->defaullt(0);
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('coastal_vessel_id')
                  ->references('id')
                  ->on('coastal_vessels')
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
        Schema::dropIfExists('coastal_vessel_carrings');
    }
}
