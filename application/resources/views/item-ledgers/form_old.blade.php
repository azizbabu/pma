<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <strong>Issue Code: </strong>{{ $issue_code }}
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
            <label for="issue_date" class="control-label">Issue Date {!! validation_error($errors->first('issue_date'),'issue_date') !!}</label>
            {!! Form::text('issue_date', old('issue_date') ? old('issue_date') : (!empty($itemLedger) ? $itemLedger->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'issue_date']) !!}
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped table-vertical-middle">
        <thead class="thead-dark">
            <tr>
                <th>Item</th>
                <th width="12%" class="text-right">Opening Qty</th>
                <th width="12%" class="text-right">Avg. Price</th>
                <th width="12%">Receive Qty</th>
                <th width="12%">Issue Qty</th>
                <th width="14%" class="text-right">Total Price</th>
                <th width="20%" >Remarks</th>
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
                                {!! Form::select('itm_id', $items, null, ['class'=>'form-control chosen-select', 'id' => 'itm_id', 'onchange' => 'addItem();']) !!}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr id="total-row" class="font-weight-bold">
                <td colspan="5" class="text-right">Total</td>
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
    $('.item-add-link').on('click', function() {
    $('.item-holder').removeClass('hide');
    setTimeout(function(){ 
        $('#item_id').trigger("chosen:open"); }, 
    10);

    $(this).addClass('hide');
});

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
                    var totalPrice = parseFloat(response.avg_price * response.opening_qty).toFixed(2);

                    var tableRow = '<tr><td>'+ response.name +'<input type="hidden" name="item_id[]" value="'+ response.id +'" /></td><td class="text-right">'+ response.opening_qty +'<input type="hidden" name="opening_qty[]" value="'+ response.opening_qty +'" /></td><td class="text-right">৳'+ response.avg_price +'<input type="hidden" name="avg_price[]" value="'+ response.avg_price +'" /></td><td><input type="number" name="receive_qty[]" value="0" class="form-control" onchange="updateItemLedgerInfo(this);"/></td><td><input type="number" name="issue_qty[]" value="0" class="form-control" onchange="updateItemLedgerInfo(this);"/></td><td class="text-right">'+totalPrice+'</td><td><textarea name="remarks[]" cols="30" rows="2" class="form-control" placeholder="Enter remarks...">'+response.remarks+'</textarea></td><td><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';

                    $(tableRow).insertBefore('#add-item-row');

                    calculateItemLedger();
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

function removeRow(element) {

    var rowCount = $('tbody tr').length;

    if (rowCount > 1) {
        $(element).parents("tr").remove();
    }

    calculateItemLedger();
}

function calculateItemLedger()
{
    var totalPrice = 0;
    var sumTotalPrice = 0;

    $('tbody tr').each(function() {
        var openingQty = parseFloat($(this).find('input[name^=opening_qty]').val());
        
        if(isNaN(openingQty)) {
            openingQty = 0;
        }
        var avgPrice = parseFloat($(this).find('input[name^=avg_price]').val());

        if(isNaN(avgPrice)) {
            avgPrice = 0;
        }

        var receiveQty = parseFloat($(this).find('input[name^=receive_qty]').val());

        if(isNaN(receiveQty)) {
           receiveQty = 0; 
        }

        var issueQty = parseFloat($(this).find('input[name^=issue_qty]').val());

        if(isNaN(issueQty)) {
            issueQty = 0;
        }

        totalPrice = (openingQty + receiveQty - issueQty) * avgPrice;
        $(this).find('td:nth-child(6)').text('৳' + totalPrice.toFixed(2));

        sumTotalPrice += totalPrice;
    });

    $('#total-column').text('৳' + sumTotalPrice.toFixed(2));
}

function updateItemLedgerInfo(obj)
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

    calculateItemLedger();
}

function addItemLedger()
{
    $('#ajaxloader').removeClass('hide');

    $.ajax({
        url:'{{ url('item-ledgers') }}',
        method:'POST',
        data:$('#item-ledger-create-form').serialize(),
        success:function(response) {
            if(response.status == 400) {
                $.each(response.error, function(index, value) {
                    $('#ve-'+index).html('['+ value +']');
                });
            }else {
                toastMsg(response.message, response.type);

                if(response.type == 'success') {
                    setTimeout(function() {
                        location.href = '{{ url('item-ledgers/list') }}';
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


