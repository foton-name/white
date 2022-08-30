<?php
namespace Foton;

class Handler{
    function __construct($arr){
		global $core;
        $this->config = new Config();
        $this->router = new Router();
        $this->render = new Render();
        $this->core = $core;
        foreach($arr as $key=>$val){
            $this->{$key} = $val;
        }
        return $this->start($arr);
    }
    
    private function err503($date=''){
        $this->core->status(503);
        $this->render->mvc([$date,$this->dir],$this->page);
        exit();
    }

    private function request(){
        if(isset($this->core->request->g)){
            foreach($this->core->request->g as $k=>$v){
                    if(!$this->core->valid->request($v,true)){
                        $this->render->mvc(['',$this->dir],$this->error);
                        exit(); 
                    }
                }
            
        }
    }
    
    private function start($arr){
        $this->obj = new \StdClass();
        foreach($arr as $key=>$val){
            if(is_string($val)){
                $name = $key.'_'.$val;
                if(method_exists($this,$name)){
                    $this->obj = $this->$name();
                    if(isset($this->stop)){
                        break;
                    }
                }
            }
        }
        return $this->obj;
    }
    
    // public function dir_site(){
    //     $this->page = 'err503';
    //     $this->err503();
    //     $this->request();
    //     $this->stop=true;
    //     return $this;
    // }
    
    // public function format_xml(){
    //     $this->page = 'html';
    //     $this->get = 'new get';
    //     $this->format = 'json';
    //     return $this;
    // }
    
    // public function page_html(){
    //     $this->get = 'new get';
    //     return $this;
    // }
}