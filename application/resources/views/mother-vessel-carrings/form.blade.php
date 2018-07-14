<div class="row">
    <div class="col-md-12">{!! validationHints() !!}</div>
</div>
<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="mother_vessel_id" class="control-label">Mother Vessel {!! validation_error($errors->first('mother_vessel_id'),'mother_vessel_id') !!}</label>
            {!! Form::select('mother_vessel_id', $motherVessels, null, ['class' => 'form-control chosen-select']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Mother Vessel Carring Code 
                @if(empty($motherVesselCarring))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($motherVesselCarring))
                {!! Form::text('code', old('code') ? old('code') : (!empty($motherVesselCarring) ? $motherVesselCarring->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter Mother Vessel Carring Code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">The code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('code', $motherVesselCarring->code, ['class'=>'form-control', 'placeholder' => 'Enter Mother Vessel Code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="lc_number" class="control-label">LC Number 
                @if(empty($motherVesselCarring))
                    {!! validation_error($errors->first('lc_number'),'lc_number') !!}
                @endif
            </label>
            @if(empty($motherVesselCarring))
                {!! Form::text('lc_number', old('lc_number') ? old('lc_number') : (!empty($motherVesselCarring) ? $motherVesselCarring->lc_number : $lcNumber), ['class'=>'form-control', 'placeholder' => 'Enter LC Number', 'id' => 'lc_number']) !!}
                <small id="lc_numberHelpBlock" class="form-text text-muted">The LC number may only contain letters and numbers and at least 8 characters long.</small>
            @else
                {!! Form::text('lc_number', $motherVesselCarring->lc_number, ['class'=>'form-control', 'placeholder' => 'Enter Mother Vessel lc_number', 'id' => 'lc_number', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="carring_date" class="control-label">Carring Date {!! validation_error($errors->first('carring_date'),'carring_date') !!}</label>
            {!! Form::text('carring_date', old('carring_date') ? old('carring_date') : (!empty($motherVesselCarring) ? $motherVesselCarring->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'carring_date']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="loading_date" class="control-label">Loading Date {!! validation_error($errors->first('loading_date'), 'loading_date') !!}</label>
            {!! Form::text('loading_date', old('loading_date') ? old('loading_date') : (!empty($motherVesselCarring) ? $motherVesselCarring->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'loading_date']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="received_date" class="control-label">Received Date {!! validation_error($errors->first('received_date'), 'received_date') !!}</label>
            {!! Form::text('received_date', old('received_date') ? old('received_date') : (!empty($motherVesselCarring) ? $motherVesselCarring->date : date('Y-m-d')), ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'received_date']) !!}
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
            <label for="received_quantity" class="control-label">Received Quantity (MT) {!! validation_error($errors->first('received_quantity'), 'received_quantity') !!}</label>
            {!! Form::text('received_quantity', null, ['class' => 'form-control', 'placeholder' => 'Enter Received Quantity', 'id' => 'received_quantity', 'min' => 0, 'onchange' => 'setTransportLoss();']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="transport_loss" class="control-label">Transport Loss (%) {!! validation_error($errors->first('transport_loss'), 'transport_loss') !!}</label>
            {!! Form::text('transport_loss', null, ['class' => 'form-control', 'placeholder' => 'Enter Transport Loss', 'id' => 'transport_loss']) !!}
        </div>
    </div>
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


