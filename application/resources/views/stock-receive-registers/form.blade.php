<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="receive_date" class="control-label">Receive Date {!! validation_error($errors->first('receive_date'),'receive_date') !!}</label>
            {!! Form::text('receive_date', old('receive_date') ? old('receive_date') : date('Y-m-d'), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'receive_date']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('receive_code', 'Receive Code') !!}
            {!! Form::text('receive_code', $receive_code, ['class' => 'form-control', 'placeholder' => 'Enter receive code', 'disabled' => 'disabled']) !!}
        </div>
    </div>
</div>

<div class="row margin-top-10">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('po_no', 'PO Number') !!}
            {!! Form::select('po_no', $purchaseOrders, null, ['class'=>'form-control chosen-select', 'id' => 'po_no', 'onchange' => 'getPurchaseOrders();']) !!}
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped table-vertical-middle table-sm">
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
            @if(!empty($stockReceiveRegisters) && $stockReceiveRegisters->isNotEmpty())
                @php $i = 1 @endphp
                @foreach($stockReceiveRegisters as $stockReceiveRegister)
                    @php
                    $item = $stockReceiveRegister->item;
                    @endphp
                    <tr>
                        <td>
                            {{ $i++ }}
                            {!! Form::hidden('po_number[]', $stockReceiveRegister->po_number) !!}
                        </td>
                        <td>
                            {{ $stockReceiveRegister->requisition_code }}
                            {!! Form::hidden('requisition_code[]', $stockReceiveRegister->requisition_code) !!}
                        </td>
                        <td>{{ strtoupper($item->code) }}</td>
                        <td>
                            {{ $item->name }}
                            {!! Form::hidden('item_id[]', $item->id) !!}
                        </td>
                        <td class="text-right">
                            {{ $stockReceiveRegister->po_qty }}
                            {!! Form::hidden('po_qty[]', $stockReceiveRegister->po_qty) !!}
                        </td>
                        <td>
                            {!! Form::number('grn_qty[]', $stockReceiveRegister->grn_qty, ['class' => 'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::text('remarks[]', ($stockReceiveRegister->remarks ? $stockReceiveRegister->remarks : ''), ['class' => 'form-control', 'placeholder' =>'Enter remarks...']) !!}
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
function getPurchaseOrders()
{
    var poNumber = $('select[name=po_no]').val();

    if(poNumber) {
        $('#ajaxloader').removeClass('hide');
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
                            tableRow += '<tr><td>'+ sl++ +'<input type="hidden" name="po_number[]" value="'+ value.po_number +'" /></td><td>'+value.requisition_code.toUpperCase()+'<input type="hidden" name="requisition_code[]" value="'+ value.requisition_code +'" /></td><td>'+ value.code.toUpperCase() +'</td><td>'+ value.name +'<input type="hidden" name="item_id[]" value="'+ value.id +'" /></td><td class="text-right">'+ value.po_qty +'<input type="hidden" name="po_qty[]" value="'+ value.po_qty +'" /></td><td class="text-right"><input type="number" name="grn_qty[]" value="1" class="form-control" min="1"/ required></td><td><input name="remarks[]" class="form-control" placeholder="Enter remarks..."></td><td><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';
                        });
                    }

                    if(tableRow) {
                        $(tableRow).appendTo('tbody');
                    }
                }
            },
            complete:function() {
                $('select[name=po_no]').val('').trigger('chosen:updated');
                $('#ajaxloader').addClass('hide');
            },
            error:function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' +thrownError);
            }
        });
    }
}

function removeRow(element) {

    var rowCount = $('tbody tr').length;

    // if (rowCount > 1) {
        $(element).parents("tr").remove();
    // }
}

function addStockReceiveRegisters()
{
    $('#ajaxloader').removeClass('hide');

    $.ajax({
        url:'{{ url('stock-receive-registers') }}',
        method:'POST',
        data:$('#stock-receive-register-form').serialize(),
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
</script>
@endsection


