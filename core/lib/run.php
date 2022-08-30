<?php
  namespace Foton;

//ini_set('display_errors',0);
class Run extends Custom
{
    public $class;

    function __construct($d)
    {
		global $core;
        $this->event = new Event;
        $this->type = new Type;
        $this->c = $core;
        $this->d = $d;
    }

    public function __get($property)
    {
        if(isset($this->classf)){
            $this->props = $property;
        }
        else{
            $this->classf = $property;
        }
        return $this;
    }

     public function __call($name, $arguments)
    {
        $arr_error = false;
        $this->classfoton = 'Foton\\'.$this->classf;
        if (class_exists($this->classf)) {
            if(isset($this->props)){
                $props = $this->props;
                $classto = new $this->classf;
                $classto = $classto->$props;
                unset($this->props);
            }
            else{
                $classto = new $this->classf;
            }
            if(method_exists($classto, $name) && isset($GLOBALS["foton_setting"]["debug"])){
                $_SESSION['debug'][] = ['method_core'=>$name,'time'=>microtime(true)];
            }
            if (method_exists($classto, $name) && method_exists($this->type, $this->classf . $this->d . $name)) {
                $m_type = $this->classf . $this->d . $name;
                $arr_type = $this->type->$m_type();
                foreach ($arguments as $key => $arg) {
                    if (gettype($arg) !== "$arr_type[$key]") {
                        $arr_error .= $name . ' arg: ' . $key . ' ошибка типа - передан ' . gettype($arg) . ' требуется ' . $arr_type[$key];
                    }
                }
            }
        }
        else if (class_exists($this->classfoton)) {
            if(isset($this->props)){
                $props = $this->props;
                $classto = new $this->classfoton;
                $classto = $classto->$props;
                unset($this->props);
            }
            else{
                $classto = new $this->classfoton;
            }
            if(method_exists($classto, $name) && isset($GLOBALS["foton_setting"]["debug"])){
                $_SESSION['debug'][] = ['method_core'=>$name,'time'=>microtime(true)];
            }
            $this->classfs = str_replace('Foton\\','',$this->classfoton);
            if (method_exists($classto, $name) && method_exists($this->type, $this->classfs . $this->d . $name)) {
                $m_type = $this->classfs . $this->d . $name;
                $arr_type = $this->type->$m_type();
                foreach ($arguments as $key => $arg) {
                    if (gettype($arg) !== "$arr_type[$key]") {
                        $arr_error .= $name . ' arg: ' . $key . ' ошибка типа - передан ' . gettype($arg) . ' требуется ' . $arr_type[$key];
                    }
                }
            }
        }
        else{
             $arr_error .= 'error: core/lib/Run';
        }
        if ($arr_error) {
           $this->c->log($arr_error);
        } 
        else
        {
            if(isset($arguments) && $this->c->isAuth()){
                $this->c->log($arguments,'J');
            }
            if (method_exists($classto, $name) && method_exists($this->event, 'after' . $this->d . $this->classf . $this->d . $name) && !method_exists($this->event, 'before' . $this->d . $this->classf . $this->d . $name)) {
                $on = 'after' . $this->d . $this->classf . $this->d . $name;
                $return = $classto->$name(...array_values($arguments));
                return $this->event->$on($return);

            } 
            else if (method_exists($classto, $name) && method_exists($this->event, 'before' . $this->d . $this->classf . $this->d . $name)) {
                $on = 'before' . $this->d . $this->classf . $this->d . $name;
                $arguments = $this->event->$on(...array_values($arguments));
                if (method_exists($classto, $name) && method_exists($this->event, 'after' . $this->d . $this->classf . $this->d . $name)) {
                    $on = 'after' . $this->d . $this->classf . $this->d . $name;
                    $return = $classto->$name(...array_values($arguments));
                    return $this->event->$on($return);
                } else {
                    return $classto->$name(...array_values($arguments));
                }
            } else {
                return $classto->$name(...array_values($arguments));
            }
        }
    }
    
    function __destruct(){
        unset($this->classf);
        unset($this->props);
    }
}




