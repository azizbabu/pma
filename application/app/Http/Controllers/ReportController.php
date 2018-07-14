<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoastalVesselCarring;
use App\DailyEngineActivity;
use App\DailyEnergyMeterBilling;
use App\DailyEngineGrossGeneration;
use App\DailyHfoLubeModule;
use App\DailyPlantGeneration;
use App\Equipment;
use App\Engine;
use App\EngineGrossGeneration;
use App\EnergyGrossGeneration;
use App\FuelInventory;
use App\FuelTrade;
use App\ItemGroup;
use App\MotherVesselCarring;
use App\Plant;
use App\PlantEquipment;
use App\PurchaseOrder;
use App\Terminal;
use Carbon, DB;

class ReportController extends Controller
{
    /**
     * Show info of daily operation
     *
     * @param  \Illuminate\Http\Request $request
     * @param $action
     * @return \Illuminate\Http\Response
     */
    public function getDailyOperationInfo(Request $request, $action = null)
    {
    	$engineGrossGenerationsGensetArr = [];
        $energyGrossGenerationsArr = [];
        $engineGrossGenerationsRunningArr = [];

    	if(array_filter(array_except($request->all(), ['_token']))) {

    		$engineGrossGenerationsGenset = EngineGrossGeneration::select(['id', 'plant_id', 'engine_id', 'op_date', 'start_time', 'end_time', 'diff_time', 'start_op_mwh', 'end_op_mwh'])
    			->wherePlantId(trim($request->plant_id))
    			->whereOpDate(trim($request->op_date))
    			->where('end_op_mwh', '>', 0)
    			->groupBy('engine_id')->get();

    		$energyGrossGenerations = EnergyGrossGeneration::select(['id', 'plant_id', 'meter_id', 'op_date', 'export_start_kwh', 'export_end_kwh', 'import_start_kwh', 'import_end_kwh', 'export_start_kvarh', 'export_end_kvarh', 'import_start_kvarh', 'import_end_kvarh'])
    			->wherePlantId(trim($request->plant_id))
    			->whereOpDate(trim($request->op_date))
    			->get();

    		if($engineGrossGenerationsGenset->isEmpty() && $energyGrossGenerations->isEmpty()) {
    			
    			session()->flash('toast', toastMessage('No data found!', 'error'));

    			return back();
    		}

    		if($engineGrossGenerationsGenset->isNotEmpty()) {
    			foreach($engineGrossGenerationsGenset as $engineGrossGeneration) {
    				$item_info = [];

    				$item_info['engine_id']	= $engineGrossGeneration->engine->id;
    				$item_info['name']	= $engineGrossGeneration->engine->name;
    				$item_info['start_op_mwh']	= number_format($engineGrossGeneration->start_op_mwh, 2);
    				$item_info['end_op_mwh']	= number_format($engineGrossGeneration->end_op_mwh, 2);
    				$item_info['diff']	= number_format(($engineGrossGeneration->end_op_mwh - $engineGrossGeneration->start_op_mwh), 2);

    				$engineGrossGenerationsGensetArr[] = $item_info;
    			}

    			$total_engine = count($engineGrossGenerationsGensetArr);
    		}

    		$engineGrossGenerationsRunning = EngineGrossGeneration::select(['id', 'plant_id', 'engine_id', 'op_date', 'start_time', 'end_time', 'diff_time', 'start_op_mwh', 'end_op_mwh'])
    			->wherePlantId(trim($request->plant_id))
    			->whereOpDate(trim($request->op_date))
    			->get();

    		// dd($engineGrossGenerationsRunning);

    		if($engineGrossGenerationsRunning->isNotEmpty()) {
    			foreach($engineGrossGenerationsRunning as $engineGrossGeneration) {
    				$item_info = [];

    				$engine_id = $engineGrossGeneration->engine->id;
    				$item_info['engine_id'] = $engine_id;
    				$item_info['start_time'] = $engineGrossGeneration->start_time;
    				$item_info['end_time']	= $engineGrossGeneration->end_time;
    				$item_info['diff_time']	= $engineGrossGeneration->diff_time;

    				$engineGrossGenerationsRunningArr[] = $item_info;
    			}

    			// dd($engineGrossGenerationsRunningArr);

    			$total_row = count($engineGrossGenerationsRunningArr) /$total_engine;
    		}

    		if($energyGrossGenerations->isNotEmpty()) {
    			foreach($energyGrossGenerations as $energyGrossGeneration) {
    				
    				$item_info = [];
    				$item_info['meter_name'] = $energyGrossGeneration->meter->name;
    				$item_info['export_start_kwh'] = $energyGrossGeneration->export_start_kwh;
    				$item_info['export_end_kwh'] = $energyGrossGeneration->export_end_kwh;
    				$item_info['export_diff_kwh'] = $energyGrossGeneration->export_end_kwh - $energyGrossGeneration->export_start_kwh;
    				$item_info['import_start_kwh'] = $energyGrossGeneration->import_start_kwh;
    				$item_info['import_end_kwh'] = $energyGrossGeneration->import_end_kwh;
    				$item_info['import_diff_kwh'] = $energyGrossGeneration->import_end_kwh - $energyGrossGeneration->import_start_kwh;

    				$item_info['export_start_kvarh'] = $energyGrossGeneration->export_start_kvarh;
    				$item_info['export_end_kvarh'] = $energyGrossGeneration->export_end_kvarh;
    				$item_info['export_diff_kvarh'] = $energyGrossGeneration->export_end_kvarh - $energyGrossGeneration->export_start_kvarh;
    				$item_info['import_start_kvarh'] = $energyGrossGeneration->import_start_kvarh;
    				$item_info['import_end_kvarh'] = $energyGrossGeneration->import_end_kvarh;
    				$item_info['import_diff_kvarh'] = $energyGrossGeneration->import_end_kvarh - $energyGrossGeneration->import_start_kvarh;

    				$energyGrossGenerationsArr[] = $item_info;
    			}

    			// dd($energyGrossGenerationsArr);

    			$totalEnergyGrossGenerations = count($energyGrossGenerationsArr);

    			if($totalEnergyGrossGenerations == 2) {
    				$totalExportKwh = ($energyGrossGenerationsArr[0]['export_diff_kwh'] + $energyGrossGenerationsArr[0]['export_diff_kwh']) * 300;
    				$totalExportKvarh = ($energyGrossGenerationsArr[0]['export_diff_kvarh'] + $energyGrossGenerationsArr[0]['export_diff_kvarh']) * 300;
    				$totalImportKwh = ($energyGrossGenerationsArr[0]['import_diff_kwh'] + $energyGrossGenerationsArr[0]['import_diff_kwh']) * 300;
    				$totalImportKvarh = ($energyGrossGenerationsArr[0]['import_diff_kvarh'] + $energyGrossGenerationsArr[0]['import_diff_kvarh']) * 300;
    			}
    		}
    	}

    	if($action == 'print') {
    		
            $plant = Plant::find($request->plant_id);
    		
            return view('prints.daily-operations', compact('plants', 'engineGrossGenerationsGensetArr', 'total_engine', 'engineGrossGenerationsRunning', 'engineGrossGenerationsRunningArr', 'total_row', 'energyGrossGenerationsArr', 'totalExportKwh', 'totalExportKvarh', 'totalImportKwh', 'totalImportKvarh', 'engineGrossGenerationsRunning', 'plant'));
    	}

    	$plants = Plant::getDropDownList();

    	return view('reports.daily-operations', compact('plants', 'engineGrossGenerationsGensetArr', 'total_engine', 'engineGrossGenerationsRunning', 'engineGrossGenerationsRunningArr', 'total_row', 'energyGrossGenerationsArr', 'totalExportKwh', 'totalExportKvarh', 'totalImportKwh', 'totalImportKvarh', 'engineGrossGenerationsRunning'));
    }

