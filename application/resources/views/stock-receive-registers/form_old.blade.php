<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <strong>Receive Code: </strong>{{ $receive_code }}
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
            <label for="receive_date" class="control-label">Receive Date {!! validation_error($errors->first('receive_date'),'receive_date') !!}</label>
            {!! Form::text('receive_date', old('receive_date') ? old('receive_date') : date('Y-m-d'), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'receive_date']) !!}
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped table-vertical-middle">
        <thead class="thead-dark">
            <tr>
                <th width="4%">Sl.</th>
                <th width="10%">PR No</th>
                <th width="10%">Code</th>
                <th>Item</th>
                <th width="10%" class="text-right">PO Qty</th>
                <th width="14%">GRN Qty</th>
                <th width="30%" >Remarks</th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            <tr id="add-item-row">
                <td colspan="8" align="center">
                    <a class="bold text-decoration-none item-add-link" href="#/"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add an Item</a>
                    <div class="item-holder row justify-content-center hide">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::select('po_no', $purchaseOrders, null, ['class'=>'form-control chosen-select', 'id' => 'po_no', 'onchange' => 'getPurchaseOrders();']) !!}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
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

function getPurchaseOrders()
{
    var poNumber = $('select[name=po_no]').val();

    if(poNumber) {
        $.ajax({
            url:'{{ url('stock-receive-registers/get-purchase-orders') }}/' + poNumber,
            method:'POST',
            dataType:'JSON',
            success:function(response) {
                if(response.type == 'error') {
                    toastMsg(response.message, response.type);
                }else {

                    var tableRow = '';

                    if(response.length) {
                        $('tbody tr').not('#add-item-row').remove();
                        var sl = 1;
                        $.each(response, function(key, value) {
                            tableRow += '<tr><td>'+ sl++ +'<input type="hidden" name="po_number[]" value="'+ value.po_number +'" /></td><td>'+value.item_pr_number.toUpperCase()+'<input type="hidden" name="item_pr_number[]" value="'+ value.item_pr_number +'" /></td><td>'+ value.code +'</td><td>'+ value.name +'<input type="hidden" name="item_id[]" value="'+ value.id +'" /></td><td class="text-right">'+ value.po_qty +'<input type="hidden" name="po_qty[]" value="'+ value.po_qty +'" /></td><td class="text-right"><input type="number" name="grn_qty[]" value="1" class="form-control" min="1"/ required></td><td><textarea name="remarks[]" cols="30" rows="2" class="form-control" placeholder="Enter remarks..."></textarea></td><td><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';
                        });
                    }

                    if(tableRow) {
                        $(tableRow).insertBefore('#add-item-row');
                    }
                }
            },
            complete:function() {
                $(".item-holder").addClass('hide');
                $('.item-add-link').removeClass('hide');
                $('select[name=po_no]').val('').trigger('chosen:updated');
            },
            error:function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' +thrownError);
            }
        });
    }
}

function removeRow(element) {

    var rowCount = $('tbody tr').length;

    if (rowCount > 1) {
        $(element).parents("tr").remove();
    }
}

function addStockReceiveRegisters()
{
    $('#ajaxloader').removeClass('hide');

    $.ajax({
        url:'{{ url('stock-receive-registers') }}',
        method:'POST',
        data:$('#stock-receive-register-create-form').serialize(),
        success:function(response) {
            if(response.status == 400) {
                $.each(response.error, function(index, value) {
                    $('#ve-'+index).html('['+ value +']');
                });
            }else {
                toastMsg(response.message, response.type);

                if(response.type == 'success') {
                    setTimeout(function() {
                        location.href = '{{ url('stock-receive-registers/list') }}';
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


