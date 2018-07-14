<table class="table table-striped table-bordered">
	<thead class="table-dark">
		<tr>
			<th width="9%">PR. Code</th>
			<th width="12%">Item</th>
			<th width="14%" class="text-right">Avg Price</th>
			<th width="12%" class="text-right">Last Price</th>
			<th width="12%" class="text-right">Pr Qty</th>
			<th width="12%" class="text-right">Pr Value</th>
			<th width="12%" class="text-right">Remaining Qty</th>
		</tr>
	</thead>
	
	<tbody>
		@if(!empty($purchaseRequisitions))
		@forelse($purchaseRequisitions as $purchaseRequisition)
		<tr>
			<td>{{ strtoupper($purchaseRequisition->requisition_code) }}</td>
			<td>{{ $purchaseRequisition->name }}</td>
			<td class="text-right">{{ $purchaseRequisition->avg_price }}</td>
			<td class="text-right">{{ $purchaseRequisition->last_price }}</td>
			<td class="text-right">{{ $purchaseRequisition->pr_qty }}</td>
			<td class="text-right">{{ $purchaseRequisition->pr_value }}</td>
			<td class="text-right">{{ $purchaseRequisition->remaining_qty }}</td>
		</tr>
		@empty
		<tr>
			<td colspan="7" align="center">No Record Found!</td>
		</tr>
		@endforelse
		@else
		<tr>
			<td colspan="7" align="center">No Record Found!</td>
		</tr>
		@endif
	</tbody>
</table>