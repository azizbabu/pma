<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $timestamps = false;

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
    	return $this->hasMany(Page::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
    * Get module list
    */
    public static function getDropDownList($prepend = true)
    {
        $modules = Module::pluck('name', 'id');

        if($prepend) {
            $modules->prepend('Select a module', '');
        }

        return $modules->all();
    }
}
