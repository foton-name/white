<?php

  class Xmljson_c extends Xmljson_m
{
    public function includefilesup()
    {
        if (isset($this->request->p['include-files-up'])) {
            $this->include_files_up($this->request->p['include-files'], $this->request->p['include-files-text'], $this->request->p['from']);
        } else if (isset($this->request->p['include-files-del'])) {
            $this->files_unlink($this->request->p['include-files'], $this->request->p['from']);
        } else if (isset($this->request->p['include-files-create'])) {
            $this->files_creates($this->request->p['include-files-text'], $this->request->p['from']);
        } else {
            return false;
        }
        return true;
    }

    public function parsexml()
    {
        $arr = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/app/view/xml/" . $GLOBALS['foton_setting']['sitedir'] . "/*.php") as $filename) {
            $basename = str_replace('_view.php', '', basename($filename));
            $title = $this->core->select_db('seo', 'views', $basename, 'title')[0];
            $arr[$filename] = $title;
        }
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/app/view/xml/" . $GLOBALS['foton_setting']['sitedir'] . "/*.tpl") as $filename) {
            $basename = str_replace('_view.tpl', '', basename($filename));
            $title = $this->core->select_db('seo', 'views', $basename, 'title')[0];
            $arr[$filename] = $title;
        }
        return $arr;
    }

    public function parsejson()
    {
        $arr = array();
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/app/view/json/" . $GLOBALS['foton_setting']['sitedir'] . "/*.php") as $filename) {
            $basename = str_replace('_view.php', '', basename($filename));
            $title_c = $this->core->select_db('seo', 'views', $basename, 'title');
            if (isset($title_c[0])) {
                $title = $title_c[0];
                $arr[$filename] = $title;
            }
        }
        foreach (glob($GLOBALS["foton_setting"]["path"] . "/app/view/json/" . $GLOBALS['foton_setting']['sitedir'] . "/*.tpl") as $filename) {
            $basename = str_replace('_view.tpl', '', basename($filename));
            $title_c = $this->core->select_db('seo', 'views', $basename, 'title');
            if (isset($title_c[0])) {
                $title = $title_c[0];
                $arr[$filename] = $title;
            }
        }
        return $arr;
    }

    public function upfile()
    {
        if (isset($this->request->p['from']) && isset($this->request->p['include-files'])) {
            $file = str_replace($GLOBALS["foton_setting"]["path"] . "/system/api/tpl/" . $this->request->p['from'] . '/', '', $this->request->p['include-files']);
            return $file;
        }
    }

    public function includefiles()
    {
        if (isset($this->request->p['include-files'])) {
            if (isset($this->request->p['include-files-up'])) {

                $this->include_files_up($this->request->p['include-files'], $this->request->p['include-files-text'], $this->request->p['from']);
            } else {

            }

            return $this->include_files($this->request->p['include-files']);
        } else {
            return false;
        }
    }
}

?>