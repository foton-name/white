<?php   class Taxonomy{
        function __construct(){
        global $core; $this->core = $core;
    }
    
    public function echo_type($table,$value,$name,$number,$section,$tpl){
        $return='';
         $arr_tpl = $this->core->table('taxonomy')->where(array('name'=>$tpl))->forq();
         $arrs = $this->core->table($table)->where(array('section'=>$section,'number'=>$number))->sorts('sorts','ASC')->forq();
        if($arr_tpl[0]['prefix']!='0'){
            $pr = str_replace('||taxonomy||','attr_name="'.$name.'" class="tx taxonomy'.$number.'"  number="'.$number.'"  attr-tb="'.$table.'" ',$arr_tpl[0]['prefix']);
            $pr = str_replace('||name||','name="'.$name.'"',$pr);
            $pr = str_replace('||cat||',$arrs[0]['name'],$pr);
            $return.=$pr;
       }
       $arrs = $this->core->table($table)->where(array('section'=>$section,'number'=>$number))->sorts('sorts','ASC')->forq();
       foreach($arrs as $k=>$v){
        if($arrs[$k]['value']==$value){
        $body = str_replace('||val||',$value.'"  ids="'.$arrs[$k]['id'].'" selected checked ',$arr_tpl[0]['body']);
        $body = str_replace('||cat_s||',$value,$body);
        }
        else{
            if($arrs[$k]['value']!=''){
             $body = str_replace('||val||',$arrs[$k]['value'].'" ids="'.$arrs[$k]['id'],$arr_tpl[0]['body']);  
             $body = str_replace('||cat_s||',$arrs[$k]['value'],$body);
            }
            else{
          $body = str_replace('||val||',$value.'" ids="'.$arrs[$k]['id'],$arr_tpl[0]['body']);
          $body = str_replace('||cat_s||',$value,$body);
            }
           
        }
        $body = str_replace('||cat||',$arrs[$k]['name'],$body);
        
        $body = str_replace('||taxonomy||','attr_name="'.$name.'" class="tx taxonomy'.$number.'" number="'.$number.'" ids="'.$arrs[$k]['id'].'"  attr-tb="'.$table.'" ',$body);
        if($body!='0'){
            $return.=$body;
        }
       }
         $ids=$number;
       if($arr_tpl[0]['suffix']!='0'){
           $sf=str_replace('||taxonomy||','attr_name="'.$name.'" class="tx taxonomy'.$number.'" number="'.$number.'" ids="'.$arrs[$k]['id'].'"  attr-tb="'.$table.'"',$arr_tpl[0]['suffix']);
           $return.=$sf;
       }
       
       return $return;
    }
    public function taxonomy_recurs($table,$arr,$section,&$content,&$echo_to=1){
        $arr_ids=$this->core->table($table)->where(array('section'=>$section))->sorts('sorts','ASC')->forq();
        foreach($arr_ids as $id=>$val){
            $arr_child=$this->core->table($table)->where(array('section'=>$val['id']))->sorts('sorts','ASC')->forq();
            if(isset($arr[$val['id']])){
               $value = $arr[$val['id']];
               $content.=htmlspecialchars_decode($this->echo_type($table,$value,$val['name'],$val['number'],$val['section'],$val['tpl']));
           }
           else{
               if($val['tpl']=='Текст'){
                 $value = $val['value'];  
               }
               else{
                   $value = '';
               }
               
           }
            if(isset($arr_child) && isset($arr[$val['id']])){
                $content.="<div class='taxonomy_".$val['number']."'>";
                $content.=$this->taxonomy_recurs($table,$arr,$val['id'],$content,$echo);
                $content.='</div>';
            }  
             
            
            
        }

        
    }

  public function index($table=null,$name=null,$val=null,$section=0){

        if (iconv_strlen($val)<10) {
                $return='<div class="taxonomy_html">';
               $arr0=$this->core->table($table,'number,tpl')->where(array('section'=>$section))->group('number,tpl')->forq();
             
                foreach($arr0 as $k0=>$v0){
                    $return.=$this->echo_type($table,'',$name,$arr0[$k0]['number'],$section,$arr0[$k0]['tpl']);
                  
                   $return.="<div class='taxonomy_".$arr0[$k0]['number']."'></div>";
                        
               }
               $return.='</div><input type="hidden" name="'.$name.'" class="hid_taxonomy">';
               
                return htmlspecialchars_decode($return);
            }
            else{
        $arr = unserialize($val);
        $content='<div class="taxonomy_html">';
        $arr0=$this->core->table($table,'number')->where(array('section'=>0))->group('number')->forq();
          foreach($arr0 as $key=>$arr2){
              $arr_new = $this->core->table($table)->where(array('=number'=>$arr2['number'],'section'=>0))->forq();
              $isset = 'no';
              foreach($arr_new as $v_new){
                  if(isset($arr[$table][$v_new['id']])){
                     $isset = 'yes';
                  }
                  else{
                      $ids_new = $v_new['id'];
                  }
              }
              if($isset=='no' && isset($ids_new)){
                 $arr[$table][$ids_new] = ''; 
              }
          }
        foreach($arr as $table=>$ar){
            unset($ar['null']);
            $content.=$this->taxonomy_recurs($table,$ar,0,$content);
        }
        
                $content.="</div><input type='hidden' name='".$name."' class='hid_taxonomy' value='".$val."'>";
                return $content;
            }
  }
}