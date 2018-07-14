<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tank extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function terminal()
    {
    	return $this->belongsTo(Terminal::class);
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
        $current_datetime = strtotime(date('Y-m-d h:i:s'));
        $code = substr(md5($current_datetime), 0, 6);

        $tank = Tank::whereNumber($code)->first(['number']);

        if($tank) {
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
        $tanks = Self::pluck('number', 'id');

        if($prepend) {
            $tanks->prepend('Select a tank', '');
        }

        return $tanks->all();
    }
}
