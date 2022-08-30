$(document).on('click','.step_3',function() {
 
 var table='';
  var nores='no';
  $('.sp_table').each(function(i,elem) {
      if($(elem).find('.table').val()==''){
          nores='yes';
      }
  table=table+'@@@'+$(elem).find('.table').val()+'---'+$(elem).find('.table_n').val()+':::';
  var name_pole='';
  var format_pole='';
  var html_pole='';
var name_pole_r='';
$(elem).find('.tablex .pole_sp_2').each(function(i,elem2) {
    if($(elem2).val()==''){
         nores='yes';
    }
name_pole=name_pole+'---'+$(elem2).val();
});
$(elem).find('.tablex .pole_sp_n').each(function(i,elem2) {
    if($(elem2).val()==''){
         nores='yes';
    }
name_pole_r=name_pole_r+'---'+$(elem2).val();
});


$(elem).find('.tablex .pole_html_2').each(function(i,elem2) {
 if($(elem2).val()==''){
         nores='yes';
    }
var ids=$(elem2).attr('ids');
var arg = $(elem2).parent('td').next('td').find('#'+ids).val();
if(arg!=''){
html_pole=html_pole+'---'+$(elem2).val()+':'+arg;
}
else{
html_pole=html_pole+'---'+$(elem2).val();
}
});
$(elem).find('.tablex .pole_format_2').each(function(i,elem2) {
 if($(elem2).val()==''){
         nores='yes';
    }
format_pole=format_pole+'---'+$(elem2).val();

});
table=table+name_pole+'%%%'+html_pole+'%%%'+format_pole+'%%%'+name_pole_r;

});

if(nores=='yes'){
      alert('Заполнены не все поля');
  }
else{
$.ajax({ 

type: "POST",
            url: "/panelsections/update_razdel.ajax",
            data:{table:table},
            success: function(data){
console.log(data);


       $('.head_sections div').each(function(i,elem) {
      $(elem).removeClass('activ_section');
  });
     $('.i3').addClass('activ_section');
    $.ajax({ 
            type: "POST",
            url: "/panelsections/chmod_ajax_tab3.ajax",
            success: function(data){
$('.content_sections').html(data);

  
}
});

}
});
}

});
$(document).on('mouseover','.step_3',function() {
$('.tablex').each(function(i,el){
$(el).children('table').find('tr').find('td:eq(3)').children('select:eq(0)').val('input');

});
});

$(document).on('mouseover','.create_razdel',function() {
$('.pole_sp_section').each(function(i,el){
$(el).children('.contener_new_pole:eq(0)').find('select.html_pole_new').val('input');

});
});
$(document).on('click','.del_r',function() {
    var r=$(this).attr('razdel');
    var isdel = confirm("Вы уверены, что хотите удалить раздел?");
if(isdel){
       $.ajax({ 
            type: "POST",
            url: "/panelsections/del_razdel.ajax",
            data:{r:r},
            success: function(data){

document.location.href='/panelsections.modul';
  
}
});
}
});

$(document).on('click','.create_check',function() {
var tb=$('.tb_name').val();

   $.ajax({ 
            type: "POST",
            url: "/panelsections/dop_check.ajax",
            data:{tb:tb},
            success: function(data){
$('.content_sections').prepend(data);

  
}
});

});

$(document).on('click','.create_table',function() {
var htmlres=$('.content_sections').html();
var razdel=$('.razdel_add').val();
if(razdel!=''){
    $.ajax({ 
            type: "POST",
            url: "/panelsections/dop_table.ajax",
            data:{razdel:razdel},
            success: function(data){
$('.content_sections').html(data+htmlres);

  
}
});
}
else{
    alert('Введите название раздела');
}

});
$(document).on('click','.step_5',function() {
    
        $.ajax({ 
            type: "POST",
            url: "/panelsections/compilation_res.ajax",
             success: function(data){
$('.side2').css('width','100%');
    $('.side2').html('100%');
document.location.href='/panelsections.modul';
  
}
});
    
    
});


