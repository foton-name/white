<?php
  class Panelsections_a extends Panelsections_c{
    
    public function table_section_view(){
        if(isset($this->request->p['mass'])){
         
          $arr=unserialize($this->request->p['mass']);  
     require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/item1.php';
    
        }
    }
    
    public function del_razdel(){
        if($this->request->p['r']!=''){
            $r=$this->request->p['r'];
            require_once $GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/model_'.$r.'.php';
             $method = "names";
             // проверяем есть ли данный метод у этой модели, если есть подключаем
             $name_model='Model_'.$r;
             $obj_m=new $name_model;
           if(method_exists($name_model,$method)){
               $arr=$obj_m->$method();
           }
           foreach($arr as $key=>$val){
              $this->db->query("DROP TABLE ".$key);
                $this->core->dir_delete_foton($GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/img/'.$key);
               
           }
            unlink($GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS["foton_setting"]["sitedir"].'/'.$this->request->p['r'].'_view.php');
            unlink($GLOBALS['foton_setting']['path'].'/app/controller/'.$GLOBALS['foton_setting']['sitedir'].'/controller_'.$this->request->p['r'].'.php');
            unlink($GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/model_'.$this->request->p['r'].'.php');
         }
        
    }
    public function delpole(){
    if(isset($this->request->p['table'])){
           $_SESSION['del_pole'][$this->request->p['table']][]=$this->request->p['pole'];
      
    }    
    
    }
    
    
    public function deltb(){
    
     $_SESSION['del_table'].=$this->request->p['table'].'::';
      $this->core->dir_delete_foton($GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/img/'.$this->request->p['table']);
    }
    public function dop_table(){
        if(isset($this->request->p['razdel'])){
            $str=strtolower($this->translitesthis_tb_no($this->request->p['razdel']));
            $razdel=$this->request->p['razdel'];
                require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/item_add_table.php'; 
        }
        
    }
    public function update_razdel(){
        if(isset($this->request->p['table'])){
           $categ='';
           $arr_tables='';
           $arr_tables2='';
            $arr1=explode('@@@',$this->translitesthis_tb($this->request->p['table']));
            require_once $GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/model_'.$_SESSION['section'].'.php';
            $class_f='Model_'.$_SESSION['section'];
            $obj_c=new $class_f;
            $method='interfaces';
             if(method_exists($class_f,$method)){
            $arr_tb_old=$obj_c->$method();
            }else{
                $arr_tb_old=array();
            }
            foreach($arr1 as $key2=>$val2){
            $arr2=explode(':::',$val2);
            $arr2_name=explode(':::',$arr1[$key2]);
            $table=explode('---',$arr2[0])[0];
            if(isset(explode('---',$arr2[0])[1])){
            $tb_name=explode('---',$arr2[0])[1];
            }
            if($table!=''){
            
            $categ.='"'.$table.'"=>"'.$this->translitesthis_tb_to($tb_name).'",';
            $arr_pole=explode('%%%',$arr2[1]);
            $arr_pole[0]=substr($arr_pole[0],3);
            $arr_pole[2]=substr($arr_pole[2],3);
            $arr_pole[1]=substr($arr_pole[1],3);
            $arr_pole[3]=substr($arr_pole[3],3);
            $pole_n=str_replace('---','","',$arr_pole[0]);
            $pole_f=str_replace('---','","',$arr_pole[2]);
            $pole_h=str_replace('---','||","||',$arr_pole[1]);
            $pole_nr=str_replace('---','","',$arr_pole[3]);
            $arr_pole_html=explode('---',$arr_pole[1]);
            $arr_pole_format=explode('---',$arr_pole[2]);
            $arr_pole_name=explode('---',$arr_pole[0]);
            $arr_pole_namer=explode('---',$arr_pole[3]);
            $pole_n2='';
            $pole_f2='';
            $pole_h2='';
            $pole_h3='';
            $pole_h5='';
            
            
            
            foreach($arr_pole_html as $k=>$v){
             
            if(($v=='text' || $v=='input') && $arr_pole_format[$k]!='' && $v!=''){
            $pole_n2.='"'.$arr_pole_name[$k].'",';
            $pole_f2.='"'.$arr_pole_format[$k].'",';
            $pole_h2.='"||'.$v.'||",';
            $pole_h5.='"||submit||",';
            $pole_h3.='"||text||",';
            }
            }
            $pole_n='"'.$pole_n.'"';
            $pole_nr='"'.$pole_nr.'"';
            $pole_f='"'.$pole_f.'"';
            $pole_h='"||'.$pole_h.'||"';
            
            
            
            
            $arr_tables.="\n\n".'"'.$table.'"=> array("field" => array("id",'.$pole_n.'),'."\n".'"format" => array("int",'.$pole_f.'),'."\n".'"name" => array("id",'.$this->translitesthis_tb_to($pole_nr).'),'."\n".'"format_select" => array("||ids||",'.$pole_h.'),'."\n".'"format_select_list" => array("||ids||",'.substr($pole_h3, 0, -1).'),'."\n".'"field_filter"=>array("id",'.substr($pole_n2, 0, -1).'),'."\n".'"text_filter"=>array("id",'.substr($pole_n2, 0, -1)."),\n".'"format_filter"=>array("||ids||",'.substr($pole_h2, 0, -1)."),\n".'"format_sort"=>array("||ids||",'.substr($pole_h5, 0, -1)."),\n".'"pagination"=>"10","key" => "id"),';
            $arr_tables2.="\n\n".'"'.$table.'"=> array("field" => array("id",'.$pole_n.'),'."\n".'"name" => array("id",'.$this->translitesthis_tb_to($pole_nr).'),'."\n".'"format_select" => array("||ids||",'.$pole_h.'),'."\n".'"format" => array("int",'.$pole_f.'),'."\n".'"key" => "id"),';
            
            }
            }
            $res_interface_sp='public function interface_sp(){$arr = array('.substr($arr_tables, 0, -1).'); return $arr;}'."\n";
            
            $res_interface='public function interfaces(){$arr = array('.substr($arr_tables2, 0, -1).'); return $arr;}'."\n";
            $res_names='public function names(){$arr = array('.substr($categ, 0, -1).');return $arr;}'."\n";;
            
            $_SESSION['res_interface_sp']=$res_interface_sp;
            $_SESSION['res_interface']=$res_interface;
            $_SESSION['res_names']=$res_names;
            
        }
        
    
    }
    
    
    
    public function kompilation(){
        if($this->request->p['table']){
            $_SESSION['table_check']=$this->request->p['table'];
        }
      
    
     
     $tb_res=array();
     if(isset($_SESSION['del_pole']) && is_array($_SESSION['del_pole'])){
    foreach($_SESSION['del_pole'] as $key=>$val){
    $pole=implode('","',$val);
    $tb_res[]='"'.$key.'" => array(
    "field" => array("'.$pole.'")
    )';
    }
    }
    $tb_res_str=implode(',',$tb_res);
    
   
    $del_tb_str=array();
    $del_tables=explode('::',$_SESSION['del_table']);
    foreach($del_tables as $key=>$val){
        if($val!=''){
    $del_tb_str[]='"'.$val.'" => array(
        "del" => "1"
        )';
        }
    }
    $del_tb_str=implode(',',$del_tb_str);

    $drop_res="public function drop_interface(){ \$arr = array(";
    if($tb_res_str!=''){
    $drop_res.=$tb_res_str;
    if($del_tb_str!=''){
    $drop_res.=','.$del_tb_str;
    }
    }
    else{
     if($del_tb_str!=''){
    $drop_res.=$del_tb_str;
    }   
        
    }
    
    $drop_res.=');return $arr;}';
    
     $tb_check = explode(':::',$_SESSION['table_check']);
    $return_check='';
    if(isset($tb_check)){
    foreach($tb_check as $val){
        if($val!=''){
    $val_tb=explode('%%%',$val);
    $val_tb_sp=str_replace('---',',',$val_tb[1]);
    $val_tb_sp = substr($val_tb_sp, 1);
    }
    if($val!=''){
    $return_check.="public function ".$val_tb[0]."_chmod(){
         return [".$val_tb_sp."];
        
    }\n";
    
    }
    }
    }
    $_SESSION['del_pole']=$drop_res;
    $_SESSION['table_check']=$return_check;
        require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/item5.php'; 
        
    }
    public function is_razdel(){
        $res='no';
        if(isset($this->request->p['razdel']) && $this->request->p['razdel']!=''){
             foreach (glob($GLOBALS["foton_setting"]["path"]."/app/model/*") as $filename) {
                 if(basename($filename)=='model_'.$this->request->p['razdel'].'.php'){
                    $res='yes'; 
                 }
             }
             
              foreach (glob($GLOBALS["foton_setting"]["path"]."/app/controller/".$GLOBALS['foton_setting']['sitedir']."/*") as $filename) {
                 if(basename($filename)=='controller_'.$this->request->p['razdel'].'.php'){
                    $res='yes'; 
                 }
             }
             echo $res;
        }
        
    }
    public function new_pole(){
        $arr=$this->core->select_db('html');
    
        
        require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/new_pole.php';
    }
    
    public function new_pole_sp(){
        $arr=$this->core->select_db('html');
        require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/new_pole_sp.php';
    }
    
    public function dop_check(){
    if(isset($this->request->p['tb'])){
    $name=$this->request->p['tb'];
    $tb=strtolower($this->translitesthis_tb_no($name));
    
    $arr=$this->core->select_db('role');
    
      require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/add_check.php';
    }
    }
    
    
    public function new_pole0(){
        $arr3=$this->core->select_db("role");
        require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/new_pole0.php';
    }
    public function translites(){
       
        if(isset($this->request->p['text'])){
        $name=$this->request->p['text'];
        echo strtolower($this->core->tr($name));
        }
    }
    public function translitesthis_tb_no ($text=null) {
    $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'е' => 'e',   'ж' => 'j',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sch',  'щ' => '',
            'ь' => '',  'ы' => 'i',   'ъ' => '',
            'э' => 'e',   'ю' => 'y',  'я' => 'ya',' '=>''
        );
		$text=mb_strtolower($text, 'UTF-8');
      return strtr($text, $converter);
        
    }
    public function translitesthis_tb ($text=null) {
    $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'е' => 'e',   'ж' => '!',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => '~',  'ш' => '#',  'щ' => '$',
            'ь' => '`',  'ы' => '^',   'ъ' => '&',
            'э' => '*',   'ю' => '{',  'я' => '}',' '=>'_'
        );
		$text=mb_strtolower($text, 'UTF-8');
      return strtr($text, $converter);
        
    }

    public function translitesthis_tb_to($text=null) {
    $converter = array(
            'a'=>'а',   'b'=>'б',   'v'=>'в',
            'g'=>'г',   'd'=>'д',   'e'=>'е',
            'e'=>'е',   '!'=>'ж',  'z'=>'з',
            'i'=>'и',   'y'=>'й',   'k'=>'к',
            'l'=>'л',   'm'=>'м',   'n'=>'н',
            'o'=>'о',   'p'=>'п',   'r'=>'р',
            's'=>'с',   't'=>'т',   'u'=>'у',
            'f'=>'ф',   'h'=>'х',   'c'=>'ц',
            '~'=>'ч',  '#'=>'ш',  '$'=>'щ',
            '`'=>'ь',  '^'=>'ы',   '&'=>'ъ',
            '*'=>'э',   '{'=>'ю',  '}'=>'я','_'=>' '
        );
		$text=mb_strtolower($text, 'UTF-8');
      return strtr($text, $converter);
        
    }
    private function translitesthis($text){
        return $this->translitesthis_tb($text);
    }

    public function chmod_ajax_tab3(){
    
        $filename=$GLOBALS["foton_setting"]["path"]."/app/model/".$GLOBALS['foton_setting']['sitedir']."/model_".$_SESSION['section'].".php";
        $arr = array();
        $arr2 = array();
        $arr22 = array();
        $arr3 = array();
        $name_file = "model_".$_SESSION['section'].".php";
        $name_model = str_replace('.php','',$name_file);
        require_once $filename;
        $obj_m=new $name_model;
        $name = str_replace('model_','',$name_model);
        if($name!='users' && $name!='html'){
         require_once $filename;
         $method = "names";
                    // проверяем есть ли данный метод у этой модели, если есть подключаем
               if(method_exists($name_model,$method)){
                   $arr=$obj_m->$method();
               }
               foreach($arr as $k=>$v){
                $method2 = $k."_chmod";
        if(method_exists($name_model,$method2)){
           $arr2[$k]=$obj_m->$method2();
           $arr22[$k]=$obj_m->$method()[$k];
           $arr3[$k]=$this->select_user($arr2[$k]);
           
           }
           }
        }
        $arr_role_key=$this->core->select_db('role');
       
        $arr_role=array();
        foreach($arr_role_key as $key=>$val){
            $arr_role[$val['text']]=$val['name'];
        }
        require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/item3.php'; 

    
    }
    
    public function table_ajax(){
        if(isset($this->request->p['table'])){            
            $filename=$GLOBALS["foton_setting"]["path"]."/app/model/".$GLOBALS['foton_setting']['sitedir']."/model_".$_SESSION['section'].".php";
            $arr = array();
            $name_file = "model_".$_SESSION['section'].".php";
            $name_model = str_replace('.php','',$name_file);
             require_once $GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/model_'.$_SESSION['section'].".php";
            $obj_m = new $name_model;
            $name = str_replace('model_','',$name_model);
            if($name!='interfaces'){
             require_once $filename;
             $method ="interfaces";
                        // проверяем есть ли данный метод у этой модели, если есть подключаем
                   if(method_exists($name_model,$method)){
                       $arr=$obj_m->$method()[$this->request->p['table']];
                   }
            
                }
                 $arr_html_select=$this->core->select_db('html');
                require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/item1_1.php'; 
        }
    
    }
    
    public function create_razdel(){
        if(isset($this->request->p['table']) && $this->request->p['table']!=''){
            $tb=strtolower($this->translitesthis_tb($this->request->p['table']));
            $arr1=explode('@@@',$tb);
            $arr1_name=explode('@@@',$this->request->p['table']);
            foreach($arr1 as $key2=>$val2){
                $arr2=explode(':::',$val2);
                $arr2_name=explode(':::',$arr1_name[$key2]);
                $table=$arr2[0];
                if($table!=''){
                    $table=$this->core->abc09($table);
                    $tb_name=$arr2_name[0];
                    $categ.='"'.$table.'"=>"'.$tb_name.'",';
                    $arr_pole=explode('%%%',$arr2[1]);
                    $arr_pole[0]=substr($arr_pole[0],3);
                    $arr_pole[2]=substr($arr_pole[2],3);
                    $arr_pole[1]=substr($arr_pole[1],3);
                    $arr_pole[3]=substr($arr_pole[3],3);
                    $arr_pole[4]=substr($arr_pole[4],3);
                    $pole_n=str_replace('---','","',$arr_pole[0]);
                    $pole_f=str_replace('---','","',$arr_pole[2]);
                    $pole_h=str_replace('---','||","||',$arr_pole[1]);
                    $pole_nr=str_replace('---','","',$arr_pole[3]);
                    $pole_p=str_replace('---',',',$arr_pole[4]);
                    $arr_pole_html=explode('---',$arr_pole[1]);
                    $arr_pole_format=explode('---',$arr_pole[2]);
                    $arr_pole_name=explode('---',$arr_pole[0]);
                    $arr_pole_namer=explode('---',$arr_pole[3]);
                    $arr_pole_prava=explode('---',$arr_pole[4]);
                    foreach($arr_pole_html as $k=>$v){
                        if(($v=='text' || $v=='input') && $arr_pole_format[$k]!=''){
                            $pole_n2.='"'.$arr_pole_name[$k].'",';
                            $pole_f2.='"'.$arr_pole_format[$k].'",';
                            $pole_h5.='"||submit||",';
                            $pole_h2.='"||'.$v.'||",';
                            $pole_h3.='"||text||",';
                        }
                    }
                        $pole_n='"'.$pole_n.'"';
                        $pole_nr='"'.$pole_nr.'"';
                        $pole_f='"'.$pole_f.'"';
                        $pole_h='"||'.$pole_h.'||"';
                        $pole_p=implode(',',$arr_pole_prava);
                        $arr_tables.="\n\n".'"'.$table.'"=> array("field" => array("id",'.$pole_n.'),'."\n".'"format" => array("int",'.$pole_f.'),'."\n".'"name" => array("id",'.$this->translitesthis_tb_to($pole_nr).'),'."\n".'"format_select" => array("||ids||",'.$pole_h.'),'."\n".'"format_select_list" => array("||ids||",'.substr($pole_h3, 0, -1).'),'."\n".'"field_filter"=>array("id",'.substr($pole_n2, 0, -1).'),'."\n".'"text_filter"=>array("id",'.substr($pole_n2, 0, -1)."),\n".'"format_filter"=>array("||ids||",'.substr($pole_h2, 0, -1)."),\n".'"format_sort"=>array("||ids||",'.substr($pole_h5, 0, -1)."),\n".'"pagination"=>"10","key" => "id"),';
                        $arr_tables2.="\n\n".'"'.$table.'"=> array("field" => array("id",'.$pole_n.'),'."\n".'"name" => array("id",'.$this->translitesthis_tb_to($pole_nr).'),'."\n".'"format_select" => array("||ids||",'.$pole_h.'),'."\n".'"format" => array("int",'.$pole_f.'),'."\n".'"key" => "id"),';
                        
                        $func_chmod.='public function '.$table.'_chmod(){  return ['.$pole_p.'];}';
                }
            }
            $res.='public function interface_sp(){$arr = array('.substr($arr_tables, 0, -1).'); return $arr;}'."\n";
            
            $res.='public function interfaces(){$arr = array('.substr($arr_tables2, 0, -1).'); return $arr;}'."\n";
            $res.='public function names(){$arr = array('.substr($categ, 0, -1).');return $arr;}'."\n";;
                    $res.=$func_chmod."\n";;
            $res.='public function nameinclude(){  return "'.$this->request->p['name'].'";}'."\n";
            
            $files='<? class Model_'.$this->request->p['file'].' extends Model{'.$res.'}';
               
               
            file_put_contents($GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/model_'.$this->request->p['file'].'.php',$files);
            $controller='<?class Controller_'.$this->request->p['file'].' extends Model_'.$this->request->p['file'].'{public function dir(){ return "'.$GLOBALS["foton_setting"]["sitedir"].'";}}';
            file_put_contents($GLOBALS['foton_setting']['path'].'/app/controller/'.$GLOBALS['foton_setting']['sitedir'].'/controller_'.$this->request->p['file'].'.php',$controller);
        }
    }
    
    public function compilation_res(){
        $file=file_get_contents($GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/model_'.$_SESSION['section'].'.php');
        if (preg_match("/public function names/i",$file)) {
            $file=preg_replace('#public function names\(\)\{([^\}]+)\}#',$_SESSION['res_names'],$file);
        }
        else{
            $file=substr($file, 0, -1);
             $file.=$_SESSION['res_names'].'}';
             
        }
        
         if (preg_match("/public function interface_sp/i",$file)) {
            $file=preg_replace('#public function interface_sp\(\)\{([^\}]+)\}#',$_SESSION['res_interface_sp'],$file);
        }
        else{
             $file=substr($file, 0, -1);
             $file.=$_SESSION['res_interface_sp'].'}';
             
        }
           if (preg_match("/public function drop_interface/i",$file)) {
            $file=preg_replace('#public function drop_interface\(\)\{([^\}]+)\}#',$_SESSION['del_pole'],$file);
        }
        else{
             $file=substr($file, 0, -1);
             $file.=$_SESSION['del_pole'].'}';
             
        }
            if (preg_match("/public function interfaces/i",$file)) {
            $file=preg_replace('#public function interfaces\(\)\{([^\}]+)\}#',$_SESSION['res_interface'],$file);
        }
        else{
             $file=substr($file, 0, -1);
             $file.=$_SESSION['res_interface'].'}';
             
        }
    
            $file=preg_replace('#public function ([^_]+)_chmod\(\)\{([^\}]+)\}#','',$file);
             $file=substr($file, 0, -1);
             $file.=$_SESSION['table_check'].'}';
          
             file_put_contents($GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/model_'.$_SESSION['section'].'.php',$file);
        
    }

    
    
    public function select_sections_ajax(){
        $_SESSION['del_pole']='';
        $_SESSION['del_table']='';
        $_SESSION['res_interface_sp']='';
        $_SESSION['res_interface']='';
        $_SESSION['res_names']='';
        $_SESSION['table_check']='';
        $arr = array();
        
        foreach (glob($GLOBALS["foton_setting"]["path"]."/app/model/".$GLOBALS['foton_setting']['sitedir']."/*.php") as $filename) {
            $name_file = basename($filename);
            $name_model = str_replace('.php','',$name_file);
             require_once $filename;
            $obj_m=new $name_model;
            $name = str_replace('model_','',$name_model);
            if($name!='users' && $name!='html'){
                 require_once $filename;
                 $method = "nameinclude";
                 $method2 = "interfaces";
                            // проверяем есть ли данный метод у этой модели, если есть подключаем
                       if(method_exists($name_model,$method) && method_exists($name_model,$method2)){
                           $arr[$name]=$obj_m->$method();
                       }
            }
    
    }
    
     require_once $GLOBALS["foton_setting"]["path"].'/dev/modul/panelsections/item0.php';
     }
    

public function table_section(){
  
    if(isset($this->request->p['section'])){
        $filename=$GLOBALS["foton_setting"]["path"]."/app/model/".$GLOBALS['foton_setting']['sitedir']."/model_".$this->request->p['section'].".php";
        $arr = array();
        $name_file = "model_".$this->request->p['section'].".php";
        $name_model = str_replace('.php','',$name_file);
        require_once $GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/model_'.$this->request->p['section'].".php";
        $obj_m=new $name_model;
        $name = str_replace('model_','',$name_model);
        if($name!='interfaces'){
             require_once $filename;
             $method = "names";
              $method2 = "nameinclude";
                        // проверяем есть ли данный метод у этой модели, если есть подключаем
                   if(method_exists($name_model,$method)){
                       $arr=$obj_m->$method();
                   }
               if(method_exists($name_model,$method2)){
                       $_SESSION['section_name']=$obj_m->$method2();
                   }
            
        }
        $_SESSION['section']=$this->request->p['section'];
        
        echo serialize($arr);
    }
}


























}