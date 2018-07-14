<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('module_id');
            $table->string('name');
            $table->string('link')->nullable();
        });

        $data = [
            ['module_id' => 1, 'name' => 'User Entry', 'link' => 'users/list'],
            ['module_id' => 1, 'name' => 'User Permission', 'link' => 'permissions/list'],
            ['module_id' => 1, 'name' => 'Plant Information', 'link' => 'plants/list'],
            ['module_id' => 1, 'name' => 'Terminal Information', 'link' => 'terminals/list'],
            ['module_id' => 1, 'name' => 'Fuel Type Information', 'link' => 'fuel-types/list'],
            ['module_id' => 1, 'name' => 'Tank Information', 'link' => 'tanks/list'],
            ['module_id' => 1, 'name' => 'Party Information', 'link' => 'parties/list'],
            ['module_id' => 2, 'name' => 'Mother Vessel Information', 'link' => 'mother-vessels/list'],

            ['module_id' => 2, 'name' => 'Mother Vessel Carring', 'link' => 'mother-vessel-carrings/list'],
            ['module_id' => 2, 'name' => 'Daily Terminal Stock', 'link' => 'daily-terminal-stocks/list'],
            ['module_id' => 2, 'name' => 'Fuel Trade', 'link' => 'fuel-trades/list'],
            ['module_id' => 2, 'name' => 'Coastal Vessel Information', 'link' => 'lighter-vessels/list'],
            ['module_id' => 2, 'name' => 'Coastal Vessel Carring', 'link' => 'coastal-vessel-carrings/list'],
            ['module_id' => 2, 'name' => 'Fuel Inventory', 'link' => 'fuel-inventories/list'],

            ['module_id' => 3, 'name' => 'Engine Information', 'link' => 'engines/list'],
            ['module_id' => 3, 'name' => 'Meter Information', 'link' => 'meters/list'],
            ['module_id' => 3, 'name' => 'Daily Plant Generation', 'link' => 'daily-plant-generations/list'],
            ['module_id' => 3, 'name' => 'Plant Equipment', 'link' => 'plant-equipments/list'],
            ['module_id' => 3, 'name' => 'Equipment Running Hour', 'link' => 'equipment-running-hours/list'],

            ['module_id' => 4, 'name' => 'Item Types', 'link' => 'item-groups/list'],
            ['module_id' => 4, 'name' => 'Item Information', 'link' => 'items/list'],
            ['module_id' => 4, 'name' => 'Purchase Requisition', 'link' => 'purchase-requisitions/list'],
            ['module_id' => 4, 'name' => 'Purchase Order', 'link' => 'purchase-orders/list'],
            ['module_id' => 4, 'name' => 'Stock Receive Register', 'link' => 'stock-receive-registers/list'],
            ['module_id' => 4, 'name' => 'Issue Register', 'link' => 'issue-registers/list'],
            ['module_id' => 4, 'name' => 'Item Stock', 'link' => 'item-stocks'],
            ['module_id' => 4, 'name' => 'Item Ledger', 'link' => 'item-ledgers/list'],

            ['module_id' => 5, 'name' => 'Daily Operation', 'link' => 'reports/daily-plant-generation'],
            ['module_id' => 5, 'name' => 'Monthly Fuel Inventory', 'link' => 'reports/monthly-fuel-inventory'],
            ['module_id' => 5, 'name' => 'Purchase', 'link' => 'reports/purchase'],
            ['module_id' => 5, 'name' => 'Consumption', 'link' => 'reports/consumption'],
            ['module_id' => 5, 'name' => 'Pending Purchase Requisition', 'link' => 'reports/pending-purchase-requisition'],
            
        ];

        \App\Page::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
