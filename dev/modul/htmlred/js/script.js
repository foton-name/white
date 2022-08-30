$(document).on("mousedown",".lefts",function(){
$(this).addClass('lefts2');

});
$(document).on("mouseup",".lefts",function(){
$(this).removeClass('lefts2');

}); 
$(document).on("keyup",'.panel22 input[type="text"]',function(){
if($('.panel23').css('display')=='none'){
var val = $(this).val();
$(this).attr('value',val);
}
}); 

$(document).on('click','.new-theme',function(){
    var theme = $(this).attr('theme');
    if(theme=='dark'){
      $('body').addClass('dark-theme');
      $(this).attr('theme','white');
    }
    else{
      $('body').removeClass('dark-theme');
      $(this).attr('theme','dark');
    }
});
 $(document).on('click','.up_x',function(){
    if($('.drops').is('.actions')){
        console.log('x');
        $('.workarea .actions').addClass('none_x');
        $('.workarea .actions').parent('.drops').addClass('actions');
        $('.workarea .none_x').removeClass('actions');
        $('.workarea .none_x').removeClass('none_x');
        $('.workarea .actions').click();
    }
});
  $(document).on('click','.down_x',function(){
    if($('.drops').is('.actions')){
        console.log('x');
        $('.workarea .actions').addClass('none_x');
        $('.workarea .actions').find('.drops').addClass('actions');
        $('.workarea .none_x').removeClass('actions');
        $('.workarea .none_x').removeClass('none_x');
        $('.workarea .actions').click();
    }
});
  $(document).on('click','.left_x',function(){
    if($('.drops').is('.actions')){
        console.log('x');
        $('.workarea .actions').addClass('none_x');
        $('.workarea .actions').prev('.drops').addClass('actions');
        $('.workarea .none_x').removeClass('actions');
        $('.workarea .none_x').removeClass('none_x');
        $('.workarea .actions').click();
    }
});
$(document).on('click','.obj_up',function(){
    if($('.drops').is('.actions')){
        var element = $('.drops.actions');
        var prev = $('.drops.actions').prev();
        prev.before(element);
    }
});
$(document).on('click','.obj_down',function(){
    if($('.drops').is('.actions')){
        var element = $('.drops.actions');
        var next = $('.drops.actions').next();
        next.after(element);
    }
});
  $(document).on('click','.right_x',function(){
    if($('.drops').is('.actions')){
        console.log('x');
        $('.workarea .actions').addClass('none_x');
        $('.workarea .actions').next('.drops').addClass('actions');
        $('.workarea .none_x').removeClass('actions');
        $('.workarea .none_x').removeClass('none_x');
        $('.workarea .actions').click();
    }
});
 $(document).on('change','.upfotos',function(){
    files = this.files;
});
$.fn.hasAttr = function(name) {
   return this.attr(name) !== undefined;
};
$(document).on("click",".next_step",function(){
      var page=$('.adress').val();
    if(page==''){
        page=$('.site_str').val();
        if(page!='---'){
        page=page.split('.')[0];
        }
    }
    if(page!='---' && page!=''){
      $.ajax({
        url: '/htmlred/next_tmp.ajax', 
        type: 'POST',
        data: {next:'yes',page:page},
        success: function(data){
            if(data.indexOf('<')!= -1){
            $('.htmlred5 > .workarea').html(data);
            $.ajax({
                url: '/htmlred/console_step.ajax', 
                type: 'POST',
                success: function(data){
                   $('.console_step').html(data);
                   $('.workarea .body').css('opacity','1');
                }
             });
            }
        }
     });
    }

});
$(document).on("click",".selector_class",function(){
     $.ajax({
        url: '/htmlred/testx.ajax', 
        type: 'POST',
        data: {step:'yes'},
        success: function(data){
           console.log(data);
        }
     });
 });
$(document).on("click",".clear_step",function(){
     var page=$('.adress').val();
    if(page==''){
        page=$('.site_str').val();
        if(page!='---'){
        page=page.split('.')[0];
        }
    }
    if(page!='---' && page!=''){
      $.ajax({
        url: '/htmlred/clear_tmp.ajax', 
        type: 'POST',
        data: {page:page},
        success: function(data){
           $('.console_step').html('Сессия очищена');
        }
     });
    }
    
});
$(document).on("click",".prev_step",function(){
     var page=$('.adress').val();
    if(page==''){
        page=$('.site_str').val();
        if(page!='---'){
        page=page.split('.')[0];
        }
    }
    if(page!='---' && page!=''){
      $.ajax({
        url: '/htmlred/prev_tmp.ajax', 
        type: 'POST',
        data: {prev:'yes',page:page},
        success: function(data){
            if(data.indexOf('<')!= -1){
            $('.htmlred5 > .workarea').html(data);
            $.ajax({
                url: '/htmlred/console_step.ajax', 
                type: 'POST',
                success: function(data){
                   $('.console_step').html(data);
                   $('.workarea .body').css('opacity','1');
                }
             });
            }
        }
     });
    }
    
});
$(document).on("mousedown",".pthoto-block img,.listv,.dels,.copy3,.vz,.vz2,.save_red_kod,.save_attr_red,.okno_znak2 div,.copy2,.sozd .drops,.obj_up,.obj_down",function(){
    var html = $('.htmlred5 > .workarea').html();
    var page=$('.adress').val();
    if(page==''){
        page=$('.site_str').val();
        if(page!='---'){
        page=page.split('.')[0];
        }
    }
    if(page!='---' && page!=''){
     $.ajax({
        url: '/htmlred/save_tmp.ajax', 
        type: 'POST',
        data: {tmp:html,page:page},
        success: function(data){
            console.log(data);
            $('.console_step').html(data);
        }
     });
    }
    
});
$(document).on("focus",".panel22 input,.panel23 input",function(){
    var html = $('.htmlred5 > .workarea').html();
      var page=$('.adress').val();
    if(page==''){
        page=$('.site_str').val();
        if(page!='---'){
        page=page.split('.')[0];
        }
    }
    if(page!='---' && page!=''){
     $.ajax({
        url: '/htmlred/save_tmp.ajax', 
        type: 'POST',
        data: {tmp:html,page:page},
        success: function(data){
            console.log(data);
            $('.console_step').html(data);
        }
     });
    }
    
});
$(document).on("change",".panel22 select,.panel23 select",function(){
    var html = $('.htmlred5 > .workarea').html();
      var page=$('.adress').val();
    if(page==''){
        page=$('.site_str').val();
        if(page!='---'){
        page=page.split('.')[0];
        }
    }
    if(page!='---' && page!=''){
     $.ajax({
        url: '/htmlred/save_tmp.ajax', 
        type: 'POST',
        data: {tmp:html,page:page},
        success: function(data){
            console.log(data);
            $('.console_step').html(data);
        }
     });
    }
    
});
 $(document).on("click",".copy_css",function(){
     if($('.actions').attr('id')!='boxA' && $('.actions').hasAttr('id')){
         var style=$('.actions').attr('style');
            style=style.replace(/null;/g,' nocss');
         style=style.replace(/;/g,' !important;');
           style=style.replace(/nocss/g,' null;');
     var css='.'+$('.actions').attr('id')+'{'+style+'}';
     }
     else{
           var style=$('.actions').attr('style');
            style=style.replace(/null;/g,' nocss');
         style=style.replace(/;/g,' !important;');
           style=style.replace(/nocss/g,' null;');
     var css='.actions_custom{'+style+'}';
     }
   $('.actions *').each(function(i,e){
       classx=$(e).attr('id');
       if(classx!='boxA' && $(e).hasAttr('id')){
           var style=$(e).attr('style');
           style=style.replace(/null;/g,' nocss');
         style=style.replace(/;/g,' !important;');
         style=style.replace(/nocss/g,' null;');
           css+='.'+classx+'{'+style+'}';
       }
   });
   alert(css);
   navigator.clipboard.writeText(css)
  .then(() => {
   alert('CSs код скопирован в буфер обмена');
  })
  .catch(err => {
    console.log('Ошибка копирования', err);
  });
 });
 $(document).on("click",".upfoto",function(){
    event.stopPropagation(); 
    event.preventDefault(); 

 if (typeof(files) == "undefined"){ 
alert('Выберите фото');
} 
else{
  var data = new FormData();
    $.each( files, function( key, value ){
        data.append( key, value );
  
    });
var path = $(this).attr('path');
var path2 = $(this).attr('path2');
data.append('path',path);
    $.ajax({
        url: '/htmlred/ajaxfiles_all.ajax', 
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, 
        contentType: false, 
        success: function( respond, textStatus, jqXHR ){
        if( typeof respond.error === 'undefined' ){
                var files_path = respond.files;
                $.each(files_path, function( key, val ){
                    val=val.replace(path2,'');
                $('.pthoto-block').append('<img src="'+val+'" style="width:100%;"  alt="Удаление при двойном нажатии" title="Удаление при двойном нажатии" >');
                    
                })
            }
            else{ 
                console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );

            }

        },
        error: function( jqXHR, textStatus, errorThrown ){
            console.log('ОШИБКИ AJAX запроса: ' + textStatus );


        } 

    });
}
 });
 
 
function hexDec(h){
var m=h.slice(1).match(/.{2}/g);
 
m[0]=parseInt(m[0], 16);
m[1]=parseInt(m[1], 16);
m[2]=parseInt(m[2], 16);
 
return m;
 }
 
 function begDec(h){
var m=h.replace('rgba(','');
var m=m.replace('rgb(','');
var m=m.replace(/ /g,'');
  m=m.replace(')','');
  m=m.split(',');
return m;
 }
$(document).on("click",'.close_new_okno',function(){
$('.color-open').hide(200);
$('input[name="color"][type="text"]').click();
$('input[name="background-color"][type="text"]').click();
});
$(document).on("dblclick",'input[name="color"]',function(){
    var colors=$(this).val();
     if(colors!=''){
        
    if(colors.indexOf('#')!=-1){
        var r=hexDec(colors)[0];
        var g=hexDec(colors)[1];
        var b=hexDec(colors)[2];
        $('.color_r').val(r);
        $('.color_g').val(g);
        $('.color_b').val(b);
    }else{
        var mass_v=begDec(colors);
    
         var r=mass_v[0];
          
        var g=mass_v[1];
       
        var b=mass_v[2];
        var p=mass_v[3];
        $('.color_r').val(r);
        $('.color_g').val(g);
        $('.color_b').val(b);
        
        $('.color_p').val(parseFloat(p));
          
    }
     }
    $('.color_type').val('color');
$('.color-open').show(200);
});

