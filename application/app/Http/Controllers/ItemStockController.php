<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemGroup;
use App\Plant;
use Carbon, DB;

class ItemStockController extends Controller
{
    /**
     * Show information of item stock
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $action = null)
    {
    	$remarks = 'N/A';
    	$data = [];
    	$report_title_arr = [];
    	$report_title = '';
    	$query_string = '';

    	if(array_filter(array_except($request->all(), ['_token']))) {
    		
	    	$query = DB::table('items AS i')
	    	->leftJoin('issue_registers AS ir', 'i.id', '=', 'ir.item_id')
	    	->select(
	    		'i.code',
	    		'i.source_type',
	    		'i.name',
	    		'i.avg_price',
	    		'i.safety_stock_qty',
	    		DB::raw('i.opening_qty+i.receive_qty AS opening_receiving_qty'),
	    		'i.issue_qty',
	    		DB::raw('SUM(i.opening_qty+i.receive_qty+i.return_qty-i.issue_qty) AS present_stock_qty')

		    );

		    if($request->filled('item_group_id')) {
		    	$query->where('i.item_group_id', trim($request->item_group_id));
		    	$data['item_group_id'] = trim($request->item_group_id);
		    }

		    if($request->filled('plant_id')) {
		    	$query->where('i.plant_id', trim($request->plant_id));
		    	$data['plant_id'] = trim($request->plant_id);
		    }

		    if($request->filled('name')) {
		    	$query->where('i.name', 'LIKE', '%' . trim($request->name) .'%');
		    	$data['name'] = trim($request->name);
		    }

		    if($request->filled('source_type')) {
		    	$query->where('i.source_type', trim($request->source_type));
		    	$data['source_type'] = trim($request->source_type);
		    }
		    
		    if($request->filled('stock_type')) {
		    	$stock_type = trim($request->stock_type);

		    	if($stock_type == 'under-stock') {
		    		// $query->havingRaw('present_stock_qty < i.safety_stock_qty');
		    		$query->whereRaw('i.opening_qty+i.receive_qty+i.return_qty-i.issue_qty < i.safety_stock_qty');
		    		$remarks = 'Under Stock';
		    	}else if($stock_type == 'over-stock') {
		    		$query->whereRaw('i.opening_qty+i.receive_qty+i.return_qty-i.issue_qty >= 5*i.safety_stock_qty');
		    		$remarks = 'Over Stock';
		    	}else if($stock_type == 'zero-stock') {
		    		$query->whereRaw('i.opening_qty+i.receive_qty+i.return_qty-i.issue_qty = 0');
		    		$remarks = 'Zero Stock';
		    	}else {
		    		$query->whereRaw('i.opening_qty+i.receive_qty+i.return_qty-i.issue_qty > 0')
		    			->whereRaw('i.opening_qty+i.receive_qty+i.return_qty-i.issue_qty > i.safety_stock_qty')
		    			->whereRaw('i.opening_qty+i.receive_qty+i.return_qty-i.issue_qty < 5*i.safety_stock_qty');
		    	}
		    	$data['stock_type'] = trim($request->stock_type);
		    }

		    if($request->filled('item_moving') && $request->filled('moving_time')) {
		    	$item_moving = trim($request->item_moving);
		    	$moving_time_arr = explode('-', trim($request->moving_time));

		    	$time_length_amount = $moving_time_arr[0];
		    	$time_length_name = $moving_time_arr[1];

		    	if($time_length_name == 'month') {
		    		$from_date = Carbon::now()->subMonth($time_length_amount)->format('Y-m-d');
		    	}else if($time_length_name == 'year') {
		    		$from_date = Carbon::now()->subYear($time_length_amount)->format('Y-m-d');
		    	}
				$to_date = date('Y-m-d');

				$query->whereRaw('ir.issue_date >="'. $from_date .'" AND ir.issue_date <="'. $to_date.'"');		    	
		    	
		    	$slow_moving_qty = $request->filled('slow_moving_qty') ? trim($request->slow_moving_qty) : config('constants.slow_moving_item_no');
		    	// dd($slow_moving_qty);
		    	if($item_moving == 'fastmoving') {
		    		$query->havingRaw('SUM(ir.issue_qty) > '. $slow_moving_qty);
		    	}else if($item_moving == 'slowmoving') {
		    		$query->havingRaw('SUM(ir.issue_qty) <= '. $slow_moving_qty .' AND SUM(ir.issue_qty) > 0');
		    	}else if($item_moving == 'nonmoving') {
		    		$query->havingRaw('SUM(ir.issue_qty) = 0');
		    	}

		    	$data['item_moving'] = trim($request->item_moving);
		    	$data['moving_time'] = trim($request->moving_time);

		    	if($request->filled('slow_moving_qty')) {
		    		$data['slow_moving_qty'] = trim($request->slow_moving_qty);
		    	}
		    }

		    $items = $query->groupBy('i.id')->orderBy('i.name')->get();

		    if($data) {
				$i = 1;
				foreach($data as $key=>$value) {
					
					if($key != 'slow_moving_qty') {
						$report_title_arr[] = trim(ucfirst(str_replace('id', '', str_replace('_', ' ', $key))));
					}

					$query_string .=$key .'='. $value .'&';
					$i++;
				}

				$report_items = implode(', ', $report_title_arr);
				
				if(str_contains($report_items, ', ')) {
					$report_title = str_replace_last(', ', ' and ', $report_items) . ' wise Report';
				}else {
					$report_title = $report_items . ' wise Report';
				}
				
				$query_string = rtrim($query_string, '&');
			}
		}

		if(!empty($items) && $action == 'print') {
	    	return view('prints.item-stock', compact('items', 'remarks', 'report_title'));
	    }

    	$itemGroups = ItemGroup::getDropDownList();
    	$plants = Plant::getDropDownList();

	    return view('item-stock', compact('itemGroups', 'plants','items', 'remarks', 'query_string'));
    }
}
