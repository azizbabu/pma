<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoastalVessel extends Model
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

        $motherVessel = Self::whereCode($code)->first(['code']);

        if($motherVessel) {
            Self::getCode();
        }else {
            $code = $code;
        }

        return $code;
    }

    /**
     * Get Coastal Vessel List
     *
     * @return array
     */
    public static function getDropDownList($prepend = true)
    {
        $coastal_vessels = Self::pluck('name', 'id');

        if($prepend) {
            $coastal_vessels->prepend('Select a coastal vessel', '');
        }

        return $coastal_vessels->all();
    }
}
