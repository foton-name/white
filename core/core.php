<?php
  namespace Foton;

/* core 12*/

trait Core12
{

}

class Main extends Parents
{
    public static $ifile;
    public $db_query;    
    public static $dbh;
    public $var;
    public $db;
    public $get_post;
    public $xml;
    public $file;
    public $arr_property_file;
    public $inc_file;
    public static $instance;
    public $type_file;
    protected $dbname;
    public $valid;
    public $is_valid;
    public $request;
    public static $get;
    public function __construct()
    {
        if(empty(self::$dbh)){
            self::$dbh = $this->db();
        }
        $this->db = self::$dbh;
        $this->get_post = $this->post_get();
        if (class_exists('XMLWriter')) {
            $this->xml = new \XMLWriter();
        } else {
            $this->xml = false;
        }
        $this->arr_property_file = array('TYPE', 'VALUE', 'NAME', 'ID');
        $this->inc_file = 'ID';
        $this->widget = new Widget;
        $this->mod = new Mod;
        $this->valid = new Validate;
        $this->is_valid = new Is_Validate;
        $this->type_file = 'TYPE';
        $this->arr_type = $GLOBALS["foton_setting"]['orm']['type'];
        $this->request = $this->get();
    }
    
    public function __call($name, $arg){
        $name = preg_replace('#([a-z]+)([A-Z]+)#','$1_$2',$name);
        $name=mb_strtolower($name);
        if(method_exists($this,$name)){
            return $this->$name(...array_values($arg));
        }
        else{
            $this->log('methods '.$name.' undefined in core');
        }
    }

    public function debug_test(){
        if(isset($_SESSION['debug'])){
            $this->arr($_SESSION['debug']);
            $_SESSION['debug']=[];
        }
    }

    public static function st(){
    	if(empty(static::$instance)) {
            static::$instance = new static();
        } 
        return static::$instance;
    }
    public function requiref($class){
        $class = str_replace("\\","/",$class);
        if(strrpos($class,'Controller_') !== false){
            $class = str_replace('Controller_','controller_',$class);
            $admin = $this->git($GLOBALS['foton_setting']['path'].'/app/controller/'.$GLOBALS['foton_setting']['admindir'].'/'.$class.'.php');
            $site = $this->git($GLOBALS['foton_setting']['path'].'/app/controller/'.$GLOBALS['foton_setting']['sitedir'].'/'.$class.'.php');
        }
        else if(strrpos($class,'Model_') !== false){
            $class = str_replace('Model_','model_',$class);
            $admin = $this->git($GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['admindir'].'/'.$class.'.php');
            $site = $this->git($GLOBALS['foton_setting']['path'].'/app/model/'.$GLOBALS['foton_setting']['sitedir'].'/'.$class.'.php');
        }
        else{
            $arr_dir = explode('/', $class);
            if(strpos($class,'/')){
                $dir = $arr_dir[0];
                $modulef = $arr_dir[1];
                $facade = $this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/'.$dir.'/facade.ini');
                if (file_exists($facade)) {
                    $facadearr = parse_ini_file($facade);
                    if (isset($facadearr[$modulef])) {
                        $class = '/'.$dir.'/'.$facadearr[$modulef];
                    }
                }
            } 
            $module = $this->git($GLOBALS['foton_setting']['path'].'/dev/modules'.$class.'.php');
            $modulei = $this->git($GLOBALS['foton_setting']['path'].'/dev/modules'.$class.'.i.php');
        }
        if(isset($modulei) && file_exists($modulei)){
            require_once $modulei;
        }
        else if(isset($module) && file_exists($module)){
            require_once $module;
        }
        else if(isset($admin) && $this->isAuth() && file_exists($admin)){
            require_once $admin;
        }
        else if(isset($site) && file_exists($site)){
            require_once $site;
        } 
        else{
            
        }        
    }    

    public function validate_m($arr=array(),$model=null,$table=null,$format=null){
        if($model!=null && $table!=null && $format!=null){
            $class_m = $this->i($model);
            $obj_m = new $class_m;
            $method_v = $table.'_validate';
            $method_is = $table.'_is';
            if(method_exists($obj_m,$method_v)){
                $arr_v = $obj_m->$method_v();
                if(isset($arr_v['public_'.$format])){
                    if(isset($arr_v['delete']['public_'.$format])){
                        $arr = $this->validate($arr_v['public_'.$format],$arr,['delete'=>true]);  
                    }
                    else{
                        $arr = $this->validate($arr_v['public_'.$format],$arr);
                    }
                }
            }
            if(method_exists($obj_m,$method_is)){
                $arr_is = $obj_m->$method_is();
                if(isset($arr_is['public_'.$format])){
                    $is = $this->isvalid($arr_is['public_'.$format],$arr,true);
                    if(!$is){
                        $this->log('Неверный формат данных');
                        return false;
                    }
                }
            }
            if(isset($arr)){
                return $arr;
            }
            else{
                return false;
            }
            
        }
        else{
            $this->log('validate_m: не переданы аргументы model,table,format');
        }
    }
     public function dbins($table = null, $arr = null,$model=null)
    {
        if($model!=null && $arr!=null){
            $this->validate_m($arr,$model,$table,'ins');
        }          
        if ($table != null && $arr != null) {
            $up = '';
            if($model!=null){
                $class_m = 'Model_'.$model;
                $obj_m = new $class_m;
                $ins_prev = 'before_ins_' . $table;
                $arr2 = $arr;
                if (method_exists($obj_m, $ins_prev)) {
                    $arr = $obj_m->$ins_prev($arr);
                }
            }
            foreach ($arr as $key => $val) {
                $value[] = $key;
                $value_p[] = ':' . $key;
                $prepare[$key] = $val;
            }
            $value = implode(",", $value);
            $value_p = implode(",", $value_p);
            $sql = "INSERT INTO  " . $table . "  (" . $value . ") VALUES (" . $value_p . ")";
            try {
                $res = $this->db->prepare($sql);
                $res->execute($prepare);
                $id = $this->db->lastInsertId();
                if($model!=null){
                    $arr2['id'] = $id;
                    $ins_a = 'after_ins_' . $table;
                    if (method_exists($obj_m, $ins_a)) {
                        $obj_m->$ins_a($arr2);
                    }
                }
                return $id;
            } catch (\PDOException $e) {
                $this->log('dbins error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }

            return $arr;
        } else {
            $this->log('dbins: таблица и массив не могут быть пустыми');
            return false;
        }
    }

    public function dbdel($table = null, $where = 'no')
    {
        if ($table != null && $where != 'no') {
            $where_str='';
            if (is_array($where)) {
                foreach ($where as $key2 => $val2) {
                    if(is_int($key2) && isset($GLOBALS["foton_setting"]['orm']['custom'][$val2])){                    
                        $where_str .= ' ' . $GLOBALS["foton_setting"]['orm']['custom'][$val2] . ' ';
                    }
                    else {
                        $m = $key2[0];
                        if(isset($GLOBALS["foton_setting"]['orm']['where'][$m])){
                            $key2 = substr($key2, 1);
                            $replace = ["[%value%]"=>":where".$key2,"[field]"=>$key2];
                            $where_str0= strtr($GLOBALS["foton_setting"]['orm']['where'][$m], $replace);
                            $replace = ["[%value]"=>":where".$key2,"[field]"=>$key2];
                            $where_str0= strtr($where_str0, $replace);
                            $replace = ["[value%]"=>":where".$key2,"[field]"=>$key2];
                            $where_str0= strtr($where_str0, $replace);
                            $replace = ["[value]"=>":where".$key2,"[field]"=>$key2];
                            $where_str.= strtr($where_str0, $replace);
                        }
                        else {
                            $where_str .= $key2. '=:where' . $key2.' ';
                        }
                        if(isset($GLOBALS["foton_setting"]['orm']['where'][$m])){
                            if(strpos($GLOBALS["foton_setting"]['orm']['where'][$m],"[%value%]")!==false){
                                $prepare['where' . $key2] = '%'.$val2.'%';
                            }
                            else if(strpos($GLOBALS["foton_setting"]['orm']['where'][$m],"[%value]")!==false){
                                $prepare['where' . $key2] = '%'.$val2;
                            }
                            else if(strpos($GLOBALS["foton_setting"]['orm']['where'][$m],"[value%]")!==false){
                                $prepare['where' . $key2] = $val2.'%';
                            }
                            else{
                               $prepare['where' . $key2] = $val2; 
                            }
                        }
                        else{
                            $prepare['where' . $key2] = $val2;
                        }
                    }
                }
            }
            else{
                $prepare['id'] = $where;
            }
            if (isset($where_str)) {
                $sql = "DELETE  FROM " . $table . " WHERE " . $where_str . " ";
            } else {
                $sql = "DELETE  FROM " . $table . "  WHERE id=:id ";
            }
            try {
                $res = $this->db->prepare($sql);
                $res->execute($prepare);
            } catch (\PDOException $e) {
                $this->log('dbdel error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }
            return true;
        } else {
            $this->log('dbdel: таблица и массив не могут быть пустыми');
            return false;
        }
    }
    
    public function dbup($table = null, $arr = null, $where = 'id',$model=null)
    {
        if($model!=null && $arr!=null){
            $this->validate_m($arr,$model,$table,'up');
        }  
        if ($table != null && $arr != null) {
            $up = '';
            $where_str='';
            if($model!=null){
                $class_m = 'Model_'.$model;
                $obj_m = new $class_m;
                $ins_prev = 'before_up_' . $table;
                $arr2 = $arr;
                if (method_exists($obj_m, $ins_prev)) {
                    $arr = $obj_m->$ins_prev($arr);
                }
            }
            if (is_array($where)) {
                foreach ($where as $key2 => $val2) {
                    if(is_int($key2) && isset($GLOBALS["foton_setting"]['orm']['custom'][$val2])){                    
                        $where_str.= ' ' . $GLOBALS["foton_setting"]['orm']['custom'][$val2] . ' ';
                    }
                    else {
                        $m = $key2[0];
                        if(isset($GLOBALS["foton_setting"]['orm']['where'][$m])){
                            $key2 = substr($key2, 1);
                            $replace = ["[%value%]"=>":where".$key2,"[field]"=>$key2];
                            $where_str0= strtr($GLOBALS["foton_setting"]['orm']['where'][$m], $replace);
                            $replace = ["[%value]"=>":where".$key2,"[field]"=>$key2];
                            $where_str0= strtr($where_str0, $replace);
                            $replace = ["[value%]"=>":where".$key2,"[field]"=>$key2];
                            $where_str0= strtr($where_str0, $replace);
                            $replace = ["[value]"=>":where".$key2,"[field]"=>$key2];
                            $where_str.= strtr($where_str0, $replace);
                        }
                        else {
                            $where_str .= $key2. '=:where' . $key2.' ';
                        }
                        if(isset($GLOBALS["foton_setting"]['orm']['where'][$m])){
                            if(strpos($GLOBALS["foton_setting"]['orm']['where'][$m],"[%value%]")!==false){
                                $prepare['where' . $key2] = '%'.$val2.'%';
                            }
                            else if(strpos($GLOBALS["foton_setting"]['orm']['where'][$m],"[%value]")!==false){
                                $prepare['where' . $key2] = '%'.$val2;
                            }
                            else if(strpos($GLOBALS["foton_setting"]['orm']['where'][$m],"[value%]")!==false){
                                $prepare['where' . $key2] = $val2.'%';
                            }
                            else{
                               $prepare['where' . $key2] = $val2; 
                            }
                        }
                        else{
                            $prepare['where' . $key2] = $val2;
                        }
                    }
                }
            }
            else{
                
            }
            foreach ($arr as $key => $val) {
                if (is_array($where)) {
                    $up .= ' '.$key . "=:" . $key . ",";
                    $prepare[$key] = $val;
                } else {
                    if ($key == $where) {
                        $id = 'where' . $key;
                        $prepare['where' . $key] = $val;
                    } else {
                        $up .= ' '.$key . "=:" . $key . ",";
                        $prepare[$key] = $val;
                    }
                }
            }
            $up = substr($up, 0, -1);
            if (isset($where_str)) {
                $sql = "UPDATE " . $table . " SET  " . $up . " WHERE " . $where_str . " ";
            } else {
                $sql = "UPDATE " . $table . " SET  " . $up . " WHERE " . $where . "=:" . $id . " ";
            }
            try {
                $res = $this->db->prepare($sql);
                $res->execute($prepare);
            } catch (\PDOException $e) {
                $this->log('dbup error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }
            if($model!=null){
                $up_a = 'after_up_' . $table;
                if (method_exists($obj_m, $up_a)) {
                    $arr = $obj_m->$up_a($arr2);
                }
            }
            return $arr;
        } else {
            $this->log('dbup: таблица и массив не могут быть пустыми');
            return false;
        }
    }
    
    public function exitAdmin(){    
        unset($_SESSION);
        session_destroy();
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-36000);
                setcookie($name, '', time()-36000, '/');
            }
        }                
        unset($_COOKIE);
        header('Location:'.$GLOBALS['foton_setting']['http'] . '://' . $_SERVER['HTTP_HOST'].'/'.$GLOBALS['foton_setting']['admin'].'/');        
    }

    public function cron($method=null,$data=null,$time=null){
        if($method!=null && $data!=null && $time!=null){
            $arr = explode(':',$time);
            $format = $arr[0];
            $time = $arr[1];
            if(!file_exists($GLOBALS['foton_setting']['path'].'/system/cron')){
                mkdir($GLOBALS['foton_setting']['path'].'/system/cron');
            }
            if(!file_exists($GLOBALS['foton_setting']['path'].'/system/cron/'.$format)){
                mkdir($GLOBALS['foton_setting']['path'].'/system/cron/'.$format);
            }
            if(!file_exists($GLOBALS['foton_setting']['path'].'/system/cron/'.$format.'/'.$time)){
                mkdir($GLOBALS['foton_setting']['path'].'/system/cron/'.$format.'/'.$time);
            }
            $data = serialize($data);
            file_put_contents($GLOBALS['foton_setting']['path'].'/system/cron/'.$format.'/'.$time.'/'.$method.'___'.time().'.txt',$data);
        }
        else{
            $this->log('cron: не переданы аргументы method,data,time');
        }
    }
    
    public function work($obj,$count=2){
        if(file_exists($GLOBALS['foton_setting']['path'].'/system/cron')){
            foreach(glob($GLOBALS['foton_setting']['path'].'/system/cron/*') as $dir){
                if(basename($dir)!='log'){
                    foreach(glob($dir.'/*') as $time){
                        if(intval(date(basename($dir)))%intval(basename($time))==0){
                            $i=0;
                            foreach(glob($time.'/*.txt') as $file){
                                if($i<$count){
                                    $data = unserialize(file_get_contents($file));
                                    $name = explode('___',basename($file))[0];
                                    if(!file_exists($GLOBALS['foton_setting']['path'].'/system/cron/log')){
                                        mkdir($GLOBALS['foton_setting']['path'].'/system/cron/log');
                                    }
                                    try{
                                        $result = $obj->$name(...$data);
                                        file_put_contents($GLOBALS['foton_setting']['path'].'/system/cron/log/success_'.basename($file),json_encode($result,true));
                                    } catch (\Throwable $e) {
                                       file_put_contents($GLOBALS['foton_setting']['path'].'/system/cron/log/error_'.basename($file),json_encode($e->getMessage(),true)); 
                                    }
                                    unlink($file);
                                    $i++;
                                }
                                else{
                                    $i=0;
                                    break 1;
                                }
                            }
                        }
                    }
                }
            } 
        }
    }    
    
    public function csrf($key='pass'){
        if($key!=null){
            $result = $this->user_ip().$_SERVER['HTTP_USER_AGENT'].$_SERVER['REQUEST_URI'].session_id();
            $key = $this->valid->$key($result);
            $encrypt = $this->generate_csrf($key);
            $key_new = pack('H*', $GLOBALS["foton_setting"]['multiplay']);
            $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key_new), -32));
            $passcrypt = json_encode([$encrypt=>$mac],true);
            $encoded = base64_encode($passcrypt);
            return $encoded;
        }
    }
    
    public function validate_csrf($decrypt='pass',$request=null){
        if($decrypt!=null && $request!=null){
            $result = $this->user_ip().$_SERVER['HTTP_USER_AGENT'].$_SERVER['REQUEST_URI'].session_id();
            $decrypt = $this->valid->$decrypt($result);
            $decrypt = $this->generate_csrf($decrypt);
            $decoded = base64_decode($request);
            $decoded = json_decode($decoded,true);
           if(isset($decoded[$decrypt])){
                $decrypted = $decoded[$decrypt];
                $key_new = pack('H*', $GLOBALS["foton_setting"]['multiplay']);
                $calcmac = hash_hmac('sha256', $decrypt, substr(bin2hex($key_new), -32));
                if($calcmac===$decoded[$decrypt]){ return true; }
           }
           return false;
        }
        else{
            $this->log('name_mtf: не передана зашифрованная строка');
        }
    }

    private function generate_csrf($key){
        $encrypt = json_encode([$key,$GLOBALS["foton_setting"]['multiplay']],true);
        $encrypt = serialize($encrypt);
        return $encrypt;
    }
    
