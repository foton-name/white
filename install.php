<html>
<meta charset="utf-8">
<head>
<title>Install Framework Foton</title>
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
  if(isset($_POST["base"]) && $_POST["base"]!='' && (($_POST["pass"]!='' && $_POST["login"]!='') || $_POST['sql']=="l") && $_POST["license"]!=''){
    if(isset($_POST["host"])){
        $host = $_POST["host"];
    }
    else{
        $host = 'localhost';
    }
    // MySQL username
    $mysql_username = $_POST["login"];
    // MySQL password
    $mysql_password = $_POST["pass"];
    // Database name
    $mysql_database = $_POST["base"];
    // Connect to MySQL server
    if($_POST['sql']=='mysql'){
         $filename = 'base.sql';
         if(stristr($host,':')){
             $hostf=explode(':',$host);
              $link=mysqli_connect($hostf[0], $mysql_username, $mysql_password,$mysql_database,$hostf[1]) or die('Error connecting to MySQL server: ' . mysqli_error($link));
         }
         else{
              $link=mysqli_connect($host, $mysql_username, $mysql_password) or die('Error connecting to MySQL server: ' . mysqli_error($link));
         }
      mysqli_query($link,"SET NAMES 'utf8'"); 
      mysqli_query($link,"SET CHARACTER SET 'utf8'");
      mysqli_query($link,"SET SESSION collation_connection = 'utf8_general_ci'");
      // Select database
      mysqli_select_db($link,$mysql_database) or die('Error selecting MySQL database: ' . mysqli_error($link));
      // Temporary variable, used to store current query
      $templine = '';
      // Read in entire file
      $lines = file($filename);
      // Loop through each line
      foreach ($lines as $line)
      {
        // Skip it if it a comment
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;
        // Add this line to the current segment
        $templine .= $line;
        
        // If it has a semicolon at the end, it the end of the query
        if (substr(trim($line), -1, 1) == ';')
        {
            // Perform the query
          
            mysqli_query($link,$templine) or print('Ошибка запроса \'<strong>' . $templine . '\': ' . mysqli_error($link) . '<br /><br />');
            // Reset temp variable to empty
            $templine = '';
        }
      
      }
     
    }
    else{
         $filename = 'pbase.sql';
         if(stristr($host,':')){
            $hostf=explode(':',$host);
            pg_connect("host=".$hostf[0]." port=".$hostf[1]." dbname=$mysql_database user=$mysql_username password=$mysql_password");
         }
         else{
            pg_connect("host=$host dbname=$mysql_database user=$mysql_username password=$mysql_password"); 
         }
         $templine = '';
      // Read in entire file
      $lines = file($filename);
      // Loop through each line
      foreach ($lines as $line)
      {
        // Skip it if it a comment
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;
        // Add this line to the current segment
        $templine .= $line;
        // If it has a semicolon at the end, it the end of the query
        if (substr(trim($line), -1, 1) == ';')
        {
        
            $link = pg_query($templine) or die('Ошибка запроса ' . $templine);
            echo pg_result_error($link);
            // Reset temp variable to empty
            $templine = '';
        }
      }
    }
   
    $file=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/core/config.php');
    $file=str_replace('|sql|',$_POST["sql"],$file);
    $file=str_replace('|host|',$host,$file);
    $file=str_replace('|base|',$_POST["base"],$file);
    $file=str_replace('|login|',$_POST["login"],$file);
    $file=str_replace('|pass|',$_POST["pass"],$file);
    $file=str_replace('|license|',$_POST["license"],$file);
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/core/config.php',$file);
    if(isset($_POST['sql']) && $_POST['sql']=='mysql' && mysqli_error($link)){
        echo '<script>document.location.href="/admin/?sql_error=true";</script>';
      }
      else if(isset($_POST['sql']) && $_POST['sql']=='pgsql' && pg_result_error($link)){
        echo '<script>document.location.href="/admin/?sql_error=true";</script>';
      }
      else{
        echo '<script>document.location.href="/admin/";</script>';
      }
    }
    else if(isset($_POST["base"])){
      echo '<script>alert("Заполните пожалуйста все поля");</script>';
    }
    else{}
?>

