<?php

 

function FotonArr($arr = array()){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

function FotonAuth(){
	if(isset($_SESSION['login']) && isset($_SESSION['chmod_id']) && $_SESSION['chmod_id']!=0){
		return true;
	}
	else{
		return false;
	}
}


function FotonGit($path = null)
{
    if (stristr(PHP_OS, "win")) {
        $path = str_replace(DIRECTORY_SEPARATOR.'core','',$path);
        $path = str_replace("/",DIRECTORY_SEPARATOR,$path);
    }
    if(isset($GLOBALS["foton_setting"]["debug"]) && $path!='sql.php' && $path!='dev/sharding.php'){
        $_SESSION['debug'][] = ['file'=>$path,'time'=>microtime(true)];
    }
    if($GLOBALS["foton_setting"]["git"]=="Y"){
        if (isset($_SESSION['gittest']) && isset($_SESSION['login']) && $_SESSION['gittest'] == $GLOBALS["foton_setting"]["backup"]) {            
            $path = str_replace($GLOBALS["foton_setting"]["path"] . "/", '', $path);
            $branch = $GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/branch.php';
            if (file_exists($branch)) {
                $f_branch = file_get_contents($branch);        
                if (file_get_contents($branch) != '' && file_exists($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/release/' . $f_branch . '/' . $path)) {
                    return $GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/release/' . $f_branch . '/' . $path;
                } 
            }
            else if ($_SESSION['login']  && file_exists($GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/work/' . $path)) {
                return $GLOBALS["foton_setting"]["path"] . '/.gitf/' . $_SESSION['login'] . '/work/' . $path;
            }
            else if (isset($GLOBALS['foton_setting']['custom'])) {
                $path = str_replace($GLOBALS["foton_setting"]["path"].'/','',$path);
                if(strrpos($GLOBALS['foton_setting']['custom'],':')!=false){
                    $custom_p=str_replace(':','',$GLOBALS['foton_setting']['custom']);
                    $path_custom = str_replace($_SERVER['SERVER_NAME'],$custom_p,$GLOBALS['foton_setting']['path']);
                    if(file_exists($path_custom . '/' . $path)){
                        return $path_custom . '/' . $path;
                    }
                    else{
                        return $path;
                    }
                }
                else{
                    if(file_exists($GLOBALS["foton_setting"]["path"] . '/'.$GLOBALS['foton_setting']['custom'].'/' . $path)){
                        return $GLOBALS["foton_setting"]["path"] . '/'.$GLOBALS['foton_setting']['custom'].'/' . $path;
                    } 
                    else{
                        return $path;
                    }
                }
            }
            else {
                return $path;
            }
        }
        else if (isset($GLOBALS['foton_setting']['custom'])) {
            $path = str_replace($GLOBALS["foton_setting"]["path"].'/','',$path);
            if(strrpos($GLOBALS['foton_setting']['custom'],':')!=false){
                $custom_p=str_replace(':','',$GLOBALS['foton_setting']['custom']);
                $path_custom = str_replace($_SERVER['SERVER_NAME'],$custom_p,$GLOBALS['foton_setting']['path']);
                if(file_exists($path_custom . '/' . $path)){
                    return $path_custom . '/' . $path;
                }
                else{
                    return $path;
                }
            }
            else{
                if(file_exists($GLOBALS["foton_setting"]["path"] . '/'.$GLOBALS['foton_setting']['custom'].'/' . $path)){
                    return $GLOBALS["foton_setting"]["path"] . '/'.$GLOBALS['foton_setting']['custom'].'/' . $path;
                } 
                else{
                    return $path;
                }
            }
        }
        else {
            return $path;
        }
    }
    else{
       return $path; 
    }
}

function FotonHttps(){
	$_SERVER['HTTP_HOST'] = str_replace(':80',':443',$_SERVER['HTTP_HOST']);
    $uri = substr($_SERVER['REQUEST_URI'], -1); 
    if($uri!='/'){
	    $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'].'/';
    }  
    if(empty($_SERVER['HTTP_HTTPS']) || $_SERVER['HTTP_HTTPS']!='YES'){       
        header("HTTP/1.1 301 Moved Permanently");                                
        header("Location:https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);          
    }        
    if($last!='/' && empty($arr301[$_SERVER['REQUEST_URI']]) && !stristr($_SERVER['REQUEST_URI'],'?') && !stristr($_SERVER['REQUEST_URI'],'.jpg') && !stristr($_SERVER['REQUEST_URI'],'.png') && !stristr($_SERVER['REQUEST_URI'],'.svg') && !stristr($_SERVER['REQUEST_URI'],'.jpeg') && !stristr($_SERVER['REQUEST_URI'],'.php')){
	    header("HTTP/1.1 301 Moved Permanently"); 
	    header("Location:https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."/");
    }
    $url_no_get = preg_replace('#\?utm_source=YandexMarket&utm_medium=cpc&offer=([^=]+)$#','',$_SERVER['REQUEST_URI']);
    if(isset($arr301[$_SERVER['REQUEST_URI']])){
	    $_SERVER['HTTP_HOST']=str_replace('www.','',$_SERVER['HTTP_HOST']);
	    header("HTTP/1.1 301 Moved Permanently"); 
	    header("Location: https://".$_SERVER['HTTP_HOST'].$arr301[$_SERVER['REQUEST_URI']]); 
    }
    if(strrpos($_SERVER['REQUEST_URI'],'%20')){
	    $new_url = str_replace('%20','-',$_SERVER['REQUEST_URI']);
	    $new_url = preg_replace('#%([0-9]+)([^0-9]{1})#','$2',$new_url);
	    header("HTTP/1.1 301 Moved Permanently"); 
	    header("Location: https://".$_SERVER['HTTP_HOST']. $new_url); 
    }
    else if(strripos($_SERVER['HTTP_HOST'],'www.')!==false){
	    $_SERVER['HTTP_HOST']=str_replace('www.','',$_SERVER['HTTP_HOST']);
	    header("HTTP/1.1 301 Moved Permanently"); 
	    header("Location:https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); 
    }
    else if(strripos($_SERVER['REQUEST_URI'],'index.php')!==false){
	    $_SERVER['REQUEST_URI']=str_replace('index.php','',$_SERVER['REQUEST_URI']);
	    header("HTTP/1.1 301 Moved Permanently"); 
	    header("Location:https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    }
    else{

    }       
}