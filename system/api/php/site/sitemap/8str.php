<?php  preg_match_all("|%([^%]+)%|U",$row2["routs"],    $out0, PREG_PATTERN_ORDER);
				for($it=0;$it<count($out0[1]);$it++){
					$row27 = preg_replace('#%'.$out0[1][$it].'%:([^:]+):#',$row22[$out0[1][$it]],$row2['routs']);
					$row27 = preg_replace('#%'.$out0[1][$it].'%@([^@]+)@#',$row22[$out0[1][$it]],$row27);
				}

echo $GLOBALS['foton_setting']['http'].'://'.$_SERVER['HTTP_HOST'].'/'.$row27;?>