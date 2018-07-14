@extends('layouts.master')

@section('title') Edit Engine Generation @endsection 
@section('page_title') Engine Generations @endsection

@section('content')

<div class="container">
	{!! Form::model($engineGeneration, array('url' => 'engine-generations/'.$engineGeneration->id, 'role' => 'form', 'id'=>'engine-generation-edit-form', 'method' => 'PUT')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Edit Engine Generation</h3>
			</div>
			<div class="card-body">
				<div class="row">
				    <div class="col-sm-6">
				        <div class="details-info">
				            <strong>Gen Code: </strong>{{ $engineGeneration->gen_code }} <br>
				            <strong>Gen Date: </strong>{{ Carbon::parse($engineGeneration->gen_date)->format('d M, Y') }} <br>
				            <strong>Plant : </strong>{{ $engineGeneration->plant->name }} <br>
				            <strong>Engine : </strong>{{ $engineGeneration->engine->name }}
				        </div>
				    </div>
				</div>

				<div class="row margin-top-20">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="start" class="control-label">Start {!! validation_error($errors->first('start'),'start') !!}</label>
				            {!! Form::number('start', null, ['class'=>'form-control','id' => 'start', 'step' => 'any', 'onchange' => 'getTotal(this);', 'required' => 'required']) !!}
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="end" class="control-label">End {!! validation_error($errors->first('end'),'end') !!}</label>
				            {!! Form::number('end', null, ['class'=>'form-control','id' => 'end', 'step' => 'any', 'onchange' => 'getTotal(this);', 'required' => 'required']) !!}
						</div>
					</div>
                    <div class="col-sm-4 text-center">
                        <div class="form-group">
                            <strong>Total</strong>
                            <div class="total margin-top-10">{{ $engineGeneration->total }}</div>
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

<script>
function setDefaultValue(elem)
{
    elem.parent().addClass('has-error');
    elem.val('0:00');

    return;
}

function removeInvalidClass(elem)
{
    elem.parent().find('.invalid-feedback').remove();
    elem.removeClass('is-invalid');
}

function isValid(elem)
{
    elemName = elem.attr('name');
    elemValue = elem.val();
    hasError = false;

    if(!elemValue) {
        msg = 'The '+ elemName +' should not be empty';
        hasError = true;
    }
    else if(isNaN(elemValue)) {
        msg = 'The '+ elemName +' must be number value';
        hasError = true;
    } 
    else if(elemValue < 0) {
        msg = 'The ' + elemName +' value can not be smaller than 0';
        hasError = true;
    }

    if(hasError) {
        elem.parent().find('.invalid-feedback').remove();
        elem.parent().append('<div class="invalid-feedback">'+ msg +'</div>');
        elem.addClass('is-invalid').val(0);

        return false;
    }

    return true;
}

function getTotal(obj)
{   
    var elem = $(obj);

    if(isValid(elem)) {
        removeInvalidClass(elem);

        var startElem = $('#start');
        var startElemValue = parseFloat(startElem.val());

        var endElem = $('#end');
        var endElemValue = parseFloat(endElem.val());

        if(startElemValue > endElemValue) {
            msg = 'Start value can not be larger than end value';

            startElem.parent().append('<div class="invalid-feedback">'+ msg +'</div>');
            startElem.addClass('is-invalid');
            endElem.addClass('is-invalid');

            return;
        }else {
            removeInvalidClass(startElem);
            removeInvalidClass(endElem);
        }

        total = endElemValue - startElemValue;

        $('.total').text(total.toFixed(2));
    }
}
</script>

@endsection



