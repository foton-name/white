<input type='text' class='razdel_add' placeholder='Название раздела'><input type='button' class='create_table'
                                                                            value='Создать раздел'>

<?php   foreach ($arr as $k => $v) { ?>
    <div class='sp_table deltb_<?= $k; ?>'>
        <input type="text" class="table" value="<?= $k; ?>">
        <input type="text" class="table_n" value="<?= $v; ?>">
        <input type="button" class="pole" table="<?= $k; ?>" value="Поля">
        <input type="button" class="del_tb" table="<?= $k; ?>" value="Удалить">
        <div class="table_<?= $k; ?> tablex"></div>
    </div>
<?php   } ?>
<input type='button' class='step_3 steps' value='Далее'>