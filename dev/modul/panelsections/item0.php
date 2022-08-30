     <input type='button' class='create_section' value='Создать раздел'>
        <div class='section_all'>
            <?foreach($arr as $key=>$val){?>
            <div class='section' href='<?=$key;?>'><?=$val;?></div>
            <div class='del_r' razdel='<?=$key;?>'>Удалить</div>
            <?}?>
            
        </div>