$(document).on("change",'.color_rgb',function(){
var r =$('.color_r').val();
var g =$('.color_g').val();
var b =$('.color_b').val();
var p=$('.color_p').val();
if($('.color_type').val()=='color'){
    $('.actions').css('color','rgba('+r+','+g+','+b+','+p+')');
    $('input[name="color"]').val('rgba('+r+','+g+','+b+','+p+')');
}
else{
    $('.actions').css('background-color','rgba('+r+','+g+','+b+','+p+')');
    $('input[name="background-color"]').val('rgba('+r+','+g+','+b+','+p+')');  
}
});

  $(document).on('mouseover','.okno',function(){
   $('.workarea .body').css('opacity','0.7');
   $('.workarea').addClass('hoverw');
   
   });
      $(document).on('mouseout','.okno',function(){
   $('.workarea .body').css('opacity','1');
   $('.workarea').removeClass('hoverw');
   });

    $(document).on('mouseover','.del-sh',function(){
if($(this).html()!=''){
 $(this).html('');

var val = $('.shs').val();
file='/modulis/htmlred/tpl/'+val+'.html';
$.ajax({ 
            type: "POST",   
            url: "/htmlred/removefile.ajax",
            data: {file:file},
             success: function(data){  



}                 
}); 

  }                
}); 

       $(document).on('mouseout','.del-sh',function(){
if($(this).html()!=''){
 $(this).html('');
var val = $('.shs').val();
file='/modulis/htmlred/tpl/'+val+'.html';
$.ajax({ 
            type: "POST",   
            url: "/htmlred/removefile.ajax",
            data: {file:file},
             success: function(data){  



}                 
}); 

    

    }              
}); 
 $(document).on('keyup','.panel23 input[name="font-face"]',function(){
$('.fonts').html($(this).val());

});

$(document).on('click','.vkl2',function(){

$('.panel23 input[name="background-image"]').val($('.body').css('backgroundImage'));
$('.panel23 input[name="background-color"]').val($('.body').css('backgroundColor'));
$('.panel23 input[name="background-size"]').val($('.body').css('backgroundSize'));
$('.panel23 input[name="background-position"]').val($('.body').css('backgroundPosition'));
$('.panel23 input[name="padding"]').val($('.body').css('padding'));
$('.panel23 input[name="box-shadow"]').val($('.body').css('boxShadow'));
$('.panel23 input[name="font-family"]').val($('.body').css('fontFamily'));
var styles = $('.body').attr('style');
var myregexp = /width:([^;]+);/;
var match = myregexp.exec(styles);
if (match != null) {
    var widths = match[1];
} 

var myregexp2 = /height:([^;]+);/;
var match2 = myregexp2.exec(styles);
if (match2 != null) {
    var heights = match2[1];
} 

$('.panel23 input[name="width"]').val(widths);
$('.panel23 input[name="height"]').val(heights);
if($('.fonts').html()==''){
$('.fonts').html($('.workarea > link[href*="https://fonts.googleapis.com"]').clone()); 
}
$('.panel23 input[name="font-face"]').val($('.fonts').html());
});

$(document).on('dblclick','.pthoto-block img',function(){
var img=$(this).attr('src');
$(this).remove();

$.ajax({ 
            type: "POST",   
            url: "/htmlred/removefile.ajax",
            data: {file:img},
             success: function(data){  



}                 
}); 


});
$(document).on("click",'.attrbut_red',function(){
    $('.okno-attr').html('');
$('.actions').each(function() {
  $.each(this.attributes, function() {
     if(this.specified) {
         if(this.name!='style' && this.name!='draggable' && this.name!='ondragstart' && this.name!='id' && this.name!='class'){
             
             $('.okno-attr').append('<div><span>'+this.name+'</span><input type="text" class="attr_red_input" name="'+this.name+'" value="'+this.value+'">');
         }
    }
  });
});
$('.okno_attr').show(300);
});
$(document).on("click",'.close_attr_okno',function(){
    $('.okno_attr').hide(300);
});

$(document).on("click",'.save_attr_red',function(){

$(".okno-attr input").each(function(indx, elem){
        $('.actions').attr($(elem).attr('name'),$(elem).val());
        
    });
});
$(document).on("click",'.kod_red',function(){
var text = $('.body .actions').html();
$('.red_kod_text').val(text);
$('.red_kod_area').show(300);

});
$(document).on("click",'.red_kod_area .x',function(){

$('.red_kod_area').hide(300);
});
$(document).on("click",'.save_red_kod',function(){

var text = $('.red_kod_text').val();
$('.body .actions').html(text);
});

$(document).on("click",'.panel22 input[type="text"]',function(){
if($('.panel23').css('display')=='none'){
var val = $(this).val();
$(this).attr('value',val);
}
}); 
$(document).on("click",'.panel22 input[type="color"]',function(){
if($('.panel23').css('display')=='none'){
var val = $(this).val();
$(this).attr('value',val);
}
}); 
$(document).on("change",'.panel22 input[type="color"]',function(){
if($('.panel23').css('display')=='none'){
var val = $(this).val();
$(this).attr('value',val);
}
}); 
$(document).on("change",'.panel22 input[type="text"]',function(){
if($('.panel23').css('display')=='none'){
var val = $(this).val();
$(this).attr('value',val);
}

}); 


$(document).on("mousemove",".lefts2",function(){
    var left=$(this).css('left');
var name=$(this).attr('names');
var attr=$(this).attr('attr'); 
if(attr=='plus'){
$('.panel22 input[name="'+name+'"]').val(left);
$('.panel22 input[name="'+name+'"]').attr('value',left);
$('.actions').css(name,left);
$('.inputp[names="'+name+'"]').attr('values',left);
}
else{ 
left2=parseInt(left)-500;
$('.panel22 input[name="'+name+'"]').val(left2+'px');
$('.panel22 input[name="'+name+'"]').attr('value',left2+'px');
$('.actions').css(name,left2+'px');
$('.inputp[names="'+name+'"]').attr('values',left);
}
});

  $(document).on("click",".close",function(){
 
$('.overokno').css('display','none');
 });
   $(document).on("click",".inputp",function(){
       $('.lefts').css('left','0px');
 var name=$(this).attr('names');
 var value=$(this).attr('values');
  var attr=$(this).attr('attr');
 $('.lefts').css('left',value);
  $('.lefts').attr('names',name);
  $('.lefts').attr('attr',attr);
 $('.overokno').css('display','block');
   });
   
   function randm(min, max) {
    var rand = min - 0.5 + Math.random() * (max - min + 1)
    rand = Math.round(rand);
    return rand;
  }
  
        $(document).on("dblclick",'input[name="background-color"]',function(){
                var colors=$(this).val();
                if(colors!=''){
    if(colors.indexOf('#') + 1){
        var r=hexDec(colors)[0];
        var g=hexDec(colors)[1];
        var b=hexDec(colors)[2];
        $('.color_r').val(r);
        $('.color_g').val(g);
        $('.color_b').val(b);
    }else{
         var r=begDec(colors)[0];
        var g=begDec(colors)[1];
        var b=begDec(colors)[2];
        var p=begDec(colors)[3];
        $('.color_r').val(r);
        $('.color_g').val(g);
        $('.color_b').val(b);
         $('.color_p').val(parseFloat(p));
    }
                }
   $('.color_type').val('background');
$('.color-open').show(200);
});
   function rgb_to_hex(color){
var rgb = color.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
return (rgb && rgb.length === 4) ? "#" + ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) + ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) + ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : color;
}
      $(document).on("click",".boxp",function(){
  var name=$(this).attr('names');
 var value=$(this).attr('values');
 var attr=$(this).attr('attr');
 var valuemass=value.split(" ");
 var valuel=valuemass[1];
 var valuer=valuemass[2];
 var valuet=valuemass[3];
 var valuem=valuemass[4];
 var colors2=valuemass[0];
 var pos=colors2.indexOf('rgb');
 if(pos!==-1){
 var colors = rgb_to_hex(colors2);
  }
  else{
 var colors = colors2;  
  }
$('.leftsl').css('left','0px');
$('.leftsl').css('left',valuel);
$('.leftsl').attr('names',name);

$('.leftsr').css('left','0px');
$('.leftsr').css('left',valuer);
$('.leftsr').attr('names',name);

$('.leftst').css('left','0px');
$('.leftst').css('left',valuet);
$('.leftst').attr('names',name);

$('.leftsm').css('left','0px');
$('.leftsm').css('left',valuem);
$('.leftsm').attr('names',name);

$('.colorbox').val(colors);
$('.colorbox').attr('value',colors);
$('.colorbox').attr('color',colors);
$('.overoknobox').css('display','block');
   });
   function removeaction(){
   $('.drops').each(function(i,elem) { 
$(elem).removeClass("actions");
});
$('.drops:first').click();
   }
   $(document).on("change",".site_views_str",function(){

$('.select').change();
$('.create').click();

setTimeout(removeaction, 200);

   });
   
     $(document).on("mouseup",".overoknobox",function(e){

    if ($(e.target).attr('class') === 'overoknobox'){
       $('.overoknobox').css('display','none');
       $('body').css('overflow','inherit'); 
    }
 });
 
 
 
 $(document).on("mousemove",".leftsl",function(){

    var leftl=$(".leftsl").css('left');
var leftr=$(".leftsr").css('left');
var leftt=$(".leftst").css('left');
var leftm=$(".leftsm").css('left');
var colors = $('.colorbox').attr('value');
var left =colors+' '+leftl+' '+leftr+' '+leftt+' '+leftm;
var name=$(this).attr('names');
var attr=$(this).attr('attr'); 
$('.panel22 input[name="'+name+'"]').val(left);
$('.panel22 input[name="'+name+'"]').attr('value',left);
$('.actions').css(name,left);
$('.boxp[names="'+name+'"]').attr('values',left);

});
$(document).on("mousemove",".leftsr",function(){

    var leftl=$(".leftsl").css('left');
var leftr=$(".leftsr").css('left');
var leftt=$(".leftst").css('left');
var leftm=$(".leftsm").css('left');
var colors = $('.colorbox').attr('value');
var left =colors+' '+leftl+' '+leftr+' '+leftt+' '+leftm;
var name=$(this).attr('names');
var attr=$(this).attr('attr'); 
$('.panel22 input[name="'+name+'"]').val(left);
$('.panel22 input[name="'+name+'"]').attr('value',left);
$('.actions').css(name,left);
$('.boxp[names="'+name+'"]').attr('values',left);

});
$(document).on("mousemove",".leftst",function(){

    var leftl=$(".leftsl").css('left');
var leftr=$(".leftsr").css('left');
var leftt=$(".leftst").css('left');
var leftm=$(".leftsm").css('left');
var colors = $('.colorbox').attr('value');
var left =colors+' '+leftl+' '+leftr+' '+leftt+' '+leftm;
var name=$(this).attr('names');
var attr=$(this).attr('attr'); 
$('.panel22 input[name="'+name+'"]').val(left);
$('.panel22 input[name="'+name+'"]').attr('value',left);
$('.actions').css(name,left);
$('.boxp[names="'+name+'"]').attr('values',left);

});
$(document).on("mousemove",".leftsm",function(){

    var leftl=$(".leftsl").css('left');
var leftr=$(".leftsr").css('left');
var leftt=$(".leftst").css('left');
var leftm=$(".leftsm").css('left');
var colors = $('.colorbox').attr('value');
var left =colors+' '+leftl+' '+leftr+' '+leftt+' '+leftm;
var name=$(this).attr('names');
var attr=$(this).attr('attr'); 
$('.panel22 input[name="'+name+'"]').val(left);
$('.panel22 input[name="'+name+'"]').attr('value',left);
$('.actions').css(name,left);
$('.boxp[names="'+name+'"]').attr('values',left);

});

