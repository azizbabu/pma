@extends('layouts.master')

@section('title') Edit Permission @endsection 
@section('page_title') Permissions @endsection

@section('content')

<div class="container">
	{!! Form::model($permission, array('url' => 'permissions/'.$permission->id, 'role' => 'form', 'id'=>'permission-edit-form', 'method' => 'PUT')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Edit Permission</h3>
			</div>
			<div class="card-body">
				<div class="details-info">
					<strong>Role: </strong>{{ $permission->role->name }} <br>  
					<strong>Module: </strong>{{ $permission->module->name }} <br>
					<strong>Page: </strong>{{ $permission->page->name }} <br>
				</div>

				<div class="row margin-top-20">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<strong>Can Create </strong> <label>{!! Form::checkbox('can_create', 1, null) !!} Yes</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<strong>Can Update </strong> <label>{!! Form::checkbox('can_update', 1, null) !!} Yes</label>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<strong>Can Delete </strong> <label>{!! Form::checkbox('can_delete', 1, null) !!} Yes</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<strong>Can View </strong> <label>{!! Form::checkbox('can_view', 1, null) !!} Yes</label>
								</div>
							</div>
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



