<?php
  class Controller_Files extends Model_Files{
public function js_red(){
    $js_v='';
    foreach(glob($GLOBALS["foton_setting"]["path"]."/app/view/".$GLOBALS['foton_setting']['admindir']."/code/js/*.js") as $js){
        $js=str_replace($GLOBALS["foton_setting"]["path"],'',$js);
        $js_v.='<script src="'.$js.'"></script>';
    }
    return $js_v;
}
public function css_red(){
    $css_v='';
    foreach(glob($GLOBALS["foton_setting"]["path"]."/app/view/".$GLOBALS['foton_setting']['admindir']."/code/css/*.css") as $css){
           $css=str_replace($GLOBALS["foton_setting"]["path"],'',$css);
       $css_v.='<link rel="stylesheet" type="text/css" href="'.$css.'">';
    }
    return $css_v;
}

public function file_update_sistem(){
    if(isset($this->request->p['name'])){
        $arr=$this->mass_files();
        if($this->mass_sh_files()[$this->request->g['2']]=="1"){
            $view=$this->request->p['name'];
            file_put_contents($GLOBALS["foton_setting"]["path"].$arr[$this->request->g['2']],$view);
            $file_path=str_replace('/app/view/','/system/api/tpl/',$arr[$this->request->g['2']]);
            $file_path=str_replace('.tpl','.php',$file_path);
            unlink($GLOBALS["foton_setting"]["path"].$file_path);
            $view=$this->core->cache_foton($view);
            file_put_contents($GLOBALS["foton_setting"]["path"].$file_path,$view);
        }
        file_put_contents($GLOBALS["foton_setting"]["path"].$arr[$this->request->g['2']],$this->request->p['name']);
    }
}
public function sub(){
    if(isset($this->request->g['3'])){
        return true;
    }
    else{
        return false;
    }
}

public function file_path(){
    if(isset($this->request->p['file'])){
        $_SESSION['F']=$this->request->p['file'];
         return $this->request->p['file'];
    }
    else if(isset($_SESSION['F'])){
        return $_SESSION['F'];
    }
    else{
        return false;
    }
}

public function file_redact(){
if($this->request->g['2']){
$arr=$this->mass_files();
$file=file_get_contents($GLOBALS["foton_setting"]["path"].$arr[$this->request->g['2']]);
}
else if($this->file_path()){
$file=file_get_contents($GLOBALS["foton_setting"]["path"].$this->file_path());
}
else{
}
return $file;
}


}