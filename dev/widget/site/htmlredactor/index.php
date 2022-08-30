<?php
  class Htmlredactor {
        function __construct(){
        global $core; $this->core = $core;
        $this->request = $this->core->request;
    }
    public function index($value=null,$name=null) { 
 if($name!=null){
        $rand=rand(0,2000);
        $value=htmlspecialchars_decode($value, ENT_COMPAT);
       
        $rand2=rand(200,500000);
        $value=str_replace("'",'"',$value);
        $value=htmlspecialchars_decode($value);
        $ret='';
       
        $ret.="<div class='but_html'>";
        
        $result25 = $this->core->db->query("SELECT * FROM  buttonred ");  foreach($result25 as $row) {
        $func = $row['func'];$lists = $row['lists'];$text = $row['text']; $format = $row['format'];$lists2=explode(',',$lists);
        if($format=='button'){
            foreach($lists2 as $valt=>$vals){
                $ret.="<input type='button' class='htmlinnerbut' value='".$vals."' onclick='".$func.$valt.$rand2."()' />";
            }
        }
        else if($format=='select'){
            $ret.="<select  class='htmlinnerbut'  onchange='".$func.$rand2."(this.options[this.selectedIndex].value)'><option>".$func."</option>";
            foreach($lists2 as $valt=>$vals){
                $ret.="<option value='".$vals."' >".$vals."</option>";
            }
            $ret.='</select>';
        }
        else if($format=='link'){
            $ret.="<input type='button'  class='htmlinnerbut'  onclick='".$func.$rand2."($(\".href".$func.$rand2."\").val());' value='".$func."'>";
            $ret.='<input type="text" class="linkhref href'.$func.$rand2.'" value="'.$lists.'">';
        }
        else{
            foreach($lists2 as $valt=>$vals){
                $ret.="<input type='button' class='htmlinnerbut' value='".$vals."' onclick='".$func.$valt.$rand2."()' />";
            }
        }
    }
         
        $ret.="</div>";
        $ret.="<div id='".$name.$rand."'></div>";
        $ret.='<script>
        
        var iDiv'.$rand.'=document.getElementById("'.$name.$rand.'");
        iDiv'.$rand.'.innerHTML= "<iframe  frameborder=\'no\' src=\'#\' class=\'ifrs\' id=\"'.$name.$rand2.'\" name=\"'.$name.$rand2.'\"></iframe><br/>";
        var isGecko'.$rand.' = navigator.userAgent.toLowerCase().indexOf("gecko") != -1;
        var iframe'.$rand.' = (isGecko'.$rand.') ? document.getElementById("'.$name.$rand2.'") : frames["'.$name.$rand2.'"];
        var iWin'.$rand.' = (isGecko'.$rand.') ? iframe'.$rand.'.contentWindow : iframe'.$rand.'.window;
        var iDoc'.$rand.' = (isGecko'.$rand.') ? iframe'.$rand.'.contentDocument : iframe'.$rand.'.document;
        iHTML'.$rand.' = "<html><head>";
        
        iHTML'.$rand.' += \'<body>'.$value.'</body>\';
        iHTML'.$rand.' += "</html>";
        iDoc'.$rand.'.open();
        iDoc'.$rand.'.write(iHTML'.$rand.');
        iDoc'.$rand.'.close();
        if (!iDoc'.$rand.'.designMode) alert("Визуальный режим редактирования не поддерживается Вашим браузером");
        else iDoc'.$rand.'.designMode = (isGecko'.$rand.') ? "on" : "On";';
        
      
        
        $result25 = $this->core->db->query("SELECT * FROM  buttonred ");  foreach($result25 as $row) {
        $func = $row['func'];$lists = $row['lists'];$text = $row['text'];$lists2=explode(',',$lists);
         $format = $row['format'];
        
        if($format=='button'){
            foreach($lists2 as $valt=>$vals){
                $ret.="function ".$func.$valt.$rand2."() {
                iWin".$rand.".focus();";$ret.="iWin".$rand.".document.execCommand(\"".$text."\", null, '".$vals."');";$ret.="}
                ";}
            }
        else if($format=='select' || $format=='link'){
         
            $ret.="function ".$func.$rand2."(valuest) {
            iWin".$rand.".focus();";$ret.="iWin".$rand.".document.execCommand(\"".$text."\", null,valuest);";$ret.="}
            ";
        }
        else{
            foreach($lists2 as $valt=>$vals){
                $ret.="function ".$func.$valt.$rand2."() {
            iWin".$rand.".focus();";$ret.="iWin".$rand.".document.execCommand(\"".$text."\", null, '".$vals."');";$ret.="}
            ";}
        }
        }
       
       $ret.="$(document).on('mouseout','#".$name.$rand."', function(){
        
        document.getElementById('hid".$rand."').value = iDoc".$rand.".body.innerHTML; 
        });
         </script>";
        
        $ret.="<input type='hidden' name=\"".$name."\" id=\"hid".$rand."\" value='".$value."'>";
        
        return $ret;
        }
}

}