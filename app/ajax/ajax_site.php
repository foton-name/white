<?php

  class Ajax_site extends Ajax_site_m
{
    public function __call($methodf, $arguments)
    {
        
        if (file_exists($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/ajax/view/' . $methodf . '.php')) {
            if (file_exists($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/ajax/class/' . $methodf . '.php')) {
                require_once($this->core->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/ajax/class/' . $methodf . '.php'));
                $classf = 'CAjax_' . $methodf;
                $objf = new $classf;
                foreach (get_class_methods($classf) as $valf) {
                    if (method_exists($classf, $valf) !== false) {
                        $data[$valf] = $objf->$valf();
                    }
                }
            }
            if(isset($arguments[0]['global'])){
                $global = $arguments[0]['global'];
            }
            include($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/ajax/view/' . $methodf . '.php');
        } else {
            return 'no_file';
        }
    }

    public function autorizajax()
    {
        if (isset($this->request->p['login']) && isset($this->request->p['pass']) && isset($this->request->p['key'])) {
            echo $this->core->auth($this->request->p['login'], $this->request->p['pass'], $this->request->p['key']);
        } else if (isset($this->request->p['key2'])) {
            echo $this->core->auth(null, null, null, $this->request->p['key2']);
        } else {
            echo $this->core->auth();
        }
    }

    public function siteajax()
    {

        if (isset($this->request->p['controller']) && isset($this->request->p['method'])) {
            $ret = 'yes';
            $method = $this->request->p['method'];
            $pos = strripos($method, 'func_pub');
            if ($pos !== false) {
                if (file_exists("app/model/" . $GLOBALS['foton_setting']['sitedir'] . "/model_" . $this->request->p['controller'] . ".php")) {
                    require_once "app/model/" . $GLOBALS['foton_setting']['sitedir'] . "/model_" . $this->request->p['controller'] . ".php";
                } else if (file_exists("app/model/" . $GLOBALS['foton_setting']['admindir'] . "/model_" . $this->request->p['controller'] . ".php")) {
                    require_once "app/model/" . $GLOBALS['foton_setting']['admindir'] . "/model_" . $this->request->p['controller'] . ".php";
                } else {
                    $ret = 'no';
                }
                if (file_exists("app/controller/" . $GLOBALS['foton_setting']['sitedir'] . "/controller_" . $this->request->p['controller'] . ".php")) {
                    require_once "app/controller/" . $GLOBALS['foton_setting']['sitedir'] . "/controller_" . $this->request->p['controller'] . ".php";
                } else if (file_exists("app/controller/" . $GLOBALS['foton_setting']['admindir'] . "/controller_" . $this->request->p['controller'] . ".php")) {
                    require_once "app/controller/" . $GLOBALS['foton_setting']['admindir'] . "/controller_" . $this->request->p['controller'] . ".php";
                } else {
                    $ret = 'no';
                }
                if ($ret == 'yes') {

                    $mod_name = '\controller_' . $this->request->p['controller'];
                    $mod = 'odj_model';
                    ${$mod} = new $mod_name;
                    echo ${$mod}->$method();
                }
            }
        }
    }

    public function siteajax_vardump()
    {
        if (isset($this->request->p['controller']) && isset($this->request->p['method'])) {
            $method = $this->request->p['method'];
            $pos = strripos($method, 'func_pub');
            if ($pos !== false) {
                require_once "app/model/" . $GLOBALS['foton_setting']['sitedir'] . "/model_" . $this->request->p['controller'] . ".php";
                require_once "app/controller/" . $GLOBALS['foton_setting']['sitedir'] . "/controller_" . $this->request->p['controller'] . ".php";
                $mod_name = '\controller_' . $this->request->p['controller'];
                $mod = 'odj_model';
                ${$mod} = new $mod_name;
                var_dump(${$mod}->$method());
            }
        }
    }

}