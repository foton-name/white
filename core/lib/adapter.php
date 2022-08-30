<?php
  namespace Foton;

interface Format{    
    public function ajaxsite($get,$page);
    public function ajaxadmin($get,$page);
    public function face($get,$page);
    public function tpl($get,$page);
    public function ajax($get,$page);
    public function modul($get,$page);
    public function xml($get,$page);
    public function json($get,$page);
    public function mvc($get,$page);
}


interface Face{
	// javascript file type data
	public function js_i();
	// css file type data
	public function css_i();
	//heading page
	public function h1();
	//create/alter/drop user table
	public function ready();
	//save/delete/update user records
	public function callback();
}

class Render extends RenderLib implements Format{	
    function __call($name,$arg){
        return $this->plain($arg[0],$arg[1],$name);
    }

    public function plain($get,$page,$format){
	     $path = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_mvc.$GLOBALS["foton_setting"]['route'][$format]['dir']."/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.php");
        if(is_array($GLOBALS["foton_setting"]['route'][$format]['header']) && !headers_sent()){
    		foreach($GLOBALS["foton_setting"]['route'][$format]['header'] as $header){
    		    if(file_exists($path)){
        		    $header = str_replace('[length]',filesize($path),$header);
    		    }
    		    $header = str_replace('[url]',$_SERVER['REQUEST_URI'].'.'.$format,$header);
    			header($header);
    		}
    	}
    	else{
		    header($GLOBALS["foton_setting"]['route'][$format]['header']);
		}
		$data = $get;
	    $c_p = '\controller_'.$page;
        $m_p = '\model_'.$page;
        $g_p = '\Controller_Globals';
         if(class_exists($g_p)){
            $glob = new $g_p;
        }
        if(class_exists($c_p)){
            $controller_class = new $c_p;
        }
        if(class_exists($m_p)){
            $model_class = new $m_p;
		}
		if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS["foton_setting"]['route'][$format]['dir']."/".$GLOBALS['foton_setting']['lang'].'/'.$page."_view.php"))){
			require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS["foton_setting"]['route'][$format]['dir']."/".$GLOBALS['foton_setting']['lang'].'/'.$page."_view.php"); 
		}
		if(file_exists($path)){
    		require_once $path;
		}
		else{
		    $this->router->ErrorPage404();
		}
	}

    public function ajaxsite($get,$page){ 
        $arr = $this->globdata($GLOBALS['foton_setting']['sitedir']);
        $global = $arr['global'];
        print_r($get);
    }

    public function ajaxadmin($get,$page){
        $arr = $this->globdata($GLOBALS['foton_setting']['admindir']);
        $global = $arr['global'];
        print_r($get);
    }
	public function face($get,$page){
	    $arr = $this->globdata($GLOBALS['foton_setting']['admindir']);
        $global = $arr['global'];
        print_r($get);
    }
    public function tpl($get,$page){
    	if(isset($GLOBALS["foton_setting"]["type"]) && $GLOBALS["foton_setting"]["type"]=='file'){
			$dir = $GLOBALS['foton_setting']['admindir'];
		}
		else{
			$dir = $GLOBALS['foton_setting']['sitedir'];
		}
        $arr = $this->globdata($dir);
        $global = $arr['global'];
        print_r($get);
    }
    public function ajax($get,$page){
        $arr = $this->globdata($GLOBALS['foton_setting']['admindir']);
        $global = $arr['global'];
    	print_r($get);
	}
	public function modul($get,$page){
    	$get['modules_status'] = 'modul';
        $arr = $this->preload($get,$page);
		$this->url=$this->router->path($page,'modul');
	    require_once $this->config->core->git('app/view/'.$GLOBALS['foton_setting']['templates']);      
	}	
	public function xml($get,$page){
		if(!headers_sent())
		{
			header("Content-Type: text/xml");
		}
		$data = $get;
	    $c_p = '\controller_'.$page;
        $m_p = '\model_'.$page;
        $g_p = '\Controller_Globals';
         if(class_exists($g_p)){
            $glob = new $g_p;
        }
        if(class_exists($c_p)){
            $controller_class = new $c_p;
        }
        if(class_exists($m_p)){
            $model_class = new $m_p;
		}
		if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/xml/".$GLOBALS['foton_setting']['lang'].'/'.$page."_view.php"))){
			require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/xml/".$GLOBALS['foton_setting']['lang'].'/'.$page."_view.php"); 
		}
		$path = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_mvc."xml/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.php");
		$pathtpl = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_mvc."xml/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.tpl");
			
		if(file_exists($path)){
    		require_once $path;
		}
		else if(file_exists($pathtpl)){
            require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/system/api/tpl/xml/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.php");
		}
		else{
		    $this->router->ErrorPage404();
		}
	}
	
	public function json($get,$page){
		if(!headers_sent())
		{
			header('Content-Type: application/json');
		}
        $c_p = '\controller_'.$page;
        $m_p = '\model_'.$page;
        $g_p = '\Controller_Globals';
         if(class_exists($g_p)){
            $glob = new $g_p;
        }
        if(class_exists($c_p)){
            $controller_class = new $c_p;
        }
        if(class_exists($m_p)){
            $model_class = new $m_p;
        }
		$data = $get;
		if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/json/".$GLOBALS['foton_setting']['lang'].'/'.$page."_view.php"))){
			require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/json/".$GLOBALS['foton_setting']['lang'].'/'.$page."_view.php"); 
		}
		$path = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_mvc."json/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.php");
		$pathtpl = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_mvc."json/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.tpl");
		if(file_exists($path)){
    		require_once $path;
		}
		else if(file_exists($pathtpl)){
            require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/system/api/tpl/json/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.php");
		}
		else{
		    $this->router->ErrorPage404();
		}

    }
    public function mvc($get,$page){
        $arr = $this->preload($get,$page);
     	$this->url=$this->router->path($page,$arr['dir']);
   	    if(file_exists($this->core->git($GLOBALS['foton_setting']['path'].'/app/view/'.$arr['dir'].'/'.$GLOBALS['foton_setting']['templates'])) && $_SERVER['REQUEST_URI']!='/'.$GLOBALS['foton_setting']['admin'].'/'){
		    require_once $this->config->core->git($GLOBALS['foton_setting']['path'].'/app/view/'.$arr['dir'].'/'.$GLOBALS['foton_setting']['templates']);
		}
		else{
       	    require_once $this->config->core->git('app/view/'.$GLOBALS['foton_setting']['templates']);
		}    	
	}
}

