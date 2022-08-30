<input type='hidden' class='userl' value='<?= $loginu; ?>'>
<div class='top front-work'>
    <div class='files'>
        <div class='vetki'>Ветки <?= $loginu; ?></div>
        <div class='divv'>
            <input type='text' class='new-vetka' placeholder='Имя ветки (лат)'><input type='button' class='create-v'
                                                                                      value='Создать'>
            <hr>
            <div class='vu2'>
                <div class='vu' path='<?= $GLOBALS['foton_setting']['path']; ?>/.gitf/<?= $loginu; ?>/work' idsu='<?= $loginu; ?>'>
                    Домой
                </div>
            </div>
            <? $arrt = $this->spisokfile($_SERVER['DOCUMENT_ROOT'] . '/.gitf/' . $loginu . "/release/");
            for ($i = 0; $i < count($arrt); $i++) {
                ?>
                <div class='vu2'>
                    <div class='vu' path='<?= $arrt[$i]['path']; ?>'
                         idsu='<?= $loginu; ?>'><?= $arrt[$i]['name']; ?></div>
                    <span class='delv' path='<?= $arrt[$i]['path']; ?>'>Удалить</span><span class='selectv'
                                                                                            path='<?= $arrt[$i]['name']; ?>'>Выбрать</span>
                </div>
            <? } ?>
        </div>
        <table>
            <tr>
                <td class='filessp'>
                    <? $arr = $this->spisokfile($this->request->p['path']);
                    for ($i = 0; $i < count($arr); $i++) {
                        $rand = rand(0, 500);
                        if (stristr($arr[$i]['name'], '.') !== FALSE) { ?>
                            <p class='file<?= $i . $rand; ?>'><span class='copy-f' ids='<?= $i . $rand; ?>'
                                                                    path='<?= $arr[$i]['path']; ?>'></span><span
                                        class='name-f names-f'
                                        path='<?= $arr[$i]['path']; ?>'><?= $arr[$i]['name']; ?></span></p>
                        <? } else { ?>
                            <p class='file<?= $i . $rand; ?>'><span class='copy-f' ids='<?= $i . $rand; ?>'
                                                                    path='<?= $arr[$i]['path']; ?>'></span><span
                                        class='name-f dir-f' ids='<?= $i . $rand; ?>'
                                        path='<?= $arr[$i]['path']; ?>'><?= $arr[$i]['name']; ?></span></p>
                            <div class='dir<?= $i . $rand; ?> dirt'></div>
                        <? }
                    } ?>
                </td>
                <td class='td-2'><span class='diff-del'>X</span>
                    <div class="file-diff" idsu="<?= $this->request->p['idsu']; ?>"></div>
                </td>
                <td class='td-f'>
                    <div class='head-f'>
                        <div class='plus-insert'></div>
                        <span class='plus-f'></span></div>
                    <div class='pole-f'>


                    </div>

                </td>


            </tr>
        </table>
    </div>
</div>
