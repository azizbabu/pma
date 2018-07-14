<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant
            @if(!empty($purchaseOrder)) 
                {!! validation_error($errors->first('plant_id'),'plant_id') !!}
            @endif
            </label>
            @if(empty($purchaseOrder)) 
                {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id', 'onchange' => 'getPurchaseRequisitions(this.value);']) !!}
            @else
                {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id', 'onchange' => 'getPurchaseRequisitions(this.value);', 'disabled'=>'disabled']) !!}
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="po_date" class="control-label">P.O Date {!! validation_error($errors->first('po_date'),'po_date') !!}</label>
            {!! Form::text('po_date', old('po_date') ? old('po_date') : (!empty($purchaseOrder) ? $purchaseOrder->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'po_date']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('po_number', 'PO Number') !!}
            {!! Form::text('po_number', $po_number, ['class' => 'form-control', 'placeholder' => 'Enter PO Number', 'disabled' => 'disabled']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="spare_parts_type" class="control-label">Spare Parts Type {!! validation_error($errors->first('spare_parts_type'),'spare_parts_type') !!}</label>
            {!! Form::select('spare_parts_type', config('constants.spare_parts_types'), null, ['class'=>'form-control chosen-select', 'id' => 'spare_parts_type']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="source_type" class="control-label">Source Type {!! validation_error($errors->first('source_type'),'source_type') !!}</label>
            {!! Form::select('source_type', config('constants.po_source_types'), null, ['class'=>'form-control chosen-select', 'id' => 'source_type']) !!}
        </div>
    </div>
</div>

<div class="table-responsive-xl">
    <table class="table table-hover table-striped table-vertical-middle table-sm">
        <thead class="thead-dark">
            <tr>
                <th width="12%">PR. Code</th>
                <th>Item</th>
                <th width="12%" class="text-right">Avg. Price</th>
                <th width="12%" class="text-right">Last Price</th>
                <th width="7%" class="text-right">PR Qty</th>
                <th width="12%" class="text-right">PR Value</th>
                <th width="7%" class="text-right">Remaining Qty</th>
                <th width="10%" class="text-right">PO Qty</th>
                <th width="12%" class="text-right">PO Price</th>
                <th width="12%" class="text-right">PO Value</th>
                <th width="15%" >Remarks</th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($purchaseOrders) && $purchaseOrders->isNotEmpty())
                @php
                $total_pr_value = 0;
                $total_po_value = 0;
                @endphp
                @foreach($purchaseOrders as $purchaseOrder)
                @php
                $item = $purchaseOrder->item
                @endphp

            <tr>
                <td>
                    {!! Form::hidden('purchase_order_id[]', $purchaseOrder->id) !!}
                    {!! Form::hidden('purchase_requisition_id[]', $purchaseOrder->purchase_requisition_id) !!}
                    {{ $purchaseOrder->requisition_code }}
                    {!! Form::hidden('requisition_code[]', $purchaseOrder->requisition_code) !!}
                </td>
                <td>
                    {{ $item->name }}
                    {!! Form::hidden('item_id[]', $item->id) !!}
                </td>
                <td class="text-right">
                    ৳{{ number_format($item->avg_price,2) }}
                    {!! Form::hidden('avg_price[]', $item->avg_price) !!}
                </td>
                <td class="text-right">
                    ৳{{ number_format($purchaseOrder->last_price,2) }}
                    {!! Form::hidden('last_price[]', $purchaseOrder->last_price) !!}
                </td>
                <td class="text-right">
                    {{ $purchaseOrder->pr_qty }}
                    {!! Form::hidden('pr_qty[]', $purchaseOrder->pr_qty) !!}
                </td>
                @php
                $pr_value = $purchaseOrder->pr_qty * $item->avg_price;
                $total_pr_value += $pr_value;
                $total_po_value += $purchaseOrder->po_value;
                @endphp
                <td class="text-right">৳{{ number_format(($purchaseOrder->pr_qty * $item->avg_price), 2) }}</td>
                <td class="text-right">{{ ($purchaseOrder->purchaseRequisition->remaining_qty + $purchaseOrder->po_qty) }}</td>
                <td>
                    {!! Form::number('po_qty[]', $purchaseOrder->po_qty
                    , ['class' => 'form-control', 'min' => 1]) !!}
                </td>
                <td>
                    {!! Form::number('po_price[]', $purchaseOrder->po_price
                    , ['class' => 'form-control', 'min' => 1]) !!}
                </td>
                <td class="text-right">৳{{ number_format($purchaseOrder->po_value,2) }}</td>
                <td>
                    {!! Form::text('remarks[]', $purchaseOrder->remarks
                    , ['class' => 'form-control']) !!}
                </td>
                <td>
                    <a class="btn btn-xs btn-danger hide" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="11" class="text-center">No Record Found!</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr id="total-row" class="font-weight-bold">
                <td colspan="5" class="text-right">Total</td>
                <td id="total-pr-value" class="text-right">{{ !empty($total_pr_value) ? '৳' . number_format($total_pr_value,2) : '' }}</td>
                <td></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td id="total-po-value" class="text-right">{{ !empty($total_po_value) ? '৳' . number_format($total_po_value,2) : '' }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

@section('custom-style')

@endsection

@section('custom-script')
<script>
function setDefaultValue() 
{
    $('tbody').html('<tr><td colspan="12" class="text-center">No Record Found!</td></tr>');
    $('#total-pr-value').html('');
    $('#total-po-value').html('');
}

function getPurchaseRequisitions(plantId)
{   
    if(!plantId) {
        setDefaultValue();

        return;
    }

    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('purchase-orders/fetch-purchase-requisitions') }}/'+plantId,
        method:'POST',
        data:{_token:$('meta[name=csrf-token]').attr('content')},
        dataType:'JSON',
        success:function(response) {
            var tableRow = '';
            if(response.length) {
                $.each(response, function(key, value) {
                    var prValue = value.avg_price * value.pr_qty;

                    tableRow += '<tr><td><input type="hidden" name="purchase_order_id[]" value="0"/><input type="hidden" name="purchase_requisition_id[]" value="'+value.id+'"/>'+value.requisition_code+'<input type="hidden" name="requisition_code[]" value="'+value.requisition_code+'"/></td><td>'+value.name+'<input type="hidden" name="item_id[]" value="'+value.item_id+'"/></td><td class="text-right">৳'+ value.avg_price.toFixed(2) +'<input type="hidden" name="avg_price[]" value="'+value.avg_price+'"/></td><td class="text-right">৳'+ value.last_price.toFixed(2) +'<input type="hidden" name="last_price[]" value="'+value.last_price+'"/></td><td class="text-right">'+ value.pr_qty +'<input type="hidden" name="pr_qty[]" value="'+value.pr_qty+'"/></td><td class="text-right">৳'+ prValue.toFixed(2) +'</td><td class="text-right">'+value.remaining_qty+'</td><td class="text-right"><input type="number" name="po_qty[]" value="1" class="form-control" min="1" max="'+value.remaining_qty+'" onchange="updatePurchaseOrderInfo(this);" required/></td><td class="text-right"><input type="number" name="po_price[]" value="1" class="form-control" min="1" onchange="updatePurchaseOrderInfo(this);" required/></td><td class="text-right">৳1.00</td><td class="text-right"><input type="text" name="remarks[]" value="" class="form-control" placeholder="Enter remarks" /></td><td class="text-right"><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';
                });

                $('tbody').html(tableRow);

                calculatePurchaseOrder();
            }else {
                setDefaultValue();

                return;
            }
        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' + thrownError);
        }
    });
}

function removeRow(element) {

    var rowCount = $('tbody tr').length;

    if (rowCount > 1) {
        $(element).parents("tr").remove();
    }

    calculatePurchaseOrder();
}

function calculatePurchaseOrder()
{
    var totalPrValue = 0;
    var totalPoValue = 0;

    tableRowNumber = 1;
    $('tbody tr').each(function() {
        
        var avgPrice = parseFloat($(this).find('input[name^=avg_price]').val());

        if(isNaN(avgPrice)) {
            avgPrice = 0;
        }

        var prQty = parseFloat($(this).find('input[name^=pr_qty]').val());

        if(isNaN(prQty)) {
           prQty = 0; 
        }

        var poQty = parseFloat($(this).find('input[name^=po_qty]').val());

        if(isNaN(poQty)) {
            poQty = 0;
        }

        var poPrice = parseFloat($(this).find('input[name^=po_price]').val());

        if(isNaN(poPrice)) {
            poPrice = 0;
        }

        prValue = prQty * avgPrice;
        poValue = poQty * poPrice;

        $(this).find('td:nth-child(6)').text('৳' + prValue.toFixed(2));
        $(this).find('td:nth-child(10)').text('৳' + poValue.toFixed(2));

        totalPrValue += prValue;
        totalPoValue += poValue;
        
        tableRowNumber++;
    });

    $('#total-pr-value').text('৳' + totalPrValue.toFixed(2));
    $('#total-po-value').text('৳' + totalPoValue.toFixed(2));
}

function updatePurchaseOrderInfo(obj)
{
    var elemName = $(obj).attr('name');
    var elemValue = parseFloat($(obj).val());
    if(isNaN(elemValue)) {
        alert('Please insert number');
        $(obj).parent().addClass('has-error');
        return;
    }else {
        $(obj).parent().removeClass('has-error');
    }

    calculatePurchaseOrder();
}

function addPurchaseOrder()
{
    $('#ajaxloader').removeClass('hide');

    $.ajax({
        url:'{{ url('purchase-orders') }}',
        method:'POST',
        data:$('#purchase-order-form').serialize(),
        success:function(response) {
            if(response.status == 400) {
                $.each(response.error, function(index, value) {
                    $('#ve-'+index).html('['+ value +']');
                });
            }else {
                toastMsg(response.message, response.type);

                if(response.type == 'success') {
                    setTimeout(function() {
                        location.href = '{{ url('purchase-orders/list') }}';
                    }, 1500);
                }
            }
        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' +thrownError);
        }
    });
}

@if(empty($purchaseOrder))
    (function() {
        var plantId = $('#plant_id').val(); 

        if(plantId) {
            getPurchaseRequisitions(plantId);
        }
    })();
@endif
</script>
@endsection


