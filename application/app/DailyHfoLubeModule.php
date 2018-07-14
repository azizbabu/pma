<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyHfoLubeModule extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function engine()
    {
    	return $this->belongsTo(Engine::class);
    }
}
