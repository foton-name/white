function CopyToClipboard(containerid) {
    if (window.getSelection) {
        if (window.getSelection().empty) { // Chrome
            window.getSelection().empty();
        } else if (window.getSelection().removeAllRanges) { // Firefox
            window.getSelection().removeAllRanges();
        }
    } else if (document.selection) { // IE?
        document.selection.empty();
    }

    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select().createTextRange();
        document.execCommand("copy");

    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().addRange(range);
        document.execCommand("copy");
    }
}
$(document).on('mouseover','.div_search,.search_div',function() {
$('.search_div').css('overflow','visible');

});

$(document).on('click','.menu_rs',function() {
if($('.menux').css('display')=='none'){
$('.menux').show(200);
$(this).css('left','203px');
}
else{
$(this).css('left','2px');
$('.menux').hide(200);
}

});
$(document).on('mouseout','.div_search',function() {
$('.search_div').css('overflow','hidden');

});
$(document).on('click','.next',function() {
CopyToClipboard('textarea-example');
});
function timenone(){
    $('.timest2').hide(300);
}
setTimeout(timenone, 500);
$(document).on('keydown','body',function(eventObject){
var text_buf = window.getSelection(); 
if(text_buf==''){
}else{
if(eventObject.keyCode=='38'){
 $('.text1').val(text_buf); 
 var text_buf=String(text_buf);
    $.ajax({
            type: "POST",
            url: "/insertsession.ajaxadmin",
            data: {name:'text1',text:text_buf},
             success: function(data){


}        });
  }
 
  else if(eventObject.keyCode=='37'){
$('.text2').val(text_buf);
 var text_buf=String(text_buf);
    $.ajax({
            type: "POST",
            url: "/insertsession.ajaxadmin",
            data: {name:'text2',text:text_buf},
             success: function(data){


}        });
  }
 
  else{
  
  }
 
  }
  if(eventObject.keyCode=='40'){
      var text_buf = String($('.text1').val());
      if(typeof(text_buf)!="undefined"){
     document.execCommand("insertText", false,text_buf);
      }
  }
   else if(eventObject.keyCode=='118'){
       $('.text1').val('');
       $('.text2').val('');
         $.ajax({
            type: "POST",
            url: "/deletesession.ajaxadmin",
            success: function(){


}        });
 }
   else if(eventObject.keyCode=='39'){
       var text_buf2=String($('.text2').val());
        if(typeof(text_buf2)!="undefined"){
       document.execCommand("insertText", false,text_buf2);
        }
  }
  else{
      
  }
});


$(document).on('dblclick','.text1_inc',function() {

if($(this).css('width')=='0px' || $(this).css('width')=='1px'){
   $(this).attr('style','width:350px !important;');
    
}
else{
    $(this).attr('style','width:0px !important;'); 
}
});



 $(document).on("click",".up_core2",function(){
     
     $.ajax({
            type: "POST",
            url: "/updates_core.ajaxadmin",
            data: {'up_core':'yes'},
             success: function(data){
                 $('.front_up_f').html('Версия ядра '+data+'  <span class="up_core2 up_after"></span>');
             }
});

 });
 $(document).on("click",".up_core",function(){
     
     $.ajax({
            type: "POST",
            url: "/updates_core.ajaxadmin",
            data: {'up_core':'yes'},
             success: function(data){
                 document.location.href='/inform/';
             }
});

 });

 
$(document).on('keyup','.search',function() {
var search = $('.search').val();
var datas = $('.chislo').val()+'-'+$('.mes').val()+'-'+$('.god').val();


$.ajax({
            type: "POST",
            url: "/searchlog.ajaxadmin",
            data: {searchl: search,datal:datas},
             success: function(data){
$('.log1').html(data);

}        });


});

$(document).on('click','.x_frame',function() {
    if($(this).siblings('#iframe').attr('name')=='CONTENT'){
         $(this).parent('.frame_foton').hide(300);

    }
    else{
            $(this).parent('.frame_foton').remove();
    }
    
});

function loadIframe(iframeName, url) {
    var $iframe = $('#' + iframeName);
    if ( $iframe.length ) {
        $iframe.attr('src',url);   
        return false;
    }
    return true;
}

