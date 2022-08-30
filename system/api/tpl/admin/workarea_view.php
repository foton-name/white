<link rel="stylesheet" type="text/css" href="<?=$data['background_user'];?>" />

<div class='head_admin'>
        <div class='center'><a href='/workarea/'><img src="/system/admin-inc/img/logo.png" class="logo-fv">Interface CRISTAL</a></div>
 
<div class='panel_all'>
<div class='input-wrapper' data-title='Удалить логи'>
     <div class='delete_log'>
        
    </div></div>
<div class='input-wrapper' data-title='Очистить кеш'>
     <div class='update'>
        
    </div></div>
<?php   if($this->core->chmod_id([1,1])){?>
<div class='input-wrapper' data-title='Обновить ключи'>
     <div class='key'>
        
    </div></div>
<?php  }?>
    <div class='input-wrapper' data-title='Настройки панели'>
     <div class='setting'>
        
    </div></div>
</div>



<?php   if($this->core->chmod_id([1,1])){?>
  <div class="search_div">
      <div class='over-search'>
<input type="text" class="search_find" placeholder="Для поиска нажмите Enter" autocomplete="off"></div>
<div class='div_search'>
    
</div>
</div>
<?php  }?>


<div class='background_up'>

<input type='text' class='background_update' placeholder='Background Url'>
<input type='button' class='up_back' value='SAVE'>
<fieldset>
   <legend>Цвет панели</legend>
<input type="color" class="colorfon">
</fieldset>
<fieldset>
   <legend>Цвет текста</legend>
<input type="color" class="colortext">
</fieldset>
</div>


<div class='login_admin'><?=$_SESSION['login'];?></div>
 <form action='/interface/' method='post' class='exits'>
    <input type='submit' name='key' value='Выход' id='exitst'>
</form>
</div>

<div class='content'>
  
<div class='menu_rs'></div>
<div class='menux'>
<div class='after_menu'>

<div class='clickme div2'>
    

<p class='h3' >Модули</p>
<div class='contener'>
<?php
  if(isset($data['glob_menu'])){
foreach($data['glob_menu'] as $key=>$val){
?>
<a href='<?=$key;?>' target="CONTENT"><?=$val;?></a><br>
<?php
  } }
?>

</div>
</div>
<div class='clickme div2'>
<p class='h3' >Интерфейсы</p>
<div class='contener'>   
<a href='/menu/' target="CONTENT">Меню</a><br>
<a href='/taxonomy/' target="CONTENT">Списки</a><br>
</div>
</div>
<?php
  foreach($data['files'] as $key=>$val){
?>
<div class='clickme div2'>
<p class='h3' style='<?=$controller_class->cssmenu($key);?>'><?=$val;?></p>
<div class='contener'>

<div class='inform_f'>
</div>
    <?
foreach($controller_class->list_w($key) as $arr){
foreach($arr as $key2=>$val2){
?>
<a href='/<?=$GLOBALS['foton_setting']['interface'];?>/<?=$key;?>/<?=$key2;?>' target="CONTENT"><?=$val2;?></a>
<?php
  }
}
?>

</div>
</div>
<?php
  }
