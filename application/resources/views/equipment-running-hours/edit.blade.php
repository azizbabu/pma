@extends('layouts.master')

@section('title') Edit Equipment Running Hour @endsection 
@section('page_title') Equipment Running Hours @endsection

@section('content')

	{!! Form::model($equipmentRunningHour, array('url' => 'equipment-running-hours/'.$equipmentRunningHour->id, 'role' => 'form', 'id'=>'equipment-running-hour-edit-form', 'method' => 'PUT')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Edit Equipment Running Hour</h3>
			</div>
			<div class="card-body">
				<div class="details-info">
					<div class="row">
						<div class="col-md-6">
							<strong>Plant: </strong>{{ $equipmentRunningHour->plant->name }} <br>
							<strong>Plant Eqipment: </strong>{{ $equipmentRunningHour->plantEquipment->name }}
						</div>
						<div class="col-md-6">
							<strong>Running Year: </strong>{{ $equipmentRunningHour->running_year }} <br>
							<strong>Running Month: </strong>{{ $equipmentRunningHour->running_month }}
						</div>
					</div>
				</div>

				<div class="row margin-top-20">
					<div class="col-md-3">
						<div class="form-group text-center">
				            <strong>Start Value</strong>
				            <div class="margin-top-10 font-weight-bold start-value">{{ $equipmentRunningHour->start_value }}</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
				            <label for="end_value" class="control-label">End Value {!! validation_error($errors->first('end_value'),'end_value') !!}</label>
				            {!! Form::text('end_value', old('end_value'), ['class'=>'form-control','id' => 'end_value', 'onchange' => 'getDiffValue();']) !!}
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group text-center">
				            <strong>Diff Value</strong> <br>
				            <div class="margin-top-10 font-weight-bold diff-value">{{ $equipmentRunningHour->diff_value }}</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}

@endsection

@section('custom-style')

@endsection

@section('custom-script')
<script>
function getDiffValue(obj)
{
    var endValueElem = $('#end_value');
    var endValue = parseFloat(endValueElem.val());

    if(isNaN(endValue)) {
        alert('Please add a number for end value');
        endValueElem.parent().addClass('has-error');
        endValueElem.val({{ $equipmentRunningHour->end_value }});

        return;
    }

    if(!endValue) {
        alert('End value can not be empty');
        endValueElem.parent().addClass('has-error');
        endValueElem.val({{ $equipmentRunningHour->end_value }});

        return;
    }

    var startValue = parseFloat($('.start-value').text());

    if(startValue >= endValue) {
        alert('End value can not be smaller than start value');
        endValueElem.parent().addClass('has-error');

        return;
    }
    var diffValue = parseFloat(endValue - startValue).toFixed(2);

    $('.diff-value').text(diffValue);
}

(function() {
   
})();
</script>

@endsection



