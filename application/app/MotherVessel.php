<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotherVessel extends Model
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

        $motherVessel = MotherVessel::whereCode($code)->first(['code']);

        if($motherVessel) {
            Self::getCode();
        }else {
            $code = $code;
        }

        return $code;
    }

    /**
     * Get Mother Vessel List
     *
     * @return array
     */
    public static function getDropDownList($prepend = true)
    {
        $mother_vessels = Self::pluck('name', 'id');

        if($prepend) {
            $mother_vessels->prepend('Select a mother vessel', '');
        }

        return $mother_vessels->all();
    }
}
