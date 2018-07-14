<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    public static function getDropDownList($prepend = true, $except_id = null)
    {
    	$roles = Role::pluck('name', 'id');

    	if($prepend) {
    		$roles->prepend('Select a role', '');
    	}

    	if($except_id) {
    		$roles = $roles->except($except_id);
    	}

    	return $roles->all();
    }
}
