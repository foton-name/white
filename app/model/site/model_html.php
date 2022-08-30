<?php

  class Model_html extends Model
{

    public function names()
    {
        $arr = array("mapofsite" => "Карта сайта", "graph" => "Графики", "menu" => "Меню сайта", "taxonomy" => "Форматы списка", "html" => "Типы данных", "exfield" => "Доп. поля", "mediateka" => "Фотографии", "router" => "ЧПУ страниц", "seo" => "Оптимизация", "buttonred" => "Кнопки редактора");
        return $arr;
    }


    public function nameinclude()
    {
        return 'Настройки системы';

    }
    
    public function before_select_html($value,$name){
        $valid = new \Foton\Validate;
        return $valid->echo_type($value);
    }

    public function interfaces()
    {
        $arr = array("mapofsite" => array("field" => array("id", "views", "tables", "sections"),
            "name" => array("id", "Представление", "Таблица", "Раздел"),
            "format_select" => array("ids", "input", "input", "checkbox"),
            "format" => array("int", "text", "text", "text"),
            "key" => "id"),
            "graph" => array("field" => array("id", "tables", "wheres", "fields", "color", "kod", "funcs", "names", "graph"),
                "name" => array("id", "Таблица", "Условие", "Поля", "Цвет", "код", "Функция", "Название", "Номер(1,2,3)"),
                "format_select" => array("ids", "input", "input", "input", "color", "input", "input", "input", "input"),
                "format" => array("int", "text", "text", "text", "text", "text", "text", "text", "text"),
                "key" => "id"),
            "menu" => array("field" => array("id", "name", "href", "sections", "title", "sorts"),
                "name" => array("id", "Название", "Ссылка", "id раздела", "Заголовок", "Сортировка"),
                "format_select" => array("ids", "input", "input", "input", "input", "input"),
                "format" => array("int", "text", "text", "text", "text", "text"),
                "key" => "id"),
            "html" => array("field" => array("id", "name", "kod", "html", "argument", "function"),
                "name" => array("id", "Название", "Код", "html", "Аргументы", "Функция"),
                "format_select" => array("ids", "input", "input", "textarea", "input", "textarea"),
                "format" => array("int", "text", "text", "text", "text", "text"),
                "key" => "id"),
            "exfield" => array("field" => array("id", "name", "excode"),
                "name" => array("id", "Название", "Описание"),
                "format_select" => array("ids", "input", "html"),
                "format" => array("int", "text", "text"),
                "key" => "id"),
            "mediateka" => array("field" => array("id", "kod", "photos"),
                "name" => array("id", "код", "Фото"),
                "format_select" => array("ids", "input", "img"),
                "format" => array("int", "text", "text"),
                "key" => "id"),
            "router" => array("field" => array("id", "routs", "view"),
                "name" => array("id", "ЧПУ", "Представление"),
                "format_select" => array("ids", "input", "input"),
                "format" => array("int", "text", "text"),
                "key" => "id"),
            "seo" => array("field" => array("id","dir", "views", "title", "keywords", "description"),
                "name" => array("id","Директория", "Представление", "Заголовок", "Ключевые слова", "Описание"),
                "format_select" => array("ids", "input", "input", "input", "input", "textarea"),
                "format" => array("int", "text", "text", "text", "text", "text"),
                "key" => "id"),
            "buttonred" => array("field" => array("id", "func", "lists", "format", "text"),
                "name" => array("id", "Функция", "Список", "Формат", "Текст"),
                "format_select" => array("ids", "input", "input", "input", "input"),
                "format" => array("int", "text", "text", "text", "text"),
                "key" => "id"),
            "taxonomy" => array("field" => array("id", "name", "kod", "prefix", "body", "suffix"),
                "name" => array("id", "Название", "Код", "Префикс", "Тело", "Постфикс"),
                "format_select" => array("ids", "input", "input", "textarea", "textarea", "textarea"),
                "format" => array("int", "text", "text", "text", "text", "text"),
                "key" => "id"));
        return $arr;
    }