$(document).on('click','.menux a,.work_content a',function() {
loadIframe('iframe',$(this).attr('href'));
var name = $(this).html();
$('.work_frame .name_f').remove();
$('.work_frame').prepend('<p class="name_f">&lt;/ '+name+' &gt;</p>');
return false;
});

$(document).on('click','.pseudocheckbox',function() {

if($(this).siblings('.checknone').prop("checked") === true) {
$(this).siblings('input[type="hidden"]').val("off");
}
else{
$(this).siblings('input[type="hidden"]').val("on");
}

});

$(document).on('click','.contener a',function() {
    
    $('.work_frame').show(300);
    
});

$(document).on('click','.input-wrapper .setting',function() {
    if($('.background_up').css('display')=='none'){
    $('.background_up').show(300);
    }
    else{
         $('.background_up').hide(300);
    }
    
});

$(document).on('click','.work_content a',function() {
    
    $('.work_frame').show(300);
    
});
$(document).on('click','.search',function() {
var search = $('.search').val();
var datas = $('.chislo').val()+'-'+$('.mes').val()+'-'+$('.god').val();


$.ajax({
            type: "POST",
            url: "/searchlog.ajaxadmin",
            data: {searchl: search,datal:datas},
             success: function(data){
$('.log1').html(data);

}        });


});


$(document).on('click','.up_back',function() {
    var fon=['.menux .contener a','.head_admin','.over-search','.menux','.contener','.menu','.menu select',' .menu input[type="text"]','.background_up','input.up_back','input.background_update','.reserve','.content input[type="submit"]','.content input[name="delete-insert-include"]',' .content input[name="id"]',' #creat_menu','a.pn.pn2','a.pn.pn1','.slider_feater','.creates input[type="submit"]'];

var color=['.menux .contener a','.menux .h3','p.names_menu','.head_admin','.over-search','.menux','.contener','.menu','.menu select',' .menu input[type="text"]','.background_up','input.up_back','input.background_update','.reserve','.content input[type="submit"]','.content input[name="delete-insert-include"]',' .content input[name="id"]',' #creat_menu','a.pn.pn2','a.pn.pn1','.slider_feater','.creates input[type="submit"]','.panel_all','.center a','.exits input#exitst','.logsn_admin','.timest'];
    
  
var url = $('.background_update').val();
var colorfon=$('.colorfon').val();
var colortext=$('.colortext').val();
var colorcss='';
var foncss='';
if(colortext=='#000000'){
    colortext='';
}
if(colorfon=='#000000'){
    colorfon='';
}
if(colorfon!=''){
    for(var key in fon){
        $(fon[key]).css('background',colorfon);
        foncss+=fon[key]+'{background:'+colorfon+';}';
    }
}

if(colortext!=''){
    for(var key in color){
        $(color[key]).css('color',colortext);
          colorcss+=color[key]+'{color:'+colortext+';}';
    }
}
$.ajax({
            type: "POST",
            url: "/background_update.ajaxadmin",
            data: {url:url,colorcss:colorcss,foncss:foncss},
             success: function(data){
                   if($('.background_update').val()!=''){
$('.menux').css('backgroundImage','url("'+url+'")');

}

}        });


});


$(document).on('click','.filetext',function() {

var text = $(this).html();
var search = $('.search').val();
var datas = $('.chislo').val()+'-'+$('.mes').val()+'-'+$('.god').val();


$.ajax({
            type: "POST",
            url: "/searchlog.ajaxadmin",
            data: {searchl: search,datal:datas,text:text},
             success: function(data){
$('.textlog').val(data);

}        });


});

$(document).on('click','.max-okno',function() {

$(this).parent('.frame_foton').css('min-width','calc(100% - 20px)');
$(this).parent('.frame_foton').css('min-height','calc(100% - 50px)');
$(this).parent('.frame_foton').css('max-width','unset');
$(this).parent('.frame_foton').css('max-height','unset');
$(this).parent('.frame_foton').css('top','0px');
$(this).parent('.frame_foton').css('left','-20px');
$(this).parent('.frame_foton').css('float','none');
$(this).parent('.frame_foton').css('position','fixed');
$(this).parent('.frame_foton').children('iframe').show(200);
$(this).addClass('res-okno');
$(this).removeClass('max-okno');
$(this).parent('.frame_foton').children('iframe').css('height','100%');
});



