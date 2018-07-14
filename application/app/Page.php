<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $timestamps = false;

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Get Page List
     */
    public static function getDropDownList($prepend = true)
    {
        $pages = Page::pluck('name', 'id');

        if($prepend) {
            $pages->prepend('Select a page', '');
        }

        return $pages->all();
    }
}
