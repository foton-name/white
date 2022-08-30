<?php

  class Xmljson_m extends Model
{
    public function name_m()
    {
        return 'Xml/Json';

    }

    public function modul_chmod(){
         return [1,2];
    }

    public function dir_m()
    {
        return '0';
    }

    public function include_files($file)
    {

        if (file_exists($file)) {
            $filest = file_get_contents($file);
            $filest = str_replace('<', '&lt;', $filest);
            return $filest;
        }
    }

    public function include_files_up($file, $up, $from)
    {
        $file = str_replace($GLOBALS["foton_setting"]["path"] . '/app/view/' . $from . '/', '', $file);
        file_put_contents($GLOBALS["foton_setting"]["path"] . '/app/view/' . $from . '/' . $file, $up);
        $filetpl = str_replace('.tpl', '.php', $file);
        if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $from . '/' . $filetpl)) {
            unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $from . '/' . $filetpl);
        }
        $up = $this->core->cache_foton($up);
        file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $from . '/' . $filetpl, $up);
        if (file_exists($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $from . '/' . $file)) {
            unlink($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $from . '/' . $file);
        }
        return true;
    }

    public function files_unlink($file, $from)
    {
        $file = str_replace($GLOBALS["foton_setting"]["path"] . '/app/view/' . $from . '/', '', $file);
        if (file_exists($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $from . '/' . $file)) {
            unlink($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $from . '/' . $file);
        }
        if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $from . '/' . $file)) {
            unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $from . '/' . $file);
        }
        $filetpl = str_replace('.tpl', '.php', $file);
        if (file_exists($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $from . '/' . $filetpl)) {
            unlink($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $from . '/' . $filetpl);
        }
    }

    public function files_creates($file, $from)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $from . '/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $file . '_view.php', '');
        file_put_contents($GLOBALS["foton_setting"]["path"] . '/app/view/' . $from . '/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $file . '_view.tpl', '');

    }

    public function set($arg)
    {
        return $arg;
    }

    public function version_up()
    {

        return array("host" => "https://foton.name/api_f.ajaxsite", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "version", "modul" => "xmljson"));

    }

    public function version()
    {

        return '1';

    }

    public function install()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "xmljson")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "xmljson")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "xmljson")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "xmljson")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "xmljson")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "xmljson")));
        return $connect;
    }

    public function install_m($data)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/xmljson_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/xmljson_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/xmljson_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/css/style.css", $data["css"]);
    }

    public function up()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "xmljson")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "xmljson")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "xmljson")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "xmljson")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "xmljson")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "xmljson")));
        return $connect;
    }

    public function up_m($data)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/xmljson_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/xmljson_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/xmljson_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/xmljson/css/style.css", $data["css"]);
    }

    public function del()
    {
        $connect = array("filest" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "delete")));
        return $connect;
    }

    public function del_m($data)
    {

    }
}

?>