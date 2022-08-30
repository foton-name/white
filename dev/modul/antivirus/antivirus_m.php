<?php
  class Antivirus_m extends Model{
       public function name_m(){
           return 'Антивирус';
           
       }
       public function modul_chmod(){
     return [1,2];
}

         public function dir_m(){
           return '0';
           
       }


public function version_up(){
	
return array("host"=>"https://foton.name/api_f.ajaxsite","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"version","modul"=>"antivirus"));	
	
}

public function version(){
	
	return '1';
	
}

public function install(){
	$connect=array("index"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updateindex","modul"=>"antivirus")),
	"m"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatem","modul"=>"antivirus")),
	"c"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatec","modul"=>"antivirus")),
	"a"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatea","modul"=>"antivirus")),
	"css"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatecss","modul"=>"antivirus")),
	"js"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatejs","modul"=>"antivirus")));
	return $connect;
}
public function install_m ($data=null) {
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/index.php",$data["index"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/antivirus_m.php",$data["m"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/antivirus_c.php",$data["c"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/antivirus_a.php",$data["a"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/js/script.js",$data["js"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/css/style.css",$data["css"]);
}
public function up(){
	$connect=array("index"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updateindex","modul"=>"antivirus")),
	"m"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatem","modul"=>"antivirus")),
	"c"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatec","modul"=>"antivirus")),
	"a"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatea","modul"=>"antivirus")),
	"css"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatecss","modul"=>"antivirus")),
	"js"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatejs","modul"=>"antivirus")));
	return $connect;
	}
public function up_m ($data=null) {
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/index.php",$data["index"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/antivirus_m.php",$data["m"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/antivirus_c.php",$data["c"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/antivirus_a.php",$data["a"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/js/script.js",$data["js"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/antivirus/css/style.css",$data["css"]);
}
public function del(){
	$connect=array("filest"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"delete")));
	return $connect;
}
public function del_m ($data=null) {

}
}
?>