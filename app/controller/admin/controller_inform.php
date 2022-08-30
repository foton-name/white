<?php   class Controller_inform extends Model_inform
{


    public function func_pub_ajax()
    {
        if (isset($this->request->p['test'])) {
            return $this->request->p['test'];
        }

    }

    public function typeuser(){
        $arr = $this->core->getlist(['role','where'=>['name'=>$_SESSION['chmod_id']]]);
        return $arr[0]['text'];
    }

    public function spbackup()
    {
        $stat = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/" . $GLOBALS["foton_setting"]["backup"] . "/*.zip") as $file) {
            $stat[] = basename($file);
        }
        return $stat;
    }

    public function grafiki_include()
    {
        return $this->grafik_include();
    }

    public function sizefile_c()
    {
        $ms = array();
        $mod = $this->core->sizefile('app/model/' . $GLOBALS['foton_setting']['sitedir']) + $this->core->sizefile('app/model/' . $GLOBALS['foton_setting']['admindir']);
        $cont = $this->core->sizefile('app/controller/' . $GLOBALS['foton_setting']['sitedir']) + $this->core->sizefile('app/controller/' . $GLOBALS['foton_setting']['admindir']);
        $view = $this->core->sizefile('app/view/' . $GLOBALS['foton_setting']['sitedir']) + $this->core->sizefile('app/view/' . $GLOBALS['foton_setting']['admindir']);
        $x = $mod + $cont + $view;
        $ms[] = 100 * $mod / $x;
        $ms[] = 100 * $view / $x;
        $ms[] = 100 * $cont / $x;

        return $ms;
    }

    public function passints()
    {
        global $date;
        global $date2;
        require_once($GLOBALS['foton_setting']['path'] . '/system/admin-inc/key.php');
        $pass1 = strlen($date);
        $pass1n = preg_replace('#([a-zA-Z]+)#', '', $date);
        if ($pass1 > 20 && $pass1n != '') {
            $arr['pass1'] = 'Хороший';
        } else if (($pass1 > 20 && $pass1n == '') || ($pass1 < 20 && $pass1n != '')) {
            $arr['pass1'] = 'Средний';
        } else if ($pass1 < 20 && $pass1n != '') {
            $arr['pass1'] = 'Слабый';
        } else {
            $arr['pass1'] = 'Слабый';
        }
        $pass1 = strlen($date2);
        $pass1n = preg_replace('#([a-zA-Z]+)#', '', $date2);
        if ($pass1 > 20 && $pass1n != '') {
            $arr['pass2'] = 'Хороший';
        } else if (($pass1 > 20 && $pass1n == '') || ($pass1 < 20 && $pass1n != '')) {
            $arr['pass2'] = 'Средний';
        } else if ($pass1 < 20 && $pass1n == '') {
            $arr['pass2'] = 'Слабый';
        } else {
            $arr['pass2'] = 'Слабый';
        }
        return $arr;
    }

    public function list_files($site = null, $admin = null)
    {
        $m = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $site) . "/*.php"));
        $v = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/view") . "/*.php"));
        $av = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/view/" . $admin) . "/*.php"));
        $vv = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/view/" . $site) . "/*.php"));
        $c = count(glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/controller/" . $site) . "/*.php"));
        return $m . ',' . $v . ',' . $av . ',' . $vv . ',' . $c;
    }

    public function fileinfo($dir = null, $dir2 = null)
    {
        $stat = array();
        foreach (glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/" . $dir . "/" . $dir2) . "/*.php") as $file) {
            $stat0 = stat($file);
            $stat0['name'] = basename($file);
            $stat[] = $stat0;
        }
        foreach (glob($this->core->git($GLOBALS["foton_setting"]["path"] . "/app/" . $dir . "/" . $dir2) . "/*.tpl") as $file) {
            $stat0 = stat($file);
            $stat0['name'] = basename($file);
            $stat[] = $stat0;
        }
        return $stat;
    }


}