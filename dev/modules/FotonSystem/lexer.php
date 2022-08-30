<?php  
namespace FotonSystem;
class lexer{
    function __construct(){
        $this->core = new \Foton\Core;
        $this->globpath = $GLOBALS['foton_setting']['path'].'/dev/modules/FotonSystem/lexer/lang/';
    }
    
    public function start($result=null){
        if($result!=null){
            if(is_array($result)){
                return $this->lang($result);
            }
            else{
                $l = $result[0];
                $result = substr($result, 1);
                if($l=='<'){
                    return $this->insert($result);
                }
                else if($l=='>'){
                    return $this->add($result);
                }
                else if($l==':'){
                    return $this->update($result);
                }
                else if($l=='^'){
                    return $this->create($result);
                }
                else if($l=='!'){
                    return $this->del($result);
                }
                else{
                    $result=$l.$result;
                    return $this->lang($result);
                }
            }
        }
        
    }
    
    public function update(){
        
    }
    
    public function create($command){
        $arr = explode('=',$command);
        $key = substr($arr[0], -1);
        $arr_new=[];
        $arr_new[$key] = $arr[1];
        $this->put($arr[0],$arr_new);
    }
    
    public function add($command){
        $arr = explode('=',$command);
        $arr_old = $this->get($arr[0]);
        $key = substr($arr[0], -1);
        $arr_old[$key] = $arr[1];
        $this->put($arr[0],$arr_old);
    }
    public function insert($command){
        $arr = explode('=',$command);
        $arr_old = $this->get($arr[0]);
        $key = substr($arr[0], -1);
        unset($arr_old[$arr[1]]);
        $this->put($arr[0],$arr_old);
    }
    public function del($command){
        
    }
    public function lang($result=null){
        if($result!=null){
            if(!is_array($result)){
                $result = explode(" ",$result);
            }
            foreach($result as $l){
                $path = explode("",$l);
                $file = array_pop($path);
                $path = implode("/",$path);
                if(file_exists($this->globpath.$path.'/'.$file.'.json')){
                    $result = json_decode(file_get_contents($this->globpath.$path.'/'.$file.'.json'),true);
                    $this->stack($result);
                }
            }
            return $this->StackResult();
        }
    }
    private function get($path){
        $path = explode("",$path);
        $file = array_pop($path);
        $path = implode("/",$path);
        if(file_exists($this->globpath.$path.'/'.$file.'.json')){
            return json_decode(file_get_contents($this->globpath.$path.'/'.$file.'.json'),true);
        }
    }
    
    private function put($path,$data){
        $path = explode("",$path);
        $file = array_pop($path);
        $paths='';
        foreach($path as $dir){
            $paths.='/'.$dir;
            if(!is_dir($this->globpath.$paths)){
                mkdir($this->globpath.$paths);
            }
        }
        $path = implode("/",$path);
        if(!file_exists($this->globpath.$path.'/'.$file.'.json')){
            file_put_contents($this->globpath.$path.'/'.$file.'.json',json_encode($data,true));
        }
    }
}