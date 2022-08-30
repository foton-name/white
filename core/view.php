<?php
namespace Foton;

class Config{
    public $arr;
    public $array_auth;
    public $get;
    public $admin;
    public $path;
    public $region;
    public function __construct(){
    	global $core;
        $this->core = $core;
        //$this->core->glob_controller();
        //$this->glob = new \Controller_Globals;
        $this->widget = $this->core->widget;
        $this->arr = ['json'=>'ajax','xml'=>'ajax','face'=>'ajax','mvc'=>'page','ajaxsite'=>'ajax','ajaxadmin'=>'ajax','modul'=>'page','ajax'=>'ajax','tpl'=>'ajax'];
        $this->admin = ['face','ajaxadmin','modul','ajax','tpl'];
        if(isset($GLOBALS["foton_setting"]['route'])){
	        foreach($GLOBALS["foton_setting"]['route'] as $key=>$val){
	           $this->arr[$key] = $key; 
	        }
	    }
        $this->get=[1,2];
        $this->alias =[$GLOBALS['foton_setting']['admin']=>'/system/admin-inc/auth.php'];
        $this->path = ['modul'=>'/dev/modul/','ajax'=>'/app/ajax/','mvc'=>'/app/'];
        $this->region = [0=>'head',1=>'foot'];
        $this->path_mvc = "/app/view/";
        $this->path_m = "/app/model/";
        $this->path_a = "/app/ajax/";
        $this->path_c = "/app/controller/";
        $this->path_modul = "/dev/modul/";
    }
}
class Cache{
    public $conf;
    public $classf;
    public function __construct($conf){
        $this->conf = $conf;
    }
    public function __get($property)
    {
        if (class_exists($property)) {
            $this->classf = $property;
        }
        else{
           $this->classf = 'Foton\\'.$property; 
        }
        return $this; 
    }
    public function __call($name, $arg)
    {
        $this->obj = new $this->classf;
        $this->name = $name;
        $this->arg = $arg;
        return $this->Run();
    }
    public function Run(){
        $method = $this->name;
        if(class_exists('Memcache') && $method!=null){                
            $memcache = new \Memcache;            
            if(!$memcache->connect($this->conf[0],$this->conf[1])){
                if(is_array($this->arg) || $this->arg!=null){                    
                    $data = $this->obj->$method(...array_values($this->arg));
                }
                else{
                    $data = $this->obj->$method();
                }
                return $data;
            }
            else{            
                $key=md5(json_encode($this->arg));
                $result = $memcache->get($key);
                
                if($result!==false)
                {
                    $memcache->close();
                    return $result;
                }
                else{ 
                    if(is_array($this->arg) || $this->arg!=null){
                        $data = $this->obj->$method(...array_values($this->arg));
                    }
                    else{
                        $data = $this->obj->$method();
                    }
                    $memcache->set($key,$data,false,$this->conf[2]);                
                    $memcache->close();
                }
 
            }
        }
        if(is_array($this->arg) || $this->arg!=null){
            $data = $this->obj->$method(...array_values($this->arg));
        }
        else{
            $data = $this->obj->$method();
        }
        return $data;
    }
}