?>
<?php   if($this->core->chmod_id([1,2])){?>
<div class='clickme div2'>
<p class='h3'>Файлы сайта</p>
<div class='contener'>
<?php
  foreach($data['global_menu'] as $key=>$val){
?>
<a href='<?=$key;?>' target="CONTENT"><?=$val;?></a><br>
<?php
  } 
?>
</div>
</div>
<?php  }?>
</div>
</div>
<?php   if($this->core->chmod_id([1,1])){?>
<div class="menu" >
   <p class='names_menu'>Панель программиста</p>
<span class="updatemenu">

<div ><p id="overhiddenright" ></p>


<select id="patch1" name="oop" patchs="controller" dir='0'><option value='---'>CONTROLLER</option>
<?php   foreach($this->core->list_mvc('controller',$GLOBALS['foton_setting']['admindir']) as $k=>$v){?>
<option value='<?=$k;?>'><?=$v;?></option>
<?php  }?>
<option disabled>Контроллеры сайта</option>
<?php   foreach($this->core->list_mvc('controller',$GLOBALS['foton_setting']['sitedir']) as $k=>$v){?>
<option value='<?=$k;?>'><?=$v;?></option>
<?php  }?>
</select>

<select id="patch2" name="oop2" patchs="model" dir='0'><option value='---'>MODEL</option>
<?php   foreach($this->core->list_mvc('model',$GLOBALS['foton_setting']['admindir']) as $k=>$v){?>
<option value='<?=$k;?>'><?=$v;?></option>
<?php  }?>
<option disabled>Модели сайта</option>
<?php   foreach($this->core->list_mvc('model',$GLOBALS['foton_setting']['sitedir']) as $k=>$v){?>
<option value='<?=$k;?>'><?=$v;?></option>
<?php  }?></select>

<input type="button" value="NEW CONTR" id="newkod" >
<input type="button" value="new \Foton\ModEL" id="newkodagr" >
</div>
<div >

 <input type="text" class="searchsh" placeholder="Название страницы">
 <select id="patch3" name="shablon" patchs="view" dir="<?=$GLOBALS['foton_setting']['sitedir'];?>"><option value='---'>Страницы сайта</option>
<?php   foreach($this->core->list_mvc('view',$GLOBALS['foton_setting']['sitedir']) as $k=>$v){?>
<option value='<?=$k;?>'><?=$v;?></option>
<?php  }?></select>

<select id="patch4" name="shablon2" patchs='view' dir="<?=$GLOBALS['foton_setting']['admindir'];?>"><option value='---'>Страницы админ. панели</option>
<?php   foreach($this->core->list_mvc('view',$GLOBALS['foton_setting']['admindir']) as $k=>$v){?>
<option value='<?=$k;?>'><?=$v;?></option>
<?php  }?>
</select>


<input type="button" value="NEW VIEW" id="newkod2" >

</div>
</span>
</div>
<?php  }?>


<input type='hidden' class='oknogogo' value='0'>

<div class="okno-glob input-wrapper" data-title='Перетаскивание при двойном нажатии' >



<form action="" method="post" class="kodes"><p class="x"></p><span class="kod55"> </span>

<span class="kod5"><p> <input type="text" name="scriptname" id="scriptname" disabled><input type="hidden" name="scriptdir" id="scriptdir"><input type="hidden" name="scriptpath" id="scriptpath" > </p></span><span id="form"><textarea id="scripti-glob" name="script" >





</textarea></span>
<input type="button" value="УДАЛИТЬ" id="del_popap" class='but_popap'>
<input type="button" value="SAVE" id="save57" class='but_popap'>
<input type="button" value="API" id="apiup" class='but_popap'>
<div class='upapi'></div>
</form>
</div>





<div class='oknologi'>
<input type='text' class='search' placeholder='Поиск...'><input type='number' class='chislo' placeholder='число' value='<?=date("j");?>'><select class='mes'>
    <?foreach($data['month_sess'] as $key=>$val){?>
<option value="<?=$key;?> ><?=$val;?></option>
<?php  }?></select>
<input type='number' class='god' placeholder='год' value='<?=date("Y");?>'>
<div class='log1'></div><div class='log2'>

<textarea class='textlog' id="textarea-example"></textarea>

</div>
<span class='texthidlogi'></span>
</div>
<div class="reserve">
<span class="prev"> ЛОГИ</span><span class="next" data-clipboard-target="#textarea-example">КОПИРОВАТЬ </span>
</div>





<div class='work_frame frame_foton'>
<iframe src="/inform/" name="CONTENT" id="iframe" class='iframes'></iframe>

</div>

<textarea class="text1 text1_inc" placeholder="1 буфер стрелка вверх вниз очитска F7"><?if(isset($_SESSION['text1'])){ echo $_SESSION['text1'];}?></textarea>
<textarea class="text2 text1_inc" placeholder="2 буфер стрелка вправо влево очитска F7"><?if(isset($_SESSION['text2'])){ echo $_SESSION['text2'];}?></textarea>




