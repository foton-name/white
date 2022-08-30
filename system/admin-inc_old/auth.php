<?php
  require_once(__DIR__.'/install_del.php');
?>
<link rel="stylesheet" type="text/css" href="/system/admin-inc/css/background.css" />
<div class='text'>Framework Foton &copy; <?=date("Y");?></div>

<div class="wrap">
  <div class="cube">
    <div class="front">
<div class='panel'>Open Panel</div>
<div class='forms' >

      <table border="0" cellpadding="4" cellspacing="0">
        <tbody><tr><form action='' method='post' id='forme1' >
            <td></td>
            <td class="nc-text-red"></td>
        </tr>
        <tr>
           
            <td><input type="text" name='login' size="32"  placeholder="Login" class="nct logininc" maxlength="255"></td>
        </tr>
<tr>
         
            <td><input type="password" name='pass' size="32"  placeholder="Password" class="nct passinc" maxlength="255"></td>
        </tr>
       <tr>
            
            <td><input type="text" name="ititifhkjk"  size="32" placeholder="Last code"  class="nct" maxlength="255"></td>
        </tr>
       
                    <tr>
                        
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <button type="button" class="but">Авторизоваться</button>
                            
                </td></form>
            </tr>
        </tbody></table></div>
  
    </div>
    <div class="back">
     
    </div>
    <div class="top">
     <div class='img'></div>
    </div>
    <div class="bottom">
        <div class='panel'></div>
    <div class='forms' id='forms2'>
      <table border="0" cellpadding="4" cellspacing="0">
        <tbody><tr><form action='' method='post' id='forme2'>
          
            <td class="nc-text-red"></td>
        </tr>
       
        
            <td><input type="text" name="hash"  size="32"  placeholder="Last code" class="nct" maxlength="255"></td>
        </tr>
       
                    <tr>
                       
                        <td><span class='logis'></span></td>
                    </tr>
                    <tr>
                        <td>
                            <button type="button" start_page='<?=$GLOBALS['foton_setting']['start_page'];?>' class="but2">Авторизоваться</button>
                            
                </td></form>
            </tr>
        </tbody></table></div>
    </div>
    <div class="left">
      <div class='logo-inc'>
<img src='/system/admin-inc/img/logo.png'>
<p>Framework foton</p>
</div>
    </div>
    <div class="right">
      <div class='logo-inc'>
<img src='/system/admin-inc/img/logo.png'>
<p>Framework foton</p>
</div>
    </div>
  </div>
</div>
