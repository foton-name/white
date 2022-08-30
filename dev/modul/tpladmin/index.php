<div class='menu_sh'>
    <input type='button' class='new_sh' value='Создать шаблон'>
    <input type='button' class='del_sh' value='Удалить шаблон'>
    <input type='button' class='ustanovit_sh' value='Установить шаблон'>
    <input type='button' class='copy_sh' value='Копировать шаблон'>
    <input type='button' class='copy_admin' value='Создать панель'>
    <input type='button' class='del_admin' value='Удалить панель'>
</div>
<div class='content_sh'>
    <? foreach ($data['mass_sh'] as $key => $val) { ?>
        <div class='item_sh'>
            <fieldset>
                <legend><?= $val[1]; ?> - <?= $key; ?></legend>
                <img src='<?= $val[0]; ?>'>
                <input type='radio' name='check' value='<?= $key; ?>'>
            </fieldset>
        </div>

    <? } ?>
    </div>
 <hr/>
    <h2>Установленные РСИ</h2>
    <div class='content_sh'>
    <? foreach ($data['arr_admin'] as $key => $val) { ?>
        <div class='item_admin'>
            <fieldset>
                <legend><?= $val[1]; ?> - <?= $key; ?></legend>
                <img src='<?= $val[0]; ?>'>
                <input type='radio' name='check' value='<?= $key; ?>'>
            </fieldset>
        </div>
    <? } ?>
    </div>
</div>