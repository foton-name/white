<?php

  class Upmodul_a extends Upmodul_c
{
    public function install_ms()
    {
        if (isset($this->request->p['mod'])) {
            $this->install_mod($this->request->p['mod']);
        }
    }

    public function up_ms()
    {
        if (isset($this->request->p['mod'])) {

            $this->update_mod($this->request->p['mod']);
        }
    }

    public function del_ms()
    {
        if (isset($this->request->p['mod'])) {
            $this->del_mod($this->request->p['mod']);
        }
    }


    public function vivodm_ajax()
    {
        $mod_array = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/*") as $filename) {
            $dir_m = basename($filename);
            $model_path = "dev/modul/" . $dir_m . "/install._!";
            require_once $filename . "/" . $dir_m . "_m.php";
            if (file_exists($model_path)) {
                $model_name = $dir_m . "_m";
                $model_class = new $model_name;
                $mod_array['name'][$dir_m] = $model_class->name_m();
                $mod_array['vr'][$dir_m] = $model_class->version();
                $mod_array['vr2'][$dir_m] = $this->version_mod($model_class->version_up());
                if ($model_class->version() < $this->version_mod($model_class->version_up())) {
                    $mod_array['up'][] = $dir_m;
                    $mod_array['vr2'][] = $this->version_mod($model_class->version_up());
                }
            }
        }
        if (isset($mod_array['up'])) {
            foreach ($mod_array['up'] as $k => $file_m) {
                echo "<tr><td><span class='nm'>" . $mod_array['name'][$file_m] . "</span> <span class='up-up' nm='" . $file_m . "'></span> - Версия " . $mod_array['vr'][$file_m] . " Обновить до Версии " . $mod_array['vr2'][$file_m] . "</td></tr>";
            }
        }

    }


}