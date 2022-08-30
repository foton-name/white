$(document).on('click','.saves-f',function(){ 
var text = $('.codi').val();   
var path=$('.open-f').attr('path'); 

$.ajax({ 
            type: "POST",
            url: "/git/saves_p.ajax",
            data: {text:text,path:path},
             success: function(data){

}
});
});

$(document).on('click','.upp_s',function(){ 
    var user=$('.user_s').val();
    $('.uploads_back').show();
    $.ajax({ 
            type: "POST",
            url: "/git/saveupp.ajax",
            data: {user:user},
             success: function(data){
                  $('.uploads_back').hide();

alert('Проект импортирован');
document.location.href='/git.modul';

}
});
    
});
$(document).on('click','.save_s',function(){ 
    $('.uploads_back').show();
var b=$('.ub').val();
var u=$('.ul').val();
var p=$('.upass').val();
var user=$('.user_s').val();
if(b!='' && u!='' && p!='' && user!=''){
$.ajax({ 
            type: "POST",
            url: "/git/saveset.ajax",
            data: {b:b,u:u,p:p,user:user},
             success: function(data){
                 $('.uploads_back').hide();
                alert('База импортирована');
document.location.href='/git.modul';

}
});
}
else{
    alert('Заполните все значения');
}
});

$(document).on('click','.uploadvetka',function(){ 

var path=$('.open-f').attr('path'); 
var text=$('.codi').html();
$.ajax({ 
            type: "POST",
            url: "/git/openv.ajax",
            data: {path:path,text:text},
             success: function(data){
alert(data);

}
});
});


$(document).on('click','.selectv',function(){ 

var path=$(this).attr('path'); 
$.ajax({ 
            type: "POST",
            url: "/git/selectv.ajax",
            data: {path:path},
             success: function(data){
alert('Ветка выбрана');

}
});
});
$(document).on('click','.delv',function(){ 

var path=$(this).attr('path'); 
var obj=$(this).parent(".vu2");
$.ajax({ 
            type: "POST",
            url: "/git/delv.ajax",
            data: {path:path},
             success: function(data){
obj.remove();

}
});
});

$(document).on('click','.create-v',function(){ 
if($('.new-vetka').val()!=''){
var path=$('.new-vetka').val(); 

$.ajax({ 
            type: "POST",
            url: "/git/createv.ajax",
            data: {path:path},
             success: function(data){
$('.divv').append(data);

}
});
}
else{
    alert('Введите название ветки');
}
});

$(document).on('click','.vetki',function(){ 
if($('.divv').css('display')=='none'){
    $('.divv').show(200);
}
else{
    $('.divv').hide(200);
}


});


$(document).on('click','.otk-f',function(){ 
var text = $('.hid-t').val();   
$('.codi').val(text); 
$('.cod').val(''); 
$('.cod').html(''); 

});


$(document).on('click','.del-f',function(){ 
  
var path=$(this).attr('path'); 
var ids=$(this).attr('ids');
var isdel = confirm("Вы уверены, что хотите удалить файл?");
if(isdel){
$.ajax({ 
            type: "POST",
            url: "/git/delete_dir.ajax",
            data: {path:path},
             success: function(data){
alert('Файл успешно удален');
$('.file'+ids).remove();
}
});
}

});


$(document).on('click','.user',function(){ 
  
var ids=$(this).attr('ids');

$.ajax({ 
            type: "POST",
            url: "/git/useri.ajax",
            data: {login:ids},
             success: function(data){

$('.body').html(data);
}
});


});

$(document).on('click','.homei',function(){ 
  
var ids=$(this).attr('ids');

$.ajax({ 
            type: "POST",
            url: "/git/userhome.ajax",
            data: {idsu:ids},
             success: function(data){

$('.body').html(data);

}
});


});


$(document).on('click','.vu',function(){ 
  
var path=$(this).attr('path');
var ids=$(this).attr('idsu');
$.ajax({ 
            type: "POST",
            url: "/git/userhome.ajax",
            data: {path:path,idsu:ids},
             success: function(data){

$('.body').html(data);

}
});


});



