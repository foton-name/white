<?php
namespace Foton;

class CI{     
    public function __construct($param){
        global $core;
        $this->core = $core;
        $this->request = $this->core->request;
        $this->param = $param;
        $i=$this->core->i_arr_all($param->model,$param->table);
        $this->param->i_def='sp';
        if(!$i){
            $i=$this->core->i_arr_all($param->model,$param->table,1);
            $this->param->i_def=false;
            if(!$i){
                exit();
            }
        }               
        $this->param->i_arr=$i;
    }
    
    public function page(){     
        if(isset($this->param->page) && $this->param->id!='create' && $this->param->id!='update'){
            return $this->param->page-1;
        }
        else{
            return 0;
        }
    }

     //подключаем js полей и интерфейса
    public function js_i(){
        $js=$this->core->i_front_js($this->param->i);
        $type=$this->param->i_arr['format_select'];
        $js.=$this->core->tpl_front_js($type);
        return $js;
    }

    //подключаем css полей и интерфейса
    public function css_i(){
        $css=$this->core->i_front_css($this->param->i);
        $type=$this->param->i_arr['format_select'];
        $css.=$this->core->tpl_front_css($type);
        return $css;
    }

    //метод выводит название раздела
    public function h1(){
        if(isset($this->param->model)){
            return $this->core->i_h1($this->param->model,$this->param->table);
        }
    }
   
    //метод загрузки инициализирует создание, удаление и обновление полей таблицы и выдает результат для проверки
    public function ready(){  
        $arr_notif=array();
        if($this->core->i_alter($this->param->model,$this->param->table)){
            $arr_notif['alter']='yes';
        }
        if($this->core->i_create($this->param->model,$this->param->table)){
            $arr_notif['create']='yes';
        }
        if($this->core->i_drop($this->param->model,$this->param->table)){
            $arr_notif['delete']='yes';
        }        
        return $arr_notif;
    }
   
    //пользовательский метод инициализирует запись, удаление и обновление данных таблицы
    public function callback(){
        if(isset($this->param->post)){
            if(isset($this->param->del) && isset($this->param->pid)){
                $this->param->post=$this->core->i_upload('yes',$this->param->post);
                $this->core->i_delete($this->param->model,$this->param->table,$this->param->pid,1,1);
            }   
            if(isset($this->param->pid)){
               if(isset($this->param->create) && $this->param->create=='0'){
                   $this->param->post=$this->core->delete_post($this->param->post,'foton_create');
                   $this->param->post=$this->core->i_upload('yes',$this->param->post);
                   $this->core->i_insert($this->param->model,$this->param->table,$this->param->post,1,1);
                }
            else{            
                    $this->param->post=$this->core->i_upload('yes',$this->param->post);
                    $this->core->i_update($this->param->model,$this->param->table,$this->param->post,1,1);
               }
            }
        }
    }
  
    //пользовательский метод выводит массив значений для фильтра поиска в формате поле=>значение
    public function find_value(){
        if($this->param->i_def){    
            if(empty($this->param->sort) && isset($this->param->post)){                
                return $this->core->arr_session($this->param->post,$this->param->table,'find_','','where',1);
            }
            else{
                return $this->core->arr_session(null,$this->param->table,'find_','','where',1);
            }
        }
    }
    
    //пользовательский метод выводит массив значений для фильтра поиска в формате поле=>значение
    public function find_sql(){
        if($this->param->i_def){
            $value_f = $this->find_value();
            if($value_f){
            foreach($value_f as $key_w=>$where){
              $arr_return['%'.$key_w]=$where;
            }
            return $arr_return;
            }
            else{
                return null;
            }
        }
    }
    
    public function ascdesc(){
         if($this->param->i_def){
             if(isset($this->param->sort)){
            $arr=$this->core->arr_session(array('sort'=>$this->param->sort,'sort_field'=>$this->param->fsort),$this->param->table);
            return array($arr['sort_field']=>$arr['sort']);
             }
             else if(isset($_SESSION[$this->param->table]['sort'])){
                  return array($_SESSION[$this->param->table]['sort_field']=>$_SESSION[$this->param->table]['sort']);
             }
             else{
                return array('id'=>'DESC'); 
             }
         }
    }
    
