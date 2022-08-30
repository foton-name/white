
$(document).on('click','.crc',function() { 
   
      var file=$(this).attr('attr');
      var classv=file.replace(/\/{1,}/g,'');
      classv=classv.replace('.','');
        $.ajax({ 
            type: "POST",
            url: "/antivirus/renames.ajax", 
            data: {file:file},
             success: function(data){
                
                 $('.'+classv+'-but').val(data);
                 $('.'+classv+'-but').attr('value',data);
             }
        });
      
  });
  
  
$(document).on('click','.open-save',function() { 
var attr = $(this).val();

        $.ajax({ 
            type: "POST",
            url: "/antivirus/files.ajax", 
            data: {files:attr},
             success: function(data){
                $('.okno-w').html(data);
                $('.okno-w').show(300);
             }
        });
      
  });
  
$(document).on('click','.save-ajax',function() { 
var datas=$('.save-ajax-form').serialize();
        $.ajax({ 
            type: "POST",
            url: "/antivirus/files.ajax", 
            data: datas,
             success: function(data){
                $('.okno-w').html(data);
               
             }
        });
      
  });
    
      $(document).on('click','.delc',function() { 
           var key=$('.key').val();
      var file=$(this).attr('attr');
    var classv=file.replace(/\/{1,}/g,'');
    classv=classv.replace('.','');
        $.ajax({ 
            type: "POST",
            url: "/antivirus/del.ajax", 
            data: {file:file},
             success: function(data){
                 $('.'+classv).remove();
             }
        });
      
  });
  
  
$(document).on('click','.close_new_okno',function() { 
     $('.okno-w').hide(300);
    
});


  $(document).on('click','.button-inc',function() { 

      $('.uploads_back').show(200);
        var check=[];
                      $('.check:checked').each(function(i,elem) {
                          check[i]=$(elem).val();
                      });
check=check.join(',');

      $.ajax({ 
            type: "POST",
            url: "/antivirus/vivod_virus.ajax", 
            data: {status:check},
             success: function(data){
    $('.resfile').html(data);
  $('.uploads_back').hide(200);
    }        });
    });  