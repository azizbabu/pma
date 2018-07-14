<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnergyGrossGeneration extends Model
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

    public function meter()
    {
        return $this->belongsTo(Meter::class);
    }
    
    /**
     * Get Unique Code
     *
     * @return string 
     */
    public static function getOpCode()
    {
        $year = date('Y');
		$month = date('m');
		$day = date('d');
		$date_part = sprintf('%s%02s%02s', $year, $month, $day);
		$energyGrossGeneration = Self::where('op_code', 'LIKE', '%'. $date_part . '%')->latest()->first(['op_code']);

		if($energyGrossGeneration) {
			$op_code = $date_part . sprintf("%02d",(int)str_replace($date_part, '', $energyGrossGeneration->op_code) + 1);
		}else {
			$op_code  = $date_part . sprintf("%02d", 1);
		}

        return $op_code;
    }
}