$(document).on('click','.settingi',function(){ 
  
var ids=$(this).attr('ids');

$.ajax({ 
            type: "POST",
            url: "/git/settinguser.ajax",
            data: {idsu:ids},
             success: function(data){

$('.body').html(data);

}
});


});


$(document).on('click','.vetkii',function(){ 
  
var ids=$(this).attr('ids');

$.ajax({ 
            type: "POST",
            url: "/git/vetkiuser.ajax",
            data: {idsu:ids},
             success: function(data){

$('.body').html(data);

}
});


});


$(document).on('click','.creates_file_dir',function(){

var path = $('.open-f').attr('path');
var file = $('.plus-insert  .new-files').val();
var format = $('.plus-insert .format').val();
if(file!=''){
 $('.up-img').css('display','block');
if(path==undefined){
path=$('.dir-f').attr('path');

}

$.ajax({ 
            type: "POST",   
            url: "/git/createsfile.ajax",
            data: {path:path,file:file,format:format},
             success: function(data){  
 
  $('.up-img').css('display','none');

}                   
});  
}
else{
alert('Введите название');
}

});
      $(document).on('click','.names-f',function(){ 
var path= $(this).attr('path');   
var name=$(this).html();
$.ajax({ 
            type: "POST",
            url: "/git/filepath.ajax",
            data: {name:name,path:path},
             success: function(data){
$('.td-f').html(data);

}
});
});
  $(document).on('click','.dir-f',function(){ 
var path= $(this).attr('path');

var ids=$(this).attr('ids');
if($('.dir'+ids).html()==''){
$.ajax({ 
            type: "POST",
            url: "/git/filesp2.ajax",
            data: {path:path,type:'sp'},
             success: function(data){
 
$('.dir'+ids).html(data);

}
});
}
else{
  $('.dir'+ids).html('');  
}
});


$(document).on('click','.saves-f',function(){ 
var text = $('.codi').val();   
var path=$('.open-f').attr('path'); 
if($(".sh-yes").prop('checked')) { 
    var sh='yes';
}
else{
    var sh='no';
}
$.ajax({ 
            type: "POST",
            url: "/git/saves_p.ajax",
            data: {text:text,path:path,sh:sh},
             success: function(data){


}
});
});

$(document).on('click','.upload-f',function(){ 
var text = $('.codi').val();   
var path=$('.open-f').attr('path'); 
var user = $('.userl').val();
if($(".sh-yes").prop('checked')) { 
    var sh='yes';
}
else{
    var sh='no';
}
console.log(sh);
$.ajax({ 
            type: "POST",
            url: "/git/saves_pu.ajax",
            data: {text:text,path:path,user:user,sh:sh},
             success: function(data){


}
});
});

$(document).on('click','.diff-del',function(){ 
   $('.file-diff').html('');
    $('.file-diff').hide(200);
    $('.diff-del').hide(200);
    $('.td-f').css('width','100%');
});

$(document).on('click','.diff-f',function(){ 
   var idsu=$('.userl').val();;
var path=$('.open-f').attr('path'); 
$('.file-diff').show(200);
$.ajax({ 
            type: "POST",
            url: "/git/diff_file.ajax",
            data: {path:path,idsu:idsu},
             success: function(data){
$('.file-diff').html(data);
$('.td-f').css('width','50%');
$('.diff-del').show(200);
}
});

});

$(document).on('click','.otk-f',function(){ 
var text = $('.hid-t').val();   
$('.codi').val(text); 
$('.cod').val(''); 
$('.cod').html(''); 

});

$(document).on('click','.plus-f',function(){
 
if($('.plus-insert').html()==''){
$('.plus-insert').html('<input type="text" class="new-files" ><select class="format"><option>file</option><option>directory</option></select><input type="button" class="creates_file_dir" value="+">');
}
else{
$('.plus-insert').html('');
 
}

});

