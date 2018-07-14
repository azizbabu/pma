<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('fuel_type_id');
            $table->string('transaction_code', 10)->unique();
            $table->date('transaction_date', 10);
            $table->double('invoice_quantity', 16, 4)->default(0);
            $table->double('received_quantity', 16, 4)->default(0);
            $table->double('transportation_loss', 8, 4)->default(0);
            $table->double('available_stock', 16, 4)->default(0);
            $table->double('consumption', 16, 4)->default(0);
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('plant_id')
                  ->references('id')
                  ->on('plants')
                  ->onDelete('cascade');

            $table->foreign('fuel_type_id')
                  ->references('id')
                  ->on('fuel_types')
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
        Schema::dropIfExists('fuel_inventories');
    }
}
