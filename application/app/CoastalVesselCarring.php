<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoastalVesselCarring extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coastalVessel()
    {
        return $this->belongsTo(CoastalVessel::class);
    }

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function coastalVesselReceivings()
    {
        return $this->hasMany(CoastalVesselReceiving::class);
    }
    
    /**
     * Get Unique Code
     *
     * @return string $code
     */
    public static function getCode()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $date_part = sprintf('%s%02s%02s', $year, $month, $day);
        $energyGrossGeneration = Self::where('code', 'LIKE', '%'. $date_part . '%')->latest()->first(['code']);

        if($energyGrossGeneration) {
            $code = $date_part . sprintf("%02d",(int)str_replace($date_part, '', $energyGrossGeneration->code) + 1);
        }else {
            $code  = $date_part . sprintf("%02d", 1);
        }

        return $code;
    }

    public function getTotalLoadQty()
    {
        return $this->coastalVesselReceivings()->sum('load_qty');
    }
}
