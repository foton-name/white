<?php
  namespace Foton;

class Event{
	public $data;
    public function __construct($data=null){
    	if($data!=null){
	        $this->data = $data;
	    }
    }
    // public function before_Core_test($x,$y)
    //   {
    //     return array($x+8,$y*2);
    //   }
    // public function after_Core_test($x)
    //   {
    //      return $x+20;
    //   }
 
}