$(document).on('click','.res-okno',function() {
$(this).parent('.frame_foton').css('min-width','unset');
$(this).parent('.frame_foton').css('min-height','unset');
$(this).parent('.frame_foton').css('max-width','unset');
$(this).parent('.frame_foton').css('max-height','unset');
$(this).parent('.frame_foton').css('top','5%');
$(this).parent('.frame_foton').css('left','0px');
$(this).parent('.frame_foton').css('float','none');
$(this).parent('.frame_foton').css('position','fixed');
$(this).addClass('max-okno');
$(this).removeClass('res-okno');
$(this).parent('.frame_foton').children('iframe').show(200);
$(this).parent('.frame_foton').children('iframe').css('height','100%');
});
function randomInteger(min, max) {
    var rand = min - 0.5 + Math.random() * (max - min + 1)
    rand = Math.round(rand);
    return rand;
  }

$(document).on('click','.min-okno',function() {
    $(this).parent('.frame_foton').css('min-width','unset');
$(this).parent('.frame_foton').css('min-height','unset');
if($(this).siblings('#iframe').attr('name')=='CONTENT'){
    $(this).parent('.frame_foton').clone().appendTo("body");
var rands=randomInteger(800, 2000);
var height=document.documentElement.clientHeight;
height=parseInt(height)-70;
$(this).parent('.frame_foton').css('max-width','258px');
$(this).parent('.frame_foton').css('max-height','0px');
$(this).parent('.frame_foton').css('top',height+'px');
$(this).parent('.frame_foton').children('iframe').css('height','0px');
$(this).parent('.frame_foton').css('float','right');
$(this).parent('.frame_foton').css('left','unset');
$(this).parent('.frame_foton').css('right','unset');
if($(this).siblings('.res-okno').length>0){
    $(this).siblings('.res-okno').addClass('max-okno');
     $(this).siblings('.res-okno').removeClass('res-okno');
}
else{
    $(this).siblings('.max-okno').addClass('res-okno');
     $(this).siblings('.max-okno').removeClass('max-okno'); 
}
$(this).parent('.frame_foton').css('position','relative');
$(this).parent('.frame_foton').addClass('.work_frame'+rands);
$(this).parent('.frame_foton').removeClass('work_frame');
$(this).siblings('#iframe').attr('name','CONTENT'+rands);
$(this).siblings('#iframe').attr('id','iframe'+rands);
$('.work_frame').css('display','none');
}
else{
var height=document.documentElement.clientHeight;
height=parseInt(height)-70;
if($(this).siblings('.res-okno').length>0){
    $(this).siblings('.res-okno').addClass('max-okno');
     $(this).siblings('.res-okno').removeClass('res-okno');
}
else{
  $(this).siblings('.max-okno').addClass('res-okno');
     $(this).siblings('.max-okno').removeClass('max-okno'); 
}
$(this).parent('.frame_foton').css('max-width','258px');
$(this).parent('.frame_foton').css('max-height','0px');
$(this).parent('.frame_foton').css('top',height+'px');
$(this).parent('.frame_foton').css('float','right');
$(this).parent('.frame_foton').children('iframe').css('height','0px');
$(this).parent('.frame_foton').css('position','relative');
    $(this).parent('.frame_foton').css('left','unset');
$(this).parent('.frame_foton').css('right','unset');
}

});


$(document).on('click','.x',function(){
    
    $('.okno-glob').hide(300);
     $('body').css('overflow','auto');
});
$(document).on('click','#overhiddenright',function(){

if($('.menu').css("width")!="25px" && $('.menu').css("width")!="25.5px"){

$('.menu').css("width","25px");
}
else{

$('.menu').css("width","25%");
}

});

$(document).on('click','.reserve .prev',function() {
if($('.oknologi').css('display')=='none'){
   $('.oknologi').show(300); 
}
else{
    $('.oknologi').hide(200);
}

});

$(document).on('click','.menu_raskr',function() {
if($('.menux').css('display')=='none'){
   $('.menux').show(300); 
}
else{
    $('.menux').hide(200);
}

});



$(document).on('click','.names_menu',function() {
   var style = $('.menu').attr('style');
  
   if(typeof style == "undefined"){
        $('.menu').css('height','max-content'); 
   }
   else{
       
if(style.indexOf('max-content')+1){
   $('.menu').css('height','25px'); 
}
else{
     $('.menu').css('height','max-content'); 
}
       
   }

});

