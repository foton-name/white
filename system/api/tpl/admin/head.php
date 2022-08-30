<?php
  if(empty($_SESSION['login'])){
     exit();
}

else{
    
}
?>
<?php   if($this->router->pathview_to()!='workarea'){?>
<p class='timest2'>time: <?=$glob->timesc();?> s.</p>
<?php  }?>



