<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyEngineGrossGeneration extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function engine()
    {
    	return $this->belongsTo(Engine::class);
    }
}
