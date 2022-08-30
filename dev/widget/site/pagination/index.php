<?php
  class Pagination{
    function __construct(){
        global $core; $this->core = $core;
        $this->request = $this->core->request;
    }
    public function index($interface=null,$table=null,$kol=null) {
if($table!=null && $interface!=null && $this->core->is_table($table)){
$return='';

   if(isset($this->request->g['filtr_'.$table])){
       $where=array();
       foreach($this->request->g['filtr_'.$table] as $key=>$name){
           if($name!='' && $key!='filtr-include'){
           $where[] =' `'.$key.'` LIKE "%'.$name.'%"';
           }
       }
       $where_str=implode(' AND ',$where);
       if($where_str!=''){
       $sql='SELECT * FROM `'.$table.'` WHERE '.$where_str;
       }
       else{
           $sql='SELECT * FROM `'.$table.'`';
       }
     
   }
   else{
      $sql='SELECT * FROM `'.$table.'`'; 
   }
$sql1=$this->core->db->prepare($sql);
$sql1->execute();
$result=$sql1->fetchAll(\PDO::FETCH_ASSOC);
$a = count($result);
$return.='<div class="pgns">';
 $ms=ceil($a/$kol);
 $count=$ms+1;
 $next0=$ms;
 if(isset($ms2)){
 $ms52=$ms2;
 }
 if(isset($this->request->g['5'])){
$next=$this->request->g['5']+1;
$prev=$this->request->g['5']-1;
}
$return.='<a href="/'.$interface.'/'.$this->request->g['2'].'/'.$this->request->g['3'].'" class="pn pn1">В начало</a>';

if(isset($this->request->g['5']) && $this->request->g['5']>1 && $prev>0 && isset($prev)){
$return.='<a href="/'.$interface.'/'.$this->request->g['2'].'/'.$this->request->g['3'].'/pagin/'.$prev.'"  class="pn  pn1">Предыдущая</a>';
}
else{}

 for($iu=1;$iu<$count;$iu++){

if(isset($this->request->g['5']) && $this->request->g['5']==$iu ){ 
$return.='<span>'.$iu.'</span>';}


else{
if($iu>0){
$return.='<a href="/'.$interface.'/'.$this->request->g['2'].'/'.$this->request->g['3'].'/pagin/'.$iu.'">'.$iu.'</a>';}
}

}

if(isset($this->request->g['5'])){

$otd=$this->request->g['5'];
}
if(isset($otd) && $ms>$otd){
if($this->request->g['5'] && $this->request->g['5']>1 && isset($this->request->g['5'])){

$next=$this->request->g['5']+1;
if($next>0){
$return.='<a href="/'.$interface.'/'.$this->request->g['2'].'/'.$this->request->g['3'].'/pagin/'.$next.'"  class="pn  pn2">Следующая</a>';}
}
else{
if($ms>1){
$return.='<a href="/'.$interface.'/'.$this->request->g['2'].'/'.$this->request->g['3'].'/pagin/2"  class="pn  pn2">Следующая</a>';
}
}
}
if($next0>0){
$return.='<a href="/'.$interface.'/'.$this->request->g['2'].'/'.$this->request->g['3'].'/pagin/'.$next0.'" class="pn  pn2">Последняя</a>';
}

if(isset($this->request->g['5']) && $this->request->g['5']<10){
$leftp=($this->request->g['5']/10-1)*22;}


elseif(isset($this->request->g['5']) && $this->request->g['5']<100){
$leftp=($this->request->g['5']/10-3)*32;}


elseif(isset($this->request->g['5']) && $this->request->g['5']<1000){
$leftp=($this->request->g['5']/10-27)*42;}
$return.='</div>';
if(isset($leftp)){
$return.="<script>
$(document).ready(function(){
$('#phid2').css('left','-".$leftp."');}

);</script>"; 
}
return $return;
}
}


}