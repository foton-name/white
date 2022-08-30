<?php

  class Htmlred_c extends Htmlred_m
{
    public function nameshstr()
    {
        $arr = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/htmlred/template/" . $GLOBALS['foton_setting']['sitedir'] . "/*.html") as $filename) {
            $f1 = basename($filename);
            $f2 = str_replace('.html', '', $f1);
            $arr[$f2] = $f1;
        }
        return $arr;
    }
}