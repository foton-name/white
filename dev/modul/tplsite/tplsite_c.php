<?php

  class Tplsite_c extends Tplsite_m
{

    public function arr_tpl()
    {
        $arr = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/*') as $files) {
            $name = basename($files);
            $desc = file_get_contents($files . '/site/desc.txt');
            $desc_def = file_get_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/desc.txt');
            if ($desc == $desc_def) {
                $desc = '<span class="def_s">' . $desc . '</span>';
            }
            $img = '/system/api/tplsite/' . $name . '/site/screen.png';
            $arr[$name][0] = $img;
            $arr[$name][1] = $desc;
        }

        return $arr;
    }
    public function arr_site()
    {
        $arr = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/*') as $files) {
            $name = basename($files);
            if($name!='template.php'){
                if(is_dir($files) && (!file_exists($files . '/interface.!')) && file_exists($files . '/desc.txt')){               
                    $desc = file_get_contents($files . '/desc.txt');
                    $desc_def = file_get_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/desc.txt');
                    if ($desc == $desc_def) {
                        $desc = '<span class="def_s">' . $desc . '</span>';
                    }
                    $files = str_replace($GLOBALS['foton_setting']['path'],'',$files);
                    $img = $files . '/screen.png';
                    $arr[$name][0] = $img;
                    $arr[$name][1] = $desc;
                }
            }
        }
        return $arr;
    }

}