<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('logout', 'Auth\LoginController@logout');

Route::middleware('auth')->group(function () {
	
	Route::get('/home', 'HomeController@index')->name('home');

	/**
	/----------------------------------------------------
	/ Routes of Setup Module
	/----------------------------------------------------
	*/

	// User Routes...
	Route::any('profile', 'UserController@profile');
	Route::any('users/list', 'UserController@getList');
	Route::post('users/delete', 'UserController@delete');
	Route::resource('users', 'UserController');

	// Permission Routes...
	Route::post('permissions/get-pages/{module_id}', 'PermissionController@getPages');
	Route::any('permissions/list', 'PermissionController@getList');
	Route::post('permissions/delete', 'PermissionController@delete');
	Route::resource('permissions', 'PermissionController');

	// Plant Routes...
	Route::any('plants/list', 'PlantController@getList');
	Route::post('plants/delete', 'PlantController@delete');
	Route::resource('plants', 'PlantController');

	// Terminal Routes...
	Route::any('terminals/list', 'TerminalController@getList');
	Route::post('terminals/delete', 'TerminalController@delete');
	Route::resource('terminals', 'TerminalController');
	
    // Fuel Type Routes...
	Route::any('fuel-types/list', 'FuelTypeController@getList');
	Route::post('fuel-types/delete', 'FuelTypeController@delete');
	Route::resource('fuel-types', 'FuelTypeController');

	// Tank Routes...
	Route::any('tanks/list', 'TankController@getList');
	Route::post('tanks/delete', 'TankController@delete');
	Route::resource('tanks', 'TankController');

	// Party Routes...
	Route::any('parties/list', 'PartyController@getList');
	Route::post('parties/delete', 'PartyController@delete');
	Route::resource('parties', 'PartyController');

	/**
	/----------------------------------------------------
	/ Routes of Fuel Management Module
	/----------------------------------------------------
	*/

	// Mother Vessel Routes...
	Route::any('mother-vessels/list', 'MotherVesselController@getList');
	Route::post('mother-vessels/delete', 'MotherVesselController@delete');
	Route::resource('mother-vessels', 'MotherVesselController');

	// Mother Vessel Carring Routes...
	Route::any('mother-vessel-carrings/list', 'MotherVesselCarringController@getList');
	Route::post('mother-vessel-carrings/delete', 'MotherVesselCarringController@delete');
	Route::resource('mother-vessel-carrings', 'MotherVesselCarringController');

	// Coastal Vessel Routes...
	Route::any('coastal-vessels/list', 'CoastalVesselController@getList');
	Route::post('coastal-vessels/delete', 'CoastalVesselController@delete');
	Route::resource('coastal-vessels', 'CoastalVesselController');

	// Coastal Vessel Carring Routes...
	Route::any('coastal-vessel-carrings/list', 'CoastalVesselCarringController@getList');
	Route::post('coastal-vessel-carrings/delete', 'CoastalVesselCarringController@delete');
	Route::resource('coastal-vessel-carrings', 'CoastalVesselCarringController');

	// Coastal Vessel Receiving Routes...
	Route::any('coastal-vessel-receivings/list/{id}', 'CoastalVesselReceivingController@getList');
	Route::get('coastal-vessel-receivings/create/{id}', 'CoastalVesselReceivingController@create');
	Route::post('coastal-vessel-receivings/delete', 'CoastalVesselReceivingController@delete');
	Route::resource('coastal-vessel-receivings', 'CoastalVesselReceivingController')->except([
		'index', 'create', 'destroy'
	]);

	// Mother Vessel Carring Routes...
	Route::any('fuel-trades/list', 'FuelTradeController@getList');
	Route::post('fuel-trades/delete', 'FuelTradeController@delete');
	Route::resource('fuel-trades', 'FuelTradeController');

	// Daily Terminal Stock Routes...
	Route::post('daily-terminal-stocks/get-tanks', 'DailyTerminalStockController@getTanks');
	Route::any('daily-terminal-stocks/list', 'DailyTerminalStockController@getList');
	Route::post('daily-terminal-stocks/delete', 'DailyTerminalStockController@delete');
	Route::resource('daily-terminal-stocks', 'DailyTerminalStockController');

	// Fuel Inventory Routes...
	Route::post('fuel-inventories/fetch-fuel-type-unit/{fuel_type_id}', 'FuelInventoryController@getFuelTypeUnit');
	Route::post('fuel-inventories/fetch-opening-stock/{transaction_date}', 'FuelInventoryController@getOpeningStock');
	Route::any('fuel-inventories/list', 'FuelInventoryController@getList');
	Route::post('fuel-inventories/delete', 'FuelInventoryController@delete');
	Route::resource('fuel-inventories', 'FuelInventoryController');

	/**
	/----------------------------------------------------
	/ Routes of M & O Management Module
	/----------------------------------------------------
	*/

	// Engine Routes...
	Route::any('engines/list', 'EngineController@getList');
	Route::post('engines/delete', 'EngineController@delete');
	Route::resource('engines', 'EngineController');

	// Meter Routes...
	Route::any('meters/list', 'MeterController@getList');
	Route::post('meters/delete', 'MeterController@delete');
	Route::resource('meters', 'MeterController');

	// Engine Gross Generation Routes...
	Route::post('engine-gross-generations/get-engines/{plant_id}', 'EngineGrossGenerationController@getEngines');
	Route::any('engine-gross-generations/list', 'EngineGrossGenerationController@getList');
	Route::post('engine-gross-generations/delete', 'EngineGrossGenerationController@delete');
	Route::resource('engine-gross-generations', 'EngineGrossGenerationController');

	// Energy Gross Generation Routes...
	Route::post('energy-gross-generations/get-energies/{plant_id}', 'EnergyGrossGenerationController@getMeters');
	Route::any('energy-gross-generations/list', 'EnergyGrossGenerationController@getList');
	Route::post('energy-gross-generations/delete', 'EnergyGrossGenerationController@delete');
	Route::resource('energy-gross-generations', 'EnergyGrossGenerationController');

	// Plant Equipment Routes...
	Route::get('plant-equipments/add-dummy-data', 'PlantEquipmentController@addDummyData');
	Route::get('plant-equipments/add-dummy-equipments', 'PlantEquipmentController@addDummyEquipments');
	Route::any('plant-equipments/list', 'PlantEquipmentController@getList');
	Route::post('plant-equipments/delete', 'PlantEquipmentController@delete');
	Route::resource('plant-equipments', 'PlantEquipmentController');

	// Equipment Running Hour Routes...
	Route::get('equipment-running-hours/add-dummy-data', 'EquipmentRunningHourController@addDummyData');
	Route::post('equipment-running-hours/get-plant-equipments', 'EquipmentRunningHourController@getPlantEquipments');
	Route::any('equipment-running-hours/list', 'EquipmentRunningHourController@getList');
	Route::post('equipment-running-hours/delete', 'EquipmentRunningHourController@delete');
	Route::resource('equipment-running-hours', 'EquipmentRunningHourController');

	// Engine Generation Routes...
	Route::post('engine-generations/get-engines/{plant_id}', 'EngineGenerationController@getEngines');
	Route::any('engine-generations/list', 'EngineGenerationController@getList');
	Route::post('engine-generations/delete', 'EngineGenerationController@delete');
	Route::resource('engine-generations', 'EngineGenerationController');

	// Energy Gross Generation Routes...
	Route::post('energy-meter-generations/get-energy-meters/{plant_id}', 'EnergyMeterGenerationController@getEnergyMeters');
	Route::any('energy-meter-generations/list', 'EnergyMeterGenerationController@getList');
	Route::post('energy-meter-generations/delete', 'EnergyMeterGenerationController@delete');
	Route::resource('energy-meter-generations', 'EnergyMeterGenerationController');

	// Daily Plant Generation Routes...
	Route::any('daily-plant-generations/fetch-associate-info', 'DailyPlantGenerationController@getAssociateInfo');
	Route::any('daily-plant-generations/list', 'DailyPlantGenerationController@getList');
	Route::post('daily-plant-generations/delete', 'DailyPlantGenerationController@delete');
	Route::resource('daily-plant-generations', 'DailyPlantGenerationController');

	/**
	/----------------------------------------------------
	/ Routes of Spare Parts Inventory Module
	/----------------------------------------------------
	*/

	// Routes of Item Groups...
	Route::any('item-groups/list', 'ItemGroupController@getList');
	Route::post('item-groups/delete', 'ItemGroupController@delete');
	Route::resource('item-groups', 'ItemGroupController');

	// Routes of Items...
	Route::any('items/list', 'ItemController@getList');
	Route::post('items/delete', 'ItemController@delete');
	Route::post('items/fetch-item/{id}', 'ItemController@fetchItem');
	Route::post('items/fetch-items/{plant_id}', 'ItemController@fetchItems');
	Route::resource('items', 'ItemController');

	// Routes of Item Ledgers...
	Route::post('item-ledgers/fetch-item/{id}', 'ItemLedgerController@fetchItem');
	Route::any('item-ledgers/list', 'ItemLedgerController@getList');
	Route::post('item-ledgers/change-approve-status', 'ItemLedgerController@changeApproveStatus');
	Route::post('item-ledgers/delete', 'ItemLedgerController@delete');
	Route::resource('item-ledgers', 'ItemLedgerController');

	// Routes of Issue Register...
	Route::post('issue-registers/fetch-item/{id}', 'IssueRegisterController@fetchItem');
	Route::any('issue-registers/list-old', 'IssueRegisterController@getListOld');
	Route::any('issue-registers/list', 'IssueRegisterController@getList');
	Route::any('issue-registers/change-approve-status/{issue_code}', 'IssueRegisterController@changeApproveStatus');
	Route::post('issue-registers/delete', 'IssueRegisterController@delete');
	Route::resource('issue-registers', 'IssueRegisterController');

	// Routes of Purchase Requisition...
	Route::post('purchase-requisitions/fetch-pr-items/{requisition_code}', 'PurchaseRequisitionController@fetchPrItems');
	Route::any('purchase-requisitions/list-old', 'PurchaseRequisitionController@getListOld');
	Route::get('purchase-requisitions/edit-view/{requisition_code}', 'PurchaseRequisitionController@editView');
	Route::any('purchase-requisitions/list', 'PurchaseRequisitionController@getList');
	Route::any('purchase-requisitions/change-approve-status/{requisition_code}', 'PurchaseRequisitionController@changeApproveStatus');
	Route::post('purchase-requisitions/delete', 'PurchaseRequisitionController@delete');
	Route::resource('purchase-requisitions', 'PurchaseRequisitionController');

	// Routes of Purchase Order...
	Route::post('purchase-orders/fetch-purchase-requisitions/{plant_id}', 'PurchaseOrderController@getPurchaseRequisitions');
	Route::post('purchase-orders/fetch-item/{id}', 'PurchaseOrderController@fetchItem');
	Route::any('purchase-orders/list-old', 'PurchaseOrderController@getListOld');
	Route::any('purchase-orders/list', 'PurchaseOrderController@getList');
	Route::post('purchase-orders/change-approve-status', 'PurchaseOrderController@changeApproveStatus');
	Route::post('purchase-orders/delete', 'PurchaseOrderController@delete');
	Route::resource('purchase-orders', 'PurchaseOrderController');

	// Routes of Stock Receive Register...
	Route::post('stock-receive-registers/get-purchase-orders/{id}', 'StockReceiveRegisterController@getPurchaseOrders');
	Route::any('stock-receive-registers/list-old', 'StockReceiveRegisterController@getListOld');
	Route::any('stock-receive-registers/list', 'StockReceiveRegisterController@getList');
	Route::post('stock-receive-registers/change-approve-status', 'StockReceiveRegisterController@changeApproveStatus');
	Route::post('stock-receive-registers/delete', 'StockReceiveRegisterController@delete');
	Route::resource('stock-receive-registers', 'StockReceiveRegisterController');

	// Route of Item Stock...
	Route::any('item-stocks/{action?}', 'ItemStockController');

	// Routes of Reports
	Route::any('reports/daily-operations/{action?}', 'ReportController@getDailyOperationInfo');
	Route::any('reports/monthly-fuel-inventory/{action?}', 'ReportController@getMonthlyFuelInventoryInfo');
	Route::any('reports/daily-plant-generation/{action?}', 'ReportController@getDailyPlantGenerationInfo');
	Route::any('reports/daily-plant-summary/{action?}', 'ReportController@getDailyPlantSummary');
	Route::any('reports/plant-wise-operation/{action?}', 'ReportController@getPlantWiseOperation');
	Route::any('reports/overall-operation/{action?}', 'ReportController@getOverallOperation');
	Route::any('reports/plant-wise-outage/{action?}', 'ReportController@getPlantWiseOutage');
	Route::any('reports/overall-outage/{action?}', 'ReportController@getOverallOutage');
	Route::any('reports/plant-wise-equipment-running-hour/{action?}', 'ReportController@getPlantWiseEquipmentRunningHour');
	Route::any('reports/overall-equipment-running-hour/{action?}', 'ReportController@getOverallEquipmentRunningHour');

	Route::any('reports/purchase/{action?}', 'ReportController@getPurchaseOrderInfo');
	Route::any('reports/consumption/{action?}', 'ReportController@getConsumptionInfo');
	Route::any('reports/pending-purchase-requisition/{action?}', 'ReportController@getPendingPurchaseRequistionInfo');
});

Route::get('test', function() {
	dd(mt_rand(0,500));
	$plant = \App\Plant::find(2);

	$meter_number = $plant->meters()->count();

	$start_date = '2018-01-01';
	$to_date = '2018-05-30';
	dd($plant->getTurbineEngineActivityHours('engine-running', $start_date, $to_date));
	// dd(Carbon::parse($to_date)->startOfMonth()->format('Y-m-d'));
    $MonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($start_date, $to_date);

    dd($MonthlyEnergyMeterBillingInfo);
});




