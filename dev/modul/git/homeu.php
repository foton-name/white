<input type='hidden' class='userl' value='<?= $loginu; ?>'>
<div class='top front-work'>
    <div class='files'>
        <table>
            <tr>
                <td class='filessp'>
                    <? $arr = $this->spisokfile($_SERVER['DOCUMENT_ROOT'] . '/.gitf/' . $loginu . "/work/");
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
