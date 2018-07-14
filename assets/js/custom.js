$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('.alert-dialog').click(function(){
        var message = "<i class='fa fa-info-circle'></i> "+$(this).attr('data-message');
        $("#global-alert-modal #globalAlertFrm").attr('action',$(this).attr('data-action'));
        $("#global-alert-modal #globalAlertFrm .modal-body").html(message);
        $("input[name=hdnResource]").val($(this).attr('data-id'));        
        $("#global-alert-modal").modal('show');
    });

    $(".chosen-select").chosen({width: "100%"});

    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });

    $('[data-toggle="tooltip"]').tooltip();
    
    $('.dropdown-submenu a.test').on("click", function(e){
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
});

function toastMsg(message, type){
    $.toast({
        //heading: type,
        text: message,
        showHideTransition: 'slide',
        icon: type,
        allowToastClose: true,
        hideAfter: 6000,   // in milli seconds
        position: 'top-right'
    });
}
