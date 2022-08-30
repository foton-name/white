<?php
  class Model{
    protected $dbname;
    public $db_query;
    public $obj_mod;
    public $obj_c;
    public static $dbh;
    public $core;
    public function __construct(){
        global $core;
        if($GLOBALS["foton_setting"]["preload"]=="Y"  && version_compare(PHP_VERSION, '5.6.0') >= 0) {
            $run = new \Foton\Run('_');
            $this->core = $run->Core;
        }
        else{
            $this->core = $core;
        }
        $this->get_post = $this->core->post_get();
        $this->mod = new \Foton\Mod;
        $this->widget = new \Foton\Widget;
        if(empty(self::$dbh)){
            self::$dbh = $this->core->db();
        }
        $this->db = self::$dbh;
        $this->request = $this->core->get();
        $this->core->glob_controller();
        $this->glob = new \Controller_Globals;        
    }
}  

?>