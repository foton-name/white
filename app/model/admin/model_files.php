<?php   class Model_Files extends Model
{
    public function nameinclude()
    {
        return 'Файлы системы';

    }

    public function mass_files()
    {
        $arr = array("js" => "/app/view/" . $GLOBALS["foton_setting"]["sitedir"] . "/js/script.js", "css" => "/app/view/" . $GLOBALS["foton_setting"]["sitedir"] . "/css/style.css", "gl" => "/system/api/tpl/" . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_view.php', "head" => "/system/api/tpl/" . $GLOBALS["foton_setting"]["sitedir"] . "/head.php"
        , "foot" => "/system/api/tpl/" . $GLOBALS["foton_setting"]["sitedir"] . "/foot.php", "robot" => "/robots.txt");
        return $arr;


    }

    public function mass_sh_files()
    {
        $arr = array("js" => "0", "css" => "0", "gl" => "1", "head" => "1", "foot" => "1", "robot" => "0");
        return $arr;


    }

    public function Model_chmod(){
         return [1,2];

    }

}