<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class' => 'form-control chosen-select', 'id' => 'plant_id', 'onchange' => 'getOpeningStock();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="transaction_code" class="control-label">Transaction Code 
                @if(empty($fuelInventory))
                {!! validation_error($errors->first('transaction_code'),'transaction_code') !!}
                @endif
            </label>
            @if(empty($fuelInventory))
                {!! Form::text('transaction_code', old('transaction_code') ? old('transaction_code') : (!empty($fuelInventory) ? $fuelInventory->transaction_code : $transaction_code), ['class'=>'form-control', 'placeholder' => 'Enter transaction code', 'id' => 'transaction_code']) !!}
                <small id="transaction_codeHelpBlock" class="form-text text-muted">The transaction code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('transaction_code', $fuelInventory->transaction_code, ['class'=>'form-control', 'placeholder' => 'Enter  transaction code', 'id' => 'transaction_code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="transaction_date" class="control-label">Transaction Date {!! validation_error($errors->first('transaction_date'),'transaction_date') !!}</label>
            {!! Form::text('transaction_date', old('transaction_date') ? old('transaction_date') : (!empty($fuelInventory) ? $fuelInventory->transaction_date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'transaction_date', 'onchange' => 'getOpeningStock();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="fuel_type_id" class="control-label">Fuel Type {!! validation_error($errors->first('transaction_date'),'transaction_date') !!}</label>
                    {!! Form::select('fuel_type_id', $fuelTypes, null, ['class' => 'form-control chosen-select', 'id' => 'fuel_type_id', 'onchange' => 'getFuelTypeUnit(this.value);']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="unit" class="control-label">Unit </label>
                    {!! Form::text('unit', !empty($fuelInventory) ? $fuelInventory->fuelType->unit : null, ['class' => 'form-control', 'disabled' => 'disabled', 'id'=>'unit']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="opening_stock_disabled" class="control-label">Opening Stock </label>
            {!! Form::text('opening_stock_disabled', $opening_stock, ['class'=>'form-control', 'placeholder' => 'Enter Opening Stock', 'id' => 'opening_stock_disabled', 'disabled' => 'disabled']) !!}
            {!! Form::hidden('opening_stock', $opening_stock) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="invoice_quantity" class="control-label">Invoice Quantity {!! validation_error($errors->first('invoice_quantity'),'invoice_quantity', true) !!}</label>
            {!! Form::text('invoice_quantity', !empty($fuelInventory) ? $fuelInventory->invoice_quantity : (old('invoice_quantity') ? old('invoice_quantity') : 0) , ['class' => 'form-control', 'placeholder' => 'Enter Invoice Quantity', 'id' => 'invoice_quantity', 'min' => 0, 'onchange' => 'setTransportLoss();']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="received_quantity" class="control-label">Received Quantity {!! validation_error($errors->first('received_quantity'),'received_quantity', true) !!}</label>
            {!! Form::text('received_quantity', !empty($fuelInventory) ? $fuelInventory->received_quantity : (old('received_quantity') ? old('received_quantity') : 0), ['class' => 'form-control', 'placeholder' => 'Enter Received Quantity', 'id' => 'received_quantity', 'min' => 0, 'onchange' => 'setTransportLoss();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="transportation_loss" class="control-label">Transportation Loss (%) </label>
            {!! Form::text('transportation_loss', null, ['class' => 'form-control', 'id' => 'transportation_loss', 'disabled']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="available_stock" class="control-label">Available Stock </label>
            {!! Form::text('available_stock', !empty($fuelInventory) ? $fuelInventory->available_stock : $opening_stock, ['class' => 'form-control', 'id' => 'available_stock', 'disabled']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="consumption" class="control-label">Consumption {!! validation_error($errors->first('consumption'), 'consumption') !!}</label>
            {!! Form::number('consumption', null, ['class' => 'form-control', 'placeholder' => 'Enter Consumption', 'id' => 'consumption', 'step' => 'any']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')
<script>
function getFuelTypeUnit(fuel_type_id)
{
    if(fuel_type_id) {
        $('#ajaxloader').removeClass('hide');
        $.ajax({
            url:'{{ url('fuel-inventories/fetch-fuel-type-unit') }}/'+fuel_type_id,
            method:'POST',
            success:function(data) {
                $('#unit').val(data);
            },
            complete:function(){
                $('#ajaxloader').addClass('hide');
            },
            error:function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' + thrownError);
            }
        });

        getOpeningStock();
    }else {
        $('#unit').val('');
    }
}

function getOpeningStock()
{
    var plant_id = $('#plant_id').val();
    var fuel_type_id = $('#fuel_type_id').val();
    var transaction_date = $('#transaction_date').val();

    if(plant_id && fuel_type_id && transaction_date) {
        $('#ajaxloader').removeClass('hide');
        $.ajax({
            url:'{{ url('fuel-inventories/fetch-opening-stock') }}/'+transaction_date,
            method:'POST',
            data:{plant_id:plant_id, fuel_type_id:fuel_type_id},
            success:function(data) {
                $('#opening_stock_disabled').val(data);
                $('input[name=opening_stock]').val(data);
                $('input[name=available_stock]').val(data);
            },
            complete:function() {
                $('#ajaxloader').addClass('hide');
            },
            error:function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' + thrownError);
            }
        });
    }else {
        $('#opening_stock_disabled').val(0);
        $('input[name=opening_stock]').val(0);
    }
}

function setAvailableStock()
{
    openingStock = parseFloat($('input[name=opening_stock]').val());
    receivedQuantity =parseFloat($('#received_quantity').val());

    if((typeof openingStock == 'number') && (typeof receivedQuantity == 'number')) {
        $('#available_stock').val(openingStock+receivedQuantity);
    }else {
        $('#available_stock').val(0);
    }
}

function setTransportLoss() {
    
    var invoiceQuantity = parseFloat($('#invoice_quantity').val());
    var receivedQuantity = parseFloat($('#received_quantity').val());

    if((typeof invoiceQuantity) == 'number') {
        if((typeof receivedQuantity) == 'number') {

            if(invoiceQuantity) {
                trasportLoss = (receivedQuantity - invoiceQuantity)/invoiceQuantity * 100;
            }else {
                trasportLoss = 0;
            }

            if((typeof trasportLoss) == 'number') {
                $('#transportation_loss').val(trasportLoss.toFixed(2));
            }

            setAvailableStock();
        }
    }else {
        $('#transportation_loss').val(0);
    }
}
</script>
@endsection