$(document).on('click','#del_popap',function() {
var name = $('.okno-glob #scriptname').val();
var path = $('.okno-glob #scriptpath').val();
var text = $('.okno-glob #scripti-glob').val();
var dir=$('.okno-glob #scriptdir').val();
var del='yes';

$.ajax({
            type: "POST",
            url: "/ajax_save_mvc.ajaxadmin",
            data: {name:name,path:path,text:text,del:del,dir:dir},
             success: function(data){
$('.okno-glob').hide(200);
$('.updatemenu').load('/workarea/ .updatemenu');
}
});
});

$(document).on('click','#apiup',function() {
if($('.upapi').css('display')=='none'){
$.ajax({
            type: "POST",
            url: "/api_up_js.ajaxadmin",
            data: $(".kodes").serialize(),
             success: function(data){
$('.upapi').html(data);
$('.upapi').show(200);
}    

            
        }); 
}
else{
$('.upapi').hide(200);
}
});

$(document).on('click','#save57',function() {
var name = $('.okno-glob #scriptname').val();
var path = $('.okno-glob #scriptpath').val();
var text = $('.okno-glob #scripti-glob').val();
var dir=$('.okno-glob #scriptdir').val();
var del='no';

$.ajax({
            type: "POST",
            url: "/ajax_save_mvc.ajaxadmin",
            data: {name:name,path:path,text:text,del:del,dir:dir},
             success: function(data){
$('.updatemenu').load('/workarea/ .updatemenu');


}
});
});
$(document).on('mousemove','.okno-glob',function(e){
if($('.oknogogo').val()=='1'){
var top=parseInt(e.pageY)-parseInt($('.okno-glob').css('height'))/2;
var left=parseInt(e.pageX)-parseInt($('.okno-glob').css('width'))/2;
$('.okno-glob').css('top',top);
$('.okno-glob').css('left',left);

}

});
$(document).on('dblclick','.okno-glob',function(e){
$('.oknogogo').val('1');

$('.okno-glob').css('cursor','move');
$('.okno-glob').css('z-index','50000000');
$('.upapi').css('z-index','50000000');
});
$(document).on('mouseup','.okno-glob',function(e){
$('.oknogogo').val('0');
$('.okno-glob').css('z-index','5');
$('.upapi').css('z-index','5');
});


$(document).on('click','.inf', function(){
if($('.information').css('display')=='none'){
    $('.information').show();
    $('.ajaxnew').hide();
$('.cont-el').hide();
}
else{
$('.information').hide();
    $('.ajaxnew').show();
if($('.administr1').css('display')=='none'){
$('.cont-el').show();
}
}
 
});
$(document).on('mouseenter','.modactiv', function(){
    $('.menu-mod').css('display','block');
});
$(document).on('mouseleave','.modactiv', function(){
    $('.menu-mod').css('display','none');
});
$(document).on('click','.pback', function(){
var key = $(this).attr('key');
$('.uploads_back').show(200);
$.ajax({
            type: "POST",
            url: "/insertdb.ajaxadmin",
            data: {key:key},
             success: function(data){

alert('Бекап сделан');
$('.uploads_back').hide(200);
$('.dop-tb').append("<tr><td>"+data+"</td><td><a href='/zip/?file="+data+"' class='href-zip'>Скачать</a> <a href='/zip/?del="+data+"' class='href-zip'>Удалить</a> <a href='/inform/' class='href-zip'>Очистить кеш</a></td></tr>");

}        });

});

$(document).on('click','.clickme',function(){
$('.clickme p').each(function(indx, element){
$(element).removeClass('active');

});
$('.contener').each(function(indx, element){

$(element).hide(300);
});
if($(this).find('.contener').css('display')=='none'){
    $(this).find('.contener').show(300);
$(this).children('p').addClass('active');
}
else{
  $(this).find('.contener').hide(300);
  $(this).children('p').removeClass('active');
  }

    
});








