<?php   class Controller_workarea extends Model_workarea
{

    public function exits(){
        if(isset($this->request->p['Foton-key']) && $this->request->p['Foton-key']=='exit'){
            return $this->core->exitAdmin();
        }
    }
    
    public function typeuser(){
        $arr = $this->core->getlist(['role','where'=>['name'=>$_SESSION['chmod_id']]]);
        return $arr[0]['text'];
    }

    public function background_user()
    {
        if (isset($_SESSION['login'])) {
            if (file_exists($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/css/' . $_SESSION['login'] . 'background.css')) {
                return '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/css/' . $_SESSION['login'] . 'background.css';
            } else {
                return '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/css/background.css';
            }
        }
    }

    public function global_menu()
    {
        $menu = array("/inform/" => "Информация о системе", "/files/gl/" => "Главная страница", "/files/css/" => "CSS сайта", "/files/js/" => "JS сайта", "/files/head/" => "Шапка сайта", "/files/foot/" => "Футер сайта", "/files/robot/" => "Robots");
        return $menu;
    }

    public function glob_menu()
    {
        if (isset($_SESSION['chmod_id'])) {
            $arrmod = glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/dev/modul") . "/*");
            if (is_array($arrmod) && count($arrmod) > 0) {
                $menu = array();
                foreach ($arrmod as $filename) {
                    $mod = basename($filename);
                    require_once $this->core->git($filename . "/" . $mod . '_m.php');
                    if (file_exists($this->core->git($filename . "/install._!"))) {
                        $mod_m = $mod . '_m';
                        $mod_mx = new $mod_m;
                        $name = $mod_mx->name_m();
                        if (method_exists($mod_m, 'modul_chmod') !== false) {
                            $chmod = $mod_mx->modul_chmod();
                            if ($this->core->chmod_id($chmod)) {
                                $menu["/" . $mod . ".modul"] = $name;
                            }
                        } else {
                            $menu["/" . $mod . ".modul"] = $name;
                        }
                    }
                }

                return $menu;
            }
        }
    }

    public function files()
    {
        return $this->core->list_files_glob();
    }

    public function cssmenu($key = null)
    {
        if ($key != null) {

            if (count($this->list_w($key)) == '0') {
                return 'display:none;';

            } else {
                return ' ';
            }
        }
    }

    public function list_w($model = null)
    {
        if (isset($this->request->g['2']) && $this->request->g['2'] != '' && $model == '1') {
            $model = $this->request->g['2'];
            return $this->core->list_model_glob($model);
        } else if ($model != '1' && isset($model)) {
            return $this->core->list_model_glob($model);
        } else {
            return false;
        }
    }

    public function list_files($site = null, $admin = null)
    {
        if ($site != null && $admin != null) {
            $m = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $site) . "/*.php"));
            $ma = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $admin) . "/*.php"));
            $c = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/controller/" . $site) . "/*.php"));
            $ca = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/controller/" . $admin) . "/*.php"));
            $va = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/view/" . $admin) . "/*.php"));
            $v = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/view/" . $site) . "/*.php"));

            return $m . ',' . $ma . ',' . $c . ',' . $ca . ',' . $v . ',' . $va;
        }
    }

    public function month_sess()
    {
        $arr = array('no', 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
        $arr2 = array();
        foreach ($arr as $key => $val) {
            if ($key != 'no') {
                if ($key == date("n")) {
                    $arr2[$key . '" selected'] = $val;
                } else {
                    $arr2[$key . '"'] = $val;
                }
            }

        }
        return $arr2;
    }

}