<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyTerminalStock extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}
