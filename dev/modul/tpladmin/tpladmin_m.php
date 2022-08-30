<?php

  class Tpladmin_m extends Model
{

    public $dir_m = 'upmodul';

    public function modul_chmod(){
         return [1,1];
    }

    public function name_m()
    {
        return 'Интерфейсы';

    }

    public function dir_m()
    {
        return '0';

    }

    public function version_up()
    {

        return array("host" => "https://foton.name/api_f.ajaxsite", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "version", "modul" => "tpladmin"));

    }

    public function version()
    {

        return '2.5';

    }


    public function install()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "tpladmin")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "tpladmin")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "tpladmin")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "tpladmin")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "tpladmin")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "tpladmin")));
        return $connect;
    }

    public function install_m($data)
    {

        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/tpladmin_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/tpladmin_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/tpladmin_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/css/style.css", $data["css"]);
    }

    public function up()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "tpladmin")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "tpladmin")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "tpladmin")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "tpladmin")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "tpladmin")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "tpladmin")));
        return $connect;
    }

    public function up_m($data)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/tpladmin_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/tpladmin_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/tpladmin_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/tpladmin/css/style.css", $data["css"]);
    }

    public function del()
    {
        $connect = array("filest" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "delete")));
        return $connect;
    }

    public function del_m($data)
    {
        //file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/upmodul/del.php",$data["filest"]);
    }

}