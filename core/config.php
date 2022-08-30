<?php
  //подключение Event->CallbackGlob, Event->CallbackAdmin, Event->Callback(MVC) - для изменения вывода
$GLOBALS["foton_setting"]=[
'obstart'=>"N",//Y
//файл конфигурации
//переменные подключения к б.д.
"host"=>'|host|',
"sql"=>'|sql|',
"dbname"=>'|base|',
"login"=>'|login|',
"pass"=>'|pass|',
//лицензия и версия ядра системы
"license"=>'|license|',
"coref"=>'12.278',
//версия для подключения ядра в режиме совместимости
'version'=>'Core12',
//директория архивов сайта
'backup'=>'system/backup',
//"type"=>"file", //для работы с типами данных администратовной панели
//абсолютный путь
"path" => str_replace(DIRECTORY_SEPARATOR.'core','',__DIR__),
//директория сайта
"sitedir"=>'site',
//директория админ. панели
"admindir"=>'admin',
//адрес входа в панель управления
'admin'=>'admin',
//начало времени работы скрипта
'time'=>microtime(true), 
//максимальный размер сохраняемых файлов в день
'sizelog'=>'25',
//главная страница
"main"=>'html',
//протокол
'http'=>'http',
//name POST данных сохраняемых в логах
'value_log'=>array('script','text'),
//основной шаблон системы
'templates'=>'template.php',
//множитель сессии
'multiplay'=>5453536,
//основной интерфейс
'interface'=>'list',
//максимальный размер в мегабайтах для загрузки
'size_file'=>20,
//разрешенные форматы к загрузке
'format' => ['jpeg','jpg','gif','png','tiff','pdf','xml','mp3','txt','doc','docx','odt'],
//максимальное число записей для вывода  в стандартном интерфейсе
'max_list'=>100,
//страница при входе в админ. панель
'start_page' => 'workarea',
//view err0r 404
"error404"=>"error404",
//включаем git Y/N
"git"=>'N',
//язык
"lang"=>'ru',
//адреса файлов обработки view ядра
"lib"=>'/core/lib/lib',
"adapter"=>'/core/lib/adapter',
//включение event,custom,type для обработки методов ядра
"preload"=>"N",//Y
//'smtp'=>['host'=>'ssl://smtp.yandex.ru','port'=>'465','login'=>'mail@yandex.ru','pass'=>'*******','charset'=>'utf-8'],
//debag project
//"debug"=>true,
//обработка глобальных данных в /dev/handler.php
//"handler"=>true,
//только системные get параметры
//'get_unset'=>true,
//путь к фото, если фото не найдено
//'image_def'=>'path',
//кастомные директории указанные в хуке контроллера dir
//"custom_dir" =>['custom1','custom2',etc...],
//путь к картинке водяного знака
//'water_mark'=>'path',
//путь к исполняемому файлу rar ля архивирования на OC Windows
//'winrar'=>'C:\ProgramFiles\WinRAR\rar.exe',
//для системного рендеринга
//'render'=>true,
//директория из которой будет работать ваше приложения или какие-то его элементы, работает только 
//с git=='Y', директория должна лежать в корне сайта, либо на одном уровне, тогда в конце напишите знак ":"
//'custom'=>'custom:',
//выключить прямой доступ по названию mvc для публичной части приложения
//'no_double'=>true
];
$GLOBALS["foton_setting"]['ifile'] = $GLOBALS["foton_setting"]['path'].'/app/controller/'.$GLOBALS["foton_setting"]['admindir'].'/file';
// $GLOBALS["foton_setting"]['route'] = [
//                                         'txt'=>
//                                             [
//                                               'header'=>"Content-Type: text/plain",
//                                               "dir"=>"txt"
//                                             ]
//                                     ];