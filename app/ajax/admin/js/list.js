
$(document).on('click','input[name="delete-insert-include"]',function() {
var id = $(this).siblings('input[name="id-include"]').val();
var table = $('.h1_razdel').html();
    $.ajax({
            type: "POST",
            url: "/list/remove_file_ajax.face",
            data: {table:table,id:id},
             success: function(data){


}        });


});

