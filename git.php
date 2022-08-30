<?php
session_start();
require_once 'core/config.php';
require_once $GLOBALS['foton_setting']['path'].'/core/lib/preload.php';
require_once $GLOBALS['foton_setting']['path'].'/core/core.php';
$core = new \Foton\Core();
if($core->isAuth() && isset($core->get()->g['git']) && $core->get()->g['git']=='yes'){
    $_SESSION['gittest']=$GLOBALS["foton_setting"]["backup"];
    if(isset($core->get()->g['branch'])){
        $branch=$GLOBALS["foton_setting"]["path"].'/.gitf/'.$_SESSION['login'].'/branch.php';
        file_put_contents($branch,$core->get()->g['branch']);
    }
    else{
        unlink($GLOBALS["foton_setting"]["path"].'/.gitf/'.$_SESSION['login'].'/branch.php');
    }
}
else{
    $_SESSION['gittest']='0';
}
?>