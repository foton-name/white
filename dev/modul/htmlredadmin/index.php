<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<script type="text/javascript">
    $(function () {

        $('.dragElement').draggable({
            axis: "x"
        }).filter('#dragV').draggable("option", "axis", "y");

    });
</script>
<div class='okno_znak'>
    <p class='close_znak_okno'><img src="/dev/modul/htmlred/css/close.svg"></p>
    <hr>
    <div class='okno_znak2'>
    </div>
</div>

<div class='okno_attr'>
    <p class='close_attr_okno'><img src="/dev/modul/htmlred/css/close.svg"></p>
    <hr>
    <div class='okno-attr'></div>
    <input type="button" class="save_attr_red" value="Сохранить">
</div>
<div class="color-open">
    <p class='close_new_okno'><img src="/dev/modul/htmlred/css/close.svg"></p>
    <fieldset>
        <legend>Красный</legend>
        <input type="range" class='color_rgb color_r' min="0" max="255" step="1" value="0">
    </fieldset>
    <fieldset>
        <legend>Зеленый</legend>
        <input type="range" class='color_rgb color_g' min="0" max="255" step="1" value="0">
    </fieldset>
    <fieldset>
        <legend>Синий</legend>
        <input type="range" class='color_rgb color_b' min="0" max="255" step="1" value="0">
    </fieldset>
    <fieldset>
        <legend>Прозрачность</legend>
        <input type="range" class='color_rgb color_p' min="0" max="1" step="0.1" value="1">
    </fieldset>
    <input type='hidden' class='color_type'>
</div>
<div class='red_kod_area'>
    <p class="x"><img src="/dev/modul/htmlred/css/close.svg"></p>
    <textarea class='red_kod_text'>
            
        </textarea>
    <input type='button' class='save_red_kod' value='Сохранить'>
</div>
<div class='console_step'></div>
<div class="position_x">
    <div class="fa-chevron-left left_x "></div>
    <div class="fa-chevron-right right_x "></div>
    <div class="fa-chevron-up up_x"></div>
    <div class="fa-chevron-down down_x"></div>
</div>
<div class='top front-work'>
    <div class='htmlred5'>

        <div class='heads'>
            <div class='zoom1'>+</div>
            <div class='zoom2'>-</div>
            <div class='new-theme  field_setting' theme='white' attr-data="Сменить тему"></div>
            <div class='copy_css field_setting' attr-data="Копировать css"></div>
            <div class='insert_simv field_setting' attr-data="Вставить символ"></div>
            <div class='attrbut_red field_setting' attr-data="Атрибуты"></div>
            <div class='kod_red field_setting' attr-data="Просмотреть код"></div>
            <div class='copy field_setting' attr-data="Копировать"></div>
            <div class='copy2 field_setting' attr-data="Копировать рядом"></div>
            <div class='dels field_setting' attr-data="Удалить"></div>
            <div class='copy3 field_setting' attr-data="Очистить"></div>            
            <div class='obj_up field_setting' attr-data="Переместить вверх"></div>
            <div class='obj_down field_setting' attr-data="Переместить вниз"></div>
            <div class='vz field_setting' attr-data="Вырезать"></div>
            <div class='vz2 field_setting' attr-data="Вставить"></div>
            <div class='next_step field_setting' attr-data="Следующий шаг"></div>
            <div class='prev_step field_setting' attr-data="Вернутся назад"></div>
            <div class='clear_step field_setting' attr-data="Очистить историю"></div>
            <input type="text" class="selector_class" placeholder="Class">

        </div>
        <div class='panel1'>

            <div class='table_site'></div>

            <div class='panel288'></div>
            <div class='selectlists'></div>
            <div class='div'></div>
            <div class='sh'>
                <select class='shs'>
                    <option value='---'>Выберите шаблон</option>
                    <?
                    foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlred/sh/" . $GLOBALS['foton_setting']['admindir'] . "/*") as $filename) {
                        $files = basename($filename);
                        $files = str_replace('.html', '', $files);
                        echo '<option>' . $files . '</option>';
                    }
                    ?>
                </select>
                <div class='del-sh' id="big" ondragenter="return dragEnter(event)" ondrop="return dragDrop(event)"
                     ondragover="return dragOver(event)"></div>
                <div class='sozdhid' style='display:none;'></div>
                <input type='text' class='sh555' placeholder='Название шаблона'>
                <div class='plus2'>+</div>


            </div>
            <div class='sozd'></div>
            <input type='text' class='photo' placeholder='Ссылка на фото'>
            <div class='plus'>+</div>

            <input type="file" class="upfotos" multiple="true">
            <input type='button' class='upfoto' path='/dev/modul/htmlred/filest/<?= $GLOBALS['foton_setting']['admindir']; ?>/'
                   path2='<?= $GLOBALS['foton_setting']['path']; ?>' value='Загрузить'>
            <div class='pthoto-block'>
                <?
                foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlred/filest/" . $GLOBALS['foton_setting']['admindir'] . "/*") as $filename) {
                    echo '<img src="/dev/modul/htmlred/filest/' . $GLOBALS['foton_setting']['admindir'] . '/' . basename($filename) . '" style="width:100%;" alt="Удаление при двойном нажатии" title="Удаление при двойном нажатии">';
                }
                ?>


            </div>


        </div>
        <div class='fonts'></div>
        <div class='workarea' id="big" contenteditable="true" ondragenter="return dragEnter(event)"
             ondrop="return dragDrop(event)" ondragover="return dragOver(event)">
            <div class='body'>

            </div>
        </div>

        <div class='panel2'>

            <div class='lines'>
                <div class='vkl1 checks'>Элемент</div>
                <div class='vkl2'>Страница</div>
            </div>
            <select class='site_str'>
                <option value='---'>Выберите страницу</option>
                <? foreach ($data['nameshstr'] as $k => $v) { ?>
                    <option value='<?= $k; ?>'><?= $v; ?></option>
                <? } ?>

            </select>
            <div class="sp-str"></div>
            <input type="text" class="adress" placeholder="Название страницы">
            <p class="p-rem"><input type="checkbox" name="rem" class="rem"> Перевести в rem</p>
            <input type='button' class='saves' value='создать/сохранить'>
            <input type='button' class='delete_str' value='удалить'>

            <div class='panel22'>
            </div>
            <div class='panel23'>
            </div>
        </div>


        <div class='overokno'><p class='close'></p>
            <div class='okno beg'>
                <div class="listv">
                    <div class="dragElement lefts ui-draggable ui-draggable-handle"></div>
                </div>
            </div>
        </div>


        <div class='overoknobox'><p class='close'></p>
            <div class='oknobox beg'>
                <div class="listv">
                    <div class="dragElement leftsl ui-draggable ui-draggable-handle"></div>
                </div>
                <div class="listv">
                    <div class="dragElement leftsr ui-draggable ui-draggable-handle"></div>
                </div>
                <div class="listv">
                    <div class="dragElement leftst ui-draggable ui-draggable-handle"></div>
                </div>
                <div class="listv">
                    <div class="dragElement leftsm ui-draggable ui-draggable-handle"></div>
                </div>
                <input type="color" class="colorbox">
            </div>
        </div>
    </div>
</div>