$(document).on('click','.step_4',function() {
var table='';
   $('.sp_table').each(function(i,elem) {
  table+=':::'+$(elem).find('.table_check').attr('tb')+'%%%';
   $(elem).find('input:checkbox:checked').each(function(i2,elem2) {
   table+='---'+$(elem2).val();
   
   });
   });
$.ajax({ 
            type: "POST",
            data:{table:table},
            url: "/panelsections/kompilation.ajax",
            success: function(data){
                    $('.head_sections div').each(function(i,elem) {
      $(elem).removeClass('activ_section');
  });
    $('.i4').addClass('activ_section');
                $('.content_sections').html(data);
}
});

});
function randomInteger(min, max) {
    var rand = min - 0.5 + Math.random() * (max - min + 1)
    rand = Math.round(rand);
    return rand;
  }

$(document).on('keyup','.pole_html_2',function() {
$(this).attr('value',$(this).val());

});
$(document).on('keyup','.pole_sp_2',function() {
$(this).attr('value',$(this).val());
});


$(document).on('click','.create_pole',function() {
    var table=$(this).attr('tb');
var rands=randomInteger(100, 500);

$.ajax({ 
            type: "POST",
            url: "/panelsections/new_pole_sp.ajax",
            success: function(data){
tr='<tr><td><input type="text" class="del_'+table+rands+' pole_sp_2" ></td><td><input type="text" class="del_'+table+rands+' pole_sp_n"  ></td><td><input type="text" class="del_'+table+rands+' pole_format_2" value="text" disabled=""></td><td><select class="del_'+table+rands+' pole_html_2" ids="ids'+rands+'">'+data+'</select></td><td><input type="text" class="del_'+table+rands+' pole_arg_2"  id="ids'+rands+'"></td><td><input type="button" class="del_pole_2" del="'+table+rands+'" value="Удалить поле"></td></tr>';
$('.table_'+table+' table').append(tr); 
}
});
   

});
$(document).on('click','.del_tb',function() {
    var id=$(this).attr('table');
    $('.deltb_'+id).remove();

$.ajax({ 
            type: "POST",
            url: "/panelsections/deltb.ajax",
            data:{table:id},
            success: function(data){

}
});

});
$(document).on('click','.del_pole_2',function() {
    var tb=$(this).parents('.sp_table').find('.table').val();
var pole=$(this).attr('del');
var pole_del=$('.del_'+pole).val();

$.ajax({ 
            type: "POST",
            url: "/panelsections/delpole.ajax",
            data:{table:tb,pole:pole_del},
            success: function(data){

}
});

var id=$(this).attr('del');
$('.del_'+id).remove();
$(this).remove();
});


$(document).on('click','.section',function() {
   
   var href =  $(this).attr('href');
   
$.ajax({ 
            type: "POST",
            url: "/panelsections/table_section.ajax",
            data:{section:href},
            success: function(data){
    $('.head_sections div').each(function(i,elem) {
      $(elem).removeClass('activ_section');
  });
    $('.i2').addClass('activ_section');
$.ajax({ 
            type: "POST",
            url: "/panelsections/table_section_view.ajax",
            data:{mass:data},
            success: function(data){
$('.content_sections').html(data);

  $('.head_sections div').each(function(i,elem) {
      $(elem).addClass('activ_tab');
  });
  
$('.pole').each(function(i,elem) {
    $(elem).click();
});
}
});
}
});
              
});

$(document).on('click','.del_new_pole',function() {
    $(this).closest('.contener_new_pole0').remove();
});


$(document).on('click','.del_new_pole2',function() {
    $(this).closest('.contener_new_pole').remove();
});


$(document).on('click','.i1',function() {
        $('.head_sections div').each(function(i,elem) {
      $(elem).removeClass('activ_section');
  });
   $('.i1').addClass('activ_section');
$.ajax({ 
            type: "POST",
            url: "/panelsections/select_sections_ajax.ajax",
        
            success: function(data){
                $('.content_sections').html(data);
            }
});

});


