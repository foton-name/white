<?php
  if(file_exists($GLOBALS['foton_setting']['path'].'/archive.zip')){
  unlink($GLOBALS['foton_setting']['path'].'/archive.zip');
}
if(file_exists($GLOBALS['foton_setting']['path'].'/core.zip')){
  unlink($GLOBALS['foton_setting']['path'].'/core.zip');
}
if(file_exists($GLOBALS['foton_setting']['path'].'/base.sql')){
  unlink($GLOBALS['foton_setting']['path'].'/base.sql');
}
if(file_exists($GLOBALS['foton_setting']['path'].'/pbase.sql')){
  unlink($GLOBALS['foton_setting']['path'].'/pbase.sql');
}
if(file_exists($GLOBALS['foton_setting']['path'].'/install.php')){
  unlink($GLOBALS['foton_setting']['path'].'/install.php');
}

?>