<?php
  class Filemanager_a extends Filemanager_c{
public function saves_p(){
    if(isset($this->request->p['text']) && isset($this->request->p['path'])){
        if(strrpos($this->request->p['path'],'/filemanager/filemanager_a.ph')===false && strrpos($this->request->p['path'],'/filemanager/js/script.j')===false){
            $this->request->p['text'] = str_replace('#slashes#',"\\",$this->request->p['text']);
            file_put_contents($this->request->p['path'],$this->request->p['text']);
        }
    }
}
public function filesp2(){

    echo $this->filesp();
}
public function dir_delete($path=null)
{
    if($path!=null){
       return $this->core->dir_delete_foton($path);
    }
}
public function delete_dir(){
if(isset($this->request->p['path'])){
return $this->dir_delete($this->request->p['path']);
}
}
public function file_change(){
  if(isset($this->request->p['path']) && isset($this->request->p['codes'])){
    $file = file_get_contents($this->request->p['path']);
    if($file!=$this->request->p['codes']){
      echo 'yes';
    }
    else{
      echo 'no';
    }
  }
}
  
 public function createsfile(){

if(isset($this->request->p['path'])){
if($this->request->p['path']=='main'){
    $path = $GLOBALS['foton_setting']['path'];
}
else{
    $path = $this->request->p['path'];
}
$pathnew=$path.'/'.$this->request->p['file'];
if(isset($this->request->p['format']) && $this->request->p['format']=='file'){
    if(strrpos($pathnew,'.php')){
file_put_contents($pathnew,'<?php ?>');
}
else{
    file_put_contents($pathnew,' ');
}

}
else{

mkdir($pathnew, 0755);
}

}


}


    public function filepath(){
        $file=file_get_contents($this->request->p['path']);
        $name=preg_replace('#([^a-zA-Z]+)#','',$this->request->p['path']);
        echo '<div class="item-lists" path="'.$this->request->p['path'].'"><div class="pole-f">';
        require_once $this->core->git('dev/modul/filemanager/editor.php');
        echo '<input type="button" class="saves-f" value="Сохранить"><input type="button" class="otk-f" value="Откатить"></div></div>';
    }
    public function filenames(){
        echo '<div class="head-f"><div class="open-f input-wrapper" data-title="'.$this->request->p['path'].'" path="'.$this->request->p['path'].'">'.$this->request->p['name'].'<span class="close-file"><img src="/dev/modul/filemanager/css/close.svg"></span></div></div>';
    }


}