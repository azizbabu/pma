@extends('layouts.master')

@section('title') Edit Daily Terminal Stock @endsection 
@section('page_title') Daily Terminal Stocks @endsection

@section('content')

<div class="container">
	{!! Form::model($dailyTerminalStock, array('url' => 'daily-terminal-stocks/'.$dailyTerminalStock->id, 'role' => 'form', 'id'=>'daily-terminalstock-edit-form', 'method' => 'PUT')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Edit Daily Terminal Stock</h3>
			</div>
			<div class="card-body">
				<div class="details-info">
					<strong>Terminal: </strong>{{ $dailyTerminalStock->terminal->name }} <br>
					<strong>Tank Number: </strong>{{ strtoupper($dailyTerminalStock->tank_number) }} <br>
					<strong>Tank Capacity (MT): </strong>{{ strtoupper($dailyTerminalStock->tank->capacity) }} <br>
				</div>

				<div class="row margin-top-20">
					<div class="col-sm-6">
						<div class="form-group">
				            <label for="transaction_date" class="control-label">Transaction Date {!! validation_error($errors->first('transaction_date'),'transaction_date') !!}</label>
				            {!! Form::text('transaction_date', old('transaction_date'), ['class'=>'form-control datepicker','id' => 'transaction_date']) !!}
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="tank_stock" class="control-label">Tank Stock {!! validation_error($errors->first('tank_stock'),'tank_stock', true) !!}</label>
				            {!! Form::text('tank_stock', old('tank_stock'), ['class'=>'form-control','id' => 'tank_stock', 'placeholder' => 'Enter Tank Stock']) !!}
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="comment" class="control-label">Comment </label>
				            {!! Form::textarea('comment', old('comment'), ['class'=>'form-control','id' => 'comment', 'placeholder' => 'Enter Comment...', 'size' => '30x1']) !!}
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection

@section('custom-style')
{{-- iCheck --}}
{!! Html::style($assets . '/plugins/icheck/skins/minimal/blue.css') !!}
@endsection

@section('custom-script')
{{-- iCheck --}}
{!! Html::script($assets . '/plugins/icheck/icheck.min.js') !!}
{{-- Bootstrap Timepicker --}}
<script>
(function() {
    $('input[type="checkbox"]').iCheck({
         checkboxClass: 'icheckbox_minimal-blue',
         increaseArea: '20%' // optional
    });
})();
</script>

@endsection



