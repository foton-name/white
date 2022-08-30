<?php

  class Upmodul_c extends Upmodul_m
{
    public function install_mod_all()
    {
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/*") as $filename) {
            $dir_m = basename($filename);
            $model_path = "dev/modul/" . $dir_m . "/install._!";
            if (!file_exists($model_path)) {
                $model_name = $dir_m . '_m';
                require_once $filename . "/" . $dir_m . "_m.php";
                $model_class = new $model_name;
                if (method_exists($model_name, 'install') !== false) {
                    $connect = $model_class->install();
                    $data_up = $this->update_modul($connect);
                    if (method_exists($model_name, 'install_m') !== false) {
                        $model_class->install_m($data_up);
                    }
                }

                file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/" . $dir_m . "/install._!", '');
            }
        }
    }

    public function install_mod($mod)
    {
        if (isset($mod)) {
            $filename = $GLOBALS["foton_setting"]["path"] . "/dev/modul/" . $mod;
            $dir_m = $mod;
            $model_path = $GLOBALS["foton_setting"]["path"] . "/dev/modul/" . $dir_m . "/install._!";
            if (!file_exists($model_path)) {
                $model_name = $dir_m . '_m';
                require_once $filename . "/" . $dir_m . "_m.php";
                $model_class = new $model_name;
                if (method_exists($model_name, 'install') !== false) {
                    $connect = $model_class->install();
                    $data_up = $this->update_modul($connect);
                    if (method_exists($model_name, 'install_m') !== false) {
                        $model_class->install_m($data_up);
                    }
                }

                file_put_contents($GLOBALS["foton_setting"]["path"] . "/dev/modul/" . $dir_m . "/install._!", '');

            }

        }
    }


    public function update_mod_all()
    {

        foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/*") as $filename) {
            $dir_m = basename($filename);
            $model_path = "dev/modul/" . $dir_m . "/install._!";
            if (file_exists($model_path)) {
                $model_name = $dir_m . '_m';
                require_once $filename . "/" . $dir_m . "_m.php";
                $model_class = new $model_name;
                if (method_exists($model_name, 'up') !== false) {
                    $connect = $model_class->up();
                    $data_up = $this->update_modul($connect);
                    if (method_exists($model_name, 'up_m') !== false) {
                        $model_class->up_m($data_up);
                    }
                }
            }
        }
    }

    public function update_mod($mod)
    {
        if (isset($mod)) {

            $filename = $GLOBALS["foton_setting"]["path"] . "/dev/modul/" . $mod;
            $dir_m = $mod;
            $model_path = "dev/modul/" . $dir_m . "/install._!";
            if (file_exists($model_path)) {
                $model_name = $dir_m . '_m';
                require_once $filename . "/" . $dir_m . "_m.php";
                $model_class = new $model_name;
                if (method_exists($model_name, 'up') !== false) {
                    $connect = $model_class->up();
                    $data_up = $this->update_modul($connect);
                    if (method_exists($model_name, 'up_m') !== false) {
                        $model_class->up_m($data_up);
                    }
                }
            }

        }
    }


    public function del_mod_all()
    {
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/*") as $filename) {
            $dir_m = basename($filename);
            $model_path = "dev/modul/" . $dir_m . "/install._!";
            if (file_exists($model_path)) {
                $model_name = $dir_m . '_m';
                require_once $filename . "/" . $dir_m . "_m.php";
                $model_class = new $model_name;
                if (method_exists($model_name, 'del') !== false) {
                    $connect = $model_class->del();
                    $data_up = $this->update_modul($connect);
                    if (method_exists($model_name, 'del_m') !== false) {
                        $model_class->del_m($data_up);
                    }
                }
                unlink($GLOBALS["foton_setting"]["path"] . "/dev/modul/" . $dir_m . "/install._!");
            }
        }
    }


    public function del_mod($mod)
    {
        if (isset($mod)) {

            $filename = $GLOBALS["foton_setting"]["path"] . "/dev/modul/" . $mod;
            $dir_m = $mod;
            $model_path = "dev/modul/" . $dir_m . "/install._!";
            if (file_exists($model_path)) {
                $model_name = $dir_m . '_m';
                require_once $filename . "/" . $dir_m . "_m.php";
                $model_class = new $model_name;
                if (method_exists($model_name, 'del') !== false) {
                    $connect = $model_class->del();
                    $data_up = $this->update_modul($connect);
                    if (method_exists($model_name, 'del_m') !== false) {
                        $model_class->del_m($data_up);
                    }
                }
                unlink($GLOBALS["foton_setting"]["path"] . "/dev/modul/" . $dir_m . "/install._!");
            }
        }
    }


    public function vivodm()
    {
        $mod_array = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/dev/modul/*") as $filename) {
            $dir_m = basename($filename);
            $model_path = "dev/modul/" . $dir_m . "/install._!";
            require_once $filename . "/" . $dir_m . "_m.php";

            $model_name = $dir_m . "_m";
            $model_class = new $model_name;
            $mod_array['name'][$dir_m] = $model_class->name_m();
            if (file_exists($model_path)) {
                $mod_array['uinst'][] = $dir_m;
            } else {
                $mod_array['inst'][] = $dir_m;

            }
        }
        return $mod_array;
    }


    public function version_mod($conn)
    {
        if (function_exists('curl_init')) {
            if ($curl = curl_init()) {
                $str_p = array();
                foreach ($conn["date"] as $k => $v) {
                    $str_p[] = $k . "=" . $v;
                }
                $str_p_str = implode('&', $str_p);
                curl_setopt($curl, CURLOPT_URL, $conn['host']);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $str_p_str);
                curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $res = curl_exec($curl);
                curl_close($curl);
                return $res;
            }
        }
    }

}