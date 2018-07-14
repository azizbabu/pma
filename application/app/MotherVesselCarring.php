<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotherVesselCarring extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function motherVessel()
    {
        return $this->belongsTo(MotherVessel::class);
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

        $motherVesselCarring = Self::whereCode($code)->first(['code']);

        if($motherVesselCarring) {
            Self::getCode();
        }else {
            $code = $code;
        }

        return $code;
    }

    /**
     * Get Unique LC Number
     *
     * @return string lcNumber
     */
    public static function getLcNumber()
    {
        $current_datetime = strtotime(date('Y-m-d h:i:s'));
        $lcNumber = substr(md5($current_datetime), 0, 8);

        $motherVesselCarring = Self::whereLcNumber($lcNumber)->first(['lc_number']);

        if($motherVesselCarring) {
            Self::getCode();
        }else {
            $lcNumber = $lcNumber;
        }

        return $lcNumber;
    }
}
