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
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select','id' => 'plant_id', 'onchange' => 'getEngines();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="gen_date" class="control-label">Gen Date {!! validation_error($errors->first('gen_date'),'gen_date') !!}</label>
            {!! Form::text('gen_date', old('gen_date') ? old('gen_date') : date('Y-m-d'), ['class'=>'form-control datepicker','placeholder' => 'YYYY-MM-DD', 'id' => 'gen_date']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="engine-generation-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Engine</th>
                        <th width="12%" class="text-center">Start (MWH)</th>
                        <th width="12%" class="text-center">End (MWH)</th>
                        <th width="12%" class="text-center">Total (MWH)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" align="center">No engine found!</td>
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
function setDefaultValue(elem)
{
    elem.addClass('is-invalid').val(0);

    return;
}

function removeInvalidClass(elem)
{
    elem.removeClass('is-invalid');
}

function getEngines() 
{
    var plantId = $('#plant_id').val();
    var gen_date = $('#gen_date').val();

    if(!plantId || !gen_date) {

        tableRow = '<tr><td colspan="4" align="center">No engine found!</td></tr>';

        $('#engine-generation-table tbody').html(tableRow);

        return;
    }

    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('engine-generations/get-engines') }}/'+plantId,
        method:'POST',
        data:$('#engine-generation-create-form').serialize(),
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

                    var total = value.end - value.start;

                    tableRow += '<tr><td>'+ value.name +'<input type="hidden" name="engine_id[]" value="'+ value.id +'" /></td><td class="text-center"><input type="number" name="start[]" value="'+ value.start +'" class="form-control" step="any" onchange="updateEngineGenerationInfo(this);"/></td><td><input type="number" name="end[]" value="'+ value.end +'" class="form-control" step="any" onchange="updateEngineGenerationInfo(this);"/></td><td class="text-center">'+ parseFloat(total).toFixed(2) +'</td></tr>';
                });
            }else {
                tableRow += '<tr><td colspan="4" align="center">No engine found!</td></tr>';
            }

            $('#engine-generation-table tbody').html(tableRow);

            calculateEngineGeneration();

        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' + thrownError);
        }
    });
}

function updateEngineGenerationInfo(obj)
{
    var elem = $(obj);
    var elemName = elem.attr('name').replace('[]', '');
    var elemValue = elem.val();

    if(!elemValue) {
        alert('The '+ elemName +' should not be empty');
        setDefaultValue(elem);
    }else {
        removeInvalidClass(elem);
    }

    if(isNaN(elemValue)) {
        alert('The '+ elemName +' must be number value');
        setDefaultValue(elem);
    }else {
        removeInvalidClass(elem);
    }

    if(elemValue < 0) {
        alert('The ' + elemName +' value can not be smaller than 0');
        setDefaultValue(elem);
    }else {
        removeInvalidClass(elem);
    }

    var tableRow = elem.closest('tr');
    var startElem = tableRow.find('input[name^=start]');
    var startElemValue = parseFloat(startElem.val());

    var endElem = tableRow.find('input[name^=end]');
    var endElemValue = parseFloat(endElem.val());

    if(startElemValue > endElemValue) {
        alert('Start value can not be larger than end value');

        startElem.addClass('is-invalid');
        endElem.addClass('is-invalid');

        return;
    }else {
        removeInvalidClass(startElem);
        removeInvalidClass(endElem);
    }

    calculateEngineGeneration();
}

function calculateEngineGeneration()
{
    var totalSum = 0;

    $('#engine-generation-table tbody tr').each(function() {
        
        var startElem = $(this).find('input[name^=start]');
        var startElemValue = parseFloat(startElem.val());

        var endElem = $(this).find('input[name^=end]');
        var endElemValue = parseFloat(endElem.val());

        var total = endElemValue - startElemValue;

        $(this).find('td:nth-child(4)').text(total.toFixed(2));
        totalSum += total;
    });

    if($('#engine-generation-table tfoot').length) {
        $('#engine-generation-table tfoot').remove();
    }
    $('<tfoot class="font-weight-bold"><tr><td colspan="3" align="right">Total</td><td class="text-center">' +totalSum.toFixed(2)+ '</td></tr></tfoot>').insertAfter('#engine-generation-table tbody');
}

function addEngineGenerations()
{
    if($('#engine-generation-table tfoot').length) {
        calculateEngineGeneration();
    }
    
    $('.validation-error').text('*');
    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('engine-generations') }}',
        method:'POST',
        data:$('#engine-generation-create-form').serialize(),
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
                        location.href = '{{ url('engine-generations/list') }}';

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


