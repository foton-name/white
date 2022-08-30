<div class='content'>
    <div class='files'>

        <table class='inst'>
            <tr>
                <td>Установить</td>
            </tr>
            <?
            if (isset($controller_class->vivodm()['inst'])) {
                foreach ($controller_class->vivodm()['inst'] as $k => $file_m) {
                    ?>
                    <tr>
                        <td><span class='nm'><?= $controller_class->vivodm()['name'][$file_m]; ?></span> <span
                                    class="up-text" nm="<?= $file_m; ?>">
</span></td>
                    </tr>
                <? }
            } ?>
        </table>


        <table>
            <tr>
                <td>Удалить</td>
            </tr><?
            foreach ($controller_class->vivodm()['uinst'] as $k => $file_m) {
                ?>
                <tr>
                    <td><span class='nm'><?= $controller_class->vivodm()['name'][$file_m]; ?></span> <span
                                class="up-del" nm="<?= $file_m; ?>">
</span></td>
                </tr>
            <? } ?>
        </table>
        <table class='tb_ajax'>
        </table>
    </div>
</div>