<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Engine extends Model
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

        $engine = Self::whereNumber($number)->first(['number']);

        if($engine) {
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
    public static function getDropDownList($prepend = true, $plant_id = 0)
    {
        $query = Self::query();

        if($plant_id) {
            $query->wherePlantId($plant_id);
        }

        $engines = $query->pluck('name', 'id');

        if($prepend) {
            $engines->prepend('Select a engine', '');
        }

        return $engines->all();
    }

    /**
     * Get daily gross generation
     *
     * @param date
     * @return float 
     */
    public function getDailyGrossGenerationInfo($date)
    {
        $daily_gross_generation_info = [];
        $dailyEngineGrossGeneration = DailyEngineGrossGeneration::whereEngineId($this->id)->whereOperationDate($date)->first();

        $daily_gross_generation_info['gross_generation'] = $dailyEngineGrossGeneration ? ($dailyEngineGrossGeneration->to_day_gross_generation - $dailyEngineGrossGeneration->last_day_gross_generation) : 0;
        $daily_gross_generation_info['fuel_consumption'] = $dailyEngineGrossGeneration ? $dailyEngineGrossGeneration->fuel_consumption  : 0;
         
        return $daily_gross_generation_info;
    }
}
