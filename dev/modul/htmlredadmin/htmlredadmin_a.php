<?php

  class htmlredadmin_a extends htmlredadmin_c
{
    public function stranicahtml()
    {
        if (isset($this->request->p['name'])) {
            $filest = file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/template/' . $GLOBALS['foton_setting']['admindir'] . '/' . $this->request->p['name'] . '.html');
            $filest = preg_replace('#font-family:(\s*)&quot;([^&]+)&quot;#', "font-family:$2", $filest);
            $filest = preg_replace("#font-family:(\s*)'([^']+)';#", "font-family:$2;", $filest);
            preg_match_all('#([^0-9]+)([0-9.]+)rem#', $filest, $outrem, PREG_PATTERN_ORDER);
            foreach ($outrem[1] as $keyrem => $valrem) {
                $rem = $outrem[2][$keyrem] * 16;
                $rem = round($rem, 2);
                $filest = str_replace($valrem . $outrem[2][$keyrem] . 'rem', $valrem . $rem . 'px', $filest);
            }
            echo $filest;
        }
    }

    public function console_step()
    {
        echo 'Шаг ' . ($_SESSION['page_cur'] + 1) . ' из ' . $_SESSION['step'];
    }

    public function clear_tmp()
    {
        if (isset($this->request->p['page']) && $this->request->p['page'] != '') {
            $path = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlredadmin/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['admindir'] . '/' . $this->request->p['page'];
            $this->core->dir_delete_foton($path);
            unset($_SESSION['step']);
            unset($_SESSION['page_cur']);
            mkdir($path);
        }

    }

    public function save_tmp()
    {
        if (isset($this->request->p['tmp'])) {
            $path0 = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlredadmin/tmp';
            $path1 = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlredadmin/tmp/' . $_SESSION['login'];
            $path2 = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlredadmin/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['admindir'];
            if (!file_exists($path0)) {
                mkdir($path0);
            }
            if (!file_exists($path1)) {
                mkdir($path1);
            }
            if (!file_exists($path2)) {
                mkdir($path2);
            }
            if (isset($this->request->p['page']) && $this->request->p['page'] != '') {
                $path3 = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlredadmin/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['admindir'] . '/' . $this->request->p['page'];
                if (!file_exists($path3)) {
                    mkdir($path3);
                }
                $date = date('Y_m_d_his');
                file_put_contents($path3 . '/tmpfile_' . $date . '.txt', $this->request->p['tmp']);
                $_SESSION['page_cur'] = count(glob($path3 . '/*.txt')) - 1;
                $_SESSION['step'] = $_SESSION['page_cur'];
                echo $_SESSION['page_cur'] + 1;
            } else {
                echo 'Название страницы не заполнено';
            }


        }
    }

    public function prev_tmp()
    {
        if (isset($this->request->p['prev']) && isset($this->request->p['page']) && $this->request->p['page'] != '') {
            $path = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlredadmin/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['admindir'] . '/' . $this->request->p['page'];

            if (isset($this->request->p['page']) && $this->request->p['page'] != '' && isset($_SESSION['page_cur']) && file_exists($path)) {

                $file_arr = glob($path . '/*.txt');

                if (isset($file_arr[$_SESSION['page_cur'] - 1]) && $file_arr[$_SESSION['page_cur'] - 1] != '') {
                    $file_prev = $file_arr[$_SESSION['page_cur'] - 1];
                    $_SESSION['page_cur'] = $_SESSION['page_cur'] - 1;
                    $_SESSION['step'] = count($file_arr);
                    if ($file_prev != '') {
                        echo file_get_contents($file_prev);
                    }
                }
            } else {
                echo 'Сессия еще не создана, либо название страницы не заполнено';
            }


        }
    }

    public function next_tmp()
    {
        if (isset($this->request->p['next']) && isset($this->request->p['page']) && $this->request->p['page'] != '') {
            $path = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlredadmin/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['admindir'] . '/' . $this->request->p['page'];
            if (isset($this->request->p['page']) && $this->request->p['page'] != '' && isset($_SESSION['page_cur']) && file_exists($path)) {

                $file_arr = glob($path . '/*.txt');

                if (isset($file_arr[$_SESSION['page_cur'] + 1]) && $file_arr[$_SESSION['page_cur'] + 1] != '') {
                    $file_next = $file_arr[$_SESSION['page_cur'] + 1];
                    $_SESSION['page_cur'] = $_SESSION['page_cur'] + 1;
                    $_SESSION['step'] = count($file_arr);
                    if ($file_next != '') {
                        echo file_get_contents($file_next);
                    }

                }
            } else {
                echo 'Сессия еще не создана, либо название страницы не заполнено';
            }


        }
    }


    public function removefile()
    {
        if (isset($this->request->p['file'])) {
            unlink($GLOBALS["foton_setting"]["path"] . $this->request->p['file']);
        }
    }