$(document).on("change click mouseover",".colorbox",function(){
var val=$(this).val();
$('.colorbox').attr('value',val);
    var leftl=$(".leftsl").css('left');
var leftr=$(".leftsr").css('left');
var leftt=$(".leftst").css('left');
var leftm=$(".leftsm").css('left');
var colors = $('.colorbox').attr('value');
var left =colors+' '+leftl+' '+leftr+' '+leftt+' '+leftm;
var name=$(this).attr('names');
var attr=$(this).attr('attr'); 
$('.panel22 input[name="'+name+'"]').val(left);
$('.panel22 input[name="'+name+'"]').attr('value',left);
$('.actions').css(name,left);
$('.boxp[names="'+name+'"]').attr('values',left);

});





  $(document).on("mouseup",".overokno",function(e){

    if ($(e.target).attr('class') === 'overokno'){
       $('.overokno').css('display','none');
       $('body').css('overflow','inherit'); 
    }
 });
zoom=1;
 $(document).on("click",".zoom1",function(){
 zoom=zoom+0.1;
 $('.workarea').css('zoom',zoom);
 
 });
  $(document).on("click",".zoom2",function(){
  zoom=zoom-0.1;
 $('.workarea').css('zoom',zoom);
 
 });


  $(document).on('contextmenu','input[name="color"],input[name="background-color"],input[name*="-color"]',function() {
      if($(this).attr('type')=='text'){
          var val=$(this).val();
     
          if(val.indexOf('#') === -1){
          $(this).val(rgb_to_hex(val));
          }
          else{
               $(this).val(val); 
          }
    $(this).attr('type', 'color');
      }
      else{
             $(this).attr('type', 'text');
      }
 return false;
  });

$(document).ready(function(){
    $('body').addClass('dark-theme');
var selects = ["span","div","a","p","img","input","textarea","select","i","b","u","video","iframe"];
var selects2 = ["Строка","Блок","Ссылка","Параграф","Картинка","Поле для заполнения","Текст для заполнения","Список","Курсив","Жирный","Подчеркнутый","Видео","Фрейм"];
var str_select='';
for(var it=0;it<selects.length;it++){
str_select+='<option value="'+selects[it]+'">'+selects2[it]+'</option>';
}
var select = '<select class="select"><option value="">Добавьте html элемент</option>'+str_select+'</select>';

$('.selectlists').html(select);
});

