<?php
  require_once(__DIR__.'/install_del.php');
?>
<link rel="stylesheet" type="text/css" href="/system/admin-inc/css/background.css" />
<div class="overload">
    <div class="relative">
        <div class="center-div">
            <div class="logo">
                <div class="in-logo">
                    <img src="/system/admin-inc/img/logo.png">
                    <p><span class="foton">Framework Foton</span>Инструмент для профессиональной Web разработки</p>
                </div>
            </div>
            <div class="window-auth">
                <p class="h1">OPEN PANEL</p>
                <p class="h3">После инсталляции введите в каждое поле demo</p>
                <span class="logis"></span>
                <form method="post">
                    <p class="input-label">Login</p>
                    <input type="text" name="login" class="login" placeholder="Введите Login">
                     <p class="input-label">Password</p>
                    <input type="password" name="pass" class="pass" placeholder="Введите пароль">
                     <p class="input-label">Last code 1</p>
                    <input type="text" name="code1 nct" class="code1" placeholder="Введите Last code">
                     <p class="input-label">Last code 2</p>
                    <input type="text" name="code2 nct" class="code2" placeholder="Введите Last code2">
                    <button type="button" start_page='<?=$GLOBALS['foton_setting']['start_page'];?>' class="but2">Авторизоваться</button>
                </form>
            </div>
        </div>
    </div>
    <div class='text'>Framework Foton &copy; <?=date("Y");?></div>
</div>
              