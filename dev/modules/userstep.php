<?php
  class Userstep{
	function __controller($url=true){
		global $core; $this->core = $core;
		if(empty($_SESSION['user_arr_foton'])){
			$_SESSION['user_arr_foton'] = [];
		}
		if($url){
			$_SESSION['user_arr_foton'][] = $_SERVER['REQUEST_URI'];	
		}	
	}

	public function set_arr($resource=null,$where=null,$operand=array()){
		if(is_array($resource) && count($resource)>0){
			if(is_array($where) && count($where)>0){
				$set = true;
				foreach($where as $key=>$val){
					if(isset($operand[$key]) && $operand[$key]=='%'){
						if(isset($resource[$key]) && strrpos($resource[$key],$val)==false){
							$set = false;
						}
					}
					else if(isset($operand[$key]) && $operand[$key]=='!'){
						if(isset($resource[$key]) && $resource[$key]==$val){
							$set = false;
						}
					}
					else if(isset($resource[$key]) && $resource[$key]!=$val){
						$set = false;
					}
					else{
						
					}
				}
				if($set){
					$_SESSION['user_arr_foton'][] = $resource;
					return true;
				}
				else{
					return false;
				}
			}
			else{
				$_SESSION['user_arr_foton'][] = $resource;
				return true;
			}
		}
		else if(is_string($resource)){
			if(is_array($where) && count($where)>0){
				$set = true;
				foreach($where as $key=>$val){
					if(isset($operand[$key]) && $operand[$key]=='%'){
						if(isset($GLOBALS['_'.$resource][$key]) && strrpos($GLOBALS['_'.$resource][$key],$val)==false){
							$set = false;
						}
					}
					else if(isset($operand[$key]) && $operand[$key]=='!'){
						if(isset($GLOBALS['_'.$resource][$key]) && $GLOBALS['_'.$resource][$key]==$val){
							$set = false;
						}
					}
					else if(isset($GLOBALS['_'.$resource][$key]) && $GLOBALS['_'.$resource][$key]!=$val){
						$set = false;
					}
					else{
						
					}
				}
				if($set){
					$_SESSION['user_arr_foton'][] = $GLOBALS['_'.$resource];
					return true;
				}
				else{
					return false;
				}
			}
			else{
				$_SESSION['user_arr_foton'][] = $GLOBALS['_'.$resource];
				return true;
			}
		}
		else{
			return false;
		}
	}

	public function set_string($resource=null){
		if($resource!=null){
			$_SESSION['user_arr_foton'][] = $resource;
			return true;
		}
		else{
			return false;
		}
	}

	public function clear(){
		if(isset($_SESSION['user_arr_foton'])){
			unset($_SESSION['user_arr_foton']);
		}
	}

	public function get(){
		if(isset($_SESSION['user_arr_foton'])){
			return $_SESSION['user_arr_foton'];
		}
		else{
			return false;
		}
	}

	public function inset($table=null,$field=null,$model=null){
		if($table!=null && $field!=null && $model){
			if(isset($_SESSION['user_arr_foton'])){
				if($model!=null){
					return $this->core->dbins($table,[$field=>json_encode($_SESSION['user_arr_foton'])],$model);
				}
				else{
					return $this->core->dbins($table,[$field=>json_encode($_SESSION['user_arr_foton'])]);
				}
				
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
}