<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoastalVesselReceiving extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coastalVesselCarring()
    {
        return $this->belongsTo(CoastalVesselCarring::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
    
    /**
     * Get Unique Code
     *
     * @return string $code
     */
    public static function getCvrNumber($coastalVesselCarring)
    {
        $coastalVesselReceiving = $coastalVesselCarring->coastalVesselReceivings()->latest()->first(['cvr_number']);
        if($coastalVesselReceiving) {

        	$cvr_number = substr($coastalVesselReceiving->cvr_number, 0, 10) . sprintf("%02d",(int)(substr($coastalVesselReceiving->cvr_number, -2, 2)) + 1); 
        }else {
        	$cvr_number = $coastalVesselCarring->code . '01';
        }

        return $cvr_number;
    }
}
