$.fn.hasAttr = function(name) {
   return this.attr(name) !== undefined;
};
$(document).on('mouseover','input[type="submit"],input[type="button"]',function() {
    $('.taxonomy_html').each(function(i0,elem0) {
 var taxonomy_html=$(elem0).html();   
var val_taxonomy='';
var table=$(elem0).find('.tx').attr('attr-tb');
$(elem0).find('.tx').each(function(i,elem) {
    if($(elem).hasAttr('ids')){
         var ids=$(elem).attr('ids');
    }
    else{
        var ids=this.options[this.selectedIndex].getAttribute('ids');
    }
    var val=$(elem).val();
    val_taxonomy+='%%%'+ids+':::'+val;
 });
 val_taxonomy+='|||'+table;
var serialize=val_taxonomy;
    $.ajax({
            type: "POST",
            url: "/taxonomy/serialize.tpl",
            async:false,
            data: {text:serialize},
             success: function(data){
                $(elem0).next('.hid_taxonomy').val(data);
                console.log($(elem0).next('.hid_taxonomy').val());        
}        });
    });
});

$(document).on('change','select.tx',function() {
var val=$(this).val();
var name=$(this).attr('attr_name');
var table = $(this).attr('attr-tb');
var id=this.options[this.selectedIndex].getAttribute('ids');
var class_div=$(this).attr('class');
var n=$(this).attr('number');
class_div=class_div.replace(" ",".");

$(this).find('option').each(function(i,elem2) {
    if($(elem2).val()!=val){
       $(elem2).attr('selected',false);
    }
    
});
this.options[this.selectedIndex].setAttribute('selected','selected');
var res=$(this);
    $.ajax({
            type: "POST",
            url: "/taxonomy/taxonomy.tpl",
            data: {val:val,table:table,name:name,section:id,n:n},
            async:false,
             success: function(data){
                 if(res.siblings('div').is('.taxs.taxonomy_'+n)){
                    res.siblings('.taxs.taxonomy_'+n).html(data);
                 }
                 else{
                  res.siblings('.taxonomy_'+n).html(data);
                }
                console.log(res.siblings('.taxonomy_'+n).html()+'---');
}        });


});

$(document).on('change','input[type="radio"].tx',function() {
var val=$(this).val();
$(this).attr('value',$(this).val());
var name=$(this).attr('attr_name');
var table = $(this).attr('attr-tb');
var id=$(this).attr('ids');
var class_div=$(this).attr('class');
var n=$(this).attr('number');
if($(this).prop('checked')===true){

var res=$(this);
  $.ajax({
            type: "POST",
            url: "/taxonomy/taxonomy.tpl",
            data: {val:val,table:table,name:name,section:id},
            async:false,
             success: function(data){
if(res.siblings('div').is('.taxs.taxonomy_'+n)){
                    res.siblings('.taxs.taxonomy_'+n).html(data);
                 }
                 else{
                  res.siblings('.taxonomy_'+n).html(data);
                }

}        });
}
else{
  if(res.siblings('div').is('.taxs.taxonomy_'+n)){
                    res.siblings('.taxs.taxonomy_'+n).html('');
                 }
                 else{
                  res.siblings('.taxonomy_'+n).html('');
                }  
}
});

$(document).on('mouseout','input.tx',function() {
    $(this).attr('value',$(this).val());
});

$(document).on('click','input[type="radio"].tx,input[type="checkbox"].tx',function() {
    if($(this).attr("checked")){
          $(this).attr('checked',false);
    }
    else{
        $(this).attr('checked',true);
    }
    
});

$(document).on('change','input[type="checkbox"].tx',function() {
var val=$(this).val();
$(this).attr('value',$(this).val());
var name=$(this).attr('attr_name');
var table = $(this).attr('attr-tb');
var id=$(this).attr('ids');
var n=$(this).attr('number');
var class_div=$(this).attr('class');
var res=$(this);
if($(this).prop('checked')===true){
  $.ajax({
            type: "POST",
            url: "/taxonomy/taxonomy.tpl",
            data: {val:val,table:table,name:name,section:id},
            async:false,
             success: function(data){
if(res.siblings('div').is('.taxs.taxonomy_'+n)){
                   res.siblings('.taxs.taxonomy_'+n).html(data);
                 }
                 else{
                  res.siblings('.taxonomy_'+n).html(data);
                }

}        });

}
else{
  if(res.siblings('div').is('.taxs.taxonomy_'+n)){
                    res.siblings('.taxs.taxonomy_'+n).html('');
                 }
                 else{
                res.siblings('.taxonomy_'+n).html('');
                }
}
});

