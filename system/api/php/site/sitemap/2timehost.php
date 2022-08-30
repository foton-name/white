<?php  $filename=$GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/html_view.php';
$filename2=$GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/html_view.tpl';
if (file_exists($filename)) {
echo date("Y-m-d H:i:s+00:00", filectime($filename));
}
else if (file_exists($filename2)) {
echo date("Y-m-d H:i:s+00:00", filectime($filename2));
}else{}?>