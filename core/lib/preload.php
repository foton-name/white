<?php
  namespace Foton;

class Parents
{
    public static $dbh;
    public static $type_arr;
    public function __construct()
    {
        if(empty(self::$dbh)){
            self::$dbh = $this->db();
        }
        $this->db = self::$dbh;
        $this->get_post = $this->post_get();
        if (class_exists('XMLWriter')) {
            $this->xml = new \XMLWriter();
        } else {
            $this->xml = false;
        }
        $this->sharding();
        $this->inc_file = 'ID';
        $this->type_file = 'TYPE';
        $this->arr_type = $GLOBALS["foton_setting"]['orm']['type'];
    }

    public function db()
    {
        if(empty(self::$dbh)){
            if (file_exists($this->git('sql.php'))) {
                require_once($this->git('sql.php'));
            }
            self::$type_arr = $GLOBALS["foton_setting"]['orm'][$GLOBALS['foton_setting']['sql']];
            if ($GLOBALS['foton_setting']['sql'] == 'lite') {                
                $dbh_obj = new \PDO('sqlite:' . $GLOBALS["foton_setting"]["path"] . '/' . $GLOBALS["foton_setting"]["dbname"] . '.db');
            } else if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                if (stristr($GLOBALS["foton_setting"]["host"], ':')) {
                    $host = explode(':', $GLOBALS["foton_setting"]["host"]);
                    $dbh_obj = new \PDO('pgsql:host=' . $host[0] . ' port=' . $host[1] . ' dbname=' . $GLOBALS["foton_setting"]["dbname"], $GLOBALS["foton_setting"]["login"], $GLOBALS["foton_setting"]["pass"], array(
                        \PDO::ATTR_PERSISTENT => true
                    ));
                } else {
                    $dbh_obj = new \PDO('pgsql:host=' . $GLOBALS["foton_setting"]["host"] . ' dbname=' . $GLOBALS["foton_setting"]["dbname"], $GLOBALS["foton_setting"]["login"], $GLOBALS["foton_setting"]["pass"], array(
                        \PDO::ATTR_PERSISTENT => true
                    ));
                }
            } else {        
                if (stristr($GLOBALS["foton_setting"]["host"], ':')) {
                    $host = explode(':', $GLOBALS["foton_setting"]["host"]);
                    $dbh_obj = new \PDO('mysql:host=' . $host[0] . ';port=' . $host[1] . ';dbname=' . $GLOBALS["foton_setting"]["dbname"], $GLOBALS["foton_setting"]["login"], $GLOBALS["foton_setting"]["pass"], array(
                        \PDO::ATTR_PERSISTENT => true
                    ));
                } else {
                    $dbh_obj = new \PDO('mysql:host=' . $GLOBALS["foton_setting"]["host"] . ';dbname=' . $GLOBALS["foton_setting"]["dbname"], $GLOBALS["foton_setting"]["login"], $GLOBALS["foton_setting"]["pass"], array(
                        \PDO::ATTR_PERSISTENT => true
                    ));
                }
                $dbh_obj->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $dbh_obj->exec("set names utf8");
            }
            return $dbh_obj;
        }
        else{
            return self::$dbh;
        }
    }

    public function git($path = null)
    {
         return FotonGit($path);
    }

    public function post_get()
    {
        $data = explode('/', $_SERVER['REQUEST_URI']);
        foreach ($data as $key => $val) {
            if ($key != 0 && $key != 1) {
                $_GET[$key] = $val;
            }
        }
    }

    public function sharding()
    {
        if (file_exists($this->git('dev/sharding.php')) && isset($GLOBALS['foton_setting']['pagef'])) {
            require_once($this->git('dev/sharding.php'));
            if (isset($arr)) {
                if (isset($arr['site'][$GLOBALS['foton_setting']['pagef']])) {
                    foreach ($arr['site'][$GLOBALS['foton_setting']['pagef']] as $key => $val) {
                        $GLOBALS[$key] = $val;
                    }
                }
                if ($this->isAuth()) {
                    if (isset($arr['admin'])) {
                        foreach ($arr['admin'] as $pattern => $arr_sh) {
                            if (preg_match("#" . $pattern . "#i", $_SERVER['REQUEST_URI'], $arr_match)) {
                                foreach ($arr_sh as $key => $val) {
                                    $GLOBALS[$key] = $val;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function status($status=200,$page='/'){
        if($status==503){
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 300');
        }
        else if($status==404){
            header("HTTP/1.1 404 Not Found");
        }
        else if($status==403){
            header('HTTP/1.0 403 Forbidden');
        }
        else if($status==301){
            header("HTTP/1.1 301 Moved Permanently"); 
            header("Location: ".$page); 
            exit(); 
        }
        else if($status==302){
            header("HTTP/1.1 302 Moved Permanently"); 
            header("Location: ".$page.", true, 302"); 
            exit(); 
        }
        else{
            
        }
        http_response_code($status);
    }

    public function head($header){
        if(is_array($header)){
            foreach($header as $h){
                header($h);
            }
        }
        else{
            header($header);  
        }        
    }
    public function isAuth()
    {
        if (isset($_SESSION['login'])) {
            $role = $this->select_db('user_inc', 'login', $_SESSION['login'], 'role');
            $_SESSION['chmod_id'] = $role[0];
            if ($_SESSION['chmod_id'] == '0' || $_SESSION['chmod_id'] == '' || $_SESSION['multiplay'] != (int)$GLOBALS['foton_setting']['multiplay'] * (int)$this->ip_user() || empty($_SESSION['chmod_id'])) {
                unset($_SESSION);
                session_destroy();
                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                    foreach($cookies as $cookie) {
                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        setcookie($name, '', time()-36000);
                        setcookie($name, '', time()-36000, '/');
                    }
                }                
                unset($_COOKIE);
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function select_db($table = null, $attr = null, $attr2 = null, $echo = null)
    {
        if ($table != null) {
            $table = $this->quotes($table);
            $arrf = array();
            if (isset($attr)) {
                if (stristr($attr, ';') === FALSE) {
                    $attr2 = str_replace('"', '', $attr2);
                    if (isset($echo)) {
                        if (stristr($echo, ';') === FALSE) {
                            $tb_e = $this->table($table)->where(array('=' . $attr => $attr2))->forq();
                            if (isset($tb_e[0][$echo])) {
                                $arrf[] = $tb_e[0][$echo];
                            }
                        } else {
                            $echo2 = explode(';', $echo);
                            $tb_e = $this->table($table)->forq();
                            $arr_row = $arrf = $tb_e;
                            foreach ($arr_row as $row) {
                                foreach ($echo2 as $val) {
                                    $arrf[] = $row[$val];
                                }
                            }
                        }
                    } else {
                        $arrf[] = $this->table($table)->where(array('=' . $attr => $attr2))->forq();
                    }
                } else {
                    ###
                    $attr2 = explode(';', $attr2);
                    $attr = explode(';', $attr);
                    $arr_f = array();
                    foreach ($attr as $key_r => $val_r) {
                        $arr_f['=' . $val_r] = $attr2[$key_r];
                    }
                    $arr_row = $arrf = $this->table($table)->where($arr_f)->forq();
                    if (isset($echo)) {
                        if (stristr($echo, ';') === FALSE) {
                            foreach ($arr_row as $row) {
                                $arrf[] = $row[$echo];
                            }
                        } else {
                            $echo2 = explode(';', $echo);
                            foreach ($arr_row as $row) {
                                foreach ($echo2 as $val) {
                                    $arrf[] = $row[$val];
                                }
                            }
                        }
                    } else {
                        foreach ($arr_row as $row) {
                            $arrf[] = $row;
                        }
                    }
                }
            } else {
                $arr_ret = $this->table($table)->forq();
                if(is_array($arr_ret)){
                    foreach ($arr_ret as $row) {
                        $arrf[] = $row;
                    }
                }
            }
            return $arrf;
        }
    }

    public function quotes($return)
    {
        $return = preg_replace('#([^0-9_a-zA-Z,а-я.А-Я-]+)#', '', $return);
        return $return;
    }

    public function forq($field_key = null, $field_value = null, $param = null)
    {
        $qw = $this->db_query;
        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $qw = str_replace('`', '', $qw);
            $qw = str_replace('"', "'", $qw);
        }
        $this->db_query = '';
        if(isset($param['S'])){
            $qw = str_replace('\"','"', $qw);
        }
        if(isset($param['debag'])){
            echo $this->log($qw);
        }
        $arr = array();
        if ($qw != '') {
            try {
                $ms = $this->db->query($qw);
            } catch (\PDOException $e) {
                $this->log('forq_f error:' . $e->getMessage() . '--' . (int)$e->getCode());
                return false;
            }
            $is_obj = (array)$ms;
            if ($field_key != null && $field_value != null) {
                if (is_array($field_value)) {
                    while ($res = $ms->fetch(\PDO::FETCH_BOTH)) {
                        foreach ($field_value as $f_value) {
                            $arr[$res[$field_key]][] = $res[$f_value];
                        }
                    }
                } else {
                    while ($res = $ms->fetch(\PDO::FETCH_BOTH)) {
                        $arr[$res[$field_key]] = $res[$field_value];
                    }
                }
            } else {
                if ($GLOBALS['foton_setting']['sql'] != 'lite' && $GLOBALS['foton_setting']['sql'] != 'pgsql' && isset($param['echo'])) {
                    if ($param['echo'] == '1') {
                        $arr = $ms->fetchAll(\PDO::FETCH_KEY_PAIR);
                    } else if ($param['echo'] == '2') {
                        $arr = $ms->fetchAll(\PDO::FETCH_NUM);
                    } else if ($param['echo'] == '3') {
                        $arr = $ms->fetchAll(\PDO::FETCH_UNIQUE);
                    } else if ($param['echo'] == 'c') {
                        $arr = $ms->rowCount();
                    } else if (!isset($is_obj[0])) {
                        foreach ($ms as $row) {
                            $arr[] = $row;
                        }
                    } else {

                    }
                } else {
                    if (!isset($is_obj[0])) {
                        while ($res = $ms->fetch()) {
                            $arr[] = $res;
                        }
                    }
                }
            }
        }
        return $arr;
    }

    /*$arr = array('where'=>array('field'=>array('('%|!|=')name'=>'value'),'and'=>'and|or'));*/

    public function where($w = null, $and = null, $group = null)
    {
        if ($w != null) {
            if (is_array($w)) {
                if ($and == null) {
                    $and = ' AND ';
                }
                $res = array();
                foreach ($w as $k => $v) {
                    if (preg_replace('#([a-zA-Z_\-]+)#', '', $k[0]) != '') {
                        $field = substr($k, 1);
                        $m = $k[0];
                    } else {
                        $field = $k;
                        $m = 'no';
                    }
                    $field = $this->quotes($field);
                    if ($field == 'id' || $m == '<' || $m == '>' || $m == '$') {
                        if (is_array($v) || $m == '$') {
                        } else {
                            $v = $this->number_foton($v);
                        }
                    } else {
                        //$v = str_replace('"', '', $v);
                    }
                    if ($m == 'no') {
                        if (is_array($v)) {
                            foreach ($v as $v_item) {
                                $res[] = ' `' . $field . '`=' . $this->db->quote($v_item) . ' ';
                            }
                        } else {
                            $res[] = '`' . $field . '`=' . $this->db->quote($v);
                        }
                    } else {
                        if (is_array($v)) {
                            foreach ($v as $v_item) {
                                if ($m == '$') {
                                    $arr_v = explode(':', $v_item);
                                    $res[] = ' `' . $field . '`>' . $this->db->quote($arr_v[0]) . ' AND `' . $field . '`<' . $this->db->quote($arr_v[1]) . ' ';
                                }
                                else if(isset($GLOBALS["foton_setting"]['orm']['where'][$m])){
                                    $re = ["[%value%]"=>$this->db->quote('%'.$v_item.'%'),"[field]"=>"`".$field."`"];
                                    $replace = strtr($GLOBALS["foton_setting"]['orm']['where'][$m], $re);
                                    $re = ["[%value]"=>$this->db->quote('%'.$v_item),"[field]"=>"`".$field."`"];
                                    $replace = strtr($replace, $re);
                                    $re = ["[value%]"=>$this->db->quote($v_item.'%'),"[field]"=>"`".$field."`"];
                                    $replace = strtr($replace, $re);
                                    $re = ["[value]"=>$this->db->quote($v_item),"[field]"=>"`".$field."`"];
                                    $res[]= strtr($replace, $re);
                                }
                                else {
                                    $res[]= '`'.$field. '`=' .$this->db->quote($v_item).' ';
                                }
                            }
                        } else {
                            if ($m == '$') {
                                $arr_v = explode(':', $v);
                                $res[] = ' `' . $field . '`>' . $this->db->quote($arr_v[0]) . ' AND `' . $field . '`<' . $this->db->quote($arr_v[1]) . ' ';
                            }
                            else if(isset($GLOBALS["foton_setting"]['orm']['where'][$m])){
                                $re = ["[%value%]"=>$this->db->quote('%'.$v.'%'),"[field]"=>"`".$field."`"];
                                $replace = strtr($GLOBALS["foton_setting"]['orm']['where'][$m], $re);
                                $re = ["[%value]"=>$this->db->quote('%'.$v),"[field]"=>"`".$field."`"];
                                $replace = strtr($replace, $re);
                                $re = ["[value%]"=>$this->db->quote($v.'%'),"[field]"=>"`".$field."`"];
                                $replace = strtr($replace, $re);
                                $re = ["[value]"=>$this->db->quote($v),"[field]"=>"`".$field."`"];
                                $res[]= strtr($replace, $re);
                            }
                            else {
                                $res[]= '`'.$field. '`=' .$this->db->quote($v).' ';
                            }
                        }
                    }
                }
                $res_str = implode($and, $res);
                if ($group == 1) {
                    $this->db_query .= "(" . $res_str . ")";
                } else if ($group == 2) {
                    $this->db_query .= " " . $res_str . " ";
                } else {
                    $this->db_query .= " WHERE " . $res_str;
                }
            } else {
                if ($group == 1) {
                    $this->db_query .= "(" . $w . ")";
                } else if ($group == 2) {
                    $this->db_query .= " " . $w . " ";
                } else {
                    $this->db_query .= " WHERE " . $w;
                }
            }
            if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                $this->db_query = str_replace('`', '', $this->db_query);
                $this->db_query = str_replace('"', "'", $this->db_query);
            }
            return $this;
        } else {
            $this->log('where_f: не задано условие');
        }
    }

    public function table($tb = null, $f = null)
    {
        $tb = $this->quotes($tb);
        if ($tb != null) {
            if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                if ($f != null) {
                    if (!is_array($f) && strpos($f, '(') === false) {
                        $f = $this->quotes($f);
                        $this->db_query .= "SELECT " . $f . " FROM " . $tb . "";
                    } else if (is_array($f)) {
                        foreach ($f as $k => $v) {
                            $f[$k] = $this->quotes($v);
                        }
                        $f = implode(",", $f);
                        $this->db_query .= "SELECT " . $f . " FROM " . $tb . "";
                    } else {
                        $this->db_query .= "SELECT " . $f . " FROM " . $tb . "";
                    }
                } else {
                    $this->db_query .= "SELECT * FROM " . $tb;
                }
            } else {
                if ($f != null) {
                    if (strpos($f, '(') === false && !is_array($f)) {
                        $f = str_replace(",", "`,`", $f);
                        $this->db_query .= "SELECT `" . $f . "` FROM `" . $tb . "`";
                    } else if (is_array($f)) {
                        foreach ($f as $k => $v) {
                            $f[$k] = $this->quotes($v);
                        }
                        $f = implode("`,`", $f);
                        $this->db_query .= "SELECT `" . $f . "` FROM `" . $tb . "`";
                    } else {
                        $this->db_query .= "SELECT " . $f . " FROM `" . $tb . "`";
                    }
                } else {
                    $this->db_query .= "SELECT * FROM `" . $tb . "`";
                }
            }
            return $this;
        } else {
            $this->log('tb_f: не задана таблица');
        }
    }

    public function ip_user()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = @$_SERVER['REMOTE_ADDR'];
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } else if (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        $ip = str_replace('.', '', $ip);
        return $ip;
    }

    public function s()
    {
        $this->db_query .= ';';
        return $this;
    }

     public function rand($passl = 10,$allchars="abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789^&*()$#@!")
    {
        $string = '';
        for ($i = 0; $i < $passl; $i++) {
            $n = mb_strlen($allchars) - 1;
            $string .= $allchars[mt_rand(0, $n)];
        }
        return $string;
    }

    public function countsql($table = null, $arr = null)
    {
        if ($table != null) {
            if ($arr != null) {
                $obj_data = $this->count($table);
                if (isset($arr['where']) && $arr['where']['field'] != null) {
                    $data = @unserialize($arr['where']);
                    if ($data !== false) {
                        $where = unserialize($arr['where']);
                    } else {
                        $where = $arr['where'];
                    }

                    if (empty($where['and'])) {
                        $where['and'] = ' AND ';
                    }
                    if (isset($where['and']) && $where['and'] == 'LOGIC') {
                        $this->db_query .= " WHERE " . $where['field'];
                        $obj_data = $this;
                    } else {
                        $obj_data = $obj_data->where($where['field'], $where['and']);
                    }

                }
                $obj_data = $obj_data->c(1);
                return $obj_data;
            } else {
                return $this->count($table)->c(1);
            }
        } else {
            $this->log('countsql: укажите таблицу');
            return false;
        }
    }

    public function count($tb = null)
    {
        $tb = $this->quotes($tb);
        if ($tb != null) {
            $this->db_query .= "SELECT COUNT(*) FROM `" . $tb . "`";
            return $this;
        } else {
            $this->log('count_f: не задана таблица');
        }
    }

    public function c($count = null,$param=array())
    {
        $qw = $this->db_query;
        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $qw = str_replace('`', '', $qw);
            $qw = str_replace('"', "'", $qw);
        }
        $this->db_query = '';
        if(isset($param['S'])){
            $qw = str_replace('\"', '"', $qw);
        }
        if (isset($qw) && $qw != null && $count == null) {
            $ms = $this->db->query($qw);
            try {
                return $ms->rowCount();
            } catch (\PDOException $e) {
                $this->log('c_f error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }
        } else if (isset($qw) && $qw != null) {
            try {
                return $this->db->query($qw)->rowCount();
            } catch (\PDOException $e) {
                $this->log('c_f error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }
        } else {
            return false;
        }
    }

    public function sql_dump_file($file = null, $no_table = null)
    {
        if ($file != null) {
            if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                $create_tb = '';
                $out_f = array();
                $arr_tb = $this->table('information_schema.tables')->where(array('=table_schema' => 'public'))->forq();
                foreach ($arr_tb as $table) {
                    $create_tb .= "CREATE TABLE " . $table['table_name'];
                    $arr_f = $this->strukture_foton($table['table_name']);
                    $format = $this->format_table_foton($table['table_name']);
                    if (count($arr_f) > 0) {
                        foreach ($arr_f as $key_v => $val) {
                            $fieldn[] = $val['column_name'];
                            if ($val['column_name'] == 'id') {
                                $fields = 'id serial NOT NULL PRIMARY KEY';
                            } else {
                                $fields = $val['column_name'] . ' ' . $format[$key_v]['data_type'];
                            }
                            $out_f[$key_v] = $fields;
                        }
                    }
                    $out_f2 = implode(",", $out_f);
                    $out_f = array();
                    $create_tb .= " (" . $out_f2 . ");\n\n";
                    $stmt2 = $this->table($table['table_name'])->forq();
                    foreach ($stmt2 as $i => $fields2) {
                        $f_val = '';
                        foreach ($fields2 as $keyp => $valp) {
                            $valp = str_replace("\n", "", $valp);
                            $valp = str_replace("\r", "", $valp);
                            $tb_n = $this->format_table_foton($table['table_name']);
                            if (is_int($keyp) && $tb_n[$keyp]['data_type'] == 'integer') {
                                $f_val .= $valp . ',';
                            } else if (isset($tb_n[$keyp]['data_type']) && $tb_n[$keyp]['data_type'] != '') {
                                $f_val .= "'" . str_replace("'", '"', $valp) . "',";
                            } else {

                            }
                        }
                        $f_val = mb_substr($f_val, 0, -1);
                        $fnstr = implode(",", $fieldn);
                        $create_tb .= "INSERT INTO " . $table['table_name'] . " (" . $fnstr . ") VALUES (" . $f_val . ");\n\n";

                    }
                    $fieldn = array();


                }
                file_put_contents($file, $create_tb);
            } else {
                $create_tb = '-- phpMyAdmin SQL Dump
            -- version ' . mysqli_get_client_info() . '
            -- https://www.phpmyadmin.net
            --
            -- Хост: localhost
            -- Время создания: ' . date("m") . ' ' . date("d") . ' ' . date("Y") . ' г., ' . date("H") . ':' . date("i") . '
            -- Версия сервера: ' . mysqli_get_client_info() . '
            -- Версия PHP: ' . phpversion() . '
        
            SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
            SET time_zone = "+00:00";
        
        
            /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
            /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
            /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
            /*!40101 SET NAMES utf8mb4 */;
        
            --
            -- База данных: 
            --
            ';
                //таблицы, которые не нужно записывать array('role','user_inc')
                if ($no_table != null) {
                    $arr_no_del = $no_table;
                } else {
                    $arr_no_del = array();
                }

                try {
                    $stmt1 = $this->db->query('SHOW TABLES', \PDO::FETCH_NUM);
                } catch (\PDOException $e) {
                    $this->log('sql_dump error:' . $e->getMessage() . '--' . (int)$e->getCode());
                }
                if (isset($stmt1)) {
                    foreach ($stmt1->fetchAll() as $row) {
                        if (!in_array($row[0], $arr_no_del)) {
                            $stmt2 = $this->db->query("SHOW CREATE TABLE `" . $row[0] . "`", \PDO::FETCH_ASSOC);
                            $table = $stmt2->fetch();
                            $create_tb .= $table['Create Table'] . ";\n\n";
                            $stmt2 = $this->db->prepare("SELECT * FROM `" . $row[0] . "`");
                            $stmt2->execute();
                            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                                $pole_val = '';
                                $i = 0;
                                foreach ($row2 as $keyp => $valp) {
                                    $valp = str_replace("\n", "", $valp);
                                    $valp = str_replace("\r", "", $valp);
                                    $tb_r = $this->format_table_foton($row[0]);
                                    if ($tb_r[$i] == 'int') {
                                        $pole_val .= $valp . ',';
                                    } else {
                                        $pole_val .= '"' . str_replace('"', '\"', $valp) . '",';
                                    }
                                    $i++;
                                }
                                $pole_val = mb_substr($pole_val, 0, -1);
                                $create_tb .= "INSERT INTO `" . $row[0] . "` (`" . implode("`,`", array_keys($row2)) . "`) VALUES (" . $pole_val . ");\n\n";
                            }

                        }
                    }
                }
                file_put_contents($file, $create_tb);
            }
        } else {
            return false;
        }
    }

    public function strukture_foton($table = null)
    {
        if ($table != null) {
            $table = $this->quotes($table);
            if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                $sth = $this->table('information_schema.columns', 'column_name')->where(array("=table_name" => $table))->forq();
                return $sth;
            } else {
                try {
                    $sth = $this->db->prepare("SHOW COLUMNS FROM `" . $this->quotes($table) . "`");
                    $sth->execute();
                    $array = $sth->fetchAll(\PDO::FETCH_COLUMN);
                    return $array;
                } catch (\PDOException $e) {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function format_table_foton($table = null)
    {
        if ($table != null) {
            $table = $this->quotes($table);
            if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                $sth = $this->table('information_schema.columns', 'data_type')->where(array("=table_name" => $table))->forq();
                return $sth;
            } else {
                try {
                    $sth = $this->db->prepare("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $this->quotes($table) . "'");
                    $sth->execute();
                    $array = $sth->fetchAll(\PDO::FETCH_COLUMN);
                    return $array;
                } catch (\PDOException $e) {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function site_dump($dump = null, $arr_no_del = null)
    {
        if ($dump != null) {
            $name = 'dump' . date("Ymdis");
            $file = $GLOBALS["foton_setting"]["path"] . '/' . $name . '.sql';
            $filezip = $GLOBALS['foton_setting']['backup'] . '/' . $name . '.zip';
            if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                $create_tb = '';
                $out_f = array();
                $arr_tb = $this->table('information_schema.tables')->where(array('=table_schema' => 'public'))->forq();
                foreach ($arr_tb as $table) {
                    if ((is_array($arr_no_del) && !in_array($table['table_name'], $arr_no_del)) || $arr_no_del == null) {
                        $create_tb .= "CREATE TABLE " . $table['table_name'];
                        $arr_f = $this->strukture_foton($table['table_name']);
                        $format = $this->format_table_foton($table['table_name']);
                        if (count($arr_f) > 0) {
                            foreach ($arr_f as $key_v => $val) {
                                $fieldn[] = $val['column_name'];
                                if ($val['column_name'] == 'id') {
                                    $fields = 'id serial NOT NULL PRIMARY KEY';
                                } else {
                                    $fields = $val['column_name'] . ' ' . $format[$key_v]['data_type'];
                                }
                                $out_f[$key_v] = $fields;
                            }
                        }
                        $out_f2 = implode(",", $out_f);
                        $out_f = array();
                        $create_tb .= " (" . $out_f2 . ");\n\n";
                        $stmt2 = $this->table($table['table_name'])->forq();
                        foreach ($stmt2 as $i => $fields2) {
                            $f_val = '';
                            foreach ($fields2 as $keyp => $valp) {
                                $valp = str_replace("\n", "", $valp);
                                $valp = str_replace("\r", "", $valp);
                                $tb_n = $this->format_table_foton($table['table_name']);
                                if (is_int($keyp) && $tb_n[$keyp]['data_type'] == 'integer') {
                                    $f_val .= $valp . ',';
                                } else if (isset($tb_n[$keyp]['data_type']) && $tb_n[$keyp]['data_type'] != '') {
                                    $f_val .= "'" . str_replace("'", '"', $valp) . "',";
                                } else {

                                }
                            }
                            $f_val = mb_substr($f_val, 0, -1);
                            $fnstr = implode(",", $fieldn);
                            $create_tb .= "INSERT INTO " . $table['table_name'] . " (" . $fnstr . ") VALUES (" . $f_val . ");\n\n";

                        }
                        $fieldn = array();
                    }

                }
                file_put_contents($file, $create_tb);
                if (stristr(PHP_OS, "win")) {
                    $globpath = str_replace("/",DIRECTORY_SEPARATOR,$GLOBALS["foton_setting"]["path"]);
                    $filezip_w = str_replace("/",DIRECTORY_SEPARATOR,$filezip);
                    if(isset($GLOBALS['foton_setting']['winrar'])){
                        exec('"'.$GLOBALS['foton_setting']['winrar'].'" a -r '.$filezip_w.' '.$globpath);
                    }
                    else{
                        exec('"C:\Program Files\WinRAR\rar.exe" a -r '.$filezip_w.' '.$globpath);
                    }
                }
                else{
                    exec('zip -r ' . $filezip . ' ' . $GLOBALS["foton_setting"]["path"] . '/ -x "*.zip" ');
                }
                unlink($file);
                return $name . '.zip';
            } else {
                $create_tb = '-- phpMyAdmin SQL Dump
          -- version 4.4.15.9
          -- https://www.phpmyadmin.net
          --
          -- Хост: localhost
          -- Время создания: ' . date("Y-m-d H:i:s") . '
          -- Версия сервера: -cll-lve
          -- Версия PHP: ' . phpversion() . '

          SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
          SET time_zone = "+00:00";


          /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
          /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
          /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
          /*!40101 SET NAMES utf8mb4 */;

          --
          -- База данных: 
          --
          ';
                try {
                    $stmt1 = $this->db->query('SHOW TABLES', \PDO::FETCH_NUM);
                } catch (\PDOException $e) {
                    $this->log('sql_dump error:' . $e->getMessage() . '--' . (int)$e->getCode());
                }
                if (isset($stmt1)) {
                    foreach ($stmt1->fetchAll() as $row) {
                        if ((is_array($arr_no_del) && !in_array($row[0], $arr_no_del)) || $arr_no_del == null) {
                            $stmt2 = $this->db->query("SHOW CREATE TABLE `" . $row[0] . "`", \PDO::FETCH_ASSOC);
                            $table = $stmt2->fetch();
                            $create_tb .= $table['Create Table'] . ";\n\n";
                            $stmt2 = $this->db->prepare("SELECT * FROM `" . $row[0] . "`");
                            $stmt2->execute();
                            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                                $f_val = '';
                                $i = 0;
                                foreach ($row2 as $keyp => $valp) {
                                    $valp = str_replace("\n", "", $valp);
                                    $valp = str_replace("\r", "", $valp);
                                    $tb_r = $this->format_table_foton($row[0]);
                                    if ($tb_r[$i] == 'int') {
                                        $f_val .= $valp . ',';
                                    } else {
                                        $f_val .= '"' . str_replace('"', '\"', $valp) . '",';
                                    }
                                    $i++;
                                }
                                $f_val = mb_substr($f_val, 0, -1);
                                $create_tb .= "INSERT INTO `" . $row[0] . "` (`" . implode("`,`", array_keys($row2)) . "`) VALUES (" . $f_val . ");\n\n";
                            }
                        }

                    }
                }
                file_put_contents($file, $create_tb);
                if (stristr(PHP_OS, "win")) {
                    $globpath = str_replace("/",DIRECTORY_SEPARATOR,$GLOBALS["foton_setting"]["path"]);
                    $filezip_w = str_replace("/",DIRECTORY_SEPARATOR,$filezip);
                    if(isset($GLOBALS['foton_setting']['winrar'])){
                        exec('"'.$GLOBALS['foton_setting']['winrar'].'" a -r '.$filezip_w.' '.$globpath);
                    }
                    else{
                        exec('"C:\Program Files\WinRAR\rar.exe" a -r '.$filezip_w.' '.$globpath);
                    }
                }
                else{
                    exec('zip -r ' . $filezip . ' ' . $GLOBALS["foton_setting"]["path"] . '/ -x "*.zip" ');
                }
                unlink($file);
                return $name . '.zip';
            }
        } else {
            $this->log('site_dump: 1 значение - 1  не задано');

        }
    }

    public function field_table_foton($table = null, $field = null)
    {
        if ($table != null) {
            $table = $this->quotes($table);
            if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                return $this->table($table, array($field))->forq();
            } else {
                try {
                    $sth = $this->db->prepare("SELECT `" . $field . "` FROM `" . $this->quotes($table) . "`");
                    $sth->execute();
                    $array = $sth->fetchAll(\PDO::FETCH_COLUMN);
                    return $array;
                } catch (\PDOException $e) {
                    return false;
                }

            }
        } else {
            return false;
        }
    }

    public function execute($arr = null)
    {
        $qw = $this->db_query;
        $this->db_query = '';
        if ($qw != '' && $arr != null) {
            try {
                $obj = $this->db->prepare($qw);
                foreach ($arr as $key => $val) {
                    $obj->bindValue($key, $val, \PDO::PARAM_STR);
                }
                // execute the update statement
                return $obj->execute();
            } catch (\PDOException $e) {
                return false;
            }

        } else {
            return false;
        }

    }

    public function insert_db($table = null, $attr = null, $attr2 = null)
    {
        if ($table != null && $attr != null && $attr2 != null) {
            $table = $this->quotes($table);
            $attr = str_replace(';', ',', $this->quotes($attr));
            if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                $attr2 = str_replace(';', ",", $this->db->quote($attr2));
                return $this->table($table)->insert("(" . $attr . ") VALUES (" . $attr2 . ")")->q();
            } else {
                $attr2 = str_replace(';', ',', $this->db->quote($attr2));
                return $this->table($table)->insert('(' . $attr . ') VALUES (' . $attr2 . ')')->q();
            }
        } else {
            $this->log('insert_db: не задана сигнатура (таблица,поля через ;, значения полей через ;)');
        }

    }

    public function q($param=array())
    {
        $qw = $this->db_query;
        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $qw = str_replace('`', '', $qw);
            $qw = str_replace('"', "'", $qw);
        }
        $this->db_query = '';
        if(isset($param['S'])){
            $qw = str_replace('\"','"', $qw);
        }
        $arr = array();
        if ($qw != '') {
            try {
                $obj = $this->db->prepare($qw);
            } catch (\PDOException $e) {
                $this->log('q_f error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }
            if ($GLOBALS['foton_setting']['sql'] != 'lite') {
                try {
                    $obj->execute();
                    return $obj->fetchAll(\PDO::FETCH_OBJ);
                } catch (\PDOException $e) {
                    $this->log('q_f error:' . $e->getMessage() . '--' . (int)$e->getCode());
                }
            }
        } else {
            return false;
        }
    }

    public function insert($tb = null)
    {
        $tb = $this->quotes($tb);
        $this->db_query .= "INSERT INTO `" . $tb . "`";
        return $this;
    }

    public function select_db_seo($table = null, $attr = null, $attr2 = null, $echo = null)
    {
        if ($table != null && $attr != null && $attr2 != null) {
            $table = $this->quotes($table);
            $arr = array();
            if (stristr($echo, ';') === FALSE) {
                if ($attr != 'id') {
                    foreach ($this->table($table)->where(array('=' . $attr => $attr2))->forq() as $row) {
                        $arr[] = $row[$echo];
                    }
                } else {
                    $attr2 = preg_replace('#([^0-9]+)#', '', $attr2);
                    foreach ($this->table($table)->where(array('=' . $attr => $attr2))->forq() as $row) {
                        $arr[] = $row[$echo];
                    }
                }
            } else {
                if ($attr != 'id') {
                    foreach ($this->table($table)->where(array('=' . $attr => $attr2))->forq() as $row) {
                        $echo_arr = explode(';', $echo);
                        foreach ($echo_arr as $echo_v) {
                            $arr[] = $row[$echo_v];
                        }
                    }
                } else {
                    $attr2 = preg_replace('#([^0-9]+)#', '', $attr2);
                    foreach ($this->table($table)->where(array('=' . $attr => $attr2))->forq() as $row) {
                        $echo_arr = explode(';', $echo);
                        foreach ($echo_arr as $echo_v) {
                            $arr[] = $row[$echo_v];
                        }
                    }
                }

            }

            return $arr;
        } else {
            $this->log('select_db_seo: сигнатура (таблица,поле если, значение если, поле для вывода) не задана');
        }
    }

    public function select_from_seo($table = null, $from = null, $to = null, $echo = null)
    {
        if ($table != null && $from != null && $to != null) {
            $table = $this->quotes($table);
            $arr = array();
            foreach ($this->table($table)->sorts($echo, 'DESC')->lim($to, $from)->forq() as $row) {
                $arr[] = $row[$echo];
            }
            return $arr;
        } else {
            $this->log('select_from_seo: сигнатура (таблица,от,до, поле для вывода) не задана');
        }
    }

    public function lim($limit = null, $ot = null)
    {
        $limit = $this->quotes($limit);
        $ot = $this->quotes($ot);
        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $this->db_query .= " LIMIT " . $limit . "  OFFSET " . $ot;
        } else {
            $this->db_query .= " LIMIT " . $ot . "," . $limit;
        }
        return $this;
    }

    public function sorts($sp = null, $s = null)
    {
        $sp = $this->quotes($sp);
        $s = $this->quotes($s);
        $this->db_query .= " ORDER BY `" . $sp . "` " . $s;
        return $this;
    }

    public function sql_insert($file = null)
    {
        if ($file != null) {
            // Temporary variable, used to store current query
            $templine = '';
            // Read in entire file
            $lines = file($file);
            // Loop through each line
            foreach ($lines as $line) {
                // Skip it if it a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                    continue;

                // Add this line to the current segment
                $templine .= $line;
                // If it has a semicolon at the end, it the end of the query
                if (substr(trim($line), -1, 1) == ';') {
                    // Perform the query
                    $this->db->query($templine);
                    // Reset temp variable to empty
                    $templine = '';
                }
            }

        } else {
            $this->log('sql_insert: путь к файлу не задан');
        }
    }

    public function no_slash($return)
    {
        $return = str_replace("'", "\'", $return);
        return $return;
    }

    public function htmlret($return)
    {
       if(strrpos($return,'?')!==false){
            return htmlspecialchars_decode(urldecode($return));
       }
       else{
           return htmlspecialchars_decode($return);
       }
    }

    public function update_db($table = null, $attr = null, $attr2 = null, $where = null)
    {
        if ($table != null && $attr != null && $attr2 != null) {
            $table = $this->quotes($table);
            $attr2 = explode(';', $attr2);
            $attr = explode(';', $attr);
            foreach ($attr as $key2 => $atr) {
                if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                    $atrs .= $atr . "=" . $this->db->quote($attr2[$key2]) . ",";
                } else {
                    $atrs .= '`' . $atr . '`=' . $this->db->quote($attr2[$key2]) . ',';
                }
            }
            $atrs = substr($atrs, 0, -1);
            return $this->up($table, $atrs)->where($where)->q();//sqllite
        }
    }

    public function up($tb = null, $p = null)
    {
        $tb = $this->quotes($tb);
        $this->db_query .= " UPDATE `" . $tb . "` SET " . $p;
        return $this;
    }

    public function delete_db($table = null, $attr = null, $attr2 = null)
    {
        if (isset($attr)) {
            $table = $this->quotes($table);
            if (stristr($attr, ';') === FALSE) {
                return $this->d($table)->where(array('=' . $attr => $attr2))->q();//sqllite
            } else {
                $attr2 = explode(';', $attr2);
                $attr = explode(';', $attr);
                foreach ($attr as $key2 => $atr) {
                    if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                        $atrs .= $atr . "=" . $this->db->quote($attr2[$key2]) . " AND ";
                    } else {
                        $atrs .= '`' . $atr . '`=' . $this->db->quote($attr2[$key2]) . ' AND ';
                    }
                }
                $atrs = substr($atrs, 0, -4);
                return $this->d($table)->where($atrs)->q();//sqllite
            }
        } else {
            return $this->trun($table)->q();//sqllite
        }
    }

    public function d($tb = null)
    {
        $tb = $this->quotes($tb);
        $this->db_query .= " DELETE FROM `" . $tb . "`";
        return $this;
    }

    public function trun($tb = null)
    {
        $tb = $this->quotes($tb);
        $this->db_query .= "TRUNCATE TABLE `" . $tb . "`";
        return $this;
    }

    public function id_field_foton($table = null, $field = null, $id = null)
    {
        $table = $this->quotes($table);
        $field = $this->quotes($field);
        $id = $this->quotes($id);
        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $sth = $this->table($table, $field)->where(array('=id' => $id))->forq();
            foreach ($sth as $value) {
                return $value;
            }
        } else {
            try {
                $sth = $this->db->prepare("SELECT `" . $field . "` FROM `" . $table . "` WHERE `id` = ?");
                $sth->execute(array($id));
                $value = $sth->fetch(\PDO::FETCH_COLUMN);
                return $value;
            } catch (\PDOException $e) {
                return false;
            }

        }
    }

    public function tablessortw($valtables = null, $id = "id", $sort = 'DESC', $ceil = '0', $w1 = null, $w2 = null)
    {
        if ($valtables != null) {
            $valtables = $this->quotes($valtables);
            $id = $this->quotes($id);
            $sort = $this->quotes($sort);
            $ceil = $this->quotes($ceil);
            $w1 = $this->quotes($w1);
            $w2 = $this->quotes($w2);
            $arr = array();
            if ($ceil == '0') {
                $arrf = $this->table($valtables)->where(array('=' . $w1 => $w2))->sorts($id, $sort)->forq();
            } else {
                $arrf = $this->table($valtables)->where(array('=' . $w1 => $w2))->sorts($id, $sort, true)->forq();
            }
            return $arrf;
        } else {
            $this->log('tablessortw: название таблицы не должно быть пустым(1)');
        }
    }

    public function table_listdesc()
    {
        if ($GLOBALS['foton_setting']['sql'] == 'lite' || $GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $res = $this->list_table();
            $t = '';
            for ($i = 0; $i < count($res); $i++) {
                $t .= ',' . $res[$i];
                $result = $this->field_table($res[$i]);
                $colw = '';
                foreach ($result as $col) {
                    $colw .= $col . ',';
                }
                $arr['desc'][] = $colw;
                $arr['id'][] = $res[$i];
            }
        } else {
            $t = '';
            $r = $this->db->query("SHOW TABLES");
            $res = $r->fetchAll();
            for ($i = 0; $i < count($res); $i++) {
                $t .= ',' . $res[$i][0];
                $result = $this->db->query("SHOW COLUMNS FROM " . $res[$i][0]);
                $colw = '';
                foreach ($result as $col) {
                    $colw .= $col[0] . ',';
                }
                $arr['desc'][] = $colw;
                $arr['id'][] = $res[$i][0];
            }
        }
        return $arr;
    }

    public function list_table($table = null, $field = null)
    {
        $table = $this->quotes($table);
        $field = $this->quotes($field);
        if ($GLOBALS['foton_setting']['sql'] == 'lite') {
            if ($field != null) {
                if ($table == null) {
                    $arr_tb = $this->table('sqlite_master', $field)->forq();
                } else {
                    $arr_tb = $this->table('sqlite_master', $field)->where(array('=type' => 'table', '=name' => $table))->forq();
                }
            } else {
                if ($table == null) {
                    $arr_tb = $this->table('sqlite_master')->where(array('=type' => 'table'))->forq();
                    foreach ($arr_tb as $table) {
                        $new_arr[] = $table['name'];
                    }
                    return $new_arr;
                } else {
                    $arr_tb = $this->table('sqlite_master')->where(array('=type' => 'table', '=name' => $table))->forq();
                }
            }
            return $arr_tb;
        } else if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $arr_tb = $this->table('information_schema.tables')->where(array('=table_schema' => 'public'))->forq();
            foreach ($arr_tb as $table) {
                $new_arr[] = $table['table_name'];
            }
            return $new_arr;
        } else {
            $arr = array();
            $r = $this->db->query("SHOW TABLES");
            $res = $r->fetchAll();
            for ($i = 0; $i < count($res); $i++) {
                $arr[] = $res[$i][0];
            }
            return $arr;
        }
    }

    public function type_column($table=null,$field=null){
        if ($table != null || $field!=null) {
            if ($GLOBALS['foton_setting']['sql'] == 'lite') {
                return false;
            }
            else if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                $arr_tb = $this->table('information_schema.columns','data_type')->where(array('=table_name' => $table,'=column_name'=>$field))->forq();
                if (isset($arr_tb[0])) {                    
                        return $arr_tb[0][0];
                }
                else{
                    return false;
                }
            }
            else{
                $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = :table AND COLUMN_NAME = :field";
                $stmt = $this->db()->prepare($sql);
                $stmt->bindValue(':table', $table, \PDO::PARAM_STR);
                $stmt->bindValue(':field', $field, \PDO::PARAM_STR);
                $stmt->execute();
                $output = array();
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    return $row['DATA_TYPE'];
                }
                return $output;
            }
        }
        else{
            return false;
        }
    }
    public function is_table($table = null)
    {
        $table = $this->quotes($table);
        if ($table != null) {
            if ($GLOBALS['foton_setting']['sql'] == 'lite') {
                $arr_tb = $this->table('sqlite_master')->where(array('=type' => 'table', '=name' => $table))->forq();
            } 
            else if($GLOBALS['foton_setting']['sql'] == 'pgsql'){
                $arr_tb = $this->table('information_schema.columns')->where(array('=table_name' => $table))->forq();
            }
            else{
                $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = :table";
                $stmt = $this->db()->prepare($sql);
                $stmt->bindValue(':table', $table, \PDO::PARAM_STR);
                $stmt->execute();
                $output = array();
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $arr_tb[] = $row['COLUMN_NAME'];
                }
            }
            if (isset($arr_tb) && is_array($arr_tb) && count($arr_tb) > 0) {
                return true;
            }
            else{
                return false;
            }
        } else {
            $this->log('field_table: название таблицы(1) не должно быть пустым');
            return false;
        }
    }
    public function field_table($table = null)
    {
        $table = $this->quotes($table);
        if ($table != null) {
             if ($GLOBALS['foton_setting']['sql'] == 'lite') {
                $out_f = array();
                $arr_tb = $this->table('sqlite_master')->where(array('=type' => 'table', '=name' => $table))->forq();
                if (isset($arr_tb[0])) {
                    $field = explode('(', $arr_tb[0]['sql']);
                    $field_arr = preg_match_all('|,([^a-z]+)([^ ]+) |', $arr_tb[0]['sql'], $out, PREG_PATTERN_ORDER);
                    foreach ($out[2] as $val) {
                        $out_f[] = $val;
                    }
                    return $out_f;
                }
            }  else if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                $arr_tb = $this->table('information_schema.columns')->where(array('=table_name' => $table))->forq();
                if (count($arr_tb) > 0) {
                    foreach ($arr_tb as $val) {
                        $out_f[] = $val['column_name'];
                    }
                    return $out_f;
                }
            } else {
                $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = :table";
                $stmt = $this->db()->prepare($sql);
                $stmt->bindValue(':table', $table, \PDO::PARAM_STR);
                $stmt->execute();
                $output = array();
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $output[] = $row['COLUMN_NAME'];
                }
                return $output;
            }
        } else {
            $this->log('field_table: название таблицы(1) не должно быть пустым');
            return false;
        }
    }

    public function create($tb = null)
    {
        $tb = $this->quotes($tb);
        $this->db_query .= "CREATE TABLE `" . $tb . "` (";
        return $this;
    }

    public function insert_arr($arr = null)
    {
        foreach ($arr as $key => $val) {
            $arr[$this->quotes($key)] = $this->db->quote($val);
        }
        $field = implode('`,`', array_keys($arr));
        $value = implode(',', $arr);
        $this->db_query .= "(`" . $field . "`) VALUES (" . $value . ")";
        return $this;
    }

    public function field($name=null,$field=null){
    	if($name!=null && isset($GLOBALS["foton_setting"]['orm'][$GLOBALS['foton_setting']['sql']][$field])){
    		$name = $this->quotes($name);
	        $this->db_query .= "`" . $name . "`".$GLOBALS["foton_setting"]['orm'][$GLOBALS['foton_setting']['sql']][$field];
	        return $this;
    	}
    	else{
    		return $this;
    	}
    }
    
    public function key($p = null)
    {
        $p = $this->quotes($p);
        if ($GLOBALS['foton_setting']['sql'] != 'pgsql') {
            $this->db_query .= "PRIMARY KEY (`" . $p . "`)";
        }
        return $this;
    }

    public function us($db = null)
    {
        $db = $this->quotes($db);
        if ($db != null) {
            $this->db_query = '';
            $this->db->exec('USE ' . $db);
            return $this;
        } else {
            return false;
        }
    }

    public function back($echo = 1, $stop = null)
    {
        if (!$this->db->beginTransaction()) {
            return $this->log('Не могу начать транзакцию');
        } else {
            if (isset($this->db_query)) {
                $qw = $this->db_query;
                if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                    $qw = str_replace('`', '', $qw);
                    $qw = str_replace('"', "'", $qw);
                }
                if (strpos($qw, ';') !== false) {
                    $qw_arr = explode(';', $qw);
                    for ($i = 0; $i < count($qw_arr) - 1; $i++) {
                        if ($qw_arr[$i] != '') {
                            $this->db->exec($qw_arr[$i]);
                        }
                    }
                    $qw = $qw_arr[count($qw_arr) - 1];
                }
                if ($echo == 1) {
                    try {
                        $ms = $this->db->query($qw);
                        $arr = $ms->fetchAll(\PDO::FETCH_NUM);
                    } catch (\PDOException $e) {
                        $this->log('begin error:' . $e->getMessage() . '--' . (int)$e->getCode());
                    }
                } else if ($echo == 2) {
                    try {
                        $ms = $this->db->query($qw);
                        $arr = $ms->fetchAll(\PDO::FETCH_UNIQUE);
                    } catch (\PDOException $e) {
                        $this->log('begin error:' . $e->getMessage() . '--' . (int)$e->getCode());
                    }
                } else {
                    try {
                        $this->db->exec($qw);
                    } catch (\PDOException $e) {
                        $this->log('begin error:' . $e->getMessage() . '--' . (int)$e->getCode());
                    }
                }
                if ($stop != null) {
                    $this->db->commit();
                } else {
                    $this->db->rollback();
                }
                if (isset($arr) && is_array($arr)) {
                    return $arr;
                } else {
                    return true;
                }
            } else {
                return $this->log('Нет заданных действий для транзакции');
            }
        }
    }

    public function cq($echo = null)
    {
        if ($GLOBALS['foton_setting']['sql'] == 'lite') {
            $this->db_query .= ")";//sqllite
            $qw = $this->db_query;
        } else {
            $this->db_query .= ") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
            $qw = $this->db_query;
        }
        if ($echo != null) {
            return $qw;
        }
        $this->db_query = '';
        try {
            return $this->db->query($qw);
        } catch (\PDOException $e) {
            $this->log('cq_f error:' . $e->getMessage() . '--' . (int)$e->getCode());
        }

    }

    public function one($p = null)
    {
        $p = $this->quotes($p);
        $this->db_query .= " ORDER BY `" . $p . "` DESC LIMIT 1";
        return $this;
    }

    public function limit($p = null, $limit = null, $ot = null)
    {
        $p = $this->quotes($p);
        $limit = $this->quotes($limit);
        $ot = $this->quotes($ot);
        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $this->db_query .= " ORDER BY `" . $p . "` DESC LIMIT " . $limit . " OFFSET " . $ot;
        } else {
            $this->db_query .= " ORDER BY `" . $p . "` DESC LIMIT " . $ot . "," . $limit;
        }
        return $this;
    }

    public function group($f = null)
    {

        if (is_array($f)) {
            foreach ($f as $k => $v) {
                $f[$k] = $this->quotes($v);
            }
            $f = implode(",", $f);
        } else {
            $f = $f;
        }
        $f = str_replace(",", "`,`", $f);
        $this->db_query .= " GROUP BY  `" . $f . "`";
        return $this;
    }

    public function limita($p = null, $limit = null, $ot = null)
    {
        $p = $this->quotes($p);
        $limit = $this->quotes($limit);
        $ot = $this->quotes($ot);
        $this->db_query .= " ORDER BY `" . $p . "` ASC LIMIT " . $ot . "," . $limit;
        return $this;
    }

    public function drop($tb = null)
    {
        $tb = $this->quotes($tb);
        $this->db_query .= " DROP TABLE `" . $tb . "`";
        return $this;
    }

    public function wr()
    {
        $this->db_query .= " WHERE ";
        return $this;
    }

    public function andf()
    {
        $this->db_query .= " AND ";
        return $this;
    }

    public function orf()
    {
        $this->db_query .= " OR ";
        return $this;
    }

    public function sc()
    {
        $this->db_query .= "; ";
        return $this;
    }

    public function like($wp = null, $w = null)
    {
        if ($wp != null && $w != null) {
            if (is_array($wp)) {
                foreach ($wp as $k => $v) {
                    if ($w[$k] == 'id') {
                        $where[] = '`' . $w[$k] . '`=' . $this->db->quote($v);
                    } else {
                        if (is_array($v)) {
                            foreach ($v as $v_item) {
                                $where[] = '`' . $w[$k] . '`LIKE ' . $this->db->quote("%" . $v_item . "%") . ' AND';
                            }
                        } else {
                            $where[] = '`' . $w[$k] . '` LIKE ' . $this->db->quote("%" . $v . "%");
                        }
                    }
                }
                $where = implode(' AND ', $where);
            } else {
                $where = $this->quotes($w) . '=' . $this->db->quote($wp);
            }
            $this->db_query .= " WHERE " . $where;
            return $this;
        } else {
            $this->log('like_f: не задано условие или поле');
        }
    }

    public function custom($res=''){
        if(is_array($res)){
            foreach($res as $key=>$val){
                if(is_int($key) && isset($GLOBALS["foton_setting"]['orm']['custom'][$val])){                    
                    $this->db_query.= ' ' . $GLOBALS["foton_setting"]['orm']['custom'][$val] . ' ';
                }
                else if(isset($GLOBALS["foton_setting"]['orm']['custom'][$key])){ 
                    $key_c = $GLOBALS["foton_setting"]['orm']['custom'][$key];
                    foreach($val as $key_r=>$val_r){
                        $key_c = str_replace($key_r,$val_r,$key_c);
                    }
                    $this->db_query.= ' ' .$key_c.' ';
                }
                else{

                }
            }
        }
        else if(is_string($res) && isset($GLOBALS["foton_setting"]['orm']['custom'][$res])){
            $this->db_query.= ' ' . $GLOBALS["foton_setting"]['orm']['custom'][$res] . ' ';
        }
        else{

        }
        return $this;        
    }
    
    public function where_arr($wp = null, $w = null)
    {
        if ($wp != null && $w != null) {
            if (is_array($wp)) {
                foreach ($wp as $k => $v) {
                    if ($w[$k] == 'id') {
                        $where[] = '`' . $this->quotes($w[$k]) . '`=' . $this->db->quote($v) . '';
                    } else {
                        $where[] = '`' . $this->quotes($w[$k]) . '`="' . $this->db->quote($v) . '"';
                    }
                }
                $where = implode(' AND ', $where);
            } else {
                $where = $this->quotes($w) . '="' . $this->db->quote($wp) . '"';
            }
            $this->db_query .= " WHERE " . $where;
            return $this;
        } else {
            $this->log('where_arr_f: не задано условие или поле');
        }
    }

    public function query($param=array())
    {
        $qw = $this->db_query;
        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $qw = str_replace('`', '', $qw);
            $qw = str_replace('"', "'", $qw);
        }
        if(isset($param['S'])){
            $qw = str_replace('\"','"', $qw);
        }
        $this->db_query = '';
        try {
            return $this->db->query($qw);
        } catch (\PDOException $e) {
            $this->log('query_f error:' . $e->getMessage() . '--' . (int)$e->getCode());
        }

    }

    public function eq($param=array())
    {
        $qw = $this->db_query;
        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
            $qw = str_replace('`', '', $qw);
            $qw = str_replace('"', "'", $qw);
        }
        if(isset($param['S'])){
            $qw = str_replace('\"','"', $qw);
        }
        $this->db_query = '';
        return $qw;
    }

    public function type_kod($f1 = null, $f2 = null)
    {
        if ($f1 != null) {
            $tb_f1 = $this->table('html')->where("kod='" . $f1 . "'")->forq();
            if ($f2 != null) {
                return $tb_f1[0][$f2];
            } else {
                return $tb_f1[0];
            }
        } else {
            $this->log('type_kod: не задан код поля');
        }
    }

    public function join($arr)
    {
        if(empty($arr['table1']) || empty($arr['table2']) || empty($arr['field2']) || empty($arr['field2']) || !is_array($arr['field2']) || !is_array($arr['field1'])){
            return false;
        }
        if ($arr['format'] == 'L') {
            $this->db_query .= ' LEFT JOIN `' . $arr['table1'] . '.` ';
        } else if ($arr['format'] == 'R') {
            $this->db_query .= ' RIGHT JOIN `' . $arr['table1'] . '` ';
        } else if ($arr['format'] == 'I') {
            $this->db_query .= ' INNER JOIN `' . $arr['table1'] . '` ';
        } else if ($arr['format'] == 'O') {
            $this->db_query .= ' OUTHER JOIN `' . $arr['table1'] . '` ';
        } else if ($arr['format'] == 'F') {
            $this->db_query .= ' FULL JOIN `' . $arr['table1'] . '` ';
        } else if ($arr['format'] == 'C') {
            $this->db_query .= ' CROSS JOIN `' . $arr['table1'] . '` ';
        } else {
            $this->db_query .= ' INNER JOIN `' . $arr['table1'] . '` ';
        }
        if ($arr['format'] != 'C') {
            foreach ($arr['field2'] as $i => $field) {
                $arr['field2'][$i] = $arr[3][$i];
                if (preg_replace('#([a-zA-Z_\-]+)#', '', $field[0]) != '') {
                    $fields = substr($field, 1);
                    $m = $field[0];
                } else {
                    $fields = $field;
                    $m = 'no';
                }
                if ($m == 'no') {
                    $res = '`' . $arr['table2'] . '`.`' . $fields . '`=`' . $arr['table1'] . '`.`' . $arr['field1'][$i] . '`';
                } else {
                    if ($m == '!') {
                        $res = '`' . $arr['table2'] . '`.`' . $fields . '`<>`' . $arr['table1'] . '`.`' . $arr['field1'][$i] . '`';
                    } else if ($m == '<') {
                        $res = '`' . $arr['table2'] . '`.`' . $fields . '`<`' . $arr['table1'] . '`.`' . $arr['field1'][$i] . '`';
                    } else if ($m == '>') {
                        $res = '`' . $arr['table2'] . '`.`' . $fields . '`>`' . $arr['table1'] . '`.`' . $arr['field1'][$i] . '`';
                    } else {

                    }
                }
                if ($i == 0) {
                    $this->db_query .= ' ON ' . $res;
                } else {
                    $this->db_query .= ' AND ' . $res;
                }
            }
        }
        return $this;
    }

    public function post_replace()
    {
        foreach ($_POST as $key => $val) {
            $_POST[$key] = str_replace('<\/textarea>', '</textarea>', $_POST[$key]);
        }
    }
}

