<p class="ph2">Ветки пользователя <?= $loginu; ?></p>

<?php   $arr = $this->spisokfile($_SERVER['DOCUMENT_ROOT'] . '/.gitf/' . $loginu . "/release/");
for ($i = 0; $i < count($arr); $i++) {
    if (file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.gitf/' . $loginu . "/branch.php") == $arr[$i]['name']) {
        $css = 'background: #383a4c;color: #fff;';
    } else {
        $css = '';
    }
    ?>
    <div class='vu2' style='<?= $css; ?>'>
        <div class='vu' path='<?= $arr[$i]['path']; ?>' idsu='<?= $loginu; ?>'><?= $arr[$i]['name']; ?></div>
        <span class='delv' path='<?= $arr[$i]['path']; ?>'>Удалить</span></div>
<?php   } ?>