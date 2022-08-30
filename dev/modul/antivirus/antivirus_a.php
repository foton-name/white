<?php

  class Antivirus_a extends Antivirus_c
{
    public function vivod_virus()
    {
        if (isset($this->request->p['status']) && $this->request->p['status'] != '') {
            $res = $this->search_dir_virus($this->request->p['status']);

            $i = 0;
            $res_m = explode(',', $res);
            if (count($res_m) > 0) {
                foreach ($res_m as $r) {
                    if ($r != '') {
                        $i++;
                        $r1 = explode('--', $r);
                        $classv = str_replace('/', '', $r1[0]);
                        $classv = str_replace('.', '', $classv);
                        echo '<div class="' . $classv . '"><form action="" method="post" class="' . $classv . '-form"><input type="button" class="' . $classv . '-but open-save" attr="' . $classv . '" name="files" value="' . $r1[0] . '"></form><span>"' . $r1[1] . '"</span><i class="crc" attr="' . $r1[0] . '">В карантин</i><i class="delc" attr="' . $r1[0] . '">Удалить</i></div>';
                    }
                }
                if ($i == 0) {
                    echo 'Вирусов не найдено';
                }
            } else {
                echo 'Вирусов не найдено';
            }
        }
    }

    public function renames()
    {
        if (isset($this->request->p['file'])) {

            $new_f = substr($this->request->p['file'], 0, -1);
            rename($_SERVER['DOCUMENT_ROOT'] . $this->request->p['file'], $_SERVER['DOCUMENT_ROOT'] . $new_f);
            echo $new_f;
        }
    }

    public function del()
    {
        if (isset($this->request->p['file'])) {

            unlink($_SERVER['DOCUMENT_ROOT'] . $this->request->p['file']);

        }
    }

    public function files()
    {
        if (isset($this->request->p['files'])) {

            if (isset($this->request->p['script'])) {
                $this->request->p['script'] = str_replace('<\/textarea>', '</textarea>', $this->request->p['script']);
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . $this->request->p['files'], $this->request->p['script']);

            }
            $text = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $this->request->p['files']);
            $text = str_replace('</textarea>', '<\/textarea>', $text);
            ?><p class="close_new_okno"></p>
            <form action="" method="post" class="save-ajax-form">
                <input type="button" class="save save-ajax" value="Сохранить">
                <input type="hidden" name="files" value="<?= $this->request->p['files']; ?>">
                <textarea name="script" class='script'>
        <?= $text; ?>
    </textarea>
            </form>
            <?
        }
    }

}