<?php

  class Interface_list extends Model
{
    public function echotype()
    {
        if (isset($this->request->p['data_ajax']) && isset($this->request->p['html'])) {
            return $this->core->i_select($this->request->p['data_ajax'], $this->request->p['html']);
        }

    }

    public function echotype_cr()
    {
        if (isset($this->request->p['html'])) {
            return $this->core->i_select_create($this->request->p['html']);
        }
    }

    public function remove_file_ajax()
    {
        if (isset($this->request->p['table']) && isset($this->request->p['id'])) {
            $this->core->del_file($this->request->p['table'], $this->request->p['id']);
        }
    }

}