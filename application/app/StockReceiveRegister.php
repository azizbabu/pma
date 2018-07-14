<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockReceiveRegister extends Model
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

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    /**
     * Get Unique Code
     *
     * @return string $code
     */
    public static function getReceiveCode()
    {
        $year = date('Y');
		$month = date('m');
		$day = date('d');
		$date_part = sprintf('%s%02s%02s', $year, $month, $day);
		$stockReceiveRegister = Self::where('receive_code', 'LIKE', '%'. $date_part . '%')->latest()->first(['receive_code']);

		if($stockReceiveRegister) {
			$receive_code = $date_part . sprintf("%04d",(int)str_replace($date_part, '', $stockReceiveRegister->receive_code) + 1);
		}else {
			$receive_code  = $date_part . sprintf("%04d", 1);
		}

        return $receive_code;
    }
}
