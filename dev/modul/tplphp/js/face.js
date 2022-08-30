$(document).on('click', '.pseudocheckbox', function() {

    if ($(this).siblings('.checknone').prop("checked") === true) {
        $(this).siblings('input[type="hidden"]').val("off");
    } else {
        $(this).siblings('input[type="hidden"]').val("on");
    }

});

function timenone() {
    $('.timest2').hide(300);
}
setTimeout(timenone, 500);