$(document).on('focus','.include-loadkey input', function(){
var val=$(this).val();
$(this).attr('val',val);
if($(this).val()==$(this).attr('name')){
$(this).val('');
}
});
$(document).on('blur','.include-loadkey input', function(){
if($(this).val()===''){
var val=$(this).attr('val');
$(this).val(val);

}
});
$(document).on('focus','.include-loadkey textarea', function(){
var val=$(this).val();
$(this).attr('val',val);
if($(this).val()==$(this).attr('name')){
$(this).val('');
}
});
$(document).on('blur','.include-loadkey textarea', function(){
if($(this).val()===''){
var val=$(this).attr('val');
$(this).val(val);

}
});


$(document).ready(function(){

$('polygon').each(function(i,elem) {
    var data_ajax =[];
$.each(elem.attributes, function() {
    if(this.specified) {
    var add=data_ajax.push(this.name+':::'+this.value);
    }
  });
var html=$(elem).html();

data_ajax=data_ajax.join('|||');

$.ajax({
            type: "POST",
            url: "/polygon_echo.ajaxadmin",
            data: {data_ajax:data_ajax,html:html},
             success: function(data){

$(elem).html(data);


}        });

});

$('.contener').css('height',$('.menux').css('height'));


});





  

$(document).on('change','.updatemenu select',function() {
var patchs=$(this).attr('patchs');

var val=$(this).val();
var mass = val.split(':');
var files =mass[0];
var dir=mass[1];

$.ajax({
            type: "POST",
            url: "/ajaxpopap.ajaxadmin",
            data: {pathmvc:patchs,file:files,dir:dir},
             success: function(data){
$('.okno-glob #scriptname').val(files);
$('.okno-glob #scriptpath').val(patchs);
$('.okno-glob #scriptdir').val(dir);
$('.okno-glob textarea').val(data);
   $('html, body').animate({scrollTop: 0}, 300);
 $('.okno-glob').show(300);

 $('body').css('overflow','hidden');
}
});


});




$(document).on('click','#newkod2',function() {

var text=$('.searchsh').val();
if(text!=''){
$.ajax({
            type: "POST",
            url: "/translite_ajax.ajaxadmin",
            data: {text:text},
             success: function(data){
$('.okno-glob #scriptname').val(data);
$('.okno-glob #scripti-glob').val('');
$('.okno-glob #scriptdir').val($('#patch3').attr('dir'));
}
});

$('.okno-glob #scriptpath').val('view');
$('.okno-glob').show(300);
}
else{
alert('введите название файла');
}
});
$(document).on('click','#newkod',function() {

var text=$('.searchsh').val();
if(text!=''){
$.ajax({
            type: "POST",
            url: "/translite_ajax.ajaxadmin",
            data: {text:text},
             success: function(data){
$('.okno-glob #scriptname').val(data);
$('.okno-glob #scripti-glob').val('<? class Controller_'+data+' extends Model_'+data+'{ public function dir(){ return "site"; } }');
}
});
$('.okno-glob #scriptdir').val('sites');
$('.okno-glob #scriptpath').val('controller');
$('.okno-glob').show(300);
}
else{
alert('введите название файла');
}
});

$(document).on('click','#newkodagr',function() {

var text=$('.searchsh').val();
if(text!=''){
$.ajax({
            type: "POST",
            url: "/translite_ajax.ajaxadmin",
            data: {text:text},
             success: function(data){
$('.okno-glob #scriptname').val(data);
$('.okno-glob #scripti-glob').val('<? class Model_'+data+' extends Model{ public function nameinclude(){    return "'+text+'"; } }');
}
});

$('.okno-glob #scriptpath').val('model');
$('.okno-glob #scriptdir').val('sites');
$('.okno-glob').show(300);
}
else{
alert('введите название файла в поиске');
}
});
$(document).on('click','.panel_all .update',function() {


$.ajax({
            type: "POST",
            url: "/update_razdel_all.ajaxadmin",
               success: function(data){

}
});


});

function del_files(){
    $.ajax({
            type: "POST",
            url: "/del_passwords.ajaxadmin",
               success: function(data){
                   
               }
});
}

$(document).on('click','.panel_all .delete_log',function() {


$.ajax({
            type: "POST",
            url: "/delete_logs.ajaxadmin",
               success: function(data){

}
});


});


$(document).on('click','.panel_all .key',function() {


$.ajax({
            type: "POST",
            url: "/new_passwords.ajaxadmin",
               success: function(data){
document.location.href='/file_force_download.ajaxadmin';

setTimeout(del_files,2000);
}
});


});








 




