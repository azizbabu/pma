@extends('layouts.master')

@section('title') Edit Item Ledger @endsection 
@section('page_title') Item Ledgers @endsection

@section('content')

<div class="container">
	{!! Form::model($itemLedger, array('url' => 'item-ledgers/'.$itemLedger->id, 'role' => 'form', 'id'=>'item-ledger-edit-form', 'method' => 'PUT')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Item Ledger</h4>
			</div>
			<div class="card-body">
				<div class="row">
				    <div class="col-md-12">{!! validationHints() !!}</div>
				</div>
				<div class="details-info margin-top-10">
		        	<div class="row">
		        		<div class="col-sm-6">
		        			<strong>Issue Code: </strong>{{ strtoupper($itemLedger->issue_code) }} <br>
		            		<strong>Issue Date: </strong>{{ Carbon::parse($itemLedger->issue_date)->format('d M, Y') }} <br>
		        		</div>
		        		<div class="col-sm-6">
		        			<strong>Plant: </strong>{{ $itemLedger->plant->name }} <br>
		            		<strong>Item: </strong>{{ $itemLedger->item->name }} <br>
		        		</div>
		        	</div>
		        </div>

				<div class="row margin-top-20">
					<div class="col-md-6">
				        <div class="form-group">
				            <label for="receive_qty" class="control-label">Receive Qty {!! validation_error($errors->first('receive_qty'),'receive_qty') !!}</label>
				            {!! Form::number('receive_qty', null, ['class'=>'form-control', 'placeholder' => 'Enter Receive Quantity', 'id' => 'receive_qty ']) !!}
				        </div>
				    </div>
				    <div class="col-md-6">
				        <div class="form-group">
				            <label for="issue_qty" class="control-label">Issue Qty {!! validation_error($errors->first('issue_qty'),'issue_qty') !!}</label>
				            {!! Form::number('issue_qty', null, ['class'=>'form-control', 'placeholder' => 'Enter Issue Quantity', 'id' => 'issue_qty ']) !!}
				        </div>
				    </div>
				</div>
				<div class="row">
					<div class="col-md-6">
				        <div class="form-group">
				            <label for="remarks" class="control-label">Remarks </label>
				            {!! Form::textarea('remarks', null, ['class'=>'form-control', 'placeholder' => 'Enter Remarks', 'id' => 'remarks', 'size' => '30x2']) !!}
				        </div>
				    </div>
				</div>
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