class fCache{
    public $conf;
    public $classf;
    public function __construct($conf){
        $this->conf = $conf;
        $this->c = new \Foton\Core;
    }
    public function __get($property)
    {
        if (class_exists($property)) {
            $this->classf = $property;
        }
        else{
           $this->classf = 'Foton\\'.$property; 
        }
        return $this; 
    }
    public function __call($name, $arg)
    {
        $this->obj = new $this->classf;
        $this->name = $name;
        $this->arg = $arg;
        return $this->Run();
    }
    public function getCache($file){
    	$filepath = $this->c->git($GLOBALS['foton_setting']['path'].'/system/cache/'.$file.'.php');
    	if(!file_exists($this->c->git($GLOBALS['foton_setting']['path'].'/system/cache'))){
    		mkdir($this->c->git($GLOBALS['foton_setting']['path'].'/system/cache'));
    	}
    	if(file_exists($filepath) && time() - filectime($filepath)<(int)$this->conf){
    		return unserialize(file_get_contents($filepath));
    	}
    	else{
    		if(file_exists($filepath)){
	    		unlink($filepath);
	    	}
    		return false;
    	}
    }
    public function clearCache(){
    	$this->c->dir_delete_foton($this->c->git($GLOBALS['foton_setting']['path'].'/system/cache'));
    	mkdir($this->c->git($GLOBALS['foton_setting']['path'].'/system/cache'));
    }
    public function setCache($file,$data){
    	if(!file_exists($this->c->git($GLOBALS['foton_setting']['path'].'/system/cache'))){
    		mkdir($this->c->git($GLOBALS['foton_setting']['path'].'/system/cache'));
    	}
    	$filepath = $this->c->git($GLOBALS['foton_setting']['path'].'/system/cache/'.$file.'.php');
    	file_put_contents($filepath,serialize($data));
    }
    public function Run(){
        $method = $this->name;          
        $key=md5(json_encode($this->arg).'--'.$this->name);
        $result = $this->getCache($key);            
        if($result!==false)
        { 
            return $result;
        }
        else{ 
            if(is_array($this->arg) || $this->arg!=null){
                $data = $this->obj->$method(...array_values($this->arg));
            }
            else{
                $data = $this->obj->$method();
            }
            $this->setCache($key,$data);                
        }      
        if(isset($data)){
            return $data;
        }
    }
}

class Router{
	public function __construct(){
		$this->get = $this->urlget();
		$this->path = $this->urlpath();	
		$this->config = new Config;
		$this->arr = $this->config->arr;
		$this->core = $this->config->core;
		$this->request = $this->core->request;
		$this->db = $this->core->db();
	}
  
   public function get(){        
        if(isset($GLOBALS['foton_setting']['get_unset']) && $GLOBALS['foton_setting']['get_unset']=='Y' && !$this->core->isAuth()){
            unset($_GET);
            unset($GLOBALS['foton_setting']['_GET']);
            $_GET = array();
        }
        else{
	        $url=$_SERVER['REQUEST_URI'];
	        if(stristr($url,'?')!==false){
	            $url_arr= explode('?', $url);
	            $get=explode('&',$url_arr[1]);
	        	foreach($get as $val){
	                $args = explode('=',$val);
	                $this->request->g[$args[0]]=$args[1];		            
		        }
	        }
	    }
    }
	public function path($page=null,$dir=null){
	    if(isset($page) && $page==$GLOBALS['foton_setting']['admin']){
	        return 'system/admin-inc/';
	    }
	    else if(isset($page) && $page=='ajax'){
	        return 'app/ajax/';
	    }
	    else if(isset($page) && $dir=='modul'){
	        return 'app/view/'.$GLOBALS['foton_setting']['admindir'].'/';
	    }
	    else if($dir!=null){
	        return 'app/view/'.$dir.'/';
	    }
	    else if($this->config->core->isAuth()){
	        return 'app/view/'.$GLOBALS['foton_setting']['admindir'].'/';
	    }
		else{
		   return 'app/view/'.$GLOBALS["foton_setting"]["sitedir"].'/';
		}
	}
    public function tpl_html(){
		$path=$_SERVER['REQUEST_URI'];
		$path=explode('?',$path)[0];
		$tpl = $this->core->git($GLOBALS['foton_setting']['path'].'/app/view/tpl-ini.php');
		if(file_exists($tpl)){
			 $tplarr = $this->parse_file($tpl); 
			 if(count($tplarr)>0){
				 foreach($tplarr as $pattern=>$file){
					 if (preg_match("#".$pattern."#i",$this->urlend(),$arr_match)) {
						 $GLOBALS['foton_setting']['templates'] = $file;
					 }
				 }
			 }
		 }
	}
	public function parse_file($file){
	    $arr_r=array();
	    $text=file_get_contents($file);
	    $for=explode("\n",$text);
	    foreach($for as $value){
	        if($value!=''){
	            $arr=explode("=",$value);
	            $arr_r[$arr[0]]=$arr[1];
	        }
	    }
	    return $arr_r;
	}
	
