<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_trades', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('party_id');
            $table->unsignedInteger('terminal_id');
            $table->date('transaction_date');
            $table->double('loan_given_qty', 16, 2)->default(0);
            $table->double('loan_receive_qty', 16, 2)->default(0);
            $table->double('loan_return_qty', 16, 2)->default(0);
            $table->double('loan_paid_by_party_qty', 16, 2)->default(0);
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('party_id')
                  ->references('id')
                  ->on('parties')
                  ->onDelete('cascade');

            $table->foreign('terminal_id')
                  ->references('id')
                  ->on('terminals')
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
        Schema::dropIfExists('fuel_trades');
    }
}
