<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $data = [
            ['name' => 'Setup'],
            ['name' => 'Fuel Management'],
            ['name' => 'O & M Management'],
            ['name' => 'Spare Parts Inventory'],
            ['name' => 'Report'],
        ];

        \App\Module::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
