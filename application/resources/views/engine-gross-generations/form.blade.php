<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <strong>OP Code: </strong>{{ $op_code }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select','id' => 'plant_id', 'onchange' => 'getEngines();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="op_date" class="control-label">Operation Date {!! validation_error($errors->first('op_date'),'op_date') !!}</label>
            {!! Form::text('op_date', old('op_date') ? old('op_date') : date('Y-m-d'), ['class'=>'form-control datepicker','placeholder' => 'YYYY-MM-DD', 'id' => 'op_date', 'onchange' => 'getEngines();']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="engine-gross-generation-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Engine</th>
                        <th width="12%" class="text-center">Start Time</th>
                        <th width="12%" class="text-center">End Time</th>
                        <th width="12%" class="text-center">Diff Time</th>
                        <th width="20%" class="text-center">Start MWH</th>
                        <th width="20%" class="text-center">End MWH</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" align="center">No engine found!</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
    if(elem.attr('name').indexOf('op_mwh') >= 0) {
        elem.val(0);
    }else {
        elem.val('0:00');
    }

    return;
}

function getEngines() 
{
    var plantId = $('#plant_id').val();
    var po_date = $('#op_date').val();

    if(!plantId || !po_date) {

        tableRow = '<tr><td colspan="6" align="center">No engine found!</td></tr>';

        $('#engine-gross-generation-table tbody').html(tableRow);
        $('#engine-gross-generation-table tfoot').remove();
        return;
    }

    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('engine-gross-generations/get-engines') }}/'+plantId,
        method:'POST',
        data:$('#engine-gross-generation-create-form').serialize(),
        dataType:'JSON',
        success:function(response) {
            if(response.type == 'error') {
                toastMsg(response.message, response.type);

                return;
            }

            // console.log(response);
            var tableRow = '';

            if(response.length) {  

                $.each(response, function(key, value) {

                    tableRow += '<tr><td>'+ value.name +'<input type="hidden" name="engine_id[]" value="'+ value.id +'" /></td><td class="text-center"><input type="text" name="start_time[]" value="0:00" class="timepicker form-control"/></td><td><input type="text" name="end_time[]" value="0:00" class="timepicker form-control"/></td><td class="text-center">0:00<input type="hidden" name="diff_time[]" value="0:00" step="any"/></td><td class="text-center">'+value.end_op_mwh+'<input type="hidden" name="start_op_mwh[]" value="'+value.end_op_mwh+'" class="form-control"/></td><td><input type="number" name="end_op_mwh[]" value="0.00" class="form-control" step="any" onchange="updateEngineGrossGenerationInfo(this);" /></td></tr>';
                });
            }else {
                tableRow += '<tr><td colspan="6" align="center">No engine found!</td></tr>';
            }

            $('#engine-gross-generation-table tbody').html(tableRow);

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
                    // var date = new Date();
                    // var h = date.getHours();
                    // var m = date.getMinutes();
                    alert('The value must be time value');
                    setDefaultValue(elem);
                }

                var tableRow = $(this).closest('tr');
                var startTimeElem = tableRow.find('input[name^=start_time]');
                var startTime = startTimeElem.val();
                var startTimeArr = startTime.split(':');
                var startTimeHour = parseInt(startTimeArr[0]);
                var startTimeMinute = parseInt(startTimeArr[1]);

                var endTimeElem = tableRow.find('input[name^=end_time]');
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

                if((startTimeHour == endTimeHour) && (startTimeMinute > endTimeMinute)) {
                    alert('End time must be greater than start time');
                    startTimeElem.parent().addClass('has-error');
                    endTimeElem.parent().addClass('has-error');

                    return;
                }else {
                    startTimeElem.parent().removeClass('has-error');
                    endTimeElem.parent().removeClass('has-error');
                }

                calculateEngineGrossGeneration();
            });

            calculateEngineGrossGeneration();

        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' + thrownError);
        }
    });
}

function updateEngineGrossGenerationInfo(obj)
{
    var elem = $(obj);
    var elemValue = $(obj).val();

    if(!elemValue) {
        alert('The value should not be empty');
        setDefaultValue(elem);
    }

    if(isNaN(elemValue)) {
        alert('The value must be number value');
        setDefaultValue(elem);
    }

    calculateEngineGrossGeneration();
}

function calculateEngineGrossGeneration()
{
    var totalDiffTime = '00:00';
    var totalStartPoMwh = 0;
    var totalEndPoMwh = 0;

    $('#engine-gross-generation-table tbody tr').each(function() {
        
        var startTimeElem = $(this).find('input[name^=start_time]');
        var startTime = startTimeElem.val();
        var startTimeArr = startTime.split(':');
        var startTimeHour = parseInt(startTimeArr[0]);
        var startTimeMinute = parseInt(startTimeArr[1]);

        var endTimeElem = $(this).find('input[name^=end_time]');
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

        if((startTimeHour == endTimeHour) && (startTimeMinute > endTimeMinute)) {
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
        // min = ((min/60)*100).toString()
        // min = min.toString()
        diffTime = hour + ":" + min.padDigit();
        
        $(this).find('td:nth-child(4)').html(diffTime + '<input type="hidden" name="diff_time[]" value="'+diffTime+'" step="any"/>');

        // Get total diff time
        totalDiffTime = totalDiffTime.split(':');
        diffTime = diffTime.split(':');
        mins = Number(totalDiffTime[1]) + Number(diffTime[1]);
        minhours = Math.floor(parseInt(mins / 60));
        hrs = Number(totalDiffTime[0]) + Number(diffTime[0]) + minhours;
        mins = mins%60;

        totalDiffTime = hrs.padDigit() + ':' + mins.padDigit();

        var startOpMwhElem = $(this).find('input[name^=start_op_mwh]');
        var endOpMwhElem = $(this).find('input[name^=end_op_mwh]');
        totalStartPoMwh += parseFloat(startOpMwhElem.val());
        totalEndPoMwh += parseFloat(endOpMwhElem.val());
    });

    if($('#engine-gross-generation-table tfoot').length) {
        $('#engine-gross-generation-table tfoot').remove();
    }
    // if(totalEndPoMwh || totalEndPoMwh) {
    $('<tfoot class="font-weight-bold"><tr><td colspan="3" align="right">Total</td><td class="text-center">' +totalDiffTime+ '</td><td class="text-center">' +totalStartPoMwh.toFixed(2)+ '</td><td>'+totalEndPoMwh.toFixed(2)+'</td></tr></tfoot>').insertAfter('#engine-gross-generation-table tbody');
    // }
}

function addEnergyGrossGenerations()
{
    if($('#engine-gross-generation-table tfoot').length) {
        calculateEngineGrossGeneration();
    }
    
    $('.validation-error').text('*');
    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('engine-gross-generations') }}',
        method:'POST',
        data:$('#engine-gross-generation-create-form').serialize(),
        success:function(response) {
            if(response.status === 400){
                //validation error
                $.each(response.error, function(index, value) {
                    $("#ve-"+index).html('['+value+']');
                });
            }else{
                toastMsg(response.message, response.type);
                
                if(response.status === 200){
                    setTimeout(function(){
                        location.href = '{{ url('engine-gross-generations/list') }}';

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
    $('.timepicker').timepicker().on('changeTime.timepicker', function() {
        alert('hi');
    });
})();
</script>

@endsection


