<?php

  class Interface_menu extends Model
{

    public function del_menu()
    {
        if (isset($this->request->p['menu']) && isset($this->request->p['del_id'])) {
            $this->request->p['del_id'] = $this->core->number_foton($this->request->p['del_id']);
            $this->core->d($this->request->p['menu'])->where(array('id' => $this->request->p['del_id']))->query();
        }
    }

    public function idupdates_menu()
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

    public function create_menu()
    {

        if (isset($this->request->p['table']) && isset($this->request->p['model'])) {
            include($this->core->include_tpl('menu/form_start'));
            $arr = array('model' => 'html', 'table' => 'menu', 'interface' => 'sp', 'extra_arr' => array('name' => 'lang'), 'fields_table' => 'field', 'fields_type' => 'format_select', 'create' => true);
            $html = $this->core->i_list_ajax($arr);
            echo $this->core->i_load_ajax($html, null, $arr);
            include($this->core->include_tpl('menu/form_cr_end'));
        }
    }

    public function menu_dop()
    {

        if (isset($this->request->p['table']) && isset($this->request->p['id']) && isset($this->request->p['model'])) {
            include($this->core->include_tpl('menu/form_start'));
            $delete = array('sections');
            $arr = array('model' => 'html', 'table' => 'menu', 'interface' => 'sp', 'extra_arr' => array('name' => 'lang'), 'fields_table' => 'field', 'fields_type' => 'format_select', 'create' => true);
            $html = $this->core->i_list_ajax($arr, null, $delete);
            echo $this->core->i_load_ajax($html, null, $arr);
            include($this->core->include_tpl('menu/id_hid'));
            include($this->core->include_tpl('menu/form_cr_end'));
        }
    }

    public function ajax_menu()
    {
        if (isset($this->request->p['menu_id']) && isset($this->request->p['model']) && isset($this->request->p['menu'])) {
            include($this->core->include_tpl('menu/form_start'));
            $arr = array('model' => 'html', 'table' => 'menu', 'interface' => 'sp', 'extra_arr' => array('name' => 'lang'), 'fields_table' => 'field', 'fields_type' => 'format_select', 'where' => array('id' => $this->request->p['menu_id']));
            $replace = array('id' => 'id');
            $html = $this->core->i_list_ajax($arr, $replace);

            echo $this->core->i_load_ajax($html, null, $arr);
            include($this->core->include_tpl('menu/form_end'));
        }

    }


}