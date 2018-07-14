<?php 

return [
	
	'module_icon_class'	=> [
		'Setup'	=> 'ti-light-bulb',
		'Fuel Management'	=> 'ti-crown',
		'O & M Management'	=> 'ti-spray',
		'Spare Parts Inventory'	=> 'ti-pencil-alt',
		'Report'	=> 'ti-pie-chart',
	],

	'item_source_types'	=> [
		'local'		=> 'Local',
		'import'	=> 'Import',
		'both'		=> 'Both',
	],

	'item_stock_types'	=> [
		'internal'	=> 'Internal',
		'external'	=> 'External',
	],

	'spare_parts_types' => [
		'me' 	=> 'ME',
		'ee' 	=> 'EE',
		'op' 	=> 'OP',
		'tools' => 'Tools',
		'others' => 'Others',
	],

	'po_source_types' => [
		'local'		=> 'Local',
		'import'	=> 'Import',
	],

	'purchase_orders_remarks'	=> [
		''	=> 'Select remarks',
		'Pending Qty' => 'Pending Qty',
		'No available in Market' => 'No available in Market',
		'High Price' => 'High Price',
		'Hold by User' => 'Hold by User',
		'Quality NG' => 'Quality NG',
	],

	'item_stock_types' =>[
		'' => 'Select stock type',
		'all' => 'All',
		'under-stock' => 'Under Stock',
		'over-stock' => 'Over Stock',
		'zero-stock' => 'Zero Stock'
	],

	'engine_activity_state' => [
		'engine-running'	=> 'Engine Running',
		'schedule-outage'	=> 'Schedule Outage',
		'maintenance-outage'	=> 'Maintenance Outage',
		'force-outage'	=> 'Forge Outage Grid',
	],

	'item_moving' => [
		'' => 'Select Moving State',
		'fastmoving'	=> 'Fastmoving',
		'slowmoving'	=> 'Slowmoving',
		'nonmoving'		=> 'nonmoving',
	],

	'slow_moving_item_no'	=> 5,

	'moving_time' => [
		'' => 'Select Moving Time',
		'3-month'	=> '3 Month',
		'6-month'	=> '6 Month',
		'1-year'	=> '1 Year',
	],

	'fuel_unit' => [
		'MT'	=> 'Metric Ton',
		'L'	=> 'Litre',
	],
];