<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model
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

        $plant = Party::whereCode($code)->first(['code']);

        if($plant) {
            Self::getCode();
        }else {
            $code = $code;
        }

        return $code;
    }

    /**
    * Get terminal list
    */
    public static function getDropDownList($prepend = true)
    {
        $parties = Self::pluck('name', 'id');

        if($prepend) {
            $parties->prepend('Select a party', '');
        }

        return $parties->all();
    }
}
