<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="/system/admin-inc/img/logo.png" type="image/png">
    <title><?$this->core->meta($page,'title',$get[0]);?></title>
    <meta name="description" content="<?$this->core->meta($page,'description',$get[0]);?>">
    <meta name="keywords" content="<?$this->core->meta($page,'keywords',$get[0]);?>">
    <? if (!isset($get['modules_status'])) { ?>
        <link rel="stylesheet" type="text/css" href="/<?= $this->url; ?>css/style.css"/>
    <?
    }
    if ($this->router->css_page('mvc', $page)) {
        $this->render_css($this->router->css_page('mvc', $page));
    } ?>
    <script src="/<?= $this->url; ?>js/jquery.js" type="text/javascript"></script>
    <? if (!isset($get['modules_status'])) { ?>
        <script src="/<?= $this->url; ?>js/script.js" type="text/javascript"></script>
    <?
    }
    if ($this->router->js_page('mvc', $page)) {
        $this->render_js($this->router->js_page('mvc', $page));
    } ?>
</head>
<body>
<?php

  $this->html($get, $page, $arr);
?>
</body>
</html>