    public function user_ip(){
        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];
        foreach ($keys as $key) {
            if (isset($_SERVER[$key])) {
               $ip = $_SERVER[$key];
               if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
               }
            }
        }
    }

    public function json_file($source = null)
    {
        if ($source != null) {
            $result='';
            for($i=0;$i<mb_strlen($source);$i++){
                $tab = substr_count($source, '{', 0, $i);
                $tab_no = substr_count($source, '}', 0, $i);
                $tab = $tab - $tab_no;
                $tabs="\n";
                for($t=0;$t<=$tab;$t++){
                    $tabs.="\t\r";
                }
                $rest = substr($source, $i, 1);
                $rest = str_replace('{',$tabs.'{',$rest);
                $rest = str_replace('}',$tabs.'}',$rest);
                $tab = substr_count($source, '[', 0, $i);
                $tab_no = substr_count($source, ']', 0, $i);
                $tab = $tab - $tab_no;
                for($t=0;$t<=$tab;$t++){
                    $tabs.="\t\r";
                }
                $rest = str_replace('[',$tabs.'[',$rest);
                $rest = str_replace(']',$tabs.']',$rest);
                $result.=$rest;
            }
            return $result;
        }
    }

    public function import_csv($model=null,$table=null,$name=null,$path=null){
        if($model!=null && $table!=null && $name!=null && $path!=null){
            $up = $this->i_upload(1,null, $path,null, ['csv']);
            if(isset($up[$name])){
               $file = $this->git('app/view/'.$GLOBALS['foton_setting']['sitedir'].'/'.$up[$name]);
               $data_file=[];
               $header = true;
                if ($fp = fopen($file, 'r')) {
                    while (!feof($fp)) {
                        $row = fgetcsv($fp, 1000, ";");
                        if($header){
                            $arr_name = $row;
                            $header = false;
                        }
                        else{
                            if(isset($arr_name)){
                                $arr_new=[];
                                if(!is_array($row)){
                                    $row = (array)$row;
                                }
                                if(count($row)>0){
                                    foreach($row as $key=>$val){
                                       $arr_new[$arr_name[$key]]=str_replace('`','"',$val);
                                    }
                                    $data_file[] = $arr_new;
                                }
                            }
                        }
                    }
                    fclose($fp);
                }
                foreach($data_file as $element){
                    if(isset($element['id']) && (int)$element['id']>0){
                        $this->i_update($model,$table,$element,1,1);
                    }
                    else if(isset($element['id']) && $element['id']==0){
                        unset($element['id']);
                        $this->i_insert($model,$table,$element,1,1);
                    }
                    else{
                       $this->log('id в элементе не найден'); 
                    }
                }
                unlink($this->git('app/view/'.$GLOBALS['foton_setting']['sitedir'].'/'.$up[$name]));
            }
        }
        else{
            $this->log('import_csv: необходимо указать model,table,name,path');
        }
    }
    public function export_csv($table=null,$where=null,$path=null){
        if($path!=null){
            if(is_array($where) && count($where)>0){
                $arr=array($table,'where'=>$where);
            }
            else{
                $arr=array($table);
            }
            $getlist=$this->getlist($arr);
            $i=0;
            $csvh=array();
            $csv=array();
            $csvs='';
            foreach($getlist as $key=>$value){
                if($i==0){
                    foreach($value as $key2=>$value2){
                        if(!is_numeric($key2)){
                            $csvh[]=$key2;
                            $value2=str_replace('"',"`",$value2);
                            $value2=str_replace("\r","",$value2);
                            $value2=str_replace("\n","",$value2);
                            $csv[$key][]=$value2;
                        }
                    }
                }
                else{
                    foreach($value as $key2=>$value2){
                        if(!is_numeric($key2)){
                            $value2=str_replace('"',"`",$value2);
                            $value2=str_replace("\r","",$value2);
                            $value2=str_replace("\n","",$value2);
                            $csv[$key][]=$value2;
                        }
                    }
                }
                $i++;  
            }       
            if(count($getlist)>0){
                $csvs.='"'.implode('","',$csvh).'"'."\n\r";
                foreach($getlist as $key=>$value){
                    $value=str_replace('"','`',$value);
                    $value=str_replace("\r","",$value);
                    $value=str_replace("\n","",$value);
                    $csvs.='"'.implode('","',$csv[$key]).'"'."\n\r";
                }
                if(!file_exists($this->git($path))){
                    mkdir($path);
                }
                file_put_contents($path.'/'.$table.'.csv',$csvs);
                $path = str_replace($GLOBALS['foton_setting']['path'],'',$path);
                return $path.'/'.$table.'.csv';
            }
            else{
                return false;
            }  
        }
        else{
            $this->log('export_csv: необходимо указать table,where/null,path');
        }            
    }         
    public function nearest($arr, $arr_search, $count = 2)
    {
        foreach ($arr as $key => $c) {
            $res = 0;
            for ($v = 0; $v < $count; $v++) {
                $res += abs($c[$v] - $arr_search[$v]);
            }
            $arr_new[$res] = $key;
        }
        ksort($arr_new);
        $first = array_shift($arr_new);
        return $first;
    }
    public function redirect_get($select=array(),$stop=10,$increment=1,$no_increment=array()){
        if(isset($this->request->g)){
            $getname = array();
            foreach($this->request->g as $key=>$val){
                if(in_array($key, $select)){
                    if(!in_array($key, $no_increment)){
                        if(is_array($stop) && array_key_exists($key, $stop)){
                            if($val>=$stop[$key]){
                                return true;
                            }
                        }
                        else{
                            if($val>=$stop){
                                return true;
                            }
                        }
                        if(is_array($increment) && array_key_exists($key, $increment)){
                            if(strrpos($increment[$key],'eval:')!==false){
                                $increment[$key] = str_replace('eval:','',$increment[$key]);
                                foreach($this->request->g as $key2=>$val2){
                                    $increment[$key] = str_replace('|'.$key2.'|',$val2,$increment[$key]);
                                }
                                $this->request->g[$key] = (int)$val + (int)$increment[$key];
                            }
                            else{
                               $this->request->g[$key] = (int)$val + (int)$increment[$key]; 
                            }
                            
                        }
                        else if(!is_array($increment)){
                            $this->request->g[$key] = $val + $increment;
                        }
                        else{
                            
                        }
                    }
                    if($key!='' && $this->request->g[$key]!=''){
                        $getname[]=$key.'='.$this->request->g[$key];
                    }
                }
            }
            $get = implode('&',$getname);
            if(count($getname)>0){
                echo "<script>document.location.href='".$GLOBALS['foton_setting']['http']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?".$get."';</script>";
            }
        }
    }
    public function h_site(){
        return $GLOBALS['foton_setting']['http']."://".$_SERVER['SERVER_NAME'].'/';
    }
    public function p_site(){
        return $GLOBALS['foton_setting']['http']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }
    public function mbsplit($val)
    {
        preg_match_all('#.{1}#uis', $val, $result);
        return $result[0];
    }

    public function searchf($name, $list)
    {
        if (array_search($name, $list)) {
            return $name;
        }
        $arr_name = $this->mbsplit($name);
        $c_n = count($arr_name);
        $arr_res = array();
        foreach ($list as $str) {
            foreach ($arr_name as $key1 => $a) {
                $search = $this->mbsplit($str);
                $c_s = count($search);
                if (stristr($str, $a) && ($c_n - $c_s < 2 || $c_s - $c_n < 2)) {
                    if (empty($arr_res[$str])) {
                        $arr_res[$str] = 0;
                    }
                    $arr_res[$str] += 1;
                }
                foreach ($search as $key2 => $abs) {
                    if ($arr_name[$key1] == $search[$key2] && $key1 == $key2 && ($c_n - $c_s < 2 || $c_s - $c_n < 2)) {
                        $arr_res[$str] += 1;
                    }
                }
                $search2 = array_reverse($search);
                $arr_name2 = array_reverse($arr_name);
                foreach ($search2 as $key2 => $abs) {
                    if ($arr_name2[$key1] == $search2[$key2] && $key1 == $key2 && ($c_n - $c_s < 2 || $c_s - $c_n < 2)) {
                        $arr_res[$str] += 2;
                    }
                }
            }
        }
        $arr_new = array_flip($arr_res);
        ksort($arr_new);
        $first = array_pop($arr_new);
        return $first;
    }

    public function gv($arr=null){
        if($arr!=null){
            return md5(json_encode($arr,true).$GLOBALS["foton_setting"]['multiplay']);
        }
        else{
            $this->log('gv: не задан массив полей формы');
        }
    }

    public function pathmod($path){
        return $this->request->sr['DOCUMENT_ROOT'].$this->git('/dev/modul/'.$path);
    }
     public function get()
    {
        if(isset(self::$get)){
            return self::$get;
        }
        else{
            $obj = new \stdClass();
            $obj->r = $_REQUEST;
            $obj->s = $_SESSION;
            $obj->sr = $_SERVER;                    
            $obj->g = $_GET;
            $obj->p = $_POST;
            $obj->h = getallheaders();
            self::$get = $obj;
            return $obj; 
        }
        
    }

    function search($text, $arr)
    {
        if (mb_strlen($text) > 3) {
            foreach ($arr as $key => $val) {
                $arr2[$key] = similar_text($text, $val) - levenshtein($text, $val);
            }
            asort($arr2);
            $last = array_key_last($arr2);
            $arr2[$last] = abs($arr2[$last]);
            if ($arr2[$last] > 3) {
                return $arr[$last];
            } else {
                return false;
            }
        } else {
            return $text;
        }
    }
    public function water_mark($image=null,$mark=null,$w=20,$h=20,$zoom=7){
        if($image==null){
            if(isset($GLOBALS['foton_setting']['image_def'])){
                $image = $GLOBALS['foton_setting']['image_def'];
            }
            else{
                $this->log('Укажите параметры:water_mark(img,mark), либо создайте глобальные переменные image_def,water_mark');
                return false;
            }
        }
        if($mark==null){
            if(isset($GLOBALS['foton_setting']['water_mark'])){
                $mark = $GLOBALS['foton_setting']['water_mark'];
            }
            else{
                $this->log('Укажите параметры:water_mark(img,mark), либо создайте глобальные переменные image_def,water_mark');
                return false;
            }
        }
        header('content-type: image/jpeg'); 
        $watermark = imagecreatefrompng($mark); 
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);  
        $image_path = $image;
        $image = imagecreatefromjpeg($image_path);
        if ($image === false) {
            $image = $image_path; 
            $watermark = imagecreatefrompng($mark);  
            $watermark_width = imagesx($watermark);
            $watermark_height = imagesy($watermark); 
            $image_path = $image;
            $image = imagecreatefrompng($image_path);
            if($image===false && isset($GLOBALS['foton_setting']['image_def']) && isset($GLOBALS['foton_setting']['water_mark'])){
                $image = $GLOBALS['foton_setting']['image_def'];
                $watermark = imagecreatefrompng($GLOBALS['foton_setting']['water_mark']);
                $watermark_width = imagesx($watermark)/$w;
                $watermark_height = imagesy($watermark)/$h;  
                $image_path = $image;
                $image = imagecreatefrompng($image_path);
            }
        }
        $size = getimagesize($image_path);
        $dest_x = $size[0]/$zoom;
        if($size[1]>$watermark_height){
            $dest_y = $size[1]/$zoom;
        }
        else{
            $dest_y = $size[1]/$zoom;
        }
        imagealphablending($image, true);
        imagealphablending($watermark, true);
        imagecopy($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
        imagejpeg($image);
        imagedestroy($image);
        imagedestroy($watermark);
    }
    
    public function getHeaders() {
        $headers = array();
        foreach($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }
    
    public function api_key($key){
        $key = preg_replace_callback('|\[([^\]]+)\]|', function ($matches) {
            return date($matches[1]);
        },$key);
        $key = preg_replace_callback('|\{([^/\}]+)/([^\}]+)\}|', function ($matches) {
            return intdiv(date($matches[1]),$matches[2]);
        },$key);
        $key = password_hash($key, PASSWORD_DEFAULT);
        return $key;
    }
    
    public function rest_api($param=array()){
        $this->get()->g['format']='api_json';
        $arr_ex = ['key','method','page','count','logic'];
        $arr_data = ['model'=>false, 'table'=>false, 'arr'=>array(), 'where'=>false];
        $arr_getlist = ['data','fields','group','sort'];
        if(empty($param['key'])){
            http_response_code(401);
            return 'invalid key';
        }
        if(isset($param['data'])){
            $arrf = json_decode(file_get_contents('php://input'),true);
            if(is_array($arrf) && count($arrf)>0){
                foreach($arrf as $key=>$res){
                    if(in_array($key,$arr_getlist)){
                        $arr[$key] = $res;
                    }
                    else if(in_array($key,$arr_ex)){
                        $arr[$key] = $res;
                    }
                    else{
                        ${$key} = $res;
                    }
                }           
            }
        }
        if(isset($param['header'])){
            $arrf = $this->getHeaders();
            if(is_array($arrf) && count($arrf)>0){
                foreach($arrf as $key=>$res){
                    if(in_array($key,$arr_ex)){
                        $arr[$key] = $res;
                    }
                    else if($key=='Data'){
                        $arr['data'] = json_decode($res,true);
                    }
                    else if($key=='Sort'){
                        $arr['sort'] = json_decode($res,true);
                    }
                    else if($key=='Fields'){
                        $arr['fields'] = json_decode($res,true);
                    }
                    else if($key=='Logic'){
                        $arr['logic'] = $res;
                    }
                    else if($key=='Group'){
                        $arr['group'] = json_decode($res,true);
                    }
                    else{
                        ${mb_strtolower($key)} = json_decode($res,true);
                    }
                }           
            }
        }       
        if(count($this->get()->g)>0){
            foreach($this->get()->g as $keyg=>$resg){
                if(in_array($keyg,$arr_ex)){
                    $arr[$keyg] = $resg;
                }
                else if(in_array($keyg,$arr_getlist)){
                    $arr[$keyg] = json_decode(urldecode($resg),true);
                }
                else{
                    ${$keyg} = json_decode(urldecode($resg),true);
                }
            }           
        }
        if(count($this->get()->p)>0){
            foreach($this->get()->p as $keyg=>$resg){
                if(in_array($keyg,$arr_ex)){
                    $arr[$keyg] = $resg;
                }
                else if(in_array($keyg,$arr_getlist)){
                    $arr[$keyg] = json_decode(urldecode($resg),true);
                }
                else{
                    ${$keyg} = json_decode(urldecode($resg),true);
                }
            }           
        }
        foreach($arr_data as $key=>$val){
            if(empty(${$key})){
                if(!$val){
                    ${$key} = null;
                }
                else{
                    ${$key} = $val;
                }
            }
        }
        if(isset($param['model'])){
            $model = $param['model'];
        }
        if(isset($param['table'])){
            $table = $param['table'];
        }
        if(isset($param['no_method']) && in_array($arr['method'],$param['no_method'])){
            $result = ['error'=>403, 'text' => 'wrong key for this method'];
            return json_encode($result,true);
        }
        if(isset($param['no_table']) && in_array($table,$param['no_table'])){
            $result = ['error'=>403, 'text' => 'wrong key for this table'];
            return json_encode($result,true);
        }
        if(isset($param['secret_key']) && isset($arr['key'])){
            $param['key'] = preg_replace_callback('|\[([^\]]+)\]|', function ($matches) {
                return date($matches[1]);
            },$param['key']);
            $param['key'] = preg_replace_callback('|\{([^/\}]+)/([^\}]+)\}|', function ($matches) {
                return intdiv(date($matches[1]),$matches[2]);
            },$param['key']);
            $arr['hash'] = true;
        }
        if(isset($arr[0]) && isset($where[0])){
            $arr_result=[];
            foreach($arr as $key=>$data){
                if(isset($param['replace']) && isset($data['data'])){
                    $data_new=[];
                    foreach($param['replace'] as $dkey=>$dval){
                        if(isset($data['data'][$dkey])){
                            $data_new[$dval] = $data['data'][$dkey];
                        }
                    }
                    $data['data'] = $data_new;
                }
                $arr_result[]=json_decode($this->api($param['key'], $model, $table, $data, $where[$key]),true);
            }
            return json_encode($arr_result,JSON_UNESCAPED_UNICODE);
        }
        else{
            if(isset($param['replace']) && isset($arr['data'])){
                $data_new=[];
                foreach($param['replace'] as $dkey=>$dval){
                    if(isset($arr['data'][$dkey])){
                        $data_new[$dval] = $arr['data'][$dkey];
                    }
                }
                $arr['data'] = $data_new;
            }
            return $this->api($param['key'], $model, $table, $arr, $where);
        }
    }

    public function api($key="key", $model = null, $table = null, $arr = array(), $where = null)
    {
        $this->get()->g['format']='api_json';
        $arr_getlist = ['count','page','fields','group','logic','sort'];
        if(isset($arr['key']) && isset($arr['hash']) && !password_verify($key, $arr['key'])){
             $result = ['error'=>401, 'text' => 'error key'];
        }
        else if (empty($arr['hash']) && (empty($arr['key']) || $arr['key'] !== $key)) {
            $result = ['error'=>401, 'text' => 'error key'];
        }
        else if(isset($arr['method'])){
            if ($arr['method'] == 'update') {
                if($table==null || empty($arr['data']) || count($arr['data'])==0){
                    $result = ['error'=>415, 'text' => 'error up: no table or arr[data]'];
                }
                else{
                    $result['update'] = $this->dbup($table,$arr['data'],$where,$model);
                    $result['success'] = 201;
                }
            }
            else if ($arr['method'] == 'insert') {
                if($table==null || empty($arr['data']) || count($arr['data'])==0){
                    $result = ['error'=>415, 'text' => 'error i: no table or arr[data]'];
                }
                else{
                    $result['insert'] = $this->dbins($table,$arr['data'],$model);
                    $result['success'] = 201;
                }
            } 
            else if ($arr['method'] == 'delete') {
                if($table==null || $where==null){
                    $result = ['error'=>415, 'text' => 'error del: no table  or where'];
                }
                else{
                    $result['delete'] = $this->dbdel($table, $where);
                    $result['success'] = 201;
                }
            } 
            else if ($arr['method'] == 'echo') {
                if($table==null || $where==null){
                    $result = ['error'=>415, 'text' => 'error echo: no table or where'];
                }
                else{                
                    $arr_get = array($table, 'where' => $where, 'format' => 'J');
                    $result['echo'] = $this->getlist($arr_get);
                }
            } 
            else if ($arr['method'] == 'list') {
                $arr_get=[];
                if($table==null){
                    $result = ['error'=>415, 'text' => 'error list: no table'];
                }
                else{
                    $arr_get[] = $table;
                    $arr_get['format'] = 'J';
                    foreach($arr_getlist as $add_arr){
                        if(isset($arr[$add_arr])){
                            $arr_get[$add_arr] = $arr[$add_arr];
                        }
                    }
                    if($where!=null){
                        $arr_get['where'] = $where;
                    }
                    $result['list'] = $this->getlist($arr_get);
                }
            }
            else{
                $result = ['error'=>400, 'text' => 'undefined method'];
            }
        }
        else{
            $result = ['error'=>400, 'text' => 'absent method'];
        } 
        if(isset($result['error'])){
            http_response_code($result['error']);
        }
        else if(isset($result['success'])){
            http_response_code($result['success']);
        }
        if(isset($arr['logs'])){
            $this->log_file('api',json_encode($result, JSON_UNESCAPED_UNICODE), true);
        }
        return json_encode($result,JSON_UNESCAPED_UNICODE);      
    }
    
    public function isvalid($arr1 = array(), $arr2 = array(), $status = null)
    {
        if ($status === null) {
            foreach ($arr1 as $key => $val) {
                if(is_array($val)){
                    $method=$val[0];
                    $val[0]=$arr2[$key];
                    if (!$this->is_valid->$method(...$val)){
                        $arr_new[$key] = false;
                    }
                }
                else{
                    if (!$this->is_valid->$arr2[$key](...$val)) {
                        $arr_new[$key] = false;
                    }
                }
            }
            return $arr_new;
        } else {
            if(is_array($val)){
                $method=$val[0];
                $val[0]=$arr2[$key];
                if (!$this->is_valid->$method(...$val)){
                    return false;
                }
            }
            else{
                if (!$this->is_valid->$arr2[$key](...$val)) {
                    return false;
                }
            }
            return true;
        }
    }

    public function validate($arr1 = null, $arr2 = array())
    {
        if($arr1!=null){
            if(is_string($arr1)){
                foreach ($arr2 as $key => $val) {
                    $arr2[$key] = $this->valid->$arr1($val);
                }
            }
            else if(is_array($arr1)){
                foreach ($arr1 as $key => $val) {
                    if(is_array($val)){
                        $method=$val[0];
                        $val[0]=$arr2[$key];
                        $arr2[$key] = $this->valid->$method(...$val);
                    }
                    else{
                        $arr2[$key] = $this->valid->$val($arr2[$key]);
                    }
                }
            }
            else{
              return false;  
            }
        }
        else{
            return false;
        }
        return $arr2;
    }

    public function is_valid($val, $flag)
    {
        if ($flag == 'M' && filter_var($val, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else if ($flag == 'D' && filter_var($val, FILTER_VALIDATE_DOMAIN)) {
            return true;
        } else if ($flag == 'F' && filter_var($val, FILTER_VALIDATE_FLOAT)) {
            return true;
        } else if ($flag == 'IP' && filter_var($val, FILTER_VALIDATE_IP)) {
            return true;
        } else if ($flag == 'R' && filter_var($val, FILTER_VALIDATE_REGEXP)) {
            return true;
        } else if ($flag == 'U' && filter_var($val, FILTER_VALIDATE_URL)) {
            return true;
        } else if ($flag == 'I' && filter_var($val, FILTER_VALIDATE_INT)) {
            return true;
        }
        else if ($flag == '0' && $val!='') {
            return true;
        }
        else if ($flag == 'T' && is_string($val)) {
            return true;
        }
         else {
            return false;
        }
    }
    public function icode($code=null,$file=null){
        if($code!=null && $file!=null && isset($GLOBALS['foton_setting']['ifile'])){
            if(file_exists($GLOBALS['foton_setting']['ifile'].'/'.$file.'.txt')){
                $arr = json_decode(file_get_contents($GLOBALS['foton_setting']['ifile'].'/'.$file.'.txt'),true);
                if(in_array($code,$arr['NAME'])){
                    $key = array_search($code,$arr['NAME']);
                    return $arr['VALUE'][$key];
                }
                else{
                    $this->log('icode: элемент с кодом:'.$code.' в справочнике '.$file.' не найден');
                }
            }
            else{
                $this->log('icode: справочник '.$file.' не найден');
            }
        }
        else{
            $this->log('icode: Не указан глобальный параметр ifile или не передан код и название справочника');
        }
    }
    public function ifile_create($file = null)
    {
        if (!file_exists($file) && $file != null) {
            $arr = array();
            foreach ($this->arr_property_file as $property) {
                $arr[$property] = array();
            }
            file_put_contents($file, json_encode($arr));
        }
    }

    public function ifile_start($file = null)
    {
        if ($file != null) {
            self::$ifile = $file;
        }
    }
    public function ifile_update($arr = null)
    {
       
        if (isset(self::$ifile) && self::$ifile != '') {
            if ($arr != null && is_array($arr)) {
                $file = json_decode(file_get_contents(self::$ifile),true);
                    if (isset($file['ID'][$arr[$this->inc_file]])) {
                        foreach ($this->arr_property_file as $property) {
                            if (isset($arr[$property]) && $property!=$this->inc_file) {
                                $arr[$property] = str_replace("'", '"', $arr[$property]);
                                $file[$property][$arr[$this->inc_file]] = $arr[$property];
                            }
                        }
                    } else {
                        return $this->log('ifile_update:Не найден id');
                    }
                file_put_contents(self::$ifile, json_encode($file,true));
                return true;
            }
            else {
                return $this->log('ifile_update:Не передан массив');
            }
        } else {
            return $this->log('Файла обработки не существует либо он не запущен');
        }
    }

    public function ifile_insert($arr = null)
    {
        if (isset(self::$ifile) && self::$ifile != '') {
            if ($arr != null && is_array($arr)) {
                $file = json_decode(file_get_contents(self::$ifile),true);
                foreach ($arr as $field => $values) {
                    foreach ($values as $value) {
                        $value = str_replace("'", '"', $value);                        
                        $file[$field][] = $value;
                    }
                }
                file_put_contents(self::$ifile, json_encode($file,true));
                return count($file[$this->inc_file]) - 1;
            } else {
                return $this->log('ifile_insert:Не передан массив');
            }
        } else {
            return $this->log('Файла обработки не существует либо он не запущен');
        }
    }

    public function ifile_delete($id)
    {
        if (isset(self::$ifile) && self::$ifile != '') {
            $file = json_decode(file_get_contents(self::$ifile),true);
            foreach ($this->arr_property_file as $property) {
                unset($file[$property][$id]);
            }
            file_put_contents(self::$ifile, json_encode($file,true));
            return true;
        } else {
            return $this->log('Файла обработки не существует либо он не запущен');
        }
    }

    public function ifile_drop()
    { 
        if (isset(self::$ifile) && self::$ifile != '') {
            if (unlink($GLOBALS['foton_setting']['path'].'/'.self::$ifile)) {
                return true;
            } else {
                return false;
            }
        } else {
            return $this->log('Файла обработки не существует либо он не запущен');
        }
    }
    public function ifile_arr(){
        if(isset(self::$ifile)){
            $file = json_decode(file_get_contents(self::$ifile),true);
            return $file;
        }
        else{
            return false;
        }
    }
    public function ifile_list($default_name='Значение',$select = array('TYPE','NAME','VALUE'))
    {
        if (in_array($this->type_file,$select)) {
            $select[] = $this->type_file;
        }
        if (isset(self::$ifile) && self::$ifile != '') {
            $file = json_decode(file_get_contents(self::$ifile),true);
            $arr_result_to = array();
            if ($select != null) {
                foreach ($select as $select_name) {
                    $arr_result_to[$select_name] = $file[$select_name];
                }
            } else {
                $arr_result_to = $file;
            }
            $result=array();
            foreach ($arr_result_to[$this->type_file] as $key => $type) {
                $result[$key]=$this->f_echo_type($type, array('fields'=>array('name' => 'VALUE','lang'=>$default_name,'value' => $arr_result_to['VALUE'][$key])));
            }
            return $result;
        } else {
            return $this->log('Файла обработки не существует либо он не запущен');
        }
    }



    public function error($text = null)
    {
        if ($text != null) {
            echo "<p style='color:red;border:2px solid;padding:12px;'>" . $text . "</p>";
        }
    }

    /*$arr = array('fields'=>array('name'=>str,'value'=>str));*/
    public function f_echo_type($type = null, $arr = null)
    {
        if ($type != null && $arr != null) {
             if(isset($GLOBALS["foton_setting"]["type"]) && $GLOBALS["foton_setting"]["type"]=='file'){
                if(strrpos($type,':')){
                    $typearr = explode(':', $type);
                    $type = $typearr[0];
                    $arg = $typearr[1];
                }

                    $tpl = $this->git($GLOBALS["foton_setting"]["path"].'/app/ajax/'.$GLOBALS["foton_setting"]["admindir"].'/type/'.$type.'.tpl');
                    $path = $this->git($GLOBALS["foton_setting"]["path"].'/app/ajax/'.$GLOBALS["foton_setting"]["admindir"].'/type/'.$type.'.php');
                    if(file_exists($tpl)){
                        $htmlred = file_get_contents($tpl);
                        $htmlred = html_entity_decode($htmlred);
                        $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                        foreach ($arr['fields'] as $key_f => $val_f) {
                            $val_f = str_replace("&lt;/textarea&gt;", "&lt;\/textarea&gt;", $val_f);
                            $val_f = str_replace("</textarea>", "<\/textarea>", $val_f);
                            if ($key_f != 'value') {
                                $htmlred = str_replace('[[' . $key_f . ']]', $val_f, $htmlred);
                            } 
                            else {                                
                                $htmlred = str_replace('[[' . $key_f . ']]', '{{' . $key_f . '}}', $htmlred);
                                $value_var = $val_f;
                            }
                        }
                        $htmlred = preg_replace('#\[\[([^\]]+)\]\]#', '', $htmlred);
                        $htmlred = str_replace('{{value}}', $value_var, $htmlred); 
                        $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                        if(file_exists($path)){
                            require_once $path;
                            $typename = 'Type_'.$type;
                            $obj = new $typename;
                            if(empty($arg)){
                                ${$type} = $obj->index();
                            }
                            else{
                                $arg=explode(',',$arg);
                                ${$type} = $obj->index(...array_values($arg));
                            }
                            return eval($htmlred);
                        }
                        return $htmlred;                      
                    }
                    else{
                        $this->error('Тип данных ' . $type . ' не существует');
                        exit();
                    }
                }
                else{
                    $f = $this->select_db('html', 'kod', $type, 'function');
                    $f = implode("", $f);
                    if ($f == '0') {
                        $htmlred = $this->select_db('html', 'kod', $type, 'html');
                        $htmlred = implode("", $htmlred);
                        $htmlred = str_replace('&#039;', '"', $htmlred);
                        $htmlred = html_entity_decode($htmlred);
                        foreach ($arr['fields'] as $key_f => $val_f) {
                            if ($key_f != 'value') {
                                $htmlred = str_replace('[[' . $key_f . ']]', $val_f, $htmlred);
                            } else {
                                $val_f = str_replace("&lt;/textarea&gt;", "&lt;\/textarea&gt;", $val_f);
                                $val_f = str_replace("</textarea>", "<\/textarea>", $val_f);
                                $htmlred = str_replace('[[' . $key_f . ']]', '{{' . $key_f . '}}', $htmlred);
                                $value_var = $val_f;
                            }
                        }
                        $htmlred = preg_replace('#\[\[([^\]]+)\]\]#', '', $htmlred);
                        $htmlred = str_replace('{{value}}', $value_var, $htmlred);
                        $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                        return $htmlred;
                    } else {
                        $pos = strripos($type, ':');
                        if ($pos === false) {
                            $htmlred = $this->select_db('html', 'kod', $type, 'function');
                            $htmlred = implode("", $htmlred);
                            $htmlred = html_entity_decode($htmlred);
                            $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                            foreach ($arr['fields'] as $key_f => $val_f) {
                                if ($key_f != 'value') {
                                    $htmlred = str_replace('[[' . $key_f . ']]', $val_f, $htmlred);

                                } else {
                                    $val_f = str_replace("&lt;/textarea&gt;", "&lt;\/textarea&gt;", $val_f);
                                    $val_f = str_replace("</textarea>", "<\/textarea>", $val_f);
                                    $htmlred = str_replace('[[' . $key_f . ']]', '{{' . $key_f . '}}', $htmlred);
                                    $value_var = $val_f;
                                }
                            }
                            $htmlred = preg_replace('#\[\[([^\]]+)\]\]#', '', $htmlred);
                            $htmlred = str_replace('{{value}}', $value_var, $htmlred);
                        } else {
                            $type = explode(':', $type);
                            $func = $type[0];
                            $arg = $type[1];
                            $htmlred = $this->select_db('html', 'kod', $func, 'function');
                            $htmlred = implode("", $htmlred);
                            $htmlred = html_entity_decode($htmlred);
                            $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                            foreach ($arr['fields'] as $key_f => $val_f) {
                                if ($key_f != 'value') {
                                    $htmlred = str_replace('[[' . $key_f . ']]', $val_f, $htmlred);
                                } else {
                                    $val_f = str_replace("&lt;/textarea&gt;", "&lt;\/textarea&gt;", $val_f);
                                    $val_f = str_replace("</textarea>", "<\/textarea>", $val_f);
                                    $htmlred = str_replace('[[' . $key_f . ']]', '{{' . $key_f . '}}', $htmlred);
                                    $value_var = $val_f;
                                }
                            }
                            $htmlred = preg_replace('#\[\[([^\]]+)\]\]#', '', $htmlred);
                            $htmlred = str_replace('{{value}}', $value_var, $htmlred);
                            $argn = $this->select_db('html', 'kod', $func, 'argument');
                            if (empty($argn[0])) {
                                $this->error('Тип данных ' . $func . ' не существует');
                                exit();
                            }
                            $argn = $argn[0];
                            if (strpos($arg, ',') === false) {
                                ${$argn} = $arg;
                            } else {
                                $argn_arr = explode(',', $argn);
                                $arg_arr = explode(',', $arg);
                                foreach ($argn_arr as $k_arg => $v_arg) {
                                    ${$v_arg} = $arg_arr[$k_arg];
                                }
                            }
                        }
                        $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                        return eval($htmlred);
                    }
                }
        } else {
            $this->log('f_echo_type: не задан тип или массив параметров');
            return false;
        }
    }


    public function to_obj($arr)
    {
        $obj = new \stdClass();
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $val = $this->to_obj($val);
            }
            $obj->$key = $val;
        }
        return $obj;
    }

    public function dump_obj($arr, $obj = 'O')
    {
        if ($obj == 'AO') {
            return $this->to_obj($this->mask_obj($arr));
        } else if ($obj == 'A') {
            return $this->mask_obj($arr);
        } else {
            return $this->mask_to_obj($arr);
        }
    }

    public function mask_obj($arr, &$arr_r = array())
    {

        foreach ($arr as $key => $val) {
            if (strripos($key, '::') !== false) {
                $key = explode('::', $key);
                $class = $key[0];
                $pf_arr = explode('#', $key[1]);
                if (count($pf_arr) > 1) {
                    $method = $pf_arr[0];
                    $pf = '_' . $pf_arr[1];
                } else {
                    $method = $key[1];
                    $pf = '';
                }
                if ($class == '') {
                    $obj = $this;
                } else {
                    $obj = new $class;

                }
            } else {
                $obj = null;
                $pf_arr = explode('#', $key);
                if (count($pf_arr) > 1) {
                    $method = $pf_arr[0];
                    $pf = '_' . $pf_arr[1];
                } else {
                    $method = $key;
                    $pf = '';
                }

            }
            foreach ($val['v'] as $k => $v) {
                if (isset($arr_r[$k])) {
                    $val['v'][$k] = $arr_r[$k];
                }
            }
            if ($obj == null) {
                $arr_r[$method . $pf] = call_user_func_array($method, $val['v']);
            } else {
                $arr_r[$method . $pf] = call_user_func_array(array($obj, $method), $val['v']);
            }
            if (isset($val['m'])) {
                $this->mask_obj($val['m'], $arr_r);
            }
        }
        return $arr_r;

    }

    public function mask_to_obj($arr, &$obj_r = null, &$arr_r = array())
    {

        if (!is_object($obj_r)) {
            $obj_r = new \stdClass();
        }
        foreach ($arr as $key => $val) {

            if (strripos($key, '::') !== false) {
                $key = explode('::', $key);
                $class = $key[0];
                $pf_arr = explode('#', $key[1]);
                if (count($pf_arr) > 1) {
                    $method = $pf_arr[0];
                    $pf = '_' . $pf_arr[1];
                } else {
                    $method = $key[1];
                    $pf = '';
                }
                if ($class == '') {
                    $obj = $this;
                } else {
                    $obj = new $class;

                }
            } else {
                $pf_arr = explode('#', $key);
                if (count($pf_arr) > 1) {
                    $method = $pf_arr[0];
                    $pf = '_' . $pf_arr[1];
                } else {
                    $method = $key;
                    $pf = '';
                }
                $obj = null;
            }
            foreach ($val['v'] as $k => $v) {
                if (isset($arr_r[$k])) {
                    $val['v'][$k] = $arr_r[$k];
                }
            }
            $odj_method = $method . $pf;
            if ($obj == null) {
                $obj_r->$odj_method = call_user_func_array($method, $val['v']);
                $arr_r[$method . $pf] = call_user_func_array($method, $val['v']);
            } else {
                $obj_r->$odj_method = call_user_func_array(array($obj, $method), $val['v']);
                $arr_r[$method . $pf] = call_user_func_array(array($obj, $method), $val['v']);

            }
            if (isset($val['m'])) {
                $this->mask_to_obj($val['m'], $obj_r->$odj_method, $arr_r);
            }
        }
        return $obj_r;

    }




    public function var_start($var = null)
    {
        if ($var != null) {
            $this->var = $var;
            return $this;
        } else {
            return false;
        }
    }

    public function var_inarr($arr = null)
    {
        if ($arr != null && is_array($arr)) {
            if (in_array($this->var, $arr)) {

                return true;
            } else {
                return false;
            }
        } else {
            return $this->log('var_inarr:  не передан массив');
        }
    }

    public function var_inarr_to($str = null)
    {
        if ($str != null && is_array($this->var)) {
            if (in_array($str, $this->var)) {

                return true;
            } else {
                return false;
            }
        } else {
            return $this->log('var_inarr_to:  не передана строка или элемент не является массивом');
        }
    }

    public function var_im($d = '')
    {
        if (is_array($this->var)) {
            $this->var = implode($d, $this->var);
        } else {
            return $this->log('var_im: переменная var не является массивом');
        }
        return $this;
    }

    public function var_ex($d = null)
    {
        if (is_array($this->var)) {
            return $this->log('var_ex: переменная var не является строкой');
        } else {
            if ($d == null) {
                $this->var = explode(' ', $this->var);
            } else {
                $this->var = explode($d, $this->var);
            }
        }
        return $this;
    }

    public function var_method($method = null)
    {
        if ($method != null) {
            $thix->var = $this->$method($this->var);
            return $this;
        } else {

            return $this->log('var_method: не передан метод');
        }
    }

    public function var_replace($pattern, $replace)
    {
        $this->var = str_replace($pattern, $replace, $this->var);
        return $this;
    }

    public function var_preplace($pattern, $replace)
    {
        $this->var = preg_replace($pattern, $replace, $this->var);
        return $this;
    }

    public function var_search($search)
    {
        $this->var = strripos($this->var, $search);
        return $this;
    }

    public function var_search_to($search)
    {
        $this->var = strripos($search, $this->var);
        return $this;
    }

    public function var_input($path)
    {
        if (is_array($this->var)) {
            $str = json_encode($this->var);
        } else {
            $str = $this->var;
        }
        file_put_contents($path, $str);
        return $this;
    }

    public function var_output($path)
    {
        $this->var = file_get_contents($path);
        return $this;
    }

    public function var_end()
    {
        $var = $this->var;
        $this->var = '';
        return $var;
    }

    public function var_count()
    {
        if (is_array($this->var)) {
            return count($this->var);
        } else {
            return mb_strlen($this->var, 'UTF-8');
        }
    }

    public function ev($var = null)
    {
        if ($var != null) {
            $var = str_replace('"','',$var);
            return eval('echo "' . $var . '";');
        } else {
            return false;
        }

    }

    //название страницы, дирекотрия где лежит модель для получения объекта (1, не указана или название директории)
    public function tpl_html($tpl = null, $dir = null)
    {
        if ($tpl != null) {
            try {               
                if ($dir == null) {                
                    require_once('app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $tpl . '.php');
                    $class = '\model_' . $tpl;
                    $obj = new $class;
                    return $obj;
                } else if ($dir == 1) {
                    require_once('app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $tpl . '.php');
                    $class = '\model_' . $tpl;
                    $obj = new $class;
                    return $obj;
                } else {
                    require_once('app/model/' . $dir . '/model_' . $tpl . '.php');
                    $class = '\model_' . $tpl;
                    $obj = new $class;
                    return $obj;
                }
            } catch (\Throwable $e) {
                $this->log('Ошибка модели'.$tpl.': '.$e->getMessage());
            }
        } else {
            $this->log('tpl_html: значение первого аргумента не может быть пустым');
        }

    }


    //список методов класса
    public function arr_methods($tpl = null, $dir = null, $type = null)
    {
        if ($tpl != null) {
            try{                
                if ($dir == null) {
                    if ($type == 1) {
                        require_once('app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $tpl . '.php');
                        $class = '\model_' . $tpl;
                    } else if ($type == 2) {
                        require_once('app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $tpl . '.php');
                        require_once('app/controller/' . $GLOBALS['foton_setting']['sitedir'] . '/controller_' . $tpl . '.php');
                        $class = '\controller_' . $tpl;
                    } else {
                        $class = $tpl;
                    }
                    $obj = new $class;

                } else if ($dir == 1) {
                    if ($type == 1) {
                        require_once('app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $tpl . '.php');
                        $class = '\model_' . $tpl;
                    } else if ($type == 2) {
                        require_once('app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $tpl . '.php');
                        require_once('app/controller/' . $GLOBALS['foton_setting']['admindir'] . '/controller_' . $tpl . '.php');
                        $class = '\controller_' . $tpl;
                    } else {
                        $class = $tpl;
                    }

                    $obj = new $class;
                } else {
                    if ($type == 1) {
                        require_once('app/model/' . $dir . '/model_' . $tpl . '.php');
                        $class = '\model_' . $tpl;
                    } else if ($type == 2) {
                        require_once('app/model/' . $dir . '/model_' . $tpl . '.php');
                        require_once('app/controller/' . $dir . '/controller_' . $tpl . '.php');
                        $class = '\controller_' . $tpl;
                    } else {
                        $class = $tpl;
                    }
                    if (!class_exists($class)) {
                        return false;
                    }
                    
                }
            } catch (\Throwable $e) {
                $this->log('Ошибка шаблона '.$tpl.': '.$e->getMessage());
            }
            if(isset($obj)){
                $class_methods = get_class_methods($obj);
                $arr_result = array();
                foreach ($class_methods as $method_name) {
                    $arr_result[] = $method_name;
                }
                return $arr_result;
            }
            else{
                return array();            
            }
        } else {
            $this->log('arr_methods: значение первого аргумента не может быть пустым');
        }
    }

    public function include_tpl($path = null, $dir = null)
    {
        if ($path != null && $dir == null) {
            $arr = explode('/', $path);
            $path = $this->git($GLOBALS['foton_setting']['path'] . '/app/ajax/' . $GLOBALS['foton_setting']['admindir'] . '/php/' . $arr[0] . '/' . $arr[1] . '.php');

            if (file_exists($path)) {
                try{
                    return $path;                
                } catch (\Throwable $e) {
                    $this->log('Ошибка в файле '.$path.': '.$e->getMessage());
                    return false;
                }
            } else {
                $this->log('include_tpl: интерфейс не найден');
                return false;
            }
        } else {
            $arr = explode('/', $path);
            $path = $this->git($GLOBALS['foton_setting']['path'] . '/app/ajax/' . $GLOBALS['foton_setting']['sitedir'] . '/php/' . $arr[0] . '/' . $arr[1] . '.php');

            if (file_exists($path)) {
                try{
                    return $path;                
                } catch (\Throwable $e) {
                    $this->log('Ошибка в файле '.$path.': '.$e->getMessage());
                    return false;
                }
            } else {
                $this->log('include_tpl: интерфейс не найден');
                return false;
            }
        }
    }


    public function globfunc($mvc = null, $dir = 'cs')
    {
        if ($mvc != null) {
            try{
                if ($dir == 'cs') {
                    require_once $this->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $GLOBALS['foton_setting']['sitedir'] . "/model_" . $mvc . ".php");
                    require_once $this->git($GLOBALS["foton_setting"]["path"] . "/app/controller/" . $GLOBALS['foton_setting']['sitedir'] . "/controller_" . $mvc . ".php");
                    $mvc_name = '\controller_' . $mvc;
                }
                if ($dir == 'ms') {
                    require_once $this->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $GLOBALS['foton_setting']['sitedir'] . "/model_" . $mvc . ".php");
                    $mvc_name = '\model_' . $mvc;
                } else if ($dir == 'ma') {
                    require_once $this->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $GLOBALS['foton_setting']['admindir'] . "/model_" . $mvc . ".php");
                    $mvc_name = '\model_' . $mvc;
                } else if ($dir == 'ca') {
                    require_once $this->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $GLOBALS['foton_setting']['admindir'] . "/model_" . $mvc . ".php");
                    require_once $this->git($GLOBALS["foton_setting"]["path"] . "/app/controller/" . $GLOBALS['foton_setting']['admindir'] . "/controller_" . $mvc . ".php");
                    $mvc_name = '\controller_' . $mvc;
                }

                ${$mvc} = new $mvc_name;
                return ${$mvc};
            } catch (\Throwable $e) {
                $this->log('Ошибка в шаблоне '.$mvc.': '.$e->getMessage());
                return false;
            }
        } else {
            $this->log('globfunc:Не указано название шаблона');
        }
    }


    public function i_unlink($del = null, $arr = null)
    {
        if ($del != null) {
            $path = $this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir']) . '/';

            if ($arr != null) {
                foreach ($arr as $key => $v) {
                    if (stristr($arr[$key], '%%%')) {
                        $arr_f = explode('%%%', $arr[$key]);
                        foreach ($arr_f as $k => $f) {
                            if (file_exists($path . $f) && $arr[$key] != '' && !is_dir($path . $f)) {
                                unlink($path . $f);
                            }
                        }
                    } else {
                        if (file_exists($path . $arr[$key]) && $arr[$key] != '' && !is_dir($path . $arr[$key])) {
                            unlink($path . $arr[$key]);
                        }
                    }
                }
            } else {
                foreach ($_POST as $key => $v) {
                    if (stristr($this->get()->p[$key], '%%%')) {
                        $arr_f = explode('%%%', $this->get()->p[$key]);
                        foreach ($arr_f as $k => $f) {
                            if (file_exists($path . $f) && $this->get()->p[$key] != '' && !is_dir($path . $f)) {
                                unlink($path . $f);
                            }
                        }
                    } else {
                        if (file_exists($path . $this->get()->p[$key]) && $this->get()->p[$key] != '' && !is_dir($path . $this->get()->p[$key])) {
                            unlink($path . $this->get()->p[$key]);
                        }
                    }
                }
            }
        } else {
            $this->log('i_unlink: значение первого аргумента не может быть пустым');
        }

    }

    public function i_upload($up = null, $arr = null, $path_dir = null, $size = null, $format = null)
    {
        if ($up != null) {
            if ($_FILES) {
                if ($size == null) {
                    $size = 1024 * $GLOBALS['foton_setting']['size_file'] * 1024;
                }
                if ($path_dir == null) {
                    $path = $this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir']) . '/img/';
                    $path_dir = 'img';
                } else {
                    if (!file_exists($this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $path_dir))) {
                        mkdir($this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $path_dir));
                    }
                    $path = $this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $path_dir) . '/';
                }
                $path_del = $this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir']) . '/';

                foreach ($_FILES as $key => $name) {


                    if (isset($_FILES[$key]["tmp_name"]) && is_array($_FILES[$key]["tmp_name"])) {
                        $file_arr = '';
                        foreach ($_FILES[$key]["tmp_name"] as $key_f => $name_f) {
                            $file_name = $_FILES[$key]["name"][$key_f];
                            $file_tmp = $_FILES[$key]["tmp_name"][$key_f];
                            $f_info = pathinfo($file_name, PATHINFO_EXTENSION);
                            $size_f = $_FILES[$key]["size"][$key_f];

                            if ($size_f <= $size) {
                                if ($format == null) {
                                    $format = $GLOBALS['foton_setting']['format'];
                                }
                                if (in_array($f_info, $format)) {

                                    if (is_uploaded_file($file_tmp)) {
                                        if ($arr != null && isset($arr[$key])) {
                                            $arr_p = explode('%%%', $arr[$key]);
                                            foreach ($arr_p as $k => $file_p) {
                                                if (file_exists($path_del . $file_p) && $file_p != '' && !is_dir($path . $file_p)) {
                                                    unlink($path_del . $file_p);
                                                }
                                            }
                                        } else {
                                            if(isset($this->get()->p[$key])) {
                                                $arr_p = explode('%%%', $this->get()->p[$key]);
                                                foreach ($arr_p as $k => $file_p) {
                                                    if (file_exists($path_del . $file_p) && $file_p != '' && !is_dir($path . $file_p)) {
                                                        unlink($path_del . $file_p);
                                                    }
                                                }
                                            }
                                        }

                                        $real_name = rand(200, 5000) . $this->translit_base($file_name);
                                        json_encode($path . $real_name);
                                        move_uploaded_file($file_tmp, $path . $real_name);

                                        if ($arr != null) {
                                            $file_arr .= '%%%' . $path_dir . '/' . $real_name;
                                        } else {

                                            $file_arr .= '%%%' . $path_dir . '/' . $real_name;
                                        }


                                    } else {
                                        if ($_FILES[$key]['error'] == 1) {
                                            $this->log('Ошибка загрузки: Размер принятого файла превысил максимально допустимый размер php.ini');
                                        } else if ($_FILES[$key]['error'] == 4) {
                                            $this->log('Ошибка загрузки: Файл не был загружен');
                                        } else if ($_FILES[$key]['error'] == 6) {
                                            $this->log('Ошибка загрузки: Отсутствует временная папка');
                                        } else {
                                            $this->log('Ошибка загрузки:' . $_FILES[$key]['error']);
                                        }
                                    }
                                } else {
                                    $this->log('Ошибка загрузки: Формат файла запрещен');
                                }
                            } else {
                                $this->log('Ошибка загрузки: Размер файла превышен');
                            }

                        }
                        if (isset($file_arr) && $file_arr != '') {
                            if ($arr != null) {
                                $arr[$key] = $file_arr;
                            } else {

                                $this->get()->p[$key] = $file_arr;
                            }

                        }
                    } else {
                        if ($_FILES[$key]["size"] <= $size) {
                            $f_info = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
                            if ($format == null) {
                                $format = $GLOBALS['foton_setting']['format'];
                            }
                            if (in_array($f_info, $format)) {

                                if (is_uploaded_file($_FILES[$key]["tmp_name"])) {
                                    if ($arr != null) {

                                        if (isset($arr[$key]) && file_exists($path_del . $arr[$key]) && $arr[$key] != '' && !is_dir($path . $arr[$key])) {
                                            unlink($path_del . $arr[$key]);
                                        }
                                    } else {

                                        if (isset($this->get()->p[$key]) && file_exists($path_del . $this->get()->p[$key]) && $this->get()->p[$key] != '' && !is_dir($path . $this->get()->p[$key])) {
                                            unlink($path_del . $this->get()->p[$key]);
                                        }
                                    }
                                    $real_name = rand(200, 5000) . $this->translit_base($_FILES[$key]["name"]);
                                    move_uploaded_file($_FILES[$key]["tmp_name"], $path . $real_name);

                                    if ($arr != null) {
                                        $arr[$key] = $path_dir . '/' . $real_name;
                                    } else {

                                        $this->get()->p[$key] = $path_dir . '/' . $real_name;
                                    }

                                } else {
                                    if ($_FILES[$key]['error'] == 1) {
                                        $this->log('Ошибка загрузки: Размер принятого файла превысил максимально допустимый размер php.ini');
                                    } else if ($_FILES[$key]['error'] == 4) {
                                        $this->log('Ошибка загрузки: Файл не был загружен');
                                    } else if ($_FILES[$key]['error'] == 6) {
                                        $this->log('Ошибка загрузки: Отсутствует временная папка');
                                    } else {
                                        $this->log('Ошибка загрузки:' . $_FILES[$key]['error']);
                                    }
                                }
                            } else {
                                $this->log('Формат файла запрещен');
                            }
                        } else {
                            $this->log('Файл слишком большой');
                        }
                    }
                }

            } else {

            }
            if ($arr != null) {
                return $arr;
            } else {
                return $_POST;
            }
        } else {
            $this->log('i_upload: значение первого аргумента не может быть пустым');
        }
    }

    public function dir_search_foton($path = null, $search = null, &$files = null)
    {

        if (is_dir($path)) {
            $cleanPath = array_diff(scandir($path), array('.', '..'));
            foreach ($cleanPath as $file) {
                $finalPath = $path . '/' . $file;
                $result = $this->dir_search_foton($finalPath, $search, $files);
                if (!is_null($result)) $files[] = $result;
            }
        } else if (is_file($path) && strstr(file_get_contents($path), $search) != false) {
            return $path;
        }
    }

    public function translite_m($text = null)
    {
        if ($text != null) {
            $converter = array(
                'а' => 'a', 'б' => 'b', 'в' => 'v',
                'г' => 'g', 'д' => 'd', 'е' => 'e',
                'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
                'и' => 'i', 'й' => 'y', 'к' => 'k',
                'л' => 'l', 'м' => 'm', 'н' => 'n',
                'о' => 'o', 'п' => 'p', 'р' => 'r',
                'с' => 's', 'т' => 't', 'у' => 'u',
                'ф' => 'f', 'х' => 'h', 'ц' => 'c',
                'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
                'ь' => '', 'ы' => 'y', 'ъ' => '',
                'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

                'А' => 'A', 'Б' => 'B', 'В' => 'V',
                'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
                'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
                'И' => 'I', 'Й' => 'Y', 'К' => 'K',
                'Л' => 'L', 'М' => 'M', 'Н' => 'N',
                'О' => 'O', 'П' => 'P', 'Р' => 'R',
                'С' => 'S', 'Т' => 'T', 'У' => 'U',
                'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
                'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
                'ь' => '', 'Ы' => 'Y', 'Ъ' => '',
                'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya', ' ' => '', '-' => '', '"' => '', ' ' => ''
            );
            return strtr($text, $converter);
        } else {
            $this->log('translite_m: значение первого аргумента не может быть пустым');
        }
    }

    public function up_custom_tpl($path){
        if(!file_exists($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $path)){
            @mkdir($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $path,0755,true);
        }
        if(stripos($path,'/')){
            $arr_dir = explode('/',$path);
            foreach($arr_dir as $path_dir){
                if(empty($dir_f)){
                    $dir_f = $path_dir;
                }
                else{
                    $dir_f.='/'.$path_dir; 
                }
                foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $dir_f . '/*.tpl') as $filename) {
                    $view = file_get_contents($filename);
                    $name_f = basename($filename);
                    $name_f = str_replace('.tpl', '.php', $name_f);
                    if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $dir_f . '/' . $name_f)) {
                        unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $dir_f . '/' . $name_f);
                    }
                    $view = $this->cache_foton($view);
                    file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $dir_f . '/' . $name_f, $view);
                }                
            }
        }
        else{
            foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $path . '/*.tpl') as $filename) {
                $view = file_get_contents($filename);
                $name_f = basename($filename);
                $name_f = str_replace('.tpl', '.php', $name_f);
                if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $path . '/' . $name_f)) {
                    unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $path . '/' . $name_f);
                }
                $view = $this->cache_foton($view);
                file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $path . '/' . $name_f, $view);
            }
        }
    }

    public function up_tpl()
    {
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/*.tpl') as $filename) {
            $view = file_get_contents($filename);
            $name_f = basename($filename);
            $name_f = str_replace('.tpl', '.php', $name_f);
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f)) {
                unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f);
            }
            $view = $this->cache_foton($view);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f, $view);
        }
        if(isset($GLOBALS["foton_setting"]["custom_dir"])){
            foreach($GLOBALS["foton_setting"]["custom_dir"] as $dir){                
                $this->up_custom_tpl($dir);
            }
        }
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/*.tpl') as $filename) {
            $view = file_get_contents($filename);
            $name_f = basename($filename);
            $name_f = str_replace('.tpl', '.php', $name_f);
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/' . $name_f)) {
                unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['admindir'] . '/' . $name_f);
            }
            $view = $this->a_cache_foton($view);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS['foton_setting']['admindir'] . '/' . $name_f, $view);
        }
        foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/xml/' . $GLOBALS['foton_setting']['sitedir'] . '/*.tpl') as $filename) {
            $view = file_get_contents($filename);
            $name_f = basename($filename);
            $name_f = str_replace('.tpl', '.php', $name_f);
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/xml/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f)) {
                unlink($GLOBALS["foton_setting"]["path"] . '/app/view/xml/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f);
            }
            $view = $this->cache_foton($view);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/xml/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f, $view);
        }

        foreach (glob($GLOBALS["foton_setting"]["path"] . '/app/view/json/' . $GLOBALS['foton_setting']['sitedir'] . '/*.tpl') as $filename) {
            $view = file_get_contents($filename);
            $name_f = basename($filename);
            $name_f = str_replace('.tpl', '.php', $name_f);
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/json/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f)) {
                unlink($GLOBALS["foton_setting"]["path"] . '/app/view/json/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f);
            }
            $view = $this->cache_foton($view);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/json/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $name_f, $view);
        }
        if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_view.tpl')) {
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_view.php')) {
                unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_view.php');
            }
            $view = file_get_contents($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_view.tpl');
            $view = $this->cache_foton($view);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_view.php', $view);
        }
        if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_mob_view.tpl')) {
            if (file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_mob_view.php')) {
                unlink($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_mob_view.php');
            }
            $view = file_get_contents($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_mob_view.tpl');
            $view = $this->cache_foton($view);
            file_put_contents($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $GLOBALS["foton_setting"]["main"] . '_mob_view.php', $view);
        }
    }

    public function up_core($yes = null)
    {
        if ($yes != null) {
            $arr = $this->up_core_conn();
            $arr2 = array('m' => 'model.php', 'c' => 'core.php', 'p' => 'lib/preload.php', 'v' => 'view.php', 'a' => 'lib/adapter.php', 'l' => 'lib/lib.php', 'r' => 'lib/run.php');
            foreach ($arr as $key => $val) {
                if ($key == 'core') {
                    if($this->update_core($val)!='no_license' && $this->update_core($val)!=''){
                        $file = file_get_contents($GLOBALS['foton_setting']['path'] . '/core/config.php');
                        $file = preg_replace("#\[\"coref\"\]='([^']+)';#", "[\"coref\"]='" . $this->update_core($val) . "';", $file);
                        echo $this->update_core($val);
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/core/config.php', $file);
                    }
                } else {
                    if($this->update_core($val)!='no_license' && $this->update_core($val)!=''){
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/core/' . $arr2[$key], $this->update_core($val));
                    }
                }
            }
        } else {
            $this->log('up_core: значение первого аргумента не может быть пустым');
        }
    }

    public function del_file($table = null, $id = null)
    {
        $i = 0;
        if ($table != null && $id != null) {
            try {
                $resultup = $this->db->query("SELECT * FROM `" . $table . "` WHERE `id`=" . $id);
            } catch (\PDOException $e) {
                $this->log('del_file error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }
            if (isset($resultup)) {
                foreach ($resultup as $row) {
                    foreach ($row as $k => $v) {
                        $pos = strripos($row[$k], '%%%');
                        if ($pos === false) {
                            if (file_exists($this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $row[$k]))) {
                                unlink($this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $row[$k]));
                                $i++;
                            }
                        } else {
                            $arr_del = explode('%%%', $row[$k]);
                            foreach ($arr_del as $file_del) {

                                if (file_exists($this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $file_del))) {
                                    unlink($this->git($GLOBALS['foton_setting']['path'] . '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $file_del));
                                    $i++;
                                }
                            }
                        }

                    }
                }
                return $i;
            }
        } else {
            $this->log('del_file: таблица(1) и id записи(2) не заполнены');
        }
    }

    public function post_arr()
    {
        if (isset($_POST)) {
            $post = array();
            foreach ($_POST as $key => $val) {
                $post[$key] = $val;
            }
            return $post;
        }
    }

    public function arr($arr = null)
    {
        if ($arr != null) {
            echo '<pre>';
            print_r($arr);
            echo '</pre>';
        } else {
            $this->log('arr_f: массив не передан');
        }
    }

    public function require_class($mod = null, $dir = null)
    {
        if ($mod != null && $dir != null) {
            try{
                $name_model = "model_" . $mod;
                if (!class_exists($name_model)) {
                    require_once $this->git("app/model/" . $dir . "/model_" . $mod . ".php");
                }
                $name_c = "controller_" . $mod;
                if (!class_exists($name_c)) {
                    require_once $this->git("app/controller/" . $dir . "/controller_" . $mod . ".php");
                }
                $mod_name = '\model_' . $mod;
                return $mod_name;
            } catch (\Throwable $e) {
                $this->log('Ошибка в шаблоне '.$mod.': '.$e->getMessage());
                return false;
            }

        } else {
            $this->log('require_class: название модели(1) и название директории(2) не могут быть пустыми');
        }
    }

    public function require_obj($mod = null, $dir = null)
    {
        if (isset($mod)) {
            try{
                $name_model = "model_" . $mod;
                if (!class_exists($name_model)) {
                    require_once $this->git("app/model/" . $dir . "/model_" . $mod . ".php");
                }
                $name_c = "controller_" . $mod;
                if (!class_exists($name_c)) {
                    require_once $this->git("app/controller/" . $dir . "/controller_" . $mod . ".php");
                }

                $mod_name = '\controller_' . $mod;
                ${$mod} = new $mod_name;
                return ${$mod};
            } catch (\Throwable $e) {
                $this->log('Ошибка в шаблоне '.$mod.': '.$e->getMessage());
                return false;
            }
        } else {
            $this->log('require_obj: название модели(1) и название директории(2) не могут быть пустыми');
        }
    }


    public function log_file($name = 'no', $text = null, $append = 'no')
    {
        if ($text != null) {
            if ($name != 'no') {
                if ($append != 'no') {
                    file_put_contents($GLOBALS["foton_setting"]["path"] . "/.logs/" . $name . "-" . date("Ymd") . ".!?", $text, FILE_APPEND | LOCK_EX);
                } else {
                    file_put_contents($GLOBALS["foton_setting"]["path"] . "/.logs/" . $name . "-" . date("Ymd") . ".!?", $text);
                }
            } else {
                if ($append != 'no') {
                    file_put_contents($GLOBALS["foton_setting"]["path"] . "/.logs/" . date("Ymd") . ".!?", $text, FILE_APPEND | LOCK_EX);
                } else {
                    file_put_contents($GLOBALS["foton_setting"]["path"] . "/.logs/" . date("Ymd") . ".!?", $text);
                }
            }

        } else {
            $this->log('log_file: текст(2) не передан в метод');
        }
    }

        public function log($text = null, $format = null, $method = null)
    {
        if ($text != null) {
            if ($method != null) {
                $text = $this->$method($text);
            }
            if ($format != null) {
                $text = serialize($text);
            }
            if (!is_array($text)) {
                $text = str_replace('"', "", $text);
            }
            if((isset($this->get()->g['format']) && $this->get()->g['format']=='api_json') || @$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
                $arr = ['error_log'=>$text];
                echo json_encode($arr,true);
            }
            else if($this->is_cli()){
                print_r($text)."\r\n";
            }
            else{
                echo '<script>console.log("';
                print_r($text);
                echo '");</script>';
            }
        }
    }
    protected function unit_method($class,$method,...$args)
    {
        $return = new \ReflectionMethod($class, $method);
        $return->setAccessible(true);
        $obj = new $class;
        $class_new = $return->isStatic() ? null : $obj;
        return $return->invoke($class_new, ...$args);
    }

    public function unit($method,$args,$arr=array(),$class=__CLASS__)
    {
        if(isset($method)){
            if($class==__CLASS__){
                $obj_unit = $this;
            }
            else{
                $obj_unit = new $class;
            }
            if(method_exists($obj_unit,$method)){
                if(is_callable(array($class, $method))){
                    $return = $obj_unit->$method(...$args); 
                }
                else{
                    $return = $this->unit_method($class,$method,...$args);
                }
                $arr_res = array();
                if(isset($arr['type']) && is_array($arr['type'])){
                    foreach($arr['type'] as $key_type=>$type){
                        if(strrpos($key_type,':')!== false){
                            $data = explode(':',$key_type);
                            $result = $return;
                            foreach($data as $element){
                                if(isset($result[$element])){
                                    $result = $result[$element];
                                }
                            }
                        }
                        else{
                            $result = $return;
                        }
                        if(gettype($result)!==$type){
                            $arr_res['type'][$key_type.':'.gettype($result)] = 'no';
                        }
                        else{
                            $arr_res['type'][$key_type.':'.gettype($result)] = 'yes';
                        }
                    }
                }       
                if(isset($arr['value']) && is_array($arr['value'])){
                    foreach($arr['value'] as $key_method=>$pattern){
                        if(strrpos($key_method,':')!== false){
                            $data = explode(':',$key_method);
                            $result = $return;
                            foreach($data as $element){
                                if(isset($result[$element])){
                                    $result = $result[$element];
                                }
                            }
                        }
                        else{
                            $result = $return;
                        }                   
                        $value = substr($pattern, 1);                       
                        if($pattern[0]=='<'){
                            if((int)$result<(int)$value){
                                $arr_res['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res['result'][$key_method] = 'no';
                            }
                        }
                        else if($pattern[0]=='>'){
                            if((int)$result>(int)$value){
                                $arr_res['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res['result'][$key_method] = 'no';
                            }
                        }
                        else if($pattern[0]=='!'){
                            if($result!=$value){
                                $arr_res['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res['result'][$key_method] = 'no';
                            }
                        }
                        else if($pattern[0]=='='){
                            if($result==$value){
                                $arr_res['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res['result'][$key_method] = 'no';
                            }
                        }
                        else if($pattern[0]=='%'){
                            if(strrpos($result,$value)!==false){
                                $arr_res['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res['result'][$key_method] = 'no';
                            }
                        }
                        else{
    
                        }
                    }
                }
                return $arr_res;
            }
            else{
                return 'error method:'.$method.' in class '.$class;
            }
            
        }
        else{
            return 'error argument: no method';
        }
    }   

    public function is_cli()
    {
        return empty($_SERVER['REQUEST_METHOD']);
    }

    public function alert($text = null, $method = null, $format = null)
    {
        if ($text != null) {
            if ($method != null) {
                $text = $this->$method($text);
            }
            if ($format != null) {
                $text = serialize($text);
            }
            if (!is_array($text)) {
                $text = str_replace("'", "", $text);
            }
            echo "<script>alert('" . $text . "');</script>";
        } else {
            $this->log('alert: текст(1) не передан');
        }
    }
    public function transactions($tr=null){
        if($tr==null){
            return 'Укажите транзакцию';
        }
        else{
            $files = $this->git($GLOBALS['foton_setting']['path'] . '/app/model/transaction/model_' . $tr . '.php');
            $table = explode('-',$tr)[1];
            if (file_exists($files)) {
                $this->i_alter($tr,$table);
                $this->i_create($tr,$table);                
                $this->i_drop($tr,$table);
                return 'Транзакция '.$tr.' выполнена';
            }
            else{
                return 'Транзакция не найдена';
            }
        }
    }
    public function i($c_name = null)
    {
        $c_name = mb_strtolower($c_name);
        if ($c_name != null) {
            if(strpos($c_name,'-')!==false){
                $files = $this->git($GLOBALS['foton_setting']['path'] . '/app/model/transaction/model_' . $c_name . '.php');
                $c_name=explode('-',$c_name)[0];
                if (file_exists($files)) {
                    try{
                        require_once($files);
                    } catch (\Throwable $e) {
                        $this->log('Ошибка в файле '.$files.': '.$e->getMessage());
                        return false;
                    }
                } else {
                    $this->log('i_f: название модели(1) не передано');
                    return false;
                }
            }
            else{
                $file = $this->git($GLOBALS['foton_setting']['path'] . '/app/model/model_' . $c_name . '.php');
                $filea = $this->git($GLOBALS['foton_setting']['path'] . '/app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $c_name . '.php');
                $files = $this->git($GLOBALS['foton_setting']['path'] . '/app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $c_name . '.php');
                if (file_exists($file)) {
                    try{
                        require_once($file);
                    } catch (\Throwable $e) {
                        $this->log('Ошибка в файле '.$file.': '.$e->getMessage());
                        return false;
                    }
                } else if (file_exists($filea)) {
                    try{
                        require_once($filea);
                    } catch (\Throwable $e) {
                        $this->log('Ошибка в файле '.$filea.': '.$e->getMessage());
                        return false;
                    }
                } else if (file_exists($files)) {
                    try{
                        require_once($files);
                    } catch (\Throwable $e) {
                        $this->log('Ошибка в файле '.$files.': '.$e->getMessage());
                        return false;
                    }
                } else {
                    $this->log('i_f: название модели(1) не передано');
                    return false;
                }
            }
            $mod = '\model_' . $c_name;
            return $mod;
        } else {
            return false;
        }
    }

    public function i_h1($c_name = null, $table = null)
    {
        if ($table != null) {
            $name_m = 'names';
            $class_m = $this->i($c_name);
            if($class_m){
                $obj_m = new $class_m;
                if (method_exists($obj_m, $name_m)) {
                    return $obj_m->$name_m()[$table];
                } else {
                    return false;
                }
            }
            else{
                return false;
            }
        } else {
            $this->log('i_h1: название таблицы(2) не задано');
        }
    }

    public function replace_post($arr = null, $pole = null, $pole2 = null)
    {
        if ($arr != null && $pole != null) {
            if ($pole2 != null) {
                $arr[$pole2] = $arr[$pole];
                unset($arr[$pole]);
                return $arr;
            } else {
                $this->log('replace_post: ключ замены не определен(3)');
            }
        } else {
            return true;
        }

    }

    public function delete_post($arr = null, $field = null)
    {
        if ($arr != null && $field != null) {
            if (is_array($field)) {
                foreach ($field as $p_v) {
                    unset($arr[$p_v]);
                }
            } else {
                unset($arr[$field]);
            }
            return $arr;
        } else {

            return false;
        }
    }

    public function i_update($c_name = null, $table = null, $arr = null, $prev = null, $after = null)
    {
        if ($table != null && $arr != null && $c_name != null) {
            $class_m = $this->i($c_name);
            $obj_m = new $class_m;
            $chmod = $table . '_chmod';
            if (method_exists($class_m, $chmod)) {
                $chmod_m = $obj_m->$chmod();
                if (!in_array($_SESSION['chmod_id'], $chmod_m)) {
                    return false;
                }
            }
            $method_v = $table.'_validate';
            $method_is = $table.'_is';
            if(method_exists($obj_m,$method_v)){
                $arr_v = $obj_m->$method_v();
                if(isset($arr_v['up'])){
                    if(isset($arr_v['delete']['up'])){
                      $arr = $this->validate($arr_v['up'],$arr,['delete'=>true]);  
                    }
                    else{
                        $arr = $this->validate($arr_v['up'],$arr);
                    }
                }
            }   
            if(method_exists($obj_m,$method_is)){
                $arr_is = $obj_m->$method_is();
                if(isset($arr_is['up'])){
                    $is = $this->isvalid($arr_is['up'],$arr,true);
                    if(!$is){
                        $this->log('Неверный формат данных');
                        return false;
                    }
                }
            }
            $arr2 = $arr;
            if ($prev != null) {
                $up_prev = 'before_up_' . $table;
                if (method_exists($obj_m, $up_prev)) {
                    $arr = $obj_m->$up_prev($arr);
                }
            }
            $up = '';
            foreach ($arr as $key => $val) {
                if ($key == 'id') {
                    $id = $val;
                } else {
                    $up_del = 'delete_up';
                    if (method_exists($obj_m, $up_del)) {
                        $arr_no = $obj_m->$up_del();
                        if(isset($arr_no[$table])){
                            if(!array_key_exists($key,$arr_no[$table])){
                                $val = $this->db->quote($val);
                                $up .= $key . "=" . $val . ",";
                            }
                        }
                        else{
                            $val = $this->db->quote($val);
                            $up .= $key . "=" . $val . ",";
                        }
                    }
                    else{
                        $val = $this->db->quote($val);
                        $up .= $key . "=" . $val . ",";
                    }
                }

            }
            $up = substr($up, 0, -1);
            $sql = "UPDATE " . $table . " SET  " . $up . " WHERE id=" . $id . " ";
            $this->log($sql);
            try {
                $res = $this->db->query($sql);
            } catch (\PDOException $e) {
                $this->log('i_update error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }
            if ($after != null) {
                $up_a = 'after_up_' . $table;
                if (method_exists($obj_m, $up_a)) {
                    $arr = $obj_m->$up_a($arr2);
                }
            }
            return $arr;
        } else {
            $this->log('i_update: контроллер(1), таблица(2) и массив(3) не могут быть пустыми');

            return false;
        }


    }


    public function abc09($text = null)
    {
        if ($text != null) {
            $text = preg_replace('#([^0-9_a-zA-Z]+)#', '', $text);
            return $text;
        } else {
            $this->log('abc09: текст(1) не может быть пустым');
        }
    }

    public function unsetPost($delete = null, $js = null)
    {
        if ($delete != null) {
            if ($_POST) {
                if ($js != null) {
                    echo "<script>document.location.href='" . $GLOBALS['foton_setting']['http'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "';</script>";
                } else {
                    header("Location: " . $_SERVER['REQUEST_URI']);
                }
            }
        } else {
            $this->log('unset_post: delete(1) не может быть пустым, может быть любым символом');
        }
    }


    public function i_insert($c_name = null, $table = null, $arr = null, $prev = null, $after = null, $res_id = null)
    {
        if ($table != null && $arr != null && $c_name != null) {
            $class_m = $this->i($c_name);
            $obj_m = new $class_m;
            $chmod = $table . '_chmod';
            if (method_exists($class_m, $chmod)) {
                $chmod_m = $obj_m->$chmod();
                if (!in_array($_SESSION['chmod_id'], $chmod_m)) {
                    return false;
                }
            }
            $method_v = $table.'_validate';
            $method_is = $table.'_is';
            if(method_exists($obj_m,$method_v)){
                $arr_v = $obj_m->$method_v();
                if(isset($arr_v['ins'])){
                    if(isset($arr_v['delete']['ins'])){
                      $arr = $this->validate($arr_v['ins'],$arr,['delete'=>true]);  
                    }
                    else{
                        $arr = $this->validate($arr_v['ins'],$arr);
                    }
                }
            }
            if(method_exists($obj_m,$method_is)){
                $arr_is = $obj_m->$method_is();
                if(isset($arr_is['ins'])){
                    $is = $this->isvalid($arr_is['ins'],$arr,true);
                    if(!$is){
                        $this->log('Неверный формат данных');
                        return false;
                    }
                }
            }
            $arr2 = $arr;
            if ($prev != null) {
                $ins_prev = 'before_ins_' . $table;
                if (method_exists($obj_m, $ins_prev)) {
                    $arr = $obj_m->$ins_prev($arr);
                }
            }
            foreach ($arr as $key => $val) {

                if ($key == 'id') {

                } else {
                    $pole[] = $key;
                    $res[] = $val;
                }

            }
            $ins = implode(',', $pole);
            $ins_v = array();
            foreach ($res as $v) {
                $ins_v[] = $this->db->quote($v);
            }
            $ins_v = implode(",", $ins_v);

            $sql = "INSERT INTO " . $table . "  (" . $ins . ") VALUES (" . $ins_v . ")";
            $this->log($sql);
            if ($res_id != null) {
                if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                    $sql = str_replace('`', '', $sql);
                    $sql = str_replace('"', "'", $sql);
                }
                try {
                    $sql_q = $this->db->prepare($sql);
                    $sql_q->execute();
                    $insid = $this->db->lastInsertId();
                    if ($after != null) {
                        $arr2['id'] = $insid;
                        $ins_a = 'after_ins_' . $table;
                        if (method_exists($obj_m, $ins_a)) {
                            $obj_m->$ins_a($arr2);
                        }
                    }
                    return $insid;
                } catch (\PDOException $e) {
                    return false;
                }

            } else {
                if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                    $sql = str_replace('`', '', $sql);
                    $sql = str_replace('"', "'", $sql);
                }
                try {
                    $sql_q = $this->db->prepare($sql);
                    $sql_q->execute();
                    $insid = $this->db->lastInsertId();
                } catch (\PDOException $e) {
                    return false;
                }
                if ($after != null) {
                    $arr2['id'] = $insid;
                    $ins_a = 'after_ins_' . $table;
                    if (method_exists($obj_m, $ins_a)) {
                        $arr = $obj_m->$ins_a($arr2);
                    }
                }
                return $arr;
            }
        } else {
            $this->log('i_insert: контроллер(1), таблица(2) и массив(3) не могут быть пустыми');

            return false;
        }

    }

    public function i_delete($c_name = null, $table = null, $id = null, $prev = null, $after = null)
    {
        if ($c_name != null && $table != null && $id != null) {
            $class_m = $this->i($c_name);
            $obj_m = new $class_m;
            $chmod = $table . '_chmod';
            if (method_exists($class_m, $chmod)) {
                $chmod_m = $obj_m->$chmod();
                if (!in_array($_SESSION['chmod_id'], $chmod_m)) {
                    return false;
                }
            }
            if ($prev != null) {
                $del_prev = 'before_del_'.$table;
                if (method_exists($obj_m, $del_prev)) {
                    $id = $obj_m->$del_prev($id);
                }
            }
            $sql = "DELETE FROM " . $table . "  WHERE id=" . $id . " ";
            $this->log($sql);
            try {
                $res = $this->db->query($sql);
            } catch (\PDOException $e) {
                $this->log('i_delete error:' . $e->getMessage() . '--' . (int)$e->getCode());
            }

            if ($after != null) {
                $del_a = 'after_del_'.$table;
                if (method_exists($obj_m, $del_a)) {
                    $id = $obj_m->$del_a($id);
                }
            }
            return $id;
        } else {
            $this->log('i_delete: контроллер(1), таблица(2) и id(3) не могут быть пустыми');
            return false;
        }
    }


    public function i_create($c_name = null, $table = null, $dbs = null)
    {
        if ($table != null && $c_name != null) {
            $method = 'interfaces';
            $method2 = 'interface_sp';
            $method_alter = 'alter_interface';
            $class_m = $this->i($c_name);
            $obj_m = new $class_m;
            $path_tr = $this->git($GLOBALS['foton_setting']['path'].'/app/model/transaction');
            if(!file_exists($path_tr)){
                mkdir($path_tr);
            }
            if (method_exists($obj_m, $method)) {
                $arrs = $obj_m->$method();
            } else if (method_exists($obj_m, $method2)) {
                $arrs = $obj_m->$method2();
            } else {
                return false;
            }
            foreach ($arrs as $table => $arr) {
                if ($GLOBALS['foton_setting']['sql'] == 'lite') {
                    $key = "`" . $arr["key"] . "` INTEGER  PRIMARY KEY AUTOINCREMENT,";
                    $parametr = '';
                    if (method_exists($obj_m, $method_alter)) {
                        $arr_alter = $obj_m->$method_alter();
                    }
                    foreach ($arr["field"] as $k => $v) {
                        if ((isset($arr_alter[$table]["alter"]) && array_key_exists($v, $arr_alter[$table]["alter"])) || $arr["key"]==$v) {
                        } else {
                            $parametr .= "`" . $v . "` " . self::$type_arr[$arr["format"][$k]] . ",";
                            try {
                                 if(!$this->field_table_foton($table,$v)){
                                    $this->db->exec("ALTER TABLE `" . $table . "` ADD  `" . $v . "` " . self::$type_arr[$arr["format"][$k]] . "");//sqllite
                                 }
                            } catch (\PDOException $e) {
                               $this->log('i_create error:' . $e->getMessage() . '--' . (int)$e->getCode());
                            }
                        }
                    }
                    $parametr = substr($parametr, 0, -1);
                    $parametr = $key . $parametr;
                    try {
                        if(!$this->is_table($table)){
                            $arr_tr = array($table=>array("del"=>"1"));
                            $content_tr = "<?php class Model_".$c_name."{ public function drop_interface(){ return json_decode('".json_encode($arr_tr)."');} } ?>";
                            file_put_contents($path_tr.'/Model_'.$c_name.'-'.$table.'-'.date("Ymdsi").'.php',$content_tr);
                            $this->db->query("CREATE TABLE `" . $table . "` (" . $parametr . ")");
                        }                        
                    } catch (\PDOException $e) {
                        $this->log('i_create error:' . $e->getMessage() . '--' . (int)$e->getCode());
                    }
                    $parametr = '';
                } else if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                    $key = $arr["key"] . " serial NOT NULL PRIMARY KEY,";
                    $parametr = '';
                    if (method_exists($obj_m, $method_alter)) {
                        $arr_alter = $obj_m->$method_alter();
                    }
                    foreach ($arr["field"] as $k => $v) {                        
                        if ((isset($arr_alter[$table]["alter"]) && array_key_exists($v, $arr_alter[$table]["alter"]))  || $arr["key"]==$v) {
                        } else {
                            $parametr .= $v . " " . self::$type_arr[$arr["format"][$k]] . ",";
                            try {
                                if(!$this->field_table_foton($table,$v)){
                                    $this->db->exec("ALTER TABLE " . $table . " ADD COLUMN " . $v . " " . self::$type_arr[$arr["format"][$k]] . "");
                                }
                            } catch (\PDOException $e) {
                                $this->log('i_create error:' . $e->getMessage() . '--' . (int)$e->getCode());
                            }
                        }
                    }
                    $parametr = substr($parametr, 0, -1);
                    $parametr = $key . $parametr;
                    $parametr = str_replace("`", "", $parametr);
                    try {
                        
                        if(!$this->is_table($table)){
                            $arr_tr = array($table=>array("del"=>"1"));
                            $content_tr = "<?php class Model_".$c_name."{ public function drop_interface(){ return json_decode('".json_encode($arr_tr)."');} } ?>";
                            file_put_contents($path_tr.'/Model_'.$c_name.'-'.$table.'-'.date("Ymdsi").'.php',$content_tr);
                            $this->db->query("CREATE TABLE " . $table . " (" . $parametr . ")");
                        }                        
                    } catch (\PDOException $e) {
                        $this->log('i_create error:' . $e->getMessage() . '--' . (int)$e->getCode());
                    }
                    $parametr = '';
                } else {
                    $parametr = '';
                    if (method_exists($obj_m, $method_alter)) {
                        $arr_alter = $obj_m->$method_alter();
                    }
                    foreach ($arr["field"] as $k => $v) {                        
                        if (isset($arr_alter[$table]["alter"]) && array_key_exists($v, $arr_alter[$table]["alter"])) {
                        } else {
                            $parametr .= "`" . $v . "` " . self::$type_arr[$arr["format"][$k]] . ",";
                            try {
                                if(!$this->field_table_foton($table,$v)){
                                    $this->db->exec("ALTER TABLE `" . $table . "` ADD  `" . $v . "` " . self::$type_arr[$arr["format"][$k]] . "");
                                }
                            } catch (\PDOException $e) {
                               $this->log('i_create error:' . $e->getMessage() . '--' . (int)$e->getCode());
                            }
                        }
                    }
                    if ($dbs == null) {
                        $dbs = 'InnoDB';
                    }
                    try {
                        if(!$this->is_table($table)){
                            $arr_tr = array($table=>array("del"=>"1"));
                            $content_tr = "<?php class Model_".$c_name."{ public function drop_interface(){ return json_decode('".json_encode($arr_tr)."');} } ?>";
                            file_put_contents($path_tr.'/Model_'.$c_name.'-'.$table.'-'.date("Ymdsi").'.php',$content_tr);
                            $this->db->query("CREATE TABLE IF NOT EXISTS `" . $table . "` (" . $parametr . " PRIMARY KEY (`" . $arr["key"] . "`)) Engine=" . $dbs . " DEFAULT CHARSET=utf8;");
                        }
                       
                    } catch (\PDOException $e) {
                        $this->log('i_create error:' . $e->getMessage() . '--' . (int)$e->getCode());
                    }
                    $parametr = '';
                }
            }
            return true;
        } else {
            $this->log('i_create: контроллер(1) и таблица(2)  не могут быть пустыми');
            return false;
        }
    }

    public function i_drop($c_name = null, $table = null)
    {
        if ($table != null && $c_name != null) {
            $path_tr = $this->git($GLOBALS['foton_setting']['path'].'/app/model/transaction');
            if(!file_exists($path_tr)){
                mkdir($path_tr);
            }
            $method = 'drop_interface';
            $method_i = 'interfaces';
            $class_m = $this->i($c_name);
            $obj_m = new $class_m;
            if (method_exists($obj_m, $method)) {
                $arr = $obj_m->$method();
            } else {
                return false;
            }
            if (isset($arr[$table]["del"]) && $arr[$table]["del"] == "1") {
                if(method_exists($obj_m,$method_i)){
                    $arr_tr = $obj_m->$method_i();
                    $arr_tr = $arr_tr[$table];
                    if(count($arr_tr)>0){
                        $content_tr = "<?php class Model_".$c_name."{ public function interfaces(){ return json_decode('".json_encode($arr_tr)."');} } ?>";
                        file_put_contents($path_tr.'/Model_'.$c_name.'-'.$table.'-'.date("Ymdsi").'.php',$content_tr);
                    }
                }
                if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                    try {
                        $this->db->exec("DROP TABLE " . $table);
                    } catch (\PDOException $e) {
                        $this->log('i_drop error:' . $e->getMessage() . '--' . (int)$e->getCode());
                    }

                } else {

                    try {
                        $this->db->exec("DROP TABLE `" . $table . "`");
                    } catch (\PDOException $e) {
                        $this->log('i_drop error:' . $e->getMessage() . '--' . (int)$e->getCode());
                    }
                }
                return true;
            }
            if (isset($arr[$table]["field"])) {
                if(method_exists($obj_m,$method_i)){
                    $arr_tr = $obj_m->$method_i();
                    $arr_tr = $arr_tr[$table];                    
                }
                foreach ($arr[$table]["field"] as $f) {
                    if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                        try {
                            $this->db->exec("ALTER TABLE  " . $table . " DROP COLUMN " . $f . " RESTRICT");
                            $true_drop = true;
                        } catch (\PDOException $e) {
                            $this->log('i_drop error:' . $e->getMessage() . '--' . (int)$e->getCode());
                        }
                    } else {
                        try {
                            $this->db->exec("ALTER TABLE  `" . $table . "` DROP COLUMN `" . $f . "`");
                            $true_drop = true;
                        } catch (\PDOException $e) {
                            $this->log('i_drop error:' . $e->getMessage() . '--' . (int)$e->getCode());
                        }

                    }
                }
                if(isset($true_drop) && count($arr_tr)>0){
                        $content_tr = "<?php class Model_".$c_name."{ public function interfaces(){ return json_decode('".json_encode($arr_tr)."');} public function add_colunm(){ return json_decode('".json_encode($arr[$table]["field"])."'); } } ?>";
                        file_put_contents($path_tr.'/Model_'.$c_name.'-'.$table.'-'.date("Ymdsi").'.php',$content_tr);
                    }
            }

        } else {
            $this->log('i_drop: контроллер(1) и таблица(2)  не могут быть пустыми');
            return false;
        }
    }


    /*$delete=array('tables');
        $replace=array('wheres'=>'textarea');
        $arr=array('model'=>'html','table'=>'graph','interfaces'=>'sp','extra_arr'=>array('name'=>'lang'),'fields_table'=>'field','fields_type'=>'format_select');*/
    public function i_list_ajax($arr = null, $replace = null, $delete = null)
    {

        if ($arr != null) {
            $arr = $this->i_arr($arr);
            $html = array();

            foreach ($arr["fields"] as $k => $v) {
                if ($replace != null && isset($replace[$v])) {
                    $arr['type'][$k] = $replace[$v];
                }

                if ($delete != null) {

                    if (!in_array($v, $delete)) {
                        $type = $arr['type'][$k];
                        if (isset($arr['extra_cod'])) {
                            $extra = array();
                            foreach ($arr['extra_cod'] as $name => $shortcod) {
                                $extra[] = $name . ':' . $shortcod;
                            }
                            $extra_string = implode('|', $extra);
                            $html[] = '[' . $v . '|' . $type . '|' . $extra_string . ']';
                        } else {
                            $html[] = '[' . $v . '|' . $type . ']';
                        }
                    }

                } else {

                    $type = $arr['type'][$k];
                    if (isset($arr['extra_cod'])) {
                        $extra = array();
                        foreach ($arr['extra_cod'] as $name => $shortcod) {
                            $extra[] = $name . ':' . $shortcod;
                        }
                        $extra_string = implode('|', $extra);
                        $html[] = '[' . $v . '|' . $type . '|' . $extra_string . ']';
                    }
                    $html[] = '[' . $v . '|' . $type . ']';


                }

            }

            return $html;
        } else {
            $this->log('i_list_ajax: массив(1)  не может быть пустым');
            return false;
        }
    }
    private function up_table($arr){
        foreach ($arr as $table => $fields) {
            $this->i_alter($model, $table);
            $this->i_create($model, $table);
            $this->i_drop($model, $table);
        }
    }
    public function migrate($interface = null, $f_dir = null)
    {
        if ($f_dir != null) {
            $arr_dir = explode('', $f_dir);
        } else {
            $arr_dir = array($GLOBALS['foton_setting']['sitedir'], $GLOBALS['foton_setting']['admindir']);
        }
        foreach ($arr_dir as $dir) {
            foreach (glob($GLOBALS['foton_setting']['path'] . '/app/model/' . $dir . '/model_*') as $file) {
                $model = basename($file);
                $model = str_replace('model_', '', $model);
                $model = str_replace('.php', '', $model);
                try{
                    require_once $file;
                } catch (\Throwable $e) {
                    $this->log('Ошибка в файле '.$file.': '.$e->getMessage());
                    return false;
                }
                $class = '\model_' . $model;
                $obj = new $class;
                if ($interface == null) {
                    $m1 = 'interfaces';
                    $m2 = 'interface_sp';
                    if (method_exists($class, $m1)) {
                        $this->up_table($obj->$m1());
                    }
                    if (method_exists($class, $m2)) {
                        $this->up_table($obj->$m2());
                    }
                } else {
                    $m = $interface;
                    if (method_exists($class, $m)) {
                        $this->up_table($obj->$m());
                    }
                }
            }
        }
    }

    public function fork($arr = array(), $arr_arg = array(), $proc = 50, $time = 5)
    {
        if (count($arr) > 0 && isset($arr[0])) {
            $load = $this->getServerLoad();
            if ((int)$load > $proc) {
                sleep($time);
                $this->fork($arr, $arr_arg, $proc, $time);
            } else {
                $m = array_shift($arr);
                if (isset($arr_arg[$m])) {
                    $arg = $arr_arg[$m];
                    unset($arr_arg[$m]);
                    $this->$m(...array_values($arg));
                } else {
                    $this->$m();
                }
                $this->fork($arr, $arr_arg, $proc, $time);
            }
        } else {
            echo " process end";
        }

    }

    public function load($file=null){
        if($file!=null && file_exists($file)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    public function getServerLoad()
    {
        $load = null;
        if (stristr(PHP_OS, "win")) {
            $load = shell_exec('wmic cpu get LoadPercentage');
            $load = str_replace('LoadPercentage', '', $load);

        } else {
            $load = sys_getloadavg();
        }
        return $load;
    }

    public function m_json($model = null, $status = null)
    {
        if ($model == null || $status == null) {
            return array();
        } else {
            if (!file_exists($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'])) {
                mkdir($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir']);
            }
            $filepath = $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/';
            foreach (glob($filepath . $model . '_' . $status . '*.json') as $file) {
                $LastModified[] = filemtime($file);
                $FileName[filemtime($file)] = $file;
            }
            if (isset($LastModified) && is_array($LastModified)) {
                sort($LastModified);
                $lastIndex = $LastModified[count($LastModified) - 1];
                $LastMFile = $FileName[$lastIndex];
                $json = file_get_contents($LastMFile);
                return json_decode($json, true);
            } else {
                return array();
            }
        }
    }

    public function mBack($model = null, $status = null)
    {
        if ($model == null || $status == null) {
            return false;
        } else {
            if ($this->mf_json($model, $status) !== false) {
                unlink($this->mf_json($model, $status));
                return $this->mf_json($model, $status);
            }
            return false;
        }
    }

    public function mDiff($model = null)
    {
        if ($model == null) {
            return 'Укажите модель';
        } else {
            $filepath = $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/';
            foreach (glob($filepath . $model . '_create*.json') as $file) {
                $name = basename($file);
                $arr[] = json_decode(file_get_contents($file), true);
                $arr_n[] = str_replace('.php', '', $name);
                $name_a = str_replace('_create', '_alter', $name);
                $name_d = str_replace('_create', '_delete', $name);
                $f_a = $filepath . $name_a;
                $f_d = $filepath . $name_d;
                if (file_exists($f_a)) {
                    $arr_a[] = json_decode(file_get_contents($f_a), true);
                    $arr_an[] = str_replace('.php', '', $name_a);
                }
                if (file_exists($f_d)) {
                    $arr_d[] = json_decode(file_get_contents($f_d), true);
                    $arr_dn[] = str_replace('.php', '', $name_d);
                }
            }
            foreach ($arr as $key => $arr_f) {
                if ($key > 0) {
                    if (count($diff1) > 0) {
                        $diff1 = $this->check_diff($arr[$key], $arr[$key - 1]);
                        echo $arr_n[$key] . "\n";
                        print_r($diff1);
                    }
                }
                $diff2 = $this->check_diff($arr[$key + 1], $arr[$key]);
                if (count($diff2) > 0) {
                    echo $arr_n[$key] . "\n";
                    print_r($diff2);
                }
                if (isset($arr_a[$key])) {
                    echo $arr_an[$key] . "\n";
                    print_r($arr_a[$key]);
                }
                if (isset($arr_d[$key])) {
                    echo $arr_dn[$key] . "\n";
                    print_r($arr_d[$key]);
                }
            }
        }
    }

    public function check_diff($array1, $array2)
    {
        $result = array();
        foreach ($array1 as $key => $val) {
            if (isset($array2[$key])) {
                if (is_array($val) && $array2[$key]) {
                    $result[$key] = $this->check_diff($val, $array2[$key]);
                    if (is_array($result[$key]) && count($result[$key]) == 0) {
                        unset($result[$key]);
                    }
                }
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }

    public function mCreate($model = null, $table = null, $field = null)
    {
        if ($model == null || $table == null || $field == null) {
            return 'Укажите модель, таблицу и поля';
        } else {
            $is_table = $this->field_table($table);
            $is_arr = (array)$is_table;
            if (count($is_arr) > 0) {
                return 'Таблица уже создана';
            } else {
                $field_arr = explode('%', $field);
                if (isset($field_arr[0]) && isset($field_arr[1]) && isset($field_arr[2]) && isset($field_arr[3])) {
                    $f1 = explode(',', $field_arr[0]);
                    $f3 = explode(',', $field_arr[1]);
                    $f2 = explode(',', $field_arr[2]);
                    if (is_array($f1) && is_array($f2) && is_array($f3)) {
                        $arr_d = $this->m_json($model, 'delete');
                        $arr_a = $this->m_json($model, 'alter');
                        foreach ($f1 as $f) {
                            $arr_df = array_flip($arr_d[$table]['field']);
                            if (isset($arr_df[$f])) {
                                unset($arr_d[$table]['field'][$arr_df[$f]]);
                                unset($arr_d[$table]['name'][$arr_df[$f]]);
                            }
                            if (isset($arr_a[$table]['alter'][0]) && $arr_d[$table]['alter'][0] == $f) {
                                unset($arr_a[$table]['alter']);
                            }
                            if (isset($arr_a[$table]['type'][$f])) {
                                unset($arr_a[$table]['type']);
                            }
                        }
                        $arr = array();
                        $arr[$table] = array('field' => $f1, 'name' => $f1, 'format_select' => $f3, 'format' => $f2, 'key' => $field_arr[3]);
                        if (count($arr_d) > 0) {
                            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_delete_' . $date . '.json', json_encode($arr_d, true));
                        }
                        if (count($arr_a) > 0) {
                            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_alter_' . $date . '.json', json_encode($arr_a, true));
                        }
                        $arr_n = json_decode(file_get_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_name_new.json'), true);
                        $arr_n[$table] = $table;
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_name_new.json', json_encode($arr_n, true));
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_new.json', json_encode($arr, true));
                        return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_new.json';
                    } else {
                        return 'Укажите поля таблицы (название полей через ,%форматы вывода через ,%формат хранения через ,%поле ключа';
                    }
                } else {
                    return 'Укажите поля таблицы (название полей через ,%форматы вывода через ,%формат хранения через ,%поле ключа';
                }
            }
        }
    }

    public function mUpdate($model = null, $table = null, $field = null)
    {
        $date = date("H_s_i_d_m_Y");
        if ($model == null || $table == null || $field == null) {
            return 'Укажите модель, таблицу и поля';
        } else {

            try {
                $is_table = $this->db->query("SHOW TABLES FROM '" . $GLOBALS["foton_setting"]["dbname"] . "' LIKE '" . $model . "';");
            } catch (\PDOException $e) {
                return 'mUpdate error:' . $e->getMessage() . '--' . (int)$e->getCode();
            }
            $is_arr = (array)$is_table;
            if (count($is_array) > 0) {
                return 'Таблица уже создана';
            } else {
                $field_arr = explode('%', $field);
                if (isset($field_arr[0]) && isset($field_arr[1]) && isset($field_arr[2]) && isset($field_arr[3])) {
                    $f1 = explode(',', $field_arr[0]);
                    $f3 = explode(',', $field_arr[1]);
                    $f2 = explode(',', $field_arr[2]);
                    if (is_array($f1) && is_array($f2) && is_array($f3)) {
                        $arr_d = $this->m_json($model, 'delete');
                        $arr_a = $this->m_json($model, 'alter');
                        foreach ($f1 as $f) {
                            $arr_df = array_flip($arr_d[$table]['field']);
                            if (isset($arr_df[$f])) {
                                unset($arr_d[$table]['field'][$arr_df[$f]]);
                                unset($arr_d[$table]['name'][$arr_df[$f]]);
                            }
                            if (isset($arr_a[$table]['alter'][0]) && $arr_d[$table]['alter'][0] == $f) {
                                unset($arr_a[$table]['alter']);
                            }
                            if (isset($arr_a[$table]['type'][$f])) {
                                unset($arr_a[$table]['type']);
                            }
                        }
                        $arr = $this->m_json($model, 'create');
                        $arr[$table] = array('field' => $f1, 'name' => $f1, 'format_select' => $f3, 'format' => $f2, 'key' => $field_arr[3]);
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_delete_' . $date . '.json', json_encode($arr_d, true));
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_alter_' . $date . '.json', json_encode($arr_a, true));
                        foreach ($arr as $table => $val) {
                            $arr_n[$table] = $table;
                        }
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_name_' . $date . '.json', json_encode($arr_n, true));
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json', json_encode($arr, true));
                        return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json';
                    } else {
                        return 'Укажите поля таблицы (название полей через ,%форматы вывода через ,%формат хранения через ,%поле ключа';
                    }
                } else {
                    return 'Укажите поля таблицы (название полей через ,%форматы вывода через ,%формат хранения через ,%поле ключа';
                }
            }
        }
    }

    public function mUpdateField($model = null, $table = null, $field = null)
    {
        $date = date("H_s_i_d_m_Y");
        if ($model == null || $table == null || $field == null) {
            return 'Укажите модель, таблицу и поля';
        } else {
            $field_arr = explode('%', $field);
            if (isset($field_arr[0]) && isset($field_arr[1]) && isset($field_arr[2])) {
                $f1 = $field_arr[0];
                $f3 = $field_arr[1];
                $f2 = $field_arr[2];
                $arr = $this->m_json($model, 'create');
                if (isset($arr[$table])) {
                    $fields = array_flip($arr[$table]['field']);
                    if (isset($fields[$f1])) {
                        return 'Поле уже существует в массиве';
                    }
                    $arr_d = $this->m_json($model, 'delete');
                    $arr_df = array_flip($arr_d[$table]['field']);
                    if (isset($arr_df[$f1])) {
                        unset($arr_d[$table]['field'][$arr_df[$f1]]);
                        unset($arr_d[$table]['name'][$arr_df[$f1]]);
                    }
                    $arr_a = $this->m_json($model, 'alter');
                    if (isset($arr_a[$table]['alter'][0]) && $arr_d[$table]['alter'][0] == $f1) {
                        unset($arr_a[$table]['alter']);
                    }
                    if (isset($arr_a[$table]['type'][$f1])) {
                        unset($arr_a[$table]['type']);
                    }
                    $arr[$table]['field'][] = $f1;
                    $arr[$table]['name'][] = $f1;
                    $arr[$table]['format_select'][] = $f3;
                    $arr[$table]['format'][] = $f2;
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json', json_encode($arr, true));
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_delete_' . $date . '.json', json_encode($arr_d, true));
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_alter_' . $date . '.json', json_encode($arr_a, true));
                    return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json';
                } else {
                    return 'Таблица не найдена';
                }
            } else {
                return 'Укажите поля таблицы (название поля%формат вывода%формат хранения)';
            }

        }
    }

    public function mAddtr($model = null, $table = null, $field = null)
    {
        $date = date("H_s_i_d_m_Y");
        if ($model == null || $table == null || $field == null) {
            return 'Укажите модель, таблицу и поля';
        } else {
            $field_ar = explode('%', $field);
            $tr = $field_ar[0];
            $field_arr = explode(',', $field_ar[1]);
            $arr = $this->m_json($model, 'create');
            if (count($arr) > 0) {
                if (isset($arr[$table])) {
                    if (isset($arr[$table][$tr])) {
                        return 'Строка уже существует в массиве';
                    }
                    $arr[$table][$tr] = $field_arr;
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json', json_encode($arr, true));
                    return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json';

                } else {
                    return 'Таблица не найдена';
                }
            } else {
                return 'Массив пуст';
            }
        }
    }

    public function mEcho($model = null, $status = null)
    {
        if ($status == null) {
            $file = $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '.php';
            if (file_exists($file)) {
                return json_decode(file_get_contents($file), true);
            } else {
                return 'Миграция не найдена';
            }
        } else {
            $arr = $this->m_json($model, $status);
            if (count($arr) > 0) {
                return $arr;
            } else {
                return 'Миграция не найдена';
            }
        }
    }

    public function mType()
    {
        print_r($this->arr_type);
    }

    public function mAlter($model = null, $table = null, $field = null)
    {
        $date = date("H_s_i_d_m_Y");
        if ($model == null || $table == null || $field == null) {
            return 'Укажите модель, таблицу и поле для замены,новое поле:тип поля';
        } else {
            $arr = $this->m_json($model, 'create');
            $arr_f = explode(':', $field);
            if (count($arr_f) <= 0) {
                return 'Не указан тип';
            }
            $arr_f2 = explode(',', $arr_f[0]);
            if (count($arr_f2) <= 0) {
                return 'Не указаны поля для замены';
            }
            $f_old = $arr_f2[0];
            $arr_new = array_flip($arr[$table]['field']);
            if (isset($arr_new[$f_old])) {
                $f_new = $arr_f2[1];
                $type = $arr_f[1];
                if (in_array($type, $this->arr_type)) {
                    $arr_a = $this->m_json($model, 'alter');
                    $arr_a[$table]['alter'] = $arr_f2;
                    $arr_a[$table]['type'] = array($f_new => $type);
                    $key = $arr_new[$f_old];
                    $arr[$table]['format'][$key] = $type;
                    $arr[$table]['field'][$key] = $f_new;
                    $arr[$table]['name'][$key] = $f_new;
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json', json_encode($arr, true));
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_alter_' . $date . '.json', json_encode($arr_a, true));
                    return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_alter_' . $date . '.json';
                } else {
                    return 'Тип не найден, для просмотра типов данных введите php foton type';
                }
            } else {
                return 'Поле для замены не найдено';
            }
        }
    }

    public function mDel($model = null, $table = null, $field = null)
    {
        $date = date("H_s_i_d_m_Y");
        if ($model == null || $table == null || $field == null) {
            return 'Укажите модель, таблицу и поле для удаления через запятую';
        } else {
            $arr = $this->m_json($model, 'create');
            $arr_f = explode(',', $field);
            $arr_d = $this->m_json($model, 'delete');
            $arr_new = array_flip($arr[$table]['field']);
            foreach ($arr_f as $f) {
                if (!isset($arr_new[$f])) {
                    return 'Поле ' . $f . ' не найдено';
                }

                if (empty($arr_d[$table]["field"][$f])) {
                    $arr_d[$table]["field"][] = $f;
                }
                $key = $arr_new[$f];
                foreach ($arr[$table] as $tr => $v_arr) {
                    if (isset($arr[$table][$tr][$key])) {
                        unset($arr[$table][$tr][$key]);
                    }
                }
            }
            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json', json_encode($arr, true));
            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_delete_' . $date . '.json', json_encode($arr_d, true));
            return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_delete_' . $date . '.json';

        }
    }


    public function mDrop($model = null, $table = null)
    {
        $date = date("H_s_i_d_m_Y");
        if ($model == null || $table == null) {
            return 'Укажите модель и таблицу';
        } else {
            $arr = $this->m_json($model, 'create');
            $arr_d = $this->m_json($model, 'delete');
            if (!isset($arr[$table])) {
                return 'Таблица не найдена';
            }
            unset($arr[$table]);
            unset($arr_d[$table]['field']);
            $arr_d[$table]['del'] = 1;
            foreach ($arr as $table => $val) {
                $arr_n[$table] = $table;
            }
            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_name_' . $date . '.json', json_encode($arr_n, true));
            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json', json_encode($arr, true));
            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_delete_' . $date . '.json', json_encode($arr_d, true));
            return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_delete_' . $date . '.json';
        }
    }

    public function mDeltr($model = null, $table = null, $field = null)
    {
        $date = date("H_s_i_d_m_Y");
        if ($model == null || $table == null || $field == null) {
            return 'Укажите модель, таблицу и поля';
        } else {
            if ($field == "format_select" || $field == "field" || $field == "format") {
                return "Нельзя удалить обязательные поля";
            }
            $arr = $this->m_json($model, 'create');
            if (count($arr) > 0) {
                if (isset($arr[$table])) {
                    if (isset($arr[$table][$field])) {
                        unset($arr[$table][$field]);
                        file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json', json_encode($arr, true));
                        return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json';
                    } else {
                        return 'Строка не существует в массиве';
                    }

                } else {
                    return 'Таблица не найдена';
                }
            } else {
                return 'Массив пуст';
            }
        }
    }

    /* $arr=array('model'=>'model','table'=>'table','interfaces'=>'interfaces','extra_arr'=>array(),'fields_table'=>'fields','fields_type'=>'type'*/
    public function i_arr($arr = null)
    {
        if (isset($arr['model']) && isset($arr['table']) && isset($arr['interface'])) {
            $arr_table = $this->i_arr_all($arr['model'], $arr['table'], $arr['interface']);
            $new_arr = array();
            $new_arr['other_f'] = array();
            if (isset($arr['extra_arr'])) {
                foreach ($arr['extra_arr'] as $key_e => $val_e) {
                    $new_arr['other_f'][$val_e] = $arr_table[$key_e];
                }
            }

            if (isset($arr_table[$arr['fields_type']]) && isset($arr_table[$arr['fields_table']])) {
                $new_arr['fields'] = $arr_table[$arr['fields_table']];
                $new_arr['type'] = $arr_table[$arr['fields_type']];
            }
            $new_arr['model'] = $arr['model'];
            $new_arr['table'] = $arr['table'];
            $new_arr['interface'] = $arr['interface'];
            return $new_arr;
        } else {
            $this->log('i_arr: $arr["model"] $arr["table"] и $arr["interface"] должны существовать');

            return false;
        }
    }

    public function arr_session($arr = null, $key = null, $before = '', $after = '', $inside = null, $delete = null)
    {
        $arr_r = array();

        if ($arr == null) {
            if ($inside != null && isset($_SESSION[$key][$inside]) && $inside != '') {

                return $_SESSION[$key][$inside];

            } else if ($inside != '') {
                return array();
            } else if (isset($_SESSION[$key])) {
                return $_SESSION[$key];

            } else {
                return false;
            }
        }
        if ($arr != null && $key != null) {
            if ($inside == null) {
                foreach ($arr as $key_arr => $val) {
                    if ($before != '') {
                        if (stristr($key_arr, $before)) {
                            if ($val != '') {
                                $key_arr = str_replace($before, '', $key_arr);
                                if ($after != '' && $val != '') {
                                    $val = $after . $val;
                                }
                                $_SESSION[$key][$key_arr] = $val;

                            } else {
                                if ($delete != null) {
                                    $key_arr = str_replace($before, '', $key_arr);
                                    unset($_SESSION[$key][$key_arr]);

                                }
                            }

                        } else {
                        }
                    } else {
                        if ($val != '') {
                            if ($after != '' && $val != '') {
                                $val = $after . $val;
                            }
                            $_SESSION[$key][$key_arr] = $val;
                        } else {
                            if ($delete != null) {

                                unset($_SESSION[$key][$key_arr]);
                            }
                        }
                    }
                }
                if (isset($_SESSION[$key])) {
                    return $_SESSION[$key];
                } else {
                    return null;
                }
            } else {
                foreach ($arr as $key_arr => $val) {
                    if ($before != '') {
                        if (stristr($key_arr, $before)) {
                            if ($val != '') {
                                $key_arr = str_replace($before, '', $key_arr);
                                if ($after != '' && $val != '') {
                                    $val = $after . $val;
                                }
                                $_SESSION[$key][$inside][$key_arr] = $val;

                            } else {
                                if ($delete != null) {
                                    $key_arr = str_replace($before, '', $key_arr);

                                    unset($_SESSION[$key][$inside][$key_arr]);

                                }
                            }

                        } else {
                        }
                    } else {
                        if ($val != '') {
                            if ($after != '' && $val != '') {
                                $val = $after . $val;
                            }
                            $_SESSION[$key][$inside][$key_arr] = $val;
                        } else {
                            if ($delete != null) {
                                unset($_SESSION[$key][$inside][$key_arr]);
                            }
                        }
                    }
                }
                if (isset($_SESSION[$key][$inside])) {
                    return $_SESSION[$key][$inside];
                } else {
                    return null;
                }
            }
        } else {
            return false;
        }

    }

    public function i_handler_ajax($data = null, $arr = null)
    {

        /*$arr = array('where'=>array('field'=>array('('%|!|=')name'=>'value'),'and'=>'and|or'),'count'=>int,'page'=>int,'sort'=>array('fields'=>'asc'));*/
        if ($data != null || $arr != null) {
            if (!is_array($data) && $data != null) {
                $arr_data = explode('|||', $data);
                foreach ($arr_data as $k => $v) {
                    $v_s = explode(':::', $v);
                    ${$v_s[0]} = $v_s[1];
                }
            } else if ($data == null && $arr != null) {

                foreach ($arr as $k => $v) {
                    ${$k} = $v;
                }
            } else {
                foreach ($data as $k => $v) {
                    ${$k} = $v;
                }
            }
            if (isset($create)) {
                $arr['create'] = $create;
            }
            if (isset($value_create)) {
                $arr['value_create'] = unserialize($value_create);
            }
            if (isset($interface)) {
                $arr['interface'] = $interface;
            }
            if (isset($model)) {
                $arr['model'] = $model;
            }
            if(isset($table) && $table != ''){
                $arr['table'] = $table;
            }
             if (isset($where)) {
                if(is_string($where) && $where!=''){
                    $data = @unserialize($where);
                }
                else{
                    $data = $where;
                }
                if ($data !== false && is_string($where)&& $where!='') {
                    $arr['where']['field'] = unserialize($where);
                } else {
                    $arr['where']['field'] = $where;
                }
            }
            if ((isset($pgn) || isset($page)) && isset($count)) {
                $arr['count'] = $count;
                $arr['page'] = $page;
            }
            if (isset($sort)) {
                $arr['sort'] = array($sort_f => $sort);
            }

            }
            else {
                $this->log('i_handler_ajax: $data(1) и $arr(2) не должны быть пустыми');
                return false;
            }
            return $arr;
        }
       
    


    public function i_load_ajax($html = null, $data = null, $arr_new = null)
    {
        if ($html != null && ($data != null || $arr_new != null)) {
            $arr = $this->i_handler_ajax($data, $arr_new);

            $i_arr = $this->i_arr($arr);

            if (isset($arr['table'])) {
                $value = $this->handlersql($arr['table'], $arr);

            } else {
                return false;
            }

            $fields = array();
            if (is_array($html)) {
                $html = implode("", $html);
            }
            preg_match_all("|\[([^\]]+)\]|U", $html, $out);

            if (isset($arr['create'])) {
                $htmls = $html;
                for ($it = 0; $it < count($out[1]); $it++) {

                    $res = explode('|', $out[1][$it]);
                    $arr_result = array();
                    $type = $res[1];
                    if (isset($arr['value_create'][$res[0]])) {
                        $arr_result['value'] = $arr['value_create'][$res[0]];
                    } else {
                        $arr_result['value'] = '';
                    }
                    $arr_result['name'] = $res[0];
                    $key = array_search($res[0], $i_arr['fields']);
                    foreach ($i_arr['other_f'] as $k_o => $v_o) {
                        $arr_result[$k_o] = $i_arr['other_f'][$k_o][$key];
                    }
                    if (count($arr_result) > 0) {
                        $arr_echo = array('fields' => $arr_result, 'model' => $arr['model'], 'table' => $arr['table']);

                        $htmls = str_replace('[' . $out[1][$it] . ']', $this->i_echo_type($type, $arr_echo), $htmls);

                    }

                }
                echo $htmls;

            } else {
                foreach ($value as $key_v => $value_item) {
                    $htmls = $html;
                    for ($it = 0; $it < count($out[1]); $it++) {

                        $res = explode('|', $out[1][$it]);
                        $arr_result = array();
                        $type = $res[1];

                        $arr_result['value'] = $value[$key_v][$res[0]];
                        $arr_result['name'] = $res[0];
                        $key = array_search($res[0], $i_arr['fields']);
                        foreach ($i_arr['other_f'] as $k_o => $v_o) {
                            $arr_result[$k_o] = $i_arr['other_f'][$k_o][$key];
                        }
                        if (count($arr_result) > 0) {
                            $arr_echo = array('fields' => $arr_result, 'model' => $arr['model'], 'table' => $arr['table']);

                            $htmls = str_replace('[' . $out[1][$it] . ']', $this->i_echo_type($type, $arr_echo), $htmls);

                        }

                    }
                    echo $htmls;

                }
            }

        } else {
            $this->log('i_load_ajax: $html(1) или $data(2) и $arr(3) не должны быть пустыми');
            return false;
        }
    }

    public function i_for_ajax($arr_type, $arr_field)
    {
        $html = array();
        foreach ($arr_field as $k => $v) {
            if (isset($arr_type[$k])) {
                $html[] = '[' . $v . '|' . $arr_type[$k] . ']';
            } else {
                $html[] = '[' . $v . '|ids]';
            }
        }
        return $html;

    }


    public function delete_mvc($dir = null, $name = null)
    {
        if ($dir == null || $name == null) {
            return 'Введите название директории и название шаблона';
        } else {
            $name_f = mb_strtolower($name, 'UTF-8');
            if (file_exists($GLOBALS['foton_setting']['path'] . '/app/controller/' . $dir . '/controller_' . $name_f . '.php')) {
                unlink($GLOBALS['foton_setting']['path'] . '/app/controller/' . $dir . '/controller_' . $name_f . '.php');
            }
            if (file_exists($GLOBALS['foton_setting']['path'] . '/app/model/' . $dir . '/model_' . $name_f . '.php')) {
                unlink($GLOBALS['foton_setting']['path'] . '/app/model/' . $dir . '/model_' . $name_f . '.php');
            }
            if (file_exists($GLOBALS['foton_setting']['path'] . '/app/view/' . $dir . '/' . $name_f . '_view.tpl')) {
                unlink($GLOBALS['foton_setting']['path'] . '/app/view/' . $dir . '/' . $name_f . '_view.tpl');
            }
            if (file_exists($GLOBALS['foton_setting']['path'] . '/system/api/tpl/' . $dir . '/' . $name_f . '_view.php')) {
                unlink($GLOBALS['foton_setting']['path'] . '/system/api/tpl/' . $dir . '/' . $name_f . '_view.php');
            }
        }
    }

    public function up_mvc($dir = null, $name = null)
    {
        if ($dir == null || $name == null) {
            return 'Введите название директории и название шаблона';
        } else {
            $name_f = mb_strtolower($name, 'UTF-8');
            $c = "<? class Controller_" . $name . " extends Model_" . $name . "\n{\n \n}";
            $m = "<?\nclass Model_" . $name . " extends Model{\n    public function nameinclude(){\n        return '" . $name . "';\n    }\n    public function Model_chmod(){\n        return '1,2';\n    }\n    /*public function interfaces(){\n        \$arr = array('" . $name . "_table'=>\n            array(\n            \"field\" => array(\"id\",\"name\",\"description\"),\n            \"name\" => array(\"id\",\"Название\",\"Таблица\"),\n            \"format_select\" => array(\"ids\",\"input\",\"textarea\"),\n            \"format\" => array(\"int\",\"text\",\"text\"),\n            \"key\" => \"id\"\n        )\n        );\n       return \$arr;\n    }*/\n}";
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/controller/' . $dir . '/controller_' . $name_f . '.php', $c);
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/model/' . $dir . '/model_' . $name_f . '.php', $m);
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $dir . '/' . $name_f . '_view.tpl', '');
            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/api/tpl/' . $dir . '/' . $name_f . '_view.php', '');
        }
    }

    public function changeArr($arr = null, $search = null, &$arr_new=null)
    {
        if ($search != null) {
            if (is_array($arr)) {
                foreach ($arr as $key => $val) {
                    if (is_string($val) && stristr($val, $search)) {
                        $arr_new[] = $val;
                    }
                    $this->changeArr($val, $search, $arr_new);
                }
            } else if (stristr($val, $search)) {
                $arr_new[] = $arr;
            } else {
            }
            return $arr_new;
        }
    }
    public function model_table($table=null,$dir=null){
        if($dir==null){
           $dir = $GLOBALS['foton_setting']['sitedir'];
        }
        if($table!=null){
           foreach (glob($this->git($GLOBALS['foton_setting']['path'] . "/app/model/" . $dir) . "/*.php") as $file) {
                $name = basename($file);
                $name_model = str_replace('.php', '', $name);
                require_once $this->fexists_foton($name, 'model');               
                $method = 'names';
                $obj_m = new $name_model;
                if (method_exists($name_model, $method)) {
                    $name_obj = $obj_m->$method();
                    if(isset($name_obj[$table])){
                        return $name_model;
                    }
                }
           }
        }
        else{
            $this->log('model_table: аргумент table не задан');
        }
        return false;
    }
    public function create_mvc($dir = null, $name = null, $json = false)
    {
        if ($dir == null || $name == null) {
            return 'Введите название директории и название шаблона';
        } else {
            $name_f = mb_strtolower($name, 'UTF-8');
            if (file_exists($GLOBALS['foton_setting']['path'] . '/app/controller/' . $dir . '/controller_' . $name_f . '.php')) {
                return 'Контроллер уже существует';
            }
            if (file_exists($GLOBALS['foton_setting']['path'] . '/app/model/' . $dir . '/model_' . $name_f . '.php')) {
                return 'Модель уже существует';
            }
            if (file_exists($GLOBALS['foton_setting']['path'] . '/app/view/' . $dir . '/' . $name_f . '_view.tpl') || file_exists($GLOBALS['foton_setting']['path'] . '/app/view/' . $dir . '/' . $name_f . '_view.php')) {
                return 'Представление уже существует';
            }
            $c = "<? class Controller_" . $name . " extends Model_" . $name . "\n{\n \n}";
            if ($json) {
                $m = "<?\nclass Model_" . $name . " extends Model{\n     public function nameinclude(){\n        return '" . $name . "';\n    }\n    public function Model_chmod(){\n        return [1,2];\n    }\n    public function interfaces(){\n        \$arr = \$this->core->m_json('" . $name . "','create');     return \$arr;\n    \n}\n public function names(){\n\$arr = \$this->core->m_json('" . $name . "','name'); \nreturn \$arr;\n}\n    public function Model_" . $name . "_alter_interface(){\n    \$arr = \$this->core->m_json('" . $name . "','alter'); return \$arr;}\n    public function Model_" . $name . "_drop_interface(){\n    \$arr = \$this->core->m_json('" . $name . "','delete');\n    return \$arr;\n    } \n}";
            } else {
                $m = "<?\nclass Model_" . $name . " extends Model{\n    public function nameinclude(){\n        return '" . $name . "';\n    }\n    public function Model_chmod(){\n        return [1,2];\n    }\n    /*public function interfaces(){\n        \$arr = array('" . $name . "_table'=>\n            array(\n            \"field\" => array(\"id\",\"name\",\"description\"),\n            \"name\" => array(\"id\",\"Название\",\"Таблица\"),\n            \"format_select\" => array(\"ids\",\"input\",\"textarea\"),\n            \"format\" => array(\"int\",\"text\",\"text\"),\n            \"key\" => \"id\"\n        )\n        );\n      return \$arr;\n    }*/\n}";
            }
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/controller/' . $dir . '/controller_' . $name_f . '.php', $c);
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/model/' . $dir . '/model_' . $name_f . '.php', $m);
            file_put_contents($GLOBALS['foton_setting']['path'] . '/app/view/' . $dir . '/' . $name_f . '_view.tpl', '');
            file_put_contents($GLOBALS['foton_setting']['path'] . '/system/api/tpl/' . $dir . '/' . $name_f . '_view.php', '');
        }
    }

    /*   $add = array('replace'=>array('name'=>'test'),'delete'=>array('title'),'add'=>array('field'=>array('name2'=>'test2'),'tpl'=>array('name2'=>'textarea'),'extra'=>array('lang'=>'new_text')));
    $sql = array('where'=>array('field'=>array('%name'=>'Фото'),'and'=>'and'),'count'=>5,'page'=>0,'sort'=>array('id'=>'ASC'));
    $face=array('model'=>'html','table'=>'menu','interfaces'=>'sp','extra_arr'=>array('name'=>'lang'),'fields_table'=>'pole','fields_type'=>'format_select','create'=>true,'create_value'=>array('field'=>'value'),'count'=>'pagination');
   */
    public function i_list($face = null, $sql = null, $add_arr = null)
    {
        if ($face != null) {
            $arr_face = $this->i_arr($face);

            if (empty($sql['count']) && isset($face['count'])) {
                $face_all = $this->i_arr_all($face['model'], $face['table'], $face['interface']);
                $sql['count'] = $face_all[$face['count']];
            }
            if (isset($face['create'])) {
                $arr_value = array();
                $arr = $this->field_table($face['table']);
                foreach ($arr as $key => $val) {
                    if (isset($face['create_value'][$val])) {
                        $arr_value[0][$val] = $face['create_value'][$val];
                    } else {
                        $arr_value[0][$val] = '';
                    }

                }
            } else {
                if ($sql != null) {
                    $arr_value = $this->handlersql($face['table'], $sql);
                } else {
                    $arr_value = $this->handlersql($face['table']);

                }
            }
            if ($add_arr != null) {
                if (isset($add_arr['replace'])) {
                    foreach ($add_arr['replace'] as $k_r => $v_r) {
                        foreach ($arr_value as $k_sql => $v_sql) {
                            $arr_value[$k_sql][$k_r] = $v_r;
                        }

                    }
                }
                $count_face = count($arr_face['fields']);
                $count = count($arr_value) - 1;
                if (isset($add_arr['add']['field'])) {
                    foreach ($add_arr['add']['field'] as $k_add => $v_add) {
                        for ($i = 0; $i <= $count; $i++) {
                            $arr_value[$i][$k_add] = $v_add;
                            $arr_face['fields'][$count_face] = $k_add;
                        }
                    }
                }

                if (isset($add_arr['add']['extra'])) {
                    foreach ($add_arr['add']['extra'] as $k_add => $v_add) {
                        $arr_face['other_f'][$k_add][$count_face] = $v_add;
                    }
                }
                if (isset($add_arr['add']['tpl'])) {
                    foreach ($add_arr['add']['tpl'] as $k_tpl => $v_tpl) {
                        $arr_face['type'][$count_face] = $v_tpl;
                    }
                }
                if (isset($add_arr['delete'])) {
                    foreach ($add_arr['delete'] as $del) {
                        foreach ($arr_face['fields'] as $k_d => $v_d) {
                            if ($v_d == $del) {
                                unset($arr_face['type'][$k_d]);
                                unset($arr_face['fields'][$k_d]);
                            }
                        }
                    }
                }

            }

            /*$arr = array('fields'=>array('name'=>'value'),'model'=>'model','table'=>'table');*/
            $arr = array();
            $arr['table'] = $arr_face['table'];
            $arr['model'] = $arr_face['model'];
            foreach ($arr_value as $k_val => $v_val) {
                foreach ($arr_face['fields'] as $key_f => $val_f) {
                    if(empty($v_val[$val_f])){
                        $v_val[$val_f]='';
                    }
                    $arr['fields']['value'] = $v_val[$val_f];
                    $arr['fields']['name'] = $val_f;
                    foreach ($arr_face['other_f'] as $k_o => $v_o) {
                        if (isset($v_o[$key_f])) {
                            $arr['fields'][$k_o] = $v_o[$key_f];
                        }
                    }

                    if (isset($arr_face['type'][$key_f])) {
                        $arr_result[$k_val][$key_f] = $this->i_echo_type($arr_face['type'][$key_f], $arr);
                    }                    
                }

            }
            if (isset($arr_result)) {
                return $arr_result;
            } else {
                $this->log('i_list:$arr_result не создан, нет типов данных');
            }
        } else {
            return false;
        }

    }

    public function getid($arr)
    {
        if (isset($arr['table']) && $arr['table'] != '' && isset($arr['id']) && $arr['id'] != '') {
            if (isset($arr['fields']) && $arr['fields'] != '') {
                $obj_data = $this->table($arr['table'], $arr['fields']);
            } else {
                $obj_data = $this->table($arr['table']);
            }
            $obj_data = $obj_data->where(array('id' => $arr['id']), 'and');
            if (isset($arr['error']) && count($obj_data->forq()) < 1) {
                $rout = new Router;
                return $rout->ErrorPage404();
            }
            if (isset($arr['format']) && $arr['format'] == 'O') {
                return $obj_data->q();
            } else if (isset($arr['format']) && $arr['format'] == 'E') {
                return $obj_data->eq();
            } else if (isset($arr['format']) && $arr['format'] == 'X') {
                if(!$this->xml){
                    return $this->log('Библиотека XMLWriter не подключена');
                }
                $arr_r = $obj_data->forq();                
                $this->xml->openMemory();
                $this->xml->startDocument('1.0', 'UTF-8');
                $this->xml->startElement('XML');
                if (is_array($arr_r)) {
                    $this->getXML($arr_r);
                }
                $this->xml->endElement();
                return $this->xml->outputMemory();
            } else if (isset($arr['format']) && $arr['format'] == 'J') {
                return json_encode($obj_data->forq());
            } else if (isset($arr['format']) && $arr['format'] == 'S') {
                return serialize($obj_data->forq());
            } else {
                return $obj_data->forq();
            }
        } else {
            return $this->log('getid: не указана таблица или id');
        }

    }

    public function getlist($arr)
    {
        if (isset($arr[0])) {
            $arr_sql = array();
            if (isset($arr['where']) && $arr['where'] != null) {
                $arr_sql['where']['field'] = $arr['where'];
            }
            if (isset($arr['fields']) && $arr['fields'] != null) {
                if (is_array($arr['fields'])) {
                    $arr_sql['fields'] = implode(',', $arr['fields']);
                } else {
                    $arr_sql['fields'] = $arr['fields'];
                }
            }
            if (isset($arr['group']) && $arr['group'] != null) {
                $arr_sql['group'] = $arr['group'];
            }
            if (isset($arr['logic']) && $arr['logic'] != null) {
                $arr_sql['where']['and'] = $arr['logic'];
            }
            if (isset($arr['sort']) && $arr['sort'] != null) {
                $arr_sql['sort'] = $arr['sort'];
            }
            if (isset($arr['count']) && $arr['count'] != null) {
                $arr_sql['count'] = $arr['count'];
            }
            if (isset($arr['page']) && $arr['page'] != null) {
                $arr_sql['page'] = $arr['page'];
            } else {
                $arr_sql['page'] = 0;
            }
            if (isset($arr['param']) && $arr['param'] != null) {
                $arr_sql['param'] = $arr['param'];
            }
            if (isset($arr['error']) && count($this->handlersql($arr[0], $arr_sql)) < 1)
            {
                $rout = new Router;
                return $rout->ErrorPage404();
            }
            if (isset($arr['format']) && $arr['format'] == 'O') {
                return $this->handlersql($arr[0], $arr_sql, 'O');
            } else if (isset($arr['format']) && $arr['format'] == 'E') {
                return $this->handlersql($arr[0], $arr_sql, 'E');
            } else if (isset($arr['format']) && $arr['format'] == 'X') {
                if(!$this->xml){
                    return $this->log('Библиотека XMLWriter не подключена');
                }
                $arr_r = $this->handlersql($arr[0], $arr_sql);
                $this->xml->openMemory();
                $this->xml->startDocument('1.0', 'UTF-8');
                $this->xml->startElement('XML');
                if (is_array($arr_r)) {
                    $this->getXML($arr_r);
                }
                $this->xml->endElement();
                return $this->xml->outputMemory();
            } else if (isset($arr['format']) && $arr['format'] == 'J') {
                return json_encode($this->handlersql($arr[0], $arr_sql));
            } else if (isset($arr['format']) && $arr['format'] == 'S') {
                return serialize($this->handlersql($arr[0], $arr_sql));
            } else {
                return $this->handlersql($arr[0], $arr_sql);
            }
        } else {
            $this->log('getlist: Укажите таблицу');
            return false;
        }
    }

    public function getXML($data)
    {

        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $key = 'key' . $key;
            }
            if (is_array($val)) {
                $this->xml->startElement($key);
                $this->getXML($val);
                $this->xml->endElement();
            } else {
                $this->xml->writeElement($key, $val);
            }
        }
    }

    /*$arr = array('where'=>array('field'=>array('('%|!|=')name'=>'value'),'and'=>'and|or'),'count'=>int,'page'=>int,'sort'=>array('fields'=>'asc'));*/
    public function handlersql($table = null, $arr = null, $format = null)
    {
        if ($table != null) {
            if ($arr != null) {
                if (isset($arr['fields']) && $arr['fields'] != '' && (empty($arr['group']) || $arr['group'] == '')) {
                    $obj_data = $this->table($table, $arr['fields']);
                } else if (isset($arr['group'])) {
                    if (is_array($arr['group'])) {
                        $arr['group'] = implode(',', $arr['group']);
                    }
                    $obj_data = $this->table($table, $arr['group']);
                } else {
                    $obj_data = $this->table($table);
                }
                if (isset($arr['where']) && $arr['where']['field'] != null) {
                    if (is_string($arr['where'])) {
                        $data = @unserialize($arr['where']);
                    } else {
                        $data = false;
                    }
                    if ($data !== false) {
                        $where = unserialize($arr['where']);
                    } else {
                        $where = $arr['where'];
                    }

                    if (empty($where['and'])) {
                        $where['and'] = ' AND ';
                    }
                    if (isset($where['and']) && $where['and'] == 'LOGIC') {
                        $this->db_query .= " WHERE " . $where['field'];
                        $obj_data = $this;
                    } else {
                        $obj_data = $obj_data->where($where['field'], $where['and']);
                    }

                }                
                if (isset($arr['group'])) {
                    $obj_data = $obj_data->group($arr['group']);
                }
                if (isset($arr['sort'])) {
                    foreach ($arr['sort'] as $key_s => $val_s) {
                        $obj_data = $obj_data->sorts($key_s, $val_s);
                    }
                }
                if (empty($arr['count'])) {
                    $arr['count'] = $GLOBALS['foton_setting']['max_list'];
                }
                if (empty($arr['page'])) {
                    $arr['page'] = 0;
                }
                if (isset($arr['param']) && $arr['param'] != null) {
                    $arr_p = $arr['param'];
                }
                else{
                    $arr_p = array();
                }
                if ($format == 'O') {                    
                    $obj_data = $obj_data->lim($arr['count'], $arr['page'] * $arr['count'])->q($arr_p);
                    
                } else if ($format == 'E') {
                    $obj_data = $obj_data->lim($arr['count'], $arr['page'] * $arr['count'])->eq($arr_p);
                    
                } else {
                    $obj_data = $obj_data->lim($arr['count'], $arr['page'] * $arr['count'])->forq(null,null,$arr_p);                    
                }
                return $obj_data;
            } else {
                if (isset($arr['param']) && $arr['param'] != null) {
                    $arr_p = $arr['param'];
                }
                else{
                    $arr_p = array();
                }
                if($format == 'O') {
                    return $this->table($table)->lim($GLOBALS['foton_setting']['max_list'], 0)->q($arr_p);
                } 
                else if ($format == 'E') {
                    return $this->table($table)->lim($GLOBALS['foton_setting']['max_list'], 0)->eq($arr_p);
                } 
                else {
                    return $this->table($table)->lim($GLOBALS['foton_setting']['max_list'], 0)->forq(null,null,$arr_p);                   
                }
            }
        } else {
            $this->log('handlersql: укажите таблицу');
            return false;
        }

    }


    public function i_arr_all($model = null, $table = null, $interface = null)
    {
        $model = mb_strtolower($model);
        if ($model != null) {
            $arr_result = array();
            if ($interface == null) {
                $method = 'interface_sp';
            } else if ($interface == 1) {
                $method = 'interfaces';
            } else {
                $method = 'interface_' . $interface;
            }
            $class_m = $this->i($model);
            if (!$class_m) {
                $this->log('i_arr_all: нет класса');
                exit();
            }
            $obj_m = new $class_m;
            if (method_exists($obj_m, $method)) {
                if ($table != null) {
                    $chmod = $table . '_chmod';
                    if (method_exists($obj_m, $chmod)) {
                        $chmod_m = $obj_m->$chmod();
                        if (in_array($_SESSION['chmod_id'], $chmod_m)) {
                            if (isset($obj_m->$method()[$table])) {
                                $arr_result = $obj_m->$method()[$table];
                            }

                        } else {
                            $arr_result = array();
                        }
                    } else {
                        if (isset($obj_m->$method()[$table])) {
                            $arr_result = $obj_m->$method()[$table];
                        }
                    }
                } else {
                    foreach ($obj_m->$method() as $name_table => $arr_table) {
                        $chmod = $name_table . '_chmod';
                        if (method_exists($obj_m, $method)) {
                            $chmod_m = $obj_m->$chmod();
                            if (in_array($_SESSION['chmod_id'], $chmod_m)) {
                                $arr_result[$name_table] = $arr_table;
                            }
                        } else {
                            $arr_result[$name_table] = $arr_table;
                        }
                    }

                }
                return $arr_result;
            } else {
                $this->log('i_arr_all: метод ' . $method . ' в ' . $class_m . ' не найден');
                return false;
            }
        } else {
            $this->log('i_arr_all: укажите модель');
            return false;
        }
    }

        /*$arr = array('fields'=>array('name'=>str,'value'=>str),'model'=>'model','table'=>'table');*/
    public function i_echo_type($type = null, $arr = null)
    {
        if ($type != null && $arr != null) {
            if (isset($arr['model']) && isset($arr['table'])) {
                $class_m = $this->i($arr['model']);
                $obj_m = new $class_m;
                $chmod = $arr['table'] . '_chmod';
                if (method_exists($class_m, $chmod)) {
                    $chmod_m = $obj_m->$chmod();
                    if (in_array($_SESSION['chmod_id'], $chmod_m)) {

                    } else {
                        return false;
                    }
                }
                $s_prev = 'before_select_' . $arr['table'];
                if (method_exists($obj_m, $s_prev)) {
                    $arr['fields']['value'] = $obj_m->$s_prev($arr['fields']['value'], $arr['fields']['name']);
                }
            }

            if(isset($GLOBALS["foton_setting"]["type"]) && $GLOBALS["foton_setting"]["type"]=='file'){
                    if(strrpos($type,':')){
                        $typearr = explode(':', $type);
                        $type = $typearr[0];
                        $arg = $typearr[1];
                    }
                    $tpl = $this->git($GLOBALS["foton_setting"]["path"].'/app/ajax/'.$GLOBALS["foton_setting"]["admindir"].'/type/'.$type.'.tpl');
                    $path = $this->git($GLOBALS["foton_setting"]["path"].'/app/ajax/'.$GLOBALS["foton_setting"]["admindir"].'/type/'.$type.'.php');
                    if(file_exists($tpl)){
                        $htmlred = file_get_contents($tpl);
                        $htmlred = html_entity_decode($htmlred);
                        $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);                       
                        foreach ($arr['fields'] as $key_f => $val_f) {
                            if ($key_f != 'value') {
                                $htmlred = str_replace('[[' . $key_f . ']]', $val_f, $htmlred);
                            } 
                            else {                                
                                $htmlred = str_replace('[[' . $key_f . ']]', '{{' . $key_f . '}}', $htmlred);
                                $value_var = $val_f;
                            }
                        }
                         $htmlred = preg_replace('#\[\[([^\]]+)\]\]#', '', $htmlred);
                        if(strrpos($value_var,'%')){
                            $htmlred = str_replace('{{value}}',$value_var, $htmlred);
                        }
                        else{
                            $htmlred = str_replace('{{value}}', urldecode($value_var), $htmlred);
                        }
                        $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                        if(file_exists($path)){
                            require_once $path;
                            $typename = 'Type_'.$type;
                            $obj = new $typename;
                            if(empty($arg)){
                                ${$type} = $obj->index();
                            }
                            else{
                                $arg=explode(',',$arg);
                                ${$type} = $obj->index(...array_values($arg));
                            }
                            return eval($htmlred);
                        }

                        return $htmlred;                      
                    }
                    else{
                        $this->error('Тип данных ' . $type . ' не существует');
                        exit();
                    }
                }
                else{
                    $f0 = $this->select_db('html', 'kod', $type, 'function');
                    $f = implode("", $f0);
                    if (isset($f0[0]) && ($f == '0' || $f == '')) {
                        $htmlred = $this->select_db('html', 'kod', $type, 'html');
                        if (count($htmlred) == 0) {
                            $this->error('Тип данных ' . $type . ' не существует');
                            exit();
                        }
                        $htmlred = implode("", $htmlred);
                        $htmlred = str_replace('&#039;', '"', $htmlred);
                        $htmlred = html_entity_decode($htmlred);
                        foreach ($arr['fields'] as $key_f => $val_f) {
                            if ($key_f != 'value') {
                                $htmlred = str_replace('[[' . $key_f . ']]', $val_f, $htmlred);
                            } else {
                                $htmlred = str_replace('[[' . $key_f . ']]', '{{' . $key_f . '}}', $htmlred);
                                $value_var = $val_f;
                            }

                        }
                        $htmlred = preg_replace('#\[\[([^\]]+)\]\]#', '', $htmlred);
                        if(strrpos($value_var,'%')){
                            $htmlred = str_replace('{{value}}',$value_var, $htmlred);
                        }
                        else{
                            $htmlred = str_replace('{{value}}', urldecode($value_var), $htmlred);
                        }
                        $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                        $htmlred = $this->htmlret($htmlred);
                        return $htmlred;
                    } else {
                        $pos = strripos($type, ':');
                        if ($pos === false) {
                            $htmlred = $this->select_db('html', 'kod', $type, 'function');
                            $htmlred = implode("", $htmlred);
                            $htmlred = html_entity_decode($htmlred);
                            $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                            foreach ($arr['fields'] as $key_f => $val_f) {
                                if ($key_f != 'value') {
                                    $htmlred = str_replace('[[' . $key_f . ']]', $this->no_slash($val_f), $htmlred);

                                } else {
                                    $htmlred = str_replace('[[' . $key_f . ']]', '{{' . $key_f . '}}', $htmlred);
                                    $value_var = $val_f;
                                }
                            }
                            $htmlred = preg_replace('#\[\[([^\]]+)\]\]#', '', $htmlred);
                            if(strrpos($value_var,'%')){
                                $htmlred = str_replace('{{value}}',$this->no_slash($value_var), $htmlred);
                            }
                            else{
                                $htmlred = str_replace('{{value}}', urldecode($this->no_slash($value_var)), $htmlred);
                            }
                        } else {
                            $type = explode(':', $type);
                            $func = $type[0];
                            $arg = $type[1];
                            $htmlred = $this->select_db('html', 'kod', $func, 'function');
                            if (count($htmlred) == 0) {
                                $this->error('Тип данных ' . $func . ' не существует');
                                exit();
                            }
                            $htmlred = implode("", $htmlred);
                            $htmlred = html_entity_decode($htmlred);
                            $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                            foreach ($arr['fields'] as $key_f => $val_f) {
                                if ($key_f != 'value') {
                                    $htmlred = str_replace('[[' . $key_f . ']]', $this->no_slash($val_f), $htmlred);

                                } else {
                                    $htmlred = str_replace('[[' . $key_f . ']]', '{{' . $key_f . '}}', $htmlred);
                                    $value_var = $val_f;
                                }
                            }
                            $htmlred = preg_replace('#\[\[([^\]]+)\]\]#', '', $htmlred);
                            if(strrpos($value_var,'%')){
                                $htmlred = str_replace('{{value}}',$this->no_slash($value_var), $htmlred);
                            }
                            else{
                                $htmlred = str_replace('{{value}}', urldecode($this->no_slash($value_var)), $htmlred);
                            }
                            $argn = $this->select_db('html', 'kod', $func, 'argument');
                            if (empty($argn[0])) {
                                $this->error('Тип данных ' . $func . ' не существует');
                                exit();
                            }
                            $argn = $argn[0];
                            if (strpos($arg, ',') === false) {
                                ${$argn} = $arg;
                            } else {
                                $argn_arr = explode(',', $argn);
                                $arg_arr = explode(',', $arg);
                                foreach ($argn_arr as $k_arg => $v_arg) {
                                    ${$v_arg} = $arg_arr[$k_arg];
                                }
                            }
                        }
                        $htmlred = htmlspecialchars_decode($htmlred, ENT_QUOTES);
                        return eval($this->htmlret($htmlred));

                    }
            }
        } else {
            $this->log('i_echo_type: не задан тип или массив параметров');
            return false;
        }
    }


    public function list_model($dir = null)
    {
        if ($dir != null) {
            $arr = array();
            foreach (glob($this->git($GLOBALS['foton_setting']['path'] . "/app/model/" . $dir) . "/*.php") as $file) {
                try{
                    $name = basename($file);
                    $name_model = str_replace('.php', '', $name);
                    require_once $this->fexists_foton($name, 'model');               
                    $method = 'names';
                    $obj_m = new $name_model;
                    // проверяем есть ли данный метод у этой модели, если есть подключаем
                    if (method_exists($name_model, $method)) {
                        $arr[] = $obj_m->$method();
                    }
                } catch (\Throwable $e) {
                    $this->log('Ошибка в файле '.$file.': '.$e->getMessage());
                    return array();
                }
            }
            return $arr;
        } else {
            $this->log('list_model_f: нет модели');
        }
    }

    public function i_alter($c_name = null, $table = null)
    {
        $path_tr = $this->git($GLOBALS['foton_setting']['path'].'/app/model/transaction');
        if(!file_exists($path_tr)){
            mkdir($path_tr);
        }      
        if ($table != null && $c_name != null) {
            $method2 = 'alter_interface';
            $class_m = $this->i($c_name);
            $obj_m = new $class_m;
            $arr_tr = array();
            if (method_exists($obj_m, $method2)) {
                $arr = $obj_m->$method2();
                if (isset($arr[$table]["alter"]) && isset($arr[$table]["type"])) {               
                    foreach($arr[$table]["alter"] as $old=>$new){
                        $type = $arr[$table]["type"][$new];
                        $type_sql = self::$type_arr[$type];
                        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {                            
                            try {
                                $type_old = $this->type_column($table,$old);
                                if($type_old){                                      
                                    $arr_tr[$table]["type"][$old] = $type_old;
                                }
                                $arr_tr[$table]["alter"] = array($new=>$old);                                
                                $this->db->exec("ALTER TABLE " . $table . " ALTER COLUMN " . $old . "  TYPE " . $type_sql . ";");
                                $this->db->exec("ALTER TABLE " . $table . " RENAME COLUMN " . $old . "  TO " . $new . ";");
                            } catch (\PDOException $e) {
                                $this->log('i_alter error:' . $e->getMessage() . '--' . (int)$e->getCode());
                            }
                        }
                        else{                           
                            try {
                                if ($GLOBALS['foton_setting']['sql'] == 'lite') {
                                    $arr_tr[$table]["type"][$old] = $arr[$table]["type"][$new];
                                }
                                else{
                                    $type_old = $this->type_column($table,$old);
                                    if($type_old){                                      
                                        $arr_tr[$table]["type"][$old] = $type_old;
                                    }
                                }                                
                                $arr_tr[$table]["alter"] = array($new=>$old);
                                $this->db->exec("ALTER TABLE `" . $table . "` CHANGE COLUMN `" . $old . "` `" . $new. "` " . $type_sql . ";");
                            } catch (\PDOException $e) {
                                $this->log('i_alter error:' . $e->getMessage() . '--' . (int)$e->getCode());
                            }
                        }
                   }
                } else if (isset($arr[$table]["type"])) {
                    foreach ($arr[$table]["type"] as $key => $field) {                        
                        if ($GLOBALS['foton_setting']['sql'] == 'pgsql') {
                            try {
                                $type_sql = self::$type_arr[$field];
                                $type_old = $this->type_column($table,$key);
                                if($type_old){                                      
                                    $arr_tr[$table]["type"][$key] = $type_old;
                                }
                                $this->db->exec("ALTER TABLE " . $table . " ALTER COLUMN " . $key . "  TYPE " . $type_sql . ";");
                            } catch (\PDOException $e) {
                                $this->log('i_alter error:' . $e->getMessage() . '--' . (int)$e->getCode());
                            }
                        } else {
                            try {
                                $type_sql = self::$type_arr[$field];
                                if ($GLOBALS['foton_setting']['sql'] == 'lite') {
                                    $arr_tr[$table]["type"][$key] = $field;
                                }
                                else{
                                    $type_old = $this->type_column($table,$key);
                                    if($type_old){                                      
                                        $arr_tr[$table]["type"][$key] = $type_old;
                                    }
                                }                                
                                $this->db->exec("ALTER TABLE `" . $table . "` CHANGE COLUMN `" . $key . "` `" . $key . "` " . $type_sql . ";");
                            } catch (\PDOException $e) {
                                $this->log('i_alter error:' . $e->getMessage() . '--' . (int)$e->getCode());
                            }
                        }
                    }
                } else {

                }
            }
            if(isset($arr_tr[$table]["type"]) && $GLOBALS['foton_setting']['sql'] != 'lite') {
                $content_tr = "<?php class Model_".$c_name."{ public function alter_interface(){ return json_decode('" . json_encode($arr_tr) . "');} } ?>";
                file_put_contents($path_tr . '/Model_' . $c_name . '-' .$table.'-'. date("Ymdsi") . '.php', $content_tr);
            }
            return true;
        } else {
            $this->log('i_alter: контроллер(1) и таблица(2)  не могут быть пустыми');

            return false;
        }
    }

    public function mUpdateField2($model = null, $table = null, $field = null)
    {
        $date = date("H_s_i_d_m_Y");
        if ($model == null || $table == null || $field == null) {
            return 'Укажите модель, таблицу и поля';
        } else {
            $field_arr = explode('%', $field);
            if (isset($field_arr[0]) && isset($field_arr[1]) && isset($field_arr[2])) {
                $f1 = $field_arr[0];
                $f3 = $field_arr[1];
                $f2 = $field_arr[2];
                $arr = $this->m_json($model, 'create');
                if (isset($arr[$table])) {
                    $fields = array_flip($arr[$table]['field']);
                    $arr_d = $this->m_json($model, 'delete');
                    $arr_df = array_flip($arr_d[$table]['field']);
                    if (isset($arr_df[$f1])) {
                        unset($arr_d[$table]['field'][$arr_df[$f1]]);
                        unset($arr_d[$table]['name'][$arr_df[$f1]]);
                    }
                    $arr_a = $this->m_json($model, 'alter');
                    if (isset($arr_a[$table]['alter'][0]) && $arr_d[$table]['alter'][0] == $f1) {
                        unset($arr_a[$table]['alter']);
                    }
                    $arr_a[$table]['alter'] = array($f1, $f1);
                    if (isset($arr_a[$table]['type'][$f1])) {
                        unset($arr_a[$table]['type']);
                    }
                    $arr_a[$table]['type'][$f1] = $f2;
                    foreach ($fields as $field) {
                        $arr[$table]['format_select'][$field] = $f3;
                        $arr[$table]['format'][$field] = $f2;
                        $arr[$table]['name'][$field] = $f1;
                    }
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json', json_encode($arr, true));
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_delete_' . $date . '.json', json_encode($arr_d, true));
                    file_put_contents($GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_alter_' . $date . '.json', json_encode($arr_a, true));
                    return 'Миграция создана' . $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $model . '_create_' . $date . '.json';
                } else {
                    return 'Таблица не найдена';
                }
            } else {
                return 'Укажите поля таблицы (название поля%формат вывода%формат хранения)';
            }

        }
    }

    public function mf_json($model = null, $status = null)
    {
        if ($model == null || $status == null) {
            return array();
        } else {
            $filepath = $GLOBALS['foton_setting']['path'] . '/system/migrations/' . $GLOBALS['foton_setting']['sitedir'] . '/';
            foreach (glob($filepath . $model . '_' . $status . '*.json') as $file) {
                $LastModified[] = filemtime($file);
                $FileName[filemtime($file)] = $file;
            }
            if (isset($LastModified) && is_array($LastModified)) {
                sort($LastModified);
                $lastIndex = $LastModified[count($LastModified) - 1];
                $LastMFile = $FileName[$lastIndex];
                return $LastMFile;
            } else {
                return false;
            }
        }
    }

    public function auth($login = null, $passw = null, $key1 = null, $key2 = null)
    {
        if (isset($_SESSION['login']) === FALSE) {
            require_once $GLOBALS['foton_setting']['path'] . '/system/admin-inc/key.php';
            if (isset($login) && $login != null) {
                $login = $this->abc09($login);
                if (isset($this->select_db('user_inc', 'login', $login, 'pass')[0])) {
                    $pass = $this->select_db('user_inc', 'login', $login, 'pass')[0];
                }
                if (isset($this->select_db('user_inc', 'login', $login, 'id')[0])) {
                    $chmod = $this->select_db('user_inc', 'login', $login, 'role')[0];
                }
            }
            if (isset($chmod)) {
                if (isset($this->select_db('role', 'id', $chmod, 'name')[0])) {
                    $chmod_id = $this->select_db('role', 'id', $chmod, 'name')[0];
                }
            }
            if (isset($key1) && $key1 == $date2 && $key1 != null && isset($pass) && $passw != null && $pass == md5($passw) && isset($chmod_id) && $chmod_id > 0) {
                $_SESSION['login2'] = $login;
                $_SESSION['chmod'] = $chmod;
                $_SESSION['chmod_id'] = $chmod_id;
                return 'Вы прошли 1 уровень авторизации';
            } else if (isset($_SESSION['login2']) && $key2 != null) {
                if (isset($key2) && $key2 == $date) {
                    $_SESSION['login'] = $_SESSION['login2'];
                    $_SESSION['multiplay'] = (int)$GLOBALS['foton_setting']['multiplay'] * (int)$this->ip_user();
                    return 'Вы прошли 2 уровень авторизации';
                } else {
                    session_destroy();
                    return 'Данные авторизации введены не верно';
                }
            } else {
                session_destroy();
                return 'Данные авторизации введены не верно';

            }
        } else {
            session_destroy();
            return 'Данные авторизации введены не верно';

        }
    }
    public function st_start()
    {
        $_SESSION['speed_test'] = time();
    }

    public function st_end()
    {
        $result = time() - (int)$_SESSION['speed_test'];
        unset($_SESSION['speed_test']);
        return $result;
    }


    public function s_mvc($path = null, $name = null, $dirw = null)
    {
        if ($path != null && $name != null && $dirw != null) {
            $dir = str_replace('/core', '', __DIR__);
            if ($path == 'view') {
                $path2 = $dirw;
                if ($dirw == $GLOBALS["foton_setting"]["sitedir"]) {

                    if (file_exists($this->git($dir . "/app/view/" . $path2 . "/" . $name . "_" . $path . ".tpl")) && !is_dir($this->git($dir . "/app/view/" . $path2 . "/" . $name . "_" . $path . ".tpl"))) {
                        $file = file_get_contents($this->git($dir . "/app/view/" . $path2 . "/" . $name . "_" . $path . ".tpl"));
                    } else if (file_exists($this->git($dir . "/app/view/" . $path2 . "/" . $name . "_" . $path . ".php")) && !is_dir($this->git($dir . "/app/view/" . $path2 . "/" . $name . "_" . $path . ".php"))) {
                        $file = file_get_contents($this->git($dir . "/app/view/" . $path2 . "/" . $name . "_" . $path . ".php"));
                    } else if (file_exists($this->git($dir . "/system/api/tpl/" . $path2 . "/" . $name . "_" . $path . ".php")) && !is_dir($this->git($dir . "/system/api/tpl/" . $path2 . "/" . $name . "_" . $path . ".php"))) {
                        $file = file_get_contents($this->git($dir . "/system/api/tpl/" . $path2 . "/" . $name . "_" . $path . ".php"));
                    } else {
                        $file = '';
                    }
                } else {
                    if (file_exists($this->git($dir . "/app/view/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".tpl")) && !is_dir($this->git($dir . "/app/view/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".tpl"))) {
                        $file = file_get_contents($this->git($dir . "/app/view/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".tpl"));
                    } else if (file_exists($this->git($dir . "/app/view/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".php")) && !is_dir($this->git($dir . "/app/view/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".php"))) {
                        $file = file_get_contents($this->git($dir . "/app/view/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".php"));
                    } else if (file_exists($this->git($dir . "/system/api/tpl/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".php")) && !is_dir($this->git($dir . "/system/api/tpl/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".php"))) {
                        $file = file_get_contents($this->git($dir . "/system/api/tpl/" . $GLOBALS['foton_setting']['admindir'] . "/" . $name . "_" . $path . ".php"));
                    } else {
                        $file = '';
                    }
                }
            } else {
                $path2 = $path;
                $f1 = $dir . "/app/" . $path2 . "/" . $dirw . "/" . $path . "_" . $name . ".php";

                if (file_exists($this->git($f1)) && !is_dir($this->git($f1))) {
                    $file = file_get_contents($this->git($f1));
                } else {
                    if (file_exists($this->git($dir . "/app/" . $path2 . "/" . $path . "_" . $name . ".php")) && !is_dir($this->git($dir . "/app/" . $path2 . "/" . $path . "_" . $name . ".php"))) {
                        $file = file_get_contents($this->git($dir . "/app/" . $path2 . "/" . $path . "_" . $name . ".php"));
                    }
                }
            }

            return $file;
        } else {
            $this->log('s_mvc: не заданы значения: (view|model|controller,название шаблона, директория)');
        }
    }

    public function chmod_section($table = null)
    {
        if ($table != null) {
            foreach (glob($this->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $GLOBALS['foton_setting']['sitedir']) . "/*.php") as $filename) {
                $model_file2 = $this->fexists_foton(basename($filename), 'model');
                $model_file = basename($filename);
                $model_name = str_replace('.php', '', $model_file);
                $change_r = $table . '_chmod';

                if (basename($filename) != 'model_interface.php') {
                    try{
                        require_once $model_file2;
                        if (method_exists($model_name, $change_r)) {
                            $ch = $model_name::$change_r();
                            if ($this->chmod_id($ch,false)) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    } catch (\Throwable $e) {
                        $this->log('Ошибка в файле '.$model_file2.': '.$e->getMessage());
                        return false;
                    }
                } else {
                    return true;
                }
            }

            foreach (glob($this->git($GLOBALS["foton_setting"]["path"] . "/app/model/" . $GLOBALS['foton_setting']['admindir']) . "/*.php") as $filename) {
                $model_file2 = $this->fexists_foton(basename($filename), 'model');
                $model_file = basename($filename);
                $model_name = str_replace('.php', '', $model_file);
                $change_r = $table . '_chmod';
                if (basename($filename) != 'model_interface.php') {
                    try{
                        require_once $model_file2;                    
                        if (method_exists($model_name, $change_r)) {
                            $ch = $model_name::$change_r();
                            if ($this->chmod_id($ch,false)) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    } catch (\Throwable $e) {
                        $this->log('Ошибка в файле '.$model_file2.': '.$e->getMessage());
                        return false;
                    }
                } else {
                    return true;
                }
            }
        }
    }


    public function include_files($file = null)
    {
        if ($file != null) {
            $filest = file_get_contents($this->git($GLOBALS["foton_setting"]["path"] . $file));
            $filest = str_replace('<', '&lt;', $filest);
            return $filest;
        }
    }


    public function fexists_foton($file = null, $mc = null)
    {
        if ($file != null && $mc != null) {
            if (file_exists($this->git($GLOBALS['foton_setting']['path'] . '/app/' . $mc . '/' . $GLOBALS['foton_setting']['admindir'] . '/' . $file))) {
                return $this->git($GLOBALS['foton_setting']['path'] . '/app/' . $mc . '/' . $GLOBALS['foton_setting']['admindir'] . '/' . $file);
            } else if (file_exists($this->git($GLOBALS['foton_setting']['path'] . '/app/' . $mc . '/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $file))) {
                return $this->git($GLOBALS['foton_setting']['path'] . '/app/' . $mc . '/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $file);
            } else if (file_exists($this->git($GLOBALS['foton_setting']['path'] . '/app/' . $mc . '/' . $file))) {
                return $this->git($GLOBALS['foton_setting']['path'] . '/app/' . $mc . '/' . $file);
            } else {
                return false;
            }
        }
    }

    public function update_core($connect = null)
    {
        $out = array();

        if ($curl = curl_init()) {
            $str_p = array();
            if (is_array($connect["date"])) {
                foreach ($connect["date"] as $k => $v) {
                    $str_p[] = $k . "=" . $v;
                }
            }
            $str_p_str = implode('&', $str_p);

            curl_setopt($curl, CURLOPT_URL, $connect['host']);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $str_p_str);
            curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

            $out = curl_exec($curl);

            curl_close($curl);
        }


        return $out;
    }


    public function up_core_conn()
    {
        $connect = array("core" => array("host" => "https://foton.name/up_core.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "core")),
            "m" => array("host" => "https://foton.name/up_core.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "m")),
            "p" => array("host" => "https://foton.name/up_core.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "p")),
            "c" => array("host" => "https://foton.name/up_core.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "c")),
            "v" => array("host" => "https://foton.name/up_core.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "v")),
            "r" => array("host" => "https://foton.name/up_core.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "r")),
            "l" => array("host" => "https://foton.name/up_core.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "l")),
            "a" => array("host" => "https://foton.name/up_core.ajaxsite", "method" => "post", "date" => array("key" => $GLOBALS["foton_setting"]["license"], "func" => "a")),
        );
        return $connect;
    }

    public function tpl($view, $dir = null)
    {
        if ($dir == null) {
            return $this->git(($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $view . '_view.php'));
        } else {
            return $this->git(($GLOBALS["foton_setting"]["path"] . '/system/api/tpl/' . $dir . '/' . $view . '_view.php'));
        }
    }
    
    public function cache_foton($view = null)
    {
        if ($view != null) {
            $view = preg_replace('#@@@([^@]+)@@@#', "<?=htmlspecialchars_decode(\$this->config->core->select_db('exfield','name','$1','excode')[0], ENT_QUOTES);?>", $view);
            $view = preg_replace('#%%%([^%]+)%%%#', "<?='/app/view/'.\$GLOBALS['foton_setting']['sitedir'].'/'.\$this->config->core->select_db('mediateka','kod','$1','photos')[0];?>", $view);
            $view = preg_replace('#@tpl\(([^\),]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/system/api/tpl/'.\$GLOBALS['foton_setting']['sitedir'].'/$1_view.php'));?>", $view);
            $view = preg_replace('#@tpl\(([^,]+),([^\)]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/system/api/tpl/'.$1.'/$2_view.php'));?>", $view);
            $view = preg_replace('#@rc\(([^\(]+)\)#', "<?require_once(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/'.\$GLOBALS['foton_setting']['sitedir'].'/$1.php'));?>", $view);
            $view = preg_replace('#@inc\(([^\),]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/'.\$GLOBALS['foton_setting']['sitedir'].'/$1_view.php'));?>", $view);
            $view = preg_replace('#@inc\(([^,]+),([^\)]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/'.$1.'/$2_view.php'));?>", $view);
            $view = str_replace('@else@', "<?}else{?>", $view);
            $view = str_replace('@:@', "<?}?>", $view);
            $view = str_replace('#{','<?',$view);
            $view = str_replace('}#','?>',$view);
            $view = preg_replace('#@if\[([^\]]+)\]#', "<?if($1){?>", $view);
            $view = preg_replace('#@elsif\[([^\]]+)\]#', "<?}else if($1){?>", $view);
            $view = preg_replace('#@for\[([^\]]+)\]#', "<?for($1){?>", $view);
            $view = preg_replace('#@for:\[([^\]]+)\]#', "<?foreach($1){?>", $view);
            $view = preg_replace('#@\[([^\]]+)\]#', "<?=\$$1;?>", $view);
            $view = preg_replace('#@\{\{([^\}]+)\}\}#', "@:::$1:::@", $view);
            preg_match_all('|\{\{([^\}]+)\}\}|U',
                $view,
                $api, PREG_PATTERN_ORDER);
            foreach ($api[1] as $apivar) {
                $apivar2 = explode('_', $apivar);
                $apivari = file_get_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $apivar2[0] . '/' . $apivar2[1] . '.php');
                $view = str_replace('{{' . $apivar2[0] . '_end}}', '<?} ' . $apivar2[0] . '();?>', $view);
                $view = preg_replace('#\{\{' . $apivar2[0] . '_end:([^\}]+)\}\}#', '<?} ' . $apivar2[0] . '($1); ?>', $view);
                $view = str_replace('{{' . $apivar . '}}', $apivari, $view);

            }
            $view = str_replace('@::@', "<?}}?>", $view);
            $view = str_replace('@:::@', "<?}}}?>", $view);
            $view = preg_replace('#@if\{([^\}]+)\}#', "<?if($1){?>", $view);
            $view = preg_replace('#@elsif\{([^\}]+)\}#', "<?}else if($1){?>", $view);
            $view = preg_replace('#@for\{([^\}]+)\}#', "<?for($1){?>", $view);
            $view = preg_replace('#@g\{([^\}]+)\}#', "<?=\$GLOBALS['foton_setting'][$1];?>", $view);
            $view = preg_replace('#@s\{([^\}]+)\}#', "<?=\$_SERVER[$1];?>", $view);
            $view = preg_replace('#@sn\{([^\}]+)\}#', "<?=\$_SESSION[$1];?>", $view);
            $view = preg_replace('#@r\{([^\}]+)\}#', "<?=\$_REQUEST[$1];?>", $view);
            $view = preg_replace('#@c\{([^\}]+)\}#', "<?=\$this->core->$1();?>", $view);
            $view = preg_replace('#@csrf\{([^\}]*)\}#', '<input type="hidden" name="csrf" class="csrf" value="<?=$this->core->csrf($1);?>">', $view);
            $view = preg_replace('#@for:\{([^\}]+)\}#', "<?foreach($1){?>", $view);
            $view = preg_replace('#@arr\{([^\}]+)\}#', "<?print_r($1);?>", $view);
            $view = preg_replace('#@\{([^\}]+)\}#', "<?=\$$1;?>", $view);
            $view = preg_replace('#@:::([^:]+):::@#', "{{\\1}}", $view);
            return $view;
        } else {
            $this->log('cache_foton(): не задано представление');
        }
    }

    public function a_cache_foton($view = null)
    {
        if ($view != null) {
            $view = preg_replace('#@tpl\(([^\),]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/system/api/tpl/'.\$GLOBALS['foton_setting']['admindir'].'/$1_view.php'));?>", $view);
            $view = preg_replace('#@tpl\(([^,]+),([^\)]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/system/api/tpl/'.$1.'/$2_view.php'));?>", $view);
            $view = preg_replace('#@inc\(([^\),]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/'.\$GLOBALS['foton_setting']['admindir'].'/$1_view.php'));?>", $view);
            $view = preg_replace('#@inc\(([^,]+),([^\)]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/'.$1.'/$2_view.php'));?>", $view);
            $view = preg_replace('#@rc\(([^\)]+)\)#', "<?require_once(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/'.\$GLOBALS['foton_setting']['admindir'].'/$1.php'));?>", $view);
            $view = str_replace('@else@', "<?}else{?>", $view);
            $view = str_replace('@:@', "<?}?>", $view);
            $view = preg_replace('#@if\[([^\]]+)\]#', "<?if($1){?>", $view);
            $view = preg_replace('#@elsif\[([^\]]+)\]#', "<?}else if($1){?>", $view);
            $view = preg_replace('#@for\[([^\]]+)\]#', "<?for($1){?>", $view);
            $view = preg_replace('#@for:\[([^\]]+)\]#', "<?foreach($1){?>", $view);
            $view = preg_replace('#@\[([^\]]+)\]#', "<?=\$$1;?>", $view);
            $view = str_replace('#{','<?',$view);
            $view = str_replace('}#','?>',$view);
            $view = preg_replace('#@\{\{([^\}]+)\}\}#', "@:::$1:::@", $view);
            preg_match_all('|\{\{([^\}]+)\}\}|U',
                $view,
                $api, PREG_PATTERN_ORDER);
            foreach ($api[1] as $apivar) {
                $apivar2 = explode('_', $apivar);
                $apivari = file_get_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $GLOBALS['foton_setting']['admindir'] . '/' . $apivar2[0] . '/' . $apivar2[1] . '.php');
                $view = str_replace('{{' . $apivar2[0] . '_end}}', '<?} ' . $apivar2[0] . '();?>', $view);
                $view = preg_replace('#\{\{' . $apivar2[0] . '_end:([^\}]+)\}\}#', '<?} ' . $apivar2[0] . '($1); ?>', $view);
                $view = str_replace('{{' . $apivar . '}}', $apivari, $view);

            }
            $view = str_replace('@::@', "<?}}?>", $view);
            $view = str_replace('@:::@', "<?}}}?>", $view);
            $view = preg_replace('#@if\{([^\}]+)\}#', "<?if($1){?>", $view);
            $view = preg_replace('#@elsif\{([^\}]+)\}#', "<?}else if($1){?>", $view);
            $view = preg_replace('#@for\{([^\}]+)\}#', "<?for($1){?>", $view);
            $view = preg_replace('#@g\{([^\}]+)\}#', "<?=\$GLOBALS['foton_setting'][$1];?>", $view);
            $view = preg_replace('#@s\{([^\}]+)\}#', "<?=\$_SERVER[$1];?>", $view);
            $view = preg_replace('#@sn\{([^\}]+)\}#', "<?=\$_SESSION[$1];?>", $view);
            $view = preg_replace('#@r\{([^\}]+)\}#', "<?=\$_REQUEST[$1];?>", $view);
            $view = preg_replace('#@c\{([^\}]+)\}#', "<?=\$this->core->$1();?>", $view);
            $view = preg_replace('#@for:\{([^\}]+)\}#', "<?foreach($1){?>", $view);
            $view = preg_replace('#@csrf\{([^\}]*)\}#', '<input type="hidden" name="csrf" class="csrf" value="<?=$this->core->csrf($1);?>">', $view);
            $view = preg_replace('#@arr\{([^\}]+)\}#', "<?print_r($1);?>", $view);
            $view = preg_replace('#@\{([^\}]+)\}#', "<?=\$$1;?>", $view);
            $view = preg_replace('#@:::([^:]+):::@#', "{{\\1}}", $view);
            return $view;
        } else {
            $this->log('a_cache_foton(): не задано представление');
        }
    }

    public function wiki($text,$method=array()){
        $text = preg_replace_callback('#\[([^<\]]+)<([^:\]]+):([^>\]]+)>([^\]]*)]#', function($matches) use ($method){
            $func = $matches[2];
            if(count($method)==0 || in_array($func,$method)){
	            return '<'.$matches[1].$this->$func($matches[3]). "{$matches[4]}>";
	        }
	        else{
	        	return false;
	        }
        }, $text);
        $text = preg_replace('#\[/([^\]]+)\]#','</$1>',$text);
        return $text;
    }

    public function extracod($cod = null)
    {
        if ($cod != null) {
            return htmlspecialchars_decode($this->select_db('exfield', 'name', $cod, 'excode')[0], ENT_QUOTES);
        } else {
            $this->log('extracod:не задан код поля');
        }
    }

    public function imgcod($cod = null)
    {
        if ($cod != null) {
            return '/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/' . $this->select_db('mediateka', 'kod', $cod, 'photos')[0];;
        } else {
            $this->log('imgcod:не задан код поля');
        }
    }

    public function text_resizes_foton($text = null, $n = null)
    {
        if ($text != null && $n != null) {
            $name2 = mb_substr($text, 0, $n, 'UTF-8');
            if ($text == $name2) {
                return $name2;
            } else {
                $name2 = preg_replace('# ([^ ]+)$#', '', $name2);
                return $name2 . '...';
            }
        } else {
            $this->log('text_resizes_foton():не задан текст или длина строки');
        }
    }

    public function mobile_foton()
    {
        $mobile_agent_array = array('ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            foreach ($mobile_agent_array as $value) {
                if (strpos($agent, $value) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
    public function smtpSend($mailTo, $subject, $message) {
        if(isset($GLOBALS['foton_setting']['smtp']) && isset($GLOBALS['foton_setting']['smtp']['port'])  && isset($GLOBALS['foton_setting']['smtp']['host'])  && isset($GLOBALS['foton_setting']['smtp']['login'])  && isset($GLOBALS['foton_setting']['smtp']['pass'])){
            if(isset($GLOBALS['foton_setting']['smtp']['charset'])){
                $smtp_charset = $GLOBALS['foton_setting']['smtp']['charset'];
            }
            else{
                $smtp_charset = "utf-8";
            }
            if($mailTo!=null && $subject!=null && $message!=null){
                $nameFrom = explode('@',$GLOBALS['foton_setting']['smtp']['login'])[0];
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=".$smtp_charset."\r\n";
                $headers .= "From: ".$nameFrom." <".$GLOBALS['foton_setting']['smtp']['login'].">\r\n"; 
                $contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
                $contentMail .= 'Subject: =?' . $smtp_charset . '?B?'  . base64_encode($subject) . "=?=\r\n";
                $contentMail .= $headers . "\r\n";
                $contentMail .= $message . "\r\n";        
                try {
                    if(!$socket = @fsockopen($GLOBALS['foton_setting']['smtp']['host'],$GLOBALS['foton_setting']['smtp']['port'], $errorNumber, $errorDescription, 30)){
                        throw new \Exception($errorNumber.".".$errorDescription);
                    }
                    if (!$this->_parseServer($socket, "220")){
                        throw new \Exception('Connection error');
                    }           
                    $server_name = $_SERVER["SERVER_NAME"];
                    fputs($socket, "HELO $server_name\r\n");
                    if (!$this->_parseServer($socket, "250")) {
                        fclose($socket);
                        throw new \Exception('Error of command sending: HELO');
                    }            
                    fputs($socket, "AUTH LOGIN\r\n");
                    if (!$this->_parseServer($socket, "334")) {
                        fclose($socket);
                        throw new \Exception('Autorization error');
                    }
                    fputs($socket, base64_encode($GLOBALS['foton_setting']['smtp']['login']) . "\r\n");
                    if (!$this->_parseServer($socket, "334")) {
                        fclose($socket);
                        throw new \Exception('Autorization error');
                    }            
                    fputs($socket, base64_encode($GLOBALS['foton_setting']['smtp']['pass']) . "\r\n");
                    if (!$this->_parseServer($socket, "235")) {
                        fclose($socket);
                        throw new \Exception('Autorization error');
                    }           
                    fputs($socket, "MAIL FROM: <".$GLOBALS['foton_setting']['smtp']['login'].">\r\n");
                    if (!$this->_parseServer($socket, "250")) {
                        fclose($socket);
                        throw new \Exception('Error of command sending: MAIL FROM');
                    }            
                    $mailTo = ltrim($mailTo, '<');
                    $mailTo = rtrim($mailTo, '>');
                    fputs($socket, "RCPT TO: <" . $mailTo . ">\r\n");     
                    if (!$this->_parseServer($socket, "250")) {
                        fclose($socket);
                        throw new \Exception('Error of command sending: RCPT TO');
                    }            
                    fputs($socket, "DATA\r\n");     
                    if (!$this->_parseServer($socket, "354")) {
                        fclose($socket);
                        throw new \Exception('Error of command sending: DATA');
                    }            
                    fputs($socket, $contentMail."\r\n.\r\n");
                    if (!$this->_parseServer($socket, "250")) {
                        fclose($socket);
                        throw new \Exception("E-mail didn't sent");
                    }            
                    fputs($socket, "QUIT\r\n");
                    fclose($socket);
                } catch (\Exception $e) {
                    return $e->getMessage();
                }
                return true;
            }
            else{
                $this->log("smtpSend: кажите сигнатуры mail,subject,message");
            }
        }
        else{
            $this->log("Укажите в файле config.php GLOBALS['smtp']=['host','port',login','pass']");
        }
    }    
    private function _parseServer($socket, $response) {
        while (@substr($responseServer, 3, 1) != ' ') {
            if (!($responseServer = fgets($socket, 256))) {
                return false;
            }
        }
        if (!(substr($responseServer, 0, 3) == $response)) {
            return false;
        }
        return true;        
    }

    public function mail_foton($from = null, $to = null, $subject = null, $message = null, $file = null)
    {
        if ($from != null && $to != null && $subject != null && $message != null) {
            if ($file != null) {
                $separator = "---"; // разделитель в письме
                $mailTo = $to; // кому
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "From: $from\nReply-To: $from\n"; // задаем от кого письмо
                $headers .= "Content-Type: multipart/mixed; boundary=\"$separator\""; // в заголовке указываем разделитель
                $bodyMail = "--$separator\n"; // начало тела письма, выводим разделитель
                $bodyMail .= "Content-Type:text/html; charset=\"utf-8\"\n"; // кодировка письма
                $bodyMail .= "Content-Transfer-Encoding: 7bit\n\n"; // задаем конвертацию письма
                $bodyMail .= $message . "\n"; // добавляем текст письма
                $bodyMail .= "--$separator\n";
                $fileRead = fopen($file, "r"); // открываем файл
                $contentFile = fread($fileRead, filesize($file)); // считываем его до конца
                fclose($fileRead); // закрываем файл
                $bodyMail .= "Content-Type: application/octet-stream; name==?utf-8?B?" . base64_encode(basename($file)) . "?=\n";
                $bodyMail .= "Content-Transfer-Encoding: base64\n"; // кодировка файла
                $bodyMail .= "Content-Disposition: attachment; filename==?utf-8?B?" . base64_encode(basename($file)) . "?=\n\n";
                $bodyMail .= chunk_split(base64_encode($contentFile)) . "\n"; // кодируем и прикрепляем файл
                $bodyMail .= "--" . $separator . "--\n";
                return mail($mailTo, $subject, $bodyMail, $headers); // отправка письма
            } else {
                if(isset($GLOBALS['foton_setting']['smtp'])){
                    $result =  $this->smtpSend($to, $subject, $message); 
                    if($result === true){
                        return true;
                    }else{
                        $this->log("Письмо не отправлено. Ошибка: " . $result);
                    }
                }
                else{
                    $headers = "Content-type: text/html; charset=utf-8 \r\n";
                    $headers .= "From: " . $subject . " <" . $from . ">\r\n";
                    return mail($to, $subject, $message, $headers);
                }
            }
        } else {
            $this->log('mail_foton(): сигнатура (от кого, кому, тема, сообщение, файл(путь)) не задана');
        }
    }


    public function glob_controller()
    {
        try{
            if ($this->isAuth() && !class_exists('\Controller_Globals')) {
                if (file_exists($this->git('app/controller/' . $GLOBALS['foton_setting']['admindir'] . '_globals.php'))) {
                    require_once $this->git('app/controller/' . $GLOBALS['foton_setting']['admindir'] . '_globals.php');                
                } else {
                    if (class_exists('\Controller_Globals')) {
                    } else {
                        eval("class Controller_Globals{};");
                    }
                }

            } else if (!class_exists('\Controller_Globals')) {
                if (file_exists($this->git('app/controller/' . $GLOBALS['foton_setting']['sitedir'] . '_globals.php'))) {
                    require_once $this->git('app/controller/' . $GLOBALS['foton_setting']['sitedir'] . '_globals.php');
                } else {
                    if (class_exists('\Controller_Globals')) {
                    } else {
                        eval("class Controller_Globals{};");
                    }
                }
            } else {

            }
        } catch (\Throwable $e) {
            $this->log('Ошибка в глобальном контроллере: '.$e->getMessage());
            return false;
        }
    }

    public function dir_delete_foton($path = null)
    {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file) {
                $this->dir_delete_foton(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        } else if (is_file($path) === true) {
            return unlink($path);
        }

        return false;
    }


    public function number_foton($num = null)
    {
        $num = preg_replace('#([^0-9]+)#', '', $num);
        return $num;
    }

    public function text_foton($num = null)
    {
        $num = preg_replace('#([^0-9A-Zа-яА-Яa-z ]+)#', '', $num);
        return $num;
    }

    public function input_foton($num = null)
    {
        $arr = array('<?', '?>', '<?php', '--');

        $num = preg_replace('#<([^>]+)>#', '', $num);
        foreach ($arr as $val) {
            $num = str_replace($val, '', $num);
        }
        $num = $this->html_foton($num);
        $num = preg_replace('#<([^>]+)>#', '', $num);
        foreach ($arr as $val) {
            $num = str_replace($val, '', $num);
        }
        return $num;
    }


    public function html_foton($text = null)
    {
        return htmlspecialchars_decode(htmlspecialchars_decode($text));

    }

    public function translit_base($text = null)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'y',

            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh',
            'ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Y', 'Я' => 'Ya', ' ' => '', '-' => '', '"' => '', ' ' => ''
        );
        return strtr($text, $converter);

    }
    public function tr($text = null)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'y',

            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh',
            'ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Y', 'Я' => 'Ya', ' ' => '', '-' => '', '"' => '', ' ' => ''
        );
        return strtr($text, $converter);

    }

        public function cache_seo($view='',$dir=null){
        if($dir==null){
            $dir = $GLOBALS['foton_setting']['sitedir'];
        }
        $view = preg_replace('#@tpl\(([^\),]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/system/api/tpl/".$dir."/$1_view.php'));?>", $view);
        $view = preg_replace('#@tpl\(([^,]+),([^\)]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/system/api/tpl/'.$1.'/$2_view.php'));?>", $view);
        $view = preg_replace('#@rc\(([^\(]+)\)#', "<?require_once(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/".$dir."/$1.php'));?>", $view);
        $view = preg_replace('#@inc\(([^\),]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/".$dir."/$1_view.php'));?>", $view);
        $view = preg_replace('#@inc\(([^,]+),([^\)]+)\)#', "<?include(\$this->core->git(\$GLOBALS['foton_setting']['path'].'/app/view/'.$1.'/$2_view.php'));?>", $view);
        $view = str_replace('@else@', "<?}else{?>", $view);
        $view = str_replace('@:@', "<?}?>", $view);
        $view = str_replace('#{','<?',$view);
        $view = str_replace('}#','?>',$view);
        $view = preg_replace('#@if\[([^\]]+)\]#', "<?if($1){?>", $view);
        $view = preg_replace('#@elsif\[([^\]]+)\]#', "<?}else if($1){?>", $view);
        $view = preg_replace('#@for\[([^\]]+)\]#', "<?for($1){?>", $view);
        $view = preg_replace('#@for:\[([^\]]+)\]#', "<?foreach($1){?>", $view);
        $view = preg_replace('#@\[([^\]]+)\]#', "<?=\$$1;?>", $view);
        preg_match_all('|\{\{([^\}]+)\}\}|U',
                $view,
                $api, PREG_PATTERN_ORDER);
        foreach ($api[1] as $apivar) {
            $apivar2 = explode('_', $apivar);
            $apivari = file_get_contents($GLOBALS["foton_setting"]["path"] . '/system/api/php/' . $dir . '/' . $apivar2[0] . '/' . $apivar2[1] . '.php');
            $view = str_replace('{{' . $apivar2[0] . '_end}}', '<?} ' . $apivar2[0] . '();?>', $view);
            $view = preg_replace('#\{\{' . $apivar2[0] . '_end:([^\}]+)\}\}#', '<?} ' . $apivar2[0] . '($1); ?>', $view);
            $view = str_replace('{{' . $apivar . '}}', $apivari, $view);

        }
        $view = str_replace('@::@', "<?}}?>", $view);
        $view = str_replace('@:::@', "<?}}}?>", $view);
        $view = preg_replace('#@if\{([^\}]+)\}#', "<?if($1){?>", $view);
        $view = preg_replace('#@elsif\{([^\}]+)\}#', "<?}else if($1){?>", $view);
        $view = preg_replace('#@for\{([^\}]+)\}#', "<?for($1){?>", $view);
        $view = preg_replace('#@for:\{([^\}]+)\}#', "<?foreach($1){?>", $view);
        $view = preg_replace('#@arr\{([^\}]+)\}#', "<?print_r($1);?>", $view);
        $view = preg_replace('#@\{([^\}]+)\}#', "<?=\$$1;?>", $view);
        return $view;
    }
        
    public function meta($view=null,$field=null,$data=array())
    {
        if($view!=null && $field!=null){
            if($this->isAuth() && file_exists($this->git($GLOBALS["foton_setting"]['path'].'/app/view/'.$GLOBALS["foton_setting"]['admindir'].'/seo/'.$field.'/'.$view.'.php'))){
                require_once $this->git($GLOBALS["foton_setting"]['path'].'/app/view/'.$GLOBALS["foton_setting"]['admindir'].'/seo/'.$field.'/'.$view.'.php');
            }
            else if(file_exists($this->git($GLOBALS["foton_setting"]['path'].'/app/view/'.$GLOBALS["foton_setting"]['sitedir'].'/seo/'.$field.'/'.$view.'.php'))){
                require_once $this->git($GLOBALS["foton_setting"]['path'].'/app/view/'.$GLOBALS["foton_setting"]['sitedir'].'/seo/'.$field.'/'.$view.'.php');
            }
            else{
                
            }
        }
    }

    public function tpl_front_css($arr = null)
    {
        if ($arr != null) {
            $arr = array_unique($arr);
            $css = '';
            if(isset($GLOBALS['foton_setting']['type']) && $GLOBALS['foton_setting']['type']=='file'){
                $path = $GLOBALS['foton_setting']['path'] . '/app/ajax/' . $GLOBALS['foton_setting']['admindir'] . '/type/css/';
            }
            else{
                $path = $GLOBALS['foton_setting']['path'] . '/app/ajax/' . $GLOBALS['foton_setting']['sitedir'] . '/css/';
            }
            foreach ($arr as $file) {
                $file = preg_replace('#:([^:]+)$#', '', $file);
                $css_f = $this->git($path . $file . '.css');
                if (file_exists($css_f)) {
                    $css .= file_get_contents($css_f);
                }
            }
            return $css;
        }
    }

    public function tpl_front_js($arr = null)
    {
        if ($arr != null) {
            $arr = array_unique($arr);
            $js = '';
            if(isset($GLOBALS['foton_setting']['type']) && $GLOBALS['foton_setting']['type']=='file'){
                $path = $GLOBALS['foton_setting']['path'] . '/app/ajax/' . $GLOBALS['foton_setting']['admindir'] . '/type/js/';
            }
            else{
                $path = $GLOBALS['foton_setting']['path'] . '/app/ajax/' . $GLOBALS['foton_setting']['sitedir'] . '/js/';
            }
            foreach ($arr as $file) {
                $file = preg_replace('#:([^:]+)$#', '', $file);
                $js_f = $this->git($path . $file . '.js');

                if (file_exists($js_f)) {
                    $js .= file_get_contents($js_f);
                }
            }
            return $js;
        }
    }

    public function i_front_css($i = null)
    {
        if ($i != null) {
            $css = '';            
            $css_f = $this->git($GLOBALS['foton_setting']['path'] . '/app/ajax/' . $GLOBALS['foton_setting']['admindir'] . '/css/' . $i . '.css');
            if (file_exists($css_f)) {
                $css = file_get_contents($css_f);
            }
            return $css;
        }
    }

    public function i_front_js($i = null)
    {
        if ($i != null) {
            $js = '';
            $js_f = $this->git($GLOBALS['foton_setting']['path'] . '/app/ajax/' . $GLOBALS['foton_setting']['admindir'] . '/js/' . $i . '.js');
            if (file_exists($js_f)) {
                $js = file_get_contents($js_f);
            }
            return $js;
        }
    }

    public final function m_method($path = null, $arg = null, $arr = null)
    {
        if ($path != null) {
            $class = '';
            $facade = $this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/facade.ini');
            if (file_exists($facade)) {
                $facadearr = parse_ini_file($facade);

                if (isset($facadearr[$path])) {
                    $path = $facadearr[$path];
                }
            }
            $arr_dir = explode('/', $path);
            $method = array_pop($arr_dir);
            $path_class = implode('\\', $arr_dir);
            $path_class = str_replace('/', '\\', $path_class);
            $path_to = str_replace('\\', '/', $path_class);
            try{
                require_once($this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/' . $path_to . '.php'));
            } catch (\Throwable $e) {
                $this->log('Ошибка в файле '.$path_to.': '.$e->getMessage());
                return false;
            }
            if ($path_class != '') {
                if ($arr != null) {
                    $obj = new $path_class($arr);
                } else {
                    $obj = new $path_class;
                }
                if ($arg == null) {
                    return call_user_func_array(array($obj, $method), array());
                } else {
                    if (is_string($arg)) {
                        $arg = explode(',', $arg);
                    }
                    return call_user_func_array(array($obj, $method), $arg);
                }

            } else {
                return false;
            }
        }

    }


    public final function m_class($path = null, $arr = null)
    {
        if ($path != null) {
            $class = '';
            $arr_dir = explode('/', $path);
            if(strpos($path,'/')){
                $dir = $arr_dir[0];
                $module = $arr_dir[1];
                $facade = $this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/'.$dir.'/facade.ini');
                if (file_exists($facade)) {
                    $facadearr = parse_ini_file($facade);
                    if (isset($facadearr[$module])) {
                        $path = $facadearr[$module];
                    }
                }
            }
            $path_class = implode('\\', $arr_dir);
            $path_class = str_replace('/', '\\', $path_class);
            try{
                require_once($this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/' . $path . '.php'));
            } catch (\Throwable $e) {
                $this->log('Ошибка в файле '.$path.': '.$e->getMessage());
                return false;
            }
            if ($path_class != '') {
                if ($arr != null) {
                    $obj = new $path_class(...$arr);
                } else {
                    $obj = new $path_class;
                }
                foreach (get_class_methods($path_class) as $method) {
                    ${$class}[$method] = $obj->$method();
                }
            } else {
                return false;
            }
            return ${$class};
        }

    }

    public final function m_name($path = null)
    {
        if ($path != null) {
            $class = '';
            $arr_dir = explode('/', $path);
            if(strpos($path,'/')){
                $dir = $arr_dir[0];
                $module = $arr_dir[1];
                $facade = $this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/'.$dir.'/facade.ini');
                if (file_exists($facade)) {
                    $facadearr = parse_ini_file($facade);
                    if (isset($facadearr[$module])) {
                        $path = $facadearr[$module];
                    }
                }
            }
            
            $path_class = implode('\\', $arr_dir);
            $path_class = str_replace('/', '\\', $path_class);
            try{
                require_once($this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/' . $path . '.php'));
            } catch (\Throwable $e) {
                $this->log('Ошибка в модуле '.$path.': '.$e->getMessage());
                return false;
            }
            if ($path_class != '') {
                return $path_class;
            } else {
                return false;
            }

        }

    }

    public final function m_obj($path = null, $arr = null)
    {
        if ($path != null) {
            $arr_dir = explode('/', $path);
            if(strpos($path,'/')){
                $dir = $arr_dir[0];
                $module = $arr_dir[1];
                $facade = $this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/'.$dir.'/facade.ini');
                if (file_exists($facade)) {
                    $facadearr = parse_ini_file($facade);
                    if (isset($facadearr[$module])) {
                        $path = $facadearr[$module];
                    }
                }
            }            
            $path_class = implode('\\', $arr_dir);
            $path_class = str_replace('/', '\\', $path_class);
            try{
                require_once($this->git($GLOBALS['foton_setting']['path'] . '/dev/modules/' . $path . '.php'));
            } catch (\Throwable $e) {
                $this->log('Ошибка в модуле '.$path.': '.$e->getMessage());
                return false;
            }
            if ($path_class != '') {
                if ($arr != null) {
                    $obj = new $path_class(...$arr);
                } else {
                    $obj = new $path_class;
                }
            } else {
                return false;
            }
            return $obj;
        }

    }

    public function sizefile($dir = null)
    {
        if ($dir != null) {

            $stat = 0;
            foreach (glob($GLOBALS["foton_setting"]["path"] . "/" . $dir . "/*.php") as $file) {
                $stat += filesize($file);
            }
            return round($stat / 1024, 2);
        }
    }

    public function sizefiles($source = null,$stat=0)
    {
        if ($source != null) {
            if ($handle = opendir($source)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        $path = $source . '/' . $file;
                        if (is_file($path)) {
                            $stat += filesize($path);                            
                        } elseif (is_dir($path)) {
                                $this->sizefiles($path, $stat);                            
                        }
                        else{

                        }
                    }
                }
                closedir($handle);
            }
        }
        return $stat;
    }

    public function chmod_id($arr = array(),$result=true)
    {
        if (isset($arr[0])) {
            if (isset($_SESSION['chmod_id']) && is_array($arr)) {
                if (in_array($_SESSION['chmod_id'], $arr)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if($result){
                    return false;
                }
            }
        }
    }

    public function list_files_glob()
    {
        $arr = array();

        foreach (glob($this->git($GLOBALS['foton_setting']['path'] . "/app/model/" . $GLOBALS['foton_setting']['sitedir']) . "/*.php") as $filename) {
            try{
                $name_file = basename($filename);
                $name_model = str_replace('.php', '', $name_file);
                $name_model2 = str_replace('model_', '', $name_model);
                if (!class_exists($name_model)) {
                    require_once $filename;                
                    $name_mod = new $name_model;
                    $method = "nameinclude";
                    $method_chmod = "names";
                    $tb = "interfaces";
                    if (method_exists($name_model, $tb)) {
                        $res = false;
                        if (is_array($name_mod->$tb()) && count($name_mod->$tb()) > 0) {
                            foreach ($name_mod->$tb() as $key => $val) {
                                $change = $key . "_chmod";
                                if (method_exists($name_model, $method_chmod)) {
                                    //определяем права раздела, если есть разрешение на редактирование подключаем
                                    if (method_exists($name_model, $change) && $this->chmod_id($name_mod->$change())) {
                                        $res = true;
                                    }
                                }

                            }
                        }
                    }

                    // проверяем есть ли данный метод у этой модели, если есть подключаем
                    if (method_exists($name_model, $method_chmod)) {
                        //определяем права раздела, если есть разрешение на редактирование подключаем
                        if ($res) {
                            $arr[$name_model2] = $name_mod->$method();
                        }
                    }

                } else {
                    $name_mod = new $name_model;
                    $method = "nameinclude";
                    $method_chmod = 'names';
                    $tb = "interfaces";
                    if (method_exists($name_model, $tb)) {
                        $res = false;
                        if (is_array($name_mod->$tb()) && count($name_mod->$tb()) > 0) {
                            foreach ($name_mod->$tb() as $key => $val) {
                                $change = $key . "_chmod";
                                if (method_exists($name_model, $method_chmod)) {
                                    //определяем права раздела, если есть разрешение на редактирование подключаем
                                    if (method_exists($name_model, $change) && $this->chmod_id($name_mod->$change())) {
                                        $res = true;
                                    }
                                }

                            }
                        }
                    }

                    // проверяем есть ли данный метод у этой модели, если есть подключаем
                    if (method_exists($name_model, $method_chmod)) {
                        //определяем права раздела, если есть разрешение на редактирование подключаем
                        if ($res) {
                            $arr[$name_model2] = $name_mod->$method();
                        }
                    }
                }
            } catch (\Throwable $e) {
                $this->log('Ошибка в модуле '.$filename.': '.$e->getMessage());
                
            }            
        }

        foreach (glob($this->git($GLOBALS['foton_setting']['path'] . "/app/model/" . $GLOBALS['foton_setting']['admindir']) . "/*.php") as $filename) {
            try{
                $name_file = basename($filename);
                $name_model = str_replace('.php', '', $name_file);
                $name_model2 = str_replace('model_', '', $name_model);
                if (!class_exists($name_model)) {
                    require_once $filename;
                    $name_mod = new $name_model;
                    $method = "nameinclude";
                    $method_chmod = 'names';
                    $tb = "interfaces";
                    if (method_exists($name_model, $tb)) {
                        $res = false;
                        if (is_array($name_mod->$tb()) && count($name_mod->$tb()) > 0) {
                            foreach ($name_mod->$tb() as $key => $val) {
                                $change = $key . "_chmod";
                                if (method_exists($name_model, $method_chmod)) {
                                    //определяем права раздела, если есть разрешение на редактирование подключаем
                                    if (method_exists($name_model, $change) && $this->chmod_id($name_mod->$change())) {
                                        $res = true;

                                    }
                                }

                            }
                        }
                    }

                    // проверяем есть ли данный метод у этой модели, если есть подключаем
                    if (method_exists($name_model, $method_chmod)) {
                        //определяем права раздела, если есть разрешение на редактирование подключаем
                        if ($res) {
                            $arr[$name_model2] = $name_mod->$method();
                        }
                    }
                } else {
                    $name_mod = new $name_model;
                    $method = "nameinclude";
                    $method_chmod = 'names';
                    $tb = "interfaces";
                    if (method_exists($name_model, $tb)) {
                        $res = false;
                        if (is_array($name_mod->$tb()) && count($name_mod->$tb()) > 0) {
                            foreach ($name_mod->$tb() as $key => $val) {
                                $change = $key . "_chmod";
                                if (method_exists($name_model, $method_chmod)) {
                                    //определяем права раздела, если есть разрешение на редактирование подключаем
                                    if (method_exists($name_model, $change) && $this->chmod_id($name_mod->$change())) {
                                        $res = true;

                                    }
                                }

                            }
                        }
                    }

                    // проверяем есть ли данный метод у этой модели, если есть подключаем
                    if (method_exists($name_model, $method_chmod)) {
                        //определяем права раздела, если есть разрешение на редактирование подключаем
                        if ($res) {
                            $arr[$name_model2] = $name_mod->$method();
                        }
                    }
                }
            } catch (\Throwable $e) {
                $this->log('Ошибка в модуле '.$filename.': '.$e->getMessage());
            }
        }

        return $arr;
    }

    public function list_model_glob($model = null)
    {
        if ($model != null) {
            $arr = array();
            try{
                if (file_exists($this->git($GLOBALS['foton_setting']['path'] . '/app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $model . '.php'))) {      
                    $name_model = '\model_' . $model;
                    if (!class_exists($name_model)) {
                        require_once $GLOBALS['foton_setting']['path'] . '/app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $model . '.php';
                        $method = 'names';
                        $name_model = '\model_' . $model;
                        $name_mod = new $name_model;
                        // проверяем есть ли данный метод у этой модели, если есть подключаем
                        if (method_exists($name_model, $method)) {
                            foreach ($name_mod->$method() as $key => $val) {
                                $change = $key . "_chmod";

                                if (method_exists($name_model, $change)) {
                                    //определяем права раздела, если есть разрешение на редактирование подключаем
                                    if (method_exists($name_model, $change) && $this->chmod_id($name_mod->$change())) {

                                        if (isset($arr_res) && is_array($arr_res) && in_array($val, $arr_res)) {
                                        } else {

                                            $arr_res[$key] = $val;
                                        }
                                    }
                                }
                            }
                        }
                        $arr[] = $arr_res;
                        return $arr;
                    } else {
                        $method = 'names';
                        $name_model = '\model_' . $model;
                        $name_mod = new $name_model;
                        // проверяем есть ли данный метод у этой модели, если есть подключаем
                        if (method_exists($name_model, $method)) {
                            foreach ($name_mod->$method() as $key => $val) {
                                $change = $key . "_chmod";

                                if (method_exists($name_model, $change)) {
                                    //определяем права раздела, если есть разрешение на редактирование подключаем
                                    if (method_exists($name_model, $change) && $this->chmod_id($name_mod->$change())) {

                                        if (isset($arr_res) && is_array($arr_res) && in_array($val, $arr_res)) {
                                        } else {

                                            $arr_res[$key] = $val;
                                        }
                                    }
                                }
                            }
                        }
                        $arr[] = $arr_res;
                        return $arr;
                    }
                } else {
                    $name_model = '\model_' . $model;
                    if (!class_exists($name_model)) {
                        require_once $this->git($GLOBALS['foton_setting']['path'] . '/app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $model . '.php');
                        $method = 'names';

                        $name_mod = new $name_model;
                        // проверяем есть ли данный метод у этой модели, если есть подключаем
                        if (method_exists($name_model, $method)) {
                            foreach ($name_mod->$method() as $key => $val) {
                                $change = $key . "_chmod";
                                if (method_exists($name_model, $change)) {
                                    //определяем права раздела, если есть разрешение на редактирование подключаем
                                    if (method_exists($name_model, $change) && $this->chmod_id($name_mod->$change())) {

                                        if (isset($arr_res) && is_array($arr_res) && in_array($val, $arr_res)) {
                                        } else {

                                            $arr_res[$key] = $val;
                                        }
                                    }
                                }
                            }
                        }
                        $arr[] = $arr_res;
                        return $arr;
                    } else {
                        $method = 'names';

                        $name_mod = new $name_model;
                        // проверяем есть ли данный метод у этой модели, если есть подключаем
                        if (method_exists($name_model, $method)) {
                            foreach ($name_mod->$method() as $key => $val) {
                                $change = $key . "_chmod";
                                if (method_exists($name_model, $change)) {
                                    //определяем права раздела, если есть разрешение на редактирование подключаем
                                    if (method_exists($name_model, $change) && $this->chmod_id($name_mod->$change())) {

                                        if (isset($arr_res) && is_array($arr_res) && in_array($val, $arr_res)) {
                                        } else {

                                            $arr_res[$key] = $val;
                                        }
                                    }
                                }
                            }
                        }
                        $arr[] = $arr_res;
                        return $arr;

                    }                
                }
            } catch (\Throwable $e) {
                $this->log('Ошибка в моделе '.$model.': '.$e->getMessage());
                return false;
            }
        }
    }

    public function list_mvc($dirs = null, $dirview = null)
    {
        if ($dirs != null) {
            $arr = array();
            if ($dirs == 'view') {
                $globsp = glob($this->git($GLOBALS["foton_setting"]["path"] . "/app/" . $dirs . "/" . $dirview) . "/*_view.tpl");
            } else {
                $globsp = glob($this->git($GLOBALS["foton_setting"]["path"] . "/app/" . $dirs . "/" . $dirview) . "/*.php");
            }
            foreach ($globsp as $filename) {
                $name_file = basename($filename);
                $name = str_replace('.php', '', $name_file);
                $name = str_replace('_view', '', $name);
                $name = str_replace('.tpl', '', $name);
                $name = str_replace('controller_', '', $name);
                $name = str_replace('model_', '', $name);
                $model = '\model_' . $name;
                $file_mod = '\model_' . $name . '.php';
                $method = "nameinclude";
                if ($this->isset_file($name)) {
                    $class = $this->require_class($name, $dirview);
                    if (method_exists($class, $method)) {
                        $arr[$name . ':' . $dirview] = $this->require_obj($name, $dirview)->$method();
                    }
                }
            }
            return $arr;
        }
    }

    public function isset_file($file = null)
    {
        if ((file_exists($this->git('app/model/' . $GLOBALS['foton_setting']['admindir'] . '/model_' . $file . '.php')) && file_exists($this->git('app/controller/' . $GLOBALS['foton_setting']['admindir'] . '/controller_' . $file . '.php'))) || (file_exists($this->git('app/model/' . $GLOBALS['foton_setting']['sitedir'] . '/model_' . $file . '.php')) && file_exists($this->git('app/controller/' . $GLOBALS['foton_setting']['sitedir'] . '/controller_' . $file . '.php')))) {
            return true;
        } else {
            return false;
        }
    }

    public function hs($bytes=0)
    {
        $type=array("bytes", "Kb", "Mb", "Gb", "Tb", "Pt", "Eb", "Zb", "Yb");
        $index=0;
        while($bytes>=1024)
        {
            $bytes/=1024;
            $index++;
        }
        return round($bytes,2)." ".$type[$index];
    }

    public function size($dir=__DIR__){
        $ds['free'] = $this->hs(disk_free_space($dir));
        $ds['all'] = $this->hs(disk_total_space($dir));
        $ds['used'] = $this->hs(disk_total_space($dir) - disk_free_space($dir));
        return $ds;
    }

    public function copy_dir($source = null, $dest = null, $over = 1)
    {
        if ($source != null && $dest != null) {
            if (!is_dir($dest)) {
                mkdir($dest);
            }

            if ($handle = opendir($source)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        $path = $source . '/' . $file;
                        if (is_file($path)) {
                            if (!is_file($dest . '/' . $file || $over)) {
                                if (!@copy($path, $dest . '/' . $file)) {
                                    echo "(" . $path . ") Ошибка записи";
                                }
                            }
                        } elseif (is_dir($path)) {
                            if (!is_dir($dest . '/' . $file)) {
                                mkdir($dest . '/' . $file);
                                $this->copy_dir($path, $dest . '/' . $file, $over);
                            }
                        }
                    }
                }
                closedir($handle);
            }
        }

    }

    //проверка формата страницы
    public function is_format($format = null, $get = 0)
    {
        if ($format != null) {
            if ($get == 0) {
                $url = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
            } else {
                $url = $_SERVER['REQUEST_URI'];
            }
            if (preg_match("/\." . $format . "$/", $url)) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->log('is_format: Не задан параметр формата');
        }
    }

    public function arr_sort($arr = null, $sort = 1)
    {
        if ($arr != null) {
            if ($sort == 1) {
                usort($arr, "arrs1");
            } else {
                usort($arr, "arrs2");
            }
            return $arr;
        } else {
            $this->log('arr_sort: не передан массив');
        }
    }
    public function arr_func($arr1=array(),$arr2=array(),$func='+'){
        foreach($arr1 as $key=>$val){ 
            if(empty($arr2[$key])){
                $arr2[$key]=0;
            }
            if(is_array($val)){
                $arr[$key] = $this->arr_func($val,$arr2[$key],$func);
            }
            else{
                if($func=='*')  $arr[$key] = $val*$arr2[$key];
                else if($func=='-')  $arr[$key] = $val-$arr2[$key];
                else if($func=='/')  $arr[$key] = $val/$arr2[$key];
                else  $arr[$key] = $val+$arr2[$key];
            }
        }
        return $arr;
    }
    public function arr_shift($arr_func = null, $item = null)
    {
        $ar = array();
        if ($arr_func != null && $item != null && is_array($arr_func)) {
            if ($item == 'ARRAY') {
                for ($is = 0; $is < count($arr_func); $is++) {
                    for ($i = 0; $i < count($arr_func); $i++) {
                        $i_res = $i + $is;
                        if ($i_res < count($arr_func)) {
                            $ar[$i_res] = $arr_func[$i];
                        } else {
                            $i_res2 = $i_res - count($arr_func);
                            $ar[$i_res2] = $arr_func[$i];
                        }
                    }
                }
            } else {
                for ($i = 0; $i < count($arr_func); $i++) {
                    $i_res = $i + $item;
                    if ($i_res < count($arr_func)) {
                        $ar[$i_res] = $arr_func[$i];
                    } else {
                        $i_res2 = $i_res - count($arr_func);
                        $ar[$i_res2] = $arr_func[$i];
                    }
                }
            }
            return $ar;
        } else {
            $this->log('arr_shift: первое значение не передано, или не является массивом');
        }
    }

    //сдвигает массив вперед, при $item='ARRAY' - выполняет перебор всех вариантов

    public function arr_sort_key($arr = null, $num = null, $sort = null)
    {
        if ($arr != null && is_array($arr) && count($arr) > 0) {
            $arr_new = array();
            foreach ($arr as $key => $val) {
                $key_num = strlen($key);
                $arr_x = 'arr_new' . $key_num;
                ${$arr_x}[] = array($key => $val);
            }
            if ($num == null) {
                $num = 20;
            }
            for ($it = 1; $it < $num; $it++) {
                $arr_x = 'arr_new' . $it;
                if (count(${$arr_x}) > 0) {
                    $arr_new[] = ${$arr_x};
                }
            }
            foreach ($arr_new as $arr2) {
                foreach ($arr2 as $arr3) {
                    $arr_new2[] = $arr3;
                }
            }
            if ($sort != null) {
                $arr_new = array_reverse($arr_new2);
            }
            foreach ($arr_new as $arr2) {
                foreach ($arr2 as $keyi => $vali) {
                    $arrs[$keyi] = $vali;
                }
            }
            return $arrs;
        } else {
            return $this->log('arr_sort_key: параметр массив отсутствует, либо пустой');
        }
    }

    //redirect 404

    private function arrs1($a, $b)
    {
        if (strlen($a) == strlen($b)) {
            return 0;
        }

        return (strlen($a) < strlen($b)) ? -1 : 1;
    }

    //$arr - массив, содержащий буквенные ключи, $num - максимальное количество ключей одинаковой длины,$sort - если не пусто, то массив сортируется по длине ключа от большего, иначе от меньшего к большему

    private function arrs2($a, $b)
    {
        if (strlen($a) == strlen($b)) {
            return 0;
        }

        return (strlen($a) < strlen($b)) ? 1 : -1;
    }
}

if (isset($GLOBALS['foton_setting']['version']) && $GLOBALS['foton_setting']['version'] == 'Core12') {
    class Core extends Main
    {
        use Core12;
    }
}
else if (file_exists($GLOBALS['foton_setting']['path'].'/dev/custom_core.php')) {
    require_once($GLOBALS['foton_setting']['path'].'/dev/custom_core.php');
    class Core extends Main
    {
        use Custom_core;
    }
}
else {
    class Core extends Main
    {
    }
}





