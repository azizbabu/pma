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
            {!! Form::text('issue_date', old('issue_date') ? old('issue_date') : (!empty($purchaseOrder) ? $purchaseOrder->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'issue_date']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('issue_code', 'Issue Code') !!}
            {!! Form::text('issue_code', $issue_code, ['class' => 'form-control', 'placeholder' => 'Enter issue code', 'disabled' => 'disabled']) !!}
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
                <th width="12%" class="text-right">Avg. Price</th>
                <th width="12%" class="text-right">Safety Stock</th>
                <th width="12%" class="text-right"><span data-toggle="tooltip" title="Balance Stock">Bal. Stock</span></th>
                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Required Qty" class="text-right">Req. Qty</span></th>
                <th width="10%" class="text-right">Issue Qty</th>
                <th width="15%" >Remarks</th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {!! Form::select('itm_id', [], null, ['class'=>'form-control chosen-select', 'id' => 'itm_id', 'onchange' => 'setItemValue(this);']) !!}
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
                    {!! Form::hidden('balance_stock_qty_main', 1
                    , ['class' => 'form-control']) !!}
                </td>
                <td class="text-right">
                    0
                   {!! Form::hidden('required_qty_main', 1
                    , ['class' => 'form-control']) !!} 
                </td>
                <td>
                    {!! Form::number('issue_qty_main', 1
                    , ['class' => 'form-control']) !!}
                </td>
                <td>
                    {!! Form::text('remarks_main', null
                    , ['class' => 'form-control', 'placeholder' => 'Enter remarks...']) !!}
                </td>
                <td>
                    <a class="btn btn-xs btn-info" onclick="javascript:addItem(this);"><i class="fa fa-plus-circle"></i> ADD</a>
                </td>
            </tr>
            @if(!empty($issueRegisters) && $issueRegisters->isNotEmpty())
                @foreach($issueRegisters as $issueRegister)
                @php $item = $issueRegister->item @endphp
                <tr>
                    <td>
                        {{ $item->name }}
                        {!! Form::hidden('item_id[]', $item->id) !!}
                    </td>
                    <td class="text-right">
                        ৳{{ number_format($issueRegister->item_avg_price, 2) }}
                        {!! Form::hidden('avg_price[]', $issueRegister->item_avg_price, ['class' => 'form-control']) !!}
                    </td>
                    <td class="text-right">
                        {{ $issueRegister->item_safety_stock_qty }}
                        {!! Form::hidden('item_safety_stock_qty[]', $issueRegister->item_safety_stock_qty
                        , ['class' => 'form-control']) !!}
                    </td>
                    <td class="text-right">
                        {{ $issueRegister->balance_stock_qty }}
                        {!! Form::hidden('balance_stock_qty[]', $issueRegister->balance_stock_qty
                        , ['class' => 'form-control']) !!}
                    </td>
                    <td class="text-right">
                        {{ $issueRegister->required_qty }}
                        {!! Form::hidden('required_qty[]', $issueRegister->required_qty
                        , ['class' => 'form-control', 'min' => 1]) !!}
                    </td>
                    <td class="text-right">
                        {!! Form::number('issue_qty[]', $issueRegister->issue_qty
                        , ['class' => 'form-control']) !!}
                    </td>
                    <td>
                        {!! Form::text('remarks[]', $issueRegister->remarks ? $issueRegister->remarks :'', ['class' => 'form-control', 'placeholder' => 'Enter remarks...']) !!}
                    </td>
                    <td>
                        <a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
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
    }else {
        tableRow = $('#itm_id').closest('tr');
        setDefaultValue(tableRow);
    }
}

function setDefaultValue(tableRow)
{
    $('select[name=itm_id]').val('').trigger('chosen:updated');
    tableRow.find('input[name=item_name]').val('');
    tableRow.find('td:nth-child(2)').html('৳0.00 <input type="hidden" name="avg_price_main" value="0">');
    tableRow.find('td:nth-child(3)').html(0 +'<input type="hidden" name="item_safety_stock_qty_main" value="0">');
    tableRow.find('td:nth-child(4)').html(0 +'<input type="hidden" name="balance_stock_qty_main" value="0">');
    tableRow.find('td:nth-child(5)').html(0 +'<input type="hidden" name="required_qty_main" value="0">');
    tableRow.find('input[name^=issue_qty]').val(1);
    tableRow.find('input[name^=remarks_main]').val('');
}

function setItemValue(obj)
{
    var itemElem = $(obj);
    var itemElemValue = itemElem.val();
    var tableRow = itemElem.closest('tr');

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
                    tableRow.find('td:nth-child(4)').html(response.balance_stock_qty +'<input type="hidden" name="balance_stock_qty_main" value="'+ response.balance_stock_qty +'">');
                    tableRow.find('td:nth-child(5)').html(response.pr_qty +'<input type="hidden" name="required_qty_main" value="'+ response.pr_qty +'">');
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
        setDefaultValue(tableRow);
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
    var balanceStockQty = parseFloat(tableRow.find('input[name=balance_stock_qty_main]').val());
    var requiredQty = parseFloat(tableRow.find('input[name=required_qty_main]').val());
    var issueQty = parseFloat(tableRow.find('input[name=issue_qty_main]').val());
    var remarks = tableRow.find('input[name=remarks_main]').val();

    var newTableRow = '<tr><td>'+ itemName +'<input type="hidden" name="item_id[]" value="'+ itemId +'"></td><td class="text-right">৳'+ avgPrice +'</td><td class="text-right">'+itemSafetyStockQty+'<input type="hidden" name="item_safety_stock_qty[]" value="'+itemSafetyStockQty+'"/></td><td class="text-right">'+balanceStockQty+'<input type="hidden" name="balance_stock_qty[]" value="'+balanceStockQty+'"/></td><td class="text-right">'+requiredQty+'<input type="hidden" name="required_qty[]" value="'+requiredQty+'"/></td><td class="text-right"><input type="number" name="issue_qty[]" value="'+issueQty+'" class="form-control"/></td><td><input name="remarks[]" value="'+remarks+'" class="form-control" placeholder="Enter remarks..."></td><td><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';

    $(newTableRow).appendTo('tbody');

    setDefaultValue(tableRow);
}

function removeRow(element) {

    var rowCount = $('tbody tr').length;

    if (rowCount > 1) {
        $(element).parents("tr").remove();
    }
}

function addIssueRegisters()
{
    $('#ajaxloader').removeClass('hide');

    $.ajax({
        url:'{{ url('issue-registers') }}',
        method:'POST',
        data:$('#issue-register-form').serialize(),
        success:function(response) {
            if(response.status == 400) {
                $.each(response.error, function(index, value) {
                    $('#ve-'+index).html('['+ value +']');
                });
            }else {
                toastMsg(response.message, response.type);

                if(response.type == 'success') {
                    setTimeout(function() {
                        location.href = '{{ url('issue-registers/list') }}';
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


