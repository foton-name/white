<?php
namespace Foton;

class Custom{
    public function __construct(){
		global $core;
		$this->core = $core;
    }
    public function __call($name, $arg){
         if(method_exists($this->core,$name)){
            return $this->core->$name(...array_values($arg));
        }
        else{
            $this->log('methods '.$name.' undefined in core');
        }
    }
    
}