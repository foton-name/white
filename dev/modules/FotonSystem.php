<?php
class FotonSystem{
    function __construct(){
        global $core; $this->core = $core;
    }
    public function composer($name=null){
        $config = json_decode(file_get_contents($GLOBALS['foton_setting']['path'].'/dev/modules/FotonSystem/config.json'),true);
        if(empty($config['ignore'][$name])){
            $class = $this->core->m_obj('FotonSystem/composer',[$config,$name]);
            return $class;
        }
    }
}