class dataReturn extends dataReturnLib implements Format{
	public $controller_class;
	public $model_class;
	
	function __call($name,$arg){
        return $this->plain($arg[0],$arg[1],$name);
    }

	public function plain($get,$page,$format){ 
		if(isset($get[1]) && $get[1]!=''){
		   $path=$page;
		   $view_name = $path.'_view';
		   $error=true;
		   $controller_name = 'Controller_'.$path;
		   $model_name = 'Model_'.$path; 
		   if($path!==false){ 
			    $view_file = strtolower($view_name).'.tpl';
			    $view_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]['route'][$format]['dir']."/".$GLOBALS["foton_setting"]["sitedir"]."/".$view_file);		    
			    $view_filephp = strtolower($view_name).'.php';
			    $view_pathphp = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]['route'][$format]['dir']."/".$GLOBALS["foton_setting"]["sitedir"]."/".$view_filephp);
			    if (file_exists($view_path) || file_exists($view_pathphp)) {
			        $model_file = $GLOBALS["foton_setting"]["sitedir"]."/model_".$path.'.php';
			        $model_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_m.$model_file);
			        if(file_exists($model_path))
			        { 
			            require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_m.$model_file);
			        }
			        else{
					    $model_name="Model";
					}
					$arr = $this->globdata($GLOBALS['foton_setting']['sitedir']);
					$global = $arr['global'];
			        foreach($arr as $key=>$val){
			            ${$key}=$val;
			        }	 
					if(isset($controller_name)){
					    $controller_file = $GLOBALS["foton_setting"]["sitedir"]."/".strtolower($controller_name).'.php';
					    $controller_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_c.$controller_file);
			     	    if(file_exists($controller_path))
					    {  
					        require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_c.$controller_file);
						    $model_class = new $model_name;
						    $controller_class = new $controller_name;
							$data = $this->SystemRender($controller_name,$model_name,$format,$GLOBALS["foton_setting"]["sitedir"]);							
							$data['controller_class'] = $controller_class;
			 				
						}
					}
					$error=false;
					
				}
			}
			if($error){
		    	$this->router->ErrorPage404();
		    }
		    if(isset($data)){
                return $data;
		    }
		}
		else{
		    if(isset($data)){
                return $data;
		    }
		}
	}

	public function tpl($get,$page){
		if(isset($get[1]) && $get[1]!='' && isset($get[2]) && $get[2]!=''){
			if(isset($GLOBALS["foton_setting"]["type"]) && $GLOBALS["foton_setting"]["type"]=='file'){
				$dir = $GLOBALS['foton_setting']['admindir'].'/type';
				$dirg = $GLOBALS['foton_setting']['admindir'];
			}
			else{
				$dir = $GLOBALS['foton_setting']['sitedir'];
				$dirg = $GLOBALS['foton_setting']['sitedir'];
			}
		    $funca=$get[2];
		    $error=true;
		    $controller_name = $get[1];
		    $class='Tpl_'.$controller_name;
		    $c_file = strtolower($controller_name).'.php';
		    $c_path = $GLOBALS["foton_setting"]["path"].$this->config->path_a.$dir."/php/".$c_file;	 
		    if (file_exists($this->config->core->git($c_path))) {		        
		        $arr = $this->globdata($dirg);
	            foreach($arr as $key=>$val){
	                ${$key}=$val;
	            }
			    if(isset($controller_name)){
			        require_once $this->config->core->git($c_path);
			        $this->controller_class = $controller_class = new $class;
			        if(method_exists($controller_class,$funca)!==false){
			           if(isset($funca)){
				           	$error=false;
				            return $controller_class->$funca();
				        }
				    }
				}
			}
			if($error){
		    	$this->router->ErrorPage404();
		    }
		}
		else{
			$this->router->ErrorPage404();
		}	 
	}
	public function ajaxsite($get,$page){
		if(isset($get[1]) && $get[1]!=''){
	        $funca=$get[1];
	        $error=true;
		    $ca_name = 'Ajax_'.$GLOBALS['foton_setting']['sitedir'];
			$c_names = 'Ajax_'.$GLOBALS['foton_setting']['sitedir'].'_m';
		    $c_path = $GLOBALS["foton_setting"]["path"].$this->config->path_a.'ajax_'.$GLOBALS['foton_setting']['sitedir'].'.php';
		    $c_file0 = 'ajax_'.$GLOBALS['foton_setting']['sitedir'].'_m.php';
		    $c_path0 = $GLOBALS["foton_setting"]["path"].$this->config->path_a.$c_file0;
		    if (file_exists($this->config->core->git($c_path)) && file_exists($this->config->core->git($c_path0))) { 
		        $arr = $this->globdata($GLOBALS['foton_setting']['sitedir']);
		        $global = $arr['global'];
	            foreach($arr as $key=>$val){
	                ${$key}=$val;
	            }
	            require_once $this->config->core->git($c_path0);  
			    require_once $this->config->core->git($c_path);
			    if(class_exists($ca_name)){ 
			        require_once $this->config->core->git($c_path0);  
			        require_once $this->config->core->git($c_path); 
			        if(isset($ca_name) && class_exists($ca_name)){  
    			        $cajax_class = new $ca_name;   
			        }
			        if(method_exists($ca_name,$funca)!==false || is_callable(array($cajax_class,$funca))!==false){ 
			          if(isset($funca) && $funca!='no_file'){
				          	$error=false;
				           return $cajax_class->$funca($arr);
				      }
				   }
				}
			}
			if($error){
		    	$this->router->ErrorPage404();
		    }
	    }
	    else{
			$this->router->ErrorPage404();
		}
	}
	
	public function ajaxadmin($get,$page){
        if(isset($get[1]) && $get[1]!='' && $this->config->core->isAuth()){
	        $funca=$get[1];
	        $error=true;
		    $controller_name = 'Ajax_'.$GLOBALS['foton_setting']['admindir'];
		    $c_names = 'Ajax_admin';
		    $c_path = $GLOBALS["foton_setting"]["path"].$this->config->path_a.strtolower($controller_name).'.php';
		    $c_file0 = strtolower($controller_name).'_m.php';
		    $c_path0 = $GLOBALS["foton_setting"]["path"].$this->config->path_a.$c_file0;
		    if (file_exists($this->config->core->git($c_path))) { 
		        $arr = $this->globdata($GLOBALS['foton_setting']['admindir']);
		        $global = $arr['global'];
	            foreach($arr as $key=>$val){
	                ${$key}=$val;
	            }
			    if(isset($controller_name)){
			        require_once $this->config->core->git($c_path0);   
			        require_once $this->config->core->git($c_path);
			        $this->controller_class = $controller_class = new $c_names;
			        if(method_exists($controller_class,$funca)!==false){
			           if(isset($funca)){
				           	$error=false;
				            return $controller_class->$funca();
				        }
				    }
				   
				}
			}
			if($error){
		    	$this->router->ErrorPage404();
		    }
	    }
	    else{
		  	$this->router->ErrorPage404();
	    }
	}
	public function ajax($get,$page){
	    if(isset($get[1]) && $get[1]!='' && isset($get[2]) && $get[2]!='' && $this->config->core->isAuth()){
		    $m=$get[1];
		    $funcm=$get[2];
		    $error=true;
		    $view_name = 'index';
		    $controller_name = $m.'_c';
		    $model_name = $m.'_m';
		    $ajax_name = $m.'_a';
		    if($m!==false){
		        $view_file = strtolower($view_name).'.php';
		        $view_path = $GLOBALS["foton_setting"]["path"].$this->config->path_modul.$m;
		        if (file_exists($this->config->core->git($view_path))) {
		            $model_file = $model_name.'.php';
		            $model_path = $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_modul.$m."/".$model_file);
		            if(file_exists($model_path))
		            { 
		                require_once $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_modul.$m."/".$model_file);
		            }
		            else{
					    $model_name="Model";
					}
		            $arr = $this->globdata($GLOBALS['foton_setting']['admindir']);
		            $global = $arr['global'];
		            foreach($arr as $key=>$val){
		                ${$key}=$val;
		            }
		            if(isset($controller_name)){
		                $controller_file = strtolower($controller_name).'.php';
		                $controller_path = $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_modul.$m."/".$controller_file);
		                if(file_exists($this->config->core->git($controller_path)))
		                {
                            require_once $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_modul.$m."/".$controller_file);
			                 $this->model_class = $model_class = new $model_name;
			                 $this->controller_class = $controller_class = new $controller_name; 
			                 $ajax_file = strtolower($ajax_name).'.php';
			                 $ajax_path = $GLOBALS["foton_setting"]["path"].$this->config->path_modul.$m."/".$ajax_file;
			                 if(file_exists($this->config->core->git($ajax_path)))
			                 { 
			                    require_once $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_modul.$m."/".$ajax_file);
			                    $ajax_class = new $ajax_name;
			                    if(isset($funcm)){
				                   $error = false;
			                       return $ajax_class->$funcm();
			                    }
			                }	 
		                }
		            }
		        }	 
			}   
			if($error){
		    	$this->router->ErrorPage404();
		    }
		}
		else{
			$this->router->ErrorPage404();
		}
	}

	public function modul($get,$page){
	    if(isset($page) && $page!='' && $this->config->core->isAuth()){
	    	if(is_array($page)){
		        $item=$page[1];
		        $m=$page[0];
		    }else{
		        $m=$page;
		        $item=false;
		    }
		    $error=true;
		    $view_name = 'index';
		    $controller_name = $m.'_c';
		    $model_name = $m.'_m';	 
		    if($m!==false){	 
		        $view_file = strtolower($view_name).'.php';
		        $view_path = $GLOBALS["foton_setting"]["path"].$this->config->path_modul.$m;
		        if (file_exists($this->config->core->git($view_path))) {
		            $model_file = $model_name.'.php';
		            $model_path = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_modul.$m."/".$model_file);
		            
		            if(file_exists($model_path))
		            { 
			            require_once $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_modul.$m."/".$model_file);		 
			        }
			        $arr = $this->globdata($GLOBALS['foton_setting']['admindir']);
			        $global = $arr['global'];
		            foreach($arr as $key=>$val){
		                ${$key}=$val;
		            }
			        if(isset($controller_name)){
			            $controller_file = strtolower($controller_name).'.php';
			            $controller_path = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_modul.$m."/".$controller_file);
			            if(file_exists($controller_path))
			            {
			                require_once $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_modul.$m."/".$controller_file);
			                $this->model_class = $model_class = new $model_name;
			                if((method_exists($model_class,'modul_chmod') && $this->config->core->chmod_id($model_class->modul_chmod())) || method_exists($model_class,'modul_chmod')===false){
			                   $this->controller_class = $controller_class = new $controller_name;
			                    foreach(get_class_methods($controller_name) as $val){
			                    	try{
					                    if(method_exists($model_class,$val)===false && stristr($val,'_mod') === FALSE){
					                    	$data[$val] = $controller_class->$val();
					                    }
					                }
					                catch (\Throwable $e) {
						                if(isset($GLOBALS["foton_setting"]["debug"]) && $GLOBALS["foton_setting"]["debug"]===true){ 
						                    $this->core->log_file($controller_name,$e->getMessage(),'yes');    
						                }
						                $this->core->log($e->getMessage());                               
					                }
				                }
				            }
				            else{
				                exit();
				            }
				        }
				    }
				    if(empty($data)){
				        $data=array();
				    }
				    $dir_m=$model_class->dir_m();
				    return array($data,$dir_m);
				}	 
			}
			if($error){
		    	$this->router->ErrorPage404();
		    }
		}
		else{
			$this->router->ErrorPage404();
		}
	}


	public function xml($get,$page){ 
		if(isset($get[1]) && $get[1]!=''){
		   $pathxml=$page;
		   $view_name = $pathxml.'_view';
		   $error=true;
		   $controller_name = 'Controller_'.$pathxml;
		   $model_name = 'Model_'.$pathxml; 
		   if($pathxml!==false){ 
			    $view_file = strtolower($view_name).'.tpl';
			    $view_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc."xml/".$GLOBALS["foton_setting"]["sitedir"]."/".$view_file);		    
			    $view_filephp = strtolower($view_name).'.php';
			    $view_pathphp = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc."xml/".$GLOBALS["foton_setting"]["sitedir"]."/".$view_filephp);
			    if (file_exists($view_path) || file_exists($view_pathphp)) {
			        $model_file = $GLOBALS["foton_setting"]["sitedir"]."/model_".$pathxml.'.php';
			        $model_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_m.$model_file);
			        if(file_exists($model_path))
			        { 
			            require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_m.$model_file);
			        }
			        else{
					    $model_name="Model";
					}
					$arr = $this->globdata($GLOBALS['foton_setting']['sitedir']);
					$global = $arr['global'];
			        foreach($arr as $key=>$val){
			            ${$key}=$val;
			        }	 
					if(isset($controller_name)){
					    $controller_file = $GLOBALS["foton_setting"]["sitedir"]."/".strtolower($controller_name).'.php';
					    $controller_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_c.$controller_file);
			     	    if(file_exists($controller_path))
					    {  
					        require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_c.$controller_file);
						    $model_class = new $model_name;
						    $controller_class = new $controller_name;
							$data = $this->SystemRender($controller_name,$model_name,'xml',$GLOBALS["foton_setting"]["sitedir"]);							
							$data['controller_class'] = $controller_class;
			 				
						}
					}
					$error=false;
					
				}
			}
			if($error){
		    	$this->router->ErrorPage404();
		    }
		    if(isset($data)){
                return $data;
		    }
		}
		else{
		    if(isset($data)){
                return $data;
		    }
		}
	}

	public function json($get,$page){ 
		if(isset($get[1]) && $get[1]!=''){
		   $pathxml=$page;
		   $view_name = $pathxml.'_view';
		   $error=true;
		   $controller_name = 'Controller_'.$pathxml;
		   $model_name = 'Model_'.$pathxml; 
		   if($pathxml!==false){ 
			    $view_file = strtolower($view_name).'.tpl';
			    $view_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc."json/".$GLOBALS["foton_setting"]["sitedir"]."/".$view_file);		    
			    $view_filephp = strtolower($view_name).'.php';
			    $view_pathphp = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc."json/".$GLOBALS["foton_setting"]["sitedir"]."/".$view_filephp);
			    if (file_exists($view_path) || file_exists($view_pathphp)) {
			        $model_file = $GLOBALS["foton_setting"]["sitedir"]."/model_".$model_name.'.php';
			        $model_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_m.$model_file);
			        if(file_exists($model_path))
			        { 
			            require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_m.$model_file);
			        }
			        else{
					    $model_name="Model";
					}
					$arr = $this->globdata($GLOBALS['foton_setting']['sitedir']);
					$global = $arr['global'];
			        foreach($arr as $key=>$val){
			            ${$key}=$val;
			        }	 
					if(isset($controller_name)){
					    $controller_file = $GLOBALS["foton_setting"]["sitedir"]."/".strtolower($controller_name).'.php';
					    $controller_path = $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_c.$controller_file);
			     	    if(file_exists($controller_path))
					    {  
					        require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_c.$controller_file);
						    $model_class = new $model_name;
						    $controller_class = new $controller_name;
							$data = $this->SystemRender($controller_name,$model_name,'json',$GLOBALS["foton_setting"]["sitedir"]);
							$data['controller_class'] = $controller_class;
			 				
						}
					}
					$error=false;
					
				}
			}
			if($error){
		    	$this->router->ErrorPage404();
		    }
		    if(isset($data)){
                return $data;
		    }
		}
		else{
		    if(isset($data)){
                return $data;
		    }
		}
	}

	private function MethodClass($name,$method,$class,$get=false){
		if(isset($GLOBALS["foton_setting"]["debug"])){
            $_SESSION['debug'][] = ['method'=>$name.'::'.$method,'time'=>microtime(true)];
         }
        if(method_exists($class,$method)!==false){ 
        	try{
        	    if($get){
        	        return $class->$method(...$this->core->request->sr['SR']);
        	    }
				return $class->$method();
			}
            catch (\Throwable $e) {
                if(isset($GLOBALS["foton_setting"]["debug"]) && $GLOBALS["foton_setting"]["debug"]===true){ 
                    $this->core->log_file($name.'::'.$method,$e->getMessage(),'yes');    
                }
                return $this->core->log($e->getMessage());                               
            }		
		}
		return false;
	}

	private function SystemRender($name,$m_name,$format,$dir){
		$class = new $name;
		$model = new $m_name;
		if(isset($GLOBALS["foton_setting"]["render"]) && $dir!=$GLOBALS["foton_setting"]["admindir"]){
			if(isset($this->core->request->sr['SR'])){
	    		$method = implode('_',array_keys($this->core->request->sr['SR']));    		
	    		$format_method = $format.'_'.$method;
	     		$Mdata = $this->MethodClass($name,$method,$class,true);
	    		if($Mdata!==false){
	    			$data[$method] = $Mdata;
	    		}    		
	    		$Mdata3 = $this->MethodClass($name,$format_method,$class,true);
	    		if($Mdata3!==false){
	    			$data[$format_method] = $Mdata3;
	    		}
	    	}
	    	$format_page = $format.'_page';
    		$Mdata2 = $this->MethodClass($name,$format_page,$class);
    		if($Mdata2!==false){
    			$data[$format_page] = $Mdata2;
    		}
    	}
    	else{
			foreach(get_class_methods($name) as $val){
				if(method_exists($model,$val)===false){
					if(isset($GLOBALS["foton_setting"]["debug"])){
		                $_SESSION['debug'][] = ['method'=>$m_name.'::'.$val,'time'=>microtime(true)];
		            }
		            try{	            	
						$data[$val] = $class->$val();
					}
	                catch (\Throwable $e) {
		                if(isset($GLOBALS["foton_setting"]["debug"]) && $GLOBALS["foton_setting"]["debug"]===true){ 
		                    $this->core->log_file($name,$e->getMessage(),'yes');    
		                }
		                $data[$val] = $this->core->log($e->getMessage());                               
	                }
				}
			}
		}
		if(isset($data)){
			return $data;
		}
		else{
			return false;
		}
	}

	public function mvc($get,$page){ 
	    
		if(isset($get[0]) && $get[0]!=''){
		   $path=$get[0];
		}
		else{
			if(stristr($page,'_mob')!==false){
                $page=preg_replace('#(.+)_mob$#','$1',$page);
            }
			$path=$page;
		}
		if(stripos($path,'/')){
	        $path_arr = explode('/',$path);
	        $path = array_pop($path_arr);
	        $after_dir = implode('/',$path_arr);
	    }
	   $view_name = $path.'_view';
	   $error=true;
	   $controller_name = '\Controller_'.$path;
	   $model_name = '\Model_'.$path;
	   $c_name = 'controller_'.$path;
	   $m_name = 'model_'.$path; 
	   if($path!==false){ 			
		$view_file = strtolower($view_name).'.tpl';
		$view_file2 = strtolower($view_name).'.php';
			if(isset($after_dir)){
			    $view_file=$after_dir.'/'.$view_file;
			    $view_file2=$after_dir.'/'.$view_file2;
			}
			if($this->config->core->isAuth() && file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["admindir"]."/".$view_file))){
				$view_path = $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["admindir"]."/".$view_file);
				$dir = $GLOBALS["foton_setting"]["admindir"];
			}
			else if($this->config->core->isAuth() && file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["admindir"]."/".$view_file2))){
				$view_path = $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["admindir"]."/".$view_file2);
				$dir = $GLOBALS["foton_setting"]["admindir"];
			}
			else if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["sitedir"]."/".$view_file))){
				$view_path = $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["sitedir"]."/".$view_file);			
				$dir = $GLOBALS["foton_setting"]["sitedir"];
			}
			else if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["sitedir"]."/".$view_file2))){
				$view_path = $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["sitedir"]."/".$view_file2);			
				$dir = $GLOBALS["foton_setting"]["sitedir"];
			} 
			else{}
			if(isset($after_dir)){
			    $dir.='/'.$after_dir;
			}
			if (isset($view_path) && file_exists($view_path)) {
				$model_file = $dir."/".$m_name.'.php';
				$model_path = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_m.$model_file);				
				if(file_exists($model_path))
				{ 
					require_once $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_m.$model_file);
				}
				else{
				    $model_name="Model";
				}
				$arr = $this->globdata($dir);
				$global = $arr['global'];
				foreach($arr as $key=>$val){
					if(isset($GLOBALS["foton_setting"]["debug"])){
		                $_SESSION['debug'][] = ['method_glob'=>$key,'time'=>microtime(true)];
		            }
					${$key}=$val;
				}	 	
				if(isset($controller_name)){
					$controller_file = $dir."/".$c_name.'.php';
					$controller_path = $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_c.$controller_file);
					if(file_exists($controller_path))
					{			       
						require_once $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_c.$controller_file);
						if(file_exists($model_path))
    					{
    						$model_class = new $model_name;
    						$this->model_class = $model_class;
							$this->controller_class = $controller_class = new $controller_name;
							$data = $this->SystemRender($controller_name,$model_name,'mvc',$dir);
    					}
    					else{
					    	$this->model_class = $model_class = new \Model;
							$this->controller_class = $controller_class = new $controller_name;
    						$data = $this->SystemRender($controller_name,$model_name,'mvc',$dir);
    					}
					}
				}
				$error=false;
			}
			if(empty($data)){
			    $data=array();
			}			
			if($this->controller_class instanceof Face){
				$data_parent = $this->interfaces($path);
				$data = array_merge($data, $data_parent);
			}
			if(isset($data['dir']) && empty($after_dir)){
				$dir = $data['dir'];
			}
			if(isset($data['region']) && $data['region']===false){
				$data['region'] = 'no';
			}
			else{
			    $data['region'] = 'yes';
			}
			if(isset($dir)){
    			$data=array($data,$dir);
    			return $data;
			}
			else{
			    $data=array($data,$GLOBALS['foton_setting']['sitedir']);
    			return $data;
			}
		
		}
		if($error){
	    	$this->router->ErrorPage404();
	    }		
	}


	public function face($get,$page){
		$error=true;
		if(isset($get[2]) && $get[2]!='' && isset($get[1]) && $get[1]!='' && $this->config->core->isAuth()){
		    $funca=$get[2];    
		    $controller_name=$get[1];
		    $class = 'Interface_'.$controller_name;
		    $c_file = strtolower($controller_name).'.php';
		    $c_path = $GLOBALS["foton_setting"]["path"].$this->config->path_a.$GLOBALS['foton_setting']['admindir']."/php/".$c_file; 
		    if (file_exists($this->config->core->git($c_path))) {
		        $arr = $this->globdata($GLOBALS['foton_setting']['admindir']);
		        $global = $arr['global'];
		        foreach($arr as $key=>$val){
		            ${$key}=$val;
		        }	 
			    if(isset($controller_name)){
			        require_once $this->config->core->git($c_path);
			        $this->controller_class = $controller_class = new $class;
			        if(method_exists($controller_class,$funca)!==false){
			        	try{
				            if(isset($funca)){
					           	$error = false;
					            return  $controller_class->$funca();
					        }  
				        }
		                catch (\Throwable $e) {
			                if(isset($GLOBALS["foton_setting"]["debug"]) && $GLOBALS["foton_setting"]["debug"]===true){ 
			                    $this->core->log_file($class,$e->getMessage(),'yes');    
			                }
			                $this->core->log($e->getMessage());                               
		                }     
				    }  
				}
			}
			
		}
		if($error){
	    	$this->router->ErrorPage404();
	    }
	}
}
