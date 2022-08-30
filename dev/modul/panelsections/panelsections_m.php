<?php
  class Panelsections_m extends Model{
    
   public $dir_m='panelsections';
    public function modul_chmod(){
     return [1,1];
}
        public function name_m(){
           return 'Разделы панели';
           
       }
         public function dir_m(){
           return '0';
           
       }
        


public function version_up(){
	
    return array("host"=>"https://foton.name/api_f.ajaxsite","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"version","modul"=>"panelsections"));	
	
}

public function version(){
	
	return '1';
	
}


public function install(){
		$connect=array("index"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updateindex","modul"=>"panelsections")),
	"m"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatem","modul"=>"panelsections")),
	"c"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatec","modul"=>"panelsections")),
	"a"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatea","modul"=>"panelsections")),
	"css"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatecss","modul"=>"panelsections")),
	"js"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatejs","modul"=>"panelsections")));
	return $connect;
}
public function install_m ($data=null) {
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/index.php",$data["index"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/panelsections_m.php",$data["m"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/panelsections_c.php",$data["c"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/panelsections_a.php",$data["a"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/js/script.js",$data["js"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/css/style.css",$data["css"]);
}
public function up(){
	$connect=array("index"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updateindex","modul"=>"panelsections")),
	"m"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatem","modul"=>"panelsections")),
	"c"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatec","modul"=>"panelsections")),
	"a"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatea","modul"=>"panelsections")),
	"css"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatecss","modul"=>"panelsections")),
	"js"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatejs","modul"=>"panelsections")));
	return $connect;
	}
public function up_m ($data=null) {
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/index.php",$data["index"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/panelsections_m.php",$data["m"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/panelsections_c.php",$data["c"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/panelsections_a.php",$data["a"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/js/script.js",$data["js"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/panelsections/css/style.css",$data["css"]);
}
public function del(){
	$connect=array("filest"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"delete")));
	return $connect;
}
public function del_m ($data=null) {
	//file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/upmodul/del.php",$data["filest"]);
}
    
}