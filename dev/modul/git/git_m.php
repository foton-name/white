<?php

  class Git_m extends Model
{


    public function modul_chmod(){
         return [1,5];
    }

    public function gitm($path = null)
    {
        $path = str_replace($GLOBALS["foton_setting"]["path"] . "/", '', $path);
        if ($_SESSION['login'] && file_exists($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/work/' . $path)) {
            return $GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/work/' . $path;

        } else {
            return false;

        }
    }

    public function gitm2($path = null, $user = null)
    {
        if ($user != null) {
            $path = str_replace(".gitf/" . $user . "/work/", '', $path);
            $path = preg_replace("#\.gitf/" . $user . "/release/([^/]+)/#", '', $path);
        } else {
            $path = str_replace(".gitf/" . $_SESSION['login'] . "/work/", '', $path);
            $path = preg_replace("#\.gitf/" . $_SESSION['login'] . "/release/([^/]+)/#", '', $path);
        }
        return $path;
    }

    public function name_m()
    {
        return 'Версии';

    }

    public function dir_m()
    {
        return '0';

    }

    public function spisokfile($path)
    {
        $arr = array();
        foreach (glob($path . "*") as $filename) {
            if (basename($filename) != 'core') {
                if (filesize($filename) < 100000) {
                    $arr[] = array('path' => $filename, 'name' => basename($filename));
                }
            }
        }
        return $arr;
    }


    public function version_up()
    {

        return array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "version", "modul" => "git"));

    }

    public function version()
    {

        return '3';

    }


    public function install()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "git")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "git")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "git")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "git")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "git")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "git")));
        return $connect;
    }

    public function install_m($data)
    {

        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/git_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/git_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/git_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/css/style.css", $data["css"]);
    }

    public function up()
    {
        $connect = array("index" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updateindex", "modul" => "git")),
            "m" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatem", "modul" => "git")),
            "c" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatec", "modul" => "git")),
            "a" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatea", "modul" => "git")),
            "css" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatecss", "modul" => "git")),
            "js" => array("host" => "https://foton.name/api_f.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "updatejs", "modul" => "git")));
        return $connect;
    }

    public function up_m($data)
    {
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/index.php", $data["index"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/git_m.php", $data["m"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/git_c.php", $data["c"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/git_a.php", $data["a"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/js/script.js", $data["js"]);
        file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/git/css/style.css", $data["css"]);
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