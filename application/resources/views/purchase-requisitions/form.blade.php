<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id', 'onchange' => 'getItems(this.value);']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="issue_date" class="control-label">Requisition Date {!! validation_error($errors->first('requisition_date'),'requisition_date') !!}</label>
            {!! Form::text('requisition_date', old('requisition_date') ? old('requisition_date') : (!empty($purchaseOrder) ? $purchaseOrder->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'requisition_date']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('requisition_code', 'Requisition Code') !!}
            {!! Form::text('requisition_code', $requisition_code, ['class' => 'form-control', 'placeholder' => 'Enter requisition code', 'disabled' => 'disabled']) !!}
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
                <th>Item</th>
                <th width="10%" class="text-right">Avg. Price</th>
                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Safety Stock Qty">Saf. Stock</span></th>
                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Present Stock Qty">Pre. Stock</span></th>
                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Pipeline Qty">Pip. Qty</span></th>
                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Required Qty">Req. Qty</span></th>
                <th width="12%" class="text-right">Total Value</th>
                <th width="15%" >Remarks</th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {!! Form::select('itm_id', [], null, ['class'=>'form-control chosen-select', 'onchange' => 'setItemValue(this);']) !!}
                    {!! Form::hidden('item_name', '') !!}
                </td>
                <td class="text-right">
                    ৳0.00
                    {!! Form::hidden('avg_price_main', 0, ['class' => 'form-control']) !!}
                </td>
                <td class="text-right">
                    0
                    {!! Form::hidden('item_safety_stock_qty_main', 0
                    , ['class' => 'form-control']) !!}
                </td>
                <td class="text-right">
                    0
                    {!! Form::hidden('present_stock_qty_main', 1
                    , ['class' => 'form-control']) !!}
                </td>
                <td class="text-right">
                    0
                    {!! Form::hidden('pipeline_qty_main', 1
                    , ['class' => 'form-control']) !!}
                </td>
                <td class="text-right">
                    {!! Form::number('required_qty_main', 1
                    , ['class' => 'form-control', 'min' => 1]) !!}
                </td>
                <td class="text-right">৳0.00</td>
                <td>
                    {!! Form::text('remarks_main', null, ['class' => 'form-control', 'placeholder' => 'Enter Remarks']) !!}
                </td>
                <td>
                    <a class="btn btn-xs btn-info" onclick="javascript:addItem(this);"><i class="fa fa-plus-circle"></i> ADD</a>
                </td>
            </tr>

            @if(!empty($purchaseRequisitions) && $purchaseRequisitions->isNotEmpty())
                @php $sum_total_value = 0 @endphp
                @foreach($purchaseRequisitions as $purchaseRequisition)
                @php $item = $purchaseRequisition->item @endphp
                <tr>
                    <td>
                        {{ $item->name }}
                        {!! Form::hidden('item_id[]', $item->id) !!}
                    </td>
                    <td class="text-right">
                        ৳{{ number_format($purchaseRequisition->item_avg_price, 2) }}
                        {!! Form::hidden('avg_price[]', $purchaseRequisition->item_avg_price, ['class' => 'form-control']) !!}
                    </td>
                    <td class="text-right">
                        {{ $purchaseRequisition->item_safety_stock_qty }}
                        {!! Form::hidden('item_safety_stock_qty[]', $purchaseRequisition->item_safety_stock_qty
                        , ['class' => 'form-control']) !!}
                    </td>
                    <td class="text-right">
                        {{ $purchaseRequisition->present_stock_qty }}
                        {!! Form::hidden('present_stock_qty[]', $purchaseRequisition->present_stock_qty
                        , ['class' => 'form-control']) !!}
                    </td>
                    <td class="text-right">
                        {{ $purchaseRequisition->pipeline_qty }}
                        {!! Form::hidden('pipeline_qty[]', $purchaseRequisition->pipeline_qty
                        , ['class' => 'form-control']) !!}
                    </td>
                    <td>
                        {!! Form::number('required_qty[]', $purchaseRequisition->required_qty
                        , ['class' => 'form-control', 'min' => 1]) !!}
                    </td>
                    <td class="text-right">৳{{ number_format($purchaseRequisition->total_value, 2) }}</td>
                    <td>
                        {!! Form::text('remarks[]', null, ['class' => 'form-control', 'placeholder' => 'Enter remarks']) !!}
                    </td>
                    <td>
                        <a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
                @php $sum_total_value += $purchaseRequisition->total_value @endphp
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr id="total-row" class="font-weight-bold">
                <td colspan="6" class="text-right">Total</td>
                <td id="total-column" class="text-right">{{ !empty($sum_total_value) ? '৳'. number_format($sum_total_value, 2) :'' }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

@section('custom-style')

@endsection

@section('custom-script')
<script>
function getItems(plantId)
{
    if(plantId) {
        $('#ajaxloader').removeClass('hide');
        $.ajax({
            url:'{{ url('items/fetch-items') }}/' + plantId,
            method:'POST',
            data:{_token:$('meta[name="csrf-token"]').attr('content')},
            success:function(data) {
                $('select[name=itm_id]').html(data).trigger('chosen:updated');
            },
            complete:function() {
                $('#ajaxloader').addClass('hide');
            },
            error:function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' + thrownError);
            }
        });
    }
}

function setItemValue(obj)
{
    var itemElem = $(obj);
    var itemElemValue = itemElem.val();
    var tableRow = itemElem.closest('tr');
    var requiredQty = parseFloat(tableRow.find('input[name=required_qty_main]').val());
        
    if(isNaN(requiredQty)) {
        requiredQty = 0;
    }

    if(itemElemValue) {
        $('#ajaxloader').removeClass('hide');
        $.ajax({
            url:'{{ url('items/fetch-item/') }}/' + itemElemValue,
            method:'POST',
            dataType:'JSON',
            success:function(response) {
                if(response.type == 'error') {
                    toastMsg(response.message, response.type);
                }else {
                    tableRow.find('input[name=item_name]').val(response.name);
                    tableRow.find('td:nth-child(2)').html('৳'+ response.avg_price.toFixed(2) +'<input type="hidden" name="avg_price_main" value="'+ response.avg_price +'">');
                    tableRow.find('td:nth-child(3)').html(response.safety_stock_qty +'<input type="hidden" name="item_safety_stock_qty_main" value="'+ response.safety_stock_qty +'">');

                    var present_stock_qty = response.opening_qty + response.receive_qty + response.return_qty - response.issue_qty;

                    tableRow.find('td:nth-child(4)').html(present_stock_qty +'<input type="hidden" name="present_stock_qty_main" value="'+ present_stock_qty +'">');
                    tableRow.find('td:nth-child(5)').html(response.pipeline_qty +'<input type="hidden" name="pipeline_qty_main" value="'+ response.pipeline_qty +'">');
                    tableRow.find('input[name=remarks_main]').val(response.remarks);

                    avgPrice = parseFloat(response.avg_price);

                    totalValue = requiredQty * avgPrice;
                    tableRow.find('td:nth-child(7)').text('৳' + totalValue.toFixed(2));
                }
            },
            complete:function() {
                $('#ajaxloader').addClass('hide');
            },
            error:function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' +thrownError);
            }
        });
    }else {
        tableRow.find('input[name=item_name]').val('');
        tableRow.find('td:nth-child(2)').html('৳0.00 <input type="hidden" name="avg_price_main" value="0">');
        tableRow.find('td:nth-child(3)').html(0 +'<input type="hidden" name="item_safety_stock_qty_main" value="0">');
        tableRow.find('td:nth-child(4)').html(0 +'<input type="hidden" name="present_stock_qty_main" value="0">');
        tableRow.find('td:nth-child(5)').html(0 +'<input type="hidden" name="pipeline_qty_main" value="0">');
        tableRow.find('input[name=remarks_main]').val('');
        avgPrice = 0;
        totalValue = requiredQty * avgPrice;
        tableRow.find('td:nth-child(7)').text('৳' + totalValue.toFixed(2));
    } 
}

