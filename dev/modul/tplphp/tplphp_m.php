<?php

  class Tplphp_m extends Model
{
    public function name_m()
    {
        return 'Шаблоны PHP';

    }

    public function dir_m()
    {
        return '0';

    }

    public function modul_chmod(){
         return [1,1];
    }

    public function shablon_php()
    {
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/*') as $filename) {

            $arr[] = basename($filename);
        }
        if (isset($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return array();
        }

    }

    public function full_del_dir($deldir)
    {
        $dir = opendir($deldir);
        while (($file = readdir($dir))) {
            if (is_file($deldir . "/" . $file)) {
                unlink($deldir . "/" . $file);
            } else if (is_dir($deldir . "/" . $file) && ($file != ".") && ($file != "..")) {
                full_del_dir($deldir . "/" . $file);
            }
        }
        closedir($dir);
        rmdir($deldir);

    }


    public function shablon_php2()
    {
        $res = $this->core->list_table();
        $t = '';
        for ($i = 0; $i < count($res); $i++) {
            $t .= ',' . $res[$i];
            $result = $this->core->field_table($res[$i]);
            $colw = '';
            foreach ($result as $col) {
                $colw .= $col . ',';
            }
            $arr['desc'][] = $colw;
            $arr['id'][] = $res[$i];
        }
        return $arr;
    }


    public function version_up()
    {

        return array("host" => "https://foton.name/api_f.ajaxsite", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "version", "modul" => "tplphp"));

    }

    public function version()
    {

        return '2.7';

    }


    public function install()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "tplphp")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "tplphp")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "tplphp")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "tplphp")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "tplphp")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "tplphp")));
        return $connect;
    }

    public function install_m($data)
    {

        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/tplphp_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/tplphp_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/tplphp_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/css/style.css", $data["css"]);
    }

    public function up()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "tplphp")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "tplphp")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "tplphp")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "tplphp")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "tplphp")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "tplphp")));
        return $connect;
    }

    public function up_m($data)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/tplphp_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/tplphp_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/tplphp_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tplphp/css/style.css", $data["css"]);
    }

    public function del()
    {
        $connect = array("filest" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "delete")));
        return $connect;
    }

    public function del_m($data)
    {
//file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/tplphp/del.php",$data["filest"]);
    }


}