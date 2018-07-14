<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meter extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
    
    /**
     * Get Unique Engine Number
     */
    public static function getNumber()
    {
        $current_datetime = strtotime(date('Y-m-d h:i:s'));
        $number = substr(md5($current_datetime), 0, 6);

        $meter = Self::whereNumber($number)->first(['number']);

        if($meter) {
            Self::getNumber();
        }else {
            $number = $number;
        }

        return $number;
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
            $plants->prepend('Select a meter', '');
        }

        return $plants->all();
    }
}
