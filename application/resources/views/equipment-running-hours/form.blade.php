<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="running_year" class="control-label">Running Year {!! validation_error($errors->first('running_year'),'running_year') !!}</label>
            {!! Form::text('running_year', date('Y'), ['class'=>'form-control datetimepicker', 'placeholder' => 'YYYY', 'id' => 'running_year', 'onchange' => 'getPlantEquipments();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="running_month" class="control-label">Running Month {!! validation_error($errors->first('running_month'),'running_month') !!}</label>
            {!! Form::selectMonth('running_month', null, ['class'=>'form-control chosen-select','id' => 'running_month', 'onchange' => 'getPlantEquipments();']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
             <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id', 'onchange' => 'getPlantEquipments();']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="plant-equipment-table" class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th width="8%">Code</th>
                        <th>Plant Equipment</th>
                        <th width="16%" class="text-center">Starting</th>
                        <th width="16%" class="text-center">Ending</th>
                        <th width="16%" class="text-center">Difference</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" align="center">No Plant Equipment Found!</td>
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
function getPlantEquipments() 
{
    var runningYear  = $('#running_year').val();
    var runningMonth = $('#running_month').val();
    var plantId = $('#plant_id').val();

    if(!runningYear || !runningMonth || !plantId) {

        tableRow = '<tr><td colspan="5" align="center">No plant equipment found!</td></tr>';

        $('#plant-equipment-table tbody').html(tableRow);

        return;
    }

    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('equipment-running-hours/get-plant-equipments') }}',
        method:'POST',
        data:$('#equipment-running-hour-create-form').serialize(),
        dataType:'JSON',
        success:function(response) {
            if(response.status == 400) {
                $.each(response.error, function(index, value) {
                    $('#ve-'+index).html('['+ value +']');
                });
            }else {
                if(response.type == 'error') {
                    toastMsg(response.message, response.type);

                    return;
                }

                // console.log(response);
                var tableRow = '';

                if(response.length) {  

                    $.each(response, function(key, value) {
                            
                        tableRow += '<tr><td class="align-middle"><input type="hidden" name="equipment_running_hour_id[]" value="'+ value.equipment_running_hour_id +'" />'+ value.code +'</td><td class="align-middle">'+ value.name +'<input type="hidden" name="plant_equipment_id[]" value="'+ value.plant_equipment_id +'" /></td><td class="text-center align-middle">'+ value.start_value +'<input type="hidden" name="start_value[]" value="'+ value.start_value +'" /></td><td class="text-center align-middle"><input type="number" name="end_value[]" value="'+ value.end_value +'" class="form-control" min="0" onchange="getDiffValue(this);" step="any" required/></td><td class="text-center align-middle">'+ (value.diff_value ? value.diff_value : '') +'</td></tr>';
                    });
                }else {
                    tableRow += '<tr><td colspan="5" align="center">No plant equipment found!</td></tr>';
                }

                $('#plant-equipment-table tbody').html(tableRow);
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

function getDiffValue(obj)
{
    var endValueElem = $(obj);
    var endValue = parseFloat(endValueElem.val());

    if(isNaN(endValue)) {
        alert('Please add a number for end value');
        endValueElem.parent().addClass('has-error');
        endValueElem.val(1);

        return;
    }

    if(!endValue) {
        alert('End value can not be empty');
        endValueElem.parent().addClass('has-error');
        endValueElem.val(1);

        return;
    }

    var tableRowElem = endValueElem.closest('tr');
    var startValue = parseFloat(tableRowElem.find('input[name^=start_value]').val());

    if(startValue > endValue) {
        alert('End value can not be smaller than start value');
        endValueElem.parent().addClass('has-error');

        return;
    }
    var diffValue = parseFloat(endValue - startValue).toFixed(2);

    tableRowElem.find('td:nth-child(5)').text(diffValue);
}

function addEquipmentRunningHours()
{
    $('.validation-error').text('*');
    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('equipment-running-hours') }}',
        method:'POST',
        data:$('#equipment-running-hour-create-form').serialize(),
        success:function(response) {
            if(response.status === 400){
                //validation error
                $.each(response.error, function(index, value) {
                    $("#ve-"+index).html('['+value+']');
                });
            }else{
                toastMsg(response.message, response.type);
                
                if(response.type == 'success'){
                    setTimeout(function(){
                        location.href = '{{ url('equipment-running-hours/list') }}';

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
        viewMode: 'years',
        format: 'YYYY'
    }).on('dp.hide', function() {
        getPlantEquipments();
    });

    $('#running_month').val({{ date('m') }}).trigger('chosen:updated');
})();
</script>

@endsection


