<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemLedger extends Model
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
    public static function getIssueCode()
    {
        $year = date('Y');
		$month = date('m');
		$day = date('d');
		$date_part = sprintf('%s%02s%02s', $year, $month, $day);
		$itemLedger = Self::where('issue_code', 'LIKE', '%'. $date_part . '%')->latest()->first(['issue_code']);

		if($itemLedger) {
			$issue_code = $date_part . sprintf("%04d",(int)str_replace($date_part, '', $itemLedger->issue_code) + 1);
		}else {
			$issue_code  = $date_part . sprintf("%04d", 1);
		}

        return $issue_code;
    }
}
