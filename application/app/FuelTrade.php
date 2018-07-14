<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelTrade extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function terminal()
    {
    	return $this->belongsTo(Terminal::class);
    }

    public function party()
    {
    	return $this->belongsTo(Party::class);
    }
}
