<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<url>
<loc>
<?php  echo $GLOBALS['foton_setting']['http'].'://'.$_SERVER['HTTP_HOST'].'/';?>
</loc>
<lastmod><?$filename=$GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/html_view.php';
$filename2=$GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/html_view.tpl';
if (file_exists($filename)) {
echo date("Y-m-d H:i:s+00:00", filectime($filename));
}
else if (file_exists($filename2)) {
echo date("Y-m-d H:i:s+00:00", filectime($filename2));
}else{}?></lastmod>
</url>

<?php  $result25 = $this->db->query("SELECT * FROM  `mapofsite` WHERE `sections`='on'");  foreach($result25 as $row) { $result25i = $this->db->query("SELECT * FROM  `router` WHERE  `view`='".$row['views']."' ");

foreach($result25i as $row2) { ?>
<url>
<loc>
<?php  echo $GLOBALS['foton_setting']['http'].'://'.$_SERVER['HTTP_HOST'].'/'.$row2['routs'];?>
</loc>
<lastmod><?$filename=$GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/'.$row['views'].'_view.php';
$filename2=$GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/'.$row['views'].'_view.tpl';
if (file_exists($filename)) {
echo date("Y-m-d H:i:s+00:00", filectime($filename));
}
else if (file_exists($filename2)) {
echo date("Y-m-d H:i:s+00:00", filectime($filename2));
}else{}?></lastmod>
</url>
<?php  }}?>


<?php  $result25 = $this->db->query("SELECT * FROM  `mapofsite` WHERE `sections`='off'");  foreach($result25 as $row) { $result25i = $this->db->query("SELECT * FROM  `router` WHERE  `view`='".$row['views']."' ");

$result25i2 = $this->db->query("SELECT * FROM  `".$row['tables']."`  ");
foreach($result25i as $row2) { 
    
foreach($result25i2 as $row22) {?>
<url>
<loc>
<?php  preg_match_all("|%([^%]+)%|U",$row2["routs"],    $out0, PREG_PATTERN_ORDER);
				for($it=0;$it<count($out0[1]);$it++){
					$row27 = preg_replace('#%'.$out0[1][$it].'%:([^:]+):#',$row22[$out0[1][$it]],$row2['routs']);
					$row27 = preg_replace('#%'.$out0[1][$it].'%@([^@]+)@#',$row22[$out0[1][$it]],$row27);
				}

echo $GLOBALS['foton_setting']['http'].'://'.$_SERVER['HTTP_HOST'].'/'.$row27;?>
</loc>
<lastmod><?$filename=$GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/'.$row['views'].'_view.php';
$filename2=$GLOBALS['foton_setting']['path'].'/app/view/'.$GLOBALS['foton_setting']['sitedir'].'/'.$row['views'].'_view.tpl';
if (file_exists($filename)) {
echo date("Y-m-d H:i:s+00:00", filectime($filename));
} else if (file_exists($filename2)) {
echo date("Y-m-d H:i:s+00:00", filectime($filename2));
}else{}?></lastmod>
</url>
<?php  }}}?>

</urlset>