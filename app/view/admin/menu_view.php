<script>
    <?=$data['js_i'];?>
</script>
<style>
    <?=$data['css_i'];?>
</style>
<?php
  $table = $data['table'];
$model = $data['model'];
?>
<div class='content'>
    <div class='top front-work'>
        <div class='el-items-css dop-el-menu' ids='0' table='<?= $table; ?>' model='<?= $model; ?>'>Добавить элемент
        </div>
        <div class='form-dop el-items-css'></div>
        <div class='menu-item'>
            <?
            foreach ($data['for_menu'] as $val) {
                include($this->core->include_tpl('menu/menu_start'));
                $controller_class->recursion_menu($table, $model, $val['id']);
                include($this->core->include_tpl('menu/menu_end'));
            } ?>
        </div>
    </div>
</div>


