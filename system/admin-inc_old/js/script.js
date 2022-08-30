

$(document).on('click','.nct',function() {
document.execCommand("paste");
});
$(document).on('click','.but',function() {
var key = $('input[name="ititifhkjk"]').val();
var login=$('.logininc').val();
var pass=$('.passinc').val();
if(key!='' && login!='' && pass!=''){
var text='<style>.cube { transform: rotateX(90deg) rotateY(90deg) !important;}.bottom,.left,.right,.top,.back{opacity:1 !important;}</style>';
$('.text').html(text);

 $.ajax({
            type: "POST",
            url: "/autorizajax.ajaxsite",
            data: {key:key,login:login,pass:pass},
                   success: function(data){
$('.logis').html(data);
}  
    
 
});
}
else{
    alert('Введите данные');
}
       
});
$(document).on('click','.but2',function() {
var key2 = $('input[name="hash"]').val();
start_page = $(this).attr('start_page');
if(key2!=''){
var text='<style>.cube { transform: rotateX(180deg) rotateY(180deg) !important;}.bottom,.left,.right,.top,.back{opacity:1 !important;}</style>';
$('.text').html(text);


       setTimeout('$(\'.front\').html("<img src=\'/system/admin-inc/img/load.gif\'>")', 500);
        $.ajax({
            type: "POST",
            url: "/autorizajax.ajaxsite",
            data: {key2:key2},
               success: function(data){
                  
                if(data=="Данные авторизации введены не верно"){
                    alert(data);
                      setTimeout("document.location.href='/admin/'", 1000);
                                     }
                    else{
                    setTimeout("document.location.href='/"+start_page+"/'", 2000);
                    }
                        
                }
});

}
else{
    alert('Введите данные');
}
});
function randomInteger(min, max) {
  let rand = min - 0.5 + Math.random() * (max - min + 1);
  return Math.round(rand);
}
function backitem(){
    var rand = randomInteger(1, 8);
    $('body').css('background-image','url(https://foton.name/app/view/site/img/f'+rand+'.jpg)');
}

setInterval(backitem,12000);



