<?php 

namespace App\Helpers\Classes\PermissionChecker;

use Illuminate\Http\Request;
use App\Page;
use DB;

class PermissionChecker {

	/**
	 * Check whether there is a permission for current request
	 *
	 * @param \Illuminate\Http\Request $request;
	 * @return true|false
	 */
	public static hasPermission(Request $request)
	{
		$request_root_path = array_first(explode('/', $request->path()));

		$permission = DB::table('permissions as pr')
			->join('pages as pg', 'pr.page_id', '=', 'pg.id')
			->where('pg.link', 'LIKE', '%'. $page_root_path . '%')
			->first(['pg.name']);

		return $permission ? true : false;
	}
}