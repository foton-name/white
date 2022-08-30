<?php
    $_SESSION['del_pole']='';
  $_SESSION['del_table']='';
  $_SESSION['res_interface_sp']='';
  $_SESSION['res_interface']='';
  $_SESSION['res_names']='';
  $_SESSION['table_check']='';
  ?>
  <div class='content'>
<p class='red'>Внимание, первое поле обязательно должно иметь формат текст для отображения в списке</p>
<div class='sections_panel'>
    <div class='head_sections'>
        <div class='activ_section i1'>Разделы</div>
        <div class='i2'>Таблицы раздела</div>
        <div class='i3'>Права</div>
        <div class='i4'>Компиляция</div>
    </div>
    <div class='content_sections'>
        <input type='button' class='create_section' value='Создать раздел'>
        <div class='section_all'>
            <?foreach($data['select_sections'] as $key=>$val){?>
            <div class='section' href='<?=$key;?>'><?=$val;?></div>
            <div class='del_r' razdel='<?=$key;?>'>Удалить</div>
            <?}?>
            
        </div>
    </div>
</div>
<div class='okno_new_section'>
    <p class="close_new_okno"></p>
    <div class='content_new_section'>
        <div class='head_section_new'>
            <input type='text' class='new_name' placeholder='Название раздела'>
            <input type='text' class='new_translit' placeholder='Название файла'>
            <input type='button' class='new_section_pole0' value='Добавить таблицу'>
            
        </div>
        <div class='pole_sp_section0'>
            
        </div>
        <input type='button' class='create_razdel' value='Создать раздел'>
    </div>
</div>
</div>