<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemGroup extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    /**
     * Get Unique Code
     *
     * @return string $code
     */
    public static function getCode()
    {
        $current_datetime = strtotime(date('Y-m-d h:i:s'));
        $code = substr(md5($current_datetime), 0, 6);

        $itemGroup = Self::whereCode($code)->first(['code']);

        if($itemGroup) {
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
        $item_groups = Self::pluck('name', 'id');

        if($prepend) {
            $item_groups->prepend('Select a item group', '');
        }

        return $item_groups->all();
    }
}
