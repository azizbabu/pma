@extends('layouts.master')

@section('title') Stock Receive Registers Details @endsection 
@section('page_title') Stock Receive Registerss @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Stock Receive Registers Details</h4>
	</div>
	<div class="card-body">
		<div class="details-info">
        	<div class="row">
        		<div class="col-sm-6">
        			<strong>Plant: </strong>{{ $stockReceiveRegister->plant->name }} <br>	
        			<strong>P.O Number:</strong> {{ strtoupper($stockReceiveRegister->po_number) }} <br>
        		</div>
        		<div class="col-sm-6">
        			<strong>Receive Code:</strong> {{ strtoupper($stockReceiveRegister->receive_code) }} <br>
        			<strong>Receive Date:</strong> {{ Carbon::parse($stockReceiveRegister->po_date)->format('d M, Y') }} <br>
        		</div>
        	</div>
        </div>

        <div class="table-responsive margin-top-20">
        	<table class="table table-striped">
        		<thead class="table-dark">
        			<tr>
        				<th>SL.</th>
        				<th>PR No</th>
        				<th>Code</th>
        				<th>Item</th>
        				<th class="text-right">PO Qty</th>
        				<th class="text-right">GRN Qty</th>
        				<th>Remarks</th>
        			</tr>
        		</thead>
        		<tbody>
        		@php 
				$i = 1;
        		@endphp
        		@foreach($stockReceiveRegisters as $stockReceiveRegister)
					@php
					$item = $stockReceiveRegister->item;
					@endphp
        			<tr>
        				<td>{{ $i++ }}</td>
        				<td>{{ $stockReceiveRegister->requisition_code }}</td>
        				<td>{{ $item->code }}</td>
        				<td>{{ $item->name }}</td>
        				<td class="text-right">{{ $stockReceiveRegister->po_qty }}</td>
        				<td class="text-right">{{ $stockReceiveRegister->grn_qty }}</td>
        				<td>{{ $stockReceiveRegister->remarks ? $stockReceiveRegister->remarks : 'N/A'}}</td>
        			</tr>
        		@endforeach
        		</tbody>
        	</table>
        </div>
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('stock-receive-registers/' . $stockReceiveRegister->receive_code . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('stock-receive-registers/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


