<?php

  class Tpladmin_c extends Tpladmin_m
{

    public function mass_sh()
    {
        $arr = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/*') as $files) {
            $name = basename($files);
            $desc = file_get_contents($files . '/admin/desc.txt');
            $desc_def = file_get_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/desc.txt');
            if ($desc == $desc_def) {
                $desc = '<span class="def_s">' . $desc . '</span>';
            }
            $img = '/system/api/tpladmin/' . $name . '/admin/screen.png';
            $arr[$name][0] = $img;
            $arr[$name][1] = $desc;
        }

        return $arr;
    }
    public function arr_admin()
    {
        $arr = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/*') as $files) {
            if(is_dir($files) && (file_exists($files . '/interface.!')) && file_exists($files . '/desc.txt')){
                $name = basename($files);
                $desc = file_get_contents($files . '/desc.txt');
                $desc_def = file_get_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/desc.txt');
                if ($desc == $desc_def) {
                    $desc = '<span class="def_s">' . $desc . '</span>';
                }
                $files = str_replace($GLOBALS['foton_setting']['path'],'',$files);
                $img = $files . '/screen.png';
                $arr[$name][0] = $img;
                $arr[$name][1] = $desc;
            }
        }
        return $arr;
    }

}