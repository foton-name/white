<?php
  class Filemanager_c extends Filemanager_m{
     
     
public function js_red(){
    $js_v='';
    foreach(glob($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/code/js/*.js") as $js){
        $js=str_replace($GLOBALS["foton_setting"]["path"],'',$js);
        $js_v.='<script src="'.$js.'"></script>';
    }
    return $js_v;
}
public function css_red(){
    $css_v='';
    foreach(glob($GLOBALS["foton_setting"]["path"]."/dev/modul/filemanager/code/css/*.css") as $css){
           $css=str_replace($GLOBALS["foton_setting"]["path"],'',$css);
       $css_v.='<link rel="stylesheet" type="text/css" href="'.$css.'">';
    }
    return $css_v;
}
   
             public function filesp(){

     if(isset($this->request->p['type']) && $this->request->p['type']=='sp'){
 $arr=$this->spisokfile($this->request->p['path'].'/');  for($i=0;$i<count($arr);$i++){ 
if(stristr($arr[$i]['name'], '.') !== FALSE) {$rand=rand(0,500);?>
            <p class='file<?=$i.$rand;?>'><span class='del-f' ids='<?=$i.$rand;?>' path='<?=$arr[$i]['path'];?>'></span><span class='name-f names-f' path='<?=$arr[$i]['path'];?>'><?=$arr[$i]['name'];?></span></p>
            <?}else{$rand=rand(0,500);?>
            <p class='file<?=$i.$rand;?>'><span class='del-f' ids='<?=$i.$rand;?>' path='<?=$arr[$i]['path'];?>'></span><span class='name-f dir-f' ids='<?=$i.$rand;?>' path='<?=$arr[$i]['path'];?>'><?=$arr[$i]['name'];?></span></p>
            <div class='dir<?=$i.$rand;?> dirt'></div>
        <?}}}
}

}