    public function sort_field(){
        if($this->param->i_def){
            if(isset($_SESSION[$this->param->table]['sort']) && $_SESSION[$this->param->table]['sort']=='ASC'){
                return 'DESC';
            }
            else{
                return 'ASC';
            }
        }
    }
    
    public function filter_find(){
        if($this->param->i_def){
            $where=$this->find_value();
            $face=array('model'=>$this->param->model,'table'=>$this->param->table,'interface'=>$this->param->i_def,'extra_arr'=>$this->param->find,'fields_table'=>'field_filter','fields_type'=>'format_filter','create'=>true,'create_value'=>$where);
            return $this->core->i_list($face);
        }
    }
    
    public function arr_field_sort(){
        if($this->param->i_def){
            return $this->param->i_arr['field_filter'];
        }
    }
    
    public function filter_sort(){
        if($this->param->i_def){
            $face=array('model'=>$this->param->model,'table'=>$this->param->table,'interface'=>$this->param->i_def,'fields_table'=>'field_filter','fields_type'=>'format_sort','create'=>true);
            return $this->core->i_list($face);
        }
    }
    
    public function echo_lists(){
        if($this->param->i_def){
            $where=$this->find_sql();
            $sql = array('where'=>array('field'=>$where,'and'=>'OR'),'count'=>$this->param->i_arr[$this->param->pgn],'page'=>$this->page(),'sort'=>$this->ascdesc());
            $face=array('model'=>$this->param->model,'table'=>$this->param->table,'interface'=>$this->param->i_def,'extra_arr'=>$this->param->extra,'fields_table'=>'field_filter','fields_type'=>'format_select_list');
            $arr_res = $this->core->i_list($face,$sql);
            if(isset($arr_res)){
                return $arr_res;
            }
            else{
                return array();
            }
        }
    }
    
    public function create(){
        if(isset($this->param->id) && $this->param->id=='create'){
            $face=array('model'=>$this->param->model,'table'=>$this->param->table,'interface'=>$this->param->i_def,'extra_arr'=>$this->param->extra,'fields_table'=>'field','fields_type'=>'format_select','create'=>true);
            return $this->core->i_list($face);
        }
        else if(!$this->param->i_def){
             $face=array('model'=>$this->param->model,'table'=>$this->param->table,'interface'=>1,'extra_arr'=>$this->param->extra,'fields_table'=>'field','fields_type'=>'format_select','create'=>true);
            return $this->core->i_list($face);
        }
        else{
            return false;
        }
    }
    
    public function update(){
        if($this->param->i_def){
            if(isset($this->param->id) && $this->param->id!='pagin' && $this->param->id!='create'){
                $sql = array('where'=>array('field'=>array('id'=>$this->param->id),'and'=>'OR'));
                $face=array('model'=>$this->param->model,'table'=>$this->param->table,'interface'=>$this->param->i_def,'extra_arr'=>$this->param->extra,'fields_table'=>'field','fields_type'=>'format_select');
                return $this->core->i_list($face,$sql);
            }
            else{
                return false;
            }
        }
    }
    
