<?php
  class Tpl_taxonomy extends Model{
    public function serialize(){
        if($this->request->p['text']){
            $arr=array();
            $tb=explode('|||',$this->request->p['text']);
            $text1=explode('%%%',$tb[0]);
            $arr[$tb[1]]=array();
            foreach($text1 as $key=>$val){
                $val=explode(':::',$val);
                if(isset($val[1])){
                $arr[$tb[1]][$val[0]]=$val[1];
                }
            }
            return serialize($arr);
        }
    }
    public function taxonomy() {
        if(isset($this->request->p['table']) && isset($this->request->p['name']) && isset($this->request->p['val']) && isset($this->request->p['section'])){
        $table=$this->request->p['table'];
        $name=$this->request->p['name'];
        $val=$this->request->p['val'];
        $section=$this->request->p['section'];
            if (iconv_strlen($val)<120) {
              $return='';
               $arr0=$this->core->table($table,'number,tpl')->where(array('section'=>$section))->group('number,tpl')->forq();
                if(isset($arr0[0])){
                    foreach($arr0 as $k0=>$v0){
                       $arr_tpl = $this->core->table('taxonomy')->where(array('name'=>$arr0[$k0]['tpl']))->forq();
                       if($arr_tpl[0]['prefix']!='0'){
                         
                            $pr = str_replace('||taxonomy||','attr_name="'.$name.'" class="tx taxonomy'.$arr0[$k0]['number'].'"  number="'.$arr0[$k0]['number'].'"  attr-tb="'.$table.'" ',$arr_tpl[0]['prefix']);
                            $pr = str_replace('||name||','name="'.$name.'"',$pr);
                            $return.=$pr;
                       }
                       $arr = $this->core->table($table)->where(array('section'=>$section,'number'=>$arr0[$k0]['number']))->sorts('sorts','ASC')->forq();
                       foreach($arr as $k=>$v){
                        
                        $body = str_replace('||val||',$arr[$k]['value'].'" ids="'.$arr[$k]['id'],$arr_tpl[0]['body']);
                        $body = str_replace('||cat||',$arr[$k]['name'],$body);
                        $body = str_replace('||taxonomy||','attr_name="'.$name.'" class="tx taxonomy'.$arr0[$k0]['number'].'" number="'.$arr0[$k0]['number'].'" ids="'.$arr[$k]['id'].'"  attr-tb="'.$table.'" ',$body);
                        if($body!='0'){
                            $return.=$body;
                        }
                       }
                         $ids=$arr0[$k0]['number'];
                       if($arr_tpl[0]['suffix']!='0'){
                           $sf=str_replace('||taxonomy||','attr_name="'.$name.'" class="tx taxonomy'.$arr0[$k0]['number'].'" number="'.$arr0[$k0]['number'].'" ids="'.$arr[$k]['id'].'"  attr-tb="'.$table.'"',$arr_tpl[0]['suffix']);
                           $return.=$sf;
                       }
                       $return.="<div class='taxonomy_".$ids."'></div>";
                            
                   }
               }
               
                return htmlspecialchars_decode($return);
            }
            else{
         
            }
        }
    }
}