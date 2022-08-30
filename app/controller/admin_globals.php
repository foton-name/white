<?php

  class Model_Globals
{


}

class Controller_Globals extends Model_Globals
{
    public $core;

    public function __construct()
    {
        global $core; $this->core = $core;
    }


    public function insert_logs()
    {
        if (isset($this->core->request->p) && isset($this->core->request->s['login'])) {
            if (!file_exists($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->core->request->s['login'])) {
                mkdir($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->core->request->s['login'], 0777);
            }
            if (!file_exists($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->core->request->s['login'] . '/' . date("j") . '-' . date("n") . '-' . date("Y"))) {
                mkdir($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->core->request->s['login'] . '/' . date("j") . '-' . date("n") . '-' . date("Y"), 0777);
            }
            foreach ($this->core->request->p as $val => $val2) {
                if (strlen($val2) >= $GLOBALS['foton_setting']['sizelog'] && ($GLOBALS['foton_setting']['value_log'] == '' || in_array($val, $GLOBALS['foton_setting']['value_log']))) {
                    $routes = explode('/', $_SERVER['REQUEST_URI']);
                    if (isset($routes[2])) {
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->core->request->s['login'] . '/' . date("j") . '-' . date("n") . '-' . date("Y") . '/' . $routes[2] . '_' . stripslashes($val) . '_' . date("H_i_s") . '.php', $this->core->request->p[$val]);
                    } else {
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->core->request->s['login'] . '/' . date("j") . '-' . date("n") . '-' . date("Y") . '/_' . stripslashes($val) . '_' . date("H_i_s") . '.php', $this->core->request->p[$val]);
                    }
                }
            }
        }
    }

    public function timesc()
    {
        return round(microtime(true) - $GLOBALS['foton_setting']['time'], 3);
    }

}
