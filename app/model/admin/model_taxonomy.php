<?php

  class Model_Taxonomy extends Model
{


    public function nameinclude()
    {
        return 'Списки';

    }

    public function interfaces()
    {
        $tb_arr = $this->table_t();
        if (is_array($tb_arr)) {
            foreach ($tb_arr as $table) {
                $arr[$table['tables']] = array("field" => array("id", "name", "tpl", "section", "value", "number", "sorts"),
                    "format" => array("int", "text", "text", "text", "text", "text", "text"),
                    "format_select" => array("ids", "input", "select2:taxonomy", "input", "input", "input", "input"),
                    "key" => "id");
            }
        }
        $arr['taxonomy_t'] = array("field" => array("id", "name", "tables"),
            "format" => array("int", "text", "text"),
            "format_select" => array("ids", "input", "input"),
            "key" => "id");
        return $arr;
    }

    public function table_t()
    {
        return $this->core->table('taxonomy_t', 'tables')->forq();
    }

    public function interface_sp()
    {
        $tb_arr = $this->table_t();
        if (is_array($tb_arr)) {
            foreach ($tb_arr as $table) {
                $arr[$table['tables']] = array(
                    "field" => array("id", "name", "tpl", "section", "value", "number", "sorts"),
                    "format" => array("int", "text", "text", "text", "text", "text", "text"),
                    "name" => array("id", "Название", "Шаблон", "id раздела", "Значение", "Номер раздела", "Сортировка"),
                    "format_select" => array("ids", "input", "select2:taxonomy", "input", "input", "input", "input"),
                    "format_select_list" => array("ids", "text", "text"),
                    "pagination" => "10", "key" => "id");
            }
        }
        $arr['taxonomy_t'] = array("field" => array("id", "name", "tables"),
            "format" => array("int", "text", "text"),
            "format_select" => array("ids", "input", "input"),
            "key" => "id");
        return $arr;
    }


    public function recursion_t($table = null, $model = null, $section = '0')
    {
        if ($table != null && $model != null) {
            $data = array();

            $arrrec = $this->core->tablessortw($table, "sorts", 'ASC', '1', "section", $section);
            if (count($arrrec) > 0) {
                foreach ($arrrec as $val) {
                    include($this->core->include_tpl('taxonomy/taxonomy_start'));
                    $this->recursion_t($table, $model, $val['id']);
                    include($this->core->include_tpl('taxonomy/taxonomy_end'));
                }
            } else {

            }
        }
    }


}