<div class="row">
    <div class="col-md-12">{!! validationHints() !!}</div>
</div>
<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="coastal_vessel_id" class="control-label">Coastal Vessel {!! validation_error($errors->first('coastal_vessel_id'),'coastal_vessel_id') !!}</label>
            {!! Form::select('coastal_vessel_id', $coastalVessels, null, ['class' => 'form-control chosen-select']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Coastal Vessel Carring Code 
                @if(empty($coastalVesselCarring))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($coastalVesselCarring))
                {!! Form::text('code', old('code') ? old('code') : (!empty($coastalVesselCarring) ? $coastalVesselCarring->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter Coastal Vessel Carring Code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">The code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('code', $coastalVesselCarring->code, ['class'=>'form-control', 'placeholder' => 'Enter Coastal Vessel Code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="tank_id" class="control-label">Tank {!! validation_error($errors->first('tank_id'),'tank_id') !!}</label>
            {!! Form::select('tank_id', $tanks, null, ['class' => 'form-control chosen-select']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="carring_date" class="control-label">Carring Date {!! validation_error($errors->first('carring_date'),'carring_date') !!}</label>
            {!! Form::text('carring_date', old('carring_date') ? old('carring_date') : (!empty($coastalVesselCarring) ? $coastalVesselCarring->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'carring_date']) !!}
        </div>
    </div>
    
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="loading_date" class="control-label">Loading Date {!! validation_error($errors->first('loading_date'), 'loading_date') !!}</label>
            {!! Form::text('loading_date', old('loading_date') ? old('loading_date') : (!empty($coastalVesselCarring) ? $coastalVesselCarring->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'loading_date']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="received_date" class="control-label">Received Date {!! validation_error($errors->first('received_date'), 'received_date') !!}</label>
            {!! Form::text('received_date', old('received_date') ? old('received_date') : (!empty($coastalVesselCarring) ? $coastalVesselCarring->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'received_date']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="invoice_quantity" class="control-label">Invoice Quantity (MT) {!! validation_error($errors->first('invoice_quantity'), 'invoice_quantity') !!}</label>
            {!! Form::text('invoice_quantity', null, ['class' => 'form-control', 'placeholder' => 'Enter Invoice Quantity', 'id' => 'invoice_quantity', 'min' => 0, 'onchange' => 'setTransportLoss();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant </label>
            {!! Form::select('plant_id', $plants, null, ['class' => 'form-control chosen-select', 'id' => 'plant_id']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="comment" class="control-label">Comment </label>
            {!! Form::textarea('comment', null, ['class' => 'form-control', 'size' => '30x2', 'placeholder' => 'Enter Comment', 'id' => 'comment']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')
<script>
function setTransportLoss() {
    
    var invoiceQuantity = parseInt($('#invoice_quantity').val());
    var receivedQuantity = parseInt($('#received_quantity').val());


    if(invoiceQuantity && (typeof invoiceQuantity) == 'number') {
        if(receivedQuantity && (typeof receivedQuantity) == 'number') {
            var trasportLoss = (invoiceQuantity - receivedQuantity)/invoiceQuantity * 100;

            if((typeof trasportLoss) == 'number') {
                $('#transport_loss').val(trasportLoss.toFixed(2));
            }
        }
    }
}
</script>
@endsection


