<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('item_id');
            $table->string('requisition_code', 12);
            $table->date('requisition_date');
            $table->string('spare_parts_type', 20);
            $table->string('source_type', 20);
            $table->double('item_avg_price', 16, 2);
            $table->unsignedInteger('item_safety_stock_qty')->default(0);
            $table->unsignedInteger('present_stock_qty')->default(0);
            $table->unsignedInteger('pipeline_qty')->default(0);
            $table->unsignedInteger('required_qty')->default(0);
            $table->unsignedInteger('approved_qty');
            $table->unsignedInteger('remaining_qty')->default(0);
            $table->double('total_value', 16 ,2);
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
        Schema::dropIfExists('purchase_requisitions');
    }
}
