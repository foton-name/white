<script>
    <?=$data['js_i'];?>
</script>
<style>
    <?=$data['css_i'];?>
</style>
<?php
  $table = $data['table'];
  $model = 'taxonomy';
?>
<div class='content'>
    <?
    if ($data['no_section'] !== false) {
        ?>
        <form action='' method='post' id="i_f_filtr">
            <input type='text' name='table_create_t'>
            <input type='submit' value='Создать таблицу'>
        </form>
        <? foreach ($data['no_section'] as $val) {
            ?>
            <a href='/taxonomy/<?= $val['tables']; ?>' class='taxonomy_a '
               target="_blank"><?= $val['name']; ?></a>
            <form action='' method='post' class='del_taxonomy'>
                <input type='hidden' name='table_del_t' value='<?= $val['id']; ?>'>
                <input type='submit' value='Удалить'>
            </form>
        <?
        }
        exit();
    }
    ?>
    <div class='top front-work'>
        <div class='el-items-css dop-el-menu' ids='0' table='<?= $table; ?>' 'model'='taxonomy'>Добавить
            элемент
        </div>
        <div class='form-dop el-items-css'></div>
        <div class='menu-item'>
            <?
            foreach ($data['for_t'] as $val) {
                include($this->core->include_tpl('taxonomy/taxonomy_start'));
                $controller_class->recursion_t($table, 'taxonomy', $val['id']);
                include($this->core->include_tpl('taxonomy/taxonomy_end'));
            } ?>
        </div>
    </div>
</div>


