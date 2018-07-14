<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyEngineActivity extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function engine()
    {
    	return $this->belongsTo(Engine::class);
    }
}
