<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <strong>Gen Code: </strong>{{ $gen_code }}
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
            <label for="gen_date" class="control-label">Gen Date {!! validation_error($errors->first('gen_date'),'gen_date') !!}</label>
            {!! Form::text('gen_date', old('gen_date') ? old('gen_date') : date('Y-m-d'), ['class'=>'form-control datepicker','placeholder' => 'YYYY-MM-DD', 'id' => 'gen_date', 'onchange' => 'getMeters();']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="energy-meter-generation-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">Energy Meter</th>
                        <th colspan="2" class="text-center">Export (MWH)</th>
                        <th colspan="2" class="text-center">Import (MWH)</th>
                    </tr>
                    <tr>
                        <th width="16%" class="text-center">Start</th>
                        <th width="16%" class="text-center">End</th>
                        <th width="16%" class="text-center">Start</th>
                        <th width="16%" class="text-center">End</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" align="center">No energy meter found!</td>
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
    var genDate = $('#gen_date').val();

    if(!plantId || !genDate) {

        tableRow = '<tr><td colspan="5" align="center">No energy meter found!</td></tr>';

        $('#energy-meter-generation-table tbody').html(tableRow);

        return;
    }

    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('energy-meter-generations/get-energy-meters') }}/'+plantId,
        method:'POST',
        data:$('#energy-meter-generation-create-form').serialize(),
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

                    tableRow += '<tr><td>'+ value.name +'<input type="hidden" name="meter_id[]" value="'+ value.id +'" /></td><td class="text-center"><input type="number" name="export_start[]" value="'+ value.export_start +'" class="form-control" min="1" step="any" required/></td><td><input type="number" name="export_end[]" value="'+ value.export_end +'" class="form-control" min="1" step="any" required/></td><td class="text-center"><input type="number" name="import_start[]" value="'+ value.import_start +'" class="form-control" min="1" step="any" required/></td><td><input type="number" name="import_end[]" value="'+ value.import_end +'" class="form-control" min="1" step="any" required/></td></tr>';
                });
            }else {
                tableRow += '<tr><td colspan="5" align="center">No energy meter found!</td></tr>';
            }

            $('#energy-meter-generation-table tbody').html(tableRow);
        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' + thrownError);
        }
    });
}

function addEnergyMeterGenerations()
{
    $('.validation-error').text('*');
    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('energy-meter-generations') }}',
        method:'POST',
        data:$('#energy-meter-generation-create-form').serialize(),
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
                        location.href = '{{ url('energy-meter-generations/list') }}';

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


