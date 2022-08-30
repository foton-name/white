<?php
  if($_SESSION['login'] && $this->request->g['file']){
    $file = $GLOBALS['foton_setting']['path'].'/'.$GLOBALS['foton_setting']['backup'].'/'.$this->request->g['file'];  
    $file2 = $GLOBALS['foton_setting']['path'].'/'.$this->request->g['file'];   
    copy($file,$file2); 
    header("Location: /".$this->request->g['file']); 

} 
else if($_SESSION['login'] && $this->request->g['del']){
    $file = $GLOBALS['foton_setting']['path'].'/'.$GLOBALS['foton_setting']['backup'].'/'.$this->request->g['del'];  
    unlink($file);
    header("Location: ".$_SERVER['HTTP_REFERER']); 
}
else{ 
    echo 'Файл не найден';
}
?>