<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <strong>Requisition Code: </strong>{{ $requisition_code }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="issue_date" class="control-label">Requisition Date {!! validation_error($errors->first('requisition_date'),'requisition_date') !!}</label>
            {!! Form::text('requisition_date', old('requisition_date') ? old('requisition_date') : (!empty($purchaseOrder) ? $purchaseOrder->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'requisition_date']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="spare_parts_type" class="control-label">Spare Parts Type {!! validation_error($errors->first('spare_parts_type'),'spare_parts_type') !!}</label>
            {!! Form::select('spare_parts_type', config('constants.spare_parts_types'), null, ['class'=>'form-control chosen-select', 'id' => 'spare_parts_type']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="source_type" class="control-label">Source Type {!! validation_error($errors->first('source_type'),'source_type') !!}</label>
            {!! Form::select('source_type', config('constants.po_source_types'), null, ['class'=>'form-control chosen-select', 'id' => 'source_type']) !!}
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped table-vertical-middle">
        <thead class="thead-dark">
            <tr>
                <th>Item</th>
                <th width="10%" class="text-right">Avg. Price</th>
                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Safety Stock">Saf. Stock</span></th>
                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Present Stock">Pre. Stock</span></th>
                <th width="10%"><span data-toggle="tooltip" title="Pipeline Qty">Pip. Qty</span></th>
                <th width="10%"><span data-toggle="tooltip" title="Required Qty">Req. Qty</span></th>
                <th width="10%"><span data-toggle="tooltip" title="Approved Qty">Apv. Qty</span></th>
                <th width="12%" class="text-right">Total Value</th>
                <th width="15%" >Remarks</th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            <tr id="add-item-row">
                <td colspan="10" align="center">
                    <a class="bold text-decoration-none item-add-link" href="#/"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add an Item</a>
                    <div class="item-holder row justify-content-center hide">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::select('itm_id', $items, null, ['class'=>'form-control chosen-select', 'id' => 'itm_id', 'onchange' => 'addItem();']) !!}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr id="total-row" class="font-weight-bold">
                <td colspan="7" class="text-right">Total</td>
                <td id="total-column" class="text-right"></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

@section('custom-style')

@endsection

@section('custom-script')
<script>
(function() {
    $('.item-add-link').on('click', function() {
        $('.item-holder').removeClass('hide');
        setTimeout(function(){ 
            $('#item_id').trigger("chosen:open"); }, 
        10);

        $(this).addClass('hide');
    });
})();

function addItem()
{
    var itemId = $('select[name=itm_id]').val();

    if(itemId) {
        $.ajax({
            url:'{{ url('items/fetch-item/') }}/' + itemId,
            method:'POST',
            dataType:'JSON',
            success:function(response) {
                if(response.type == 'error') {
                    toastMsg(response.message, response.type);
                }else {

                    var tableRow = '<tr><td>'+ response.name +'<input type="hidden" name="item_id[]" value="'+ response.id +'"></td><td class="text-right">৳'+ response.avg_price +'<input type="hidden" name="avg_price[]" value="'+ response.avg_price +'"></td><td><input type="number" name="item_safety_stock_qty[]" value="'+response.safety_stock_qty+'" class="form-control" min="1" required /></td><td class="text-right"><input type="number" name="present_stock_qty[]" value="1" class="form-control" min="1"/></td><td><input type="number" name="     pipeline_qty[]" value="1" class="form-control" min="1"/></td><td class="text-right"><input type="number" name="required_qty[]" value="1" class="form-control"/></td><td class="text-right"><input type="number" name="approved_qty[]" value="1" class="form-control" min="1" onchange="updatePurchaseRequisitionInfo(this);"/></td><td class="text-right">৳'+ response.avg_price +'</td><td><textarea name="remarks[]" cols="30" rows="2" class="form-control" placeholder="Enter remarks...">'+response.remarks+'</textarea></td><td><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';

                    $(tableRow).insertBefore('#add-item-row');

                    calculatePurchaseRequisition();
                }
            },
            complete:function() {
                $(".item-holder").addClass('hide');
                $('.item-add-link').removeClass('hide');
                $('select[name=itm_id]').val('').trigger('chosen:updated');
            },
            error:function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' +thrownError);
            }
        });
    }
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

        var approvedQty = parseFloat($(this).find('input[name^=approved_qty]').val());
        
        if(isNaN(approvedQty)) {
            approvedQty = 0;
        }

        totalValue = approvedQty * avgPrice;
        $(this).find('td:nth-child(8)').text('৳' + totalValue.toFixed(2));

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
        data:$('#purchase-requisition-create-form').serialize(),
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

$(document).mouseup(function(e) 
{
    var container = $(".item-holder");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        container.addClass('hide');
        $('.item-add-link').removeClass('hide');
    }
});
</script>
@endsection