var Elementcss = {

"width":{"type":["text"],"value":["Ширина"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"height":{"type":["text"],"value":["Высота"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"color":{"type":["text"],"value":["Цвет текста"],"ids":["c3"],"redactor":["0"],"val":[""]},
"white-space":{"type":["select"],"value":["pre","pre-line","pre-wrap","break-spaces","inherit","unset","initial","nowrap","normal","revert","none"],"ids":["c3"],"val":["Форматирование текста"]},
"border-radius":{"type":["text"],"value":["Радиус"],"ids":["c2"],"redactor":["plus"],"val":[""]},
"cursor":{"type":["select"],"value":["auto,default,pointer,progress,wait,cell,crosshair,text,vertical-text,move,no-drop,col-resize,row-resize,zoom-in,zoom-out,grabbing"],"val":["Курсор"],"ids":["c2"]},
"border-top-left-radius":{"type":["text"],"value":["Радиус лево верх"],"ids":["c2"],"redactor":["plus"],"val":[""]},
"border-top-right-radius":{"type":["text"],"value":["Радиус право верх"],"ids":["c2"],"redactor":["plus"],"val":[""]},
"border-bottom-left-radius":{"type":["text"],"value":["Радиус лево низ"],"ids":["c2"],"redactor":["plus"],"val":[""]},
"border-bottom-right-radius":{"type":["text"],"value":["Радиус право низ"],"ids":["c2"],"redactor":["plus"],"val":[""]},
"border-width":{"type":["text"],"value":["Граница ширина"],"ids":["c2"],"redactor":["plus"],"val":[""]},
"border-style":{"type":["select"],"value":["solid,dashed,dotted,double,groove,inset"],"val":["Тип границы"],"ids":["c2"]},
"border-color":{"type":["color"],"value":["Граница цвет"],"ids":["c2"],"redactor":["0"],"val":[""]},
    "border-top-width":{"type":["text"],"value":["Граница верх ширина"],"ids":["c2"],"redactor":["plus"],"val":[""]},
    "border-top-style":{"type":["select"],"value":["solid,dashed,dotted,double,groove,inset"],"val":["Тип границы сверху"],"ids":["c2"]},
"border-top-color":{"type":["color"],"value":["Граница верх цвет"],"ids":["c2"],"redactor":["0"],"val":[""]},
    "border-right-width":{"type":["text"],"value":["Граница справа ширина"],"ids":["c2"],"redactor":["plus"],"val":[""]},
    "border-right-style":{"type":["select"],"value":["solid,dashed,dotted,double,groove,inset"],"val":["Тип границы справа"],"ids":["c2"]},
    "border-right-color":{"type":["color"],"value":["Граница справа цвет"],"ids":["c2"],"redactor":["0"],"val":[""]},
    "border-bottom-width":{"type":["text"],"value":["Граница низ ширина"],"ids":["c2"],"redactor":["plus"],"val":[""]},
    "border-bottom-style":{"type":["select"],"value":["solid,dashed,dotted,double,groove,inset"],"val":["Тип границы внизу"],"ids":["c2"]},
    "border-bottom-color":{"type":["color"],"value":["Граница низ цвет"],"ids":["c2"],"redactor":["0"],"val":[""]},
    "border-left-width":{"type":["text"],"value":["Граница слева ширина"],"ids":["c2"],"redactor":["plus"],"val":[""]},
    "border-left-style":{"type":["select"],"value":["solid,dashed,dotted,double,groove,inset"],"val":["Тип границы слева"],"ids":["c2"]},
    "border-left-color":{"type":["color"],"value":["Граница слева цвет"],"ids":["c2"],"redactor":["0"],"val":[""]},


"perspective":{"type":["text"],"value":["Перспектива"],"ids":["c2"],"redactor":["plus"],"val":[""]},
"column-count":{"type":["text"],"value":["Колонки"],"ids":["c2"],"redactor":["0"],"val":[""]},
"column-gap":{"type":["text"],"value":["Отступы в колонках"],"ids":["c2"],"redactor":["minus"],"val":[""]},
"transform":{"type":["text"],"value":["Поворот"],"ids":["c2"],"redactor":["plus"],"val":["rotate(0deg)"]},
"box-shadow":{"type":["text"],"value":["тень"],"ids":["c2"],"redactor":["box"],"val":[""]},
"z-index":{"type":["text"],"value":["слой"],"ids":["c2"],"redactor":["plus"],"val":[""]},
"font-size":{"type":["text"],"value":["размер шрифта"],"ids":["c3"],"redactor":["plus"],"val":[""]},
"font-style":{"type":["select"],"value":["normal,italic,oblique"],"ids":["c3"],"redactor":["plus"],"val":["Тип шрифта"]},
"text-transform":{"type":["select"],"value":["none,capitalize,lowercase,uppercase"],"ids":["c3"],"redactor":["plus"],"val":["Трансформация текста"]},
"font-weight":{"type":["select"],"value":["normal,bold,100,200,300,400,500,600,700,800"],"ids":["c3"],"redactor":["plus"],"val":["Толщина текста"]},
"letter-spacing":{"type":["text"],"value":["Межбуквенное расстояние"],"ids":["c3"],"redactor":["plus"],"val":[""]},
"opacity":{"type":["text"],"value":["прозрачность"],"ids":["c2"],"redactor":["0"],"val":[""]},
"filter":{"type":["text"],"value":["Фильтр"],"ids":["c2"],"redactor":["0"],"val":[""]},
"line-height":{"type":["text"],"value":["межстрочный интервал"],"ids":["c3"],"redactor":["plus"],"val":[""]},
"font-family":{"type":["text"],"value":["шрифт"],"ids":["c3"],"redactor":["0"],"val":[""]},
"background-image":{"type":["text"],"value":["Фон"],"ids":["c4"],"redactor":["0"],"val":[""]},
"background-repeat":{"type":["text"],"value":["Повтор фона"],"val":["no-repeat"],"ids":["c4"],"redactor":["0"]},
"background-color":{"type":["text"],"value":["Фон"],"val":["transparent"],"ids":["c4"],"redactor":["0"]},
"background-attachment":{"type":["select"],"value":["fixed","scroll","inherit"],"val":["Позиционирование фона"],"ids":["c4"]},
"background-size":{"type":["text"],"value":["Фон размер"],"ids":["c4"],"redactor":["plus"],"val":[""]},
"background-position":{"type":["text"],"value":["Фон позиция"],"ids":["c4"],"redactor":["plus"],"val":[""]},

"text-align":{"type":["select"],"value":["left,right,center,justify"],"ids":["c3"],"val":["Позиционирование"]},
"vertical-align":{"type":["select"],"value":["baseline,bottom,middle,top,sub,super,text-bottom,text-top,inherit"],"ids":["c3"],"val":["Позиционирование вертикальное"]},
"left":{"type":["text"],"value":["Отступ слева"],"ids":["c1"],"redactor":["minus"],"val":[""]},
"top":{"type":["text"],"value":["Отступ сверху"],"ids":["c1"],"redactor":["minus"],"val":[""]},
"margin-left":{"type":["text"],"value":["Отступ слева относительный"],"ids":["c1"],"redactor":["minus"],"val":[""]},
"margin-top":{"type":["text"],"value":["Отступ сверху относительный"],"ids":["c1"],"redactor":["minus"],"val":[""]},
"margin-right":{"type":["text"],"value":["Отступ справа относительный"],"ids":["c1"],"redactor":["minus"],"val":[""]},

"margin-bottom":{"type":["text"],"value":["Отступ снизу относительный"],"ids":["c1"],"redactor":["minus"],"val":[""]},
"padding-left":{"type":["text"],"value":["отступ внутри слева"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"padding-top":{"type":["text"],"value":["отступ внутри сверху"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"padding-right":{"type":["text"],"value":["отступ внутри справа"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"padding-bottom":{"type":["text"],"value":["отступ внутри снизу"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"text-decoration":{"type":["select"],"value":["none,underline,overline,line-through"],"ids":["c3"],"val":["Подчеркивание текста"]},
"display":{"type":["select"],"value":["block,inline-block,list-item,inline-table,none,table,table-row,table-cell"],"val":["Display"],"ids":["c1"]},

"float":{"type":["select"],"value":["none,both,left,right"],"ids":["c1"],"val":["Float"]},

"right":{"type":["text"],"value":["Отступ справа"],"ids":["c1"],"redactor":["minus"],"val":[""]},

"bottom":{"type":["text"],"value":["Отступ снизу"],"ids":["c1"],"redactor":["minus"],"val":[""]},
"position":{"type":["select"],"value":["relative,absolute,fixed"],"ids":["c2"],"val":["Позиционирование"]},


"overflow":{"type":["select"],"value":["hidden,auto,scroll,inherit"],"ids":["c2"],"val":["Overflow"]},


"min-height":{"type":["text"],"value":["минимальная высота"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"min-width":{"type":["text"],"value":["минимальная ширина"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"max-height":{"type":["text"],"value":["максимальная высота"],"ids":["c1"],"redactor":["plus"],"val":[""]},
"max-width":{"type":["text"],"value":["максимальная ширина"],"ids":["c1"],"redactor":["plus"],"val":[""]},


"transition-property":{"type":["text"],"value":["css свойство для движения"],"ids":["c2"],"redactor":["0"],"val":[""]},
"transition-duration":{"type":["text"],"value":["время начала движения движения"],"ids":["c2"],"redactor":["0"],"val":[""]},
"transition-timing-function":{"type":["select"],"value":["ease,ease-in,ease-out,ease-in-out,inear,cubic-bezier"],"ids":["c2"],"redactor":["0"],"val":["Временная функция"]},
"transition-delay":{"type":["text"],"value":["время окончания движения"],"ids":["c2"],"redactor":["0"],"val":[""]},
};



var Config = {

"input": {
"type": { "text": ["Текст"],"button":["Кнопка but"],"submit":["Кнопка sub"],"checkbox":["Галочка"],"radio":["Радио кнопка"],"date":["Дата"],"file":["Файл"],"color":["Цвет"],"password":["пароль"],"tel":["Телефон"],"mail":["E-mail"] },
"placeholder": [ "Описание" ],
"value": [ "Значение" ]
},
"textarea": {
"cols": [ "Высота" ],
"rows": [ "Ширина" ],
"html": [ "Значение" ],
"placeholder": [ "Описание" ]
},
"a": {
"href": "Ссылка" ,
"html": "Текст ссылки" ,
"target":  {"_blank":["В новом окне"],"_self":["В этом же окне"]} ,
"rel": {"nofollow":["Закрытая"],"follow":["Открытая"]}
},
"p": {
"html":  "Текст внутри"
},
"i": {
"html":  "Текст внутри"
},
"u": {
"html":  "Текст внутри"
},
"b": {
"html":  "Текст внутри"
},
"span": {
"html":  "Текст внутри"
},
"div": {
"html":  "Текст внутри"
},
"select": {
"value":  "Значение через запятую",
"text":"Описание через запятую"
},
"img": {
"src":  "Путь до картинки",
"width":"Ширина",
"height":"Высота"
},"video":{
    "poster":"Фото на загрузке",
    "src":"Ссылка на видео"
},"iframe":{
    "width":"Ширина",
"height":"Высота",
"scrolling":{"no":["нет скроллинга"],"yes":["есть скроллинг"]},
    "src":"Ссылка"
}

};


    
 
 $(document).on("click",".drops",function(e){
 $(this).draggable();
 e.stopPropagation();
  
  $(".panel22 select :selected").removeAttr("selected");
$('.drops').each(function(i,elem) { 
$(elem).removeClass("actions");
});
$('.panel22 input').each(function(i,elem) {
$(elem).val("");
$(elem).attr("value","");
});
$('.panel22 select').each(function(i,elem) {
$(elem).val("");
$(elem).attr("value","");
});
$('.panel22 .boxp').each(function(i,elem) {
$(elem).attr('values','');

});
$('.panel22 .inputp').each(function(i,elem) {
$(elem).attr('values','');
var attr=$(elem).attr('attr');
   if(attr=='minus'){
   
  var styleattr=500;
$(elem).attr('values',styleattr+'px');
   }
});
$(this).addClass("actions");
var styleel = $(this).attr("style");
var styleelmass=styleel.split(';');

for (var key in styleelmass) {

var styleelmass2=styleelmass[key].split(':');
var boxst=styleelmass2[1];
styleelmass2[0]=styleelmass2[0].replace(' ','');
//styleelmass2[1]=styleelmass2[1].replace(' ','');


if(styleelmass2[0]!='' && styleelmass2[1]!='undefined' && styleelmass2[1]){
   var attr=$('.inputp[names="'+styleelmass2[0]+'"]').attr('attr');
   if(attr=='minus'){
   $('.panel22 input[name="'+styleelmass2[0]+'"]').val(styleelmass2[1]);
$('.panel22 input[name="'+styleelmass2[0]+'"]').attr('value',styleelmass2[1]);
  var styleattr=parseInt(styleelmass2[1])+500;
$('.inputp[names="'+styleelmass2[0]+'"]').attr('values',styleattr+'px');
   }
else if(styleelmass2[0]=='padding'){

var paddings=styleelmass2[1].split('px');
if(paddings.length==2){
$('.panel22 input[name="padding-left"]').val(paddings[0]+'px');
$('.panel22 input[name="padding-right"]').val(paddings[0]+'px');
$('.panel22 input[name="padding-top"]').val(paddings[0]+'px');
$('.panel22 input[name="padding-bottom"]').val(paddings[0]+'px');
$('.panel22 input[name="padding-left"]').attr('value',paddings[0]+'px');
$('.panel22 input[name="padding-right"]').attr('value',paddings[0]+'px');
$('.panel22 input[name="padding-top"]').attr('value',paddings[0]+'px');
$('.panel22 input[name="padding-bottom"]').attr('value',paddings[0]+'px');
}
else if(paddings.length==3){
$('.panel22 input[name="padding-left"]').val(paddings[1]+'px');
$('.panel22 input[name="padding-right"]').val(paddings[1]+'px');
$('.panel22 input[name="padding-top"]').val(paddings[0]+'px');
$('.panel22 input[name="padding-bottom"]').val(paddings[0]+'px');
$('.panel22 input[name="padding-left"]').attr('value',paddings[1]+'px');
$('.panel22 input[name="padding-right"]').attr('value',paddings[1]+'px');
$('.panel22 input[name="padding-top"]').attr('value',paddings[0]+'px');
$('.panel22 input[name="padding-bottom"]').attr('value',paddings[0]+'px');

}
else if(paddings.length==5){
$('.panel22 input[name="padding-left"]').val(paddings[3]+'px');
$('.panel22 input[name="padding-right"]').val(paddings[1]+'px');
$('.panel22 input[name="padding-top"]').val(paddings[0]+'px');
$('.panel22 input[name="padding-bottom"]').val(paddings[2]+'px');
$('.panel22 input[name="padding-left"]').attr('value',paddings[3]+'px');
$('.panel22 input[name="padding-right"]').attr('value',paddings[1]+'px');
$('.panel22 input[name="padding-top"]').attr('value',paddings[0]+'px');
$('.panel22 input[name="padding-bottom"]').attr('value',paddings[2]+'px');
}
else{
}

}
else if(styleelmass2[0]=='box-shadow'){
boxst=boxst.replace(', ',',');
boxst=boxst.replace(', ',',');
boxst=boxst.replace(' rgb','rgb');
$('.panel22 input[name="'+styleelmass2[0]+'"]').val(boxst);

$('.panel22 input[name="'+styleelmass2[0]+'"]').attr('value',boxst);
$('.boxp[names="'+styleelmass2[0]+'"]').attr('values',boxst);

}

   else{
$('.panel22 input[name="'+styleelmass2[0]+'"]').val(styleelmass2[1]);

$('.panel22 input[name="'+styleelmass2[0]+'"]').attr('value',styleelmass2[1]);
$('.inputp[names="'+styleelmass2[0]+'"]').attr('values',styleelmass2[1]);
   }
  var value_css_attr = styleelmass2[1].replace(/([^0-9a-z\-_A-Z]+)/,'');
if(styleelmass2[0]!="background-image"){
$('.panel22 select[name="'+styleelmass2[0]+'"] option[value="'+value_css_attr+'"]').prop('selected', true);
}
   

}

}
});
  

 $(document).on("click",".c1,.c2,.c3,.c4",function(){
 
 var classt=$(this).attr('class');
 $('.cr2 input[type="text"],.cr2 input[type="color"], .cr2 select,.cr2 p').each(function(i,elem) {
 $(elem).css('display','none');
 });
 
 $('.cr2 input[ids="'+classt+'"], .cr2 select[ids="'+classt+'"],.cr2 p[ids="'+classt+'"]').each(function(i,elem) {
 $(elem).css('display','block');
 
 });
 
 $('.panel22 div').each(function(i,elem) {
     
     $(elem).css('background','#777');
 });
 
 $(this).css('background','#025f9b');
 });


 
 var Strcss = {
"center":{"type":["checkbox"],"pl":["По центру"]},
"display":{"type":["text"],"pl":["Display"],"value":["block"]},
"width":{"type":["text"],"pl":["Ширина"],"value":["1024px"]},
"height":{"type":["text"],"pl":["Высота"],"value":["2500px"]},
"position":{"type":["select"],"pl":["Позиция"],"value":["relative,absolute"]},
"background-color":{"type":["text"],"pl":["Фон"],"value":["#ffffff"]},
"background-image":{"type":["text"],"pl":["Фон картинка"],"value":[""]},
"background-size":{"type":["text"],"pl":["Фон размер"],"value":["cover"]},
"background-position":{"type":["text"],"pl":["Фон позиция"],"value":["center center"]},
"font-family":{"type":["text"],"pl":["шрифт"],"value":["sans-serif"]},
"font-face":{"type":["text"],"pl":["Гугл шрифт - ссылка"]},
"padding":{"type":["text"],"pl":["отступ внутри"]},
"box-shadow":{"type":["text"],"pl":["тень"]}

};
 
 function stylestr(){
 

 
 var scripts='';
for (var code in Strcss) {
if(!Strcss[code]["value"]){
Strcss[code]["value"]='';
}
if(Strcss[code]["type"]=='select'){
var mass=Strcss[code]["value"]+'';
mass=mass.split(',');
var texts='';
for(var its=0;its<mass.length;its++){
texts+='<option>'+mass[its]+'</option>';
}
scripts+='<select name="'+code+'">'+texts+'</select>';
}
else if(Strcss[code]["type"]=='checkbox'){
scripts+='<div id="zentr"><input type="'+Strcss[code]["type"]+'" id="check2" name="'+code+'" alt="'+Strcss[code]["pl"]+'" title="'+Strcss[code]["pl"]+'"  placeholder="'+Strcss[code]["pl"]+'" ><div class="inline">'+Strcss[code]["pl"]+'</div></div>';

}
else{
scripts+='<input type="'+Strcss[code]["type"]+'" name="'+code+'" alt="'+Strcss[code]["pl"]+'" title="'+Strcss[code]["pl"]+'"  placeholder="'+Strcss[code]["pl"]+'" value="'+Strcss[code]["value"]+'">';
}
}
$('.panel23').html('<br><input type="button" class="save2" value="Сохранить">'+scripts+'');
 
 
 
 
 }
 
 
 
 
 
 function changes(thisselect){
 
var cicl='';
var texts='';
for (var code in Config[thisselect]) {
if(typeof(Config[thisselect][code][0])=='undefined'){
for (var code2 in Config[thisselect][code]) {

cicl+='<option value="'+code2+'" >'+Config[thisselect][code][code2]+'</option>';

}
texts+='<select   name="'+code+'">'+cicl+'</select><br>';
cicl='';
}
else{
texts+='<input type="text" name="'+code+'" placeholder="'+Config[thisselect][code]+'"><br>';
}

}
return texts;
 }
 
 
 
 
 
function dragStart(ev) {
   ev.dataTransfer.effectAllowed='move';
   ev.dataTransfer.setData("Text", ev.target.getAttribute('id'));   
   ev.dataTransfer.setDragImage(ev.target,100,100);
   return true;
}

 
 function dragEnter(ev) {
   event.preventDefault();

   return true;
}
function dragOver(ev) {
     event.preventDefault();

}
function dragDrop(ev) {
   var data = ev.dataTransfer.getData("Text");
   ev.target.appendChild(document.getElementById(data));
   ev.stopPropagation();
   return false;
}



$(function() {

    $('.dragElement').draggable({
        containment: "parent"

    });
 $('.dragElement2').draggable({
        containment: "parent"
    });

});
 
 
  
 var Zamenavalue = {

"transform":{"do":["rotate("],"posle":["deg)"]}

};
 
 for (var key in Zamenavalue) {

 $(document).on("mouseup",'div[names="'+key+'"]',function(){
 var val=$(this).css('left');
 val=val.replace('px','');
 $('input[name="'+key+'"]').val(Zamenavalue[key]["do"]+val+Zamenavalue[key]["posle"]);
  $('input[name="'+key+'"]').attr("value",Zamenavalue[key]["do"]+val+Zamenavalue[key]["posle"]);
$('.save').click();
 });
 
 $(document).on("mousedown",'div[names="'+key+'"]',function(){
 var val=$(this).css('left');
 val=val.replace('px','');
 $('input[name="'+key+'"]').val(Zamenavalue[key]["do"]+val+Zamenavalue[key]["posle"]);
  $('input[name="'+key+'"]').attr("value",Zamenavalue[key]["do"]+val+Zamenavalue[key]["posle"]);
$('.save').click();
 });
 
 }
 
 

 
 
 
 
 
 
 
 
 
 
 
 
 $(document).on("click",".save2",function(){
var style='';
$('.fonts').html($('.panel23 input[name="font-face"]').val());
$('.panel23 input, .panel23 select').each(function(i,elem) {
attr=$(elem).attr('name');
val= $(elem).val();
if(val!='' && attr!='font-face' && val!==null){
style+=attr+':'+val+'; ';
}
});

$(".body").attr('style',style);
if($('#check2').prop("checked") == false){
}
else{
if($('.panel23 select[name="position"]').val()=='relative'){
$('.body').css('margin','0 auto');}
else{
$('.body').css('left','0');
$('.body').css('right','0');
$('.body').css('top','0');
$('.body').css('bottom','0');
$('.body').css('margin','auto');
}
}
});

$(document).on("click","#zentr #check2",function(){
if($(this).prop("checked") == false){
}else{
var style='';
$('.cr2 input, .cr2 select').each(function(i,elem) {
attr=$(elem).attr('name');
val= $(elem).val();
if(val!='' && val!==null){
style+=attr+':'+val+'; ';
}
});

$(".body").attr('style',style);
if($('.cr2 select[name="position"]').val()=='relative'){
$('.body').css('margin','0 auto');}
else{
$('.body').css('left','0');
$('.body').css('right','0');
$('.body').css('top','0');
$('.body').css('bottom','0');
$('.body').css('margin','auto');
}
}
});
$(document).on("click",".copy3",function(){
 var isdel = confirm("Вы уверены, что хотите очистить всю рабочую область?");
if(isdel){
$('.body').html('');
}
});
$(document).on("click",".dels",function(){

$('.actions').remove();

});





$(document).on("click",".vz",function(){
action2=$(".actions").clone();
$('.actions').remove();
});
$(document).on("click",".vz2",function(){

$('.actions').append(action2);
});
$(document).on("click",".copy2",function(){

var actions=$(".actions").clone();

$('.actions').addClass('actions2');
$(".actions").after(actions);

$('.drops').each(function(i,elem) {
$(elem).removeClass("actions");
});
$('.actions2').addClass('actions');
$('.actions').removeClass('actions2');
});
$(document).on("click",".copy",function(){

var actions=$(".actions").clone();

$(".sozd").html(actions);


});
$(document).on("click",".save",function(){
    
var style='';


$('.panel22 select').each(function(i,elem) {
attr=$(elem).attr('name');
val= $(elem).val();
if(val!='' && val!==null){
style+=attr+':'+val+'; ';
}
});

$('.panel22 input').each(function(i,elem) {
attr=$(elem).attr('name');
val= $(elem).attr('value');
if(val!='' && val!==null){

style+=attr+':'+val+'; ';
}
});
$(".actions").attr('style',style);

});

$(document).on("keyup",'.photo',function(){
if($(this).val()==''){
$(this).css('border','1px solid red');
}
else{
$(this).css('border','0px solid #eee');
}

});
 $(document).on("click",".plus",function(){

 var file=$('.photo').val();
if(file!=''){
 $.ajax({
            type: "POST",
            url: "/htmlred/files.ajax",
            data: {file:file},
             success: function(data){

 $('.pthoto-block').append('<img src="'+data+'" width="100%">');
$('.photo').val('');

}        });
 }
else{
alert('Введите ссылку на фото');
$('.photo').css('border','1px solid red');
}
 });
  $(document).on("click",".redactsites",function(){

 $.ajax({
            type: "POST",
            url: "/htmlred/files.ajax",
            data: {redact:'yes'},
             success: function(data){

$('.body').html(data);


}        });
 
 });
 
 
 
 $(document).on("change",".shs",function(){
     var value=$(this).val();
      $.ajax({
            type: "POST",
            url: "/htmlred/files.ajax",
            data: {shs:value},
             success: function(data){
$('.select').change();
$('.create').click();
$('.sozd').html(data);
createfunc();

}        });
     
 });
 $(document).ready(function(){
 
 var znochki = ["fa-glass ","fa-music ","fa-search ","fa-envelope-o ","fa-heart ","fa-star ","fa-star-o ","fa-user ","fa-film ","fa-th-large ","fa-th ","fa-th-list ","fa-check ","fa-remove","fa-close","fa-times ","fa-search-plus ","fa-search-minus ","fa-power-off ","fa-signal ","fa-gear","fa-cog ","fa-trash-o ","fa-home ","fa-file-o ","fa-clock-o ","fa-road ","fa-download ","fa-arrow-circle-o-down ","fa-arrow-circle-o-up ","fa-inbox ","fa-play-circle-o ","fa-rotate-right","fa-repeat ","fa-refresh ","fa-list-alt ","fa-lock ","fa-flag ","fa-headphones ","fa-volume-off ","fa-volume-down ","fa-volume-up ","fa-qrcode ","fa-barcode ","fa-tag ","fa-tags ","fa-book ","fa-bookmark ","fa-print ","fa-camera ","fa-font ","fa-bold ","fa-italic ","fa-text-height ","fa-text-width ","fa-align-left ","fa-align-center ","fa-align-right ","fa-align-justify ","fa-list ","fa-dedent","fa-outdent ","fa-indent ","fa-video-camera ","fa-photo","fa-image","fa-picture-o ","fa-pencil ","fa-map-marker ","fa-adjust ","fa-tint ","fa-edit","fa-pencil-square-o ","fa-share-square-o ","fa-check-square-o ","fa-arrows ","fa-step-backward ","fa-fast-backward ","fa-backward ","fa-play ","fa-pause ","fa-stop ","fa-forward ","fa-fast-forward ","fa-step-forward ","fa-eject ","fa-chevron-left ","fa-chevron-right ","fa-plus-circle ","fa-minus-circle ","fa-times-circle ","fa-check-circle ","fa-question-circle ","fa-info-circle ","fa-crosshairs ","fa-times-circle-o ","fa-check-circle-o ","fa-ban ","fa-arrow-left ","fa-arrow-right ","fa-arrow-up ","fa-arrow-down ","fa-mail-forward","fa-share ","fa-expand ","fa-compress ","fa-plus ","fa-minus ","fa-asterisk ","fa-exclamation-circle ","fa-gift ","fa-leaf ","fa-fire ","fa-eye ","fa-eye-slash ","fa-warning","fa-exclamation-triangle ","fa-plane ","fa-calendar ","fa-random ","fa-comment ","fa-magnet ","fa-chevron-up ","fa-chevron-down ","fa-retweet ","fa-shopping-cart ","fa-folder ","fa-folder-open ","fa-arrows-v ","fa-arrows-h ","fa-bar-chart-o","fa-bar-chart ","fa-twitter-square ","fa-facebook-square ","fa-camera-retro ","fa-key ","fa-gears","fa-cogs ","fa-comments ","fa-thumbs-o-up ","fa-thumbs-o-down ","fa-star-half ","fa-heart-o ","fa-sign-out ","fa-linkedin-square ","fa-thumb-tack ","fa-external-link ","fa-sign-in ","fa-trophy ","fa-github-square ","fa-upload ","fa-lemon-o ","fa-phone ","fa-square-o ","fa-bookmark-o ","fa-phone-square ","fa-twitter ","fa-facebook-f","fa-facebook ","fa-github ","fa-unlock ","fa-credit-card ","fa-feed","fa-rss ","fa-hdd-o ","fa-bullhorn ","fa-bell ","fa-certificate ","fa-hand-o-right ","fa-hand-o-left ","fa-hand-o-up ","fa-hand-o-down ","fa-arrow-circle-left ","fa-arrow-circle-right ","fa-arrow-circle-up ","fa-arrow-circle-down ","fa-globe ","fa-wrench ","fa-tasks ","fa-filter ","fa-briefcase ","fa-arrows-alt ","fa-group","fa-users ","fa-chain","fa-link ","fa-cloud ","fa-flask ","fa-cut","fa-scissors ","fa-copy","fa-files-o ","fa-paperclip ","fa-save","fa-floppy-o ","fa-square ","fa-navicon","fa-reorder","fa-bars ","fa-list-ul ","fa-list-ol ","fa-strikethrough ","fa-underline ","fa-table ","fa-magic ","fa-truck ","fa-pinterest ","fa-pinterest-square ","fa-google-plus-square ","fa-google-plus ","fa-money ","fa-caret-down ","fa-caret-up ","fa-caret-left ","fa-caret-right ","fa-columns ","fa-unsorted","fa-sort ","fa-sort-down","fa-sort-desc ","fa-sort-up","fa-sort-asc ","fa-envelope ","fa-linkedin ","fa-rotate-left","fa-undo ","fa-legal","fa-gavel ","fa-dashboard","fa-tachometer ","fa-comment-o ","fa-comments-o ","fa-flash","fa-bolt ","fa-sitemap ","fa-umbrella ","fa-paste","fa-clipboard ","fa-lightbulb-o ","fa-exchange ","fa-cloud-download ","fa-cloud-upload ","fa-user-md ","fa-stethoscope ","fa-suitcase ","fa-bell-o ","fa-coffee ","fa-cutlery ","fa-file-text-o ","fa-building-o ","fa-hospital-o ","fa-ambulance ","fa-medkit ","fa-fighter-jet ","fa-beer ","fa-h-square ","fa-plus-square ","fa-angle-double-left ","fa-angle-double-right ","fa-angle-double-up ","fa-angle-double-down ","fa-angle-left ","fa-angle-right ","fa-angle-up ","fa-angle-down ","fa-desktop ","fa-laptop ","fa-tablet ","fa-mobile-phone","fa-mobile ","fa-circle-o ","fa-quote-left ","fa-quote-right ","fa-spinner ","fa-circle ","fa-mail-reply","fa-reply ","fa-github-alt ","fa-folder-o ","fa-folder-open-o ","fa-smile-o ","fa-frown-o ","fa-meh-o ","fa-gamepad ","fa-keyboard-o ","fa-flag-o ","fa-flag-checkered ","fa-terminal ","fa-code ","fa-mail-reply-all","fa-reply-all ","fa-star-half-empty","fa-star-half-full","fa-star-half-o ","fa-location-arrow ","fa-crop ","fa-code-fork ","fa-unlink","fa-chain-broken ","fa-question ","fa-info ","fa-exclamation ","fa-superscript ","fa-subscript ","fa-eraser ","fa-puzzle-piece ","fa-microphone ","fa-microphone-slash ","fa-shield ","fa-calendar-o ","fa-fire-extinguisher ","fa-rocket ","fa-maxcdn ","fa-chevron-circle-left ","fa-chevron-circle-right ","fa-chevron-circle-up ","fa-chevron-circle-down ","fa-html5 ","fa-css3 ","fa-anchor ","fa-unlock-alt ","fa-bullseye ","fa-ellipsis-h ","fa-ellipsis-v ","fa-rss-square ","fa-play-circle ","fa-ticket ","fa-minus-square ","fa-minus-square-o ","fa-level-up ","fa-level-down ","fa-check-square ","fa-pencil-square ","fa-external-link-square ","fa-share-square ","fa-compass ","fa-toggle-down","fa-caret-square-o-down ","fa-toggle-up","fa-caret-square-o-up ","fa-toggle-right","fa-caret-square-o-right ","fa-euro","fa-eur ","fa-gbp ","fa-dollar","fa-usd ","fa-rupee","fa-inr ","fa-cny","fa-rmb","fa-yen","fa-jpy ","fa-ruble","fa-rouble","fa-rub ","fa-won","fa-krw ","fa-bitcoin","fa-btc ","fa-file ","fa-file-text ","fa-sort-alpha-asc ","fa-sort-alpha-desc ","fa-sort-amount-asc ","fa-sort-amount-desc ","fa-sort-numeric-asc ","fa-sort-numeric-desc ","fa-thumbs-up ","fa-thumbs-down ","fa-youtube-square ","fa-youtube ","fa-xing ","fa-xing-square ","fa-youtube-play ","fa-dropbox ","fa-stack-overflow ","fa-instagram ","fa-flickr ","fa-adn ","fa-bitbucket ","fa-bitbucket-square ","fa-tumblr ","fa-tumblr-square ","fa-long-arrow-down ","fa-long-arrow-up ","fa-long-arrow-left ","fa-long-arrow-right ","fa-apple ","fa-windows ","fa-android ","fa-linux ","fa-dribbble ","fa-skype ","fa-foursquare ","fa-trello ","fa-female ","fa-male ","fa-gittip","fa-gratipay ","fa-sun-o ","fa-moon-o ","fa-archive ","fa-bug ","fa-vk ","fa-weibo ","fa-renren ","fa-pagelines ","fa-stack-exchange ","fa-arrow-circle-o-right ","fa-arrow-circle-o-left ","fa-toggle-left","fa-caret-square-o-left ","fa-dot-circle-o ","fa-wheelchair ","fa-vimeo-square ","fa-turkish-lira","fa-try ","fa-plus-square-o ","fa-space-shuttle ","fa-slack ","fa-envelope-square ","fa-wordpress ","fa-openid ","fa-institution","fa-bank","fa-university ","fa-mortar-board","fa-graduation-cap ","fa-yahoo ","fa-google ","fa-reddit ","fa-reddit-square ","fa-stumbleupon-circle ","fa-stumbleupon ","fa-delicious ","fa-digg ","fa-pied-piper-pp ","fa-pied-piper-alt ","fa-drupal ","fa-joomla ","fa-language ","fa-fax ","fa-building ","fa-child ","fa-paw ","fa-spoon ","fa-cube ","fa-cubes ","fa-behance ","fa-behance-square ","fa-steam ","fa-steam-square ","fa-recycle ","fa-automobile","fa-car ","fa-cab","fa-taxi ","fa-tree ","fa-spotify ","fa-deviantart ","fa-soundcloud ","fa-database ","fa-file-pdf-o ","fa-file-word-o ","fa-file-excel-o ","fa-file-powerpoint-o ","fa-file-photo-o","fa-file-picture-o","fa-file-image-o ","fa-file-zip-o","fa-file-archive-o ","fa-file-sound-o","fa-file-audio-o ","fa-file-movie-o","fa-file-video-o ","fa-file-code-o ","fa-vine ","fa-codepen ","fa-jsfiddle ","fa-life-bouy","fa-life-buoy","fa-life-saver","fa-support","fa-life-ring ","fa-circle-o-notch ","fa-ra","fa-resistance","fa-rebel ","fa-ge","fa-empire ","fa-git-square ","fa-git ","fa-y-combinator-square","fa-yc-square","fa-hacker-news ","fa-tencent-weibo ","fa-qq ","fa-wechat","fa-weixin ","fa-send","fa-paper-plane ","fa-send-o","fa-paper-plane-o ","fa-history ","fa-circle-thin ","fa-header ","fa-paragraph ","fa-sliders ","fa-share-alt ","fa-share-alt-square ","fa-bomb ","fa-soccer-ball-o","fa-futbol-o ","fa-tty ","fa-binoculars ","fa-plug ","fa-slideshare ","fa-twitch ","fa-yelp ","fa-newspaper-o ","fa-wifi ","fa-calculator ","fa-paypal ","fa-google-wallet ","fa-cc-visa ","fa-cc-mastercard ","fa-cc-discover ","fa-cc-amex ","fa-cc-paypal ","fa-cc-stripe ","fa-bell-slash ","fa-bell-slash-o ","fa-trash ","fa-copyright ","fa-at ","fa-eyedropper ","fa-paint-brush ","fa-birthday-cake ","fa-area-chart ","fa-pie-chart ","fa-line-chart ","fa-lastfm ","fa-lastfm-square ","fa-toggle-off ","fa-toggle-on ","fa-bicycle ","fa-bus ","fa-ioxhost ","fa-angellist ","fa-cc ","fa-shekel","fa-sheqel","fa-ils ","fa-meanpath ","fa-buysellads ","fa-connectdevelop ","fa-dashcube ","fa-forumbee ","fa-leanpub ","fa-sellsy ","fa-shirtsinbulk ","fa-simplybuilt ","fa-skyatlas ","fa-cart-plus ","fa-cart-arrow-down ","fa-diamond ","fa-ship ","fa-user-secret ","fa-motorcycle ","fa-street-view ","fa-heartbeat ","fa-venus ","fa-mars ","fa-mercury ","fa-intersex","fa-transgender ","fa-transgender-alt ","fa-venus-double ","fa-mars-double ","fa-venus-mars ","fa-mars-stroke ","fa-mars-stroke-v ","fa-mars-stroke-h ","fa-neuter ","fa-genderless ","fa-facebook-official ","fa-pinterest-p ","fa-whatsapp ","fa-server ","fa-user-plus ","fa-user-times ","fa-hotel","fa-bed ","fa-viacoin ","fa-train ","fa-subway ","fa-medium ","fa-yc","fa-y-combinator ","fa-optin-monster ","fa-opencart ","fa-expeditedssl ","fa-battery-4","fa-battery","fa-battery-full ","fa-battery-3","fa-battery-three-quarters ","fa-battery-2","fa-battery-half ","fa-battery-1","fa-battery-quarter ","fa-battery-0","fa-battery-empty ","fa-mouse-pointer ","fa-i-cursor ","fa-object-group ","fa-object-ungroup ","fa-sticky-note ","fa-sticky-note-o ","fa-cc-jcb ","fa-cc-diners-club ","fa-clone ","fa-balance-scale ","fa-hourglass-o ","fa-hourglass-1","fa-hourglass-start ","fa-hourglass-2","fa-hourglass-half ","fa-hourglass-3","fa-hourglass-end ","fa-hourglass ","fa-hand-grab-o","fa-hand-rock-o ","fa-hand-stop-o","fa-hand-paper-o ","fa-hand-scissors-o ","fa-hand-lizard-o ","fa-hand-spock-o ","fa-hand-pointer-o ","fa-hand-peace-o ","fa-trademark ","fa-registered ","fa-creative-commons ","fa-gg ","fa-gg-circle ","fa-tripadvisor ","fa-odnoklassniki ","fa-odnoklassniki-square ","fa-get-pocket ","fa-wikipedia-w ","fa-safari ","fa-chrome ","fa-firefox ","fa-opera ","fa-internet-explorer ","fa-tv","fa-television ","fa-contao ","fa-500px ","fa-amazon ","fa-calendar-plus-o ","fa-calendar-minus-o ","fa-calendar-times-o ","fa-calendar-check-o ","fa-industry ","fa-map-pin ","fa-map-signs ","fa-map-o ","fa-map ","fa-commenting ","fa-commenting-o ","fa-houzz ","fa-vimeo ","fa-black-tie ","fa-fonticons ","fa-reddit-alien ","fa-edge ","fa-credit-card-alt ","fa-codiepie ","fa-modx ","fa-fort-awesome ","fa-usb ","fa-product-hunt ","fa-mixcloud ","fa-scribd ","fa-pause-circle ","fa-pause-circle-o ","fa-stop-circle ","fa-stop-circle-o ","fa-shopping-bag ","fa-shopping-basket ","fa-hashtag ","fa-bluetooth ","fa-bluetooth-b ","fa-percent ","fa-gitlab ","fa-wpbeginner ","fa-wpforms ","fa-envira ","fa-universal-access ","fa-wheelchair-alt ","fa-question-circle-o ","fa-blind ","fa-audio-description ","fa-volume-control-phone ","fa-braille ","fa-assistive-listening-systems ","fa-asl-interpreting","fa-american-sign-language-interpreting ","fa-deafness","fa-hard-of-hearing","fa-deaf ","fa-glide ","fa-glide-g ","fa-signing","fa-sign-language ","fa-low-vision ","fa-viadeo ","fa-viadeo-square ","fa-snapchat ","fa-snapchat-ghost ","fa-snapchat-square ","fa-pied-piper ","fa-first-order ","fa-yoast ","fa-themeisle ","fa-google-plus-circle","fa-google-plus-official ","fa-fa","fa-font-awesome ","fa-handshake-o ","fa-envelope-open ","fa-envelope-open-o ","fa-linode ","fa-address-book ","fa-address-book-o ","fa-vcard","fa-address-card ","fa-vcard-o","fa-address-card-o ","fa-user-circle ","fa-user-circle-o ","fa-user-o ","fa-id-badge ","fa-drivers-license","fa-id-card ","fa-drivers-license-o","fa-id-card-o ","fa-quora ","fa-free-code-camp ","fa-telegram ","fa-thermometer-4","fa-thermometer","fa-thermometer-full ","fa-thermometer-3","fa-thermometer-three-quarters ","fa-thermometer-2","fa-thermometer-half ","fa-thermometer-1","fa-thermometer-quarter ","fa-thermometer-0","fa-thermometer-empty ","fa-shower ","fa-bathtub","fa-s15","fa-bath ","fa-podcast ","fa-window-maximize ","fa-window-minimize ","fa-window-restore ","fa-times-rectangle","fa-window-close ","fa-times-rectangle-o","fa-window-close-o ","fa-bandcamp ","fa-grav ","fa-etsy ","fa-imdb ","fa-ravelry ","fa-eercast ","fa-microchip ","fa-snowflake-o ","fa-superpowers ","fa-wpexplorer ","fa-meetup"];

for(classv in znochki){
$('.okno_znak2').append('<div class="'+znochki[classv]+'"></div>');
}
 stylestr();
  var styleel = $('.body').attr("style");
  if(typeof(styleel)!=="undefined" && styleel!=null){
var styleelmass=styleel.split(';');
}
for (var key in styleelmass) {

var styleelmass2=styleelmass[key].split(':');
styleelmass2[0]=styleelmass2[0].replace(' ','');
if(styleelmass2[0]!=''){
$('.panel23 input[name="'+styleelmass2[0]+'"]').val(styleelmass2[1]);
$('.panel23 select[name="'+styleelmass2[0]+'"] option[value="'+styleelmass2[1]+'"]').prop('selected', true);

}
}
$('.panel23 input[name="font-face"]').val($('.fonts').html());
 });
  $(document).on("change",".select",function(){

var thisselect=$(this).val();
$('.div').html('<div class="cr">'+changes(thisselect)+'<input type="button" class="create" value="Создать"></div>');
 });
 
   $(document).on("click",".vkl1",function(){
 
 $(this).addClass('checks');
 $('.vkl2').removeClass('checks');

$('.panel22').css("display","block");
$('.panel23').css("display","none");
 });
 $(document).on("click",".vkl2",function(){
 
 $('.drops').each(function(i,elem) {
$(elem).removeClass("actions");
});

 $(this).addClass('checks');
 $('.vkl1').removeClass('checks');

$('.panel23').css("display","block");
$('.panel22').css("display","none"); 
 
 
 
 
 
 });
 
 
 $(document).on("click",".pthoto-block img",function(){
 $('.panel2 input[name="background-image"]').val("url('"+$(this).attr('src')+"')");
 $('.panel2 input[name="background-size"]').val("cover");
  $('.panel2 input[name="background-image"]').attr("value","url('"+$(this).attr('src')+"')");
 $('.panel2 input[name="background-size"]').attr("value","cover");
 $('.actions').css("background-image","url('"+$(this).attr('src')+"')");
 });
 
 function createfunc(){
  $('.drops').each(function(i,elem) {
$(elem).removeClass("actions");
});
 var scripts='';
for (var code in Elementcss) {
if(Elementcss[code]["type"]=='select'){
var mass=Elementcss[code]["value"]+'';
mass=mass.split(',');
var texts='';
for(var its=0;its<mass.length;its++){
texts+='<option value="'+mass[its]+'">'+mass[its]+'</option>';
}
scripts+='<p class="select-last" ids="'+Elementcss[code]["ids"]+'">'+Elementcss[code]["val"]+'</p><select name="'+code+'" ids="'+Elementcss[code]["ids"]+'">'+texts+'</select>';
}else{
if(Elementcss[code]["redactor"]=="plus"){
scripts+='<input type="'+Elementcss[code]["type"]+'" ids="'+Elementcss[code]["ids"]+'" name="'+code+'" alt="'+Elementcss[code]["value"]+'" title="'+Elementcss[code]["value"]+'" value="'+Elementcss[code]["val"]+'" placeholder="'+Elementcss[code]["value"]+'"><p class="inputp fa-edit field_setting" ids="'+Elementcss[code]["ids"]+'" names="'+code+'" attr="plus" values="'+Elementcss[code]["val"]+'" attr-data="'+Elementcss[code]["value"]+'"></p>';
}
else if(Elementcss[code]["redactor"]=="box"){
scripts+='<input type="'+Elementcss[code]["type"]+'" ids="'+Elementcss[code]["ids"]+'" name="'+code+'" alt="'+Elementcss[code]["value"]+'" title="'+Elementcss[code]["value"]+'" value="'+Elementcss[code]["val"]+'" placeholder="'+Elementcss[code]["value"]+'"><p class="boxp fa-edit field_setting" ids="'+Elementcss[code]["ids"]+'" names="'+code+'" attr="box" values="'+Elementcss[code]["val"]+'" attr-data="'+Elementcss[code]["value"]+'"></p>';
}
else if(Elementcss[code]["redactor"]=="minus"){
var val1=Elementcss[code]["val"];
if(Elementcss[code]["val"]!=''){
var val2=parseInt(Elementcss[code]["val"])+500;
}
else{
var val2=500;
}
scripts+='<input type="'+Elementcss[code]["type"]+'" ids="'+Elementcss[code]["ids"]+'" name="'+code+'" alt="'+Elementcss[code]["value"]+'" title="'+Elementcss[code]["value"]+'" value="'+val1+'" placeholder="'+Elementcss[code]["value"]+'"><p class="inputp fa-edit field_setting" ids="'+Elementcss[code]["ids"]+'" names="'+code+'" attr="minus"   values="'+val2+'" attr-data="'+Elementcss[code]["value"]+'"></p>';
}
else{
scripts+='<input type="'+Elementcss[code]["type"]+'" ids="'+Elementcss[code]["ids"]+'" name="'+code+'" alt="'+Elementcss[code]["value"]+'" title="'+Elementcss[code]["value"]+'" value="'+Elementcss[code]["val"]+'" placeholder="'+Elementcss[code]["value"]+'">';

}
}
}
$('.panel22').html('<br><input type="button" class="save" value="Сохранить"><div class="c1">Блок</div><div class="c2">Эффект</div><div class="c3">Текст</div><div class="c4">Фон</div><div class="cr2">'+scripts+'</div>');
var block = $('.select').val();
var attr='';
var val='';
var attribute='';var html2='';
if($('.select').val()=='select'){

var value=$('.cr input[name="value"]').val();
var texts=$('.cr input[name="text"]').val();
value=value.split(',');
texts=texts.split(',');
for(var ti=0;ti<texts.length;ti++){
html2+='<option value="'+value[ti]+'">'+texts[ti]+'</option>';
}
}else{
   $('.cr input[type="text"], .cr select').each(function(i,elem) {
  
attr=$(elem).attr('name');


if(attr=='html'){
html2=$(elem).val();

}

else{
val= $(elem).val();
attribute+=attr+'="'+val+'" ';

}


});
}


 }
 $(document).on("click",".create",function(){
 $('.drops').each(function(i,elem) {
$(elem).removeClass("actions");
});
 var scripts='';
for (var code in Elementcss) {
if(Elementcss[code]["type"]=='select'){
var mass=Elementcss[code]["value"]+'';
mass=mass.split(',');
var texts='';
for(var its=0;its<mass.length;its++){
texts+='<option value="'+mass[its]+'">'+mass[its]+'</option>';
}
scripts+='<p class="select-last" ids="'+Elementcss[code]["ids"]+'">'+Elementcss[code]["val"]+'</p><select name="'+code+'" ids="'+Elementcss[code]["ids"]+'">'+texts+'</select>';
}else{
if(Elementcss[code]["redactor"]=="plus"){
scripts+='<input type="'+Elementcss[code]["type"]+'" ids="'+Elementcss[code]["ids"]+'" name="'+code+'" alt="'+Elementcss[code]["value"]+'" title="'+Elementcss[code]["value"]+'" value="'+Elementcss[code]["val"]+'" placeholder="'+Elementcss[code]["value"]+'"><p class="inputp fa-edit field_setting" ids="'+Elementcss[code]["ids"]+'" names="'+code+'" attr="plus" values="'+Elementcss[code]["val"]+'" attr-data="'+Elementcss[code]["value"]+'"></p>';
}
else if(Elementcss[code]["redactor"]=="box"){
scripts+='<input type="'+Elementcss[code]["type"]+'" ids="'+Elementcss[code]["ids"]+'" name="'+code+'" alt="'+Elementcss[code]["value"]+'" title="'+Elementcss[code]["value"]+'" value="'+Elementcss[code]["val"]+'" placeholder="'+Elementcss[code]["value"]+'"><p class="boxp fa-edit field_setting" ids="'+Elementcss[code]["ids"]+'" names="'+code+'" attr="box" values="'+Elementcss[code]["val"]+'" attr-data="'+Elementcss[code]["value"]+'"></p>';
}
else if(Elementcss[code]["redactor"]=="minus"){
var val1=Elementcss[code]["val"];
if(Elementcss[code]["val"]!=''){
var val2=parseInt(Elementcss[code]["val"])+500;
}
else{
var val2=500;
}
scripts+='<input type="'+Elementcss[code]["type"]+'" ids="'+Elementcss[code]["ids"]+'" name="'+code+'" alt="'+Elementcss[code]["value"]+'" title="'+Elementcss[code]["value"]+'" value="'+val1+'" placeholder="'+Elementcss[code]["value"]+'"><p class="inputp fa-edit field_setting" ids="'+Elementcss[code]["ids"]+'" names="'+code+'" attr="minus"   values="'+val2+'" attr-data="'+Elementcss[code]["value"]+'"></p>';
}
else{
scripts+='<input type="'+Elementcss[code]["type"]+'" ids="'+Elementcss[code]["ids"]+'" name="'+code+'" alt="'+Elementcss[code]["value"]+'" title="'+Elementcss[code]["value"]+'" value="'+Elementcss[code]["val"]+'" placeholder="'+Elementcss[code]["value"]+'">';

}
}
}
$('.panel22').html('<br><input type="button" class="save" value="Сохранить"><div class="c1">Блок</div><div class="c2">Эффект</div><div class="c3">Текст</div><div class="c4">Фон</div><div class="cr2">'+scripts+'</div>');
var block = $('.select').val();
var attr='';
var val='';
var attribute='';var html2='';
if($('.select').val()=='select'){

var value=$('.cr input[name="value"]').val();
var texts=$('.cr input[name="text"]').val();
value=value.split(',');
texts=texts.split(',');
for(var ti=0;ti<texts.length;ti++){
html2+='<option value="'+value[ti]+'">'+texts[ti]+'</option>';
}
}
else if($('.select').val()=='video'){
    var poster=$('.cr input[name="poster"]').val();
    var src=$('.cr input[name="src"]').val();
   html2= '<source src="'+src+'" type="video/mp4">';
   attribute='loop muted autoplay poster="'+poster+'"';
}
else if($('.select').val()=='iframe'){
   var src=$('.cr input[name="src"]').val();
attribute=' marginheight="0" marginwidth="0" frameborder="0" src="'+src+'" ';

}


else{
   $('.cr input[type="text"], .cr select').each(function(i,elem) {
  
attr=$(elem).attr('name');


if(attr=='html'){
html2=$(elem).val();

}

else{
val= $(elem).val();
attribute+=attr+'="'+val+'" ';

}


});
}
$('.sozd').html('<'+block+' '+attribute+' class="drops"  draggable="true" class="drag" id="boxA" draggable="true" ondragstart="return dragStart(event)">'+html2+'</'+block+'>');
});

$(document).on("dblclick",".actions",function(){
var htmls=$('.sozd').html();
$(this).append(htmls);

var id=$(this).attr('id');
$('.selector_class').val(id);
});
$(document).on("keyup",".selector_class",function(){
var id=$(this).val();
$('.actions').attr('id',id);


});


$(document).on("mousemove",".panel22",function(){


$('.save').click();



});
$(document).on("keyup",".panel22 input",function(){


$('.save').click();



});
$(document).on("change",".panel22 select",function(){


$('.save').click();



});
$(document).on("mousemove",".panel23",function(){


$('.save2').click();



});
$(document).on("keyup",".panel23 input",function(){


$('.save2').click();



});
$(document).on("change",".panel23 select",function(){


$('.save2').click();



});
 $(document).on("change",".site_str",function(){

var name= $('.site_str').val(); 
if(name!='---'){
 $.ajax({
            type: "POST",
            url: "/htmlred/stranicahtml.ajax",
            data: {name:name},
             success: function(data){



$('.workarea').html(data);
createfunc();
}        });
}
 });

$(document).on("keyup",'.adress',function(){
if($(this).val()==''){
$(this).css('border','1px solid red');
}
else{
$(this).css('border','0px solid #eee');
}

});
 
 $(document).on("click",".saves",function(){
  var name=$('.workarea').html();
  var font=$('.fonts').html();
  $('.vkl2').click();
  var komment = $('.site_str').val(); 
  var namesite = $('.adress').val();
  var sitestr=$('.site_str').val();
  if(namesite!='' || sitestr!='---'){      
      if($('.rem').prop('checked')){
          var rem='yes';
      }
      else{
          var rem = 'no';
      }
      if($('.compression').prop('checked')){
          var compress='yes';
      }
      else{
          var compress = 'no';
      }
      $.ajax({
        type: "POST",
        url: "/htmlred/files.ajax",
        data: {sites:name,font:font,namesite:namesite,komment:komment,rem:rem,compress:compress},
        success: function(data){
          alert('Страница создана');
          $('.panel288').html('<br><a href="'+data+'" target="_blank">Посмотреть</a>');
        }        
      });
  }
  else{
    alert('введите название страницы');
    $('.adress').css('border','1px solid red');
  }
});
 
$(document).on("click",".delete_str",function(){
  var isdel = confirm("Вы уверены, что хотите удалить шаблон?");
  if(isdel){
  var file = $('.site_str').val(); 
   $.ajax({
      type: "POST",
      url: "/htmlred/del_files_sh.ajax",
      data: {file:file},
      success: function(data){
        alert('Страница удалена');
        document.location.href='/htmlred.modul';

      }        
    });
  } 
});
 
  $(document).on("click",".okno_znak2 div",function(){
    $(this).clone().appendTo('.actions');
  });
  $(document).on("click",".close_znak_okno",function(){
    $('.okno_znak').hide(200);
  });
  $(document).on("click",".insert_simv",function(){
    $('.okno_znak').show(200);
  });

$(document).on("keyup",'.sh555',function(){
  if($(this).val()==''){
    $(this).css('border','1px solid red');
  }
  else{
    $(this).css('border','0px solid #eee');
  }

});
  $(document).on("click",".plus2",function(){
var file=$('.actions').clone();
$(".sozdhid").html(file);
var file2=$(".sozdhid").html();
 var name=$('.sh555').val();
if(name!=''){
 $.ajax({
            type: "POST",
            url: "/htmlred/files.ajax",
            data: {sh:file2,name:name},
             success: function(data){

alert('Шаблон добавлен');


}        });
 }
else{
alert('Введите название шаблона');
$('.sh555').css('border','1px solid red');
}
 });




