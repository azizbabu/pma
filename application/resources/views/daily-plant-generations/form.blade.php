<div class="card border">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="plant_id" class="col-md-4 control-label">Plant <small class="validation-error">*</small></label>
                    <div class="col-md-8">
                        {!! Form::select('plant_id', $plants, null, ['class'=>' chosen-select','id' => 'plant_id', 'onchange' => 'getPlantGenerationInfo();']) !!}
                    </div>
                </div>

                <div class="row form-group">
                    <label for="operation_date" class="col-md-4 control-label">Operation Date <small class="validation-error">*</small></label>
                    <div class="col-md-8">
                        {!! Form::text('operation_date', old('operation_date') ? old('operation_date') : (!empty($dailyPlantGeneration->operation_date) ? $dailyPlantGeneration->operation_date : date('Y-m-d')), ['class'=>'form-control datepicker','placeholder' => 'YYYY-MM-DD', 'id' => 'operation_date', 'onchange' => 'getPlantGenerationInfo();']) !!}
                    </div>
                </div>

                <div class="row form-group">
                    <label for="remarks" class="col-md-4 control-label">Remarks </label>
                    <div class="col-md-8">
                        {!! Form::text('remarks', old('remarks'), ['class'=>'form-control','placeholder' => 'Enter Remarks', 'id' => 'remarks']) !!}
                    </div>
                </div>
                <div class="row form-group">
                    <label for="comments" class="col-md-4 control-label">Comments </label>
                    <div class="col-md-8">
                        {!! Form::textarea('comments', old('comments'), ['class'=>'form-control','placeholder' => 'Enter Comment', 'id' => 'comments', 'size' => '30x3']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row form-group">
                    <label for="plant_load_factor" class="col-md-5 control-label">Plant Load Factor (%)<small class="validation-error">*</small></label>
                    <div class="col-md-7">
                        {!! Form::number('plant_load_factor', old('plant_load_factor'), ['class'=>'form-control','placeholder' => 'Enter Plant Load Factor', 'id' => 'plant_load_factor', 'max' => 100]) !!}
                    </div>
                </div>

                <div class="row form-group">
                    <label for="plant_fuel_consumption" class="col-md-5 control-label">Fuel Consumption (MT)<small class="validation-error">*</small></label>
                    <div class="col-md-7">
                        {!! Form::number('plant_fuel_consumption', old('plant_fuel_consumption'), ['class'=>'form-control','placeholder' => 'Enter Fuel Consumption', 'id' => 'plant_fuel_consumption', 'step' => 'any']) !!}
                    </div>
                </div>

                <div class="row form-group">
                    <label for="total_hfo_stock" class="col-md-5 control-label">Total HFO Stock (MT)<small class="validation-error">*</small></label>
                    <div class="col-md-7">
                        {!! Form::number('total_hfo_stock', old('total_hfo_stock'), ['class'=>'form-control','placeholder' => 'Enter Total HFO Stock', 'id' => 'total_hfo_stock', 'step' => 'any']) !!}
                    </div>
                </div>

                <div class="row form-group">
                    <label for="reference_lhv" class="col-md-5 control-label">Reference LHV (KJ/Kg)<small class="validation-error">*</small></label>
                    <div class="col-md-7">
                        {!! Form::number('reference_lhv', old('reference_lhv'), ['class'=>'form-control','placeholder' => 'Enter Reference LHV', 'id' => 'reference_lhv', 'step' => 'any']) !!}
                    </div>
                </div>
                <div class="row form-group">
                    <label for="aux_boiler_hfo_consumption" class="col-md-5 control-label">Aux. Boiler HFO Consumption (MT)<small class="validation-error">*</small></label>
                    <div class="col-md-7">
                        {!! Form::number('aux_boiler_hfo_consumption', old('aux_boiler_hfo_consumption'), ['class'=>'form-control','placeholder' => 'Enter Aux Boiler HFO Consumption', 'id' => 'aux_boiler_hfo_consumption', 'step' => 'any']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row margin-top-20">
    <div class="col-md-5">
        <div class="table-responsive">
            <table id="genset-gross-generation-table" class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th class="align-middle">Genset Gross Generation (MWh)</th>
                        <th class="align-middle" width="22%">Last Day</th>
                        <th class="align-middle" width="22%">Today</th>
                        <th class="align-middle" width="22%"><span data-toggle="tooltip" title="Fuel Consumption">Fuel Cons.</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center" colspan="4">No Engine Found!</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-7">
        <div class="table-responsive-xl">
            <table id="engine-activity-table" class="table table-bordered table-striped table-sm" >
                <thead>
                    <tr>
                        <th colspan="5" class="text-center">Engine Activities</th>
                    </tr>
                    <tr>
                        <th>Engine Name</th>
                        <th>State</th>
                        <th>Start Time</th>
                        <th>Stop Time</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::select('engine_id_main', [], null, ['class'=>'form-control chosen-select']) !!}
                        </td>
                        <td>
                            {!! Form::select('activity_state_main', config('constants.engine_activity_state'), old('activity_state'), ['class'=>'form-control chosen-select', 'placeholder' => 'Select state', 'id' => 'activity_state', 'step' => 'any']) !!}
                        </td>
                        <td>
                            {!! Form::text('start_time_main', null, ['class' => 'form-control datetimepicker']) !!}
                        </td>
                        <td>
                            {!! Form::text('stop_time_main', null, ['class' => 'form-control datetimepicker']) !!}
                        </td>
                        <td><a class="btn btn-xs btn-info" onclick="javascript:addEngine(this);" data-toggle="tooltip" title="ADD"><i class="fa fa-plus-circle"></i></a></td>
                    </tr>
                </thead>
                <tboby>
                    <tr>
                        <td class="text-center" colspan="5">No Engine Found!</td>
                    </tr>
                </tboby>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="energy-meter-billing-table" class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th rowspan="3" class="align-middle">Energy Meter Billing</th>
                        <th colspan="4" class="text-center">Main  Meter Billing (KWh)</th>
                        <th colspan="4" class="text-center">Main Meter Billing Import (KVARH)</th>
                    </tr>
                    <tr>
                        <th class="text-center" colspan="2">Export</th>
                        <th class="text-center" colspan="2">Import</th>
                        <th class="text-center" colspan="2">Export</th>
                        <th class="text-center" colspan="2">Import</th>
                    </tr>
                    <tr>
                        <th width="10%" class="text-center">Last Day</th>
                        <th width="10%" class="text-center">Today</th>
                        <th width="10%" class="text-center">Last Day</th>
                        <th width="10%" class="text-center">Today</th>
                        <th width="10%" class="text-center">Last Day</th>
                        <th width="10%" class="text-center">Today</th>
                        <th width="10%" class="text-center">Last Day</th>
                        <th width="10%" class="text-center">Today</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" align="center">No Energy Meter Found!</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="table-responsive">
            <table id="hfo-lube-table" class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th class="text-center" colspan="3">HFO Boostar & Lube Module Reading</th>
                    </tr>
                    <tr>
                        <th>Engine</th>
                        <th width="25%">HFO (MT)</th>
                        <th width="25%">Lube Oil (KL)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center" colspan="3">No Engine Found!</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('custom-style')
{!! Html::style($assets . '/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('custom-script')
{!! Html::script($assets . '/plugins/bootstrap-datetimepicker/js/moment-with-locales.js') !!}
{!! Html::script($assets . '/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
<script>

function setDefaultValue(tableRow)
{
    tableRow.find('select[name=engine_id_main]').val('').trigger('chosen:updated');
    tableRow.find('select[name=activity_state_main]').val('').trigger('chosen:updated');
    tableRow.find('input[name=start_time_main]').val('');
    tableRow.find('input[name=stop_time_main]').val('');
    tableRow.find('.invalid').removeClass('invalid');
    tableRow.find('.is-invalid').removeClass('is-invalid');
}

function getPlantGenerationInfo()
{
    var plantId = $('#plant_id').val();
    var operationDate = $('#operation_date').val();

    gensetTableRowInitial = '<tr><td colspan="4" align="center">No Engine Found!!</td></tr>';

    engineActivitiesTableRowInitial = '<tr><td colspan="5" align="center">No Engine Found!!</td></tr>';
    energyMeterBillingTableRowInitial = '<tr><td colspan="9" align="center">No Energy Meter Found!</td></tr>';
    hfoLubeTableRowInitial = '<tr><td colspan="3" align="center">No Engine Found!</td></tr>';

    tableRow = $('select[name=engine_id_main]').closest('tr');

    if(!plantId || !operationDate) {

        $('#genset-gross-generation-table tbody').html(gensetTableRowInitial);
        $('#engine-activity-table tbody').html(engineActivitiesTableRowInitial);
        $('#energy-meter-billing-table tbody').html(energyMeterBillingTableRowInitial);
        $('#hfo-lube-table tbody').html(hfoLubeTableRowInitial);
        setDefaultValue(tableRow);

        return;
    }

    $('#ajaxloader').removeClass('hide');
        $('#energy-meter-billing-table tbody').html(energyMeterBillingTableRowInitial);
    $.ajax({
        url:'{{ url('daily-plant-generations/fetch-associate-info') }}',
         method:'POST',
        data:$('#daily-plant-generation-form').serialize(),
        dataType:'JSON',
        success:function(response) {
            if(response.type == 'error') {
                toastMsg(response.message, response.type);

                return;
            }

            var gensetTableRow = '';
            var engineActivitiesTableRow = '';
            var energyMeterBillingTableRow = '';
            var hfoLubeTableRow = '';

            if(response) {  

                if(dailyPlantGeneration = response.dailyPlantGeneration) {
                    $('#remarks').val(dailyPlantGeneration.remarks);
                    $('#comments').val(dailyPlantGeneration.comments);
                    $('#plant_load_factor').val(dailyPlantGeneration.plant_load_factor);
                    $('#plant_fuel_consumption').val(dailyPlantGeneration.plant_fuel_consumption);
                    $('#total_hfo_stock').val(dailyPlantGeneration.total_hfo_stock);
                    $('#reference_lhv').val(dailyPlantGeneration.reference_lhv);
                    $('#aux_boiler_hfo_consumption').val(dailyPlantGeneration.aux_boiler_hfo_consumption);
                    $('input[name=daily_plant_generation_id]').val(dailyPlantGeneration.id);
                }else {
                    $('#remarks').val('');
                    $('#comments').val('');
                    $('#plant_load_factor').val('');
                    $('#plant_fuel_consumption').val('');
                    $('#total_hfo_stock').val('');
                    $('#reference_lhv').val('');
                    $('#aux_boiler_hfo_consumption').val('');
                    $('input[name=daily_plant_generation_id]').val('');
                }

                if(response.dailyEngineGrossGenerations.length) {
                    $.each(response.dailyEngineGrossGenerations, function(key, value) {
                        gensetTableRow += '<tr><td><input type="hidden" name="daily_engine_gross_generation_id[]" value="'+ value.daily_engine_gross_generation_id +'" />'+ value.name +'<input type="hidden" name="daily_engine_gross_generation_engine_id[]" value="'+ value.id +'" /></td><td class="text-center">'+ value.last_day_gross_generation +'<input type="hidden" name="last_day_gross_generation[]" value="'+ value.last_day_gross_generation +'"></td><td><input type="number" name="to_day_gross_generation[]" value="'+ value.to_day_gross_generation +'" class="form-control" min="0" step="any" required/></td><td><input type="number" name="fuel_consumption[]" value="'+ value.fuel_consumption +'" class="form-control" min="0" step="any" required/></td></tr>';
                    });
                }else {
                    gensetTableRow = gensetTableRowInitial;
                }

                if(response.engineOptions.length) {
                    $('select[name=engine_id_main]').html(response.engineOptions).trigger('chosen:updated');
                    if(response.dailyEngineActivities.length) {
                        $.each(response.dailyEngineActivities, function(key, value) {
                            engineActivitiesTableRow +='<tr><td><input type="hidden" name="daily_engine_activity_id[]" value="'+value.id+'"/>'+value.engine_name+'<input type="hidden" name="daily_engine_activity_engine_id[]" value="'+ value.engine_id +'" /></td><td>'+ value.activity_state_name +'<input type="hidden" name="activity_state[]" value="'+ value.activity_state +'" /></td><td>'+value.start_time+'<input type="hidden" name="start_time[]" value="'+ value.start_time +'" /></td><td>'+value.stop_time+'<input type="hidden" name="stop_time[]" value="'+ value.stop_time +'" /></td><td>&nbsp;</td></tr>';
                        });
                    }else {
                        engineActivitiesTableRow = engineActivitiesTableRowInitial;
                    }
                }else {
                    engineActivitiesTableRow = engineActivitiesTableRowInitial;
                }

                if(response.dailyEnergyMeterBillings.length) {
                    $.each(response.dailyEnergyMeterBillings, function(key, value) {
                        energyMeterBillingTableRow += '<tr><td><input type="hidden" name="daily_energy_meter_billing_id[]" value="'+ value.daily_energy_meter_billing_id +'" />'+ value.name +'<input type="hidden" name="meter_id[]" value="'+ value.id +'" /></td><td class="text-center">'+value.export_last_day_kwh+'<input type="hidden" name="export_last_day_kwh[]" value="'+value.export_last_day_kwh+'" class="form-control" min="0" step="any" required/></td><td><input type="number" name="export_to_day_kwh[]" value="'+value.export_to_day_kwh+'" class="form-control" min="0" step="any" required/></td><td class="text-center">'+value.import_last_day_kwh+'<input type="hidden" name="import_last_day_kwh[]" value="'+value.import_last_day_kwh+'" class="form-control" min="0" step="any" required/></td><td><input type="number" name="import_to_day_kwh[]" value="'+value.import_to_day_kwh+'" class="form-control" min="0" step="any" required/></td><td class="text-center">'+value.export_last_day_kvarh+'<input type="hidden" name="export_last_day_kvarh[]" value="'+value.export_last_day_kvarh+'" class="form-control" min="0" step="any" required/></td><td><input type="number" name="export_to_day_kvarh[]" value="'+value.export_to_day_kvarh+'" class="form-control" min="0" step="any" required/></td><td class="text-center">'+value.import_last_day_kvarh+'<input type="hidden" name="import_last_day_kvarh[]" value="'+value.import_last_day_kvarh+'" class="form-control" min="0" step="any" required/></td><td><input type="number" name="import_to_day_kvarh[]" value="'+value.import_to_day_kvarh+'" class="form-control" min="0" step="any" required/></td></tr>'
                    });
                }else {
                    energyMeterBillingTableRow = energyMeterBillingTableRowInitial;
                }

                if(response.dailyHfoLubeModules.length) {
                    $.each(response.dailyHfoLubeModules, function(key, value) {
                        hfoLubeTableRow += '<tr><td><input type="hidden" name="daily_hfo_lube_module_id[]" value="'+ value.daily_hfo_lube_module_id +'" />'+ value.name +'<input type="hidden" name="daily_hfo_lube_module_engine_id[]" value="'+ value.engine_id +'" /></td><td class="text-center"><input type="number" name="hfo[]" value="'+value.hfo+'" class="form-control" min="0" step="any" placeholder="Enter HFO" required/></td><td><input type="number" name="lube_oil[]" value="'+value.lube_oil+'" class="form-control" min="0" step="any" placeholder="Enter HFO"  required/></td></tr>'
                    });
                }else {
                    hfoLubeTableRow = hfoLubeTableRowInitial;
                }
            }else {
                gensetTableRow = gensetTableRowInitial;
                engineActivitiesTableRow = engineActivitiesTableRowInitial;
                energyMeterBillingTableRow = energyMeterBillingTableRowInitial;
                hfoLubeTableRow = hfoLubeTableRowInitial;
            }

            $('#genset-gross-generation-table tbody').html(gensetTableRow);
            $('#engine-activity-table tbody').html(engineActivitiesTableRow);
            $('#energy-meter-billing-table tbody').html(energyMeterBillingTableRow);
            $('#hfo-lube-table tbody').html(hfoLubeTableRow);
        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' + thrownError);
        }
    });
}

function addEngine(obj)
{
    var tableRow = $(obj).closest('tr');
    var engineIdMain = tableRow.find('select[name=engine_id_main]');
    var engineIdMainValue = tableRow.find('select[name=engine_id_main]').val();

    if(!engineIdMainValue) {
        alert('Please select engine');
        engineIdMain.siblings('.chosen-container').addClass('invalid');

        return;
    }

    var engineName = engineIdMain.find('option[value='+engineIdMainValue+']').text();

    var activityState = tableRow.find('select[name=activity_state_main]');
    var activityStateValue = tableRow.find('select[name=activity_state_main]').val();

    if(!activityStateValue) {
        alert('Please select activity state');
        activityState.siblings('.chosen-container').addClass('invalid');

        return;
    }

    var activityStateTitle = activityState.find('option[value='+activityStateValue+']').text();

    startTimeMain = tableRow.find('input[name=start_time_main]');
    startTimeMainValue = startTimeMain.val();

    if(!startTimeMainValue) {
        alert('Please select start time');
        startTimeMain.addClass('is-invalid');

        return;
    }

    stopTimeMain = tableRow.find('input[name=stop_time_main]');
    stopTimeMainValue = stopTimeMain.val();

    if(!stopTimeMainValue) {
        alert('Please select stop time');
        stopTimeMain.addClass('is-invalid');

        return;
    }

    var startTime = new Date(startTimeMainValue);
    var stopTime = new Date(stopTimeMainValue);

    if(startTime > stopTime) {
        alert('start time can not be greater than stop time');
        startTimeMain.addClass('is-invalid');
        stopTimeMain.addClass('is-invalid');

        return;
    }

    var newTableRow = '<tr><td><input type="hidden" name="daily_engine_activity_id[]" value="0"/>'+engineName+'<input type="hidden" name="daily_engine_activity_engine_id[]" value="'+ engineIdMainValue +'" /></td><td>'+ activityStateTitle +'<input type="hidden" name="activity_state[]" value="'+ activityStateValue +'" /></td><td>'+startTimeMainValue+'<input type="hidden" name="start_time[]" value="'+ startTimeMainValue +'" /></td><td>'+stopTimeMainValue+'<input type="hidden" name="stop_time[]" value="'+ stopTimeMainValue +'" /></td><td><a class="btn btn-xs btn-danger" onclick="javascript:removeRow(this);"><i class="fa fa-trash-o"></i></a></td></tr>';

    if(!$('#engine-activity-table tbody').has('input[name^=daily_engine_activity_id]').length) {
        $('#engine-activity-table tbody').html('');
    }

    $(newTableRow).appendTo('#engine-activity-table tbody');

    setDefaultValue(tableRow);
}

function removeRow(element) {

    var rowCount = $('tbody tr').length;

    if (rowCount > 1) {
        $(element).parents("tr").remove();
    }
}

function addDailyPlantGenerations()
{
    $('.invalid-feedback').remove();
    $('#daily-plant-generation-form').find('.is-invalid').removeClass('is-invalid');
    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('daily-plant-generations') }}',
        method:'POST',
        data:$('#daily-plant-generation-form').serialize(),
        success:function(response) {
            if(response.status === 400){
                //validation error
                $.each(response.error, function(index, value) {
                    // $("#ve"+index).html('['+value+']');
                    $("#"+index).addClass('is-invalid');
                    $("#"+index).parent().append('<small class="invalid-feedback d-block">'+value+'</small>');
                });
            }else{
                toastMsg(response.message, response.type);
                
                if(response.type == 'success') {
                    setTimeout(function(){
                        location.href = '{{ url('daily-plant-generations/list') }}';

                    }, 1500); // delay 1.5s
                }
            }
        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' + thrownError);
        }
    });
}

(function() {
    $('.datetimepicker').datetimepicker({
        viewMode:'days',
        format: 'YYYY-MM-DD HH:mm',
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-arrows',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
        },
        sideBySide:true,
    });

    var plantId = $('#plant_id').val();
    var operationDate = $('#operation_date').val();

    if(plantId && operationDate) {
        getPlantGenerationInfo();
    }

})();
</script>

@endsection