    public function interface_sp()
    {
        $arr = array("mapofsite" => array("field" => array("id", "views", "tables", "sections"),
            "format" => array("int", "text", "text", "text"),
            "name" => array("id", "Представление", "Таблица", "Раздел"),
            "format_select" => array("ids", "input", "input", "checkbox"),
            "format_select_list" => array("ids", "text", "text"),
            "field_filter" => array("id", "views", "tables"),
            "text_filter" => array("id", "Представление", "Таблица"),
            "format_filter" => array("ids", "input", "input"),
            "format_sort" => array("ids", "submit", "submit"),
            "pagination" => "10", "key" => "id"),

            "taxonomy" => array("field" => array("id", "name", "kod", "prefix", "body", "suffix"),
                "format" => array("int", "text", "text", "text", "text", "text"),
                "name" => array("id", "Название", "Код", "Префикс", "Тело", "Постфикс"),
                "format_select" => array("ids", "input", "input", "textarea", "textarea", "textarea"),
                "format_select_list" => array("ids", "text", "text"),
                "field_filter" => array("id", "name", "kod"),
                "text_filter" => array("id", "Название", "Код"),
                "format_filter" => array("ids", "input", "input"),
                "format_sort" => array("ids", "submit", "submit"),
                "pagination" => "10", "key" => "id"),

            "graph" => array("field" => array("id", "tables", "wheres", "fields", "color", "kod", "funcs", "names", "graph"),
                "format" => array("int", "text", "text", "text", "text", "text", "text", "text", "text"),
                "name" => array("id", "Таблица", "Условие", "Поля", "Цвет", "код", "Функция", "Название", "Номер(1,2,3)"),
                "format_select" => array("ids", "input", "input", "input", "color", "input", "input", "input", "input"),
                "format_select_list" => array("ids", "text", "text"),
                "field_filter" => array("id", "tables", "wheres"),
                "text_filter" => array("id", "Таблица", "Условие"),
                "format_filter" => array("ids", "input", "input"),
                "format_sort" => array("ids", "submit", "submit"),
                "pagination" => "10", "key" => "id"),

            "menu" => array("field" => array("id", "name", "href", "sections", "title", "sorts"),
                "format" => array("int", "text", "text", "text", "text", "text"),
                "name" => array("id", "Название", "Ссылка", "id раздела", "Заголовок", "Сортировка"),
                "format_select" => array("ids", "input", "input", "input", "input", "input"),
                "format_select_list" => array("ids", "text", "text"),
                "field_filter" => array("id", "name", "href"),
                "text_filter" => array("id", "Название", "Ссылка"),
                "format_filter" => array("ids", "input", "input"),
                "format_sort" => array("ids", "submit", "submit"),
                "pagination" => "10", "key" => "id"),

            "html" => array("field" => array("id", "name", "kod", "html", "argument", "function"),
                "format" => array("int", "text", "text", "text", "text", "text"),
                "name" => array("id", "Название", "Код", "html", "Аргументы", "Функция"),
                "format_select" => array("ids", "input", "input", "textarea", "input", "textarea"),
                "format_select_list" => array("ids", "text", "text", "text"),
                "field_filter" => array("id", "name", "kod", "argument"),
                "text_filter" => array("id", "Название", "Код", "Аргументы"),
                "format_filter" => array("ids", "input", "input", "input"),
                "format_sort" => array("ids", "submit", "submit", "submit"),
                "pagination" => "10", "key" => "id"),

            "router" => array("field" => array("id", "routs", "view"),
                "format" => array("int", "text", "text"),
                "name" => array("id", "ЧПУ", "Представление"),
                "format_select" => array("ids", "input", "input"),
                "format_select_list" => array("ids", "text", "text"),
                "field_filter" => array("id", "routs", "view"),
                "text_filter" => array("id", "ЧПУ", "Представление"),
                "format_filter" => array("ids", "input", "input"),
                "format_sort" => array("ids", "submit", "submit"),
                "pagination" => "10", "key" => "id"),

            "seo" => array("field" => array("id","dir", "views", "title", "keywords", "description"),
                "name" => array("id","Директория", "Представление", "Заголовок", "Ключевые слова", "Описание"),
                "format_select" => array("ids", "input", "input", "input", "input", "textarea"),
                "format" => array("int", "text", "text", "text", "text", "text"),
                "format_select_list" => array("ids", "text", "text", "text"),
                "field_filter" => array("id", "views", "title", "keywords"),
                "text_filter" => array("id", "Представление", "Заголовок", "Ключевые слова"),
                "format_filter" => array("ids", "input", "input", "input"),
                "format_sort" => array("ids", "submit", "submit", "submit"),
                "pagination" => "10", "key" => "id"),

            "buttonred" => array("field" => array("id", "func", "lists", "format", "text"),
                "format" => array("int", "text", "text", "text", "text"),
                "name" => array("id", "Функция", "Список", "Формат", "Текст"),
                "format_select" => array("ids", "input", "input", "input", "input"),
                "format_select_list" => array("ids", "text", "text"),
                "field_filter" => array("id", "func", "lists"),
                "text_filter" => array("id", "Функция", "Список"),
                "format_filter" => array("ids", "input", "input"),
                "format_sort" => array("ids", "submit", "submit"),
                "pagination" => "10", "key" => "id"));
        return $arr;
    }

