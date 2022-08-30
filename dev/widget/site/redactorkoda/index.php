<?php
  class Redactorkoda{
        function __construct(){
        global $core; $this->core = $core;
        $this->request = $this->core->request;
    }
    public function index($h,$w,$name,$content,$redactor,$id,$comment){
include('massred.php');
$content=str_replace("\t","",$content);
$content=str_replace("</textarea>","<\/textarea>",$content);
if($id=='0'){
?>
<style>
.cod span{
   font-family: monospace !important;    
}
.cod{    position: relative !important;   
   width: <?=$w;?> !important;   
    height: <?=$h;?>px !important;       font-family: monospace !important;   
    font-size: 17px !important;   
    padding: 0px !important;   
    overflow: scroll !important;   
    border: 0px !important;   
color:#fff !important;   
background:#09312c !important; 
}
div.cod{
   width: <?=$w;?> !important;   
    height: <?=$h;?>px !important;       font-family: monospace !important;   
    font-size: 17px !important;   
    padding: 0px !important;   
    overflow: scroll !important;   
    border: 0px !important;   
color:#fff !important;   
background:linear-gradient(to bottom, #000000 0%,#022238 100%) !important;   top: 50px !important;   
}
textarea.codi{    font-family: monospace !important;   
    font-size: 17px !important;   
    padding: 0px !important;   
    overflow: scroll !important;   
    border: 0px !important;   
    width: <?=$w;?> !important;   
    height: <?=$h;?>px !important;   
       top: calc(50px - <?=$h;?>px) !important;    
    position: relative;word-wrap: break-word !important;   
  color: rgba(88, 255, 255,0.2) !important;   word-break: break-all !important;    
    background: transparent !important;   
   margin-bottom: calc(60px - <?=$h;?>px) !important;    
}

</style>

<div class="cod" id="ii" style="word-wrap: break-word; word-break: break-all;" contenteditable>

</div>
<textarea class='codi' id='scripti' style='resize: none;'  name='<?=$name;?>'><?=$content;?></textarea>
<script>

$('.codi').scroll(function(){
var blocki = document.getElementById("scripti");
var blocki2 = document.getElementById("ii");
$('#ii').scrollTop($('.codi').scrollTop());
var iis=blocki.scrollHeight-blocki.clientHeight-20;

if(blocki.scrollTop>iis && iis>0){
blocki.scrollTop=blocki.scrollTop-20;

}
 blocki2.scrollTop=blocki.scrollTop;
  });
$(document).on('mouseover','.codi',function(){
    $('.codi').keyup();
});
$(document).on('keyup','.codi',function(e){
   
if(e.keyCode=='40'){
var inputh = document.getElementById ("scripti");
inputh.selectionStart = inputh.value.length;


}
var blocki = document.getElementById("scripti");
var blocki2 = document.getElementById("ii");

  blocki2.scrollTop=blocki.scrollTop;

var iis=blocki.scrollHeight-blocki.clientHeight-20;

if(blocki.scrollTop>iis && iis>0){
blocki.scrollTop=blocki.scrollTop-20;

}
 blocki2.scrollTop=blocki.scrollTop;
var code5=$('.codi').val();

 code5=code5.replace(/enter-redactorcoda-include/g, "enter- redactorcoda-include");

 code5=code5.replace(/&/g, "&amp;");
code5 = code5.replace(/\n/g,"enter-redactorcoda-include");

code5 = code5.replace(/ /g,"&nbsp;");
code5=code5.replace(/</g, "&lt;");
   code5=code5.replace(/>/g, "&gt;");
  
  
$('.cod').html(code5);

var code=$('.cod').html();
 
code = code.replace(/enter-redactorcoda-include/g,'<br/>');

<?php
  foreach($arrred as $kod=>$color){
$kod = str_replace(',','|',$kod);
echo "code = code.replace(/([^a-z0-9\$_])(".$kod.")([^a-z0-9\$_])/gi,'$1<span style=\"color:".$color.";\">$2</span>$3');\n";
 

}
?>

$('.cod').html(code);

   
    
 

});

</script>
<?php
  }

else{

?><style>

.cod<?=$id;?>{
   width: <?=$w;?>;
    height: <?=$h;?>;    font-family: monospace;
    font-size: 17px;
    padding: 0px;
    overflow: scroll;
color:#fff;
background:#000;
    border: 0px;
}


.codi{    font-family: monospace;
    font-size: 17px;
    padding: 0px;
    overflow: scroll;
    border: 0px;
    width: <?=$w;?>;
    height: <?=$h;?>;
    top: -310px;
    position: relative;word-wrap: break-word;
    color: rgba(255,255,255,0.5);word-break: break-all; 
    background: transparent;
    margin-bottom: -300px;
}
 .codi<?=$id;?>{    font-family: monospace;
    font-size: 17px;
    padding: 0px;
    overflow: scroll;
    border: 0px;
    width: <?=$w;?>;
    height: <?=$h;?>;
    top: -<?=$h;?>;
    position: relative;word-wrap: break-word;
    color: rgba(255,255,255,0.5);word-break: break-all; 
    background: transparent;
   
}
.codi{    font-family: monospace;
    font-size: 17px;
    padding: 0px;
    overflow: scroll;
    border: 0px;
    width: <?=$w;?>;
    height: <?=$h;?>;
    top: -<?=$h;?>;
    position: relative;word-wrap: break-word;
    color: rgba(255,255,255,0.5);word-break: break-all; 
    background: transparent;
   
}

</style>

<div class="cod<?=$id;?>" id="ii<?=$id;?>"  style="    word-wrap: break-word; word-break: break-all;" contenteditable>

</div>
<textarea class='codi<?=$id;?>' id='scripti<?=$id;?>' style='resize: none;' name='<?=$name;?>'><?=$content;?></textarea>
<script>

$('.codi<?=$id;?>').scroll(function(){
var blocki<?=$id;?> = document.getElementById("scripti<?=$id;?>");
var blocki2<?=$id;?> = document.getElementById("ii<?=$id;?>");
$('#ii<?=$id;?>').scrollTop($('.codi<?=$id;?>').scrollTop());
var iis<?=$id;?>=blocki<?=$id;?>.scrollHeight-blocki<?=$id;?>.clientHeight-20;

if(blocki<?=$id;?>.scrollTop>iis<?=$id;?> && iis<?=$id;?>>0){
blocki<?=$id;?>.scrollTop=blocki<?=$id;?>.scrollTop-20;

}
 blocki2<?=$id;?>.scrollTop=blocki<?=$id;?>.scrollTop;
  });

$(document).on('keyup','.codi<?=$id;?>',function(e){
if(e.keyCode=='40'){
var inputh<?=$id;?> = document.getElementById ("scripti<?=$id;?>");
inputh<?=$id;?>.selectionStart = inputh<?=$id;?>.value.length;


}
var blocki<?=$id;?> = document.getElementById("scripti<?=$id;?>");
var blocki2<?=$id;?> = document.getElementById("ii<?=$id;?>");

  blocki2<?=$id;?>.scrollTop=blocki<?=$id;?>.scrollTop;

var iis<?=$id;?>=blocki<?=$id;?>.scrollHeight-blocki<?=$id;?>.clientHeight-20;

if(blocki<?=$id;?>.scrollTop>iis<?=$id;?> && iis<?=$id;?>>0){
blocki<?=$id;?>.scrollTop=blocki<?=$id;?>.scrollTop-20;

}
 blocki2<?=$id;?>.scrollTop=blocki<?=$id;?>.scrollTop;
var code5<?=$id;?>=$('.codi<?=$id;?>').val();
code5<?=$id;?>=code5<?=$id;?>.replace(/enter-redactorcoda-include/g, "enter- redactorcoda-include");
 code5<?=$id;?>=code5<?=$id;?>.replace(/&/g, "&amp;");
code5<?=$id;?> = code5<?=$id;?>.replace(/\n/g,"enter-redactorcoda-include");

code5<?=$id;?> = code5<?=$id;?>.replace(/ /g,"&nbsp;");
code5<?=$id;?>=code5<?=$id;?>.replace(/</g, "&lt;");
   
$('.cod<?=$id;?>').html(code5<?=$id;?>);
var code<?=$id;?>=$('.cod<?=$id;?>').html();
 
code<?=$id;?> = code<?=$id;?>.replace(/enter-redactorcoda-include/g,'<br/>');

<?php
  foreach($arrred as $kod=>$color){
$kod = str_replace(',','|',$kod);
echo "code".$id." = code".$id.".replace(/([^a-z0-9\$_])(".$kod.")([^a-z0-9\$_])/gi,'$1<span style=\"color:".$color.";\">$2</span>$3');\n";
 

}
?>
$('.cod<?=$id;?>').html(code<?=$id;?>);

   
    
 

});


</script>
<?php
  }
        
    }
}