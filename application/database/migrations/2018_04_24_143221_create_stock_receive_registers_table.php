<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockReceiveRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_receive_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('item_id');
            $table->string('receive_code', 12);
            $table->date('receive_date');
            $table->string('po_number', 12);
            $table->string('requisition_code', 12);
            $table->unsignedInteger('po_qty');
            $table->unsignedInteger('grn_qty');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('approved_by')->default(0);
            $table->date('approved_date')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('plant_id')
                  ->references('id')
                  ->on('plants')
                  ->onDelete('cascade');

            $table->foreign('item_id')
                  ->references('id')
                  ->on('items')
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
        Schema::dropIfExists('stock_receive_registers');
    }
}