function addItem(obj)
{
    var tableRow = $(obj).closest('tr');
    var itemId = tableRow.find('select[name=itm_id]').val();
    if(!itemId) {
        alert('Please select a item ');
        return;
    }
    var itemName = tableRow.find('input[name=item_name]').val();
    var avgPrice = parseFloat(tableRow.find('input[name=avg_price_main]').val());
    var itemSafetyStockQty = parseFloat(tableRow.find('input[name=item_safety_stock_qty_main]').val());
    var presentStockQty = parseFloat(tableRow.find('input[name=present_stock_qty_main]').val());
    var pipelineQty = parseFloat(tableRow.find('input[name=pipeline_qty_main]').val());
    var requiredQty = parseFloat(tableRow.find('input[name=required_qty_main]').val());
    var remarks = tableRow.find('input[name=remarks_main]').val();

    var total = avgPrice * requiredQty;

    var newTableRow = '<tr><td>'+ itemName +'<input type="hidden" name="item_id[]" value="'+ itemId +'"></td><td class="text-right">৳'+ avgPrice.toFixed(2) +'<input type="hidden" name="avg_price[]" value="'+ avgPrice +'"></td><td class="text-right">'+ itemSafetyStockQty +'<input type="hidden" name="item_safety_stock_qty[]" value="'+itemSafetyStockQty+'" class="form-control" min="1" required /></td><td class="text-right">'+ presentStockQty +'<input type="hidden" name="present_stock_qty[]" value="'+presentStockQty+'" class="form-control" min="1"/></td><td class="text-right">'+ pipelineQty +'<input type="hidden" name="pipeline_qty[]" value="'+pipelineQty+'" class="form-control" min="1"/></td><td class="text-right"><input type="number" name="required_qty[]" value="'+requiredQty+'" class="form-control" min="1" onchange="updatePurchaseRequisitionInfo(this);"/></td><td class="text-right">৳'+ total.toFixed(2) +'</td><td><input type="text" name="remarks[]" value="'+remarks+'" class="form-control" placeholder="Enter remarks..."></td><td><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';

    $(newTableRow).appendTo('tbody');

    $('select[name=itm_id]').val('').trigger('chosen:updated');
    tableRow.find('input[name=item_name]').val('');
    tableRow.find('td:nth-child(2)').html('৳0.00 <input type="hidden" name="avg_price_main" value="0">');
    tableRow.find('td:nth-child(3)').html(0 +'<input type="hidden" name="safety_stock_qty_main" value="0">');
    tableRow.find('td:nth-child(4)').html(0 +'<input type="hidden" name="present_stock_qty_main" value="0">');
    tableRow.find('td:nth-child(5)').html(0 +'<input type="hidden" name="pipeline_qty_main" value="0">');
    tableRow.find('input[name=required_qty_main]').val(1);
    tableRow.find('input[name=remarks_main]').val('');
    avgPrice = 0;
    totalValue = requiredQty * avgPrice;
    tableRow.find('td:nth-child(7)').text('৳' + totalValue.toFixed(2));
            
    calculatePurchaseRequisition();
}

