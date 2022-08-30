<?php
  class Filemanager_m extends Model{
    
      public function modul_chmod(){
     return [1,1];
}
      
       public function name_m(){
           return 'Файловый менеджер';
           
       }
         public function dir_m(){
           return '0';
           
       }
     public function spisokfile($path){ 
           $arr=array();
    foreach (glob($path."*") as $filename) {
        if(basename($filename)!='core'){
        if(filesize($filename)<100000){
    $arr[]=array('path'=>$filename,'name'=>basename($filename));
        }}
}
return $arr;
       }
       
     

public function version_up(){
	
return array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"version","modul"=>"filemanager"));	
	
}

public function version(){
	
	return '3';
	
}


public function install(){
		$connect=array("index"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updateindex","modul"=>"filemanager")),
	"m"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatem","modul"=>"filemanager")),
	"c"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatec","modul"=>"filemanager")),
	"a"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatea","modul"=>"filemanager")),
	"css"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatecss","modul"=>"filemanager")),
	"js"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatejs","modul"=>"filemanager")));
	return $connect;
}
public function install_m($data){
	
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/index.php",$data["index"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/filemanager_m.php",$data["m"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/filemanager_c.php",$data["c"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/filemanager_a.php",$data["a"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/js/script.js",$data["js"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/css/style.css",$data["css"]);
}
public function up(){
	$connect=array("index"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updateindex","modul"=>"filemanager")),
	"m"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatem","modul"=>"filemanager")),
	"c"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatec","modul"=>"filemanager")),
	"a"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatea","modul"=>"filemanager")),
	"css"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatecss","modul"=>"filemanager")),
	"js"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"updatejs","modul"=>"filemanager")));
	return $connect;
	}
public function up_m($data){
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/index.php",$data["index"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/filemanager_m.php",$data["m"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/filemanager_c.php",$data["c"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/filemanager_a.php",$data["a"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/js/script.js",$data["js"]);
	file_put_contents($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/css/style.css",$data["css"]);
}
public function del(){
	$connect=array("filest"=>array("host"=>"https://foton.name/api_f.ajaxsite","method"=>"post","date"=>array("key"=>$GLOBALS["foton_setting"]["license"],"func"=>"delete")));
	return $connect;
}
public function del_m($data){

}
}