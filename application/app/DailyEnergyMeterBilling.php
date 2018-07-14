<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyEnergyMeterBilling extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function meter()
    {
    	return $this->belongsTo(Meter::class);
    }
}
