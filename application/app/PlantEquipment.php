<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlantEquipment extends Model
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
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dailyTerminalStocks()
    {
        return $this->hasMany(DailyTerminalStock::class);
    }
    
    /**
     * Get Unique Code
     *
     * @return string $code
     */
    public static function getCode()
    {
        $plantEquipment = Self::latest('id')->first(['code']);

        if($plantEquipment) {
            $code = (int)$plantEquipment->code + 1;
        }else {
            $code = 10001;
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
        $plantEquipments = Self::pluck('name', 'id');

        if($prepend) {
            $plantEquipments->prepend('Select a plant equipment', '');
        }

        return $plantEquipments->all();
    }
}