<style>
body {
    background-image: url(https://foton.name/app/view/site/img/f2.jpg);
    background-size: cover;
    background-attachment: fixed;
    font-family: sans-serif;
}

.install {
    width: 370px;
    position: absolute;
    margin: auto;
    background: url(https://foton.name/app/view/site/img/2.jpg);
    padding: 25px;
    border: 1px solid #eee;
    border-radius: 6px;
    -webkit-box-shadow: 0px 0px 2px 0px rgba(207,207,207,1);
    -moz-box-shadow: 0px 0px 2px 0px rgba(207,207,207,1);
    box-shadow: 0px 0px 2px 0px rgba(207,207,207,1);
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    height: max-content;
    display: block;
    font-size: 20px;
    padding-left: 20px;
    border-radius: 2px;
    border: 1px solid #eaeaea;
    color: rgba(0, 0, 0, 0.45);
}

input[type="submit"] {
    background-color: #74d04c;
    background-image: -webkit-gradient(linear, 0 0, 100% 100%, color-stop(.25, rgba(255, 255, 255, .2)), color-stop(.25, transparent), color-stop(.5, transparent), color-stop(.5, rgba(255, 255, 255, .2)), color-stop(.75, rgba(255, 255, 255, .2)), color-stop(.75, transparent), to(transparent) );
    background: -moz-repeating-linear-gradient(top left -30deg, rgba(255, 255, 255, 0.17), rgba(255, 255, 255, 0.17) 15px, rgba(255, 255, 255, 0) 15px, rgba(255, 255, 255, 0) 30px), -moz-linear-gradient(rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0) 100%), #74d04c;
    -moz-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 0.4), inset 0px -1px 1px rgba(0, 0, 0, 0.2);
    -webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 0.4), inset 0px -1px 1px rgba(0, 0, 0, 0.2);
    -o-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 0.4), inset 0px -1px 1px rgba(0, 0, 0, 0.2);
    box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 0.4), inset 0px -1px 1px rgba(0, 0, 0, 0.2);
    -moz-animation: animate-stripes 2s linear infinite;
    -webkit-animation: animate-stripes 2s linear infinite;
    -o-animation: animate-stripes 2s linear infinite;
    -ms-animation: animate-stripes 2s linear infinite;
    -khtml-animation: animate-stripes 2s linear infinite;
    animation: animate-stripes 2s linear infinite;
    box-shadow: 0 15px 20px rgba(46,229,157,.4);
    color: white;
    transform: translateY(-7px);
    text-decoration: none;
    outline: none;
    display: block;
    padding: 0px 20px;
    height: 45px;
    line-height: 45px;
    border-radius: 45px;
    margin: 44px 0px;
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    text-transform: uppercase;
    text-align: center;
    letter-spacing: 3px;
    font-weight: 600;
    color: #524f4e;
    box-shadow: 0 8px 15px rgba(0,0,0,.1);
    transition: .3s;
    border: 0px;
    color: #fff;
    width: 370px;
}

.install input[type="text"],.install select {
    width: 370px;
    height: 44px;
    display: block;
    font-size: 20px;
    padding-left: 20px;
    margin-top: 25px;
    border-radius: 2px;
    border: 1px solid #eaeaea;
    color: rgba(0, 0, 0, 0.45);
}

p {
    text-align: center;
    line-height: 1.5;
    color: #227982;
}
.red{
  color:red;
}
</style>
</head>

<body>
<div class='install'>
<p>
<?php  if(exec('echo EXEC') != 'EXEC'){
    echo "<p class='red'>exec отключен, включите для инсталляции</p>";
}?>
Данные для входа:demo во всех полях, после установки измените логин и пароль на Ваш (пользователи -> список пользователей с ролями), а также настоятельно рекомендуем Вам сгенерировать ключ в верхней панели системы.</p>
<form action='' method='post'>
<select name="sql">
    <option value="mysql">MYSQL</option>
    <option value="pgsql">Postgress SQL</option>   
</select>
<input type="text" name="host" placeholder="host" value="localhost">
<input type="text" name="base" placeholder="Имя базы данных">
<input type="text" name="login" placeholder="Login">
<input type="text" name="pass" placeholder="пароль">
<input type="text" name="license" placeholder="лицензия">
<input type="submit" value="Инсталлировать">
</form>
</body>
</html>