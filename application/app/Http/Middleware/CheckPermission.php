<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!isSuperAdmin()) {
            $request_root_path = array_first(explode('/', $request->path()));

            $permission = DB::table('permissions as pr')
                ->join('pages as pg', 'pr.page_id', '=', 'pg.id')
                ->where('pr.role_id', $request->user()->role_id)
                ->where('pg.link', 'LIKE', '%'. $request_root_path . '%')
                ->first([
                    'pg.name',
                    'pr.can_create',
                    'pr.can_update',
                    'pr.can_delete',
                    'pr.can_view',
                ]);

            if(!$permission) {
                abort(403, 'Unauthorized Access');
            }



            $actionName = array_last(explode('@', class_basename(request()->route()->getActionName())));

            if($actionName == 'create' && !$permission->can_create) {
                $unauthorizedAccess = true;
            }elseif($actionName == 'edit' && !$permission->can_update) {
                $unauthorizedAccess = true;
            }elseif($actionName == 'delete' && !$permission->can_delete) {
                $unauthorizedAccess = true;
            }elseif($actionName == 'show' && !$permission->can_view) {
                $unauthorizedAccess = true;
            }else {
                $unauthorizedAccess = false;
            }

            if($unauthorizedAccess) {
                abort(403, 'Unauthorized Access');
            }

        }

        return $next($request);
    }
}
