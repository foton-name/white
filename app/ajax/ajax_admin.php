<?php

  class Ajax_admin extends Ajax_admin_m
{
    public $obj_mod;
    public $obj_c;
    public $mod;
    public $obj_m;
    public $widget;

    public function __construct()
    {
        global $core; $this->core = $core;
        $this->glob = new \Controller_Globals;
        $this->mod = $this->core->mod;
        $this->request = $this->core->request;
        $this->widget = $this->core->widget;
    }

    public function polygon_echo()
    {
        if ($this->request->p['html'] && $this->request->p['data_ajax']) {
            $arr = array('interface' => 'sp', 'extra_arr' => array('name' => 'lang'), 'fields_table' => 'field', 'fields_type' => 'format_select');
            echo $this->core->i_load_ajax($this->request->p['html'], $this->request->p['data_ajax'], $arr);
        }
    }

    public function deletesession()
    {
        if (isset($_SESSION['text1'])) {
            unset($_SESSION['text1']);
        }
        if (isset($_SESSION['text2'])) {
            unset($_SESSION['text2']);
        }

    }

    public function updates_core()
    {
        if (isset($this->request->p['up_core']) && $this->request->p['up_core'] == 'yes') {
            $this->core->up_core('yes');
        }

    }

    public function save_file_css()
    {
        if (isset($this->request->p['css'])) {
            file_put_contents($this->core->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/css/workarea.css'), $this->request->p['css'], FILE_APPEND);
        }
    }

    public function search_dir()
    {
        if (isset($this->request->p['search'])) {
            $files = [];
            $this->core->dir_search_foton($GLOBALS["foton_setting"]["path"], $this->request->p['search'], $files);
            $file = implode(',', $files);
            $file = str_replace($GLOBALS["foton_setting"]["path"], '', $file);
            echo $file;
        }
    }


    public function file_force_download()
    {
        $file = $GLOBALS["foton_setting"]["path"] . '/system/admin-inc/key.html';

        if (file_exists($file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            // читаем файл и отправляем его пользователю
            readfile($file);

        }
    }

    public function new_passwords()
    {
        $array_sumbol = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $array_numbers = range('0', '9');
        $array_func = array('*', '+', '.');
        $arr_dates = array('god', 'mes', 'chas', 'min');
        $stroka_new = '';
        for ($i = 0; $i < rand(20, count($array_numbers)); $i++) {
            $stroka_new .= '"' . $array_sumbol[rand(0, count($array_sumbol))] . '".$' . $arr_dates[rand(0, 3)] . '.';
        }
        for ($i = 0; $i < rand(0, count($array_numbers)); $i++) {
            $stroka_new .= '"' . $array_numbers[rand(0, count($array_numbers))] . '".$' . $arr_dates[rand(0, 3)] . '.';
        }

        $stroka_new_js = str_replace('.', '+', $stroka_new);
        $stroka_new_js = str_replace('$', '', $stroka_new_js);
        $stroka_new2 = '';
        for ($i = 0; $i < rand(20, count($array_numbers)); $i++) {
            $stroka_new2 .= '"' . $array_sumbol[rand(0, count($array_sumbol))] . '".$' . $arr_dates[rand(0, 3)] . '.';
        }
        for ($i = 0; $i < rand(0, count($array_numbers)); $i++) {
            $stroka_new2 .= '"' . $array_numbers[rand(0, count($array_numbers))] . '".$' . $arr_dates[rand(0, 3)] . '.';
        }

        $stroka_new_js2 = str_replace('.', '+', $stroka_new2);
        $stroka_new_js2 = str_replace('$', '', $stroka_new_js2);
        $keyphp = '<?
          $god=date("Y"); 
          $mes=date("n")-1;
          $chas=date("H");
          $min=date("i");
          $date=' . $stroka_new . '"key";
          $date2=' . $stroka_new2 . '"key";?>';
        file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/admin-inc/key.php', $keyphp);
        $js = $this->key_file($stroka_new_js, $stroka_new_js2);
        file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/admin-inc/key.html', $js);


    }

    public function del_passwords()
    {
        unlink($GLOBALS["foton_setting"]["path"] . '/system/admin-inc/key.html');

    }

    public function delete_logs()
    {
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/.logs/' . $_SESSION['login'] . '/*-*-*') as $filename) {

            if (is_dir($filename)) {
                foreach (glob($filename . '/*') as $filename2) {

                    unlink($filename2);
                }
                sleep(2);
                rmdir($filename);

            }

        }
    }

    public function save_work_contener()
    {

        if (isset($this->request->p['text']) && $this->request->p['text'] != '') {
            $text = preg_replace('#<div class="remove_work_elem([^>]+)></div>#', '', $this->request->p['text']);
            mkdir($GLOBALS["foton_setting"]["path"] . '/.logs/' . $_SESSION['login']);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/.logs/' . $_SESSION['login'] . '/work_area.html', $text);
        }
    }


    public function update_razdel_all()
    {
        $this->core->up_tpl();
    }

    public function insertsession()
    {
        if (isset($this->request->p['text']) && $this->request->p['text'] != '') {
            $_SESSION[$this->request->p['name']] = $this->request->p['text'];

        }

    }


    public function translite_ajax()
    {
        return $this->core->translate($this->request->p['text']);
    }

        public function ajax_save_mvc(){
        if(isset($this->request->p['name']) && $this->request->p['name']!=''){
          if($this->request->p['del']=='no'){
            $path=$this->request->p['path'];
            $name=$this->request->p['name'];
            if($this->request->p['path']=='view'){
              $path2=$this->request->p['dir'];
              file_put_contents($this->core->git($GLOBALS['foton_setting']['path']."/app/view/".$path2."/".$name."_".$path.".tpl"),$this->request->p['text']);
              if($path2==$GLOBALS['foton_setting']['admindir']){
                $view=$this->core->a_cache_foton($this->request->p['text']);   
              }
              else{
                $view=$this->core->cache_foton($this->request->p['text']);
              }
              file_put_contents($this->core->git($GLOBALS['foton_setting']['path']."/system/api/tpl/".$path2."/".$name."_".$path.".php"),$view);
    
            }
            else{
              if($this->request->p['dir']=='sites'){
                file_put_contents($this->core->git($GLOBALS['foton_setting']['path']."/app/".$path."/".$GLOBALS['foton_setting']['sitedir']."/".$path."_".$name.".php"),$this->request->p['text']);
    
              }else{
                file_put_contents($this->core->fexists_foton($path."_".$name.".php",$path),$this->request->p['text']); 
              }
    
            }
          }
          else{
            $path=$this->request->p['path'];
            $name=$this->request->p['name'];
            if($this->request->p['path']=='view'){
              $path2=$GLOBALS["foton_setting"]["sitedir"];
              unlink($this->core->git($GLOBALS['foton_setting']['path']."/system/api/tpl/".$path2."/".$name."_".$path.".php"));
              unlink($this->core->git($GLOBALS['foton_setting']['path']."/app/view/".$path2."/".$name."_".$path.".php"));
              unlink($this->core->git($GLOBALS['foton_setting']['path']."/app/view/".$path2."/".$name."_".$path.".tpl"));
            }
            else{
              unlink($this->core->fexists_foton("controller_".$name.".php","controller"));
              unlink($this->core->fexists_foton("model_".$name.".php","model"));
            }
          }
        }   
    
      }
    
    public function background_update()
    {
        if (isset($this->request->p['url']) && $this->request->p['url'] != '') {
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/css/' . $_SESSION['login'] . 'background.css', '.menux{background-image:url("' . $this->request->p['url'] . '"); }' . $this->request->p['colorcss'] . $this->request->p['foncss']);
        } else if ($this->request->p['colorcss'] != '' && $this->request->p['foncss'] != '') {
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/css/' . $_SESSION['login'] . 'background.css', $this->request->p['colorcss'] . $this->request->p['foncss'], FILE_APPEND);
        } else if ($this->request->p['colorcss'] != '') {
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/css/' . $_SESSION['login'] . 'background.css', $this->request->p['colorcss'], FILE_APPEND);
        } else if ($this->request->p['foncss'] != '') {

            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/css/' . $_SESSION['login'] . 'background.css', $this->request->p['foncss'], FILE_APPEND);
        } else {

        }
    }

    public function ajaxpopap()
    {
        if (isset($this->request->p['pathmvc']) && isset($this->request->p['dir']) && isset($this->request->p['file'])) {
            return $this->core->s_mvc($this->request->p['pathmvc'], $this->request->p['file'], $this->request->p['dir']);
        }
    }

    public function api_up_js()
    {
        if (isset($this->request->p['script'])) {
            preg_match_all('|{{([^_]+)_([^}]+)}}|U',
                $this->request->p['script'],
                $out22, PREG_PATTERN_ORDER);
            $stack = array();
            for ($i = 0; $i < count($out22[1]); $i++) {
                array_push($stack, $out22[1][$i]);
            }
            $out225 = array_unique($stack);
            for ($it = 0; $it < count($out22[1]); $it++) {
                if ($out225[$it]) {
                    echo $out225[$it] . '<br>';
                }

            }
        }
    }


    public function searchlog(){
        $pos2 = strripos($this->request->p['searchl'], '/');
        if ($pos2 === false) {
            if(isset($this->request->p['text']) && file_exists($GLOBALS['foton_setting']['path'].'/.logs/'.$_SESSION['login'].'/'.$this->request->p['datal'].'/'.$this->request->p['text'])){
                $file=file_get_contents($GLOBALS['foton_setting']['path'].'/.logs/'.$_SESSION['login'].'/'.$this->request->p['datal'].'/'.$this->request->p['text']);
                echo $file; 
            }
            else{
                $pos = strripos($this->request->p['searchl'], '+');
                if ($pos === false) {
                    foreach (glob($GLOBALS['foton_setting']['path'].'/.logs/'.$_SESSION['login'].'/'.$this->request->p['datal'].'/'.$this->request->p['searchl'].'*') as $filename) {
                        echo "<a href='#' class='filetext'>".basename($filename)."</a>";
                    }
                }
                else{
                    $arr=explode('+',$this->request->p['searchl']);
                    foreach (glob($GLOBALS['foton_setting']['path'].'/.logs/'.$_SESSION['login'].'/'.$this->request->p['datal'].'/*'.$arr[0].'*_*'.$arr[1].'*_*'.$arr[2].'*') as $filename) {
                        echo "<a href='#' class='filetext'>".basename($filename)."</a>";
                    }
                }
            }
        }
        else{
            $arr2=explode('/',$this->request->p['searchl']);
            if(isset($this->request->p['text']) && file_exists($GLOBALS['foton_setting']['path'].'/.logs/*'.$arr2[0].'*/'.$this->request->p['datal'].'/'.$this->request->p['text'])){
                $file=file_get_contents($GLOBALS['foton_setting']['path'].'/.logs/*'.$arr2[0].'*/'.$this->request->p['datal'].'/'.$this->request->p['text']);
                echo $file;
            }
            else{
                $pos = strripos($arr2[1], '+');
                if ($pos === false) {
                    foreach (glob($GLOBALS['foton_setting']['path'].'/.logs/*'.$arr2[0].'*/'.$this->request->p['datal'].'/'.$arr2[1].'*') as $filename) {
                        echo "<a href='#' class='filetext'>".basename($filename)."</a>";
                    }
                }
                else{
                    $arr=explode('+',$arr2[1]);
                    foreach (glob($GLOBALS['foton_setting']['path'].'/.logs/*'.$arr2[0].'*/'.$this->request->p['datal'].'/*'.$arr[0].'*_*'.$arr[1].'*_*'.$arr[2].'*') as $filename) {
                        echo "<a href='#' class='filetext'>".basename($filename)."</a>";
                    }
                }
            }
        }
    }

    public function insertdb()
    {
        if (isset($this->request->p['key']) && $this->request->p['key'] == $GLOBALS['foton_setting']['backup']) {
            return $this->core->site_dump('yes');
        }
    }


}