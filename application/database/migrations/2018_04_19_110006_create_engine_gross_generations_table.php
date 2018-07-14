<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngineGrossGenerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engine_gross_generations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('engine_id');
            $table->string('op_code', 10);
            $table->date('op_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('diff_time');
            $table->double('start_op_mwh', 16, 8)->default(0);
            $table->double('end_op_mwh', 16, 8)->default(0);
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('plant_id')
                  ->references('id')
                  ->on('plants')
                  ->onDelete('cascade');

            $table->foreign('engine_id')
                  ->references('id')
                  ->on('engines')
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
        Schema::dropIfExists('engine_gross_generations');
    }
}
