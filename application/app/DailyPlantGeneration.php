<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyPlantGeneration extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function plant()
    {
    	return $this->belongsTo(Plant::class);
    }

    public function dailyEnergyMeterBillings()
    {
    	return $this->hasMany(DailyEnergyMeterBilling::class);
    }

    public function dailyEngineActivities()
    {
    	return $this->hasMany(DailyEngineActivity::class);
    }

    public function dailyEngineGrossGenerations()
    {
    	return $this->hasMany(DailyEngineGrossGeneration::class);
    }
}
