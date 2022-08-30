<?php

  class htmlredadmin_c extends htmlredadmin_m
{
    public function nameshstr()
    {
        $arr = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlredadmin/template/" . $GLOBALS['foton_setting']['admindir'] . "/*.html") as $filename) {
            $f1 = basename($filename);
            $f2 = str_replace('.html', '', $f1);
            $arr[$f2] = $f1;
        }
        return $arr;
    }
}