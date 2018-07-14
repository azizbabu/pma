<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon;

class EngineGrossGeneration extends Model
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

    public function engine()
    {
        return $this->belongsTo(Engine::class);
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
		$engineGrossGeneration = Self::where('op_code', 'LIKE', '%'. $date_part . '%')->latest()->first(['op_code']);

		if($engineGrossGeneration) {
			$op_code = $date_part . sprintf("%02d",(int)str_replace($date_part, '', $engineGrossGeneration->op_code) + 1);
		}else {
			$op_code  = $date_part . sprintf("%02d", 1);
		}

        return $op_code;
    }

    /**
     * Get the start time value.
     *
     * @param  string  $value
     * @return string
     */
    public function getStartTimeAttribute($value)
    {
        return \Carbon::createFromTimeString($value, env('APP_TIMEZONE'))->format('H:i');
    }

    /**
     * Get the end time value.
     *
     * @param  string  $value
     * @return string
     */
    public function getEndTimeAttribute($value)
    {
        return \Carbon::createFromTimeString($value, env('APP_TIMEZONE'))->format('H:i');
    }

    /**
     * Get the diff time value.
     *
     * @param  string  $value
     * @return string
     */
    public function getDiffTimeAttribute($value)
    {
        return \Carbon::createFromTimeString($value, env('APP_TIMEZONE'))->format('H:i');
    }
}
