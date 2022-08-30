$(document).on('click','.new_sh',function() {
    $.ajax({ 
            type: "POST",
            url: "/tplsite/sozd_sh.ajax",
            success: function(data){
                alert('Шаблон успешно создан');
                document.location.href='/tplsite.modul';
            }
    });
});

$(document).on('click','.copy_sh',function() {
   var sh = $(".item_sh input[type='radio']:checked").val();
   if(sh===undefined){
       alert('Выберите шаблон');
   }
   else{
        $.ajax({ 
                type: "POST",
                url: "/tplsite/copy_sh.ajax",
                data:{sh:sh},
                success: function(data){
                    alert('Шаблон  '+sh+' успешно скопирован');
                    document.location.href='/tplsite.modul';
                }
        });
   }
});

$(document).on('click','.del_site',function() {
    var sh = $(".item_site input[type='radio']:checked").val();
    if(sh===undefined){
       alert('Выберите сайт');
    }
    else{
       var isdel = confirm("Вы уверены, что хотите удалить сайт?");
        if(isdel){
            $.ajax({ 
                    type: "POST",
                    url: "/tplsite/del_site.ajax",
                    data:{sh:sh},
                    success: function(data){
                        alert('Сайт '+sh+' успешно удален');
                        document.location.href='/tplsite.modul';
                    }
            });
        }
    }
});
$(document).on('click','.item_sh',function() {

$(this).find('input[type="radio"]').prop('checked', true);

});
$(document).on('click','.item_site',function() {

$(this).find('input[type="radio"]').prop('checked', true);

});

$(document).on('click','.del_sh',function() {
    var sh = $(".item_sh input[type='radio']:checked").val();
    if(sh===undefined){
       alert('Выберите шаблон');
    }
    else{
       var isdel = confirm("Вы уверены, что хотите удалить шаблон?");
        if(isdel){
        $.ajax({ 
                type: "POST",
                url: "/tplsite/del_sh.ajax",
                data:{sh:sh},
                success: function(data){
                    alert('Шаблон '+sh+' успешно удален');
                    document.location.href='/tplsite.modul';
                }
        });
    }
}
});
$(document).on('click','.copy_site',function() {
    var sh = $(".item_sh input[type='radio']:checked").val();
    if(sh===undefined){
       alert('Выберите шаблон');
    }
    else{
        var site = prompt('Укажите директорию сайта',"site");
        if(site!==undefined && site!==''){
                var isdel = confirm("Все даные сайта будут удалены, и загружены с шаблона, если Вы сохранили данные сайта, или они Вам не нужны, нажмите 'OK'");
                if(isdel){
                $.ajax({ 
                        type: "POST",
                        url: "/tplsite/copy_site.ajax",
                        data:{sh:sh,site:site},
                        success: function(data){
                            alert('Шаблон '+sh+' успешно установлен');
                            document.location.href='/tplsite.modul';
                        }
                });
            }
        }
        else{
            alert('Укажите директорию сайта');
        }
    }
    
});
$(document).on('click','.ustanovit_sh',function() {
        var sh = $(".item_sh input[type='radio']:checked").val();
    if(sh===undefined){
       alert('Выберите шаблон');
    }
    else{
        var isdel = confirm("Все даные сайта будут удалены, и загружены с шаблона, если Вы сохранили данные текущего сайта, или они Вам не нужны, нажмите 'OK'");
        if(isdel){
        $.ajax({ 
                type: "POST",
                url: "/tplsite/ust_sh.ajax",
                data:{sh:sh},
                success: function(data){
                    alert('Шаблон '+sh+' успешно установлен');
                    document.location.href='/tplsite.modul';
                }
        });
    }
}
});