	public function js_page($page='mvc',$m=null){
	    $arr=array();
		if($page=='mvc'){
			if(is_array($m)){
		        $m=$m[0];
		    }
		    if(file_exists($this->config->core->git('app/view/'.$GLOBALS['foton_setting']['admindir'].'/js/'.$m.'.js')) && $this->config->core->isAuth()){
		        return '/'.$this->config->core->git('app/view/'.$GLOBALS['foton_setting']['admindir'].'/js/'.$m.'.js');
		    }
		    else if(file_exists($this->config->core->git('app/view/'.$GLOBALS['foton_setting']['sitedir'].'/js/'.$m.'.js'))){
			    return '/'.$this->config->core->git('app/view/'.$GLOBALS['foton_setting']['sitedir'].'/js/'.$m.'.js');
			}
			else{
			    return false; 
			}
		}
		else if($page=='modul'){
			if(is_array($m)){
		        $m=$m[0];
		    }
	        foreach (glob($this->config->core->git($GLOBALS["foton_setting"]["path"]."/dev/modul/".$m."/js/*.js")) as $filename) {
	            $arr[]='/dev/modul/'.$m."/js/".basename($filename);	                
	        }
	        return $arr;
		}
		else{
            return array();
		}
	}
	public function css_page($page='mvc',$m=null){
	    $arr=array();
		if($page=='mvc'){
			if(is_array($m)){
		        $m=$m[0];
		    }
		    if(file_exists($this->config->core->git('app/view/'.$GLOBALS['foton_setting']['admindir'].'/css/'.$m.'.css')) && $this->config->core->isAuth()){
		        return '/'.$this->config->core->git('app/view/'.$GLOBALS['foton_setting']['admindir'].'/css/'.$m.'.css');
		    }
		    else if(file_exists($this->config->core->git('app/view/'.$GLOBALS['foton_setting']['sitedir'].'/css/'.$m.'.css'))){
		        return '/'.$this->config->core->git('app/view/'.$GLOBALS['foton_setting']['sitedir'].'/css/'.$m.'.css');
			}
			else{
			    return false; 
			}
		}
		else if($page=='modul'){
			if(is_array($m)){
		        $m=$m[0];
		    }
			foreach (glob($this->config->core->git($GLOBALS["foton_setting"]["path"]."/dev/modul/".$m."/css/*.css")) as $filename) {
		        $arr[]="/dev/modul/".$m."/css/".basename($filename);	                
		    }
		    return $arr;
		}
		else{
            return array();
		}
	}
	public function ispage($path){
		if(is_array($path)){
			$error=true;
			foreach($path as $p){
				if(file_exists($this->config->core->git($p))){
					$error=false;
					require_once $this->config->core->git($p);
				}
			}
			if($error){
				$this->ErrorPage404();
			}			
		}
		else{
			if(isset($path) && file_exists($this->config->core->git($path))){
					require_once $this->config->core->git($path);
				}
				else{
					$this->ErrorPage404();
				}
		}		
	}
	public function urlget(){
	    $url=$_SERVER['REQUEST_URI'];
	    if(stristr($url,'?')!==false){
	        $arr_get=array();
	        $url_arr= explode('?', $url);
	        $arr0=explode('&',$url_arr[1]);
	        foreach($arr0 as $get){
	            $get=explode('=',$get);
	            $arr_get[$get[0]]=$get[1];
	        }
	        return $arr_get;
	    }
	    else{
	        return array();
	    }        
	}
	public function urlpath(){
	    $url=$_SERVER['REQUEST_URI'];
	    if(stristr($url,'/')!==false){
	        $arr_get=array();
	        $url_arr= explode('?', $url);
	        $arr0=explode('/',$url_arr[0]);
	        foreach($arr0 as $key=>$get){
	        	if($get!=''){
		            $arr_get[$key-1]=$get;
		        }
	        }
	        return $arr_get;
	    }
	    if($url!=''){
	    	return array(0=>$url);
	    }
	    else{
	        return array();
	    }        
	}
	public function urlend(){
	    $url=$_SERVER['REQUEST_URI'];
	    if(stristr($url,'?')!==false){
	        $url_arr= explode('?', $url);
	        return $url_arr[0];
	    }
	    else{
	        return $url;
	    }        
	}
	public function url_route($url,$i)
	{ 
	    if(is_array($url)){
	        $url = $url[0];
	    }
	    $_SERVER['REQUEST_URI']=preg_replace('#\.'.$url.'$#','',$this->urlend());
        //определяем путь страницы
	    $routes = explode('/', $_SERVER['REQUEST_URI']);        
        // получаем имя контроллера
	    if ( !empty($routes[$i]) )
	    {
	        return $routes[$i];

	    }
	    else{
	        return false;
	    }

	}    
	public function isurl()
	{ 
	    foreach($this->arr as $key=>$val){
	        if (preg_match("/\.".$key."$/", $this->urlend())) {
	            return $key;
	        }
	        
	    }
	    return false;
	}
	public function pathview_to($from=null) { 
		$auth = $this->config->core->isAuth();
	    if($from!=null){
	        $_SERVER['REQUEST_URI']=preg_replace('#\.'.$from.'$#','',$this->urlend());
        }
        //определяем путь страницы
        $routes = explode('/', $_SERVER['REQUEST_URI']);        
        // получаем имя контроллера
        if(empty($routes[1])){
            $routes[1]=$GLOBALS["foton_setting"]["main"];
        }
        if($auth && isset($GLOBALS['foton_setting']['no_double'])){
            return $routes[1];
        }
        $urlnew = $_SERVER['REQUEST_URI'];
        $urlnew=substr($urlnew, 1);
        $chpu_status="no";
        foreach($this->core->select_db('router') as $val){
            $reg=preg_replace('#%([^%]+)%:([^:]+):#','([$2]+)',$val["routs"]);
            $reg=preg_replace('#%([^%]+)%@([^@]+)@#','([$2]*)',$reg);
            if($from!=null){
                $reg=preg_replace('#\.html$#','',$reg);
            }
            if($from=='json' || $from=='xml'){
                $reg=preg_replace('#/$#','',$reg);
            }
            if($from==null){
				$urlnew=str_replace('.xml','',$urlnew);
	            $urlnew=str_replace('.json','',$urlnew);
        	}
            if (preg_match('#^'.$reg.'$#',$urlnew) || preg_match('#^'.$reg.'/$#',$urlnew)) {
               $view=$val["view"];
               preg_match_all("|".$reg.".".$from."|U",$urlnew.".".$from,$out, PREG_PATTERN_ORDER);
               preg_match_all("|%([^%]+)%|U",$val["routs"],$out0, PREG_PATTERN_ORDER);
	            for($it=0;$it<count($out0[1]);$it++){
	               	if(isset($out[$it+1][0])){
	               	    $this->request->sr['SR'][$out0[1][$it]] = $out[$it+1][0];
	                    $this->request->g[$out0[1][$it]] = $out[$it+1][0];
	                }
	            }
                $chpu_status="yes";
            }
            else{
 
            }
        }
        if($chpu_status=="yes"){
            if(isset($from)){
                   $view=preg_replace('#\.'.$from.'$#','',$view);
                }
            return  $view;
        }
        else{
        	if($from!=null){
	            if($from=='modul' && isset($routes[2]) && empty($routes[3])){
	                return array($routes[1],$routes[2]);
	            }            
	            else if(isset($routes[2])){
	                return false;
	            }
	            else{
	                if(isset($from)){
                        $routes[1]=preg_replace('#\.'.$from.'$#','',$routes[1]);
                    }
	                return $routes[1];
	            }
            }
            else{
            	if(isset($GLOBALS['foton_setting']['no_double']) && $_SERVER['REQUEST_URI']!='/' && !$auth && $from==null){
                    return $this->ErrorPage404();
                }
                if(isset($from)){
                    $routes[1]=preg_replace('#\.'.$from.'$#','',$routes[1]);
                }
				return $routes[1];
            }
 
        }
	 
	}

