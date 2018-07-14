<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelInventory extends Model
{
	use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function plant()
    {
    	return $this->belongsTo(Plant::class);
    }

    public function fuelType()
    {
    	return $this->belongsTo(FuelType::class);
    }

    /**
     * Get Unique transaction_code
     *
     * @return string $transaction_code
     */
    public static function getTransactionCode()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $date_part = sprintf('%s%02s%02s', $year, $month, $day);
        $fuelInventory = Self::where('transaction_code', 'LIKE', '%'. $date_part . '%')->latest()->first(['transaction_code']);

        if($fuelInventory) {
            $transaction_code = $date_part . sprintf("%02d",(int)str_replace($date_part, '', $fuelInventory->transaction_code) + 1);
        }else {
            $transaction_code  = $date_part . sprintf("%02d", 1);
        }

        return $transaction_code;
    }

    /**
     * Get Opening Stock
     *
     * @param int $plant_id
     * @param string $date
     * @return float
     */
    public static function getOpeningStock($plant_id, $fuel_type_id, $date)
    {
    	$fuelInventoryOld = FuelInventory::selectRaw('
				IFNULL(SUM(available_stock), 0) AS available_stock,
				IFNULL(SUM(consumption), 0) AS consumption
    		')->wherePlantId($plant_id)
    		->whereFuelTypeId($fuel_type_id)
    		->where('transaction_date', '<', $date)
    		->first();

        return $fuelInventoryOld ? ($fuelInventoryOld->available_stock - $fuelInventoryOld->consumption) : 0;
    }
}
