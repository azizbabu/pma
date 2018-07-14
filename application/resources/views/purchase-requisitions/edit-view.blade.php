@extends('layouts.master')

@section('title') Edit View Purchase Requisition @endsection 
@section('page_title') Purchase Requisitions @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'purchase-requisitions', 'role' => 'form', 'id'=>'purchase-requisition-edit-view-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Purchase Requisition</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				    <table class="table table-hover table-striped table-vertical-middle table-sm">
				        <thead class="thead-dark">
				            <tr>
				                <th>Item</th>
				                <th width="10%" class="text-right">Avg. Price</th>
				                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Safety Stock">Saf. Stock</span></th>
				                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Present Stock">Pre. Stock</span></th>
				                <th width="10%"><span data-toggle="tooltip" title="Pipeline Qty">Pip. Qty</span></th>
				                <th width="10%"><span data-toggle="tooltip" title="Required Qty">Req. Qty</span></th>
				                <th width="12%" class="text-right">Total Value</th>
				                <th width="15%" >Remarks</th>
				                <th width="5%"></th>
				            </tr>
				        </thead>
				        <tbody>
				        	{{--  
				            <tr>
				                <td>
				                    {!! Form::select('itm_id', $items, null, ['class'=>'form-control chosen-select', 'onchange' => 'setItemValue(this);']) !!}
				                    {!! Form::hidden('item_name', '') !!}
				                </td>
				                <td class="text-right">
				                    ৳0.00
				                    {!! Form::hidden('avg_price_main', 0, ['class' => 'form-control']) !!}
				                </td>
				                <td>
				                    {!! Form::number('item_safety_stock_qty_main', 0
				                    , ['class' => 'form-control']) !!}
				                </td>
				                <td>
				                    {!! Form::number('present_stock_qty_main', 1
				                    , ['class' => 'form-control']) !!}
				                </td>
				                <td>
				                    {!! Form::number('pipeline_qty_main', 1
				                    , ['class' => 'form-control']) !!}
				                </td>
				                <td>
				                    {!! Form::number('required_qty_main', 1
				                    , ['class' => 'form-control']) !!}
				                </td>
				                <td class="text-right">৳0.00</td>
				                <td>
				                    {!! Form::text('remarks_main', null, ['class' => 'form-control', 'placeholder' => 'Enter Remarks']) !!}
				                </td>
				                <td>
				                    <a class="btn btn-xs btn-info" onclick="javascript:addItem(this);"><i class="fa fa-plus-circle"></i> ADD</a>
				                </td>
				            </tr>
				            --}}
				            @php
							$sum_total_value = 0;
				            @endphp
				            @foreach($purchaseRequisitions as $purchaseRequisition)
				            <tr>
				            	<td>{{ $purchaseRequisition->item->name }}</td>
				            	<td>৳{{ $purchaseRequisition->item_avg_price }}</td>
				            	<td>
				                    {!! Form::number('item_safety_stock_qty[]', $purchaseRequisition->item_safety_stock_qty
				                    , ['class' => 'form-control']) !!}
				                </td>
				                <td>
				                    {!! Form::number('present_stock_qty[]', $purchaseRequisition->present_stock_qty
				                    , ['class' => 'form-control']) !!}
				                </td>
				                <td>
				                    {!! Form::number('pipeline_qty[]', $purchaseRequisition->pipeline_qty
				                    , ['class' => 'form-control']) !!}
				                </td>
				                <td>
				                    {!! Form::number('required_qty[]', $purchaseRequisition->required_qty
				                    , ['class' => 'form-control']) !!}
				                </td>
				                <td class="text-right">৳{{ $purchaseRequisition->total_value }}</td>
				                <td>
				                    {!! Form::text('remarks[]', $purchaseRequisition->remarks, ['class' => 'form-control', 'placeholder' => 'Enter Remarks']) !!}
				                </td>
				            	<td>
				            		<a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a>
				            	</td>
				            </tr>
				            @php
							$sum_total_value += $purchaseRequisition->total_value;
				            @endphp
				            @endforeach
				        </tbody>
				        <tfoot>
				            <tr id="total-row" class="font-weight-bold">
				                <td colspan="6" class="text-right">Total</td>
				                <td id="total-column" class="text-right">৳{{ $sum_total_value }}</td>
				                <td colspan="2"></td>
				            </tr>
				        </tfoot>
				    </table>
				</div>
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-info" onclick="addPurchaseRequisitions();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


