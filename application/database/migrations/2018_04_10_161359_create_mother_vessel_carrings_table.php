<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotherVesselCarringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mother_vessel_carrings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mother_vessel_id');
            $table->string('code', 10)->unique();
            $table->date('carring_date');
            $table->string('lc_number', 40)->unique();
            $table->date('loading_date');
            $table->double('invoice_quantity', 16, 8);
            $table->date('received_date');
            $table->double('received_quantity', 16, 8);
            $table->float('transport_loss');
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('mother_vessel_id')
                  ->references('id')
                  ->on('mother_vessels')
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
        Schema::dropIfExists('mother_vessel_carrings');
    }
}
