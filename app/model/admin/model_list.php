<?php

  class Model_list extends Model
{

    public function param(){
        $mi = new \Foton\MI;
        $param = $mi->param();
        return $param;
    }

    public function nameinclude()
    {
        return "Интерфейс Список";
    }

    public function after_item_find($filtr_item = null)
    {
        if ($filtr_item != null) {
            $filtr_item = str_replace("name='", "name='find_", $filtr_item);
            $filtr_item = str_replace('not_value_foton', '', $filtr_item);
            $filtr_item = str_replace('default_span', 'filtr_i', $filtr_item);
            return $filtr_item;
        }
    }


}