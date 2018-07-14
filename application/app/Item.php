<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function itemGroup()
    {
        return $this->belongsTo(ItemGroup::class);
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
    public static function getCode()
    {
        $current_datetime = strtotime(date('Y-m-d h:i:s'));
        $code = substr(md5($current_datetime), 0, 6);

        $item = Self::whereCode($code)->first(['code']);

        if($item) {
            Self::getCode();
        }else {
            $code = $code;
        }

        return $code;
    }

    /**
     * Get Unique PR Number
     *
     * @return string $code
     */
    public static function getPrNumber()
    {
        $pr_number = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 6);;

        $item = Self::wherePrNumber($pr_number)->first(['pr_number']);

        if($item) {
            Self::getPrNumber();
        }else {
            $pr_number = $pr_number;
        }

        return $pr_number;
    }

    /**
     * Get Dropdown List
     *
     * @return array
     */
    public static function getDropDownList($prepend = true, $plant_id = 0)
    {
        $query = Self::query();

        if($plant_id) {
            $query->wherePlantId($plant_id);
        }

        $items = $query->pluck('name', 'id');

        if($prepend) {
            $items->prepend('Select a item', '');
        }

        return $items->all();
    }

    /**
     * Get balance stock
     *
     * @return int 
     */
    public function getBalanceStockQty()
    {
        return ($this->opening_qty + $this->receive_qty + $this->return_qty - $this->issue_qty);
    }
}
