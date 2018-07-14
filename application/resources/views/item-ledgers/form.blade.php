<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id', 'onchange' => 'getItems(this.value);']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="issue_date" class="control-label">Issue Date {!! validation_error($errors->first('issue_date'),'issue_date') !!}</label>
            {!! Form::text('issue_date', old('issue_date') ? old('issue_date') : (!empty($itemLedger) ? $itemLedger->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'issue_date']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('issue_code', 'Issue Code') !!}
            {!! Form::text('issue_code', $issue_code, ['class' => 'form-control', 'placeholder' => 'Enter Issue Code', 'disabled' => 'disabled']) !!}
        </div>
    </div>
</div>

<div class="table-responsive-xl">
    <table class="table table-hover table-striped table-vertical-middle table-sm">
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
            <tr>
                <td>
                    {!! Form::select('itm_id', [], null, ['class'=>'form-control chosen-select', 'onchange' => 'setItemValue(this);']) !!}
                    {!! Form::hidden('item_name', '') !!}
                </td>
                <td class="text-right">0</td>
                <td class="text-right">
                    ৳0.00
                    {!! Form::hidden('avg_price_main', 0, ['class' => 'form-control']) !!}
                </td>
                <td>
                    {!! Form::number('receive_qty_main', 0
                    , ['class' => 'form-control']) !!}
                </td>
                <td>
                    {!! Form::number('issue_qty_main', 0
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
    var receiveQty = parseFloat(tableRow.find('input[name=receive_qty_main]').val());
        
    if(isNaN(receiveQty)) {
        receiveQty = 0;
    }

    var issueQty = parseFloat(tableRow.find('input[name=issue_qty_main]').val());
        
    if(isNaN(issueQty)) {
        issueQty = 0;
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
                    tableRow.find('td:nth-child(2)').text(response.opening_qty);
                    tableRow.find('td:nth-child(3)').html('৳'+ response.avg_price.toFixed(2) +'<input type="hidden" name="avg_price_main" value="'+ response.avg_price +'">');
                    tableRow.find('input[name=remarks_main]').val(response.remarks);

                    var totalPrice = parseFloat(response.avg_price * response.opening_qty).toFixed(2);
                    tableRow.find('td:nth-child(6)').text('৳' + totalPrice);
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
        tableRow.find('td:nth-child(2)').text(0);
        tableRow.find('td:nth-child(3)').html('৳0.00 <input type="hidden" name="avg_price_main" value="0">');
        tableRow.find('input[name=receive_qty_main]').val(0);
        tableRow.find('input[name=issue_qty_main]').val(0);
        tableRow.find('td:nth-child(6)').text('৳0.00');
        tableRow.find('input[name=remarks_main]').val('');
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
    var openingQty = parseFloat(tableRow.find('td:nth-child(2)').text());
    var avgPrice = parseFloat(tableRow.find('input[name=avg_price_main]').val());
    var receiveQty = parseFloat(tableRow.find('input[name=receive_qty_main]').val());
    var issueQty = parseFloat(tableRow.find('input[name=issue_qty_main]').val());
    var remarks = tableRow.find('input[name=remarks_main]').val();

    var totalPrice = (avgPrice * openingQty).toFixed(2);

    var newTableRow = '<tr><td>'+ itemName +'<input type="hidden" name="item_id[]" value="'+ itemId +'" /></td><td class="text-right">'+ openingQty +'<input type="hidden" name="opening_qty[]" value="'+ openingQty +'" /></td><td class="text-right">৳'+ avgPrice +'<input type="hidden" name="avg_price[]" value="'+ avgPrice +'" /></td><td><input type="number" name="receive_qty[]" value="'+ receiveQty +'" class="form-control" onchange="updateItemLedgerInfo(this);"/></td><td><input type="number" name="issue_qty[]" value="'+ issueQty +'" class="form-control" onchange="updateItemLedgerInfo(this);"/></td><td class="text-right">'+totalPrice+'</td><td><input name="remarks[]" value="'+ remarks +'" class="form-control" placeholder="Enter remarks..."></td><td><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';

    $(newTableRow).appendTo('tbody');

    $('select[name=itm_id]').val('').trigger('chosen:updated');
    tableRow.find('input[name=item_name]').val('');
    tableRow.find('td:nth-child(2)').text(0);
    tableRow.find('td:nth-child(3)').html('৳0.00 <input type="hidden" name="avg_price_main" value="0">');
    tableRow.find('input[name=receive_qty_main]').val(0);
    tableRow.find('input[name=issue_qty_main]').val(0);
    tableRow.find('td:nth-child(6)').text('৳0.00');
    tableRow.find('input[name=remarks_main]').val('');

    calculateItemLedger();
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

(function() {
    var plantId = $('#plant_id').val();

    if(plantId) {
        getItems(plantId);
    }
})();
</script>
@endsection