    public function before_ins_seo($arr=false){
        if(isset($arr)){
            if(!file_exists($this->core->git('app/view/'.$arr['dir'].'/seo'))){
                mkdir($this->core->git('app/view/'.$arr['dir'].'/seo'));
            }
            foreach($arr as $key=>$view){
                if($key!='dir' && $key!='views' && $key!='id'){
                    if(!file_exists($this->core->git('app/view/'.$arr['dir'].'/seo/'.$key))){
                        mkdir($this->core->git('app/view/'.$arr['dir'].'/seo/'.$key));
                    }
                    $path = $this->core->git('app/view/'.$arr['dir'].'/seo/'.$key.'/'.$arr['views'].'.php');
                    $view = $this->core->cache_seo($view,$arr['dir']);
                    file_put_contents($path,$view);
                }
            }
        }
        return $arr;
    }

    public function before_up_seo($arr){
        if(isset($arr)){
            if(!file_exists($this->core->git('app/view/'.$arr['dir'].'/seo'))){
                mkdir($this->core->git('app/view/'.$arr['dir'].'/seo'));
            }
            foreach($arr as $key=>$view){
                if($key!='dir' && $key!='views' && $key!='id'){
                    if(!file_exists($this->core->git('app/view/'.$arr['dir'].'/seo/'.$key))){
                        mkdir($this->core->git('app/view/'.$arr['dir'].'/seo/'.$key));
                    }
                    $path = $this->core->git('app/view/'.$arr['dir'].'/seo/'.$key.'/'.$arr['views'].'.php');
                    $view = $this->core->cache_seo($view,$arr['dir']);
                    file_put_contents($path,$view);
                }
            }
        }
        return $arr;
    }
    

    public function drop_interface()
    {
        $arr = array();
        return $arr;
    }

    public function graph_chmod(){
         return [1,3];

    }

    public function menu_chmod(){
         return [1,3,2];

    }

    public function taxonomy_chmod(){
         return [1,3];

    }

    public function html_chmod(){
         return [1];

    }

    public function exfield_chmod(){
         return [1,2];

    }

    public function mediateka_chmod(){
         return [1,2];

    }

    public function router_chmod(){
         return [1,2];

    }

    public function seo_chmod(){
         return [1,2];

    }

    public function mapofsite_chmod(){
         return [1,2];

    }

    public function buttonred_chmod(){
         return [1];

    }


    public function taxonomy($table = null, $name = null, $val = null, $section = 0)
    {

        if ($table != null) {
            return $this->widget->taxonomy->index($table, $name, $val, $section);

        }
    }

    public function htmlredactor($value = null, $name = null)
    {
        if ($name != null) {
            $value = htmlspecialchars_decode($value, ENT_COMPAT);
            return $this->widget->htmlredactor->index($value, $name);
        }
    }

}