    public function is_list(){
        if($this->param->i_def){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function list_one(){
        if(!$this->param->i_def){
           $face=array('model'=>$this->param->model,'table'=>$this->param->table,'interface'=>1,'extra_arr'=>$this->param->extra,'fields_table'=>'field','fields_type'=>'format_select');
           $for_one = $this->core->i_list($face); 
           if($for_one){
               return $for_one;
           }
           else{
               return array();
           }
        }
    }
    
    public function pagination(){
        if($this->param->i_def){
            $where=$this->find_sql();
            if(is_array($where) && count($where)>0){
                $count = $this->core->table($this->param->table)->where($where)->c();
            }
            else{
               $count = $this->core->table($this->param->table)->c();  
            }
            $count = ceil($count/$this->param->i_arr[$this->param->pgn]);
            return $count;
        }
    }
}

class MI{
    function __construct(){
        global $core;
        $this->core = $core;
        $this->request = $this->core->request;
    }
    public function param(){ 
        if(empty($this->request->g['3']) || empty($this->request->g['2']) || $this->request->g['3']=='' || $this->request->g['2']==''){
            exit();
        }
        $param = new \stdClass();
        $param->extra = array('name'=>'lang');
        $param->find = array('text_filter'=>'lang');
        $param->i_def='sp';
        $param->i = 'list';
        $param->model=$this->request->g['2'];
        $param->table=$this->request->g['3'];
        $param->pgn="pagination";
        if(isset($this->request->g['4'])){
            $param->id = $this->request->g['4'];
        }
        if(isset($this->request->g['5'])){
            $param->page = $this->request->g['5'];
        }
        if(isset($this->request->p['delete-insert-include'])){
            $param->del = $this->request->p['delete-insert-include'];
        }
        if(isset($this->request->p['id'])){
            $param->pid = $this->request->p['id'];
        }
        if(isset($this->request->p['foton_create'])){
            $param->create = $this->request->p['foton_create'];
        }
        if(isset($_POST)){
            $param->post = $this->request->p;
        }
        if(isset($this->request->p['foton_sort'])){
            $param->sort = $this->request->p['foton_sort'];
        }
        if(isset($this->request->p['sort_field'])){
            $param->fsort = $this->request->p['sort_field'];
        }
        return $param;
    }
}

class RenderLib{
    public $url;
    public $controller_class;
    public $model_class;
     public function __construct(){
        $this->router = new Router;
        $this->config = new Config;
        $this->core=$this->config->core;
        $this->request = $this->core->request;
        $this->db = $this->core->db();
        $this->widget = $this->config->widget;        
    }   
    public function CompressPath($path=null,$format=null,$action=false){
        if($path!=null && $format!=null){
            $name = md5($path);
            $paths = $GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/compress';
            if(file_exists($paths.'/'.$name.".".$format) && !$action){
                $paths = str_replace($GLOBALS['foton_setting']['path'],'',$paths);
                return $paths.'/'.$name.".".$format;
            }
            else {
                if(!file_exists($paths)){
                    mkdir($paths);
                }
                $text='';
                foreach(glob($GLOBALS['foton_setting']['path'].$path.'/*.'.$format) as $file){
                    $text.=file_get_contents($file);
                }
                if($format=='css'){
                  $text=str_replace("\r","",$text);
                  $text=str_replace("\n","",$text);
                }
                file_put_contents($paths.'/'.$name.".".$format,$text);
                $paths = str_replace($GLOBALS['foton_setting']['path'],'',$paths);
                return $paths.'/'.$name.".".$format;
            }
           
        }
        else{
            return false;
        }
    }
    public function CompressArr($arr=null,$format=null,$action=false){
        if($arr!=null && $format!=null){
            $name = md5(json_encode($arr));
            $paths = $GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/compress';
            if(file_exists($paths.'/'.$name.".".$format) && !$action){
                $paths = str_replace($GLOBALS['foton_setting']['path'],'',$paths);
                return $paths.'/'.$name.".".$format;
            }
            else{
                if(!file_exists($paths)){
                    mkdir($paths);
                }
                $name = md5(json_encode($arr));
                $text='';
                foreach($arr as $file){
                    $text.=file_get_contents($GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].$file);
                }
                if($format=='css'){
                  $text=str_replace("\r","",$text);
                  $text=str_replace("\n","",$text);
                }
                file_put_contents($paths.'/'.$name.".".$format,$text);
                $paths = str_replace($GLOBALS['foton_setting']['path'],'',$paths);
                return $paths.'/'.$name.".".$format;
            }
            
        }
        else{
            return false;
        }
    }
    public function globdata($dir=null){
        if($dir==$GLOBALS['foton_setting']['admindir'] && $this->config->core->isAuth() && !class_exists('\Controller_Globals')){
            require_once $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_c.$GLOBALS['foton_setting']['admindir'].'_globals.php');
        }
        else if(!class_exists('\Controller_Globals')){
            require_once $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_c.$GLOBALS['foton_setting']['sitedir'].'_globals.php');
        }
        else{
            
        }
        if(class_exists('\Controller_Globals')){ }else{eval("class Controller_Globals{};");} 
            $glob = new \Controller_Globals;
            foreach(get_class_methods('\Controller_Globals') as $val){
              if(method_exists('\Controller_Globals',$val)!==false){
                $global[$val] = $glob->$val();
            }
        }
        if(empty($global)){
            $global='';
        }
        $arr['global']=$global;
        $arr['glob']=$glob;
        return $arr;
    }
    public function callback($text){
        $obj = new Event($this->data);
        $method = 'Callback'.$this->obpage;
        if($this->config->core->isAuth()){
            $methodglob = 'CallbackAdmin';
        }
        else{
            $methodglob = 'CallbackGlob';
        }
        if(method_exists($obj,$methodglob)){
            $text = $obj->$methodglob($text);
        }
        if(method_exists($obj,$method)){
            $text = $obj->$method($text);
        }
        return $text;
        
    }
    public function html($get,$page,$arrf){
            $dir=$arrf['dir'];
            unset($arrf['dir']);
            $this->router->tpl_html();
            $arr = $this->globdata($dir);
            $global = $arr['global']; 
        if(isset($get['modules_status']) && $get['modules_status']=='modul'){
            foreach($this->router->css_page('modul',$page) as $css){
                $this->render_css($css);
            }    
            foreach($this->router->js_page('modul',$page) as $js){
                $this->render_js($js);
            }
            if(is_array($page)){
                $item = $page[1];
                $page = $page[0];
            }
            $c_p = $page.'_c';
            $m_p = $page.'_m';
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
            $dir_m=$get[1];
            $data=$get[0];
             if($dir_m!='0'){
                    $this->region($this->config->region[0],$dir);
                }   
                foreach($arrf as $pathf){
                    require_once $pathf;
                }
                 if($dir_m!='0'){
                    $this->region($this->config->region[1],$dir);
                } 
        }
        else{
            $c_p = '\Controller_'.$page;
            $m_p = '\Model_'.$page;
            $g_p = '\Controller_Globals';
             if(class_exists($g_p)){
                $glob = new $g_p;
            }
            if(class_exists($m_p)){
                $model_class = new $m_p;
            }
            if(class_exists($c_p)){
                $controller_class = new $c_p;
            } 
            $dir=$get[1];
            $data=$get[0];          
            if($page!=$GLOBALS['foton_setting']['admin'] && (empty($get[0]['region']) || $get[0]['region']!='no')){
                $this->region($this->config->region[0],$dir);
            }            
             foreach($arrf as $pathf){
                 if(!file_exists($pathf)){
                     $pathf=$GLOBALS['foton_setting']['path'].$pathf;                    
                     if(file_exists($pathf)){
                         if(isset($GLOBALS['foton_setting']['obstart']) && $GLOBALS['foton_setting']['obstart']=='Y'){
                             $this->obpage = $page;
                             $this->data = $data;
                             ob_start(array($this,'callback'));
                             require_once $pathf;
                             ob_end_flush();
                         }
                         else{
                             require_once $pathf;
                         }
                     }
                     else{
                         $this->router->ErrorPage404(); 
                     }
                 }
                 else{
                     if(isset($GLOBALS['foton_setting']['obstart']) && $GLOBALS['foton_setting']['obstart']=='Y'){
                         $this->obpage = $page;
                         $this->data = $data;
                         ob_start(array($this,'callback'));
                         require_once $pathf;
                         ob_end_flush();
                     }
                     else{
                        require_once $pathf; 
                     }
                    
                 }
            }            
            if($page!=$GLOBALS['foton_setting']['admin'] && (empty($get[0]['region']) || $get[0]['region']!='no')){
                $this->region($this->config->region[1],$dir);
            }
        }
        
    }    public function preload($get,$page){  
        if(isset($get['modules_status']) && $get['modules_status']=='modul'){
            if(is_array($page)){
              $item = $page[1];
              $page = $page[0];
          }
          if(isset($item)){
              $path = $GLOBALS['foton_setting']['path'].$this->config->path_modul."/".$page."/item/".$item.".php";
              if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/modul/".$GLOBALS['foton_setting']['lang'].'/'.$page."/".$item.".php"))){
                  $arr_r[]=$this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/modul/".$GLOBALS['foton_setting']['lang'].'/'.$page."/".$item.".php"); 
              }
          }
          else{
              $path = $GLOBALS['foton_setting']['path'].$this->config->path_modul."/".$page."/index.php";
              if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/modul/".$GLOBALS['foton_setting']['lang'].'/'.$page."/index.php"))){
                  $arr_r[]=$this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/modul/".$GLOBALS['foton_setting']['lang'].'/'.$page."/index.php"); 
              }
          }
          if(file_exists($this->config->core->git($path))){                            
              $arr_r[]=$this->config->core->git($path);                
          }
          else{
             $this->router->ErrorPage404(); 
          }
          $dir=$GLOBALS['foton_setting']['admindir'];
         
      }
      else{
   
          $dir=$get[1];
          $data=$get[0];
          if(isset($this->config->alias[$page])){
               $arr_r[]=$this->config->core->git($GLOBALS['foton_setting']['path'].'/'.$this->config->alias[$page]);
          }
          else{
              if(stristr($page,'_view.')===false){
                    $pagetpl=$page."_view.tpl";
                    $page=$page."_view.php";
              }
              if(stristr($page,'_mob')===false){
                    $page_mob=str_replace('_view.php','_mob_view.php',$page);
                    $pagetpl_mob=str_replace('_view.tpl','_mob_view.tpl',$pagetpl);
              }
              else{
                    $page_mob=$page;
                    $page=preg_replace('#(.+)_mob$#','$1',$page);
                    $pagetpl=preg_replace('#(.+)_mob$#','$1',$pagetpl);
                    $pagetpl_mob=str_replace('_view.tpl','_mob_view.tpl',$pagetpl);
              }
              if(stripos($dir,'/')){
                  $dir = explode('/',$dir)[0];
              }
              if($this->config->core->mobile_foton()===true && file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$dir."/".$page_mob))){
                 
                  $path = $GLOBALS["foton_setting"]["path"].$this->config->path_mvc."/".$dir."/".$page_mob;
                  if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$page_mob))){
                       $arr_r[]=$this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$page_mob); 
                  }
                   $arr_r[]=$this->config->core->git($path);
                 
              }
              else if($this->config->core->mobile_foton()===true && file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$dir."/".$pagetpl_mob))){
                 
                  $path = $GLOBALS["foton_setting"]["path"]."/system/api/tpl/".$dir."/".$page_mob;
                  if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$page_mob))){
                       $arr_r[]=$this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$page_mob); 
                  }
                   $arr_r[]=$this->config->core->git($path);
                 
              }
              else if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$dir."/".$page))){
                 
                  $path = $GLOBALS["foton_setting"]["path"].$this->config->path_mvc."/".$dir."/".$page;
                  if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$page))){
                       $arr_r[]= $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$page); 
                  }
                  $arr_r[]=$this->config->core->git($path);
                  
              }
              else if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$dir."/".$pagetpl))){
                  
                  $path = $GLOBALS["foton_setting"]["path"]."/system/api/tpl/".$dir."/".$page;
                  if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$page))){
                      $arr_r[]= $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$page); 
                  }
                  $arr_r[]= $this->config->core->git($path);
                  
              }
              else{
                  $this->router->ErrorPage404();
              }
          }
      }
      $arr_r['dir'] = $dir;
      return $arr_r;
  }
    public function render_css($path){
        echo '<link rel="stylesheet" type="text/css" href="'.$path.'">';
    }
    public function render_js($path){
        echo "<script src='".$path."'></script>";
    }
    public function region($region='head',$dir=null){
        $g_p = '\Controller_Globals';
             if(class_exists($g_p)){
                $glob = new $g_p;
            }
            $arr = $this->globdata($dir);
            $global = $arr['global'];
           
      if($dir==null){
      if($this->config->core->isAuth()){
        if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["admindir"]."/".$region.".php"))){
                if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS["foton_setting"]["admindir"]."/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'))){
                    require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS["foton_setting"]["admindir"]."/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'); 
                }
                
                if(file_exists($this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["admindir"]."/".$region.".tpl"))){
                    require_once $this->core->git($GLOBALS["foton_setting"]["path"].'/system/api/tpl/'.$GLOBALS["foton_setting"]["admindir"]."/".$region.".php");
                }
                else{
                    require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["admindir"]."/".$region.".php");
                }
      }
      else if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$region.".php"))){
                if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'))){
                    require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'); 
                }
          require_once $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc."/".$region.".php");
      }
      else{

      }
    }
    else{
      if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["sitedir"]."/".$region.".php"))){
                if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS["foton_setting"]["sitedir"]."/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'))){
                    require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS["foton_setting"]["sitedir"]."/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'); 
                }
                if(file_exists($this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["sitedir"]."/".$region.".tpl"))){
                    require_once $this->core->git($GLOBALS["foton_setting"]["path"].'/system/api/tpl/'.$GLOBALS["foton_setting"]["sitedir"]."/".$region.".php");
                }
                else{
                    require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$GLOBALS["foton_setting"]["sitedir"]."/".$region.".php");
                }
      }
      else if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$region.".php"))){
                if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'))){
                    require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'); 
                }
                if(file_exists($this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc."/".$region.".tpl"))){
                    require_once $this->core->git($GLOBALS["foton_setting"]["path"].'/system/api/tpl/'.$region.".php");
                }
                else{
                    require_once $this->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc."/".$region.".php");
                }
          
      }
      else{

      }
    }
      }
      else{
          if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$dir."/".$region.".php"))){
                if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'))){
                    require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'); 
                }
          require_once $this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$dir."/".$region.".php");
      }
      else if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"].$this->config->path_mvc.$dir."/".$region.".tpl"))){
          if(file_exists($this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'))){
                    require_once $this->config->core->git($GLOBALS["foton_setting"]["path"]."/app/lang/".$dir."/".$GLOBALS['foton_setting']['lang'].'/'.$region.'.php'); 
                }
          require_once $this->config->core->git($GLOBALS["foton_setting"]["path"].'/system/api/tpl/'.$dir."/".$region.".php");
      }
      else{
          
      }
      }
    }

}



