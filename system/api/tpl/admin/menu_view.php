
<link rel="stylesheet" type="text/css" href="/face/css/menu.css" />
<div class='top front-work'>
  <form action='/interface/menu/menu/' method='post'>
<div class='el-items dop-el-menu'>Добавить элемент</div>
<div class='form-dop'></div>
<div class='menu-item'>
<?php  foreach($this->core->tablessortw('menu',"sort",'ASC','1',"razdel","0") as $val){
    ?>
<div class='el-items el-it<?=$val['id'];?>' ids='<?=$val['id'];?>'><i><?=$val['name'];?></i><span class='del-menu' ids='<?=$val['id'];?>'></span><span class='red-menu' ids='<?=$val['id'];?>'></span><span class='raskr-menu' ids='<?=$val['id'];?>'></span><span class='plus-menu' ids='<?=$val['id'];?>'></span><div class='form-sp<?=$val['id'];?>'></div>
<div class='menu-item menuid<?=$val['id'];?>'><? $controller_class->regurs_razdel('menu',$val['id']);?>
</div>
</div>
    
    
<?php
  }

?>
</div></div>
</form>