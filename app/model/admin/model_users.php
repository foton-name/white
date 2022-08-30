<?php

  class Model_users extends Model
{

    public function names()
    {
        $arr = array('role' => 'Роли', 'user_inc' => 'Список пользователей с ролями');
        return $arr;

    }

    public function role_chmod(){
         return [1,1];

    }

    public function user_inc_chmod(){
         return [1,1];

    }

    public function nameinclude()
    {
        return 'Пользователи';

    }

    public function before_ins_user_inc($arr = false)
    {
        if (isset($arr)) {
            $arr['pass'] = md5($arr['pass']);
            if (!file_exists($GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['login'])) {
                mkdir($GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['login']);
            }
            if (!file_exists($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->request->p['login'])) {
                mkdir($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->request->p['login']);
            }
            file_put_contents($GLOBALS['foton_setting']['path'] . '/.logs/' . $this->request->p['login'] . '/work_area.html', '');
            if (!file_exists($GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['login'] . '/work')) {
                mkdir($GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['login'] . '/work');
            }
            if (!file_exists($GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['login'] . '/release')) {
                mkdir($GLOBALS['foton_setting']['path'] . '/.gitf/' . $this->request->p['login'] . '/release');
            }
            return $arr;
        }

    }

    public function before_up_user_inc($arr)
    {
        if (isset($arr)) {
            if ($arr['pass'] == '') {
                unset($arr['pass']);
            } else {
                $arr['pass'] = md5($arr['pass']);
            }
            return $arr;
        }

    }

    public function before_select_user_inc($value, $name)
    {
        if (isset($value) && $name == 'pass') {
            $value = '';

        }
        return $value;
    }

    public function interfaces()
    {

        $arr = array(
            "role" => array(
                "field" => array('id', 'name', 'text'),
                "name" => array('id', 'Номер группы', 'Название группы'),
                "format" => array('int', 'text', 'text'),
                "format_select" => array("ids", "number", "input"),
                "key" => "id"
            ),
            "user_inc" => array(
                "field" => array('id', 'role', 'name', 'login', 'pass'),
                "name" => array('id', 'Группа', 'Имя', 'login', 'Пароль'),
                "format" => array('int', 'text', 'text', 'text', 'text'),
                "format_select" => array("ids", "selectall:role,text,name", "input", "input", "input"),
                "key" => "id"
            )
        );
        return $arr;

    }

    public function drop_interface()
    {
        $arr = array(
            "role" => array(
                "field" => array('desc', 'asc')
            ),
            "user_inc" => array(
                "del" => "0"
            )
        );
        return $arr;

    }


}