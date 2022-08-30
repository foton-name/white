
 $(document).on("click",".click",function(){
     
     $.ajax({
            type: "POST",
            url: "/siteajax.ajaxsite",
            data: {controller:'inform',method:'func_pub_ajax',test:'Это тестовый шаблон'},
             success: function(data){
                 alert(data);
             }
});

 });

