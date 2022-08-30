<?php

  if ($this->core->chmod_id([1,1])) {
    require_once 'users.php';
} else if ($this->core->chmod_id([5,5])) {
    require_once 'home.php';
} else {

}
?>