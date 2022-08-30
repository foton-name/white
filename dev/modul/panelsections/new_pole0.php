<div class='contener_new_pole0'>       <input type="text" class="new_name_pole new_name_pole2" placeholder="Название подраздела">
            <input type="button" class="new_section_pole" value="Добавить поле">
            <input type="button" class="del_new_pole" value="Удалить">
                  
     <div class='hrs'>Права</div>   <?

foreach($arr3 as $val){?>
 <fieldset class='filests'>
   <legend><?=$val['text'];?></legend>
<input type='checkbox' name='checks' class='checks' value='<?=$val['name'];?>'>
  </fieldset>

<?php  }?>
             <div class='pole_sp_section'>
            
        </div>
  
            </div>  