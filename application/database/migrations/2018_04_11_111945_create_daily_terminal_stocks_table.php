<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyTerminalStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_terminal_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('terminal_id');
            $table->unsignedInteger('tank_id');
            $table->string('tank_number', 10);
            $table->date('transaction_date');
            $table->double('tank_stock', 16, 2)->default(0);
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('terminal_id')
                  ->references('id')
                  ->on('terminals')
                  ->onDelete('cascade');

            $table->foreign('tank_id')
                  ->references('id')
                  ->on('tanks')
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
        Schema::dropIfExists('daily_terminal_stocks');
    }
}
