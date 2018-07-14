@extends('layouts.master')

@section('title') Fuel Inventory Details @endsection 
@section('page_title') Fuel Inventory Carrings @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Fuel Inventory Details</h4>
	</div>
	<div class="card-body">
		<div class="details-info">
			<div class="row">
				<div class="col-md-6">	
		        	<strong>Plant: </strong>{{  $fuelInventory->plant->name }} <br>	
		        	<strong>Fuel Type: </strong>{{  $fuelInventory->fuelType->name }} <br>	
		        	<strong>Transaction Code:</strong> {{ strtoupper($fuelInventory->transaction_code) }} <br>
		        	<strong>Transaction Date:</strong> {!! Carbon::parse($fuelInventory->transaction_date)->format('d M, Y') !!} 
		        </div>
		        <div class="col-md-6">
		        	@php $unit = $fuelInventory->fuelType->unit @endphp 
		        	<strong>Invoice Quantity ({{ $unit }}):</strong> {!! number_format($fuelInventory->invoice_quantity, 2) !!} <br>
		        	<strong>Received Quantity ({{ $unit }}):</strong> {!! number_format($fuelInventory->received_quantity, 2) !!} <br>
		        	<strong>Transport Loss(%):</strong> {!! $fuelInventory->transport_loss ? $fuelInventory->transport_loss : 'N/A' !!} <br>
		        	<strong>Available Stock ({{ $unit }}):</strong> {!! $fuelInventory->available_stock ? $fuelInventory->available_stock : 'N/A' !!} <br>
		        	<strong>Consumption ({{ $unit }}):</strong> {!! $fuelInventory->consumption ? $fuelInventory->consumption : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $fuelInventory->created_at->format('d M, Y H:i A') }}
				</div>
			</div>	
		</div>
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('fuel-inventories/' . $fuelInventory->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('fuel-inventories/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


