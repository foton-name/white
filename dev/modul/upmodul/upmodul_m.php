<?php

  class Upmodul_m extends Model
{

    public $dir_m = 'upmodul';

    public function modul_chmod(){
         return [1,1];
    }

    public function name_m()
    {
        return 'Обновление модулей';

    }

    public function dir_m()
    {
        return '0';

    }


    public function update_modul($connect)
    {
        $out = array();
        foreach ($connect as $key_f => $f_sist) {
            if ($curl = curl_init()) {
                $str_p = array();
                foreach ($f_sist["date"] as $k => $v) {
                    $str_p[] = $k . "=" . $v;
                }
                $str_p_str = implode('&', $str_p);
                if ($f_sist['method'] == 'post') {
                    curl_setopt($curl, CURLOPT_URL, $f_sist['host']);

                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $str_p_str);
                    curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                } else {
                    curl_setopt($curl, CURLOPT_URL, $f_sist['host'] . '?' . $str_p_str);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    $out = curl_exec($curl);

                }
                $out[$key_f] = curl_exec($curl);

                curl_close($curl);
            }

        }
        return $out;
    }


    public function version_up()
    {

        return array("host" => "https://foton.name/api_f.ajaxsite", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "version", "modul" => "upmodul"));

    }

    public function version()
    {

        return '2';

    }


    public function install()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "upmodul")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "upmodul")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "upmodul")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "upmodul")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "upmodul")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "upmodul")));
        return $connect;
    }

    public function install_m($data)
    {

        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/upmodul_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/upmodul_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/upmodul_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/css/style.css", $data["css"]);
    }

    public function up()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "upmodul")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "upmodul")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "upmodul")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "upmodul")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "upmodul")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "upmodul")));
        return $connect;
    }

    public function up_m($data)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/upmodul_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/upmodul_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/upmodul_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/upmodul/css/style.css", $data["css"]);
    }

    public function del()
    {
        $connect = array("filest" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "delete")));
        return $connect;
    }

    public function del_m($data)
    {
//	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/upmodul/del.php",$data["filest"]);
    }

}