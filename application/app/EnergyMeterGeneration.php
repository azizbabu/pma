<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnergyMeterGeneration extends Model
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
    public static function getGenCode()
    {
        $year = date('Y');
		$month = date('m');
		$day = date('d');
		$date_part = sprintf('%s%02s%02s', $year, $month, $day);
		$energyMeterGeneration = Self::where('gen_code', 'LIKE', '%'. $date_part . '%')->latest()->first(['gen_code']);

		if($energyMeterGeneration) {
			$gen_code = $date_part . sprintf("%02d",(int)str_replace($date_part, '', $energyMeterGeneration->gen_code) + 1);
		}else {
			$gen_code  = $date_part . sprintf("%02d", 1);
		}

        return $gen_code;
    }
}
