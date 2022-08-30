

$(document).on("click",".default", function(){

var u='';
var vart=$('#var').val();
$('.func').val($('#table').val());


 var tagList = vart.split(',');
var itst=tagList.length+2;

$('#kolv').val(itst);
var iu=0;
var iu2=iu+1;
 u=u+"name:<input type='text' id='poleii"+iu+"' name='poleii"+iu+"' value='"+iu2+"head'> Содержимое:<textarea name='fileii"+iu+"' id='fileii"+iu+"'>$result25 = $this->db->query(\"SELECT * FROM  `"+$('#table').val()+"` \");  foreach($result25 as $row) {</textarea><br> ";
var counti=tagList.length;
 for(var iu=0;iu<counti;iu++){
 var count=length.tagList-1;
 var iu2=iu+1;
 var iu3=iu2+1;
 



 u=u+"name:<input type='text' id='poleii"+iu2+"' name='poleii"+iu2+"' value='"+iu3+"block'> Содержимое:<textarea name='fileii"+iu2+"' id='fileii"+iu2+"'> echo $row['"+tagList[iu]+"'];</textarea><br> ";

  }
iu=iu3+1;
  u=u+"name:<input type='text' id='poleii"+iu+"' name='poleii"+iu+"' value='"+iu+"end'> Содержимое:<textarea name='fileii"+iu+"' id='fileii"+iu+"'>}</textarea><br> ";

 $("#u").html(u);
  $("#u2").html(u);
 var vart=$('#var').val('');
 });

$(document).on("click",".default2", function(){

var u='';
var vart=$('#var2').val();
$('.func').val($('#table').val());


 var tagList = vart.split(',');
var itst=tagList.length+2;

$('#kolv').val(itst);
var iu=0;
var iu2=iu+1;
 u=u+"name:<input type='text' id='poleii"+iu+"' name='poleii"+iu+"' value='"+iu2+"head'> Содержимое:<textarea name='fileii"+iu+"' id='fileii"+iu+"'>$result25 = $this->db->query(\"SELECT * FROM  `"+$('#table').val()+"` \");  foreach($result25 as $row) {</textarea><br> ";
var counti=tagList.length;
 for(var iu=0;iu<counti;iu++){
 var count=length.tagList-1;
 var iu2=iu+1;
 var iu3=iu2+1;
 



 u=u+"name:<input type='text' id='poleii"+iu2+"' name='poleii"+iu2+"' value='"+iu3+"block'> Содержимое:<textarea name='fileii"+iu2+"' id='fileii"+iu2+"'> echo $row['"+tagList[iu]+"'];</textarea><br> ";

  }
iu=iu3+1;
  u=u+"name:<input type='text' id='poleii"+iu+"' name='poleii"+iu+"' value='"+iu+"end'> Содержимое:<textarea name='fileii"+iu+"' id='fileii"+iu+"'>}</textarea><br> ";

 $("#u").html(u);
  $("#u2").html(u);
 var vart=$('#var2').val('');
 });
 
 $(document).on("click",".checkeds", function(){
if($(this).val()=='on'){
$(this).siblings('input[type="hidden"]').val('off');
$(this).val('off');
}
else{
$(this).val('on');
$(this).siblings('input[type="hidden"]').val('on');
}
});

$(document).on("click",".plust", function(){
var attr=$(this).attr('attr-id');
var id=$(this).attr('attr-id2');
var id2=parseInt(id)+1;
 $(".divdel"+attr).append("<div class='divdel"+attr+"2'>name:<input type='text' id='poleii"+id2+"' name='poleii"+id2+"' value=''> Содержимое:<textarea name='fileii"+id2+"' id='fileii"+id2+"'></textarea><input type='button' class='plust' value='+' attr-id='"+attr+"' attr-id2='"+id2+"'><input type='button' class='minust' value='-' attr-id='"+attr+"' attr-id2='"+id2+"' ></div><br>");

});

$(document).on("click",".minust", function(){
var attr=$(this).attr('attr-id');
var id=$(this).attr('attr-id2');
var id2=parseInt(id)+1;
 $(".divdel"+attr).remove();

});


$(document).on("keyup","#kolv", function(){
var u='';
 
 for(var iu=0;iu<$("#kolv").val();iu++){
 var iu2=iu+1;
  u=u+"name:<input type='text' id='poleii"+iu+"' name='poleii"+iu+"' value='"+iu2+"block'> Содержимое:<textarea name='fileii"+iu+"' id='fileii"+iu+"'></textarea><br> ";
  
  }

 $("#u").html(u);

 });
 $(document).on('click','.new',function() {
if($('.hiddes').css('display')=='none'){
$('.hiddes').css('display','block');
}
else{
$('.hiddes').css('display','none');
}

});

 $(document).on('click','.hiddes2 p',function() {
if($('.hiddes2').css('display')=='none'){
$('.hiddes2').css('display','block');
}
else{
$('.hiddes2').css('display','none');
}

});

 $(document).on('click','.hiddes p',function() {
if($('.hiddes').css('display')=='none'){
$('.hiddes').css('display','block');
}
else{
$('.hiddes').css('display','none');
}

});
$(document).on('change','#table',function(){
 
  if($(this).val()=='new-vars'){
 
  $('#var').val('');
  
  
 }
  else{
  
   var descvar=$('#'+$(this).val()).attr('desc-id');
var descvar=descvar.substring(0, descvar.length - 1);

 
 $('#var').val(descvar);
 
 }
  });
 $(document).on("keyup","#kolv2", function(){
var u='';
 
 for(var iu=0;iu<$("#kolv2").val();iu++){
 var iu2=iu+1;
  u=u+"name:<input type='text' id='poleii"+iu+"' name='poleii"+iu+"'  value='"+iu2+"block'> Содержимое:<textarea name='fileii"+iu+"' id='fileii"+iu+"'></textarea><br> ";
  
  }

 $("#u2").html(u);

 });



$(document).on('change','#table2',function(){
 
  if($(this).val()=='new-vars'){
 
  $('#var2').val('');
  
  
 }
  else{
  
   var descvar=$('#'+$(this).val()).attr('desc-id');
var descvar=descvar.substring(0, descvar.length - 1);

 
 $('#var2').val(descvar);
 
 }
  });
  
  $(document).on('click','.filetext',function() {
var api=$(this).html();
$.ajax({
            type: "POST",
            url: "/tplphp/obr_php_shablon.ajax",
            data: {api: api},
                success: function(data){
$('.hiddes2').html(data);
$('.hiddes2').css('display','block');
}     
        });

});

$(document).on("click",".copyf", function(){
var api=$('.copyfs').val();
var copy=$('.copyr').val();
$.ajax({
            type: "POST",
            url: "/tplphp/obr_php_shablon.ajax",
            data: {api: api,copy:copy},
                success: function(data){
$('.hiddes2').html(data);

}     
        });



});