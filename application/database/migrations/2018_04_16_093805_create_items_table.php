<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_group_id');
            $table->unsignedInteger('plant_id');
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('pr_number', 10)->unique();
            $table->string('source_type', 20);
            $table->string('stock_type', 20);
            $table->unsignedInteger('opening_qty')->default(0);
            $table->double('avg_price', 16, 2);
            $table->unsignedInteger('safety_stock_qty')->default(0);
            $table->unsignedInteger('receive_qty')->default(0);
            $table->unsignedInteger('return_qty')->default(0);
            $table->unsignedInteger('issue_qty')->default(0);
            $table->unsignedInteger('pr_qty')->default(0);
            $table->unsignedInteger('pipeline_qty')->default(0);
            $table->unsignedInteger('requisition_approval_qty')->default(0);
            $table->unsignedInteger('po_qty')->default(0);
            $table->unsignedInteger('issue_approval_qty')->default(0);
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('item_group_id')
                  ->references('id')
                  ->on('item_groups')
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
        Schema::dropIfExists('items');
    }
}
