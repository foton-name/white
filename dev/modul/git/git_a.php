<?php

  class Git_a extends Git_c
{
    public function saves_p()
    {
        if (isset($this->request->p['path']) && isset($this->request->p['text'])) {
            file_put_contents($this->request->p['path'], $this->request->p['text']);
            if ($this->request->p['sh'] == 'yes') {
                $this->request->p['path'] = str_replace("system/api/tpl/", 'app/view/', $this->request->p['path']);
                $text = $this->core->cache_foton($this->request->p['text']);
                file_put_contents($this->request->p['path'], $text);
            }
        }
    }


    public function saveupp()
    {
        if (isset($this->request->p['user']) && $_SESSION['chmod_id'] == '1') {
            $arr = array('controller', 'view', 'face', 'model', 'modules', 'ajax', 'api');
            foreach ($arr as $dir) {
                $src = $GLOBALS['foton_setting']['path'] . '/' . $dir;
                mkdir($GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['user'] . '/work/' . $dir, 0755);
                $dest = $GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['user'] . '/work/' . $dir;
                $this->core->copy_dir($src, $dest, 1);
            }
        }

    }


    public function saveset()
    {
        if (isset($this->request->p['b']) && isset($this->request->p['u']) && isset($this->request->p['p']) && $this->request->p['b'] != '' && $this->request->p['u'] != '' && $this->request->p['p'] != '' && $_SESSION['chmod_id'] == '1') {
            $text = "<? \$GLOBALS['foton_setting']['dbname']='" . $this->request->p['b'] . "';\$GLOBALS['foton_setting']['login']='" . $this->request->p['u'] . "';\$GLOBALS['foton_setting']['pass']='" . $this->request->p['p'] . "';?>";
            $file = $GLOBALS["foton_setting"]["path"] . '/.gitf/' . $this->request->p['user'] . '/dump.sql';
            $this->core->sql_dump_file($file);
            file_put_contents($GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['user'] . '/work/sql.php', $text);
            $_SESSION['gittest'] = '1';
            $_SESSION['up_sql'] = 'yes';
            $_SESSION['usergit'] = $_SESSION['login'];
            $_SESSION['login'] = $this->request->p['user'];
        }

    }