class Widget extends Parents
{

    public function __get($property)
    {
        $path = $this->git($GLOBALS['foton_setting']['path'] . '/dev/widget/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $property . '/index.php');
        if (file_exists($path)) {
            require_once $path;
            $name = ucfirst($property);
            $obj = new $name;
            return $obj;
        } else {
            return false;
        }
    }

    public function __call($property, $arg)
    {
        $path = $this->git($GLOBALS['foton_setting']['path'] . '/dev/widget/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $property . '/index.php');
        if (file_exists($path)) {
            require_once $path;
            $name = ucfirst($property);
            $obj = new $name(...array_values($arg));
            return $obj;
        } else {
            return false;
        }
    }
}

class Mod extends Parents
{
    public function __get($property)
    {
        $path = $this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/' . $property . '.php');
        if (file_exists($path)) {
            require_once $path;
            $name = ucfirst($property);
            $obj = new $name;
            return $obj;
        } else {
            return false;
        }
    }

    public function __call($property, $arg)
    {
        $path = $this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/' . $property . '.php');
        if (file_exists($path)) {
            require_once $path;
            $name = ucfirst($property);
            $obj = new $name(...array_values($arg));
            return $obj;
        } else {
            return false;
        }
    }
}

if (file_exists($GLOBALS['foton_setting']['path'].'/dev/custom_valid.php')) {
    require_once($GLOBALS['foton_setting']['path'].'/dev/custom_valid.php');
}
class Is_Validate{
    use Custom_isValid;
    function __construct(){
        $this->parents = new Parents;
    }
}
class Validate{
    use Custom_Validate;
    function __construct(){
        $this->parents = new Parents;
    }
    public function html($text=null)
    {
        return htmlspecialchars($text);
    }
    public function dhtml($text=null)
    {
        return htmlspecialchars_decode($text);
    }
    public function pass($text=null)
    {
        return md5($text);
    }
    public function echo_type($text){
        return htmlentities(htmlentities(htmlspecialchars($text)));
    }
    public function text($text=null)
    {
        return preg_replace('/[^[:print:]]/', '', $text);
    }
    public function medium($text=null)
    {
        return preg_replace('/[^a-zA-Zа-яёА-ЯЁ ]/u', '', $text);
    }
    public function abcd($text=null)
    {
        return preg_replace('/[^a-zA-Zа-яёА-ЯЁ0-9 ]/', '', $text);
    }
    public function abc($text=null)
    {
        return preg_replace('/[^a-zA-Z]/', '', $text);
    }
    public function abv($text=null)
    {
        return preg_replace('/[^а-яёА-ЯЁ]/u', '', $text);
    }
    public function same($text=null)
    {
        return $text;
    }
    public function int($text=null)
    {
        return preg_replace('#([^0-9]+)#','',$text);
    }

    public function phone($text=null)
    {
        return preg_replace('#([^0-9+-\(\)]+)#', '', $text);
    }

    public function tags($text=null)
    {
        $return = preg_replace('#<([^>]+)>#', '', $text);
        $return = strip_tags($return);
        return $return;
    }
    public function no_xss($text=null)
    {
         $text = addslashes($text); 
         $text = htmlspecialchars(strip_tags(stripslashes($text)));
         return $text;
    }

    public function plus($text=null)
    {
        return abs($text);
    }

    public function mini($text=null)
    {
        return floor($text);
    }

    public function maxi($text=null)
    {
        return ceil($text);
    }

    public function nul($text=null)
    {
        return null;
    }

    public function tabs($text=null)
    {
        return preg_replace('#(\s+)#', '', $text);
    }
    public function req($query='',$err=false){
        $return = $this->no_xss(urldecode($query));
        $return = preg_replace('#<([^>]+)>#', '', $return);
        $return = strip_tags($return);
        $return = str_replace('"', '', $return);
        $return = str_replace("'", '', $return);
        return $return;
    }
    public function texts($text=null)
    {
        return preg_replace('/[^a-zA-Zа-яёА-ЯЁ0-9,-\.+ ]/', '', $text);
    }
    public function request($query='',$err=false){
        $return = $this->no_xss(urldecode($query));
        $return = preg_replace('#<([^>]+)>#', '', $return);
        $return = strip_tags($return);
        $return = str_replace('"', '', $return);
        $return = str_replace("'", '', $return);
        if($err && $return!=$query){
            return false;
        }
        return true;
    } 
}
