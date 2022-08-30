<?php

  class Tpl_photos extends Model
{

    public function ajaxfiledel()
    {
        if (isset($this->request->p['table']) && isset($this->request->p['id'])) {
            $resultup = $this->core->db->query("SELECT * FROM `" . $this->request->p['table'] . "` WHERE `id`=" . $this->request->p['id']);
            foreach ($resultup as $row) {
                $img = $row[$this->request->p['img']];
            }
            $imgone = str_replace('/app/view/' . $GLOBALS['foton_setting']['sitedir'] . '/', '', $this->request->p['img_one']);
            $imgnew = str_replace('%%%' . $imgone, '', $img);

            $resultups = $this->core->db->query("UPDATE `" . $this->request->p['table'] . "` SET `" . $this->request->p['img'] . "`='" . $imgnew . "' WHERE `id`=" . $this->request->p['id'] . "");

            unlink($GLOBALS["foton_setting"]["path"] . $this->request->p['img_one']);
        }

    }


    public function ajaxfile()
    {
        $datas = array();
        $error = false;
        $files = array();
        $rand = rand(0, 200) . rand(0, 200) . rand(0, 200);
        $size = 1024 * $GLOBALS['foton_setting']['size_file'] * 1024;

        if (isset($_FILES)) {

            $resultup = $this->core->db->query("SELECT * FROM `" . $this->request->p['table'] . "` WHERE `id`=" . $this->request->p['id']);

            foreach ($resultup as $row) {
                $img = $row[$this->request->p['img']];
            }
        }
        if (isset($img)) {
            preg_match('|%%%([^/]+)/|', $img, $dirs);
            if (isset($dirs[1])) {
                $dir = $dirs[1];
            } else {
                $dir = '';
            }
        }
        // переместим файлы из временной директории в указанную
        if (!file_exists($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $dir)) {
            mkdir($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $dir);
        }
        $filet = '';
        foreach ($_FILES as $file) {
            $f_info = pathinfo($file['name'], PATHINFO_EXTENSION);
            $size_f = $file["size"];
            if ($size_f <= $size && in_array($f_info, $GLOBALS['foton_setting']['format'])) {
                if (move_uploaded_file($file['tmp_name'], $GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $dir . '/' . $rand . basename($file['name']))) {
                    $files[] = realpath($GLOBALS["foton_setting"]["path"] . '/app/view/' . $GLOBALS["foton_setting"]["sitedir"] . '/' . $dir . '/' . $rand . $file['name']);
                    $filet .= '%%%' . $dir . '/' . $rand . $file['name'];
                } else {
                    $error = true;
                }
            }
        }
        if (isset($_FILES)) {

            $imgnew = $filet . $img;
            $resultups = $this->core->db->query("UPDATE `" . $this->request->p['table'] . "` SET `" . $this->request->p['img'] . "`='" . $imgnew . "' WHERE `id`=" . $this->request->p['id'] . "");
        }

        $datas = $error ? array('error' => 'Ошибка загрузки файлов.') : array('files' => $files);
        echo json_encode($datas);
    }

}