class dataReturnLib{
    function __construct(){
        $this->config = new Config;
        $this->core=$this->config->core;
        $this->request = $this->core->request;
        $this->router = new Router;
        $this->render = new Render;
    }
    
    public function interfaces($page,&$data=array()){
        $error=true;
        if($this->config->core->isAuth()){
            $class = 'Controller_'.$page;
            $controller_name = $class;
            $c_path = $GLOBALS["foton_setting"]["path"].'/app/controller/'.$GLOBALS["foton_setting"]["admindir"].'/controller_'.$page.'.php';
            if (file_exists($this->config->core->git($c_path))) {
                if(isset($controller_name)){
                    require_once $this->config->core->git($c_path);
                    $controller_class = new $class;
                    if(method_exists($controller_class,'parent')!==false){
                        $parent_name=$controller_class->parent();
                        if($parent_name=='default'){
                            $param = $controller_class->param();
                            $obj_def = new \Foton\CI($param);
                            foreach(get_class_methods($obj_def) as $method_def){
                                if(method_exists($controller_class,'ignore')!==false && isset($controller_class->ignore()[$method_def])){
                                    $data[$method_def] = $controller_class->$method_def();
                                }
                                else{
                                    if($method_def!='__construct'){
                                        $data[$method_def] = $obj_def->$method_def();
                                    }
                                }
                            }
                            return $data;
                        }
                        else{
                            if(file_exists($this->config->core->git($this->config->path_m.$GLOBALS["foton_setting"]["admindir"]."/model_".$parent_name.".php"))){
                                require_once $this->config->core->git($this->config->path_m.$GLOBALS["foton_setting"]["admindir"]."/model_".$parent_name.".php");
                            }
                            $parent_path = $this->config->core->git($GLOBALS['foton_setting']['path'].'/app/controller/'.$GLOBALS["foton_setting"]["admindir"]."/controller_".$parent_name.".php");
                            if(file_exists($parent_path)){
                                require_once $parent_path;
                                $p_class="Controller_".$parent_name;
                                $parent_class = new $p_class;
                                if(method_exists($controller_class,'parent_method')!==false){
                                    $arr_method=$controller_class->parent_method();
                                    foreach($arr_method['method'] as $key=>$val){
                                      try {                
                                        if(isset($arr_method['args']) && is_array($arr_method['args'])){
                                            if(method_exists($parent_class,$val)!==false){
                                                if(is_array($arr_method['args'][$key])){                                              
                                                    $data[$val] = $parent_class->$val(...array_values($arr_method['args'][$key]));
                                                }
                                                else{
                                                    $data[$val] = $parent_class->$val();
                                                }
                                            }
                                        }
                                        else{
                                            if(method_exists($parent_class,$val)!==false){
                                                $data[$val] = $parent_class->$val();
                                            }
                                        }
                                      } 
                                      catch (\Throwable $e) {
                                        if(isset($GLOBALS["foton_setting"]["debug"]) && $GLOBALS["foton_setting"]["debug"]===true){ 
                                          $this->core->log_file($class,$e->getMessage(),'yes');    
                                        }
                                        $this->core->log($e->getMessage());                               
                                      }
                                    }
                                    if($parent_class instanceof Face){
                                      return $this->interfaces($parent_name,$data);
                                    }
                                }
                            }
                            else{
                              
                            }
                        }
                    }
                    else{
                        return $data;
                    }
                }
            }
        }
        if($error){
            return array();
        }
    }