    /**
     * Show info of daily operation
     *
     * @param  \Illuminate\Http\Request $request
     * @param $action
     * @return \Illuminate\Http\Response
     */
    public function getMonthlyFuelInventoryInfo(Request $request, $action = null)
    {
        if(array_filter(array_except($request->all(), ['_token']))) {
            
            if($request->has('date_range')) {
                $date_range_arr  = explode(' - ',$request->date_range);
                $from_date = \Carbon::parse($date_range_arr[0])->format('Y-m-d');
                $to_date = \Carbon::parse($date_range_arr[1])->format('Y-m-d');
            }
            
            $motherVesselCarring = MotherVesselCarring::
                select( 
                    DB::raw('
                        IFNULL(SUM(invoice_quantity), 0) AS invoice_quantity, 
                        IFNULL(SUM(received_quantity),0) AS received_quantity
                    ')
                )
                ->whereRaw('received_date >="'.$from_date.'" AND received_date <="'.$to_date.'"')
                ->first();

            // $CoastalVesselCarringTotal = CoastalVesselCarring::select(
            //     DB::raw('
            //         SUM
            //     ')
            //     )->whereRaw('received_date >="'.$from_date.'" AND received_date <="'.$to_date.'"')
            //     ->first('invoice_quantity');
            $plant_site_current_invoice_quantity = CoastalVesselCarring::whereRaw('received_date >="'.$from_date.'" AND received_date <="'.$to_date.'"')->sum('invoice_quantity');
            $plant_site_total_received_qty = CoastalVesselCarring::whereRaw('received_date >="'.$from_date.'" AND received_date <="'.$to_date.'"')->sum('received_quantity');
            // dd($plant_site_current_invoice_quantity);

            if(!$motherVesselCarring->invoice_quantity || !$motherVesselCarring->received_quantity ) {
                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            $mother_vessel_carring_received_quantity = MotherVesselCarring::where('received_date',  '<',  $from_date)->sum('received_quantity');

            $plant_site_invoice_quantity = CoastalVesselCarring::where('received_date',  '<',  $from_date)->sum('invoice_quantity');

            $terminal = Terminal::select('id', 'name')->first();
            $terminal_name_filtered = str_after($terminal->name, '(');
            $terminal_short_name = str_before($terminal_name_filtered, ')');

            $terminal_opening_stock = $mother_vessel_carring_received_quantity - $plant_site_invoice_quantity;

            $terminal_closing_stock = $terminal_opening_stock + $motherVesselCarring->received_quantity - $plant_site_current_invoice_quantity;
            
            $fuelTrade = FuelTrade::select(
                DB::raw('
                    IFNULL(SUM(loan_given_qty), 0) AS loan_given_qty, 
                    IFNULL(SUM(loan_receive_qty), 0) AS loan_receive_qty,
                    IFNULL(SUM(loan_return_qty), 0) AS loan_return_qty,
                    IFNULL(SUM(loan_paid_by_party_qty), 0) AS loan_paid_by_party_qty
                ')
            )->whereTerminalId($terminal->id)->first();

            $plants = Plant::select('id', 'name')->get();

            $total_invoice_quantity = 0;
            $total_received_quantity = 0;
            $total_waiting_quantity = 0;
            $fuel_qty_arr = [];
            
            foreach($plants as $plant) {
                $fuel_qty = $plant->getFuelQuantity($from_date, $to_date);
                $total_invoice_quantity += $fuel_qty->invoice_quantity;
                $total_received_quantity += $fuel_qty->received_quantity;
                $total_waiting_quantity += $fuel_qty->waiting_quantity;

                $fuel_qty_arr['invoice_quantity'][$plant->id] = $fuel_qty->invoice_quantity;
                $fuel_qty_arr['received_quantity'][$plant->id] = $fuel_qty->received_quantity;
                $fuel_qty_arr['waiting_quantity'][$plant->id] = $fuel_qty->waiting_quantity;
            }

            // Fuel inventory report info
            $fuelTypes = DB::table('fuel_inventories AS fi')
                                 ->join('fuel_types AS ft', 'fi.fuel_type_id', '=', 'ft.id')
                                 ->selectRaw('DISTINCT ft.id, ft.name, ft.unit')
                                 ->whereRaw('fi.transaction_date >="'.$from_date.'" AND fi.transaction_date <="'.$to_date.'"')
                                 ->get();

            $inland_transportation_loss_qty = $total_received_quantity + $total_waiting_quantity - $total_invoice_quantity;

            $average_transportation_loss = 'N/A';
            if($total_invoice_quantity) {
                $average_transportation_loss = $inland_transportation_loss_qty/$total_invoice_quantity;
            }
            
            $substructed_value = ($motherVesselCarring->invoice_quantity + $total_invoice_quantity) - $total_waiting_quantity;

            $overall_transportation_loss = (($motherVesselCarring->received_quantity + $plant_site_total_received_qty) - $substructed_value)/$substructed_value;

            $fuelTradeLoanReceiveInfo = FuelTrade::where('loan_receive_qty', '>', 0)->latest('id')->first(['id', 'party_id', 'loan_receive_qty', 'transaction_date']);
            
            $fuelTradeLoanReturnAmount = FuelTrade::select(
                    DB::raw('IFNULL(SUM(loan_return_qty), 0) AS loan_return_qty')
                )
                ->wherePartyId($fuelTradeLoanReceiveInfo->party_id)
                ->where('transaction_date', '>=', $fuelTradeLoanReceiveInfo->transaction_date)
                ->first();

            $fuelTradeLoanReturnDate = FuelTrade::wherePartyId($fuelTradeLoanReceiveInfo->party_id)
                ->where('transaction_date', '>=', $fuelTradeLoanReceiveInfo->transaction_date)
                ->latest('transaction_date')
                ->first(['transaction_date']);

            $fuelTradeLoanReceiveInfo = FuelTrade::where('loan_given_qty', '>', 0)->latest('id')->first(['id', 'party_id', 'loan_given_qty', 'transaction_date']);

            // $remarks_loan_receive_info = 'We Received HFO as Loan from '. ;

        }

        if($action == 'print') {

            return view('prints.monthly-fuel-inventory',compact('motherVesselCarring', 'plants', 'from_date', 'to_date', 'terminal', 'terminal_short_name', 'fuelTrade', 'terminal_opening_stock', 'terminal_closing_stock', 'plant_site_info', 'plant_site_current_invoice_quantity', 'plant_site_total_received_qty', 'fuel_qty_arr', 'fuelTypes', 'average_transportation_loss', 'inland_transportation_loss_qty', 'total_waiting_quantity', 'overall_transportation_loss'));
        }

        return view('reports.monthly-fuel-inventory',compact('motherVesselCarring', 'plants', 'from_date', 'to_date', 'terminal', 'terminal_short_name', 'fuelTrade', 'terminal_opening_stock', 'terminal_closing_stock', 'plant_site_info', 'plant_site_current_invoice_quantity', 'plant_site_total_received_qty', 'fuel_qty_arr', 'fuelTypes', 'average_transportation_loss', 'inland_transportation_loss_qty', 'total_waiting_quantity', 'overall_transportation_loss'));
    }

    /**
     * Show info of daily operation
     *
     * @param  \Illuminate\Http\Request $request
     * @param $action
     * @return \Illuminate\Http\Response
     */
    public function getDailyPlantGenerationInfo(Request $request, $action = null)
    {
        $dailyEngineGrossGenerationArr = [];
        $dailyEnergyMeterBillingsArr = [];
        $dailyEngineActivitiesArrInitial = [];
        $dailyEngineActivitiesArr = [];
        $engine_state_count_arr = [];
        $sumGrossGeneration = 0;

        if(array_filter(array_except($request->all(), ['_token']))) {
            $dailyPlantGeneration = DailyPlantGeneration::wherePlantId(trim($request->plant_id))->whereOperationDate(trim($request->operation_date))->first(['id', 'plant_fuel_consumption', 'reference_lhv', 'total_hfo_stock', 'aux_boiler_hfo_consumption']);

            if(!$dailyPlantGeneration) {
                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            $plant = Plant::find($request->plant_id);

            $dailyEngineGrossGenerations = $dailyPlantGeneration->dailyEngineGrossGenerations;

            if($dailyEngineGrossGenerations->isNotEmpty()) {
                foreach($dailyEngineGrossGenerations as $dailyEngineGrossGeneration) {
                    $engine_id = $dailyEngineGrossGeneration->engine_id;

                    $dailyEngineGrossGenerationArr[$engine_id]['engine_name'] = $dailyEngineGrossGeneration->engine->name; 
                    $dailyEngineGrossGenerationArr[$engine_id]['last_day_gross_generation'] = $dailyEngineGrossGeneration->last_day_gross_generation;
                    $dailyEngineGrossGenerationArr[$engine_id]['to_day_gross_generation'] = $dailyEngineGrossGeneration->to_day_gross_generation; 
                    $dailyEngineGrossGenerationArr[$engine_id]['fuel_consumption'] = $dailyEngineGrossGeneration->fuel_consumption; 
                    $diff = round($dailyEngineGrossGeneration->to_day_gross_generation - $dailyEngineGrossGeneration->last_day_gross_generation,2);
                    $dailyEngineGrossGenerationArr[$engine_id]['diff'] = $diff;

                    $turbine_diff = 0;
                    if($engine_id == 4) {
                        $turbine_diff = $diff;
                    }

                    $sumGrossGeneration += $diff;
                }

                $sumGrossGeneration = round($sumGrossGeneration, 2);
            }

            $total_engine = count($dailyEngineGrossGenerationArr);

            $dailyEnergyMeterBillings = $dailyPlantGeneration->dailyEnergyMeterBillings;

            if($dailyEnergyMeterBillings->isNotEmpty()) {
                foreach($dailyEnergyMeterBillings as $dailyEnergyMeterBilling) {
                    $item_info = [];
                    $item_info['meter_name'] = $dailyEnergyMeterBilling->meter->name;
                    $item_info['export_last_day_kwh'] = $dailyEnergyMeterBilling->export_last_day_kwh;
                    $item_info['export_to_day_kwh'] = $dailyEnergyMeterBilling->export_to_day_kwh;
                    $item_info['export_diff_kwh'] = $dailyEnergyMeterBilling->export_to_day_kwh - $dailyEnergyMeterBilling->export_last_day_kwh;

                    $item_info['import_last_day_kwh'] = $dailyEnergyMeterBilling->import_last_day_kwh;
                    $item_info['import_to_day_kwh'] = $dailyEnergyMeterBilling->import_to_day_kwh;
                    $item_info['import_diff_kwh'] = $dailyEnergyMeterBilling->import_to_day_kwh - $dailyEnergyMeterBilling->import_last_day_kwh;

                    $item_info['export_last_day_kvarh'] = $dailyEnergyMeterBilling->export_last_day_kvarh;
                    $item_info['export_to_day_kvarh'] = $dailyEnergyMeterBilling->export_to_day_kvarh;
                    $item_info['export_diff_kvarh'] = $dailyEnergyMeterBilling->export_to_day_kvarh - $dailyEnergyMeterBilling->export_last_day_kvarh;


                    $item_info['import_last_day_kvarh'] = $dailyEnergyMeterBilling->import_last_day_kvarh;
                    $item_info['import_to_day_kvarh'] = $dailyEnergyMeterBilling->import_to_day_kvarh;
                    $item_info['import_diff_kvarh'] = $dailyEnergyMeterBilling->import_to_day_kvarh - $dailyEnergyMeterBilling->import_last_day_kvarh;

                    $dailyEnergyMeterBillingsArr[] = $item_info;
                }

                $totalEnergyGrossGenerations = count($dailyEnergyMeterBillingsArr);

                if($totalEnergyGrossGenerations == 2) {
                    $totalExportKwh = round((($dailyEnergyMeterBillingsArr[0]['export_diff_kwh'] + $dailyEnergyMeterBillingsArr[1]['export_diff_kwh']) * $plant->energy_meter_multification_factor), 6);
                    $totalExportKvarh = round((($dailyEnergyMeterBillingsArr[0]['export_diff_kvarh'] + $dailyEnergyMeterBillingsArr[1]['export_diff_kvarh']) * $plant->energy_meter_multification_factor), 6);
                    $totalImportKwh = round((($dailyEnergyMeterBillingsArr[0]['import_diff_kwh'] + $dailyEnergyMeterBillingsArr[1]['import_diff_kwh']) * $plant->energy_meter_multification_factor), 6);
                    $totalImportKvarh = round((($dailyEnergyMeterBillingsArr[0]['import_diff_kvarh'] + $dailyEnergyMeterBillingsArr[1]['import_diff_kvarh']) * $plant->energy_meter_multification_factor), 6);
                }
            }

            $dailyEngineActivities = $dailyPlantGeneration->dailyEngineActivities;

            if($dailyEngineActivities->isNotEmpty()) {
                foreach($dailyEngineActivities as $dailyEngineActivity) {
                    $dailyEngineActivitiesArrInitial[$dailyEngineActivity->activity_state][$dailyEngineActivity->engine_id]['start_time'][] = Carbon::parse($dailyEngineActivity->start_time)->format('d/m/y H:i');
                    $dailyEngineActivitiesArrInitial[$dailyEngineActivity->activity_state][$dailyEngineActivity->engine_id]['stop_time'][] = Carbon::parse($dailyEngineActivity->stop_time)->format('d/m/y H:i');
                    $dailyEngineActivitiesArrInitial[$dailyEngineActivity->activity_state][$dailyEngineActivity->engine_id]['diff_time'][] = $dailyEngineActivity->diff_time;
                }

                $dailyEngineActivitiesStates = $dailyPlantGeneration->dailyEngineActivities()->select(
                'engine_id',
                DB::raw('count(engine_id) AS engine_number')
                )->wherePlantId(trim($request->plant_id))
                ->whereOperationDate(trim($request->operation_date))
                ->groupBy('engine_id', 'activity_state')->get();

                foreach($dailyEngineActivitiesStates as $dailyEngineActivitiesState) {
                    $engine_state_count_arr[] = $dailyEngineActivitiesState->engine_number;
                }

                $total_row = max($engine_state_count_arr);

                $activity_state_arr = config('constants.engine_activity_state');
                
                for ($i=0; $i < $total_row; $i++) { 
                    foreach ($activity_state_arr as $activity_key => $activity_value) {
                        foreach($dailyEngineGrossGenerationArr as $engine_id => $value) {
                            $dailyEngineActivitiesArr[$activity_key][$engine_id]['start_time'][$i] = array_key_exists($activity_key, $dailyEngineActivitiesArrInitial) && array_key_exists($engine_id, $dailyEngineActivitiesArrInitial[$activity_key]) && array_key_exists($i, $dailyEngineActivitiesArrInitial[$activity_key][$engine_id]['start_time']) ? $dailyEngineActivitiesArrInitial[$activity_key][$engine_id]['start_time'][$i] : '';
                            $dailyEngineActivitiesArr[$activity_key][$engine_id]['stop_time'][$i] = array_key_exists($activity_key, $dailyEngineActivitiesArrInitial) && array_key_exists($engine_id, $dailyEngineActivitiesArrInitial[$activity_key]) && array_key_exists($i, $dailyEngineActivitiesArrInitial[$activity_key][$engine_id]['stop_time']) ? $dailyEngineActivitiesArrInitial[$activity_key][$engine_id]['stop_time'][$i] : '';
                            $dailyEngineActivitiesArr[$activity_key][$engine_id]['diff_time'][$i] = array_key_exists($activity_key, $dailyEngineActivitiesArrInitial) && array_key_exists($engine_id, $dailyEngineActivitiesArrInitial[$activity_key]) && array_key_exists($i, $dailyEngineActivitiesArrInitial[$activity_key][$engine_id]['diff_time']) ? $dailyEngineActivitiesArrInitial[$activity_key][$engine_id]['diff_time'][$i] : '0:00:00';
                        }
                    }

                    $dailyEngineActivitiesFilterArr = array_except($dailyEngineActivitiesArr, ['engine-running']);
                }
            }

            $start_date = Carbon::parse(trim($request->operation_date))->startOfMonth()->format('Y-m-d');

            $plf_mdt = $plant->getPLFMonthToDate($start_date, trim($request->operation_date));

            $dailyHfoLubeModules = DailyHfoLubeModule::wherePlantId(trim($request->plant_id))->whereOperationDate(trim($request->operation_date))->get();
            
            $fuel_consumption_flowmeter = 0;
            foreach($dailyHfoLubeModules as $dailyHfoLubeModule) {
                $fuel_consumption_flowmeter += $dailyHfoLubeModule->hfo;
            }

            if($action == 'print') {
                
                return view('prints.daily-plant-generation', compact('plants', 'dailyPlantGeneration', 'dailyEngineGrossGenerationArr', 'sumGrossGeneration', 'turbine_diff', 'total_engine', 'dailyEnergyMeterBillingsArr', 'totalExportKwh', 'totalExportKvarh', 'totalImportKwh', 'totalImportKvarh', 'total_row', 'dailyEngineActivitiesArr', 'dailyEngineActivitiesFilterArr', 'plant', 'dailyHfoLubeModules', 'fuel_consumption_flowmeter', 'plf_mdt'));
            }
        }
        
        $plants = Plant::getDropDownList();

        return view('reports.daily-plant-generation', compact('plants', 'dailyEngineGrossGenerationArr', 'dailyPlantGeneration', 'sumGrossGeneration', 'turbine_diff', 'total_engine', 'dailyEnergyMeterBillingsArr', 'totalExportKwh', 'totalExportKvarh', 'totalImportKwh', 'totalImportKvarh', 'total_row', 'dailyEngineActivitiesArr', 'dailyEngineActivitiesFilterArr', 'plant', 'dailyHfoLubeModules', 'fuel_consumption_flowmeter', 'plf_mdt'));
    }

    /**
     * Show info of purchase order
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $action
     * @return \Illuminate\Http\Response
     */
    public function getDailyPlantSummary(Request $request, $action = null)
    {
        $plant_info = [];
        $engine_info = [];
        $engine_arr = [];
        $total_engine_arr = [];

        if(array_filter(array_except($request->all(), ['_token']))) {
            $plant_objs = Plant::get(['id', 'name', 'energy_meter_multification_factor']);

            $start_date = Carbon::parse(trim($request->operation_date))->startOfMonth()->format('Y-m-d');
            $to_date = trim($request->operation_date);

            foreach($plant_objs as $plant_obj) {
                
                $engine_arr[$plant_obj->id] = $plant_obj->engines()->pluck('name', 'id');
                $total_engine_arr[$plant_obj->id] = count($engine_arr[$plant_obj->id]);
                $dailyPlantGeneration = $plant_obj->dailyPlantGenerations()->whereOperationDate(trim($request->operation_date))->first(); 
                $dailyEnergyMeterBillingInfo = $plant_obj->getDailyEnergyMeterBillingInfo(request()->operation_date);

                $plant_info['gauranteed_capacity'][$plant_obj->id] = $plant_obj->id == 3 ? 53.972*24 : 55*24; 
                $plant_info['total_dispatched'][$plant_obj->id] = $dailyEnergyMeterBillingInfo ? $dailyEnergyMeterBillingInfo['total_export_kwh'] : '';   
                $plant_info['plf'][$plant_obj->id] = $dailyEnergyMeterBillingInfo ? ($dailyEnergyMeterBillingInfo['total_export_kwh']/$plant_info['gauranteed_capacity'][$plant_obj->id])*100 : '';             
                $plant_info['fuel_consumption'][$plant_obj->id] = $dailyPlantGeneration ? $dailyPlantGeneration->plant_fuel_consumption : 0;
                $plant_info['reference_lhv'][$plant_obj->id] = $dailyPlantGeneration ? $dailyPlantGeneration->reference_lhv : 0;
                $plant_info['aux_boiler_hfo_consumption'][$plant_obj->id] = $dailyPlantGeneration ? $dailyPlantGeneration->aux_boiler_hfo_consumption : 0;
                $plant_info['pumpable_fuel_stock'][$plant_obj->id] = $dailyPlantGeneration ? $dailyPlantGeneration->total_hfo_stock - $plant_obj->tank_dead_stock : 0;
            }

            $engines = Engine::get(['id', 'plant_id', 'name']);
           
            foreach($engines as $engine) {
               $engine_info[$engine->plant_id][$engine->id]['gross_generation'] = $engine->getDailyGrossGenerationInfo(trim($request->operation_date))['gross_generation'];
               $engine_info[$engine->plant_id][$engine->id]['fuel_consumption'] = $engine->getDailyGrossGenerationInfo(trim($request->operation_date))['fuel_consumption'];
            }

            if(!$plant_info || !$engine_info) {
                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            if($action == 'print') {
                return view('prints.daily-plant-summary', compact('plant_objs', 'plant_info', 'engine_info', 'engine_arr', 'total_engine_arr', 'start_date', 'to_date'));
            }
        }

        return view('reports.daily-plant-summary', compact('plant_objs', 'plant_info', 'engine_info', 'engine_arr', 'total_engine_arr', 'start_date', 'to_date'));
    }

    /**
     * Show info of plant wise operation
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $action
     * @return \Illuminate\Http\Response
     */
    public function getPlantWiseOperation(Request $request, $action = null)
    {
        $plants = Plant::getDropDownList();
        $gross_generation = [];

        if(array_filter(array_except($request->all(), ['_token']))) {

            if($request->has('date_range')) {
                $date_range_arr  = explode(' - ',$request->date_range);
                $from_date = \Carbon::parse($date_range_arr[0])->format('Y-m-d');
                $to_date = \Carbon::parse($date_range_arr[1])->format('Y-m-d');

                $day_no = Carbon::parse($from_date)->diffInDays($to_date);

                $this_month_start_date = Carbon::parse($from_date)->startOfMonth()->format('Y-m-d');
                $this_month_end_date = Carbon::parse($from_date)->endOfMonth()->format('Y-m-d');
                $this_month_day_no = Carbon::parse($this_month_start_date)->diffInDays($this_month_end_date);

                $last_month_start_date = Carbon::parse($from_date)->subMonth()->startOfMonth()->format('Y-m-d');
                $last_month_end_date = Carbon::parse($from_date)->subMonth()->endOfMonth()->format('Y-m-d');
                $last_month_day_no = Carbon::parse($last_month_start_date)->diffInDays($last_month_end_date);

                $ytd_start_date = Carbon::parse($from_date)->startOfYear()->format('Y-m-d');
                $ytd_day_no = Carbon::parse($ytd_start_date)->diffInDays($to_date);
            }

            if($request->has('plant_id')) {
                $plant = Plant::find($request->plant_id);
            }

            if(empty($plant) || empty($date_range_arr)) {

                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            // gross generation 
            $gross_generation['this_month'] = $plant->getMonthlyEngineGrossGeneration($this_month_start_date, $this_month_end_date);
            $gross_generation['last_month'] = $plant->getMonthlyEngineGrossGeneration($last_month_start_date, $last_month_end_date);
            $gross_generation['ytd'] = $plant->getMonthlyEngineGrossGeneration($ytd_start_date, $to_date);

            // net generation 
            $thisMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($this_month_start_date, $this_month_end_date);
            $lastMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($last_month_start_date, $last_month_end_date);
            $ytdMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($ytd_start_date, $to_date);

            $net_generation['this_month'] = $thisMonthlyEnergyMeterBillingInfo ? $thisMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;
            $net_generation['last_month'] = $lastMonthlyEnergyMeterBillingInfo ? $lastMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;
            $net_generation['ytd'] = $ytdMonthlyEnergyMeterBillingInfo ? $ytdMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;

            // Energy import
            $energy_import['this_month'] = $thisMonthlyEnergyMeterBillingInfo ? $thisMonthlyEnergyMeterBillingInfo['total_import_kwh'] : 0;
            $energy_import['last_month'] = $lastMonthlyEnergyMeterBillingInfo ? $lastMonthlyEnergyMeterBillingInfo['total_import_kwh'] : 0;
            $energy_import['ytd'] = $ytdMonthlyEnergyMeterBillingInfo ? $ytdMonthlyEnergyMeterBillingInfo['total_import_kwh'] : 0;

            // PLF
            $plf['this_month'] = $plant->getPLFMonthToDate($this_month_start_date, $this_month_end_date);
            $plf['last_month'] = $plant->getPLFMonthToDate($last_month_start_date, $last_month_end_date);
            $plf['ytd'] = $plant->getPLFMonthToDate($ytd_start_date, $to_date);

            // Running hours
            $engine_activities['engine-running']['this_month'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $this_month_start_date, $this_month_end_date);
            $engine_activities['engine-running']['last_month'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $last_month_start_date, $last_month_end_date);
            $engine_activities['engine-running']['ytd'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $ytd_start_date, $to_date);

            // Schedule outage
            $engine_activities['schedule-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $this_month_start_date, $this_month_end_date);
            $engine_activities['schedule-outage']['last_month'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $last_month_start_date, $last_month_end_date);
            $engine_activities['schedule-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $ytd_start_date, $to_date);

            // Maintenance outage
            $engine_activities['maintenance-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $this_month_start_date, $this_month_end_date);
            $engine_activities['maintenance-outage']['last_month'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $last_month_start_date, $last_month_end_date);
            $engine_activities['maintenance-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $ytd_start_date, $to_date);

            // Force outage
            $engine_activities['force-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $this_month_start_date, $this_month_end_date);
            $engine_activities['force-outage']['last_month'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $last_month_start_date, $last_month_end_date);
            $engine_activities['force-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $ytd_start_date, $to_date);

            // Reverse Shutdown
            $engine_activities_time = [];

            // this month reverse shut down
            $engine_running_this_month_arr = explode('.', $engine_activities['engine-running']['this_month']);
            $engine_activities_time['this_month'][] = $engine_running_this_month_arr[0].':'.$engine_running_this_month_arr[1];

            $schedule_outage_this_month_arr = explode('.', $engine_activities['schedule-outage']['this_month']);
            $engine_activities_time['this_month'][] = $schedule_outage_this_month_arr[0].':'.$schedule_outage_this_month_arr[1];

            $maintenance_outage_this_month_arr = explode('.', $engine_activities['maintenance-outage']['this_month']);
            $engine_activities_time['this_month'][] = $maintenance_outage_this_month_arr[0].':'.$maintenance_outage_this_month_arr[1];

            $force_outage_this_month_arr = explode('.', $engine_activities['force-outage']['this_month']);
            $engine_activities_time['this_month'][] = $force_outage_this_month_arr[0].':'.$force_outage_this_month_arr[1];

            $this_month_total_activity_time = getTotalTime($engine_activities_time['this_month']);
            $this_month_total_time =  $this_month_day_no * 24 .':00';

            // last month reverse shut down
            $engine_running_last_month_arr = explode('.', $engine_activities['engine-running']['last_month']);
            $engine_activities_time['last_month'][] = $engine_running_last_month_arr[0].':'.$engine_running_last_month_arr[1];

            $schedule_outage_last_month_arr = explode('.', $engine_activities['schedule-outage']['last_month']);
            $engine_activities_time['last_month'][] = $schedule_outage_last_month_arr[0].':'.$schedule_outage_last_month_arr[1];

            $maintenance_outage_last_month_arr = explode('.', $engine_activities['maintenance-outage']['last_month']);
            $engine_activities_time['last_month'][] = $maintenance_outage_last_month_arr[0].':'.$maintenance_outage_last_month_arr[1];

            $force_outage_last_month_arr = explode('.', $engine_activities['force-outage']['last_month']);
            $engine_activities_time['last_month'][] = $force_outage_last_month_arr[0].':'.$force_outage_last_month_arr[1];

            $last_month_total_activity_time = getTotalTime($engine_activities_time['last_month']);
            $last_month_total_time =  $last_month_day_no * 24 .':00';

            // ytd reverse shut down
            $engine_running_ytd_arr = explode('.', $engine_activities['engine-running']['ytd']);
            $engine_activities_time['ytd'][] = $engine_running_ytd_arr[0].':'.$engine_running_ytd_arr[1];

            $schedule_outage_ytd_arr = explode('.', $engine_activities['schedule-outage']['ytd']);
            $engine_activities_time['ytd'][] = $schedule_outage_ytd_arr[0].':'.$schedule_outage_ytd_arr[1];

            $maintenance_outage_ytd_arr = explode('.', $engine_activities['maintenance-outage']['ytd']);
            $engine_activities_time['ytd'][] = $maintenance_outage_ytd_arr[0].':'.$maintenance_outage_ytd_arr[1];

            $force_outage_ytd_arr = explode('.', $engine_activities['force-outage']['ytd']);
            $engine_activities_time['ytd'][] = $force_outage_ytd_arr[0].':'.$force_outage_ytd_arr[1];

            $ytd_total_activity_time = getTotalTime($engine_activities_time['ytd']);
            $ytd_total_time =  $ytd_day_no * 24 .':00';        

            $engine_activities['reverse_shut_down']['this_month'] = getDiffTimeFromTimeValue($this_month_total_activity_time, $this_month_total_time);
            $engine_activities['reverse_shut_down']['last_month'] = getDiffTimeFromTimeValue($last_month_total_activity_time, $last_month_total_time);
            $engine_activities['reverse_shut_down']['ytd'] = getDiffTimeFromTimeValue($ytd_total_activity_time, $ytd_total_time);

            // Plant availablity 
            $engine_activities['plant_availability']['this_month'] = 100 * ((float) $engine_activities['engine-running']['this_month'] + (float) $engine_activities['reverse_shut_down']['this_month'])/($this_month_day_no * 24);
            $engine_activities['plant_availability']['last_month'] = 100 * ((float) $engine_activities['engine-running']['last_month'] + (float) $engine_activities['reverse_shut_down']['last_month'])/($last_month_day_no * 24);
            $engine_activities['plant_availability']['ytd'] = 100 * ((float) $engine_activities['engine-running']['ytd'] + (float) $engine_activities['reverse_shut_down']['ytd'])/($ytd_day_no * 24);

            // Plant reliability
            $engine_activities['plant_reliability']['this_month'] = 100 * (($this_month_day_no * 24) - (float) $engine_activities['maintenance-outage']['this_month'])/($this_month_day_no * 24);
            $engine_activities['plant_reliability']['last_month'] = 100 * (($last_month_day_no * 24) - (float) $engine_activities['maintenance-outage']['last_month'])/($last_month_day_no * 24);
            $engine_activities['plant_reliability']['ytd'] = 100 * (($ytd_day_no * 24) - (float) $engine_activities['maintenance-outage']['ytd'])/($ytd_day_no * 24);

            // Plant utilization
            $engine_activities['plant_utilization']['this_month'] = 100 * (float) $engine_activities['engine-running']/($this_month_day_no * 24);
            $engine_activities['plant_utilization']['last_month'] = 100 * (float) $engine_activities['engine-running']/($last_month_day_no * 24);
            $engine_activities['plant_utilization']['ytd'] = 100 * (float) $engine_activities['engine-running']/($ytd_day_no * 24);

            $thisMonthHfoLubeModuleInfo = $plant->getMonthlyHfoLubeModuleConsumptionInfo($this_month_start_date, $this_month_end_date);
            $lastMonthHfoLubeModuleInfo = $plant->getMonthlyHfoLubeModuleConsumptionInfo($last_month_start_date, $last_month_end_date);
            $ytdHfoLubeModuleInfo = $plant->getMonthlyHfoLubeModuleConsumptionInfo($ytd_start_date, $to_date);

            // total fuel comsumption flowmeter
            $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['this_month'] = $thisMonthHfoLubeModuleInfo->hfo;
            $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['last_month'] = $lastMonthHfoLubeModuleInfo->hfo;
            $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['ytd'] = $ytdHfoLubeModuleInfo->hfo;

            $thisMonthPlantGenerationInfo = $plant->getMonthlyPlantGenerationInfo($this_month_start_date, $this_month_end_date);
            $lastMonthPlantGenerationInfo = $plant->getMonthlyPlantGenerationInfo($last_month_start_date, $last_month_end_date);
            $ytdPlantGenerationInfo = $plant->getMonthlyPlantGenerationInfo($ytd_start_date, $to_date);

            // total fuel comsumtion tank sorounding
            $fuel_consumption_heat_rate['total_fuel_consumption_tank']['this_month'] = $thisMonthPlantGenerationInfo->plant_fuel_consumption;
            $fuel_consumption_heat_rate['total_fuel_consumption_tank']['last_month'] = $lastMonthPlantGenerationInfo->plant_fuel_consumption;
            $fuel_consumption_heat_rate['total_fuel_consumption_tank']['ytd'] = $ytdPlantGenerationInfo->plant_fuel_consumption;

            // Auxiliary Boiler HFO Consmp. (Assumption) 
            $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['this_month'] = $thisMonthPlantGenerationInfo->aux_boiler_hfo_consumption;
            $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['last_month'] = $lastMonthPlantGenerationInfo->aux_boiler_hfo_consumption;
            $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['ytd'] = $ytdPlantGenerationInfo->aux_boiler_hfo_consumption;

            // Sludge Production
            $fuel_consumption_heat_rate['sludge_production']['this_month'] = $fuel_consumption_heat_rate['total_fuel_consumption_tank']['this_month'] ? 100 * ($fuel_consumption_heat_rate['total_fuel_consumption_tank']['this_month'] - $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['this_month'] - $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['this_month'])/$fuel_consumption_heat_rate['total_fuel_consumption_tank']['this_month'] : 'N/A';
            $fuel_consumption_heat_rate['sludge_production']['last_month'] = $fuel_consumption_heat_rate['total_fuel_consumption_tank']['last_month'] ? 100 * ($fuel_consumption_heat_rate['total_fuel_consumption_tank']['last_month'] - $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['last_month'] - $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['last_month'])/$fuel_consumption_heat_rate['total_fuel_consumption_tank']['last_month'] : 'N/A';
            $fuel_consumption_heat_rate['sludge_production']['ytd'] = $fuel_consumption_heat_rate['total_fuel_consumption_tank']['ytd'] ? 100 * ($fuel_consumption_heat_rate['total_fuel_consumption_tank']['ytd'] - $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['ytd'] - $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['ytd'])/$fuel_consumption_heat_rate['total_fuel_consumption_tank']['ytd'] : 'N/A';

            // Heating Value of HFO
            $fuel_consumption_heat_rate['heating_value_hfo']['this_month'] = $thisMonthPlantGenerationInfo->reference_lhv;
            $fuel_consumption_heat_rate['heating_value_hfo']['last_month'] = $lastMonthPlantGenerationInfo->reference_lhv;
            $fuel_consumption_heat_rate['heating_value_hfo']['ytd'] = $ytdPlantGenerationInfo->reference_lhv;

            // Net Heat Rate based on Flowmeter
            $fuel_consumption_heat_rate['net_heat_rate_flowmeter']['this_month'] = $net_generation['this_month'] ? $thisMonthPlantGenerationInfo->reference_lhv * $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['this_month']/$net_generation['this_month'] : 'N/A';
            $fuel_consumption_heat_rate['net_heat_rate_flowmeter']['last_month'] = $net_generation['last_month'] ? $lastMonthPlantGenerationInfo->reference_lhv * $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['last_month']/$net_generation['last_month'] : 'N/A';
            $fuel_consumption_heat_rate['net_heat_rate_flowmeter']['ytd'] = $net_generation['ytd'] ? $ytdPlantGenerationInfo->reference_lhv * $fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['ytd']/$net_generation['ytd'] : 'N/A';

            // Net Heat Rate based on Tank sounding
            $fuel_consumption_heat_rate['net_heat_rate_tank']['this_month'] = $net_generation['this_month'] ? ($fuel_consumption_heat_rate['total_fuel_consumption_tank']['this_month'] - $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['this_month']) * $thisMonthPlantGenerationInfo->reference_lhv/$net_generation['this_month'] : 'N/A';
            $fuel_consumption_heat_rate['net_heat_rate_tank']['last_month'] = $net_generation['last_month'] ? ($fuel_consumption_heat_rate['total_fuel_consumption_tank']['last_month'] - $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['last_month']) * $lastMonthPlantGenerationInfo->reference_lhv/$net_generation['last_month'] : 'N/A';
            $fuel_consumption_heat_rate['net_heat_rate_tank']['ytd'] = $net_generation['ytd'] ? ($fuel_consumption_heat_rate['total_fuel_consumption_tank']['ytd'] - $fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['ytd']) * $ytdPlantGenerationInfo->reference_lhv/$net_generation['ytd'] : 'N/A';

            // Total Lube oil Consumption
            $fuel_consumption_heat_rate['total_lube_oil_consumption']['this_month'] = $thisMonthHfoLubeModuleInfo->lube_oil;
            $fuel_consumption_heat_rate['total_lube_oil_consumption']['last_month'] = $lastMonthHfoLubeModuleInfo->lube_oil;
            $fuel_consumption_heat_rate['total_lube_oil_consumption']['ytd'] = $ytdHfoLubeModuleInfo->lube_oil;

            // Specific Lube oil Consumption
            $fuel_consumption_heat_rate['specific_lube_oil_consumption']['this_month'] = $net_generation['this_month'] ? $thisMonthHfoLubeModuleInfo->lube_oil/$net_generation['this_month'] : 'N/A';
            $fuel_consumption_heat_rate['specific_lube_oil_consumption']['last_month'] = $net_generation['last_month'] ? $lastMonthHfoLubeModuleInfo->lube_oil/$net_generation['last_month'] : 'N/A';
            $fuel_consumption_heat_rate['specific_lube_oil_consumption']['ytd'] = $net_generation['ytd'] ? $ytdHfoLubeModuleInfo->lube_oil/$net_generation['ytd'] : 'N/A';

            // Total turbine generation
            $turbine_info['total_generation']['this_month'] = $plant->getMonthlyTurbineGeneration($this_month_start_date, $this_month_end_date);
            $turbine_info['total_generation']['last_month'] = $plant->getMonthlyTurbineGeneration($last_month_start_date, $last_month_end_date);
            $turbine_info['total_generation']['ytd'] = $plant->getMonthlyTurbineGeneration($ytd_start_date, $to_date);

            // Co generation 
            $turbine_info['co_generation']['this_month'] = $gross_generation['this_month'] ? 100 * $turbine_info['total_generation']['this_month']/$gross_generation['this_month']: 0;
            $turbine_info['co_generation']['last_month'] = $gross_generation['last_month'] ? 100 * $turbine_info['total_generation']['last_month']/$gross_generation['last_month'] : 0;
            $turbine_info['co_generation']['ytd'] = $gross_generation['ytd'] ? 100 * $turbine_info['total_generation']['ytd']/$gross_generation['ytd'] : 0;

            $turbine_info['running_hour']['this_month'] = $plant->getTurbineEngineActivityHours('engine-running', $this_month_start_date, $this_month_end_date);
            $turbine_info['running_hour']['last_month'] = $plant->getTurbineEngineActivityHours('engine-running', $last_month_start_date, $last_month_end_date);
            $turbine_info['running_hour']['ytd'] = $plant->getTurbineEngineActivityHours('engine-running', $ytd_start_date, $to_date);

            if($action == 'print') {
                return view('prints.plant-wise-operation', compact('plants', 'plant', 'from_date', 'to_date', 'gross_generation', 'net_generation', 'energy_import', 'plf', 'engine_activities', 'fuel_consumption_heat_rate', 'turbine_info'));
            }
        }

        return view('reports.plant-wise-operation', compact('plants', 'from_date', 'to_date', 'gross_generation', 'net_generation', 'energy_import', 'plf', 'engine_activities', 'fuel_consumption_heat_rate', 'turbine_info'));
    }

    /**
     * Show info of overall operation
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $action
     * @return \Illuminate\Http\Response
     */
    public function getOverallOperation(Request $request, $action = null)
    {
        $plants = Plant::get(['id', 'name', 'energy_meter_multification_factor']);

        if(array_filter(array_except($request->all(), ['_token']))) {

            if($request->has('date_range')) {
                $date_range_arr  = explode(' - ',$request->date_range);
                $from_date = \Carbon::parse($date_range_arr[0])->format('Y-m-d');
                $to_date = \Carbon::parse($date_range_arr[1])->format('Y-m-d');

                $day_no = Carbon::parse($from_date)->diffInDays($to_date);

                $this_month_start_date = Carbon::parse($from_date)->startOfMonth()->format('Y-m-d');
                $this_month_end_date = Carbon::parse($from_date)->endOfMonth()->format('Y-m-d');
                $this_month_day_no = Carbon::parse($this_month_start_date)->diffInDays($this_month_end_date);

                $ytd_start_date = Carbon::parse($from_date)->startOfYear()->format('Y-m-d');
                $ytd_day_no = Carbon::parse($ytd_start_date)->diffInDays($to_date);
            }

            if($plants->isEmpty() || empty($date_range_arr)) {

                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            foreach($plants as $plant) {

                // gross generation 
                $plant_info[$plant->id]['gross_generation']['this_month'] = $plant->getMonthlyEngineGrossGeneration($this_month_start_date, $this_month_end_date);
                $plant_info[$plant->id]['gross_generation']['ytd'] = $plant->getMonthlyEngineGrossGeneration($ytd_start_date, $to_date);

                // net generation 
                $thisMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($this_month_start_date, $this_month_end_date);
                $ytdMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($ytd_start_date, $to_date);

                $plant_info[$plant->id]['net_generation']['this_month'] = $thisMonthlyEnergyMeterBillingInfo ? $thisMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;
                $plant_info[$plant->id]['net_generation']['ytd'] = $ytdMonthlyEnergyMeterBillingInfo ? $ytdMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;

                // Energy import
                $plant_info[$plant->id]['energy_import']['this_month'] = $thisMonthlyEnergyMeterBillingInfo ? $thisMonthlyEnergyMeterBillingInfo['total_import_kwh'] : 0;
                $plant_info[$plant->id]['energy_import']['ytd'] = $ytdMonthlyEnergyMeterBillingInfo ? $ytdMonthlyEnergyMeterBillingInfo['total_import_kwh'] : 0;

                // Net export
                $plant_info[$plant->id]['net_export']['this_month'] = $plant_info[$plant->id]['net_generation']['this_month'] - $plant_info[$plant->id]['energy_import']['this_month'];
                $plant_info[$plant->id]['net_export']['ytd'] = $plant_info[$plant->id]['net_generation']['ytd'] - $plant_info[$plant->id]['energy_import']['ytd'];

                // Station Load MWh
                $plant_info[$plant->id]['station_load_mwh']['this_month'] = $plant_info[$plant->id]['gross_generation']['this_month'] - $plant_info[$plant->id]['net_generation']['this_month'] + $plant_info[$plant->id]['energy_import']['this_month'];
                $plant_info[$plant->id]['station_load_mwh']['ytd'] = $plant_info[$plant->id]['gross_generation']['ytd'] - $plant_info[$plant->id]['net_generation']['ytd'] + $plant_info[$plant->id]['energy_import']['ytd'];

                // Station Load percentage
                $plant_info[$plant->id]['station_load_percentage']['this_month'] = $plant_info[$plant->id]['gross_generation']['this_month'] ? 100 * $plant_info[$plant->id]['station_load_mwh']['this_month']/$plant_info[$plant->id]['gross_generation']['this_month'] : '';
                $plant_info[$plant->id]['station_load_percentage']['ytd'] = $plant_info[$plant->id]['gross_generation']['ytd'] ? 100 * $plant_info[$plant->id]['station_load_mwh']['ytd']/$plant_info[$plant->id]['gross_generation']['ytd'] : '';

                // PLF
                $plant_info[$plant->id]['plf']['this_month'] = $plant->getPLFMonthToDate($this_month_start_date, $this_month_end_date);
                $plant_info[$plant->id]['plf']['ytd'] = $plant->getPLFMonthToDate($ytd_start_date, $to_date);

                // Running hours
                $plant_info[$plant->id]['engine-running']['this_month'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $this_month_start_date, $this_month_end_date);
                $plant_info[$plant->id]['engine-running']['ytd'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $ytd_start_date, $to_date);

                // Schedule outage
                $plant_info[$plant->id]['schedule-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $this_month_start_date, $this_month_end_date);
                $plant_info[$plant->id]['schedule-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $ytd_start_date, $to_date);

                // Maintenance outage
                $plant_info[$plant->id]['maintenance-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $this_month_start_date, $this_month_end_date);
                $plant_info[$plant->id]['maintenance-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $ytd_start_date, $to_date);

                // Force outage
                $plant_info[$plant->id]['force-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $this_month_start_date, $this_month_end_date);
                $plant_info[$plant->id]['force-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $ytd_start_date, $to_date);

                // Reverse Shutdown
                $engine_activities_time = [];

                // this month reverse shut down
                $engine_running_this_month_arr = explode('.', $plant_info[$plant->id]['engine-running']['this_month']);
                $engine_activities_time['this_month'][] = $engine_running_this_month_arr[0].':'.$engine_running_this_month_arr[1];

                $schedule_outage_this_month_arr = explode('.', $plant_info[$plant->id]['schedule-outage']['this_month']);
                $engine_activities_time['this_month'][] = $schedule_outage_this_month_arr[0].':'.$schedule_outage_this_month_arr[1];

                $maintenance_outage_this_month_arr = explode('.', $plant_info[$plant->id]['maintenance-outage']['this_month']);
                $engine_activities_time['this_month'][] = $maintenance_outage_this_month_arr[0].':'.$maintenance_outage_this_month_arr[1];

                $force_outage_this_month_arr = explode('.', $plant_info[$plant->id]['force-outage']['this_month']);
                $engine_activities_time['this_month'][] = $force_outage_this_month_arr[0].':'.$force_outage_this_month_arr[1];

                $this_month_total_activity_time = getTotalTime($engine_activities_time['this_month']);
                $this_month_total_time =  $this_month_day_no * 24 .':00';

                // ytd reverse shut down
                $engine_running_ytd_arr = explode('.', $plant_info[$plant->id]['engine-running']['ytd']);
                $engine_activities_time['ytd'][] = $engine_running_ytd_arr[0].':'.$engine_running_ytd_arr[1];

                $schedule_outage_ytd_arr = explode('.', $plant_info[$plant->id]['schedule-outage']['ytd']);
                $engine_activities_time['ytd'][] = $schedule_outage_ytd_arr[0].':'.$schedule_outage_ytd_arr[1];

                $maintenance_outage_ytd_arr = explode('.', $plant_info[$plant->id]['maintenance-outage']['ytd']);
                $engine_activities_time['ytd'][] = $maintenance_outage_ytd_arr[0].':'.$maintenance_outage_ytd_arr[1];

                $force_outage_ytd_arr = explode('.', $plant_info[$plant->id]['force-outage']['ytd']);
                $engine_activities_time['ytd'][] = $force_outage_ytd_arr[0].':'.$force_outage_ytd_arr[1];

                $ytd_total_activity_time = getTotalTime($engine_activities_time['ytd']);
                $ytd_total_time =  $ytd_day_no * 24 .':00';       

                $plant_info[$plant->id]['reverse_shut_down']['this_month'] = getDiffTimeFromTimeValue($this_month_total_activity_time, $this_month_total_time);
                $plant_info[$plant->id]['reverse_shut_down']['ytd'] = getDiffTimeFromTimeValue($ytd_total_activity_time, $ytd_total_time);

                // Plant availablity 
                $plant_info[$plant->id]['plant_availability']['this_month'] = 100 * ((float) $plant_info[$plant->id]['engine-running']['this_month'] + (float) $plant_info[$plant->id]['reverse_shut_down']['this_month'])/($this_month_day_no * 24);
                $plant_info[$plant->id]['plant_availability']['ytd'] = 100 * ((float) $plant_info[$plant->id]['engine-running']['ytd'] + (float) $plant_info[$plant->id]['reverse_shut_down']['ytd'])/($ytd_day_no * 24);

                // Plant reliability
                $plant_info[$plant->id]['plant_reliability']['this_month'] = 100 * (($this_month_day_no * 24) - (float) $plant_info[$plant->id]['maintenance-outage']['this_month'])/($this_month_day_no * 24);
                $plant_info[$plant->id]['plant_reliability']['ytd'] = 100 * (($ytd_day_no * 24) - (float) $plant_info[$plant->id]['maintenance-outage']['ytd'])/($ytd_day_no * 24);

                // Plant utilization
                $plant_info[$plant->id]['plant_utilization']['this_month'] = 100 * (float) $plant_info[$plant->id]['engine-running']/($this_month_day_no * 24);
                $plant_info[$plant->id]['plant_utilization']['ytd'] = 100 * (float) $plant_info[$plant->id]['engine-running']/($ytd_day_no * 24);

                $thisMonthHfoLubeModuleInfo = $plant->getMonthlyHfoLubeModuleConsumptionInfo($this_month_start_date, $this_month_end_date);
                $ytdHfoLubeModuleInfo = $plant->getMonthlyHfoLubeModuleConsumptionInfo($ytd_start_date, $to_date);

                // total fuel comsumption flowmeter
                $plant_info[$plant->id]['total_fuel_consumption_flowmeter']['this_month'] = $thisMonthHfoLubeModuleInfo->hfo;
                $plant_info[$plant->id]['total_fuel_consumption_flowmeter']['ytd'] = $ytdHfoLubeModuleInfo->hfo;

                $thisMonthPlantGenerationInfo = $plant->getMonthlyPlantGenerationInfo($this_month_start_date, $this_month_end_date);
                $ytdPlantGenerationInfo = $plant->getMonthlyPlantGenerationInfo($ytd_start_date, $to_date);

                // total fuel comsumtion tank sorounding
                $plant_info[$plant->id]['total_fuel_consumption_tank']['this_month'] = $thisMonthPlantGenerationInfo->plant_fuel_consumption;
                $plant_info[$plant->id]['total_fuel_consumption_tank']['ytd'] = $ytdPlantGenerationInfo->plant_fuel_consumption;

                // Auxiliary Boiler HFO Consmp. (Assumption) 
                $plant_info[$plant->id]['aux_boiler_hfo_consumption']['this_month'] = $thisMonthPlantGenerationInfo->aux_boiler_hfo_consumption;
                $plant_info[$plant->id]['aux_boiler_hfo_consumption']['ytd'] = $ytdPlantGenerationInfo->aux_boiler_hfo_consumption;

                // Sludge Production
                $plant_info[$plant->id]['sludge_production']['this_month'] = $plant_info[$plant->id]['total_fuel_consumption_tank']['this_month'] ? 100 * ($plant_info[$plant->id]['total_fuel_consumption_tank']['this_month'] - $plant_info[$plant->id]['total_fuel_consumption_flowmeter']['this_month'] - $plant_info[$plant->id]['aux_boiler_hfo_consumption']['this_month'])/$plant_info[$plant->id]['total_fuel_consumption_tank']['this_month'] : '';
                $plant_info[$plant->id]['sludge_production']['ytd'] = $plant_info[$plant->id]['total_fuel_consumption_tank']['ytd'] ? 100 * ($plant_info[$plant->id]['total_fuel_consumption_tank']['ytd'] - $plant_info[$plant->id]['total_fuel_consumption_flowmeter']['ytd'] - $plant_info[$plant->id]['aux_boiler_hfo_consumption']['ytd'])/$plant_info[$plant->id]['total_fuel_consumption_tank']['ytd'] : '';

                // Heating Value of HFO
                $plant_info[$plant->id]['heating_value_hfo']['this_month'] = $thisMonthPlantGenerationInfo->reference_lhv;
                $plant_info[$plant->id]['heating_value_hfo']['ytd'] = $ytdPlantGenerationInfo->reference_lhv;

                // Net Heat Rate based on Flowmeter
                $plant_info[$plant->id]['net_heat_rate_flowmeter']['this_month'] = $plant_info[$plant->id]['net_generation']['this_month'] ? $thisMonthPlantGenerationInfo->reference_lhv * $plant_info[$plant->id]['total_fuel_consumption_flowmeter']['this_month']/$plant_info[$plant->id]['net_generation']['this_month'] : '';
                $plant_info[$plant->id]['net_heat_rate_flowmeter']['ytd'] = $plant_info[$plant->id]['net_generation']['ytd'] ? $ytdPlantGenerationInfo->reference_lhv * $plant_info[$plant->id]['total_fuel_consumption_flowmeter']['ytd']/$plant_info[$plant->id]['net_generation']['ytd'] : '';

                // Net Heat Rate based on Tank sounding
                $plant_info[$plant->id]['net_heat_rate_tank']['this_month'] = $plant_info[$plant->id]['net_generation']['this_month'] ? ($plant_info[$plant->id]['total_fuel_consumption_tank']['this_month'] - $plant_info[$plant->id]['aux_boiler_hfo_consumption']['this_month']) * $thisMonthPlantGenerationInfo->reference_lhv/$plant_info[$plant->id]['net_generation']['this_month'] : '';
                $plant_info[$plant->id]['net_heat_rate_tank']['ytd'] = $plant_info[$plant->id]['net_generation']['ytd'] ? ($plant_info[$plant->id]['total_fuel_consumption_tank']['ytd'] - $plant_info[$plant->id]['aux_boiler_hfo_consumption']['ytd']) * $ytdPlantGenerationInfo->reference_lhv/$plant_info[$plant->id]['net_generation']['ytd'] : '';

                // Total Lube oil Consumption
                $plant_info[$plant->id]['total_lube_oil_consumption']['this_month'] = $thisMonthHfoLubeModuleInfo->lube_oil;
                $plant_info[$plant->id]['total_lube_oil_consumption']['ytd'] = $ytdHfoLubeModuleInfo->lube_oil;

                // Specific Lube oil Consumption
                $plant_info[$plant->id]['specific_lube_oil_consumption']['this_month'] = $plant_info[$plant->id]['net_generation']['this_month'] ? $thisMonthHfoLubeModuleInfo->lube_oil/$plant_info[$plant->id]['net_generation']['this_month'] : '';
                $plant_info[$plant->id]['specific_lube_oil_consumption']['ytd'] = $plant_info[$plant->id]['net_generation']['ytd'] ? $ytdHfoLubeModuleInfo->lube_oil/$plant_info[$plant->id]['net_generation']['ytd'] : '';

                // Total turbine generation
                $plant_info[$plant->id]['turbine_total_generation']['this_month'] = $plant->getMonthlyTurbineGeneration($this_month_start_date, $this_month_end_date);
                $plant_info[$plant->id]['turbine_total_generation']['ytd'] = $plant->getMonthlyTurbineGeneration($ytd_start_date, $to_date);

                // Co generation 
                $plant_info[$plant->id]['turbine_co_generation']['this_month'] = $plant_info[$plant->id]['gross_generation']['this_month'] ? 100 * $plant_info[$plant->id]['turbine_total_generation']['this_month']/$plant_info[$plant->id]['gross_generation']['this_month']: 0;
                $plant_info[$plant->id]['turbine_co_generation']['ytd'] = $plant_info[$plant->id]['gross_generation']['ytd'] ? 100 * $plant_info[$plant->id]['turbine_total_generation']['ytd']/$plant_info[$plant->id]['gross_generation']['ytd'] : 0;

                $plant_info[$plant->id]['turbine_running_hour']['this_month'] = $plant->getTurbineEngineActivityHours('engine-running', $this_month_start_date, $this_month_end_date);
                $plant_info[$plant->id]['turbine_running_hour']['ytd'] = $plant->getTurbineEngineActivityHours('engine-running', $ytd_start_date, $to_date);
            }

            if($action == 'print') {
                return view('prints.overall-operation', compact('plants','from_date', 'to_date', 'plant_info'));
            }
        }

        // dd($plant_info);
        return view('reports.overall-operation', compact('plants','from_date', 'to_date', 'plant_info'));
    }

    /**
     * Show info of plant wise outage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $action
     * @return \Illuminate\Http\Response
     */
    public function getPlantWiseOutage(Request $request, $action = null)
    {
        $plants = Plant::getDropDownList();
        $plant_info = [];

        if(array_filter(array_except($request->all(), ['_token']))) {

            if($request->has('date_range')) {
                $date_range_arr  = explode(' - ',$request->date_range);
                $from_date = \Carbon::parse($date_range_arr[0])->format('Y-m-d');
                $to_date = \Carbon::parse($date_range_arr[1])->format('Y-m-d');

                $day_no = Carbon::parse($from_date)->diffInDays($to_date);

                $this_month_start_date = Carbon::parse($from_date)->startOfMonth()->format('Y-m-d');
                $this_month_end_date = Carbon::parse($from_date)->endOfMonth()->format('Y-m-d');
                $this_month_day_no = Carbon::parse($this_month_start_date)->diffInDays($this_month_end_date);

                $last_month_start_date = Carbon::parse($from_date)->subMonth()->startOfMonth()->format('Y-m-d');
                $last_month_end_date = Carbon::parse($from_date)->subMonth()->endOfMonth()->format('Y-m-d');
                $last_month_day_no = Carbon::parse($last_month_start_date)->diffInDays($last_month_end_date);

                $ytd_start_date = Carbon::parse($from_date)->startOfYear()->format('Y-m-d');
                $ytd_day_no = Carbon::parse($ytd_start_date)->diffInDays($to_date);
            }

            if($request->has('plant_id')) {
                $plant = Plant::find($request->plant_id);
            }

            if(empty($plant) || empty($date_range_arr)) {

                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            // Dependable Capacity as per PPA
            $plant_info['dependable_capacity']['this_month'] = $plant->getDependableCapacity();
            $plant_info['dependable_capacity']['last_month'] = $plant->getDependableCapacity();
            $plant_info['dependable_capacity']['ytd'] = $plant->getDependableCapacity();

            // net generation 
            $thisMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($this_month_start_date, $this_month_end_date);
            $lastMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($last_month_start_date, $last_month_end_date);
            $ytdMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($ytd_start_date, $to_date);

            $plant_info['net_generation']['this_month'] = $thisMonthlyEnergyMeterBillingInfo ? $thisMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;
            $plant_info['net_generation']['last_month'] = $lastMonthlyEnergyMeterBillingInfo ? $lastMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;
            $plant_info['net_generation']['ytd'] = $ytdMonthlyEnergyMeterBillingInfo ? $ytdMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;

            // Running hours
            $plant_info['engine-running']['this_month'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $this_month_start_date, $this_month_end_date);
            $plant_info['engine-running']['last_month'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $last_month_start_date, $last_month_end_date);
            $plant_info['engine-running']['ytd'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $ytd_start_date, $to_date);

            // Schedule outage
            $plant_info['schedule-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $this_month_start_date, $this_month_end_date);
            $plant_info['schedule-outage']['last_month'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $last_month_start_date, $last_month_end_date);
            $plant_info['schedule-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $ytd_start_date, $to_date);

            // Maintenance outage
            $plant_info['maintenance-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $this_month_start_date, $this_month_end_date);
            $plant_info['maintenance-outage']['last_month'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $last_month_start_date, $last_month_end_date);
            $plant_info['maintenance-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $ytd_start_date, $to_date);

            // Force outage
            $plant_info['force-outage']['this_month'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $this_month_start_date, $this_month_end_date);
            $plant_info['force-outage']['last_month'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $last_month_start_date, $last_month_end_date);
            $plant_info['force-outage']['ytd'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $ytd_start_date, $to_date);

            // This month outage including Grid
            $plant_info['this_month_outage_including_grid']['this_month'] =   str_replace(':', '.', getTotalTime([$plant_info['schedule-outage']['this_month'], $plant_info['maintenance-outage']['this_month'], $plant_info['force-outage']['this_month']])); 
            $plant_info['this_month_outage_including_grid']['last_month'] =   str_replace(':', '.', getTotalTime([$plant_info['schedule-outage']['last_month'], $plant_info['maintenance-outage']['last_month'], $plant_info['force-outage']['last_month']])); 
            $plant_info['this_month_outage_including_grid']['ytd'] =   str_replace(':', '.', getTotalTime([$plant_info['schedule-outage']['ytd'], $plant_info['maintenance-outage']['ytd'], $plant_info['force-outage']['ytd']])); 

            // This month  outage  Excluding  Grid
            $plant_info['this_month_outage_excluding_grid']['this_month'] = 
                str_replace(':', '.', getTotalTime([$plant_info['schedule-outage']['this_month'], $plant_info['maintenance-outage']['this_month']]));
            $plant_info['this_month_outage_excluding_grid']['last_month'] = 
                str_replace(':', '.', getTotalTime([$plant_info['schedule-outage']['last_month'], $plant_info['maintenance-outage']['last_month']]));
            $plant_info['this_month_outage_excluding_grid']['ytd'] = 
                str_replace(':', '.', getTotalTime([$plant_info['schedule-outage']['ytd'], $plant_info['maintenance-outage']['ytd']]));

            // Reverse Shutdown
            $engine_activities_time = [];

            // this month reverse shut down
            $engine_running_this_month_arr = explode('.', $plant_info['engine-running']['this_month']);
            $engine_activities_time['this_month'][] = $engine_running_this_month_arr[0].':'.$engine_running_this_month_arr[1];

            $schedule_outage_this_month_arr = explode('.', $plant_info['schedule-outage']['this_month']);
            $engine_activities_time['this_month'][] = $schedule_outage_this_month_arr[0].':'.$schedule_outage_this_month_arr[1];

            $maintenance_outage_this_month_arr = explode('.', $plant_info['maintenance-outage']['this_month']);
            $engine_activities_time['this_month'][] = $maintenance_outage_this_month_arr[0].':'.$maintenance_outage_this_month_arr[1];

            $force_outage_this_month_arr = explode('.', $plant_info['force-outage']['this_month']);
            $engine_activities_time['this_month'][] = $force_outage_this_month_arr[0].':'.$force_outage_this_month_arr[1];

            $this_month_total_activity_time = getTotalTime($engine_activities_time['this_month']);
            $this_month_total_time =  $this_month_day_no * 24 .':00';

            // last month reverse shut down
            $engine_running_last_month_arr = explode('.', $plant_info['engine-running']['last_month']);
            $engine_activities_time['last_month'][] = $engine_running_last_month_arr[0].':'.$engine_running_last_month_arr[1];

            $schedule_outage_last_month_arr = explode('.', $plant_info['schedule-outage']['last_month']);
            $engine_activities_time['last_month'][] = $schedule_outage_last_month_arr[0].':'.$schedule_outage_last_month_arr[1];

            $maintenance_outage_last_month_arr = explode('.', $plant_info['maintenance-outage']['last_month']);
            $engine_activities_time['last_month'][] = $maintenance_outage_last_month_arr[0].':'.$maintenance_outage_last_month_arr[1];

            $force_outage_last_month_arr = explode('.', $plant_info['force-outage']['last_month']);
            $engine_activities_time['last_month'][] = $force_outage_last_month_arr[0].':'.$force_outage_last_month_arr[1];

            $last_month_total_activity_time = getTotalTime($engine_activities_time['last_month']);
            $last_month_total_time =  $last_month_day_no * 24 .':00';

            // ytd reverse shut down
            $engine_running_ytd_arr = explode('.', $plant_info['engine-running']['ytd']);
            $engine_activities_time['ytd'][] = $engine_running_ytd_arr[0].':'.$engine_running_ytd_arr[1];

            $schedule_outage_ytd_arr = explode('.', $plant_info['schedule-outage']['ytd']);
            $engine_activities_time['ytd'][] = $schedule_outage_ytd_arr[0].':'.$schedule_outage_ytd_arr[1];

            $maintenance_outage_ytd_arr = explode('.', $plant_info['maintenance-outage']['ytd']);
            $engine_activities_time['ytd'][] = $maintenance_outage_ytd_arr[0].':'.$maintenance_outage_ytd_arr[1];

            $force_outage_ytd_arr = explode('.', $plant_info['force-outage']['ytd']);
            $engine_activities_time['ytd'][] = $force_outage_ytd_arr[0].':'.$force_outage_ytd_arr[1];

            $ytd_total_activity_time = getTotalTime($engine_activities_time['ytd']);
            $ytd_total_time =  $ytd_day_no * 24 .':00';        

            $plant_info['reverse_shut_down']['this_month'] = getDiffTimeFromTimeValue($this_month_total_activity_time, $this_month_total_time);
            $plant_info['reverse_shut_down']['last_month'] = getDiffTimeFromTimeValue($last_month_total_activity_time, $last_month_total_time);
            $plant_info['reverse_shut_down']['ytd'] = getDiffTimeFromTimeValue($ytd_total_activity_time, $ytd_total_time);

            // Total Permissible Outage
            $plant_info['total_permissible_outage']['this_month'] = $plant->permissible_outage ;
            $plant_info['total_permissible_outage']['last_month'] = $plant->permissible_outage ;
            $plant_info['total_permissible_outage']['ytd'] = $plant->permissible_outage ;

            // YTD Outage (Including Grid) This month
            $ytd_outage_including_grid_this_month_arr = [];
            $ytd_outage_including_grid_this_month_arr[] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $ytd_start_date, $this_month_end_date);
            $ytd_outage_including_grid_this_month_arr[] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $ytd_start_date, $this_month_end_date);
            $ytd_outage_including_grid_this_month_arr[] = $plant->getMonthlyEngineActivityStateHours('force-outage', $ytd_start_date, $this_month_end_date);

            $plant_info['ytd_outage_including_grid']['this_month'] = str_replace(':', '.', getTotalTime($ytd_outage_including_grid_this_month_arr));

            // YTD Outage (Including Grid) last month
            $ytd_outage_including_grid_last_month_arr = [];
            $ytd_outage_including_grid_last_month_arr[] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $ytd_start_date, $last_month_end_date);
            $ytd_outage_including_grid_last_month_arr[] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $ytd_start_date, $last_month_end_date);
            $ytd_outage_including_grid_last_month_arr[] = $plant->getMonthlyEngineActivityStateHours('force-outage', $ytd_start_date, $last_month_end_date);

            $plant_info['ytd_outage_including_grid']['last_month'] = str_replace(':', '.', getTotalTime($ytd_outage_including_grid_last_month_arr));

            // YTD Outage (Including Grid) ytd
            $ytd_outage_including_grid_ytd_arr = [];
            $ytd_outage_including_grid_ytd_arr[] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $ytd_start_date, $to_date);
            $ytd_outage_including_grid_ytd_arr[] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $ytd_start_date, $to_date);
            $ytd_outage_including_grid_ytd_arr[] = $plant->getMonthlyEngineActivityStateHours('force-outage', $ytd_start_date, $to_date);

            $plant_info['ytd_outage_including_grid']['ytd'] = str_replace(':', '.', getTotalTime($ytd_outage_including_grid_ytd_arr));

            // YTD Outage (Excluding Grid)
            array_pop($ytd_outage_including_grid_this_month_arr);
            array_pop($ytd_outage_including_grid_last_month_arr);
            array_pop($ytd_outage_including_grid_ytd_arr);

            $plant_info['ytd_outage_excluding_grid']['this_month'] = str_replace(':', '.', getTotalTime($ytd_outage_including_grid_this_month_arr));
            $plant_info['ytd_outage_excluding_grid']['last_month'] = str_replace(':', '.', getTotalTime($ytd_outage_including_grid_last_month_arr));
            $plant_info['ytd_outage_excluding_grid']['ytd'] = str_replace(':', '.', getTotalTime($ytd_outage_including_grid_ytd_arr));

            // Remaining Permissible Outage for this year  (including Grid) for this month
            $total_permissible_outage_this_month = $plant_info['total_permissible_outage']['this_month'].':00';
            $ytd_outage_including_grid_this_month = str_replace('.', ':', $plant_info['ytd_outage_including_grid']['this_month']);

            $plant_info['remaining_permissible_outage_including_grid']['this_month'] = getDiffTimeFromTimeValue($ytd_outage_including_grid_this_month, $total_permissible_outage_this_month);

            // Remaining Permissible Outage for this year  (including Grid) for last month
            $total_permissible_outage_last_month = $plant_info['total_permissible_outage']['last_month'].':00';
            $ytd_outage_including_grid_last_month = str_replace('.', ':', $plant_info['ytd_outage_including_grid']['last_month']);

            $plant_info['remaining_permissible_outage_including_grid']['last_month'] = getDiffTimeFromTimeValue($ytd_outage_including_grid_last_month, $total_permissible_outage_last_month);

            // Remaining Permissible Outage for this year  (including Grid) for ytd
            $total_permissible_outage_ytd = $plant_info['total_permissible_outage']['ytd'].':00';
            $ytd_outage_including_grid_ytd = str_replace('.', ':', $plant_info['ytd_outage_including_grid']['ytd']);

            $plant_info['remaining_permissible_outage_including_grid']['ytd'] = getDiffTimeFromTimeValue($ytd_outage_including_grid_ytd, $total_permissible_outage_ytd);

            // Remaining Permissible Outage for this year  (excluding Grid) for this month
            $ytd_outage_excluding_grid_this_month = str_replace('.', ':', $plant_info['ytd_outage_excluding_grid']['this_month']);

            $plant_info['remaining_permissible_outage_excluding_grid']['this_month'] = getDiffTimeFromTimeValue($ytd_outage_excluding_grid_this_month, $total_permissible_outage_this_month);

            // Remaining Permissible Outage for this year  (excluding Grid) for last month
            $ytd_outage_excluding_grid_last_month = str_replace('.', ':', $plant_info['ytd_outage_excluding_grid']['last_month']);

            $plant_info['remaining_permissible_outage_excluding_grid']['last_month'] = getDiffTimeFromTimeValue($ytd_outage_excluding_grid_last_month, $total_permissible_outage_last_month);

            // Remaining Permissible Outage for this year  (excluding Grid) for ytd
            $ytd_outage_excluding_grid_ytd = str_replace('.', ':', $plant_info['ytd_outage_excluding_grid']['ytd']);

            $plant_info['remaining_permissible_outage_excluding_grid']['ytd'] = getDiffTimeFromTimeValue($ytd_outage_excluding_grid_ytd, $total_permissible_outage_ytd);

            // Remaining Permissible Outage for this year  (including Grid) MWh
            $plant_info['remaining_permissible_outage_including_grid_mwh']['this_month'] = $plant_info['dependable_capacity']['this_month'] * (float) $plant_info['remaining_permissible_outage_including_grid']['this_month'] ;
            $plant_info['remaining_permissible_outage_including_grid_mwh']['last_month'] = $plant_info['dependable_capacity']['last_month'] * (float) $plant_info['remaining_permissible_outage_including_grid']['last_month'] ;
            $plant_info['remaining_permissible_outage_including_grid_mwh']['ytd'] = $plant_info['dependable_capacity']['ytd'] * (float) $plant_info['remaining_permissible_outage_including_grid']['ytd'] ;

            // Remaining Permissible Outage for this year  (excluding Grid) MWh
            $plant_info['remaining_permissible_outage_excluding_grid_mwh']['this_month'] = $plant_info['dependable_capacity']['this_month'] * (float) $plant_info['remaining_permissible_outage_excluding_grid']['this_month'] ;
            $plant_info['remaining_permissible_outage_excluding_grid_mwh']['last_month'] = $plant_info['dependable_capacity']['last_month'] * (float) $plant_info['remaining_permissible_outage_excluding_grid']['last_month'] ;
            $plant_info['remaining_permissible_outage_excluding_grid_mwh']['ytd'] = $plant_info['dependable_capacity']['ytd'] * (float) $plant_info['remaining_permissible_outage_excluding_grid']['ytd'] ;

            if($action == 'print') {
                return view('prints.plant-wise-outage', compact('plants', 'plant', 'from_date', 'to_date', 'plant_info'));
            }
        }

        return view('reports.plant-wise-outage', compact('plants', 'plant', 'from_date', 'to_date', 'plant_info'));
    }

    /**
     * Show info of overall outage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $action
     * @return \Illuminate\Http\Response
     */
    public function getOverallOutage(Request $request, $action = null)
    {
        $plants = Plant::get(['id', 'name', 'permissible_outage', 'energy_meter_multification_factor']);
        $plant_info = [];

        if(array_filter(array_except($request->all(), ['_token']))) {

            if($request->has('date_range')) {
                $date_range_arr  = explode(' - ',$request->date_range);
                $from_date = \Carbon::parse($date_range_arr[0])->format('Y-m-d');
                $to_date = \Carbon::parse($date_range_arr[1])->format('Y-m-d');

                $day_no = Carbon::parse($from_date)->diffInDays($to_date);

                $this_month_start_date = Carbon::parse($from_date)->startOfMonth()->format('Y-m-d');
                $this_month_end_date = Carbon::parse($from_date)->endOfMonth()->format('Y-m-d');
                $this_month_day_no = Carbon::parse($this_month_start_date)->diffInDays($this_month_end_date);

                $last_month_start_date = Carbon::parse($from_date)->subMonth()->startOfMonth()->format('Y-m-d');
                $last_month_end_date = Carbon::parse($from_date)->subMonth()->endOfMonth()->format('Y-m-d');
                $last_month_day_no = Carbon::parse($last_month_start_date)->diffInDays($last_month_end_date);

                $ytd_start_date = Carbon::parse($from_date)->startOfYear()->format('Y-m-d');
                $ytd_day_no = Carbon::parse($ytd_start_date)->diffInDays($to_date);
            }

            if($plants->isEmpty() || empty($date_range_arr)) {

                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            foreach ($plants as $plant) {

                // Dependable Capacity as per PPA
                $plant_info[$plant->id]['dependable_capacity'] = $plant->getDependableCapacity();

                // net generation 
                $thisMonthlyEnergyMeterBillingInfo = $plant->getMonthlyEnergyMeterBillingInfo($this_month_start_date, $this_month_end_date);

                $plant_info[$plant->id]['net_generation'] = $thisMonthlyEnergyMeterBillingInfo ? $thisMonthlyEnergyMeterBillingInfo['total_export_kwh'] : 0;

                // Running hours
                $plant_info[$plant->id]['engine-running'] = $plant->getMonthlyEngineActivityStateHours('engine-running', $this_month_start_date, $this_month_end_date);

                // Schedule outage
                $plant_info[$plant->id]['schedule-outage'] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $this_month_start_date, $this_month_end_date);

                // Maintenance outage
                $plant_info[$plant->id]['maintenance-outage'] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $this_month_start_date, $this_month_end_date);

                // Force outage
                $plant_info[$plant->id]['force-outage'] = $plant->getMonthlyEngineActivityStateHours('force-outage', $this_month_start_date, $this_month_end_date);

                // This month outage including Grid
                $plant_info[$plant->id]['this_month_outage_including_grid'] =   str_replace(':', '.', getTotalTime([$plant_info[$plant->id]['schedule-outage'], $plant_info[$plant->id]['maintenance-outage'], $plant_info[$plant->id]['force-outage']]));

                // This month  outage  Excluding  Grid
                $plant_info[$plant->id]['this_month_outage_excluding_grid'] = 
                    str_replace(':', '.', getTotalTime([$plant_info[$plant->id]['schedule-outage'], $plant_info[$plant->id]['maintenance-outage']]));

                // Reverse Shutdown
                $engine_activities_time = [];

                // this month reverse shut down
                $engine_running_this_month_arr = explode('.', $plant_info[$plant->id]['engine-running']);
                $engine_activities_time[] = $engine_running_this_month_arr[0].':'.$engine_running_this_month_arr[1];

                $schedule_outage_this_month_arr = explode('.', $plant_info[$plant->id]['schedule-outage']);
                $engine_activities_time[] = $schedule_outage_this_month_arr[0].':'.$schedule_outage_this_month_arr[1];

                $maintenance_outage_this_month_arr = explode('.', $plant_info[$plant->id]['maintenance-outage']);
                $engine_activities_time[] = $maintenance_outage_this_month_arr[0].':'.$maintenance_outage_this_month_arr[1];

                $force_outage_this_month_arr = explode('.', $plant_info[$plant->id]['force-outage']);
                $engine_activities_time[] = $force_outage_this_month_arr[0].':'.$force_outage_this_month_arr[1];

                $this_month_total_activity_time = getTotalTime($engine_activities_time);
                $this_month_total_time =  $this_month_day_no * 24 .':00';    
                $plant_info[$plant->id]['reverse_shut_down'] = getDiffTimeFromTimeValue($this_month_total_activity_time, $this_month_total_time);

                // Total Permissible Outage
                $plant_info[$plant->id]['total_permissible_outage'] = $plant->permissible_outage;

                // YTD Outage (Including Grid) This month
                $ytd_outage_including_grid_this_month_arr = [];
                $ytd_outage_including_grid_this_month_arr[] = $plant->getMonthlyEngineActivityStateHours('schedule-outage', $ytd_start_date, $this_month_end_date);
                $ytd_outage_including_grid_this_month_arr[] = $plant->getMonthlyEngineActivityStateHours('maintenance-outage', $ytd_start_date, $this_month_end_date);
                $ytd_outage_including_grid_this_month_arr[] = $plant->getMonthlyEngineActivityStateHours('force-outage', $ytd_start_date, $this_month_end_date);

                $plant_info[$plant->id]['ytd_outage_including_grid'] = str_replace(':', '.', getTotalTime($ytd_outage_including_grid_this_month_arr));

                // YTD Outage (Excluding Grid)
                array_pop($ytd_outage_including_grid_this_month_arr);

                $plant_info[$plant->id]['ytd_outage_excluding_grid'] = str_replace(':', '.', getTotalTime($ytd_outage_including_grid_this_month_arr));

                // Remaining Permissible Outage for this year  (including Grid) for this month
                $total_permissible_outage_this_month = $plant_info[$plant->id]['total_permissible_outage'].':00';
                $ytd_outage_including_grid_this_month = str_replace('.', ':', $plant_info[$plant->id]['ytd_outage_including_grid']);

                $plant_info[$plant->id]['remaining_permissible_outage_including_grid'] = getDiffTimeFromTimeValue($ytd_outage_including_grid_this_month, $total_permissible_outage_this_month);

                // Remaining Permissible Outage for this year  (excluding Grid) for this month
                $ytd_outage_excluding_grid_this_month = str_replace('.', ':', $plant_info[$plant->id]['ytd_outage_excluding_grid']);

                $plant_info[$plant->id]['remaining_permissible_outage_excluding_grid'] = getDiffTimeFromTimeValue($ytd_outage_excluding_grid_this_month, $total_permissible_outage_this_month);

                // Remaining Permissible Outage for this year  (including Grid) MWh
                $plant_info[$plant->id]['remaining_permissible_outage_including_grid_mwh'] = $plant_info[$plant->id]['dependable_capacity'] * (float) $plant_info[$plant->id]['remaining_permissible_outage_including_grid'] ;

                // Remaining Permissible Outage for this year  (excluding Grid) MWh
                $plant_info[$plant->id]['remaining_permissible_outage_excluding_grid_mwh'] = $plant_info[$plant->id]['dependable_capacity'] * (float) $plant_info[$plant->id]['remaining_permissible_outage_excluding_grid'];
            }

            if($action == 'print') {
                return view('prints.overall-outage', compact('plants', 'plant', 'from_date', 'to_date', 'plant_info'));
            }
        }

        return view('reports.overall-outage', compact('plants', 'plant', 'from_date', 'to_date', 'plant_info'));
    }

    /**
     * Show info of plant wise equipment running hour
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $action
     * @return \Illuminate\Http\Response
     */
    public function getPlantWiseEquipmentRunningHour(Request $request, $action = null)
    {
        if(array_filter(array_except($request->all(), ['_token']))) {

            $plantEquipments = DB::table('equipment AS e')
                    ->join('plant_equipments AS pe', 'e.id', '=', 'pe.equipment_id')
                    ->join('equipment_running_hours AS erh', 'pe.id', '=', 'erh.plant_equipment_id')
                    ->select(
                        'e.name', 
                        'erh.start_value',
                        'erh.end_value',
                        'erh.diff_value'
                    )
                    ->where('pe.plant_id', trim($request->plant_id))
                    ->where('erh.running_year', trim($request->running_year))
                    ->where('erh.running_month', trim($request->running_month))
                    ->get();

            if($plantEquipments->isEmpty()) {
                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            $plant = Plant::find($request->plant_id);

            if($action == 'print') {
                return view('prints.plant-wise-equipment-running-hour', compact('plants', 'plantEquipments', 'plant'));
            }
        }

        $plants = Plant::getDropDownList();

        return view('reports.plant-wise-equipment-running-hour', compact('plants', 'plantEquipments'));
    }

    /**
     * Show info of overall equipment running hour
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $action
     * @return \Illuminate\Http\Response
     */
    public function getOverallEquipmentRunningHour(Request $request, $action = null)
    {
        $equipments = Equipment::all();
        $plants = Plant::get(['id', 'name']);
        $plant_info = [];

        if(array_filter(array_except($request->all(), ['_token']))) {

            $plantEquipments = DB::table('equipment AS e')
                    ->join('plant_equipments AS pe', 'e.id', '=', 'pe.equipment_id')
                    ->join('equipment_running_hours AS erh', 'pe.id', '=', 'erh.plant_equipment_id')
                    ->select(
                        'pe.plant_id', 
                        'pe.equipment_id', 
                        'erh.end_value',
                        'erh.diff_value'
                    )
                    ->where('erh.running_year', trim($request->running_year))
                    ->where('erh.running_month', trim($request->running_month))
                    ->get();

            if($plantEquipments->isEmpty()) {
                session()->flash('toast', toastMessage('No data found!', 'error'));

                return back()->withInput();
            }

            foreach($plantEquipments as $plantEquipment) {
                $plant_info[$plantEquipment->plant_id][$plantEquipment->equipment_id]['end_value'] = $plantEquipment->end_value;
                $plant_info[$plantEquipment->plant_id][$plantEquipment->equipment_id]['diff_value'] = $plantEquipment->diff_value;
            }
        }

        return view('reports.overall-equipment-running-hour', compact('equipments', 'plants', 'plant_info'));
    }

    /**
     * Show info of purchase order
     *
     * @param  \Illuminate\Http\Request $request
     * @param $action
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseOrderInfo(Request $request, $action = null)
    {
        $data = [];
        $report_title_arr = [];
        $report_title = '';
        $query_string = '';

        if(array_filter(array_except($request->all(), ['_token']))) {
            
            $query = DB::table('purchase_orders AS po')
                ->leftJoin('items AS i', 'po.item_id', '=', 'i.id')
                ->leftJoin('item_groups AS ig', 'i.item_group_id', '=', 'ig.id')
                ->select(
                    'po.po_number',
                    'po.po_date',
                    'po.approved_by',
                    DB::raw('
                        COUNT(po.item_id) AS item_qty, 
                        SUM(po.pr_qty) AS pr_qty, 
                        SUM(po.po_qty) AS po_qty, SUM(po_price) AS po_price, 
                        SUM(po.po_value) AS po_value
                    ')
                ); 

            if($request->filled('po_date')) {
                $query->where('po.po_date', trim($request->po_date));
                    
                $data['po_date'] = trim($request->po_date);
            }

            if($request->filled('plant_id')) {
                $query->where('po.plant_id', 'LIKE', '%'. trim($request->plant_id) . '%');

                $data['plant_id'] = trim($request->plant_id);
            }

            if($request->filled('item_group_id')) {
                $query->where('i.item_group_id', 'LIKE', '%'. trim($request->item_group_id) . '%');

                $data['item_group_id'] = trim($request->item_group_id);
            }  

            if($request->filled('source_type')) {
                $query->where('i.source_type', trim($request->source_type));
                $data['source_type'] = trim($request->source_type);
            }      

            $purchaseOrders = $query->groupBy('po.po_number')->latest('po.id')->get();

            if($data) {
                $i = 1;
                foreach($data as $key=>$value) { 
                   $report_title_arr[] = trim(ucfirst(str_replace('id', '', str_replace('_', ' ', $key))));
                    $query_string .=$key .'='. $value .'&';
                    $i++;
                }

                $report_items = implode(', ', $report_title_arr);
                
                if(str_contains($report_items, ', ')) {
                    $report_title = str_replace_last(', ', ' and ', $report_items) . ' wise Report';
                }else {
                    $report_title = $report_items . ' wise Report';
                }
                
                $query_string = rtrim($query_string, '&');
            }
        }

        if(!empty($purchaseOrders) && $action == 'print') {
            return view('prints.purchase', compact('purchaseOrders', 'report_title'));
        }

        $itemGroups = ItemGroup::getDropDownList();
        $plants = Plant::getDropDownList();

        return view('reports.purchase', compact('purchaseOrders', 'itemGroups', 'plants', 'query_string'));
    }

    /**
     * Show info of purchase order
     *
     * @param  \Illuminate\Http\Request $request
     * @param $action
     * @return \Illuminate\Http\Response
     */
    public function getConsumptionInfo(Request $request, $action = null)
    {
        $data = [];
        $report_title_arr = [];
        $report_title = '';
        $query_string = '';

        if(array_filter(array_except($request->all(), ['_token']))) {
            
            $query = DB::table('issue_registers AS ir')
                ->leftJoin('items AS i', 'ir.item_id', '=', 'i.id')
                ->leftJoin('item_groups AS ig', 'i.item_group_id', '=', 'ig.id')
                ->select(
                    'ir.issue_code',
                    'ir.issue_date',
                    DB::raw('
                        COUNT(ir.item_id) AS item_qty, 
                        SUM(ir.required_qty) AS req_qty, 
                        SUM(ir.approved_qty) as apv_qty, 
                        SUM(ir.issue_qty) as issue_qty
                    ')
                ); 

            if($request->filled('issue_date')) {
                $query->where('ir.issue_date', trim($request->issue_date));
                    
                $data['issue_date'] = trim($request->issue_date);
            }

            if($request->filled('plant_id')) {
                $query->where('ir.plant_id', 'LIKE', '%'. trim($request->plant_id) . '%');

                $data['plant_id'] = trim($request->plant_id);
            }

            if($request->filled('item_group_id')) {
                $query->where('i.item_group_id', 'LIKE', '%'. trim($request->item_group_id) . '%');

                $data['item_group_id'] = trim($request->item_group_id);
            }  

            if($request->filled('source_type')) {
                $query->where('i.source_type', trim($request->source_type));
                $data['source_type'] = trim($request->source_type);
            }      

            $issueRegisters = $query->groupBy('ir.issue_code')->latest('ir.id')->get();
        }

        $report_info = getReportInfo($data);

        $report_title = $report_info['report_title'];
        $query_string = $report_info['query_string'];

        if(!empty($issueRegisters) && $action == 'print') {
            return view('prints.consumption', compact('issueRegisters', 'report_title'));
        }

        $itemGroups = ItemGroup::getDropDownList();
        $plants = Plant::getDropDownList();

        return view('reports.consumption', compact('issueRegisters', 'itemGroups', 'plants', 'query_string'));
    }

    /**
     * Get pending purchase requisition
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getPendingPurchaseRequistionInfo(Request $request, $action = null)
    {
        $data = [];
        $report_title_arr = [];
        $report_title = '';
        $query_string = '';

        if(array_filter(array_except($request->all(), ['_token']))) {

            $purchaseOrder = PurchaseOrder::wherePlantId($request->plant_id)->first(['id']);
            $query = DB::table('purchase_requisitions AS pr')
                ->leftJoin('purchase_orders AS po', 'pr.id', '=', 'po.purchase_requisition_id')
                ->join('items AS i', 'pr.item_id', '=', 'i.id')
                ->select(
                    'pr.id', 
                    'pr.requisition_code', 
                    'pr.remaining_qty',
                    'i.id AS item_id',
                    'i.name',
                    'i.avg_price',
                    'i.pipeline_qty AS pr_qty',
                    DB::raw('
                        IFNULL(po.po_price, 0) as last_price,
                        IFNULL(i.avg_price*i.pipeline_qty, 0) as pr_value
                    ')
                )->where('pr.remaining_qty', '>', 0);

            if($request->filled('plant_id')) {
                $query->where('pr.plant_id', trim($request->plant_id));

                $data['plant_id'] = trim($request->plant_id);
            }

            if($request->filled('item_group_id')) {
                $query->where('i.item_group_id', trim($request->item_group_id));

                $data['item_group_id'] = trim($request->item_group_id);
            }  

            if($request->filled('source_type')) {
                $query->where('i.source_type', trim($request->source_type));
                $data['source_type'] = trim($request->source_type);
            } 

            if($purchaseOrder) {
                $query->latest('po.po_price');
            }     

            $purchaseRequisitions = $query->groupBy('pr.id')->get();
        }
        
        $report_info = getReportInfo($data);

        $report_title = $report_info['report_title'];
        $query_string = $report_info['query_string'];

        if(!empty($purchaseRequisitions) && $action == 'print') {
            return view('prints.pending-purchase-requisition', compact('purchaseRequisitions', 'report_title'));
        }

        $itemGroups = ItemGroup::getDropDownList();
        $plants = Plant::getDropDownList();

        return view('reports.pending-purchase-requisition', compact('itemGroups', 'plants', 'purchaseRequisitions', 'query_string'));
    }
}
