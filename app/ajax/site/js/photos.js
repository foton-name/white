$(document).on('change', '.upfotos', function() {
    files = this.files;

});
$(document).on("mouseover", ".fotos", function() {
    var id = $(this).siblings('input[name="id"]').val();
    var name = $(this).find('.upfdels').attr('fname');
    $(this).find('.upfoto').attr('ids', id + '-' + name);
});


$(document).on("click", ".delfoto_foton", function() {
    var table = $('.upfoto').attr('table');
    var path = table;
    var img = $('.upfoto').attr('img');
    var id = $(this).parents('.fotos').siblings('input[name="id"]').val();
    var img_one = $(this).attr('delfotos');
    var hidden = $('.upfdels').val();
    hidden = hidden.replace('%%%' + img_one, '');
    console.log(path + '--' + img + '--' + id + '--' + table + '--' + img_one);
    $.ajax({
        url: '/photos/ajaxfiledel.tpl',
        type: 'POST',
        data: { path: path, img: img, id: id, table: table, img_one: img_one },
        success: function(data) {

            $('div[delfotos="' + img_one + '"]').closest('.over_foto').remove();
            $('.upfdels').val(hidden);

        }
    });

});
$(document).on("click", ".upfoto", function() {
    event.stopPropagation();
    event.preventDefault();

    if (typeof(files) == "undefined") {
        alert('Выберите фото');
    } else {
        var data = new FormData();
        $.each(files, function(key, value) {
            data.append(key, value);

        });

        var table = $(this).attr('table');
        var img = $(this).attr('img');
        var id = $(this).parents('.fotos').siblings('input[name="id"]').val();
        var path = table;
        $(this).attr('ids', $(this).parents('form').find('input[name="id"]').val());
        var ids = $(this).attr('ids');
        var pathabs = $(this).attr('pathabs');
        data.append('id', id);
        data.append('img', img);
        data.append('table', table);
        data.append('path', path);

        $.ajax({
            url: '/photos/ajaxfile.tpl',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(respond, textStatus, jqXHR) {
                if (typeof respond.error === 'undefined') {
                    console.log('yes' + respond);
                    var files_path = respond.files;
                    var html = '';

                    $.each(files_path, function(key, val) {
                        val = val.replace(pathabs, '');
                        html += '%%%' + val;
                        $('div[ids="' + ids + '"]').parent('.fotos').prepend('<div class="over_foto"><div class="delfoto_foton" delfotos="' + val + '"></div><img src="' + val + '" ></div>');
                    })


                    $('.upfdels').val($('.upfdels').val() + html);
                } else {
                    console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error);

                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('ОШИБКИ AJAX запроса: ' + textStatus);


            }

        });
    }
});