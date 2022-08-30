<?php

  class Tplphp_a extends Tplphp_c
{
    public function obr_php_shablon()
    {
        if (isset($this->request->p['api']) && isset($this->request->p['copy'])) {
            mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['copy'], 0777);

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['api'] . '/*') as $filename) {

                $file = basename($filename);

                $filenames = $GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['api'] . '/' . $file;
                $newfile = $GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['copy'] . '/' . $file;
                if (!copy($filenames, $newfile)) {
                    echo "не удалось скопировать $file...\n";
                }
            }
            echo 'Функция скопирована';
        } else if (isset($this->request->p['api'])) {
            ?><p>X</p>
            <form action='' method='post'>
                <?
                if (file_exists($this->core->git($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['api'] . '/perem.txt'))) {
                    $filevar = file_get_contents($this->core->git($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['api'] . '/perem.txt'));
                    $filevar2i = str_replace('$', '', $filevar);
                } else {
                    $filevar = '';
                    $filevar2i = '';
                }
                $ret = array();
                $its = 0;

                echo "<div class='default2'> Стандартный выбор</div><input type='text' name='var' id='var2' placeholder='переменные' value='" . $filevar2i . "'>";
                echo "<input type='text' name='searchbaza' id='searchbaza' placeholder='поиск таблицы' >";
                echo "<select  id='table2' ><option value='new-vars'>Переменная</option>";
                for ($it = 0; $it < count($this->core->table_listdesc()['id']); $it++) {
                    ?>

                    <option id='<?= $this->core->table_listdesc()['id'][$it]; ?>'
                            desc-id='<?= $this->core->table_listdesc()['desc'][$it]; ?>'><?= $this->core->table_listdesc()['id'][$it]; ?></option>

                    <?


                }
                echo "</select><br><input type='hidden' class='copyfs' value='" . $this->request->p['api'] . "'><input type='text' class='copyr' placeholder='Новая Функция'><input type='button' class='copyf' value='Копировать'>";

                $iu2 = 0;
                foreach (glob($GLOBALS["foton_setting"]["path"] . '/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['api'] . '/*') as $filename) {
                    $iu2 = $iu2 + 1;
                }
                ?><br>

                <input type='text' name='func' class='func' placeholder='Функция' value='<?= $this->request->p['api']; ?>'> <input
                        type='text' name='kolvo' id='kolv2' placeholder='кол-во' value='<?= $iu2 - 1; ?>'>
                <br>
                <div id='u2'>
                    <?
                    $iu = 0;
                    foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['api'] . '/*') as $filename) {
                        $ids = count(glob($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['api'] . '/*'));
                        $iu = $iu + 1;
                        $file = basename($filename);
                        if ($file != 'perem.txt') {
                            $filei = file_get_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['api'] . '/' . $file);
                            $file = str_replace('.php', '', $file);

                            $filei = str_replace('<? function ' . $this->request->p['api'] . '(' . $filevar . '){', '', $filei);

                            $filei = str_replace('<? function ' . $this->request->p['api'] . '(){', '', $filei);

                            $up = substr($filei, 0, 2);
                            if ($up == '<?') {
                                $filei = substr_replace($filei, null, 0, 2);
                            }

                            $filei = substr($filei, 0, -2);


                            echo "<div class='divdel" . $file . "'>name:<input type='text' id='poleii" . $iu . "' name='poleii" . $iu . "' value='" . $file . "'> Содержимое:<textarea name='fileii" . $iu . "' id='fileii" . $iu . "'>" . $filei . "</textarea>
                        <input type='button' class='plust' value='+' attr-id='" . $file . "' attr-id2='" . $ids . "'><input type='button' class='minust' value='-' attr-id='" . $file . "' attr-id2='" . $ids . "' ></div><br>";
                        }

                    }
                    ?>
                    <input type='hidden' name='hidfunc' value='<?= $this->request->p['api']; ?>'>

                </div>
                <input type='submit' value='Сохранить' class='class'> <span class='del'>Удалить: <input type='checkbox'
                                                                                                        name='del'
                                                                                                        value='on'></span>
            </form>

            <?

        }
    }
}
