<?php
  if ($_SESSION['login'] && $this->request->g['file']) {
} else if ($_SESSION['login'] && $this->request->g['del']) {
} else {
    echo 'Файл не найден';
}
?>