    public function openv()
    {
        if (isset($this->request->p['path']) && isset($this->request->p['text'])) {
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/branch.php')) {
                $vt0 = file_get_contents($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/branch.php');
                $pt = str_replace('.gitf/' . $_SESSION['login'] . '/work/', '', $this->request->p['path']);
                $pt = preg_replace("#\.gitf/" . $_SESSION['login'] . "/release/([^/]+)/#", '', $pt);
                $arrdir = explode('/', $pt);
                $dirv = '';
                foreach ($arrdir as $dk => $dir) {
                    if ($dk < count($arrdir) - 1) {
                        $dirv .= '/' . $dir;
                        if (!file_exists($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/release/' . $vt0 . $dirv)) {
                            mkdir($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/release/' . $vt0 . $dirv);
                        }
                    } else {
                        file_put_contents($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/release/' . $vt0 . $dirv . '/' . $dir, $this->core->html_foton($this->request->p['text']));
                    }
                }
                echo 'Файл скопирован в ветку ' . $vt0;
            } else {
                echo 'Создайте ветку';
            }
        }
    }

    public function selectv()
    {
        if (isset($this->request->p['path'])) {
            $vt0 = $GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/branch.php';
            file_put_contents($vt0, $this->request->p['path']);
        }
    }

    public function createv()
    {
        if (isset($this->request->p['path'])) {
            $nm = $this->core->tr($this->request->p['path']);
            mkdir($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/release/' . $nm);
            echo "<div class='vu2'><div class='vu' path='" . $GLOBALS["foton_setting"]["path"] . "/.gitf/" . $_SESSION['login'] . "/release/" . $nm . "' idsu='" . $_SESSION['login'] . "'>" . $nm . "</div><span class='delv' path='" . $GLOBALS["foton_setting"]["path"] . "/.gitf/" . $_SESSION['login'] . "/release/" . $nm . "' >Удалить</span><span class='selectv' path='" . $nm . "' >Выбрать</span></div>";
        }
    }

    public function delv()
    {
        if (isset($this->request->p['path'])) {
            $this->core->dir_delete_foton($this->request->p['path']);
        }
    }

    public function filesp2()
    {

        echo $this->filesp();
    }

    public function saves_pu()
    {
        if (isset($this->request->p['path']) && isset($this->request->p['user'])) {
            $this->request->p['path'] = str_replace(".gitf/" . $this->request->p['user'] . "/work/", '', $this->request->p['path']);
            $this->request->p['path'] = preg_replace("#\.gitf/" . $this->request->p['user'] . "/release/([^/]+)/#", '', $this->request->p['path']);
            file_put_contents($this->request->p['path'], $this->request->p['text']);
            if ($this->request->p['sh'] == 'yes') {
                $this->request->p['path'] = str_replace("system/api/tpl/", 'app/view/', $this->request->p['path']);
                $text = $this->core->cache_foton($this->request->p['text']);
                file_put_contents($this->request->p['path'], $text);
            }
        }
    }

    public function diff_file()
    {
        $path = $this->request->p['path'];
        $text = fopen($path, "r");
        $lines = null;
        if ($text) {
            while (($buffer = fgets($text)) !== false) {
                $lines[] = $buffer;
            }
        }
        fclose($text);
        if (isset($this->request->p['idsu']) && $this->request->p['idsu'] != '') {
            $lines2 = file($this->gitm2($path, $this->request->p['idsu']));
        } else {
            $lines2 = file($this->gitm2($path));
        }
        if ($lines > $lines2) {
            foreach ($lines as $k => $line) {
                if (isset($lines2[$k]) && $line != $lines2[$k]) {
                    if (in_array($lines2[$k], $lines)) {
                        echo '<p class="green2">' . htmlspecialchars($lines2[$k]) . '</p>';

                    } else {
                        echo '<p class="red">' . htmlspecialchars($lines2[$k]) . '</p>';
                    }
                } else {
                    if (isset($lines2[$k])) {
                        echo '<p class="green">' . htmlspecialchars($lines2[$k]) . '</p>';
                    }
                }
            }
        } else {
            foreach ($lines2 as $k => $line) {
                if (isset($lines[$k]) && $line != $lines[$k]) {
                    if (in_array($lines[$k], $lines2)) {
                        echo '<p class="green2">' . htmlspecialchars($lines[$k]) . '</p>';
                    } else {
                        echo '<p class="red">' . htmlspecialchars($lines[$k]) . '</p>';
                    }
                } else {
                    if (isset($lines[$k])) {
                        echo '<p class="green">' . htmlspecialchars($lines[$k]) . '</p>';
                    }
                }
            }

        }
    }

    public function userhome()
    {
        if (isset($this->request->p['idsu'])) {
            $loginu = $this->userlog();
            if (isset($this->request->p['path'])) {
                if ($_SESSION['chmod_id'] != '1') {
                    $loginu = $_SESSION['login'];
                }
                require_once 'homeuv.php';

            } else {
                require_once 'homeu.php';
            }
        }
    }

    public function userlog()
    {
        if ($_SESSION['login'] && $_SESSION['chmod_id'] == '1' && $this->request->p['idsu']) {
            return $this->request->p['idsu'];
        } else {
            return $_SESSION['login'];
        }
    }

    public function settinguser()
    {
        if (isset($this->request->p['idsu'])) {
            $user = $this->userlog();
            if (file_exists($GLOBALS['foton_setting']['path'] . '/.gitf/' . $user . '/work/sql.php')) {
                include($GLOBALS['foton_setting']['path'] . '/.gitf/' . $user . '/work/sql.php');
            }
            require_once 'settings.php';
        }
    }

    public function vetkiuser()
    {
        if (isset($this->request->p['idsu'])) {
            $loginu = $this->userlog();
            require_once 'vetkiu.php';
        }
    }

    public function useri()
    {
        if (isset($this->request->p['login'])) {
            $login = $this->request->p['login'];
            require_once 'useri.php';
        }
    }

    public function delete_dir()
    {
        if (isset($this->request->p['path'])) {
            return $this->dir_delete($this->request->p['path']);
        }
    }

    public function dir_delete($path)
    {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file) {
                $this->dir_delete(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        } else if (is_file($path) === true) {
            return unlink($path);
        }

        return false;
    }

    public function createsfile()
    {
        if (isset($this->request->p['path'])) {
            $path = preg_replace('#/([^/]+)$#', '', $this->request->p['path']);
            $pathnew = $path . '/' . $this->request->p['file'];
            if (isset($this->request->p['format']) && $this->request->p['format'] == 'file') {
                file_put_contents($pathnew, '');
            } else {
                mkdir($pathnew, 0755);
            }
        }
    }


    public function filepath()
    {
        $file = file_get_contents($this->request->p['path']);
        $file = htmlspecialchars($file);
        $file = str_replace("\t", "", $file);
        echo '<div class="head-f"><div class="open-f" path="' . $this->request->p['path'] . '">' . $this->request->p['name'] . '</div><div class="plus-insert"></div><span class="plus-f"></span></div><div class="pole-f">' . $this->widget->redactorkoda->index("700", "100%", 'name', $file, 'redactor1', "0", "0") . '<textarea class="hid-t">' . $file . '<' . '/textarea><input type="button" class="saves-f" value="Сохранить"><input type="button" class="diff-f" value="Сравнить"><input type="button" class="otk-f" value="Откатить">  <span> Как шаблон:</span><input type="checkbox" class="sh-yes">';
        if ($_SESSION['chmod_id'] == '1') {
            echo "<input type='button' class='upload-f' value='Выгрузить'>";
        } else {
            echo '<input type="button" class="uploadbranch" value="В ветку">';
        }
        echo '</div>';
    }
}