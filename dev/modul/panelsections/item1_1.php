<table>
    <tr>
        <td>
<?php   foreach($arr["field"] as $v){if($v!='id'){?>
<input type='text'  class='del_<?=$this->request->p['table'].'_'.$v;?> pole_sp_2'  value="<?=$v;?>" disabled>

<?php  }}?>
   </td>
      <td>
<?php   if(isset($arr["name"])){ foreach($arr["name"] as $k=>$v){if($v!='id'){?>
<input type='text'  class='del_<?=$this->request->p['table'].'_'.$arr["field"][$k];?> pole_sp_n'  value="<?=$v;?>" >

<?php  }}}?>
   </td>
   <td>
       <? foreach($arr["format"] as $k=>$v){if($arr["field"][$k]!='id'){?>
<input type='text'  class='del_<?=$this->request->p['table'].'_'.$arr["field"][$k];?> pole_format_2'  value="<?=$v;?>" disabled>

<?php  }}?>
   </td>
   <td>
      <? foreach($arr["format_select"] as $k=>$v){$v=str_replace('|','',$v);if($arr["field"][$k]!='id'){
     if(stristr($v, ':') !== FALSE) {
       $kod=explode(':',$v)[0];
       
     }
     else{
         $kod=$v;
     }
      ?>
<select class='del_<?=$this->request->p['table'].'_'.$arr["field"][$k];?> pole_html_2' ids='ids<?=$k;?>'>
      <?foreach($arr_html_select as $val){?>
                <option value='<?=$val['kod'];?>' <? if($kod==$val['kod']){ echo 'selected';}?>><?=$val['name'];?></option>
                <?}?>
                </select>


<?php  }}?> 
   </td>
   <td>
         <? foreach($arr["format_select"] as $k=>$v){$v=str_replace('|','',$v);if($arr["field"][$k]!='id'){
     if(stristr($v, ':') !== FALSE) {
         $arg=explode(':',$v)[1];
     }
     else{
         $arg='';
     }
      ?>
      <input type='text'  class='del_<?=$this->request->p['table'].'_'.$arr["field"][$k];?> pole_arg_2'  id='ids<?=$k;?>' value="<?=$arg;?>"> 
      <?}}?>
   </td>
<td>
        <? foreach($arr["field"] as $v){if($v!='id'){?>
    <input type='button' class='del_pole_2' del='<?=$this->request->p['table'].'_'.$v;?>' value='Удалить поле'>
    
<?php  }}?> 
</td>
    </tr>
</table>
<div class='create_pole' tb='<?=$this->request->p['table'];?>'>Добавить поле</div>
<div class='dop_pole'></div>
