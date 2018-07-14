<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
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

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }
    
    /**
     * Get Unique Code
     *
     * @return string $code
     */
    public static function getPoNumber()
    {
        $year = date('Y');
		$month = date('m');
		$day = date('d');
		$date_part = sprintf('%s%02s%02s', $year, $month, $day);
		$purchaseOrder = Self::where('po_number', 'LIKE', '%'. $date_part . '%')->latest()->first(['po_number']);

		if($purchaseOrder) {
			$po_number = $date_part . sprintf("%04d",(int)str_replace($date_part, '', $purchaseOrder->po_number) + 1);
		}else {
			$po_number  = $date_part . sprintf("%04d", 1);
		}

        return $po_number;
    }

    /**
     * Get dropdown list
     *
     * @return array
     */ 
    public static function getDropDownList($prepend = true)
    {
        $purchaseOrders = Self::distinct('po_number')->pluck('po_number', 'po_number');

        if($prepend) {
            $purchaseOrders->prepend('Select a po number', '');
        }

        return $purchaseOrders->all();
    }
}
