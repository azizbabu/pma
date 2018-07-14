<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon, DB;

class Plant extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function engines()
    {
        return $this->hasMany(Engine::class);
    }

    public function meters()
    {
        return $this->hasMany(Meter::class);
    }

    public function engineGrossGenerations()
    {
        return $this->hasMany(EngineGrossGeneration::class);
    }

    public function dailyPlantGenerations()
    {
        return $this->hasMany(DailyPlantGeneration::class);
    }

    public function dailyEngineGrossGenerations()
    {
        return $this->hasMany(DailyEngineGrossGeneration::class);
    }

    public function dailyEngineActivities()
    {
        return $this->hasMany(DailyEngineActivity::class);
    }

    public function dailyEnergyMeterBillings()
    {
        return $this->hasMany(DailyEnergyMeterBilling::class);
    }

    public function dailyHfoLubeModules()
    {
        return $this->hasMany(DailyHfoLubeModule::class);
    }
    
    /**
     * Get Unique Code
     */
    public static function getCode()
    {
        $current_datetime = strtotime(date('Y-m-d h:i:s'));
        $code = substr(md5($current_datetime), 0, 6);

        $plant = Plant::whereCode($code)->first(['code']);

        if($plant) {
            Self::getCode();
        }else {
            $code = $code;
        }

        return $code;
    }

    /**
     * Get dropdown list
     *
     * @return array
     */ 
    public static function getDropDownList($prepend = true)
    {
        $plants = Self::pluck('name', 'id');

        if($prepend) {
            $plants->prepend('Select a plant', '');
        }

        return $plants->all();
    }

    /**
     * Get opening stock
     *
     * @param string $from_date
     * @return float
     */
    public function getOpeningStock($from_date)
    {
        $coastalVesselCarringReceivedQty = CoastalVesselCarring::wherePlantId($this->id)->where('received_date', '<', $from_date)->sum('received_quantity');

        $dailyPlantGenerationFuelConsumption = DailyPlantGeneration::wherePlantId($this->id)->where('operation_date', '<', $from_date)->sum('plant_fuel_consumption');

        return ($coastalVesselCarringReceivedQty - $dailyPlantGenerationFuelConsumption);
    }

    /**
     * Get fuel quantity
     *
     * @return float
     */
    public function getFuelQuantity($from_date, $to_date)
    {
        $coastalVessel = CoastalVesselCarring::select(
            DB::raw('
                IFNULL(SUM(invoice_quantity), 0) AS invoice_quantity,
                IFNULL(SUM(received_quantity), 0) AS received_quantity,
                IFNULL(SUM(waiting_quantity), 0) AS waiting_quantity
            ')
        )->wherePlantId($this->id)
        ->whereRaw('received_date >="'.$from_date.'" AND received_date <="'.$to_date.'"')
        ->first();

        return $coastalVessel;
    }

    /**
     * Get monthly plant generation info
     *
     * @param $from_date
     * @param $to_date
     * @return object
     */
    public function getMonthlyPlantGenerationInfo($from_date, $to_date)
    {
        $dailyPlantGenerations = $this->dailyPlantGenerations()->select(
                DB::raw('
                    IFNULL(SUM(plant_fuel_consumption), 0) AS plant_fuel_consumption,
                    IFNULL(SUM(reference_lhv), 0) AS reference_lhv,
                    IFNULL(SUM(aux_boiler_hfo_consumption), 0) AS aux_boiler_hfo_consumption
                ')
            )->whereRaw('operation_date >="'.$from_date.'" AND operation_date <="'.$to_date.'"')
            ->first();

        return $dailyPlantGenerations;

    }

    /**
     * Get daily auxilary boiler hfo consumption
     *
     * @return float
     */
    public function getDailyAuxilaryBoilerHfoConsumption($date)
    {
        $dailyPlantGeneration = $this->dailyPlantGenerations()
            ->select(
                DB::raw('IFNULL(SUM(aux_boiler_hfo_consumption), 0) AS aux_boiler_hfo_consumption')
            )->whereOperationDate($date)
            ->first();

        return $dailyPlantGeneration->aux_boiler_hfo_consumption;
    }

    /**
     * Get monthly auxilary boiler hfo consumption
     *
     * @return float
     */
    public function getMonthlyAuxilaryBoilerHfoConsumption($from_date, $to_date)
    {
        if($from_date == $to_date) {
            return $this->getDailyAuxilaryBoilerHfoConsumption($from_date);
        }

        $dailyEngineGrossGeneration = $this->dailyPlantGenerations()
            ->select(
                DB::raw('IFNULL(SUM(aux_boiler_hfo_consumption), 0) AS aux_boiler_hfo_consumption')
            )->whereRaw('operation_date >="'.$from_date.'" AND operation_date <="'.$to_date.'"')
            ->first();

        return $dailyEngineGrossGeneration->aux_boiler_hfo_consumption;
    }

    public function getFuelConsumption($from_date, $to_date)
    {
        $dailyPlantGenerationFuelConsumption = DailyPlantGeneration::wherePlantId($this->id)
            ->whereRaw('operation_date >="'.$from_date.'" AND operation_date <="'.$to_date.'"')
        ->sum('plant_fuel_consumption');

        return $dailyPlantGenerationFuelConsumption;
    }

    /**
     * Get daily fuel consumption flowmeter
     *
     * @return float
     */
    public function getDailyHfoLubeModuleConsumptionFlowmeter($date)
    {
        $dailyHfoLubeModules = $this->dailyHfoLubeModules()
            ->select(
                DB::raw('
                    IFNULL(SUM(hfo), 0) AS hfo,
                    IFNULL(SUM(lube_oil), 0) AS lube_oil
                ')
            )->whereOperationDate($date)->first();

        return $dailyHfoLubeModules;
    }

    /**
     * Get monthly fuel consumption flowmeter
     *
     * @param string $from_date
     * @param string $to_date
     * @return float
     */
    public function getMonthlyHfoLubeModuleConsumptionInfo($from_date, $to_date)
    {
        if($from_date == $to_date) {
            return $this->getDailyHfoLubeModuleConsumptionFlowmeter($from_date);
        }

        $dailyHfoLubeModules = $this->dailyHfoLubeModules()
            ->select(
                DB::raw('
                    IFNULL(SUM(hfo), 0) AS hfo,
                    IFNULL(SUM(lube_oil), 0) AS lube_oil
                ')
            )->whereRaw('operation_date >="'.$from_date.'" AND operation_date <="'.$to_date.'"')
            ->first();

        return $dailyHfoLubeModules;
    }

    public function getDailyGrossGeneration($date)
    {
        $dailyEngineGrossGenerationAmount = 0;
        
        $dailyEngineGrossGenerations = $this->dailyEngineGrossGenerations()->whereOperationDate($date)->get();

        if($dailyEngineGrossGenerations->isNotEmpty()) {
            foreach($dailyEngineGrossGenerations as $dailyEngineGrossGeneration) {
                $dailyEngineGrossGenerationAmount += $dailyEngineGrossGeneration->to_day_gross_generation - $dailyEngineGrossGeneration->last_day_gross_generation;
            }
        }

        return $dailyEngineGrossGenerationAmount;
    }

    /**
     * Get monthly engine gross generation
     *
     * @param string $from_date
     * @param string $to_date
     * @return float
     */
    public function getMonthlyEngineGrossGeneration($from_date, $to_date)
    {
        $dailyEngineGrossGenerationAmount = 0;
        $engines = $this->engines()->pluck('id');

        if($engines->isEmpty()) {
            return $dailyEngineGrossGenerationAmount;
        }

        if($from_date == $to_date) {
            return $this->getDailyGrossGeneration($from_date);
        }

        $startEngineGrossGeneration = [];
        $endEngineGrossGeneration = [];
        
        $engine_number = count($engines);

        $startDailyEngineGrossGenerations = $this->dailyEngineGrossGenerations()->where('operation_date', '<=', $from_date)->latest()->take($engine_number)->get();

        if($startDailyEngineGrossGenerations->isNotEmpty()) {
            foreach($startDailyEngineGrossGenerations as $startDailyEngineGrossGeneration) {
                $startEngineGrossGeneration[$startDailyEngineGrossGeneration->engine_id] = $startDailyEngineGrossGeneration->last_day_gross_generation;
            }
        }else {
            foreach ($engines as $engine_id) {
                $startEngineGrossGeneration[$engine_id] = 0;
            }
        }

        $endDailyEngineGrossGenerations = $this->dailyEngineGrossGenerations()->where('operation_date', '<=', $to_date)->latest()->take($engine_number)->get();

        if($endDailyEngineGrossGenerations->isNotEmpty()) {
            foreach($endDailyEngineGrossGenerations as $endDailyEngineGrossGeneration) {
                $endEngineGrossGeneration[$endDailyEngineGrossGeneration->engine_id] = $endDailyEngineGrossGeneration->to_day_gross_generation;
            }
        }else {
            foreach ($engines as $engine_id) {
                $endEngineGrossGeneration[$engine_id] = 0;
            }
        }

        foreach ($engines as $engine_id) {
            $dailyEngineGrossGenerationAmount += $endEngineGrossGeneration[$engine_id] - $startEngineGrossGeneration[$engine_id];
        }

        return $dailyEngineGrossGenerationAmount;
    }

    public function getDailyTurbineGeneration($date)
    {
        $dailyEngineGrossGenerationAmount = 0;
        $dailyTurbineGeneration = DB::table('daily_engine_gross_generations AS degg')
                ->join('engines AS e', 'degg.engine_id', '=', 'e.id')
                ->select(
                    DB::raw('
                        (IFNULL(degg.to_day_gross_generation, 0) -
                        IFNULL(degg.last_day_gross_generation, 0)) AS turbine_generation
                    ')
                )
                ->where('degg.plant_id', $this->id)
                ->where('degg.operation_date', $date)
                ->where('e.name', 'LIKE', '%Turbine%')
                ->first();

        if($dailyTurbineGeneration) {
            $dailyPlantGeneration = $this->dailyPlantGenerations()->whereOperationDate($date)->first();

            $dailyEngineGrossGenerations = $dailyPlantGeneration->dailyEngineGrossGenerations;

            if($dailyEngineGrossGenerations->isNotEmpty()) {
                foreach($dailyEngineGrossGenerations as $dailyEngineGrossGeneration) {
                    $dailyEngineGrossGenerationAmount += ($dailyEngineGrossGeneration->to_day_gross_generation - $dailyEngineGrossGeneration->last_day_gross_generation);
                }
            }
        }

        return $dailyEngineGrossGenerationAmount ? round(($dailyTurbineGeneration->turbine_generation/$dailyEngineGrossGenerationAmount)*100, 2) : 'NA';
    }

    /**
     * Get monthly turbine generation
     *
     * @param string $from_date
     * @param string $to_date
     * @return float|string
     */
    public function getMonthlyTurbineGeneration($from_date, $to_date)
    {
        $startDailyTurbineGeneration = DB::table('daily_engine_gross_generations AS degg')
            ->join('engines AS e', 'degg.engine_id', '=', 'e.id')
            ->where('degg.plant_id', $this->id)
            ->where('degg.operation_date', '<=', $from_date)
            ->where('e.name', 'LIKE', '%Turbine%')
            ->latest('degg.id')
            ->first(['last_day_gross_generation']);

        if(!$startDailyTurbineGeneration) {
            $startDailyTurbineGeneration = DB::table('daily_engine_gross_generations AS degg')
            ->join('engines AS e', 'degg.engine_id', '=', 'e.id')
            ->where('degg.plant_id', $this->id)
            ->where('degg.operation_date', '>=', $from_date)
            ->where('e.name', 'LIKE', '%Turbine%')
            ->first(['last_day_gross_generation']);
        }

        $endDailyTurbineGeneration = DB::table('daily_engine_gross_generations AS degg')
            ->join('engines AS e', 'degg.engine_id', '=', 'e.id')
            ->where('degg.plant_id', $this->id)
            ->where('degg.operation_date', '<=', $to_date)
            ->where('e.name', 'LIKE', '%Turbine%')
            ->latest('degg.id')
            ->first(['to_day_gross_generation']);

        return $startDailyTurbineGeneration && $endDailyTurbineGeneration ? $endDailyTurbineGeneration->to_day_gross_generation - $startDailyTurbineGeneration->last_day_gross_generation : 0;
    }

    public function getDependableCapacity()
    {
        return $this->id == 3 ? 53.972 : 55;
    }

    public function getGuaranteedCapacity()
    {
        return $this->id == 3 ? 53.972*24 : 55*24;
    }

    /**
     * Get daily net generation
     *
     * @param string $date
     * @return float 
     */
    public function getDailyEnergyMeterBillingInfo($date)
    {
        $arr = [];
        $meter_export_kwh = [];
        $meter_import_kwh = [];
        $meter_export_kvarh = [];
        $meter_import_kvarh = [];

        $dailyEnergyMeterBillings = $this->dailyEnergyMeterBillings()->whereOperationDate(trim($date))->get();

        if($dailyEnergyMeterBillings->isNotEmpty()) {
            foreach($dailyEnergyMeterBillings as $dailyEnergyMeterBilling) {
                $meter_export_kwh[] = $dailyEnergyMeterBilling->export_to_day_kwh - $dailyEnergyMeterBilling->export_last_day_kwh;
                $meter_import_kwh[] = $dailyEnergyMeterBilling->import_to_day_kwh - $dailyEnergyMeterBilling->import_last_day_kwh;
                $meter_export_kvarh[] = $dailyEnergyMeterBilling->export_to_day_kvarh - $dailyEnergyMeterBilling->export_last_day_kvarh;
                $meter_import_kvarh[] = $dailyEnergyMeterBilling->import_to_day_kvarh - $dailyEnergyMeterBilling->import_last_day_kvarh;
            }

            $total_export_kwh = 0;
            foreach($meter_export_kwh as $value) {
                $total_export_kwh += $value;
            }

            $arr['total_export_kwh'] = $total_export_kwh * $this->energy_meter_multification_factor;

            $total_import_kwh = 0;
            foreach($meter_import_kwh as $value) {
                $total_import_kwh += $value;
            }
            $arr['total_import_kwh'] = $total_import_kwh * $this->energy_meter_multification_factor;

            $total_export_kvarh = 0;
            foreach($meter_export_kvarh as $value) {
                $total_export_kvarh += $value;
            }
            $arr['total_export_kvarh'] = $total_export_kvarh * $this->energy_meter_multification_factor;

            $total_import_kvarh = 0;
            foreach($meter_import_kvarh as $value) {
                $total_import_kvarh += $value;
            }
            $arr['total_import_kvarh'] = $total_import_kvarh * $this->energy_meter_multification_factor;

            // $arr['total_import_kwh'] = ($engine_import_kwh[0] + $engine_import_kwh[1]) * $this->energy_meter_multification_factor;
            // $arr['total_export_kvarh'] = ($engine_export_kvarh[0] + $engine_export_kvarh[1]) * $this->energy_meter_multification_factor;
            // $arr['total_import_kvarh'] = ($engine_import_kvarh[0] + $engine_import_kvarh[1]) * $this->energy_meter_multification_factor;
        }

        return $arr;
    }

    /**
     * Get daily net generation
     *
     * @param string $date
     * @return float 
     */
    public function getMonthlyEnergyMeterBillingInfo($start_date, $to_date)
    {
        if($start_date == $to_date) {
             return $this->getDailyEnergyMeterBillingInfo($start_date);
        }

        $arr = [];
        $meter_export_kwh = [];
        $meter_import_kwh = [];
        $meter_export_kvarh = [];
        $meter_import_kvarh = [];

        $meter_export_kwh_start = [];
        $meter_import_kwh_start = [];
        $meter_export_kvarh_start = [];
        $meter_import_kvarh_start = [];

        $meter_export_kwh_end = [];
        $meter_import_kwh_end = [];
        $meter_export_kvarh_end = [];
        $meter_import_kvarh_end = [];

        $meters = $this->meters()->pluck('id');
        $meter_number = count($meters);
  
        $startDailyEnergyMeterBillings = $this->dailyEnergyMeterBillings()->where('operation_date', '<=', $start_date)->latest()->take($meter_number)->get();

        // if($startDailyEnergyMeterBillings->isEmpty()) {
        //     $startDailyEnergyMeterBillings = $this->dailyEnergyMeterBillings()->where('operation_date', '>=', $start_date)->take($meter_number)->get();
        // }  

        if($startDailyEnergyMeterBillings->isNotEmpty()) {
            foreach($startDailyEnergyMeterBillings as $startDailyEnergyMeterBilling) {
                $meter_export_kwh_start[$startDailyEnergyMeterBilling->meter_id] = $startDailyEnergyMeterBilling->export_last_day_kwh;
                $meter_import_kwh_start[$startDailyEnergyMeterBilling->meter_id] = $startDailyEnergyMeterBilling->import_last_day_kwh;
                $meter_export_kvarh_start[$startDailyEnergyMeterBilling->meter_id] = $startDailyEnergyMeterBilling->export_last_day_kvarh;
                $meter_import_kvarh_start[$startDailyEnergyMeterBilling->meter_id] = $startDailyEnergyMeterBilling->import_last_day_kvarh;
            }
        }else {
            foreach($meters as $meter_id) {
                $meter_export_kwh_start[$meter_id] = 0;
                $meter_import_kwh_start[$meter_id] = 0;
                $meter_export_kvarh_start[$meter_id] = 0;
                $meter_import_kvarh_start[$meter_id] = 0;
            }
        }

        $endDailyEnergyMeterBillings = $this->dailyEnergyMeterBillings()->where('operation_date', '<=', $to_date)->latest()->take($meter_number)->get();  

        if($endDailyEnergyMeterBillings->isNotEmpty()) {
            foreach($endDailyEnergyMeterBillings as $endDailyEnergyMeterBilling) {
                $meter_export_kwh_end[$endDailyEnergyMeterBilling->meter_id] = $endDailyEnergyMeterBilling->export_to_day_kwh;
                $meter_import_kwh_end[$endDailyEnergyMeterBilling->meter_id] = $endDailyEnergyMeterBilling->import_to_day_kwh;
                $meter_export_kvarh_end[$endDailyEnergyMeterBilling->meter_id] = $endDailyEnergyMeterBilling->export_to_day_kvarh;
                $meter_import_kvarh_end[$endDailyEnergyMeterBilling->meter_id] = $endDailyEnergyMeterBilling->import_to_day_kvarh;
            }
        }else {
            foreach($meters as $meter_id) {
                $meter_export_kwh_end[$meter_id] = 0;
                $meter_import_kwh_end[$meter_id] = 0;
                $meter_export_kvarh_end[$meter_id] = 0;
                $meter_import_kvarh_end[$meter_id] = 0;
            }
        }

        foreach($meters as $meter_id) {
            $meter_export_kwh[] = $meter_export_kwh_end[$meter_id] - $meter_export_kwh_start[$meter_id];
            $meter_import_kwh[] = $meter_import_kwh_end[$meter_id] - $meter_import_kwh_start[$meter_id];
            $meter_export_kvarh[] = $meter_export_kvarh_end[$meter_id] - $meter_export_kvarh_start[$meter_id];
            $meter_import_kvarh[] = $meter_import_kvarh_end[$meter_id] - $meter_import_kvarh_start[$meter_id];
        }

        $total_export_kwh = 0;
        foreach($meter_export_kwh as $value) {
            $total_export_kwh += $value;
        }

        $arr['total_export_kwh'] = $total_export_kwh * $this->energy_meter_multification_factor;

        $total_import_kwh = 0;
        foreach($meter_import_kwh as $value) {
            $total_import_kwh += $value;
        }
        $arr['total_import_kwh'] = $total_import_kwh * $this->energy_meter_multification_factor;

        $total_export_kvarh = 0;
        foreach($meter_export_kvarh as $value) {
            $total_export_kvarh += $value;
        }
        $arr['total_export_kvarh'] = $total_export_kvarh * $this->energy_meter_multification_factor;

        $total_import_kvarh = 0;
        foreach($meter_import_kvarh as $value) {
            $total_import_kvarh += $value;
        }


        $arr['total_import_kvarh'] = $total_import_kvarh * $this->energy_meter_multification_factor;

        return $arr;
    }

    public function getPLFMonthToDate($start_date, $to_date)
    {
        $dailyEnergyMeterBillingsInfo = $this->getMonthlyEnergyMeterBillingInfo($start_date, $to_date);

        $plf = '';
        if($dailyEnergyMeterBillingsInfo) {
            $day_no = Carbon::parse($to_date)->day;

            $plf = 100 * $dailyEnergyMeterBillingsInfo['total_export_kwh']/($this->getGuaranteedCapacity() * $day_no);
        }

        return $plf;
    }

    /**
     * Get total used hours of engine state between two dates
     *
     * @param string $state_name
     * @param string $from_date
     * @param string $to_date
     * @return float
     */
    public function getMonthlyEngineActivityStateHours($state_name, $from_date, $to_date)
    {
        $diff_time_arr = [];
        $dailyEngineActivities = $this->dailyEngineActivities()
            ->whereActivityState($state_name)
            ->whereRaw('operation_date >="'.$from_date.'" AND operation_date <="'.$to_date.'"')
            ->whereTime('diff_time', '>', '00:00:00')
            ->get(['diff_time']);
            // ->toSql();
        if($dailyEngineActivities->isNotEmpty()) {
            foreach($dailyEngineActivities as $dailyEngineActivity) {
                $diff_time_arr[] = $dailyEngineActivity->diff_time;
            }
        }

        $totalTimeArr = explode(':', getTotalTime($diff_time_arr));
     
        $total_engine = $this->engines()->count();

        $total_time = (float) ($totalTimeArr[0] * 3600) + (float) ($totalTimeArr[1] * 60);

        $avg_total_time = floor($total_time/$total_engine);

        return gmdate("H.i", $avg_total_time);

        // return $totalTimeArr[0] . '.' . $totalTimeArr[1];
    }

    /**
     * Get Turbine engine activity state hour 
     *
     * @param string $state_name
     * @param string $from_date
     * @param string $to_date
     * @return string
     */
    public function getTurbineEngineActivityHours($state_name, $from_date, $to_date)
    {
        $diff_time_arr = [];
        $dailyEngineActivities = DB::table('daily_engine_activities AS dea')->join('plants as p', 'dea.plant_id', '=', 'p.id')
            ->join('engines as e', 'dea.engine_id', '=', 'e.id')
            ->where('dea.plant_id', $this->id)
            ->where('dea.activity_state', $state_name)
            ->whereRaw('operation_date >="'.$from_date.'" AND operation_date <="'.$to_date.'"')
            ->whereTime('diff_time', '>', '00:00:00')
            ->where('e.name', 'LIKE', '%Turbine%')
            ->get(['diff_time']);

        if($dailyEngineActivities->isEmpty()) {
            return 'N/A';
        }
        
        foreach($dailyEngineActivities as $dailyEngineActivity) {
            $diff_time_arr[] = $dailyEngineActivity->diff_time;
        }

        $totalTimeArr = explode(':', getTotalTime($diff_time_arr));

        $total_time = (float) $totalTimeArr[0] . '.' . (float) $totalTimeArr[1];
        $total_engine = $this->engines()->count();

        return $totalTimeArr[0] . '.' . $totalTimeArr[1];
    }

    /**
     * Get fuel inventory opening stock
     *
     * @param int $fuel_type_id
     * @param string $date
     * @return float
     */
    public function getFuelInventoryOpeningStock($fuel_type_id, $date)
    {
        $fuelInventory = FuelInventory::selectRaw('IFNULL((available_stock - consumption), 0) AS opening_stock')
            ->wherePlantId($this->id)
            ->whereFuelTypeId($fuel_type_id)
            ->where('transaction_date', '<', $date)
            ->first();

        return $fuelInventory ? $fuelInventory->opening_stock : 0;
    }

    /**
     * Get fuel inventory opening stock
     *
     * @param int $fuel_type_id
     * @param string $from_date
     * @param string $end_date
     * @return float
     */
    public function getMonthlyFuelInventoryInfo($fuel_type_id, $from_date, $to_date)
    {
        $fuelInventory = FuelInventory::selectRaw('
                IFNULL(SUM(invoice_quantity), 0) AS invoice_quantity,
                IFNULL(SUM(received_quantity), 0) AS received_quantity,
                IFNULL(SUM(available_stock), 0) AS available_stock,
                IFNULL(SUM(consumption), 0) AS consumption
            ')
            ->wherePlantId($this->id)
            ->whereFuelTypeId($fuel_type_id)
            ->whereRaw('transaction_date >="'.$from_date.'" AND transaction_date <="'.$to_date.'"')
            ->first();

        return $fuelInventory;
    }
}
