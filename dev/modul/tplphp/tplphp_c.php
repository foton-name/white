<?php

  class tplphp_c extends Tplphp_m
{
    public function up_create_shablon()
    {
        if (isset($this->request->p['hidfunc'])) {
            $this->full_del_dir($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['hidfunc']);
            if (isset($this->request->p['del']) && $this->request->p['del'] == 'on') {

            } else {
                if ($this->request->p['var'] != '') {
                    $this->request->p['var'] = str_replace(',', ',$', $this->request->p['var']);
                    $pred = '<? function ' . $this->request->p['func'] . '($' . $this->request->p['var'] . '){';
                    $vart = '$' . $this->request->p['var'];
                } else {
                    $pred = '<?';
                    $vart = '';
                }
                for ($it = 0; $it < 30; $it++) {
                    if (isset($this->request->p['poleii' . $it])) {
                        if ($it == '1') {
                            mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'], 0777);
                            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'] . '/' . $this->request->p['poleii' . $it] . '.php', $pred . $this->request->p['fileii' . $it] . '?>');
                        } else {
                            if (!is_dir($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'])) {
                                mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'], 0777);
                            }
                            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'] . '/' . $this->request->p['poleii' . $it] . '.php', '<?' . $this->request->p['fileii' . $it] . '?>');
                        }
                    }
                }
                file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'] . '/perem.txt', $vart);
            }
        } elseif (isset($this->request->p['func'])) {
            if ($this->request->p['var'] != '') {
                $this->request->p['var'] = str_replace(',', ',$', $this->request->p['var']);
                $pred = '<? function ' . $this->request->p['func'] . '($' . $this->request->p['var'] . '){';
                $vart = '$' . $this->request->p['var'];
            } else {
                $pred = '<?';
                $vart = '';
            }
            for ($it = 0; $it < 30; $it++) {
                if (isset($this->request->p['poleii' . $it])) {
                    if (!file_exists($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'])) {
                        mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'], 0777);
                    }
                    if ($it == '0') {
                        file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'] . '/' . $this->request->p['poleii' . $it] . '.php', $pred . $this->request->p['fileii' . $it] . '?>');
                    } else {
                        file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'] . '/' . $this->request->p['poleii' . $it] . '.php', '<?' . $this->request->p['fileii' . $it] . '?>');
                    }
                }
            }
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $this->request->p['func'] . '/perem.txt', $vart);
        } else {

        }
    }

    public function shablonphp()
    {
        return $this->shablon_php();
    }

    public function shablonphp2()
    {
        return $this->shablon_php2();

    }
}






