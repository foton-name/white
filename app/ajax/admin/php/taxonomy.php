<?php

  class Interface_Taxonomy extends Model
{

    public function del_t()
    {
        if (isset($this->request->p['table']) && isset($this->request->p['del_id'])) {
            $this->request->p['del_id'] = $this->core->number_foton($this->request->p['del_id']);
            $this->core->d($this->request->p['table'])->where(array('id' => $this->request->p['del_id']))->query();
        }
    }

    public function idupdates_t()
    {
        //обновляем записи в таблице меню
        if (isset($this->request->p['sort']) && isset($this->request->p['id']) && isset($this->request->p['table'])) {
            $sort = $this->request->p['sort'];
            $id = $this->request->p['id'];
            $sort = substr($sort, 1);
            $id = substr($id, 1);
            $sort = explode(',', $sort);
            $id = explode(',', $id);
            $table = $this->request->p['table'];
            foreach ($id as $key => $ids) {
                $this->core->up($table, "sorts='" . $sort[$key] . "'")->where(array('id' => $ids))->query();
            }
        }
    }

    public function create_t()
    {

        if (isset($this->request->p['table']) && isset($this->request->p['model'])) {
            include($this->core->include_tpl('taxonomy/form_start'));
            $arr = array('model' => 'Taxonomy', 'table' => $this->request->p['table'], 'interface' => 'sp', 'extra_arr' => array('name' => 'lang'), 'fields_table' => 'field', 'fields_type' => 'format_select', 'create' => true);
            $html = $this->core->i_list_ajax($arr);
            echo $this->core->i_load_ajax($html, null, $arr);
            include($this->core->include_tpl('taxonomy/form_cr_end'));
        }
    }

    public function dop_t()
    {

        if (isset($this->request->p['table']) && isset($this->request->p['id']) && isset($this->request->p['model'])) {
            include($this->core->include_tpl('taxonomy/form_start'));
            $delete = array('section');
            $arr = array('model' => 'Taxonomy', 'table' => $this->request->p['table'], 'interface' => 'sp', 'extra_arr' => array('name' => 'lang'), 'fields_table' => 'field', 'fields_type' => 'format_select', 'create' => true);
            $html = $this->core->i_list_ajax($arr, null, $delete);
            echo $this->core->i_load_ajax($html, null, $arr);
            include($this->core->include_tpl('taxonomy/id_hid'));
            include($this->core->include_tpl('taxonomy/form_cr_end'));
        }
    }

    public function ajax_t()
    {
        if (isset($this->request->p['menu_id']) && isset($this->request->p['model']) && isset($this->request->p['table'])) {
            include($this->core->include_tpl('taxonomy/form_start'));
            $arr = array('model' => 'Taxonomy', 'table' => $this->request->p['table'], 'interface' => 'sp', 'extra_arr' => array('name' => 'lang'), 'fields_table' => 'field', 'fields_type' => 'format_select', 'where' => array('id' => $this->request->p['menu_id']));
            $replace = array('id' => 'id');
            $html = $this->core->i_list_ajax($arr, $replace);

            echo $this->core->i_load_ajax($html, null, $arr);
            include($this->core->include_tpl('taxonomy/form_end'));
        }

    }


}