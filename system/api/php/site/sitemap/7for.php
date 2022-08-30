<?php  $result25 = $this->db->query("SELECT * FROM  `mapofsite` WHERE `sections`='off'");  foreach($result25 as $row) { $result25i = $this->db->query("SELECT * FROM  `router` WHERE  `view`='".$row['views']."' ");

$result25i2 = $this->db->query("SELECT * FROM  `".$row['tables']."`  ");
foreach($result25i as $row2) { 
    
foreach($result25i2 as $row22) {?>