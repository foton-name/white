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

$(document).on('click', '.diff-f', function() {
    $('.file-diff').on('scroll', function() {
        var s = $(".file-diff").scrollTop();
        console.log(s);
        $('.cod').animate({ scrollTop: s }, 0);
        $('#scripti').animate({ scrollTop: s }, 0);
    });
});