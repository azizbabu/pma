
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="role_id" class="control-label">Role {!! validation_error($errors->first('role_id'),'role_id') !!}</label>
            {!! Form::select('role_id', $roles, null, ['class'=>'form-control chosen-select','id' => 'role_id', 'onchange' => 'getPages();']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="module_id" class="control-label">Module {!! validation_error($errors->first('module_id'),'module_id') !!}</label>
            {!! Form::select('module_id', $modules, null, ['class'=>'form-control chosen-select','id' => 'module_id', 'onchange' => 'getPages();']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="permission-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th width="7%" class="text-center">Insert</th>
                        <th width="7%" class="text-center">Update</th>
                        <th width="7%" class="text-center">Delete</th>
                        <th width="7%" class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" align="center">No page found!</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('custom-style')
{{-- iCheck --}}
{!! Html::style($assets . '/plugins/icheck/skins/minimal/blue.css') !!}
@endsection

@section('custom-script')
{{-- iCheck --}}
{!! Html::script($assets . '/plugins/icheck/icheck.min.js') !!}

<script>
function getPages() 
{
    var roleId = $('#role_id').val();
    var moduleId = $('#module_id').val();

    if(!roleId || !moduleId) {

        tableRow = '<tr><td colspan="5" align="center">No page found!</td></tr>';

        $('#permission-table tbody').html(tableRow);

        return;
    }

    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('permissions/get-pages') }}/'+moduleId,
        method:'POST',
        data:$('#permission-create-form').serialize(),
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
                        
                    if(value.can_create) {
                        canCreateChecked = ' checked="checked"';
                    }else {
                        canCreateChecked = '';
                    }

                    if(value.can_update) {
                        canUpdateChecked = ' checked="checked"';
                    }else {
                        canUpdateChecked = '';
                    }

                    if(value.can_delete) {
                        canDeleteChecked = ' checked="checked"';
                    }else {
                        canDeleteChecked = '';
                    }

                    if(value.can_view) {
                        canViewChecked = ' checked="checked"';
                    }else {
                        canViewChecked = '';
                    }

                    tableRow += '<tr><td>'+ value.name +'<input type="hidden" name="page_id['+ value.id +']" value="'+ value.id +'" /></td><td class="text-center"><input type="checkbox" name="can_create['+ value.id +']" value="1"'+ canCreateChecked +'/></td><td class="text-center"><input type="checkbox" name="can_update['+ value.id +']" value="1"'+ canUpdateChecked +'/></td><td class="text-center"><input type="checkbox" name="can_delete['+ value.id +']" value="1"'+ canDeleteChecked +'/></td><td class="text-center"><input type="checkbox" name="can_view['+ value.id +']" value="1"'+ canViewChecked +'/></td></tr>';
                });
            }else {
                tableRow += '<tr><td colspan="5" align="center">No page found!</td></tr>';
            }

            $('#permission-table tbody').html(tableRow);

            $('input[type="checkbox"]').iCheck({
                 checkboxClass: 'icheckbox_minimal-blue',
                 increaseArea: '20%' // optional
            });

        },
        complete:function() {
            $('#ajaxloader').addClass('hide');
        },
        error:function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + ' ' + thrownError);
        }
    });
}

function addPermissions()
{
    $('.validation-error').text('*');
    $('#ajaxloader').removeClass('hide');
    $.ajax({
        url:'{{ url('permissions') }}',
        method:'POST',
        data:$('#permission-create-form').serialize(),
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
                        location.href = '{{ url('permissions/list') }}';

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
    $('input[type="checkbox"]').iCheck({
         checkboxClass: 'icheckbox_minimal-blue',
         increaseArea: '20%' // optional
    });
})();
</script>

@endsection


