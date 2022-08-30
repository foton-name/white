<?php

  class Model_menu extends Model
{


    public function nameinclude()
    {
        return 'Меню сайта';

    }

    public function recursion_menu($table = null, $model = null, $razdel = '0')
    {
        if ($table != null && $model != null) {
            $data = array();

            $arrrec = $this->core->tablessortw($table, "sorts", 'ASC', '1', "sections", $razdel);
            if (count($arrrec) > 0) {
                foreach ($arrrec as $val) {
                    include($this->core->include_tpl('menu/menu_start'));
                    $this->recursion_menu($table, $model, $val['id']);
                    include($this->core->include_tpl('menu/menu_end'));
                }
            } else {

            }
        }
    }


}