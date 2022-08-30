<?php

  class Htmlred_a extends Htmlred_c
{
    public function stranicahtml()
    {
        if (isset($this->request->p['name'])) {
            $filest = file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/template/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->request->p['name'] . '.html');
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
            $path = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlred/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->request->p['page'];
            $this->core->dir_delete_foton($path);
            unset($_SESSION['step']);
            unset($_SESSION['page_cur']);
            mkdir($path);
        }

    }

    public function save_tmp()
    {
        if (isset($this->request->p['tmp'])) {
            $path0 = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlred/tmp';
            $path1 = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlred/tmp/' . $_SESSION['login'];
            $path2 = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlred/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['sitedir'];
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
                $path3 = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlred/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->request->p['page'];
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
            $path = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlred/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->request->p['page'];

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
            $path = $GLOBALS['foton_setting']['path'] . '/dev/modul/htmlred/tmp/' . $_SESSION['login'] . '/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->request->p['page'];
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
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/filest/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $rand . '.png', $file);
            echo '/dev/modul/htmlred/filest/' . $rand . '.png';
        } else if (isset($this->request->p['sh'])) {
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/tpl/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->request->p['name'] . '.html', $this->request->p['sh']);
        } else if (isset($this->request->p['shs']) && file_exists($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/tpl/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->request->p['shs'] . '.html')) {
            echo file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/tpl/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->request->p['shs'] . '.html');
        } else if (isset($this->request->p['sites'])) {
            $this->request->p['sites'] = preg_replace('#<link ([^>]+)>#', '', $this->request->p['sites']);
            if (isset($this->request->p['rem']) && $this->request->p['rem'] == 'yes') {
                preg_match_all('#([^0-9]+)([0-9.]+)px#', $this->request->p['sites'], $outrem, PREG_PATTERN_ORDER);
                foreach ($outrem[1] as $keyrem => $valrem) {
                    $rem = $outrem[2][$keyrem] / 16;
                    $rem = round($rem, 2);
                    $this->request->p['sites'] = str_replace($valrem . $outrem[2][$keyrem] . 'px', $valrem . $rem . 'rem', $this->request->p['sites']);
                }
            }
            $file_shablon = $this->request->p['sites'];

            $css = '';
            $this->request->p['sites'] = str_replace("&quot;", "'", $this->request->p['sites']);
            preg_match_all('#\[\[([^\]]+)\]\]#', $this->request->p['sites'], $outhtml, PREG_PATTERN_ORDER);
            foreach ($outhtml[1] as $keyhtml => $valhtml) {
                $this->request->p['sites'] = str_replace("[[" . $valhtml . "]]", file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/tpl/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $valhtml . '.html'), $this->request->p['sites']);

            }
            $sites_html = str_replace('draggable="true"','',$this->request->p['sites']);
            $sites_html = str_replace('ondragstart="return dragStart(event)"','',$sites_html);
            $sites_html = preg_replace('#id="([^"]+)"#','class="$1"',$sites_html);
            $sites_html = str_replace('drops ui-draggable ui-draggable-handle ui-draggable-dragging','',$sites_html);
            $sites_html = str_replace('drops ui-draggable ui-draggable-handle','',$sites_html);
            $sites_html = str_replace('ui-draggable ui-draggable-handle','',$sites_html);
            $sites_html = str_replace('class="boxA"','',$sites_html);
            $sites_html = str_replace('class=""','',$sites_html);
            $sites_html = preg_replace('#<div class="body" style="([^"]+)">#','<div class="body" %%%tyle="$1">',$sites_html);
              preg_match_all('|style="([^"]+)">|U',
               $sites_html,
                $out, PREG_PATTERN_ORDER);

            for ($it = 0; $it < count($out[1]); $it++) {
                    $sites_html = str_replace('style="' . $out[1][$it] . '"','id="foton-' . $it . '-id"', $sites_html);
                    $css .= "#foton-" . $it . "-id{" . $out[1][$it] . "}\n";
            }
            $sites_html = str_replace('%%%tyle="','style="',$sites_html);
            $this->request->p['sites'] = $sites_html;
            preg_match_all('|<div class="body" style="([^"]+)">|U',
                $this->request->p['sites'],
                $out5, PREG_PATTERN_ORDER);
            $css .= "body{" . $out5[1][0] . "}";

            $this->request->p['sites'] = preg_replace('#<div class="body" style="([^"]+)">#', '<div class="body_includes">', $this->request->p['sites']);
            $this->request->p['sites'] = str_replace("><", ">\n<", $this->request->p['sites']);
            $sitest = $this->request->p['sites'];
            $arrtables = array();
            $arrpole = array();
            $arrpoleid = array();
            $css = str_replace("&quot;", "", $css);
            $css = preg_replace("|#id_include([0-9]+)\{\}|", "", $css);
            if (isset($this->request->p['compress']) && $this->request->p['compress'] == 'yes') {
                $css = str_replace("\n", "", $css);
            }
            else{
                $css = str_replace("\n", "", $css);
                $css = str_replace("}", "}\n", $css);
                $css = str_replace(";", ";\n", $css);
            }
            if ($this->request->p['namesite'] != '') {
                $namesite = strtolower($this->core->tr($this->request->p['namesite']));
            } else {
                $namesite = strtolower($this->core->tr($this->request->p['komment']));
            }
            echo '/' . $namesite . '/';
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/template/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $namesite . '.html', $this->request->p['font'] . $file_shablon);
            $css = preg_replace('#background-image:(\s*)url\((["\']*)([^\)\'"]+)(["\']*)\);#', '%%%%%$3@@@@@', $css);
            preg_match_all("|%%%%%([^@]+)@@@@@|U",
                $css,
                $cssmass, PREG_PATTERN_ORDER);
            for ($it = 0; $it < count($cssmass[1]); $it++) {
                $filename = preg_replace('#/dev/modul/htmlred/filest/' . $GLOBALS['foton_setting']['sitedir'] . '/([^.]+).([a-zA-Z]+)$#', '$1.$2', $cssmass[1][$it]);
                $filename = str_replace('http://', '', $filename);
                $filename = str_replace($_SERVER['SERVER_NAME'], '', $filename);
                $css = str_replace('%%%%%' . $cssmass[1][$it] . '@@@@@', 'background-image:url("/dev/modul/htmlred/filest/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $filename . '");', $css);
            }
            foreach(glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $GLOBALS['foton_setting']['sitedir'] . '/style_sh[0-9]*'.$namesite . '.css') as $oldfiles){
                unlink($oldfiles);
            }
            if (isset($this->request->p['compress']) && $this->request->p['compress'] == 'yes') {
                file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $GLOBALS['foton_setting']['sitedir'] . '/style_sh' . $time.$namesite . '.css', $css);
            }
            else{
                $files_font = file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/style_font.css');
                file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $GLOBALS['foton_setting']['sitedir'] . '/style_sh' . $time.$namesite . '.css', $css . $files_font);
            }
            $sitest2 = $sitest;
            $sitest = $this->core->cache_foton($sitest);
            if (!file_exists($GLOBALS["foton_setting"]["path"] . '/app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $namesite . '.php') && stristr($namesite, '_mob') === false) {
                file_put_contents($GLOBALS["foton_setting"]["path"] . '/app/controller/' . $GLOBALS['foton_setting']['sitedir'] . '/controller_' . $namesite . '.php', "<?
class Controller_" . $namesite . " extends Model_" . $namesite . "{
    public function dir(){
    return '" . $GLOBALS["foton_setting"]["sitedir"] . "';
}
}");
                file_put_contents($GLOBALS["foton_setting"]["path"] . '/app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $namesite . '.php', "<?
class Model_" . $namesite . " extends Model{
 public function nameinclude(){
    return '" . $this->request->p['namesite'] . "';
    
}
 public function interfaces(){

}
}");
            }
            $paths = '/dev/modul/htmlred/site/' . $GLOBALS['foton_setting']['sitedir'] . '/style_sh' . $time.$namesite . '.css'; 
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $namesite . '_view.php', '<link rel="stylesheet" type="text/css" href="'.$paths.'" />' . $this->request->p['font'] . htmlspecialchars_decode($sitest));
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $namesite . '_view.php')) {
                unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $namesite . '_view.php');
            }
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $namesite . '_view.tpl', '<link rel="stylesheet" type="text/css" href="'.$paths.'" />' . $this->request->p['font'] . htmlspecialchars_decode($sitest2));
        } else if (isset($this->request->p['redact'])) {
            $file2 = file_get_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/css.css');
            $file2 = str_replace("\nbody{", '.body{', $file2);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/css.css', $file2);
            $file = file_get_contents('sites.html');
            $file = preg_replace('#</div>([^<]*)</body>#', '</body>', $file);
            preg_match_all('|<body>([\s\S]*)</body>|U',
                $file,
                $out5, PREG_PATTERN_ORDER);
            $out5[1][0] = str_replace('<div class="body">', '', $out5[1][0]);
            echo '<link rel="stylesheet" href="css.css">' . $out5[1][0];
        } 
        else {

        }
    }


    public function del_files_sh()
    {
        $namesite = str_replace('.html', '', $this->request->p['file']);
        if ($namesite != $GLOBALS["foton_setting"]["main"] && $namesite != 'error404') {
            unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $namesite . '_view.tpl');
            unlink($GLOBALS["foton_setting"]["path"] . '/app/model/' . $GLOBALS["foton_setting"]["sitedir"] . '/model_' . $namesite . '.php');
            unlink($GLOBALS["foton_setting"]["path"] . '/app/controller/' . $GLOBALS["foton_setting"]["sitedir"] . '/controller_' . $namesite . '.php');
        }
        foreach(glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $GLOBALS['foton_setting']['sitedir'] . '/style_sh[0-9]*'.$namesite . '.css') as $oldfiles){
                unlink($oldfiles);
        }
        unlink($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $namesite . '_view.php');
        unlink($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/template/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $namesite . '.html');
    }


}