<div class='contener_new_pole'>       <input type="text" class="new_name_pole" placeholder="Название поля латинские буквы">
<input type="text" class="new_name_pole_rus" placeholder="Название поля">
            <select class='format_pole_new'>
                  <option value='text'>Текст</option>
                <option value='int'>Число</option>
              
            </select>
              <select class='html_pole_new'>
                  
                  <?foreach($arr as $val){?>
                <option value='<?=$val['kod'];?>'><?=$val['name'];?></option>
                <?}?>
            </select>
            <input type="text" class="arg_pole_new" placeholder="аргументы функции">
            <input type="button" class="del_new_pole2" value="Удалить">
            </div>  