<?php

  class Tpladmin_a extends Tpladmin_c
{
    public function del_sh()
    {
        if (isset($this->request->p['sh'])) {
            return $this->core->dir_delete_foton($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $this->request->p['sh']);
        }
    }
    public function copy_admin()
    {
        if (isset($this->request->p['sh']) && isset($this->request->p['site']) && $this->request->p['site']!='') {
            $dir_tpl = $this->request->p['sh'];
            $arr_create = array(
                '/dev/modul/htmlredadmin/template/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/htmlredsh',
                '/dev/modul/htmlredadmin/filest/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/filest',
                '/dev/modul/htmlredadmin/admin/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/htmladmin',
                '/app/view/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/admin',
                '/app/model/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/model',
                '/app/controller/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/controller',
                '/system/api/tpl/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/admintpl',
                '/app/ajax/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/face',
                '/system/api/php/' . $this->request->p['site'] => '/system/api/tpladmin/' . $dir_tpl . '/php',
            );
            if(file_exists($GLOBALS['foton_setting']['path'].'/app/lang/'.$this->request->p['site']) && file_exists($GLOBALS['foton_setting']['path'].'/system/api/tpladmin/'.$dir_tpl.'/lang')){
                $arr_create['/app/lang/'.$this->request->p['site']]='/system/api/tpladmin/'.$dir_tpl.'/lang';
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/admin/' . $this->request->p['site'] . '/*.css') as $filename) {
                unlink($filename);
            }
            foreach ($arr_create as $dir0 => $dir1) {
                if (file_exists($GLOBALS["foton_setting"]["path"] . $dir1)) {
                    $this->core->dir_delete_foton($GLOBALS["foton_setting"]["path"] . $dir0);
                    mkdir($GLOBALS["foton_setting"]["path"] . $dir0, 0775);
                    $this->core->copy_dir($GLOBALS["foton_setting"]["path"] . $dir1, $GLOBALS["foton_setting"]["path"] . $dir0);
                }
            }
             foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $dir_tpl . '/htmlred/style_sh*') as $filename) {
                $file = basename($filename);
                copy($filename, $GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/' . $file);
            }
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $dir_tpl . '/ajax/ajax_admin_m.php', $GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $this->request->p['site'] . '_m.php');
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $dir_tpl . '/ajax/ajax_admin.php', $GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $this->request->p['site'] . '.php');
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $dir_tpl . '/admin_globals.php', $GLOBALS["foton_setting"]["path"] . '/app/controller/' . $this->request->p['site'] . '_globals.php');

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/template/' . $this->request->p['site'] . '/*.html') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlredadmin/filest/([^/]+)/#', '/dev/modul/htmlredadmin/filest/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/admin/' . $this->request->p['site'] . '/*.css') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlredadmin/filest/([^/]+)/#', '/dev/modul/htmlredadmin/filest/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $this->request->p['site'] . '/*.php') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/admin/([^/]+)/#', '/dev/modul/htmlred/admin/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $this->request->p['site'] . '/*.tpl') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/admin/([^/]+)/#', '/dev/modul/htmlred/admin/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $this->request->p['site'] . '/*.php') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/admin/([^/]+)/#', '/dev/modul/htmlred/admin/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }
        }
    }
    public function del_admin()
    {
        if (isset($this->request->p['sh'])) {
            $dir_tpl = $this->request->p['sh'];
            $arr_del = array(
                '/dev/modul/htmlredadmin/template/' . $dir_tpl,
                '/dev/modul/htmlredadmin/filest/' . $dir_tpl,
                '/dev/modul/htmlredadmin/admin/' . $dir_tpl,
                '/app/view/' . $dir_tpl,
                '/app/model/' .$dir_tpl,
                '/app/controller/' .$dir_tpl,
                '/system/api/tpl/' . $dir_tpl,
                '/app/ajax/' . $dir_tpl,
                '/system/api/php/' . $dir_tpl
            );
            if(file_exists($GLOBALS['foton_setting']['path'].'/app/lang/'.$dir_tpl)){
                $arr_del[]='/app/lang/'.$dir_tpl;
            }
            foreach ($arr_del as $dir1) {
                if (file_exists($GLOBALS["foton_setting"]["path"] . $dir1)) {
                    $this->core->dir_delete_foton($GLOBALS["foton_setting"]["path"] . $dir1);
                }
            }
            unlink($GLOBALS["foton_setting"]["path"] . '/app/controller/' . $dir_tpl . '_globals.php');
            unlink($GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $dir_tpl . '.php');
            unlink($GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $dir_tpl . '_m.php');
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/template/' . $dir_tpl . '/*.html') as $filehtml) {
                unlink($filehtml);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/admin/' . $dir_tpl . '/*.css') as $filename) {
                unlink($filename);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/filest/' . $dir_tpl . '/*') as $filename) {
                unlink($filename);
            }
        }
    }
    public function ust_sh()
    {
        if (isset($this->request->p['sh'])) {
            $dir_tpl = $this->request->p['sh'];
            $arr_create = array(
                '/dev/modul/htmlredadmin/template/' . $GLOBALS['foton_setting']['admindir'] => '/system/api/tpladmin/' . $dir_tpl . '/htmlredsh',
                '/dev/modul/htmlredadmin/filest/' . $GLOBALS['foton_setting']['admindir'] => '/system/api/tpladmin/' . $dir_tpl . '/filest',
                '/app/view/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $dir_tpl . '/admin',
                '/app/model/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $dir_tpl . '/model',
                '/app/controller/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $dir_tpl . '/controller',
                '/system/api/tpl/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $dir_tpl . '/admintpl',
                '/app/ajax/' . $GLOBALS['foton_setting']['admindir'] => '/system/api/tpladmin/' . $dir_tpl . '/face',
                '/system/api/php/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $dir_tpl . '/php',
            );
            if(file_exists($GLOBALS['foton_setting']['path'].'/app/lang/'.$GLOBALS['foton_setting']['admindir']) && file_exists($GLOBALS['foton_setting']['path'].'/system/api/tpladmin/'.$dir_tpl.'/lang')){
                $arr_create['/app/lang/'.$GLOBALS['foton_setting']['admindir']]='/system/api/tpladmin/'.$dir_tpl.'/lang';
            }
            foreach ($arr_create as $dir0 => $dir1) {
                $this->core->dir_delete_foton($GLOBALS["foton_setting"]["path"] . $dir0);
                mkdir($GLOBALS["foton_setting"]["path"] . $dir0, 0775);
                $this->core->copy_dir($GLOBALS["foton_setting"]["path"] . $dir1, $GLOBALS["foton_setting"]["path"] . $dir0);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $dir_tpl . '/htmlred/style_sh*') as $filename) {
                $file = basename($filename);
                copy($filename, $GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/' . $file);
            }
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $dir_tpl . '/ajax/ajax_admin_m.php', $GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $GLOBALS['foton_setting']['admindir'] . '_m.php');
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $dir_tpl . '/ajax/ajax_admin.php', $GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $GLOBALS['foton_setting']['admindir'] . '.php');
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $dir_tpl . '/admin_globals.php', $GLOBALS["foton_setting"]["path"] . '/app/controller/' . $GLOBALS['foton_setting']['admindir'] . '_globals.php');

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/template/' . $GLOBALS['foton_setting']['admindir'] . '/*.html') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlredadmin/filest/([^/]+)/#', '/dev/modul/htmlredadmin/filest/' . $GLOBALS['foton_setting']['admindir'] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/admin/' . $GLOBALS['foton_setting']['admindir'] . '/*.css') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlredadmin/filest/([^/]+)/#', '/dev/modul/htmlredadmin/filest/' . $GLOBALS['foton_setting']['admindir'] . '/', $html);
                file_put_contents($filehtml, $html);
            }

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/*.php') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/admin/([^/]+)/#', '/dev/modul/htmlred/admin/' . $GLOBALS["foton_setting"]["admindir"] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/*.tpl') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/admin/([^/]+)/#', '/dev/modul/htmlred/admin/' . $GLOBALS["foton_setting"]["admindir"] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $GLOBALS['foton_setting']['admindir'] . '/*.php') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/admin/([^/]+)/#', '/dev/modul/htmlred/admin/' . $GLOBALS["foton_setting"]["admindir"] . '/', $html);
                file_put_contents($filehtml, $html);
            }


        }
    }

    public function sozd_sh()
    {
        $rand_dir = rand(10, 100) . rand(10, 100) . rand(10, 100);
        $arr_create = array(
            '/dev/modul/htmlredadmin/template/' . $GLOBALS['foton_setting']['admindir'] => '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/htmlredsh',
            '/dev/modul/htmlredadmin/filest/' . $GLOBALS['foton_setting']['admindir'] => '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/filest',
            '/app/view/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/admin',
            '/app/model/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/model',
            '/app/controller/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/controller',
            '/system/api/tpl/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/admintpl',
            '/app/ajax/' . $GLOBALS['foton_setting']['admindir'] => '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/face',
            '/system/api/php/' . $GLOBALS["foton_setting"]["admindir"] => '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/php',
        );
        if(file_exists($GLOBALS['foton_setting']['path'].'/app/lang/'.$GLOBALS['foton_setting']['admindir'])){
            $arr_create['/app/lang/'.$GLOBALS['foton_setting']['admindir']]='/system/api/tpladmin/'.$GLOBALS["foton_setting"]["admindir"] . $rand_dir.'/lang';
        }
        mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir, 0775);
        mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/htmlred', 0775);
        mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/ajax', 0775);
        foreach ($arr_create as $dir0 => $dir1) {
            mkdir($GLOBALS["foton_setting"]["path"] . $dir1, 0775);
            $this->core->copy_dir($GLOBALS["foton_setting"]["path"] . $dir0, $GLOBALS["foton_setting"]["path"] . $dir1);
        }
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlredadmin/style_sh*') as $filename) {
            $file = basename($filename);
            copy($filename, $GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/htmlred/' . $file);
        }
        copy($GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $GLOBALS['foton_setting']['admindir'] . '_m.php', $GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/ajax/ajax_admin_m.php');
        copy($GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $GLOBALS['foton_setting']['admindir'] . '.php', $GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/ajax/ajax_admin.php');
        copy($GLOBALS["foton_setting"]["path"] . '/app/controller/' . $GLOBALS['foton_setting']['admindir'] . '_globals.php', $GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $GLOBALS["foton_setting"]["admindir"] . $rand_dir . '/admin_globals.php');
    }

    public function copy_sh()
    {
        if (isset($this->request->p['sh'])) {
            $new_dir = $this->request->p['sh'] . rand(200, 1000);
            $this->core->copy_dir($GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $this->request->p['sh'], $GLOBALS["foton_setting"]["path"] . '/system/api/tpladmin/' . $new_dir);
        }
    }
}