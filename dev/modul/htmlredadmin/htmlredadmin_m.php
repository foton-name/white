<?php

  class htmlredadmin_m extends Model
{
    public function name_m()
    {
        return 'Визуальный редактор интерфейсов';

    }

    public function dir_m()
    {
        return '0';

    }

    public function modul_chmod(){
         return [1,2,3];
    }

    public function version_up()
    {

        return array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "version", "modul" => "htmlredadmin"));

    }

    public function version()
    {

        return '7.58';

    }


    public function install()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "htmlredadmin")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "htmlredadmin")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "htmlredadmin")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "htmlredadmin")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "htmlredadmin")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "htmlredadmin")));
        return $connect;
    }

    public function install_m($data)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/htmlredadmin_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/htmlredadmin_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/htmlredadmin_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/css/style.css", $data["css"]);

    }

    public function up()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "htmlredadmin")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "htmlredadmin")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "htmlredadmin")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "htmlredadmin")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "htmlredadmin")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "htmlredadmin")));
        return $connect;
    }

    public function up_m($data)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/htmlredadmin_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/htmlredadmin_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/htmlredadmin_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/css/style.css", $data["css"]);
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