<input type='text' class='tb_name' placeholder='Название раздела'><input type='button' class='create_check' value='Создать права'>

<?php    foreach($arr2 as $k=>$v){?>
<div class='sp_table '>
<p class='table_check' tb='<?=$k;?>'><?=$arr22[$k];?></p>
<?php

  foreach($arr3[$k] as $key=>$val){?>
 <fieldset>
   <legend><?=$key;?></legend>
<input type='checkbox' <?if($val=='yes'){ echo 'checked';}?> value='<?=$arr_role[$key];?>'>
  </fieldset>

<?php  }?>



</div>
<?php  }?>
<input type='button' class='step_4 steps' value='Далее'>