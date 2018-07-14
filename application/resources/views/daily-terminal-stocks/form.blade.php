
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="terminal_id" class="control-label">Terminal {!! validation_error($errors->first('terminal_id'),'terminal_id') !!}</label>
            {!! Form::select('terminal_id', $terminals, null, ['class'=>'form-control chosen-select','id' => 'terminal_id', 'onchange' => 'getTanks();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="transaction_date" class="control-label">Transaction Date {!! validation_error($errors->first('transaction_date'),'transaction_date') !!}</label>
            {!! Form::text('transaction_date', old('transaction_date') ? old('transaction_date') : (!empty($motherVesselCarring) ? $motherVesselCarring->transaction_date : date('Y-m-d')), ['class'=>'form-control datepicker','id' => 'transaction_date', 'onchange' => 'getTanks();']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="daily-terminal-stock-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="12%">Tank#</th>
                        <th width="16%" class="text-center">Tank Capacity</th>
                        <th width="16%" class="text-center">Stock Input(MT)</th>
                        <th class="text-center">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" align="center">No tank found!</td>
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
function getTanks() 
{
    var transactionDate = $('#transaction_date').val();
    var terminalId = $('#terminal_id').val();

    if(!transactionDate || !terminalId) {

        tableRow = '<tr><td colspan="4" align="center">No tank found!</td></tr>';

        $('#daily-terminal-stock-table tbody').html(tableRow);

        return;
    }

    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('daily-terminal-stocks/get-tanks') }}',
        method:'POST',
        data:$('#daily-terminal-stock-create-form').serialize(),
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
                            
                        tableRow += '<tr><td>'+ value.number +'<input type="hidden" name="tank_id[]" value="'+ value.id +'" /></td><td class="text-center">'+ value.capacity +'</td><td class="text-center"><input type="text" name="tank_stock[]" value="'+ value.tank_stock +'" class="form-control"/></td><td class="text-center"><textarea  name="comment[]" class="form-control" cols="30" rows="1" >'+ (value.comment ? value.comment : '') +'</textarea></td></tr>';
                    });
                }else {
                    tableRow += '<tr><td colspan="5" align="center">No page found!</td></tr>';
                }

                $('#daily-terminal-stock-table tbody').html(tableRow);
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

function addDailyTerminalStocks()
{
    $('.validation-error').text('*');
    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('daily-terminal-stocks') }}',
        method:'POST',
        data:$('#daily-terminal-stock-create-form').serialize(),
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
                        location.href = '{{ url('daily-terminal-stocks/list') }}';

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
   
})();
</script>

@endsection


