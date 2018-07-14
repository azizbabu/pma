<table class="table table-bordered table-striped table-sm">
	<thead>
		<tr>
			<th class="text-center align-middle" colspan="5">Plant Equipment's Monthly Running Hours Data Sheet</th>
		</tr>
		<tr>
			<th class="text-center align-middle" rowspan="2" width="7%">Serial No</th>
			<th class="align-middle" rowspan="2">Equipment Name</th>
			<th class="text-center align-middle" colspan="3">{{ Carbon::parse(request()->running_year .'-' . request()->running_month)->format('M-Y') }}</th>
		</tr>
		<tr>
			<th class="text-center align-middle">Starting of the month</th>
			<th class="text-center align-middle">End of the month</th>
			<th class="text-center align-middle">Rh our of this month</th>
		</tr>
	</thead>
	<tbody>
		@php $i=1 @endphp
		@foreach($plantEquipments as $plantEquipment)
		<tr>
			<th class="text-center align-middle">{{ $i++ }}</th>
			<td class="align-middle">{{ $plantEquipment->name }}</td>
			<td class="text-center align-middle">{{ $plantEquipment->start_value }}</td>
			<td class="text-center align-middle">{{ $plantEquipment->end_value }}</td>
			<td class="text-center align-middle">{{ $plantEquipment->diff_value }}</td>
		</tr>
		@endforeach
	</tbody>
</table>