<?php 

use App\Module;
use App\Page;

function getMenus()
{
	$menus = '';

	if(Auth::check()) {
        
        $modules = Module::leftJoin('permissions as p' ,'modules.id', '=', 'p.module_id')
                ->select('modules.*')
                ->where(function($query) {
                    if(!isSuperAdmin()) {
                        $query->where('p.role_id', request()->user()->role_id);
                    }
                })
                ->groupBy('modules.id')
                ->get();

        if($modules->isNotEmpty()) {
            foreach($modules as $module) {
                
                if(!isSuperAdmin()) {
                    
                    // Get page ids from permissions table 
                    $page_ids = $module->permissions()->whereRoleId(request()->user()->role_id)
                        ->where(function($query) {
                            $query->where('can_create', true)
                                  ->orWhere('can_update', true)
                                  ->orWhere('can_delete', true)
                                  ->orWhere('can_view', true);
                        })
                        ->pluck('page_id');

                    // Get pages accrding to page ids
                    $pages = Page::whereIn('id', $page_ids)->get();
                }else {
                    $pages = $module->pages;
                }

                if($pages->isNotEmpty()) {
                	$menus .= '<li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="'.config('constants.module_icon_class.'.$module->name).'"></i> <span> '.$module->name.' </span> <span class="pull-right"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">';

                        foreach($pages as $page) {
                            $menus .= '<li><a href="'. url($page->link). '">'.$page->name.'</a></li>';
                        }
                        
                        $menus .= '</ul>
                    </li>';
                }else {
                	$menus .= '<li>
                        <a href="#/" class="waves-effect">
                            <i class="'.config('constants.module_icon_class.'.$module->name).'"></i>
                            <span>'. $module->name .'</span>
                        </a>
                    </li>';
                }
            }
        }
    }

    return $menus;
}