    public function Methodreturn($page=null,$init=null,$var=null){
           if(isset($page) && $page!=null){
            $model_name = '\model_'.$page;
            $controller_name = '\controller_'.$page;
            $model_file = str_replace('\\','',strtolower($model_name)).'.php';
            $model_path = $this->config->core->git("app/model/".$model_file);
            if(file_exists($model_path))
            { 
                require_once $this->config->core->git("app/model/".$model_file);
               if(method_exists($model_name,$init)){
                   $foo = new $model_name;
                    if(isset($var)){ 
                       if(gettype($var)=='array'){
                           return call_user_func_array(array($foo, $init),$var);
                       }
                       else{
                           return $foo->$init($var);
                       }
                    }
                    else{
                        return $foo->$init();
                    }
                }
            }
         
        }
        else{ 
             $model = 'Model';
             if(method_exists($model,$init)){
                return $this->$init($var);

            }
        }
    }
    public function globdata($dir=null){
        if($dir==$GLOBALS['foton_setting']['admindir'] && $this->config->core->isAuth() && !class_exists('\Controller_Globals')){
            require_once $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_c.$GLOBALS['foton_setting']['admindir'].'_globals.php');
        }
        else if(!class_exists('\Controller_Globals')){
            require_once $this->config->core->git($GLOBALS['foton_setting']['path'].$this->config->path_c.$GLOBALS['foton_setting']['sitedir'].'_globals.php');
        }
        else{
            
        }
            if(class_exists('\Controller_Globals')){ }else{eval("class Controller_Globals{};");} 
            $glob = new \Controller_Globals;
            foreach(get_class_methods('\Controller_Globals') as $val){
              if(method_exists('\Controller_Globals',$val)!==false){
                try{
                  $global[$val] = $glob->$val();
                } 
                catch (\Throwable $e) {
                  if(isset($GLOBALS["foton_setting"]["debug"]) && $GLOBALS["foton_setting"]["debug"]===true){ 
                    $this->core->log_file('glob',$e->getMessage(),'yes');    
                  }
                  $this->core->log($e->getMessage());                               
                }
            }
        }
        if(empty($global)){
            $global='';
        }
        $arr['global']=$global;
        $arr['glob']=$glob;
        return $arr;
    }

    public function globstart ($init=null,$var=null) {
        $global_c = '\Controller_Globals';
        if(method_exists($global_c,$init)){
            $foo = new $global_c;
               if(isset($var)){
                   if(gettype($var)=='array'){
                       return call_user_func_array(array($foo, $init),$var);
                   }
                   else{
                       return $foo->$init($var);
                   }
               }
               else{
                return $foo->$init();
            }
     
        }
    }
}