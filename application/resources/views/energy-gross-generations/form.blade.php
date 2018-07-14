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
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select','id' => 'plant_id', 'onchange' => 'getMeters();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="op_date" class="control-label">Operation Date {!! validation_error($errors->first('op_date'),'op_date') !!}</label>
            {!! Form::text('op_date', old('op_date') ? old('op_date') : date('Y-m-d'), ['class'=>'form-control datepicker','placeholder' => 'YYYY-MM-DD', 'id' => 'op_date']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="energy-gross-generation-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">Energy Meter</th>
                        <th colspan="2" class="text-center">Main Billing Meter Export (KWH)</th>
                        <th colspan="2" class="text-center">Main Billing Meter Import (KWH)</th>
                        <th colspan="2" class="text-center">Main Billing Meter Export (KVARH)</th>
                        <th colspan="2" class="text-center">Main Billing Meter Import (KVARH)</th>
                    </tr>
                    <tr>
                        <th width="10%" class="text-center">Start</th>
                        <th width="10%" class="text-center">End</th>
                        <th width="10%" class="text-center">Start</th>
                        <th width="10%" class="text-center">End</th>
                        <th width="10%" class="text-center">Start</th>
                        <th width="10%" class="text-center">End</th>
                        <th width="10%" class="text-center">Start</th>
                        <th width="10%" class="text-center">End</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" align="center">No energy found!</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

<script>

function getMeters() 
{
    var plantId = $('#plant_id').val();
    // var po_date = $('#op_date').val();

    if(!plantId) {

        tableRow = '<tr><td colspan="9" align="center">No energy found!</td></tr>';

        $('#energy-gross-generation-table tbody').html(tableRow);

        return;
    }

    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('energy-gross-generations/get-energies') }}/'+plantId,
        method:'POST',
        /*data:$('#energy-gross-generation-create-form').serialize(),*/
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

                    tableRow += '<tr><td>'+ value.name +'<input type="hidden" name="meter_id[]" value="'+ value.id +'" /></td><td class="text-center"><input type="number" name="export_start_kwh[]" value="0" class="form-control" min="1" step="any" required/></td><td><input type="number" name="export_end_kwh[]" value="0" class="form-control" min="1" step="any" required/></td><td class="text-center"><input type="number" name="import_start_kwh[]" value="0" class="form-control" min="1" step="any" required/></td><td><input type="number" name="import_end_kwh[]" value="0" class="form-control" min="1" step="any" required/></td><td class="text-center"><input type="number" name="export_start_kvarh[]" value="0" class="form-control" min="1" step="any" required/></td><td><input type="number" name="export_end_kvarh[]" value="0" class="form-control" min="1" step="any" required/></td><td class="text-center"><input type="number" name="import_start_kvarh[]" value="0" class="form-control" min="1" step="any" required/></td><td><input type="number" name="import_end_kvarh[]" value="0" class="form-control" min="1" step="any" required/></td></tr>';
                });
            }else {
                tableRow += '<tr><td colspan="9" align="center">No energy found!</td></tr>';
            }

            $('#energy-gross-generation-table tbody').html(tableRow);
        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' + thrownError);
        }
    });
}

function addEnergyGrossGenerations()
{
    $('.validation-error').text('*');
    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('energy-gross-generations') }}',
        method:'POST',
        data:$('#energy-gross-generation-create-form').serialize(),
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
                        location.href = '{{ url('energy-gross-generations/list') }}';

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
</script>

@endsection


