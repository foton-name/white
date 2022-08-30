<?php

  class Tplsite_a extends Tplsite_c
{
    public function del_sh()
    {
        if (isset($this->request->p['sh'])) {
            return $this->core->dir_delete_foton($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $this->request->p['sh']);
        }
    }
    public function del_site()
    {
        if (isset($this->request->p['sh'])) {
            $dir_tpl = $this->request->p['sh'];
            $arr_del = array(
                '/dev/modul/htmlred/template/' . $dir_tpl,
                '/dev/modul/htmlred/filest/' . $dir_tpl,
                '/dev/modul/htmlred/site/' . $dir_tpl,
                '/system/api/php/' . $dir_tpl,
                '/app/view/' . $dir_tpl,
                '/app/model/' . $dir_tpl,
                '/app/controller/' . $dir_tpl,
                '/app/view/json/' .$dir_tpl,
                '/system/api/tpl/' . $dir_tpl,
                '/app/view/xml/' . $dir_tpl, 
                '/app/ajax/' . $dir_tpl,
                '/system/api/tpl/xml/' . $dir_tpl,
                '/system/api/tpl/json/' . $dir_tpl,
                '/dev/widget/' . $dir_tpl, 
                '/system/migrations/' . $dir_tpl
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
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/template/' . $dir_tpl . '/*.html') as $filehtml) {
                unlink($filehtml);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $dir_tpl . '/*.css') as $filename) {
                unlink($filename);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/filest/' . $dir_tpl . '/*') as $filename) {
                unlink($filename);
            }
            if(file_exists($GLOBALS['foton_setting']['path'] .'/'.$dir_tpl.'_db.sql')){
                unlink($GLOBALS['foton_setting']['path'] .'/'.$dir_tpl.'_db.sql');
            }
        }
    }
    public function ust_sh()
    {
        if (isset($this->request->p['sh'])) {
            $dir_tpl = $this->request->p['sh'];
            //таблицы, которые не нужно экспортировать и удалять
            $arr_no_del = array('role', 'user_inc');
            foreach ($this->core->list_table() as $table) {
                if (!in_array($table, $arr_no_del)) {
                    $this->db->query("DROP TABLE " . $table);
                }
            }
            $file = $GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/dump.sql';
            $this->core->sql_insert($file);
            $arr_create = array(
                '/dev/modul/htmlred/template/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/htmlredsh',
                '/dev/modul/htmlred/filest/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/filest',
                '/dev/modul/htmlred/site/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/htmlred',
                '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/php',
                '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/site',
                '/app/model/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/model',
                '/app/controller/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/controller',
                '/app/view/json/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/jsonv',
                '/system/api/tpl/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/sitetpl',
                '/app/view/xml/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/xmlv',
                '/app/ajax/' . $GLOBALS['foton_setting']['sitedir'] => '/system/api/tplsite/' . $dir_tpl . '/tpl',
                '/system/api/tpl/xml/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/xml',
                '/system/api/tpl/json/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $dir_tpl . '/json',
                '/dev/widget/' . $GLOBALS['foton_setting']['sitedir'] => '/system/api/tplsite/' . $dir_tpl . '/widget',
                '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] => '/system/api/tplsite/' . $dir_tpl . '/migrations',
            );
            if(file_exists($GLOBALS['foton_setting']['path'].'/app/lang/'.$GLOBALS['foton_setting']['sitedir']) && file_exists($GLOBALS['foton_setting']['path'].'/system/api/tplsite/'.$dir_tpl.'/lang')){
                $arr_create['/app/lang/'.$GLOBALS['foton_setting']['sitedir']]='/system/api/tplsite/'.$dir_tpl.'/lang';
            }
            $file = $GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/dump.sql';
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $GLOBALS['foton_setting']['sitedir'] . '/*.css') as $filename) {
                unlink($filename);
            }
            foreach ($arr_create as $dir0 => $dir1) {
                if (file_exists($GLOBALS["foton_setting"]["path"] . $dir1)) {
                    $this->core->dir_delete_foton($GLOBALS["foton_setting"]["path"] . $dir0);
                    mkdir($GLOBALS["foton_setting"]["path"] . $dir0, 0775);
                    $this->core->copy_dir($GLOBALS["foton_setting"]["path"] . $dir1, $GLOBALS["foton_setting"]["path"] . $dir0);
                }
            }
            $arr_no_del = array('role', 'user_inc');
            if ($GLOBALS['foton_setting']['sql'] == 'lite' && file_exists($GLOBALS['foton_setting']['path'] . '/system/api/tplsite/' . $dir_tpl . '/foton.db')) {
                copy($GLOBALS['foton_setting']['path'] . '/system/api/tplsite/' . $dir_tpl . '/foton.db', $GLOBALS['foton_setting']['path'] . '/foton.db');
            } else {
                $this->core->sql_dump_file($file, $arr_no_del);
            }
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/site_globals.php', $GLOBALS["foton_setting"]["path"] . '/app/controller/' . $GLOBALS['foton_setting']['sitedir'] . '_globals.php');
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/ajax/ajax_site.php', $GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $GLOBALS['foton_setting']['sitedir'] . '.php');
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/ajax/ajax_site_m.php', $GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $GLOBALS['foton_setting']['sitedir'] . '_m.php');
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/template/' . $GLOBALS["foton_setting"]["sitedir"] . '/*.html') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/filest/([^/]+)/#', '/dev/modul/htmlred/filest/' . $GLOBALS["foton_setting"]["sitedir"] . '/', $html);
                file_put_contents($filehtml, $html);
            }

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $GLOBALS["foton_setting"]["sitedir"] . '/*.css') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/filest/([^/]+)/#', '/dev/modul/htmlred/filest/' . $GLOBALS["foton_setting"]["sitedir"] . '/', $html);
                file_put_contents($filehtml, $html);
            }

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/*.php') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/site/([^/]+)/#', '/dev/modul/htmlred/site/' . $GLOBALS["foton_setting"]["sitedir"] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/*.tpl') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/site/([^/]+)/#', '/dev/modul/htmlred/site/' . $GLOBALS["foton_setting"]["sitedir"] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS["foton_setting"]["sitedir"] . '/*.php') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/site/([^/]+)/#', '/dev/modul/htmlred/site/' . $GLOBALS["foton_setting"]["sitedir"] . '/', $html);
                file_put_contents($filehtml, $html);
            }
        }
    }
    public function copy_site()
    {
        if (isset($this->request->p['sh']) && isset($this->request->p['site']) && $this->request->p['site']!='') {
            $dir_tpl = $this->request->p['sh'];
            $file = $GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/dump.sql';
            $arr_create = array(
                '/dev/modul/htmlred/template/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/htmlredsh',
                '/dev/modul/htmlred/filest/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/filest',
                '/dev/modul/htmlred/site/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/htmlred',
                '/system/api/php/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/php',
                '/app/view/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/site',
                '/app/model/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/model',
                '/app/controller/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/controller',
                '/app/view/json/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/jsonv',
                '/system/api/tpl/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/sitetpl',
                '/app/view/xml/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/xmlv',
                '/app/ajax/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/tpl',
                '/system/api/tpl/xml/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/xml',
                '/system/api/tpl/json/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/json',
                '/dev/widget/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/widget',
                '/system/migrations/' . $this->request->p['site'] => '/system/api/tplsite/' . $dir_tpl . '/migrations',
            );
            if(file_exists($GLOBALS['foton_setting']['path'].'/app/lang/'.$this->request->p['site']) && file_exists($GLOBALS['foton_setting']['path'].'/system/api/tplsite/'.$dir_tpl.'/lang')){
                $arr_create['/app/lang/'.$this->request->p['site']]='/system/api/tplsite/'.$dir_tpl.'/lang';
            }
            $file = $GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/dump.sql';
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $this->request->p['site'] . '/*.css') as $filename) {
                unlink($filename);
            }
            foreach ($arr_create as $dir0 => $dir1) {
                $this->core->log($GLOBALS["foton_setting"]["path"] . $dir0);
                if (file_exists($GLOBALS["foton_setting"]["path"] . $dir1)) {
                    $this->core->dir_delete_foton($GLOBALS["foton_setting"]["path"] . $dir0);
                    mkdir($GLOBALS["foton_setting"]["path"] . $dir0, 0775);
                    $this->core->copy_dir($GLOBALS["foton_setting"]["path"] . $dir1, $GLOBALS["foton_setting"]["path"] . $dir0);
                }
            }
            if ($GLOBALS['foton_setting']['sql'] == 'lite' && file_exists($GLOBALS['foton_setting']['path'] . '/system/api/tplsite/' . $dir_tpl . '/foton.db')) {
                copy($GLOBALS['foton_setting']['path'] . '/system/api/tplsite/' . $dir_tpl . '/foton.db', $GLOBALS['foton_setting']['path'] . '/foton.db');
            } else {
                copy($file,$GLOBALS['foton_setting']['path'] .'/'.$this->request->p['site'].'_db.sql');
            }
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/site_globals.php', $GLOBALS["foton_setting"]["path"] . '/app/controller/' . $this->request->p['site'] . '_globals.php');
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/ajax/ajax_site.php', $GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $this->request->p['site'] . '.php');
            copy($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $dir_tpl . '/ajax/ajax_site_m.php', $GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $this->request->p['site'] . '_m.php');
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/template/' . $this->request->p['site'] . '/*.html') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/filest/([^/]+)/#', '/dev/modul/htmlred/filest/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/dev/modul/htmlred/site/' . $this->request->p['site'] . '/*.css') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/filest/([^/]+)/#', '/dev/modul/htmlred/filest/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }

            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $this->request->p['site'] . '/*.php') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/site/([^/]+)/#', '/dev/modul/htmlred/site/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $this->request->p['site'] . '/*.tpl') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/site/([^/]+)/#', '/dev/modul/htmlred/site/' . $this->request->p['site'] . '/', $html);
                file_put_contents($filehtml, $html);
            }
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' .$this->request->p['site']. '/*.php') as $filehtml) {
                $html = file_get_contents($filehtml);
                $html = preg_replace('#/dev/modul/htmlred/site/([^/]+)/#', '/dev/modul/htmlred/site/' .$this->request->p['site']. '/', $html);
                file_put_contents($filehtml, $html);
            }
        }
    }

    public function sozd_sh()
    {
        $rand_dir = rand(10, 100) . rand(10, 100) . rand(10, 100);
        $arr_create = array(
            '/dev/modul/htmlred/template/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/htmlredsh',
            '/dev/modul/htmlred/filest/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/filest',
            '/dev/modul/htmlred/site/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/htmlred',
            '/system/api/php/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/php',
            '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/site',
            '/app/model/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/model',
            '/app/controller/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/controller',
            '/app/view/json/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/jsonv',
            '/system/api/tpl/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/sitetpl',
            '/app/view/xml/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/xmlv',
            '/app/ajax/' . $GLOBALS['foton_setting']['sitedir'] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/tpl',
            '/system/api/tpl/xml/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/xml',
            '/system/api/tpl/json/' . $GLOBALS["foton_setting"]["sitedir"] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/json',
            '/dev/widget/' . $GLOBALS['foton_setting']['sitedir'] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/widget',
            '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] => '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/migrations',
        );
         if(file_exists($GLOBALS['foton_setting']['path'].'/app/lang/'.$GLOBALS['foton_setting']['sitedir'])){
                $arr_create['/app/lang/'.$GLOBALS['foton_setting']['sitedir']]='/system/api/tplsite/'.$GLOBALS["foton_setting"]["sitedir"] . $rand_dir.'/lang';
            }
        mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir, 0775);
        copy($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $GLOBALS['foton_setting']['sitedir'] . '/'. $GLOBALS['foton_setting']['sitedir'].'_globals.php', $GLOBALS["foton_setting"]["path"] . '/app/controller/site_globals.php');
        $file = $GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/dump.sql';
        foreach ($arr_create as $dir0 => $dir1) {
            if (file_exists($GLOBALS["foton_setting"]["path"] . $dir0)) {
                mkdir($GLOBALS["foton_setting"]["path"] . $dir1, 0775);
                $this->core->copy_dir($GLOBALS["foton_setting"]["path"] . $dir0, $GLOBALS["foton_setting"]["path"] . $dir1);
            }
        }
        $arr_no_del = array('role', 'user_inc');
        if ($GLOBALS['foton_setting']['sql'] == 'lite' && file_exists($GLOBALS['foton_setting']['path'] . '/' . $GLOBALS["foton_setting"]["dbname"] . '.db')) {
            copy($GLOBALS['foton_setting']['path'] . '/' . $GLOBALS["foton_setting"]["dbname"] . '.db', $GLOBALS['foton_setting']['path'] . '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/foton.db');
        } else {
            $this->core->sql_dump_file($file, $arr_no_del);
        }

        mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/htmlred', 0775);       
        copy($GLOBALS["foton_setting"]["path"] . '/app/controller/' . $GLOBALS['foton_setting']['sitedir'] . '_globals.php', $GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/site_globals.php');
        mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/ajax', 0775);
        copy($GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $GLOBALS['foton_setting']['sitedir'] . '.php',$GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $GLOBALS["foton_setting"]["sitedir"] . $rand_dir . '/ajax/ajax_site.php');
        copy($GLOBALS["foton_setting"]["path"] . '/app/ajax/ajax_' . $GLOBALS['foton_setting']['sitedir'] . '_m.php',$GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' .$GLOBALS["foton_setting"]["sitedir"] . $rand_dir. '/ajax/ajax_site_m.php');


    }


    public function copy_sh()
    {
        if (isset($this->request->p['sh'])) {
            $new_dir = $this->request->p['sh'] . rand(200, 1000);
            $this->core->copy_dir($GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $this->request->p['sh'], $GLOBALS["foton_setting"]["path"] . '/system/api/tplsite/' . $new_dir);
        }
    }
}