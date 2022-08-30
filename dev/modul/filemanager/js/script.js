
$(document).on('click','span.href-doc',function(){
    var href = $(this).attr('attr-doc');
    $('.'+href).show(200);
    $('.popap-doc').show();
});
$(document).on('click','.close-doc',function(){
    $('.popap-doc').hide();
    $('.doc-tabs').hide();
});
$(document).on('click','.CodeMirror-hint-active',function(){
    var selector = $(this).text();
    if($('div').is('.'+selector)){
        $('.over-code-description').html($(this).text()+' <span class="href-doc" attr-doc="'+$(this).text()+'">описание</span>');
    }
    else{
         $('.over-code-description').html($(this).text()+': описание не найдено');
    }
        $('.over-code-description').show(800);
        setTimeout("$('.over-code-description').hide(800)", 7000);
    
});
$(document).on('mouseenter','.CodeMirror-hint',function(){
    var selector = $(this).text();
    if($('div').is('.'+selector)){
        $('.over-code-description').html($(this).text()+' <span class="href-doc" attr-doc="'+$(this).text()+'">описание</span>');
    }
    else{
         $('.over-code-description').html($(this).text()+': описание не найдено');
    }
        $('.over-code-description').show(200);
        setTimeout("$('.over-code-description').hide(200)", 7000);
    
});
$(document).on('click','.cm-variable',function(){
    var selector = $(this).text();
    if($('div').is('.'+selector)){
        $('.over-code-description').html($(this).text()+' <span class="href-doc" attr-doc="'+$(this).text()+'">описание</span>');
    }
    else{
         $('.over-code-description').html($(this).text()+': описание не найдено');
    }
    $('.over-code-description').show(200);
});

$(document).on('click','.saves-f',function(){ 
    var text = $(this).parents('.item-lists').find('.codi').val(); 
    text = text.replace(/\\/g,"#slashes#");
    var path=$(this).parents('.item-lists').attr('path'); 
    $.ajax({ 
        type: "POST",
        url: "/filemanager/saves_p.ajax",
        data: {text:text,path:path},
         success: function(data){
           
        }
    });
});
function first_list(){
    $('.item-lists').each(function(i,e){
        if(i==0){
            $(e).find('.pole-f').show();
            $(e).addClass('active');
            let paths = $(e).attr('path');
            $('.open-f[path="'+paths+'"]').addClass('action');
        }
    });
}
$(document).on('click','.close-file',function(){ 
  var path = $(this).parent('.open-f').attr('path'); 
  $('.item-lists[path="'+path+'"]').find('.saves-f').mouseover();
  var codes = $('.item-lists[path="'+path+'"]').find('.codi').val();
  $.ajax({ 
      type: "POST",
      url: "/filemanager/file_change.ajax",
      data: {path:path,codes:codes},
      async:true,
      success: function(data){
          if(data=='yes'){
             var isclose = confirm("Файл на сервере отличается от вашего кода, вы уверены, что хотите закрыть страницу?");
              if(isclose){    
                $('.item-lists[path="'+path+'"]').remove();
                setTimeout(first_list, 200);    
                $('.open-f[path="'+path+'"]').parent('.head-f').remove();
              }  
          }
          else{
                $('.item-lists[path="'+path+'"]').remove();
                setTimeout(first_list, 200);    
                $('.open-f[path="'+path+'"]').parent('.head-f').remove();
          }
      }
  }); 
   
});
$(document).on('click','.del-f',function(){ 
    var path=$(this).attr('path'); 
    var ids=$(this).attr('ids');
    var isdel = confirm("Вы уверены, что хотите удалить файл?");
    if(isdel){
        $.ajax({ 
            type: "POST",
            url: "/filemanager/delete_dir.ajax",
            data: {path:path},
            success: function(data){
                alert('Файл успешно удален');
                $('.file'+ids).remove();
            }
        });
    }
});
$(document).on('click','.creates_file_dir',function(){
if($('span').is('.action-dir')){
    var path = $('.action-dir').attr('path');
}
else{
    var path = 'main';
}
var file = $('.plus-insert  .new-files').val();
var format = $('.plus-insert .format').val();
if(file!=''){
 $('.up-img').css('display','block');
$.ajax({ 
            type: "POST",   
            url: "/filemanager/createsfile.ajax",
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
$(document).on('click','.open-f',function(){ 
    var path = $(this).attr('path');
    $('.item-lists').each(function(i,e){
        $(e).find('.pole-f').hide();
        $(e).removeClass('active');
        let paths = $(e).attr('path');
        $('.open-f[path="'+paths+'"]').removeClass('action');
    });
    $('.item-lists[path="'+path+'"]').find('.pole-f').show();
    $('.item-lists[path="'+path+'"]').addClass('active');
    $('.open-f[path="'+path+'"]').addClass('action');
});
    $(document).on('click','.names-f',function(){ 
          $('.item-lists').each(function(i,e){
              $(e).find('.pole-f').hide();
              let paths = $(e).attr('path');
              $('.open-f[path="'+paths+'"]').removeClass('action');
              $(e).removeClass('active');
          });
        var path= $(this).attr('path');
        if($('div').is('[path="'+path+'"]')){
            $('.item-lists[path="'+path+'"]').find('.pole-f').show();
            $('.item-lists[path="'+path+'"]').addClass('active');
            $('.open-f[path="'+path+'"]').addClass('action');
        }
        else{
            var name=$(this).html();
            $.ajax({ 
                type: "POST",
                url: "/filemanager/filepath.ajax",
                async:false,
                data: {name:name,path:path},
                success: function(data){
                    $('.td-f').append(data);
                    $('[path="'+path+'"]').parents('.item-lists').addClass('active');
                   
                    
                    }
            });
            $.ajax({ 
                type: "POST",
                url: "/filemanager/filenames.ajax",
                async:false,
                data: {name:name,path:path},
                success: function(data){
                    $('.line-f').append(data);
                    $('.open-f[path="'+path+'"]').addClass('action');
                    }
            });
        }
        
    });
  $(document).on('click','.dir-f',function(){ 
var path= $(this).attr('path');
$('.dir-f').each(function(i,e){
    $(e).removeClass('action-dir');
});
$(this).addClass('action-dir');
var ids=$(this).attr('ids');
if($('.dir'+ids).html()==''){
$.ajax({ 
            type: "POST",
            url: "/filemanager/filesp2.ajax",
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




$(document).on('click','.otk-f',function(){ 
var text = $(this).parents('.item-lists').find('.hid-t').val();   
$(this).parents('.item-lists').find('.codi').val(text); 
});

$(document).on('click','.plus-f',function(){
 
if($('.plus-insert').html()==''){
$('.plus-insert').html('<input type="text" class="new-files" ><select class="format"><option>file</option><option>directory</option></select><input type="button" class="creates_file_dir" value="+">');
}
else{
$('.plus-insert').html('');
 
}

});

