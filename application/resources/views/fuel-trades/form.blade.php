<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="party_id" class="control-label">Party {!! validation_error($errors->first('party_id'),'party_id') !!}</label>
            {!! Form::select('party_id', $parties, null, ['class'=>'form-control chosen-select', 'id' => 'party_id']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="terminal_id" class="control-label">Terminal {!! validation_error($errors->first('terminal_id'),'terminal_id') !!}</label>
            {!! Form::select('terminal_id', $terminals, null, ['class'=>'form-control chosen-select', 'id' => 'terminal_id']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="transaction_date" class="control-label">Transaction Date {!! validation_error($errors->first('transaction_date'),'transaction_date') !!}</label>
            {!! Form::text('transaction_date', old('transaction_date') ? old('transaction_date') : (!empty($fuelTrade) ? $fuelTrade->transaction_date : date('Y-m-d')), ['class'=>'form-control datepicker','id' => 'transaction_date']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="loan_given_qty" class="control-label">Loan Given Quantity {!! validation_error($errors->first('loan_given_qty'),'loan_given_qty') !!}</label>
            {!! Form::text('loan_given_qty', old('loan_given_qty') ? old('loan_given_qty') : (!empty($fuelTrade) ? $fuelTrade->loan_given_qty : 0), ['class'=>'form-control', 'placeholder' => 'Enter Loan Given Quantity', 'id' => 'loan_given_qty']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="loan_receive_qty" class="control-label">Loan Receive Quantity {!! validation_error($errors->first('loan_receive_qty'),'loan_receive_qty') !!}</label>
            {!! Form::text('loan_receive_qty', old('loan_receive_qty') ? old('loan_receive_qty') : (!empty($fuelTrade) ? $fuelTrade->loan_receive_qty : 0), ['class'=>'form-control', 'placeholder' => 'Enter Loan Receive Quantity', 'id' => 'loan_receive_qty']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="loan_return_qty" class="control-label">Loan Return Quantity {!! validation_error($errors->first('loan_return_qty'),'loan_return_qty') !!}</label>
            {!! Form::text('loan_return_qty', old('loan_return_qty') ? old('loan_return_qty') : (!empty($fuelTrade) ? $fuelTrade->loan_return_qty : 0), ['class'=>'form-control', 'placeholder' => 'Enter Loan Return Quantity', 'id' => 'loan_return_qty']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="loan_paid_by_party_qty" class="control-label">Loan Paid by Party Quantity {!! validation_error($errors->first('loan_paid_by_party_qty'),'loan_paid_by_party_qty') !!}</label>
            {!! Form::text('loan_paid_by_party_qty', old('loan_paid_by_party_qty') ? old('loan_paid_by_party_qty') : (!empty($fuelTrade) ? $fuelTrade->loan_paid_by_party_qty : 0), ['class'=>'form-control', 'placeholder' => 'Enter Loan Paid by Party Quantity', 'id' => 'loan_paid_by_party_qty']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


