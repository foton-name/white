<?php  $result25 = $this->db->query("SELECT * FROM  `mapofsite` WHERE `sections`='on'");  foreach($result25 as $row) { $result25i = $this->db->query("SELECT * FROM  `router` WHERE  `view`='".$row['views']."' ");

foreach($result25i as $row2) { ?>