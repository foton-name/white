<?php

  class Git_c extends Git_m
{


    public function sql_up()
    {
        if (isset($_SESSION['up_sql']) && isset($_SESSION['usergit']) && $_SESSION['up_sql'] == 'yes') {
            $file = $GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/dump.sql';
            $this->core->sql_insert($file);
            unset($_SESSION['gittest']);
            unset($_SESSION['up_sql']);
            $_SESSION['login'] = $_SESSION['usergit'];
            unset($_SESSION['usergit']);
            unlink($file);
            header("Location: /git.modul");
        }
    }

    public function users()
    {
        $role = $this->core->table('role')->where("`name`='5'")->forq()[0]["text"];

        $arr = $this->core->table('user_inc')->where("`role`='" . $role . "'")->forq();

        return $arr;
    }

    public function filesp()
    {

        if (isset($this->request->p['type']) && $this->request->p['type'] == 'sp') {
            $arr = $this->spisokfile($this->git($this->request->p['path'] . '/'));
            for ($i = 0; $i < count($arr); $i++) {
                if (stristr($arr[$i]['name'], '.') !== FALSE) {
                    $rand = rand(0, 500); ?>
                    <p class='file<?= $i . $rand; ?>'><span class='del-f' ids='<?= $i . $rand; ?>'
                                                            path='<?= $arr[$i]['path']; ?>'></span><span
                                class='name-f names-f' path='<?= $arr[$i]['path']; ?>'><?= $arr[$i]['name']; ?></span>
                    </p>
                <?
                } else {
                    $rand = rand(0, 500); ?>
                    <p class='file<?= $i . $rand; ?>'><span class='del-f' ids='<?= $i . $rand; ?>'
                                                            path='<?= $arr[$i]['path']; ?>'></span><span
                                class='name-f dir-f' ids='<?= $i . $rand; ?>'
                                path='<?= $arr[$i]['path']; ?>'><?= $arr[$i]['name']; ?></span></p>
                    <div class='dir<?= $i . $rand; ?> dirt'></div>
                <?
                }
            }
        }
    }

}