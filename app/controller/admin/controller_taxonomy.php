<?php

  class Controller_Taxonomy extends Model_Taxonomy implements \Foton\Face{
    public static $interface;
    public static $model;
    public static $table;
    public static $interface_arr;
    public static $type;
    public static $field;
    public static $section;

    public function __constructor()
    {
        self::$interface = 'taxonomy';
        self::$model = 'taxonomy';
        self::$table = $this->request->g['2'];
        self::$field = 'field';
        self::$type = "format_select";
        self::$section = 'section';

    }

    public function table()
    {
        return self::$table;
    }

    //метод загрузки инициализирует создание, удаление и обновление полей таблицы и выдает результат для проверки
    public function ready()
    {
        if (self::$table == '') {
            self::$table = 'taxonomy_t';
        }
        $arr_notif = array();
        if ($this->core->i_create(self::$model, self::$table)) {
            $arr_notif['create'] = 'yes';
        }
        if ($this->core->i_drop(self::$model, self::$table)) {
            $arr_notif['delete'] = 'yes';
        }
        if ($this->core->i_alter(self::$model, self::$table)) {
            $arr_notif['alter'] = 'yes';
        }
        return $arr_notif;

    }

    //подключаем js полей и интерфейса
    public function js_i()
    {

        $js = $this->core->i_front_js(self::$interface);

        return $js;
    }

    //подключаем css полей и интерфейса
    public function css_i()
    {
        $css = $this->core->i_front_css(self::$interface);

        return $css;
    }

    public function h1(){

    }
    
    //пользовательский метод инициализирует запись, удаление и обновление данных таблицы
    public function callback()
    {
        if (isset($this->request->p['id'])) {
            $this->core->i_update(self::$model, self::$table, $_POST, 1, 1);
        } else {
            $this->core->i_insert(self::$model, self::$table, $_POST, 1, 1);
        }

    }


    public function for_t()
    {
        if (self::$table != '') {
            return $this->core->tablessortw(self::$table, "sorts", 'ASC', '1', self::$section, "0");
        }
    }

    public function no_section()
    {

        if (empty($this->request->g['2'])) {
            if (isset($this->request->p['table_create_t'])) {
                $this->core->insert('taxonomy_t')->insert_arr(array('name' => $this->request->p['table_create_t'] . '_t', 'tables' => $this->core->tr($this->request->p['table_create_t']) . '_t'))->query();
            }
            if (isset($this->request->p['table_del_t'])) {
                $this->core->drop($this->core->table('taxonomy_t', 'tables')->where(array('id' => $this->request->p['table_del_t']))->forq()[0]['tables'])->query();
                $this->core->d('taxonomy_t')->where(array('id' => $this->request->p['table_del_t']))->query();
            }
            $arr = $this->core->table('taxonomy_t')->forq();
            if (count($arr) > 0) {
                return $arr;
            } else {
                return array();
            }
        } else {
            return false;
        }
    }


}