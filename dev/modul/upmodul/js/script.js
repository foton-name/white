$(document).on('click','.up-text',function(){ 
$(this).addClass('up-process');
var mod=$(this).attr('nm'); 

$.ajax({ 
            type: "POST",
            url: "/upmodul/install_ms.ajax",
            data: {mod:mod},
             success: function(data){
$('.up-process').removeClass('up-process');
}
});
});
  
function ajax_f(){  

$.ajax({ 
            type: "POST",
            url: "/upmodul/vivodm_ajax.ajax",
            success: function(data){
$('.tb_ajax').html(data);
}
});

} 

ajax_f();

$(document).on('click','.up-up',function(){ 
$(this).addClass('up-process');
var mod=$(this).attr('nm'); 

$.ajax({ 
            type: "POST",
            url: "/upmodul/up_ms.ajax",
            data: {mod:mod},
             success: function(data){
$('.up-process').removeClass('up-process');

}
});
});

$(document).on('click','.up-del',function(){ 
$(this).addClass('up-process');
var mod=$(this).attr('nm'); 

$.ajax({ 
            type: "POST",
            url: "/upmodul/del_ms.ajax",
            data: {mod:mod},
             success: function(data){
$('.up-process').removeClass('up-process');

}
});
});