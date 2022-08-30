<?php
  class Panelsections_c extends Panelsections_m{
    
    public function select_sections(){
         
    $arr = array();
    foreach (glob($GLOBALS["foton_setting"]["path"]."/app/model/".$GLOBALS['foton_setting']['sitedir']."/*.php") as $filename) {
        $name_file = basename($filename);
        require_once($filename);
        $name_model = str_replace('.php','',$name_file);
        require_once $filename;
        $obj_m = new $name_model;
        $name = str_replace('model_','',$name_model);
        if($name!='users' && $name!='html'){
             require_once $filename;
             $method = "nameinclude";
             $method2 = "interfaces";
                        // проверяем есть ли данный метод у этой модели, если есть подключаем
                   if(method_exists($name_model,$method) && method_exists($name_model,$method2)){
                       $arr[$name]=$obj_m->$method();
                   }
        }
    
    }
    return $arr;
     }
     
     public function select_user ($str=array()) {
         $arriv = $str;
         $arriv2=array();
         foreach($this->core->select_db("role") as $text){
            $arriv2[$text["text"]]='no';
         }
         foreach($arriv as $name){
            if($name!=''){
                $arriv2[$this->core->select_db("role","name",$name,"text")[0]]='yes';
            }
        }
     return $arriv2;
     }

}