    public function ajaxfiles_all()
    {
        $datas = array();
        $error = false;
        $files = array();
        $rand = rand(0, 200) . rand(0, 200) . rand(0, 200);
        if (isset($_FILES)) {
            foreach ($_FILES as $file) {
                if (move_uploaded_file($file['tmp_name'], $GLOBALS['foton_setting']['path'] . $this->request->p['path'] . $rand . basename($file['name']))) {
                    $files[] = realpath($GLOBALS["foton_setting"]["path"] . $this->request->p['path'] . $rand . basename($file['name']));

                } else {
                    $error = true;
                }

            }
            $datas = $error ? array('error' => 'Ошибка загрузки файлов.') : array('files' => $files);
            echo json_encode($datas);
        }
    }


    public function files()
    {
        $time = time();
        if (isset($this->request->p['file'])) {
            $file = file_get_contents($this->request->p['file']);
            $rand = rand(0, 200) . rand(0, 200) . rand(0, 200);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/filest/' . $GLOBALS['foton_setting']['admindir'] . '/' . $rand . '.png', $file);
            echo '/dev/modul/htmlredadmin/filest/' . $rand . '.png';
        } else if (isset($this->request->p['sh'])) {
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/tpl/' . $GLOBALS['foton_setting']['admindir'] . '/' . $this->request->p['name'] . '.html', $this->request->p['sh']);
        } else if (isset($this->request->p['shs']) && file_exists($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/tpl/' . $GLOBALS['foton_setting']['admindir'] . '/' . $this->request->p['shs'] . '.html')) {
            echo file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/tpl/' . $GLOBALS['foton_setting']['admindir'] . '/' . $this->request->p['shs'] . '.html');
        } else if (isset($this->request->p['admins'])) {
            $this->request->p['admins'] = preg_replace('#<link ([^>]+)>#', '', $this->request->p['admins']);
            if (isset($this->request->p['rem']) && $this->request->p['rem'] == 'yes') {
                preg_match_all('#([^0-9]+)([0-9.]+)px#', $this->request->p['admins'], $outrem, PREG_PATTERN_ORDER);
                foreach ($outrem[1] as $keyrem => $valrem) {
                    $rem = $outrem[2][$keyrem] / 16;
                    $rem = round($rem, 2);
                    $this->request->p['admins'] = str_replace($valrem . $outrem[2][$keyrem] . 'px', $valrem . $rem . 'rem', $this->request->p['admins']);
                }
            }
            $file_template = $this->request->p['admins'];

            $css = '';
            $this->request->p['admins'] = str_replace("&quot;", "'", $this->request->p['admins']);
            preg_match_all('#\[\[([^\]]+)\]\]#', $this->request->p['admins'], $outhtml, PREG_PATTERN_ORDER);
            foreach ($outhtml[1] as $keyhtml => $valhtml) {
                $this->request->p['admins'] = str_replace("[[" . $valhtml . "]]", file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/tpl/' . $GLOBALS['foton_setting']['admindir'] . '/' . $valhtml . '.html'), $this->request->p['admins']);

            }
            $admins_html = str_replace('draggable="true"','',$this->request->p['admins']);
            $admins_html = str_replace('ondragstart="return dragStart(event)"','',$admins_html);
            $admins_html = preg_replace('#id="([^"]+)"#','class="$1"',$admins_html);
            $admins_html = str_replace('drops ui-draggable ui-draggable-handle ui-draggable-dragging','',$admins_html);
            $admins_html = str_replace('drops ui-draggable ui-draggable-handle','',$admins_html);
            $admins_html = str_replace('ui-draggable ui-draggable-handle','',$admins_html);
            $admins_html = str_replace('class="boxA"','',$admins_html);
            $admins_html = str_replace('class=""','',$admins_html);
            $admins_html = preg_replace('#<div class="body" style="([^"]+)">#','<div class="body" %%%tyle="$1">',$admins_html);
              preg_match_all('|style="([^"]+)">|U',
               $admins_html,
                $out, PREG_PATTERN_ORDER);

            for ($it = 0; $it < count($out[1]); $it++) {
                    $admins_html = str_replace('style="' . $out[1][$it] . '"','id="foton-' . $it . '-id"', $admins_html);
                    $css .= "#foton-" . $it . "-id{" . $out[1][$it] . "}\n";
            }
            $admins_html = str_replace('%%%tyle="','style="',$admins_html);
            $this->request->p['admins'] = $admins_html;
            preg_match_all('|<div class="body" style="([^"]+)">|U',
                $this->request->p['admins'],
                $out5, PREG_PATTERN_ORDER);
            $css .= "body{" . $out5[1][0] . "}";

            $this->request->p['admins'] = preg_replace('#<div class="body" style="([^"]+)">#', '<div class="body_includes">', $this->request->p['admins']);
            $this->request->p['admins'] = str_replace("><", ">\n<", $this->request->p['admins']);
            $adminst = $this->request->p['admins'];
            $arrtables = array();
            $arrpole = array();
            $arrpoleid = array();
            $css = str_replace("&quot;", "", $css);
            $css = preg_replace("|#id_include([0-9]+)\{\}|", "", $css);
            $css = str_replace("\n", "", $css);
            $css = str_replace("}", "}\n", $css);
            $css = str_replace(";", ";\n", $css);


            if ($this->request->p['nameadmin'] != '') {
                $nameadmin = strtolower($this->core->tr($this->request->p['nameadmin']));
            } else {

                $nameadmin = strtolower($this->core->tr($this->request->p['komment']));
            }
            echo '/' . $nameadmin . '/';


            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/template/' . $GLOBALS['foton_setting']['admindir'] . '/' . $nameadmin . '.html', $this->request->p['font'] . $file_template);


            $css = preg_replace('#background-image:(\s*)url\((["\']*)([^\)\'"]+)(["\']*)\);#', '%%%%%$3@@@@@', $css);

            preg_match_all("|%%%%%([^@]+)@@@@@|U",
                $css,
                $cssmass, PREG_PATTERN_ORDER);
            for ($it = 0; $it < count($cssmass[1]); $it++) {
                $filename = preg_replace('#/dev/modul/htmlredadmin/filest/' . $GLOBALS['foton_setting']['admindir'] . '/([^.]+).([a-zA-Z]+)$#', '$1.$2', $cssmass[1][$it]);
                $filename = str_replace('http://', '', $filename);
                $filename = str_replace($_SERVER['SERVER_NAME'], '', $filename);
                $css = str_replace('%%%%%' . $cssmass[1][$it] . '@@@@@', 'background-image:url("/dev/modul/htmlredadmin/filest/' . $GLOBALS['foton_setting']['admindir'] . '/' . $filename . '");', $css);
            }
            $files_font = file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/style_font.css');
            foreach(glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/admin/' . $GLOBALS['foton_setting']['admindir'] . '/style_sh[0-9]*'.$nameadmin . '.css') as $oldfiles){
                unlink($oldfiles);
            }
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/admin/' . $GLOBALS['foton_setting']['admindir'] . '/style_sh' . $time.$nameadmin . '.css', $css . $files_font);

            $adminst2 = $adminst;
            $adminst = $this->core->a_cache_foton($adminst);

            if (!file_exists($GLOBALS["foton_setting"]["path"] . '/app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $nameadmin . '.php') && stristr($nameadmin, '_mob') === false) {
                file_put_contents($GLOBALS["foton_setting"]["path"] . '/app/controller/' . $GLOBALS['foton_setting']['admindir'] . '/controller_' . $nameadmin . '.php', "<?
class Controller_" . $nameadmin . " extends Model_" . $nameadmin . "{

}");
                file_put_contents($GLOBALS["foton_setting"]["path"] . '/app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $nameadmin . '.php', "<?
class Model_" . $nameadmin . " extends Model{

 public function interfaces(){

}
}");
            }
            $paths = '/dev/modul/htmlredadmin/admin/' . $GLOBALS['foton_setting']['admindir'] . '/style_sh' . $time.$nameadmin . '.css'; 
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS['foton_setting']['admindir'] . '/' . $nameadmin . '_view.php', '<link rel="stylesheet" type="text/css" href="'.$paths.'" />' . $this->request->p['font'] . $adminst);
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/' . $nameadmin . '_view.php')) {
                unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/' . $nameadmin . '_view.php');
            }

            file_put_contents($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/' . $nameadmin . '_view.tpl', '<link rel="stylesheet" type="text/css" href="'.$paths.'" />' . $this->request->p['font'] . $adminst2);
        } else if (isset($this->request->p['redact'])) {

            $file2 = file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/css.css');
            $file2 = str_replace("\nbody{", '.body{', $file2);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/css.css', $file2);
            $file = file_get_contents('admins.html');
            $file = preg_replace('#</div>([^<]*)</body>#', '</body>', $file);
            preg_match_all('|<body>([\s\S]*)</body>|U',
                $file,
                $out5, PREG_PATTERN_ORDER);
            $out5[1][0] = str_replace('<div class="body">', '', $out5[1][0]);

            echo '<link rel="stylesheet" href="css.css">' . $out5[1][0];
        } else {

        }
    }


    public function del_files_sh()
    {
        $nameadmin = str_replace('.html', '', $this->request->p['file']);
        if ($nameadmin != $GLOBALS["foton_setting"]["main"] && $nameadmin != 'error404') {
            unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/' . $nameadmin . '_view.tpl');
            unlink($GLOBALS["foton_setting"]["path"] . '/app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $nameadmin . '.php');
            unlink($GLOBALS["foton_setting"]["path"] . '/app/controller/' . $GLOBALS['foton_setting']['admindir'] . '/controller_' . $nameadmin . '.php');
        }
        foreach(glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/admin/' . $GLOBALS['foton_setting']['admindir'] . '/style_sh[0-9]*'.$nameadmin . '.css') as $oldfiles){
                unlink($oldfiles);
        }
        unlink($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS['foton_setting']['admindir'] . '/' . $nameadmin . '_view.php');
        unlink($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/template/' . $GLOBALS['foton_setting']['admindir'] . '/' . $nameadmin . '.html');
    }


}