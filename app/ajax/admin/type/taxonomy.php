<?
class Type_taxonomy{
    function __construct(){
        global $core; $this->core = $core;
    }
    public function index($table){
        return $table;
    }
}