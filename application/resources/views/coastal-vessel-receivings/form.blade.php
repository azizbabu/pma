<div class="row">
    <div class="col-md-12">{!! validationHints() !!}</div>
</div>

<div class="details-info margin-top-20">
    <div class="row">
        <div class="col-sm-6">
            <strong>Coastal Vessel Carring Code: </strong>{{ $coastalVesselCarring->code }}
            <br>
            <strong>Costal Vessel: </strong>{{ $coastalVesselCarring->coastalVessel->name }}
        </div>
        <div class="col-sm-6">
            <strong>Coastal Vessel Carring Date: </strong>{{ Carbon::parse($coastalVesselCarring->carring_date)->format('d M, Y') }}
            <br>
            <strong>Invoice Quantity: </strong>{{ $coastalVesselCarring->invoice_quantity }}
            <br>
            <strong>Received Quantity: </strong>{{ $coastalVesselCarring->received_quantity }}
            <br>
        </div>
    </div>
</div>

<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="cvr_number" class="control-label">CVR Number</label>
            {!! Form::text('cvr_number', $cvr_number, ['class'=>'form-control', 'placeholder' => 'Enter CVR Number', 'id' => 'cvr_number', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="cvr_date" class="control-label">CVR Date {!! validation_error($errors->first('cvr_date'),'cvr_date') !!}
            </label>
            {!! Form::text('cvr_date', old('cvr_date') ? old('cvr_date') : (!empty($coastalVesselReceiving) ? $coastalVesselReceiving->cvr_date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'Enter CVR Date', 'id' => 'cvr_date', 'required']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="cvr_qty" class="control-label">CVR Qty {!! validation_error($errors->first('cvr_qty'),'cvr_qty') !!}</label>
            {!! Form::number('cvr_qty', old('cvr_qty') ? old('cvr_qty') : (!empty($coastalVesselReceiving) ? $coastalVesselReceiving->cvr_qty : $coastalVesselCarring->invoice_quantity), ['class' => 'form-control', 'placeholder' => 'Enter CVR Qty', 'min' => 1, 'onchange' => 'getLoss(this);', 'id' => 'cvr_qty', 'step' => 'any','required']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="load_qty" class="control-label">Load Qty {!! validation_error($errors->first('load_qty'),'load_qty') !!}</label>
            {!! Form::number('load_qty', old('load_qty') ? old('load_qty') : (!empty($coastalVesselReceiving) ? $coastalVesselReceiving->load_qty : 1), ['class' => 'form-control', 'placeholder' => 'Enter Load Qty', 'min' => 1, 'onchange' => 'getLoss(this);', 'id' => 'load_qty', 'step' => 'any', 'required']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="loss" class="control-label">Loss (%)</label>
            {!! Form::number('loss', null, ['class' => 'form-control', 'min' => 1, 'step' => 'any', 'disabled' => 'disabled', 'id' => 'loss']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="lighter_vessel_name" class="control-label">Lighter Vessel Name </label>
            {!! Form::text('lighter_vessel_name', null, ['class'=>'form-control', 'placeholder' => 'Enter Lighter Vessel Name', 'id' => 'lighter_vessel_name']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_receive_date" class="control-label">Plant Receive Date </label>
            {!! Form::text('plant_receive_date', null, ['class' => 'form-control datepicker', 'placeholder' => 'Enter Plant Received Date', 'id' => 'plant_receive_date']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class' => 'form-control chosen-select', 'id' => 'plant_id']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')
<script>
function getLoss(obj)
{
    var elem = $(obj);
    var elemValue = parseInt(elem.val());
    var elemName = elem.attr('name').replace('_', ' ');
    var hasError = false;

    if(elemValue === '' || elemValue == null) {
        hasError = true;
        msg = 'The ' + elemName +' could not be empty';
    }

    if(isNaN(elemValue)) {
        hasError = true;
        msg = 'The ' + elemName +' must be integer value';
    }

    if(hasError) {
        elem.parent().find('.invalid-feedback').remove();
        elem.parent().append('<div class="invalid-feedback">'+ msg+'</div>');
        elem.addClass('is-invalid');

        return false;
    }else {
        elem.parent().find('.invalid-feedback').remove();
        elem.removeClass('is-invalid');
    }

    var cvrQty = $('#cvr_qty');
    var cvrQtyValue = parseInt(cvrQty.val());
    var loadQty = $('#load_qty'); 
    var loadQtyValue = parseInt(loadQty.val()); 

    if(loadQtyValue > cvrQtyValue) {
        loadQty.parent().append('<div class="invalid-feedback">The load qty must be equal or less than cvr qty</div>');
        loadQty.addClass('is-invalid');

        return false;
    }else {
        loadQty.parent().find('.invalid-feedback').remove();
        loadQty.removeClass('is-invalid');

        var loss = (cvrQtyValue - loadQtyValue)/cvrQtyValue * 100;

        $('#loss').val(loss.toFixed(2));
    }
}
</script>
@endsection


