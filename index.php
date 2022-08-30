<?php
session_start();
ini_set('display_errors',1);
require_once 'core/config.php';
require_once $GLOBALS['foton_setting']['path'].'/core/helper.php';
require_once $GLOBALS['foton_setting']['path'].'/core/config_custom.php';
if(isset($_SESSION['login']) && $_SERVER['REQUEST_URI']=='/'.$GLOBALS['foton_setting']['admin'].'/'){
    $host = $GLOBALS['foton_setting']['http'].'://'.$_SERVER['HTTP_HOST'].'/';
    header('Location:'.$host.$GLOBALS['foton_setting']['start_page'].'/');
}
require_once FotonGit('dev/custom_valid.php');
require_once FotonGit('core/lib/preload.php');
require_once FotonGit('core/core.php');
$core = new \Foton\Core;
spl_autoload_register(function ($class) {
	$core = new \Foton\Core;
	$core->requiref($class);      
});
if($GLOBALS["foton_setting"]['obstart']=='Y') {
        require_once FotonGit('dev/event.php');
}
if($GLOBALS["foton_setting"]['preload']=='Y') {
    require_once FotonGit('dev/type.php');
    require_once FotonGit('dev/custom.php');
    require_once FotonGit('core/lib/run.php');
}
require_once FotonGit('core/model.php');
if(isset($_GET['foton-cron']) && $_GET['foton-cron']==$GLOBALS["foton_setting"]['multiplay']){    
    require_once FotonGit('dev/cron.php');
    $cron = new \Foton\Cron;
    $core->work($cron,$cron->count);
}
else{
    require_once FotonGit('core/view.php');
    $view = new \Foton\View();
    $view->load();
}


