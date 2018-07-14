<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelType extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    /**
     * Get Unique Code
     */
    public static function getCode()
    {
        $current_datetime = strtotime(date('Y-m-d h:i:s'));
        $code = substr(md5($current_datetime), 0, 6);

        $plant = FuelType::whereCode($code)->first(['code']);

        if($plant) {
            Self::getCode();
        }else {
            $code = $code;
        }

        return $code;
    }

     /**
     * Get Dropdown List
     *
     * @return array
     */
    public static function getDropDownList($prepend = true, $except_ids = null)
    {
        $query = Self::query();

        $fuelTypes = $query->pluck('name', 'id');

        if($except_ids) {
            $fuelTypes = $fuelTypes->except($except_ids);
        }

        if($prepend) {
            $fuelTypes->prepend('Select a fuel type', '');
        }

        return $fuelTypes->all();
    }
}
