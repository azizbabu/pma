<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terminal extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tanks()
    {
        return $this->hasMany(Tank::class);
    }
    
    /**
     * Get Unique Code
     */
    public static function getCode()
    {
        $current_datetime = strtotime(date('Y-m-d h:i:s'));
        $code = substr(md5($current_datetime), 0, 6);

        $plant = Terminal::whereCode($code)->first(['code']);

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
        $terminals = Terminal::pluck('name', 'id');

        if($prepend) {
            $terminals->prepend('Select a terminal', '');
        }

        return $terminals->all();
    }
}