	function ErrorPage404()
	{
	    http_response_code(404);
   	    $return = new dataReturn;
	    $rend = new Render;
	    $page = $GLOBALS["foton_setting"]["error404"];
	    $get=array(0=>$GLOBALS["foton_setting"]["error404"]);
        $get = $return->mvc($get,$page);
	    $arr = $rend->preload($get,$page);
     	$this->url=$rend->router->path($page,$arr['dir']);
     	if(isset($GLOBALS['foton_setting']['format_render'])){
     	    $format = $GLOBALS['foton_setting']['format_render'];
            if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/view/".$format."/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.php")) || file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/system/api/tpl/".$format."/".$GLOBALS["foton_setting"]["sitedir"]."/".$page."_view.php"))){
             	return $rend->$format($get,$page);
         	}
         	else {
                return $rend->mvc($get,$page);
            }
     	}
     	else{
            return $rend->mvc($get,$page);
     	}
		die();
	}
}


require_once $GLOBALS['foton_setting']['path'].$GLOBALS['foton_setting']['lib'].".php";
require_once $GLOBALS['foton_setting']['path'].$GLOBALS['foton_setting']['adapter'].".php";
 

class View{
	public $valid;
    public $is_valid;
	public function __construct(){
		$this->valid = new Validate;
        $this->is_valid = new Is_Validate;
	}
    public function load(){
    	$this->config = new Config;
        $this->router = new Router();
        $this->router->get();
        $format = $this->router->isurl();
        if($format){
            $page=$this->router->pathview_to($format);
        }
        else{
            $format = 'mvc';
            $page= $this->router->pathview_to();
            if($page==null){
                $page = 'html';
            }
        }
		if(isset($_POST['controller'])){
			$name = $_POST['controller'];
		}
		else if(isset($_GET['controller'])){
			$name = $_GET['controller'];
		}
		else{
			$name = $page;
		}
		$main = new Main();
		if(isset($_POST['foton_validate'])){
			unset($_POST['foton_validate']);
			if(isset($name) && method_exists($this->valid,$name)){
				$valid = $this->valid->$name();
				if(is_array($valid)){
					foreach($valid as $k=>$v){
						if(is_array($v)){
							$method = $v[0];
							$v[0] = $_POST[$k]; 
							$main->request->p[$k] = $this->valid->$method(...$v);
						}
						else{
							$main->request->p[$k] = $this->valid->$v($_POST[$k]);
						}
					}
				}
			}
		}
		if(isset($_GET['foton_validate'])){
			unset($_GET['foton_validate']);               
			if(isset($name) && method_exists($this->valid,$name)){
				$valid = $this->valid->$name();
				if(is_array($valid)){
					foreach($valid as $k=>$v){
						if(is_array($v)){
							$method = $v[0];
							$v[0] = $_GET[$k];
							$main->request->g[$k] = $this->valid->$method(...$v);
						}
						else{
							$main->request->g[$k] = $this->valid->$v($_GET[$k]);
						}
					}
				}
			}
		}   
        try{
            $this->obj = new dataReturn;
            $this->render = new render;            
            $GLOBALS['foton_setting']['format_render'] = $format;
            if(in_array($format,$this->config->admin) && !$this->config->core->isAuth()){
               return $this->router->ErrorPage404();
            }
            if(!is_array($page)){
                $GLOBALS['foton_setting']['pagef']=$page.'.'.$format;
            }
            $get=[];
            foreach($this->config->get as $i){
                $get[$i] = $this->router->url_route($page,$i);
            }
            if(isset($GLOBALS['foton_setting']['handler']) && !$this->config->core->isAuth()){
                require_once($GLOBALS['foton_setting']['path'].'/dev/handler.php');
                $arrh = ['dir'=>$GLOBALS['foton_setting']['sitedir'],'format'=>$format,'page'=>$page,'get'=>$get];
                $handler = new Handler($arrh);
                $get = $handler->get;
                $format = $handler->format;
                $page = $handler->page;
            }
            $return=$this->obj->$format($get,$page);
            if(isset($GLOBALS['foton_setting']['handler']) && !$this->config->core->isAuth()){
                $arrh = ['dir'=>$GLOBALS['foton_setting']['sitedir'],'format'=>$format,'page'=>$page,'data'=>$return];
                $handler = new Handler($arrh);
                $return = $handler->data;
                $page = $handler->page;
                $format = $handler->format;
            }
            return $this->render->$format($return,$page);
        } catch (\Throwable $e) {
            $this->config->core->log('Ошибка в '.$page.': '.$e->getMessage());
        }
    }
}