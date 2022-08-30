<?
namespace Foton;
class Cron extends \Model{
    public $count;
    function __construct(){
        $this->count = 5;
    }
    
    public function mail($from = null, $to = null, $subject = null, $message = null, $file = null){
    	if ($from != null && $to != null && $subject != null && $message != null) {
	        return $this->core->mail_foton($from,$to,$subject,$message,$file);
	    }
	    else{
	    	return 'few arguments passed...';
	    }
    }    
}