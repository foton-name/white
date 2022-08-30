<?php   class Controller_zip extends Model_zip
{
	public function zipheader(){
        if($_SESSION['login'] && $this->request->g['file']){
           $file = $GLOBALS['foton_setting']['path'].'/'.$GLOBALS['foton_setting']['backup'].'/'.$this->request->g['file'];  
           return $this->core->load($this->core->git($file)); 

        } 
        else if($_SESSION['login'] && $this->request->g['del']){
           $file = $GLOBALS['foton_setting']['path'].'/'.$GLOBALS['foton_setting']['backup'].'/'.$this->request->g['del'];  
           unlink($file);
           header("Location: ".$_SERVER['HTTP_REFERER']); 
        }
        else{

        }
    }
}