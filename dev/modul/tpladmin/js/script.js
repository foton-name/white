$(document).on('click','.new_sh',function() {
    $.ajax({ 
            type: "POST",
            url: "/tpladmin/sozd_sh.ajax",
            success: function(data){
                console.log(data);
                alert('Шаблон успешно создан');
                document.location.href='/tpladmin.modul';
            }
    });
});
$(document).on('click','.copy_admin',function() {
    var sh = $(".item_sh input[type='radio']:checked").val();
    if(sh===undefined){
       alert('Выберите шаблон');
    }
    else{
        var site = prompt('Укажите директорию РСИ',"admin");
        if(site!==undefined && site!==''){
                var isdel = confirm("Все даные РСИ будут удалены, и загружены с шаблона, если Вы сохранили данные РСИ, или они Вам не нужны, нажмите 'OK'");
                if(isdel){
                $.ajax({ 
                        type: "POST",
                        url: "/tpladmin/copy_admin.ajax",
                        data:{sh:sh,site:site},
                        success: function(data){
                            alert('Шаблон '+sh+' успешно установлен, обновите страницу нажав CTRL+F5');
                            document.location.href='/tpladmin.modul';
                        }
                });
            }
        }
        else{
            alert('Укажите директорию РСИ');
        }
    }
    
});
$(document).on('click','.del_admin',function() {
    var sh = $(".item_admin input[type='radio']:checked").val();
    if(sh===undefined){
       alert('Выберите РСИ');
    }
    else{
       var isdel = confirm("Вы уверены, что хотите удалить РСИ?");
        if(isdel){
            $.ajax({ 
                    type: "POST",
                    url: "/tpladmin/del_admin.ajax",
                    data:{sh:sh},
                    success: function(data){
                        alert('РСИ '+sh+' успешно удален');
                        document.location.href='/tpladmin.modul';
                    }
            });
        }
    }
});
$(document).on('click','.copy_sh',function() {
    var sh = $(".item_sh input[type='radio']:checked").val();
    if(sh===undefined){
       alert('Выберите шаблон');
    }
    else{
        $.ajax({ 
                type: "POST",
                url: "/tpladmin/copy_sh.ajax",
                data:{sh:sh},
                success: function(data){
                    alert('Шаблон  '+sh+' успешно скопирован');
                   document.location.href='/tpladmin.modul';
                }
        });
    }
});
$(document).on('click','.item_sh',function() {
$(this).find('input[type="radio"]').prop('checked', true);
});
$(document).on('click','.item_admin',function() {
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
            var sh = $(".item_sh input[type='radio']:checked").val();
            $.ajax({ 
                    type: "POST",
                    url: "/tpladmin/del_sh.ajax",
                    data:{sh:sh},
                    success: function(data){
                        alert('Шаблон '+sh+' успешно удален');
                        document.location.href='/tpladmin.modul';
                    }
            });
        }
    }
});

$(document).on('click','.ustanovit_sh',function() {
    var sh = $(".item_sh input[type='radio']:checked").val();
    if(sh===undefined){
       alert('Выберите шаблон');
    }
    else{
        var isdel = confirm("Все даные интерфейса будут удалены, и загружены с шаблона, если Вы сохранили данные текущего интерфейса, или они Вам не нужны, нажмите 'OK'");
        if(isdel){
            var sh = $(".item_sh input[type='radio']:checked").val();
            $.ajax({ 
                    type: "POST",
                    url: "/tpladmin/ust_sh.ajax",
                    data:{sh:sh},
                    success: function(data){
                        alert('Шаблон интерфейса '+sh+' успешно установлен, обновите страницу нажав CTRL+F5');
                        document.location.href='/tpladmin.modul';
                    }
            });
        }
    }
});
