<?php
namespace Foton;

trait Custom_isValid{

}

trait Custom_Validate{
	public function translate($text,$ex=null){
	    $core = new Core;
	    return $core->translate($text,$ex);
	}
}