class Test{
    public static $test_log;
    function __construct($type=array(),$value=array(),$return=false)
    {
        if(isset($type['input'])){
            $this->itype = $type['input'];
        }
        if(isset($value['input'])){
            $this->ivalue = $value['input'];
        }
        if(isset($type['output'])){
            $this->type = $type['output'];
        }
        if(isset($value['range'])){
            $this->ranges = $value['range'];
        }
        if(isset($value['output'])){
            $this->value = $value['output'];
        }
        if(isset($value['value'])){
            $this->values = $value['value'];
        }
        $this->result = $return;
        $this->coretest = new \Foton\Core;
    }
    public function __get($property)
    {
        $this->classf = $property;
        return $this;
    }
    protected function unit_method($class,$method,...$args)
    {
        $return = new \ReflectionMethod($class, $method);
        $return->setAccessible(true);
        $obj = new $class;
        $class_new = $return->isStatic() ? null : $obj;
        return $return->invoke($class_new, ...$args);
    }
    public function rand_test($value=null){
        if($value==null){
            return $this->coretest->rand();
        }
        else{
            if(strrpos($value,':')!==false){
                $arr_pos = explode(':',$value);
                if(isset($arr_pos[1])){
                    $start = $arr_pos[1];
                }
                if(isset($arr_pos[2])){
                    $end = $arr_pos[2];
                }
                if(isset($arr_pos[0]) && $arr_pos[0]=='0'){
                    $arr_f = '0123456789';
                }
                else if(isset($arr_pos[0]) && $arr_pos[0]=='Z'){
                    $arr_f = '^&*()$#@!';
                }
                else if(isset($arr_pos[0]) && $arr_pos[0]=='S'){
                    $arr_f = '-/\*}{)("\'';
                }
                else if(isset($arr_pos[0]) && $arr_pos[0]=='A'){
                    $arr_f = 'ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮйцукенгшщзхъфывапролджэячсмитьбю';
                }
                else if(isset($arr_pos[0]) && $arr_pos[0]=='T'){
                    $arr_f = 'ЙЦ УКЕН Г;ШЩ ЗХЪ!ФЫ ВАПР .ОЛД ЖЭ-;Я ЧС М"ИТЬБ, Юйцу!кенгшщз-хъф ыв а, п ро"л"дж эя ч см- "ит ь б ю", abc".de, fgh:ij, klnm"-opqrs, tuvwx, yzABC"DE, FGHIJK:LN, MOPQ R.ST, UVWXYZ';
                }
                else if(isset($arr_pos[0]) && $arr_pos[0]=='M'){
                    $format = 'M';
                    $arr_f = 'abcde-fgh0123456789ij-klnmopqrs-tuvwx-yzAB0123456789CDE-FGHIJKLN-MOP0123456789Q-RST-UVWXYZ';
                }
                else if(isset($arr_pos[0]) && $arr_pos[0]=='L'){
                    $arr_f = 'abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ';
                }
                else{
                    $arr_f = false;
                }
            }
            else{
                if($value=='0'){
                    $arr_f = '0123456789';
                }
                else if($value=='Z'){
                    $arr_f = '^&*()$#@!';
                }
                else if($value=='S'){
                    $arr_f = '-/\*}{)("\'';
                }
                else if($value=='A'){
                    $arr_f = 'ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮйцукенгшщзхъфывапролджэячсмитьбю';
                }
                else if($value=='T'){
                    $arr_f = 'ЙЦ УКЕН Г;ШЩ ЗХЪ!ФЫ ВАПР .ОЛД ЖЭ-;Я ЧС М"ИТЬБ, Юйцу!кенгшщз-хъф ыв а, п ро"л"дж эя ч см- "ит ь б ю", abc".de, fgh:ij, klnm"-opqrs, tuvwx, yzABC"DE, FGHIJK:LN, MOPQ R.ST, UVWXYZ';
                }
                else if($value=='M'){
                    $format = 'M';
                    $arr_f = 'abcde-fgh0123456789ij-klnmopqrs-tuvwx-yzAB0123456789CDE-FGHIJKLN-MOP0123456789Q-RST-UVWXYZ';
                }
                else if($value=='L'){
                    $arr_f = 'abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ';
                }
                else{
                    $arr_f = false;
                }
            }
            if(isset($start) && isset($end) && $arr_f){
                if(isset($format) && $format=='M'){
                    return $this->coretest->rand(mt_rand($start,$end),$arr_f).'@mail.ru';
                }
                else{
                    return $this->coretest->rand(mt_rand($start,$end),$arr_f);
                }
                 
            }
            else if(isset($start) && $arr_f){
                if(isset($format) && $format=='M'){
                    return $this->coretest->rand($start,$arr_f).'@mail.ru';
                }
                else{
                    return $this->coretest->rand($start,$arr_f);
                }
            }
            else if(isset($start)){
                return $this->coretest->rand($start);
            }
            else if($arr_f){
                if(isset($format) && $format=='M'){
                    return $this->coretest->rand(10,$arr_f).'@mail.ru';
                }
                else{
                    return $this->coretest->rand(10,$arr_f);
                }
            }
            else{
                return $this->coretest->rand();
            }
            
        }
    }
    public function __call($name, $arguments)
    {
        $this->times = hrtime()[1];
        $classfto = 'Foton\\'.$this->classf;
        $classto = new $classfto;
        $arr_error = false;
        $arr_res = array();
        $type_error=false;
        if(isset($this->ranges) && is_array($this->ranges) && is_array($this->ranges) && count($this->ranges)>0){
            foreach($this->ranges as $key_r=>$range){                   
                $arguments[$key_r] = range(...$range);
            }
        }
        if(isset($this->values) && is_array($this->values) && is_array($this->values) && count($this->values)>0){
            foreach($this->values as $key_r=>$values){
                $arguments[$key_r] = $this->rand_test($values);
            }
        }
        if(isset($this->itype) && is_array($this->itype) && isset($arguments) && is_array($arguments) && count($arguments)>0){
            foreach($this->itype as $key_type=>$type){    
                if(isset($arguments[$key_type]) && gettype($arguments[$key_type])!==$type){
                  $arr_res[$name]['input'][$key_type.':'.gettype($arguments[$key_type])] = 'no';
                  $type_error=true;
                }
            }
        }
        if(isset($this->ivalue) && is_array($this->ivalue) && isset($arguments) && is_array($arguments) && count($arguments)>0){
            foreach($this->ivalue as $key_method=>$pattern){  
                if(isset($arguments[$key_method]))
                {
                    $result = $arguments[$key_method];                 
                    $value = substr($pattern, 1);                       
                    if($pattern[0]=='<'){
                        if((int)$result<(int)$value){
                            $arr_res[$name]['input_value'][$key_method] = 'yes';
                        }
                        else{
                            $arr_res[$name]['input_value'][$key_method] = 'no';
                        }
                    }
                    else if($pattern[0]=='>'){
                        if((int)$result>(int)$value){
                           $arr_res[$name]['input_value'][$key_method] = 'yes';
                        }
                        else{
                            $arr_res[$name]['input_value'][$key_method] = 'no';
                        }
                    }
                    else if($pattern[0]=='!'){
                        if($result!=$value){
                            $arr_res[$name]['input_value'][$key_method] = 'yes';
                        }
                        else{
                            $arr_res[$name]['input_value'][$key_method] = 'no';
                        }
                    }
                    else if($pattern[0]=='='){
                        if($result==$value){
                            $arr_res[$name]['input_value'][$key_method] = 'yes';
                        }
                        else{
                            $arr_res[$name]['input_value'][$key_method] = 'no';
                        }
                    }
                    else if($pattern[0]=='%'){
                        if(strrpos($result,$value)!==false){
                            $arr_res[$name]['input_value'][$key_method] = 'yes';
                        }
                        else{
                            $arr_res[$name]['input_value'][$key_method] = 'no';
                        }
                    }
                    else{

                    }
                }
            }
        }
        if(method_exists($classto,$name)){            
            if(is_callable(array($classto,$name))){
                try {
                    $return = $classto->$name(...array_values($arguments)); 
                } catch (\Throwable $e) {
                   $arr_res[$name]['error'] = 'error: '.$e->getMessage();
                   
                }
            }
            else{
                try {
                    $return = $this->unit_method($classto,$name,...$arguments);
                } catch (\Throwable $e) {
                   $arr_res[$name]['error'] = 'error: '.$e->getMessage();
                 
                }
            }
            
            if($this->result  && isset($return)){
                $arr_res[$name]['return'] = $return;
            }
            if(!isset($return)){
                $arr_res[$name]['time'] = hrtime()[1] - $this->times;
                self::$test_log[] = $arr_res;
                return false;
            }
            if(isset($this->type) && is_array($this->type)){
                foreach($this->type as $key_type=>$type){
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
                        $arr_res[$name]['type'][$key_type.':'.gettype($result)] = 'no';
                    }
                    else{
                        $arr_res[$name]['type'][$key_type.':'.gettype($result)] = 'yes';
                    }
                }
            }
            if(isset($this->value) && is_array($this->value)){
                    foreach($this->value as $key_method=>$pattern){
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
                                $arr_res[$name]['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res[$name]['result'][$key_method] = 'no';
                            }
                        }
                        else if($pattern[0]=='>'){
                            if((int)$result>(int)$value){
                               $arr_res[$name]['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res[$name]['result'][$key_method] = 'no';
                            }
                        }
                        else if($pattern[0]=='!'){
                            if($result!=$value){
                                $arr_res[$name]['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res[$name]['result'][$key_method] = 'no';
                            }
                        }
                        else if($pattern[0]=='='){
                            if($result==$value){
                                $arr_res[$name]['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res[$name]['result'][$key_method] = 'no';
                            }
                        }
                        else if($pattern[0]=='%'){
                            if(strrpos($result,$value)!==false){
                                $arr_res[$name]['result'][$key_method] = 'yes';
                            }
                            else{
                                $arr_res[$name]['result'][$key_method] = 'no';
                            }
                        }
                        else{
    
                        }
                    }
            }
            $arr_res[$name]['time'] = hrtime()[1] - $this->times;
            self::$test_log[] = $arr_res;
            return $return;
        }
        else{
            return 'error method:'.$method.' in class '.$class;
        }
    }
    public function test_log(){
        echo '<pre style="background:#eee">';
        print_r(self::$test_log);
        echo '</pre>';
        self::$test_log = [];
    }
    public function test_return($debag=false){
        $return = self::$test_log;
        if(!$debag){
            self::$test_log = [];
        }
        return $return;
    }
    public function clear_test(){
        if(isset($this->itype)){
            unset($this->itype);
        }
        if(isset($this->ivalue)){
            unset($this->ivalue);
        }
        if(isset($this->type)){
            unset($this->type);
        }
        if(isset($this->ranges)){
            unset($this->ranges);
        }
        if(isset($this->value)){
            unset($this->value);
        }
    }

}