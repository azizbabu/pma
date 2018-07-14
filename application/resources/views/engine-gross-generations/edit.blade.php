@extends('layouts.master')

@section('title') Edit Engine Gross Generation @endsection 
@section('page_title') Engine Gross Generations @endsection

@section('content')

<div class="container">
	{!! Form::model($engineGrossGeneration, array('url' => 'engine-gross-generations/'.$engineGrossGeneration->id, 'role' => 'form', 'id'=>'engine-gross-generation-edit-form', 'method' => 'PUT')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Edit Engine Gross Generation</h3>
			</div>
			<div class="card-body">
				<div class="row">
				    <div class="col-sm-6">
				        <div class="details-info">
				            <strong>OP Code: </strong>{{ $engineGrossGeneration->op_code }} <br>
				            <strong>OP Date: </strong>{{ Carbon::parse($engineGrossGeneration->op_date)->format('d M, Y') }} <br>
				            <strong>Plant : </strong>{{ $engineGrossGeneration->plant->name }} <br>
				            <strong>Engine : </strong>{{ $engineGrossGeneration->engine->name }}
				        </div>
				    </div>
				</div>

				<div class="row margin-top-20">
					<div class="col-sm-2">
						<div class="form-group">
							<label for="start_time" class="control-label">Start Time {!! validation_error($errors->first('start_time'),'start_time') !!}</label>
				            {!! Form::text('start_time', null, ['class'=>'form-control timepicker','id' => 'start_time']) !!}
						</div>
					</div>
					<div class="col-sm-2 text-center">
						<div class="form-group">
                            <strong>Diff Time</strong>
							<div class="diff-time margin-top-10">{{ $engineGrossGeneration->diff_time }}</div>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="end_time" class="control-label">End Time {!! validation_error($errors->first('end_time'),'end_time') !!}</label>
				            {!! Form::text('end_time', null, ['class'=>'form-control timepicker','id' => 'end_time']) !!}
						</div>
					</div>
					<div class="col-sm-3 text-center">
						<div class="form-group">
							<strong>Start OP MWH</strong>
				            <div class="margin-top-10">{{ $engineGrossGeneration->start_op_mwh }}</div>
						</div>
					</div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="end_op_mwh" class="control-label">End OP MWH {!! validation_error($errors->first('end_op_mwh'),'end_op_mwh') !!}</label>
                            {!! Form::number('end_op_mwh', null, ['class'=>'form-control','id' => 'end_op_mwh', 'step' => 'any']) !!}
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
{{-- Bootstrap Timepicker --}}
{!! Html::style($assets . '/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css') !!}
@endsection

@section('custom-script')
{{-- Bootstrap Timepicker --}}
{!! Html::script($assets . '/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js') !!}
<script>
Number.prototype.padDigit = function () {
    return (this < 10) ? '0' + this : this;
}

function setDefaultValue(elem)
{
    elem.parent().addClass('has-error');
    elem.val('0:00');

    return;
}

(function() {
	
    $('.timepicker').timepicker({
        showMeridian:false,
        minuteStep: 5,
        showInputs: false,
        disableFocus: true,
        icons:{
            up:'fa fa-chevron-up',
            down:'fa fa-chevron-down',
        }
    }).on('hide.timepicker', function() {
        var elem = $(this);
        var elemValue = elem.val();
        var arr = elemValue.split(':');
        var hour = parseInt(arr[0]);
        var minute = parseInt(arr[1]);

        if(!elemValue) {
            alert('The value should not be empty');
            setDefaultValue(elem);
        }

        if(isNaN(hour) || isNaN(minute)) {
            alert('The value must be time value');
            setDefaultValue(elem);
        }

        var startTimeElem = $('#start_time');
        var startTime = startTimeElem.val();
        var startTimeArr = startTime.split(':');
        var startTimeHour = parseInt(startTimeArr[0]);
        var startTimeMinute = parseInt(startTimeArr[1]);

        var endTimeElem = $('#end_time');
        var endTime = endTimeElem.val();
        var endTimeArr = endTime.split(':');
        var endTimeHour = parseInt(endTimeArr[0]);
        var endTimeMinute = parseInt(endTimeArr[1]);

        if(startTimeHour > endTimeHour) {
            alert('End time must be greater than start time');
            startTimeElem.parent().addClass('has-error');
            endTimeElem.parent().addClass('has-error');

            return;
        }else {
            startTimeElem.parent().removeClass('has-error');
            endTimeElem.parent().removeClass('has-error');
        }

        if((startTimeHour == endTimeHour) && (startTimeMinute >= endTimeMinute)) {
            alert('End time must be greater than start time');
            startTimeElem.parent().addClass('has-error');
            endTimeElem.parent().addClass('has-error');

            return;
        }else {
            startTimeElem.parent().removeClass('has-error');
            endTimeElem.parent().removeClass('has-error');
        }

        min = endTimeMinute-startTimeMinute;
        hourCarry = 0;
        if(min < 0){
           min += 60;
           hourCarry += 1;
        }
        hour = endTimeHour-startTimeHour-hourCarry;
        diffTime = hour + ":" + min.padDigit();
        
        $('.diff-time').text(diffTime);
    });
})();
</script>

@endsection



