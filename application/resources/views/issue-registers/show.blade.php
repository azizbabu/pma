@extends('layouts.master')

@section('title') Issue Register Details @endsection 
@section('page_title') Issue Registers @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Issue Register Details</h4>
	</div>
	<div class="card-body">
		<div class="details-info">
        	<div class="row">
        		<div class="col-sm-6">
        			<strong>Plant: </strong>{{ $issueRegister->plant->name }} <br>	
        			<strong>Spare Parts Type:</strong> {{ config('constants.spare_parts_types.'.$issueRegister->spare_parts_type) }} <br>
        			<strong>Source Type:</strong> {{ config('constants.po_source_types.'.$issueRegister->source_type) }} <br>
        		</div>
        		<div class="col-sm-6">
        			<strong>Issue Code:</strong> {{ strtoupper($issueRegister->issue_code) }} <br>
        			<strong>Issue Date:</strong> {{ Carbon::parse($issueRegister->issue_date)->format('d M, Y') }} <br>
        		</div>
        	</div>

        	<div class="table-responsive margin-top-20">
			    <table class="table table-hover table-striped table-vertical-middle">
			        <thead class="thead-dark">
			            <tr>
			                <th>Item</th>
			                <th width="12%" class="text-right">Avg. Price</th>
			                <th width="12%" class="text-right">Safety Stock</th>
			                <th width="12%" class="text-right"><span data-toggle="tooltip" title="Balance Stock">Bal. Stock</span></th>
			                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Required Qty" class="text-right">Req. Qty</span></th>
			                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Approved Qty" class="text-right">Apv. Qty</span></th>
			                <th width="10%" class="text-right">Issue Qty</th>
			                <th width="15%" >Remarks</th>
			            </tr>
			        </thead>
			        <tbody>
			        	@foreach($issueRegisters as $issueRegister)
			        	@php
						$item = $issueRegister->item
			        	@endphp
			            <tr>
			                <td>{{ $item->name }}</td>
			                <td class="text-right">
			                    ৳{{ number_format($item->avg_price, 2) }}
			                </td>
			                <td class="text-right">{{ $issueRegister->item_safety_stock_qty }}</td>
			                <td class="text-right">{{ $issueRegister->balance_stock_qty }}</td>
			                <td class="text-right">{{ $issueRegister->required_qty }}</td>
			                <td class="text-right">{{ $issueRegister->approved_qty }}</td>
			                <td class="text-right">
			                    {{ $issueRegister->issue_qty }}
			                </td>
			                <td>
			                    {{ $issueRegister->remarks ? $issueRegister->remarks : 'N/A' }}
			                </td>
			            </tr>
			            @endforeach
			        </tbody>
			    </table>
			</div>
        </div>
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('issue-registers/' . $issueRegister->issue_code . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('issue-registers/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


