<div class='sp_table '>
<p class='table_check' tb='<?=$tb;?>'><?=$name;?></p>
<?php

  foreach($arr as $key=>$val){?>
 <fieldset>
   <legend><?=$arr[$key]['text'];?></legend>
<input type='checkbox' value="<?=$arr[$key]['name'];?>">
  </fieldset>

<?php  }?>


</div>