function calculatePurchaseRequisition()
{
    var totalValue = 0;
    var sumTotalValue = 0;

    $('tbody tr').each(function() {
        var avgPrice = parseFloat($(this).find('input[name^=avg_price]').val());
        
        if(isNaN(avgPrice)) {
            avgPrice = 0;
        }

        var requiredQty = parseFloat($(this).find('input[name^=required_qty]').val());
        
        if(isNaN(requiredQty)) {
            requiredQty = 0;
        }

        totalValue = requiredQty * avgPrice;
        $(this).find('td:nth-child(7)').text('৳' + totalValue.toFixed(2));

        sumTotalValue += totalValue;
    });

    $('#total-column').text('৳' + sumTotalValue.toFixed(2));
}

function updatePurchaseRequisitionInfo(obj)
{
    var elem = $(obj);
    var elemValue = parseFloat($(obj).val());
    if(isNaN(elemValue)) {
        alert('Please insert number');
        elem.val(0);
        $(obj).parent().addClass('has-error');

        return;
    }else if(elemValue <= 0) {
        alert('Approve Qty must be greater than 0');
        elem.val(0);
        $(obj).parent().addClass('has-error');

        return;
    }else {
        $(obj).parent().removeClass('has-error');
    }

    calculatePurchaseRequisition();
}

function removeRow(element) {

    var rowCount = $('tbody tr').length;

    if (rowCount > 1) {
        $(element).parents("tr").remove();
    }

    calculatePurchaseRequisition();
}

function addPurchaseRequisitions()
{
    $('#ajaxloader').removeClass('hide');

    $.ajax({
        url:'{{ url('purchase-requisitions') }}',
        method:'POST',
        data:$('#purchase-requisition-form').serialize(),
        success:function(response) {
            if(response.status == 400) {
                $.each(response.error, function(index, value) {
                    $('#ve-'+index).html('['+ value +']');
                });
            }else {
                toastMsg(response.message, response.type);

                if(response.type == 'success') {
                    setTimeout(function() {
                        location.href = '{{ url('purchase-requisitions/list') }}';
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

(function() {
    var plantId = $('#plant_id').val();

    if(plantId) {
        getItems(plantId);
    }
})();
</script>
@endsection


