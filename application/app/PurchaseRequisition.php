<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
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
     * @return string 
     */
    public static function getRequisitionCode()
    {
        $year = date('Y');
		$month = date('m');
		$day = date('d');
		$date_part = sprintf('%s%02s%02s', $year, $month, $day);
		$purchaseRequisition = Self::where('requisition_code', 'LIKE', '%'. $date_part . '%')->latest()->first(['requisition_code']);

		if($purchaseRequisition) {
			$requisition_code = $date_part . sprintf("%04d",(int)str_replace($date_part, '', $purchaseRequisition->requisition_code) + 1);
		}else {
			$requisition_code  = $date_part . sprintf("%04d", 1);
		}

        return $requisition_code;
    }

    /**
     * Get Dropdown List
     *
     * @return array
     */
    public static function getDropDownList($prepend = true)
    {
        $items = Self::distinct('requisition_code')
            ->latest()->pluck('requisition_code', 'requisition_code');

        if($prepend) {
            $items->prepend('Select a pr. code', '');
        }

        return $items->all();
    }

    /**
     * Get dropdown list
     *
     * @return array
     */ 
    public static function getItemDropDownList($prepend = true, $requisition_code)
    {
        $items = Self::join('items AS i', 'purchase_requisitions.item_id', '=', 'i.id')
            ->where('purchase_requisitions.requisition_code', $requisition_code)
            ->pluck('i.name', 'i.id');

        if($prepend) {
            $items->prepend('Select a item', '');
        }

        return $items->all();
    }
}
