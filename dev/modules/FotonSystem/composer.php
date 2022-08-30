<?php  
namespace FotonSystem;
class composer{
    function __construct($config,$return=null){
        global $core; $this->core = $core;
        if($return!=null){
            $this->path = $this->core->git($GLOBALS["foton_setting"]['path'].'/dev/modules/'.$return.'.php');       
            $this->module = $return;
        }
        $this->config = $config;
    }
    public function copy_mod($source = null,&$arr=array())
    {
        if ($source != null) {
            if ($handle = opendir($source)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        $path = $source . '/' . $file;
                        if (is_file($path)) {
                            $path_file = str_replace($GLOBALS['foton_setting']['path'],'',$path);
                            $arr['file'][] = $path_file;                         
                        } elseif (is_dir($path)) {
                            $path_dir = str_replace($GLOBALS['foton_setting']['path'],'',$path);
                            $arr['dir'][] = $path_dir;
                            $this->copy_mod($path,$arr);
                        }
                        else{}
                    }
                }
                closedir($handle);
                return json_encode($arr,true);
            }
        }

    }

    public function module($keyf,$module,$method=null,$file=null){
        if(isset($key) && $key==$this->config['key']){
          if($method=='list'){
            return json_encode($this->config[0]['module'],true);
          }
          if(isset($module) && $module!=null && isset($this->config['module'][$module]) && file_exists($GLOBALS['foton_setting']['path'].'/dev/modules/'.$module.'.php')){
                 $arr = $this->copy_mod($GLOBALS['foton_setting']['path'].'/dev/modules/'.$module);
                 $config = json_decode(file_get_contents($GLOBALS['foton_setting']['path'].'/dev/modules/FotonSystem/config.json'),true);
                 $arr = json_decode($arr,true);
                 if(isset($config['module'][$module])){
                     $arr['config'] = $config['module'][$module];
                 }
                 $arr = json_encode($arr,true);
                 return $arr;
          }
          else if(isset($file) && $file!=null &&  file_exists($GLOBALS['foton_setting']['path'].$file)){
                 return file_get_contents($GLOBALS['foton_setting']['path'].$file);
          }
          else{
            return 'no file';
        }
    }
    else{
        return 'no license';
    }
    }

    public function drop(){
        if(file_exists($this->path)){
            unlink($this->path);
            $this->core->dir_delete_foton($GLOBALS["foton_setting"]["path"] .'/dev/modules/'. $this->module);
        }
        else{
            return false;
        }
    }

    public function list(){
        return $this->run('list');
    }

    public function update(){
        $this->run('update');
        $return = '';
        if(isset($this->config['php'][$this->module])){
              $return.=$this->shell_mod($this->config['php'][$this->module]);              
        }
        if(isset($this->config['module'][$this->module])){
            foreach($this->config['module'][$this->module] as $module){
                if(empty($this->config['ignore'][$module])){
                    $this->run('update',$module);
                    if(isset($this->config['php'][$module])){
                        $return.=$this->shell_mod($this->config['php'][$module]);
                    }
                }
            }
        }
        return $return;
    }
    public function create(){
        $this->run('create');
        $return = '';
        if(isset($this->config['php'][$this->module])){
            $return.=$this->shell_mod($this->config['php'][$this->module]);              
        }
        if(isset($this->config['module'][$this->module])){
            foreach($this->config['module'][$this->module] as $module){
                if(empty($this->config['ignore'][$module])){
                    $this->run('create',$module);
                    if(isset($this->config['php'][$module])){
                        $return.=$this->shell_mod($this->config['php'][$module]);
                    }
                }
            }
        }
        return $return;
    }
    
    public function shell_mod($arr_c=array()){
      $load='';
      if (stristr(PHP_OS, "win")) {
          foreach($arr_c as $php){
              $load.="Подключите $php .dll в php.ini\n";
          }
          return $load;
      }
      else{
          $load.= shell_exec('sudo apt upgrade');
          $load.= shell_exec('sudo apt update');
          foreach($arr_c as $php){
              $load.= shell_exec('sudo apt-get install '.$php); 
          }
          return $load;
      }
    }
  
    public function run($method,$module=null){ 
        if($module==null){
             $module = $this->module;
        }
        $return='';
        if($method=='list'){
            $arr = array("host" => "https://foton.name/module.ajaxsite","date" => array("key" => $GLOBALS["foton_setting"]["license"],'method'=>$method));
            return json_decode($this->core->update_core($arr),true);  
        }
        else if(isset($this->config['host'][$module])){
             $arr = array("host" => $this->config['host'][$module]["url"],"date" => array("key" => $this->config['host'][$module]["key"], "module" => $module,'method'=>$method));
             $arr_file = array("host" => $this->config['host'][$module]["url"],"date" => array("key" =>$this->config['host'][$module]["key"]));
        }
        else{
             $arr = array("host" => "https://foton.name/module.ajaxsite","date" => array("key" => $GLOBALS["foton_setting"]["license"], "module" =>$module,'method'=>$method));
             $arr_file = array("host" => "https://foton.name/module.ajaxsite","date" => array("key" => $GLOBALS["foton_setting"]["license"]));
        }
       $arr_result = json_decode($this->core->update_core($arr),true);
       $arr_file['date']['file'] = '/dev/modules/'.$module.'.php';
       file_put_contents($GLOBALS['foton_setting']['path'].'/dev/modules/'.$module.'.php',$this->core->update_core($arr_file)); 
        if(!is_dir($GLOBALS['foton_setting']['path'].'/dev/modules/'.$module)) {
            mkdir($GLOBALS['foton_setting']['path'].'/dev/modules/'.$module);
        }
        
        if($method=='create' && is_array($arr_result)){
            if(isset($arr_result['config']) && count($arr_result['config'])>0){
                $config_old = json_decode(file_get_contents($GLOBALS['foton_setting']['path'].'/dev/modules/FotonSystem/config.json'),true);
                $config_old['module'][$module] = $arr_result['config'];
                file_put_contents($GLOBALS['foton_setting']['path'].'/dev/modules/FotonSystem/config.json',$this->core->var_dump($config_old,'J'));
            }
            if(isset($arr_result['dir']) && is_array($arr_result['dir'])){
                foreach($arr_result['dir'] as $dir){
                    if(!is_dir($GLOBALS["foton_setting"]["path"].$dir)) {
                        mkdir($GLOBALS["foton_setting"]["path"].$dir);
                    }
                }
            }
        }
        if(is_array($arr_result)){
            foreach($arr_result['file'] as $file){
                if($method=='update' && file_exists($GLOBALS['foton_setting']['path'].$file)){
                  $return.="module $file exists\n";
                }
                else{
                    $arr_file['date']['file'] = $file;
                    file_put_contents($GLOBALS["foton_setting"]["path"].$file,$this->core->update_core($arr_file)); 
                }
            }
        }
        if($method=='create' && isset($arr_result['config']) && count($arr_result['config'])>0){
            foreach($arr_result['config'] as $file_new){
                $this->run($method,$file_new);
            }
        }
        return $return;
    }    
}