$(document).on('click','.pole',function() {
    var table=$(this).attr('table');
$.ajax({ 
            type: "POST",
            url: "/panelsections/table_ajax.ajax",
            data:{table:table},
            success: function(data){
                $('.table_'+table).html(data);
            }
});

});






$(document).on('click','p.close_new_okno',function() {
$('.okno_new_section').hide(300);
});

$(document).on('click','input.create_section',function() {
$('.okno_new_section').show(300);
});



$(document).on('keyup','.new_name',function() {
var text = $(this).val();
    $.ajax({ 
            type: "POST",
            url: "/panelsections/translites.ajax",
data:{text:text},
            success: function(data){
$('.new_translit').val(data);

  
}
});
});

function delremove(){
       $('.new_section_pole').each(function(i,elem) {
    $(elem).removeClass('activsp'); 
       });
       
}

$(document).on('click','.new_section_pole',function() {
    $(this).addClass('activsp');
       $.ajax({ 
            type: "POST",
            url: "/panelsections/new_pole.ajax",
            success: function(data){
 $('.activsp').siblings('.pole_sp_section').append(data);   
 setTimeout(delremove, 1000);
            }
            
       });
       
    
       
    
});

$(document).on('click','.new_section_pole0',function() {
    
       $.ajax({ 
            type: "POST",
            url: "/panelsections/new_pole0.ajax",
            success: function(data){
 $('.pole_sp_section0').append(data);   
 
            }
            
       });
    
});






$(document).on('click','.create_razdel',function() {
    var trrazdel =$('.new_translit').val();
    var name=$('.new_name').val();
if(trrazdel!='' && name!=''){
$.ajax({ 
            type: "POST",
            url: "/panelsections/is_razdel.ajax",
            data:{razdel:trrazdel},
            success: function(data){

  if(data=='no'){
  
  var table='';
  var nores='no';
  $('.contener_new_pole0').each(function(i,elem) {
      if($(elem).find('.new_name_pole2').val()==''){
          nores='yes';
      }
  table=table+'@@@'+$(elem).find('.new_name_pole2').val()+':::';
  var name_pole='';
  var name_poler='';
  var format_pole='';
  var html_pole='';
  var prava='';
  $(elem).find('.filests').find('input:checkbox:checked').each(function(i,elem3){
  prava=prava+'---'+$(elem3).val();
  });
$(elem).find('.contener_new_pole').each(function(i,elem2) {
    if($(elem2).find('.new_name_pole').val()=='' || $(elem2).find('.html_pole_new').val()=='' || $(elem2).find('.new_name_pole_rus').val()=='' || $(elem2).find('.format_pole_new').val()==''){
         nores='yes';
    }
name_pole=name_pole+'---'+$(elem2).find('.new_name_pole').val();
name_poler=name_poler+'---'+$(elem2).find('.new_name_pole_rus').val();
if($(elem2).find('.arg_pole_new').val()!=''){
html_pole=html_pole+'---'+$(elem2).find('.html_pole_new').val()+':'+$(elem2).find('.arg_pole_new').val();
}
else{
html_pole=html_pole+'---'+$(elem2).find('.html_pole_new').val();
}
format_pole=format_pole+'---'+$(elem2).find('.format_pole_new').val();


  
});
table=table+name_pole+'%%%'+html_pole+'%%%'+format_pole+'%%%'+name_poler+'%%%'+prava;
  });
  if(nores=='yes'){
      alert('Заполнены не все поля');
  }
else{
$.ajax({ 
            type: "POST",
            url: "/panelsections/create_razdel.ajax",
            data:{table:table,name:name,file:trrazdel},
            success: function(data){
              
alert('Раздел создан');
document.location.href='/panelsections.modul';
}
});

}
  
  }
  else{
 alert('Раздел уже существует'); 
  }
}
});
}
else{
alert('Введите название раздела');
}
});




