<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_requisition_id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('item_id');
            $table->string('po_number', 12);
            $table->date('po_date');
            $table->string('spare_parts_type', 20);
            $table->string('source_type', 20);
            $table->string('requisition_code', 12);
            $table->double('last_price', 16, 2);
            $table->unsignedInteger('pr_qty')->default(0);
            $table->unsignedInteger('po_qty')->default(0);
            $table->double('po_price', 16, 2);
            $table->double('po_value', 16, 2);
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
        Schema::dropIfExists('purchase_orders');
    }
}
