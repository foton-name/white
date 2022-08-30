<?php

  class Controller_Menu extends Model_Menu implements \Foton\Face{
    public static $interface;
    public static $model;
    public static $table;
    public static $interface_arr;
    public static $type;
    public static $sections;

    public function __constructor()
    {
        self::$interface = 'menu';
        self::$model = 'html';
        self::$table = 'menu';
        self::$type = "format_select";
        self::$sections = 'sections';

    }

    public function model()
    {
        return self::$model;
    }

    public function table()
    {
        return self::$table;
    }
    public function ready(){

    }
    public function h1(){

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

    //пользовательский метод инициализирует запись, удаление и обновление данных таблицы
    public function callback()
    {
        if (isset($this->request->p['id'])) {
            $this->core->i_update(self::$model, self::$table, $_POST, 1, 1);
        } else {
            $this->core->i_insert(self::$model, self::$table, $_POST, 1, 1);
        }

    }

    public function for_menu()
    {
        return $this->core->tablessortw(self::$table, "sorts", 'ASC', '1', self::$sections, "0");
    }


}