<canvas id="income" width="800" height="300">
    <script>
        var income = document.getElementById("income").getContext("2d");
        var barData = {
            labels: ['модели', 'общие view', 'view сайта', 'view админ. панели', 'контроллер',],
            datasets: [
                {
                    fillColor: "#48A497",
                    strokeColor: "#48A4D1",
                    data: [<?=$controller_class->list_files('site', 'admin');?>,]
                }

            ]
        }
        new Chart(income).Bar(barData);
    </script>

</canvas>

<canvas id="countries" width="800" height="300">
    <script>
        var countries = document.getElementById("countries").getContext("2d");
        var pieData = [
            {
                value: <?=$controller_class->sizefile_c()[0];?>,
                color: "#6f79dd",

            },
            {
                value: <?=$controller_class->sizefile_c()[1];?>,
                color: "#00ff00"
            },
            {
                value: <?=$controller_class->sizefile_c()[2];?>,
                color: "#33b5ab"
            },
        ];
        var pieOptions = {
            segmentShowStroke: false,
            animateScale: false
        };
        new Chart(countries).Pie(pieData, pieOptions);
    </script>

</canvas>
<?php   foreach ($data['grafiki_include'] as $key2 => $val) {
    if ($val['graph'] == '1') {
        ?>
        <div class="grafik_f">
        <canvas id="countries<?= $val['graph'] . $key2; ?>" width="800" height="300">
            <script>
                var countries = document.getElementById("countries<?=$val['graph'] . $key2;?>").getContext("2d");

                var pieData = [
                    <? foreach($val['pole'] as $is=>$is2){?>
                    {
                        value: <?=$val['pole'][$is];?>,
                        color: "<?=$val['color'][$is];?>",

                    },
                    <?}?>
                ];
                var pieOptions = {
                    segmentShowStroke: false,
                    animateScale: false
                };
                new Chart(countries).Pie(pieData, pieOptions);
            </script>

        </canvas>
        <div class='label_inc'>
            <? foreach ($val['pole'] as $is => $is2) { ?>

                <span style="background:<?= $val['color'][$is]; ?>"><?= $val['value'][$is]; ?></span>
            <?
            } ?>
        </div></div>
    <?
    } else if ($val['graph'] == '2') { ?>
        <div class="grafik_f">
            <canvas id="countries<?= $val['graph'] . $key2; ?>" width="800" height="300">

                <script>
                    var income = document.getElementById("countries<?=$val['graph'] . $key2;?>").getContext("2d");
                    var barData = {
                        labels: [<? foreach($val['pole'] as $is=>$is2){?>'<?=$val['value'][$is];?>', <?}?>'Нет'],
                        datasets: [
                            {
                                fillColor: "<?=$val['color']['0'];?>",
                                strokeColor: "<?=$val['color']['1'];?>",
                                data: [<? foreach($val['pole'] as $is=>$is2){?><?=(int)$val['pole'][$is];?>, <?}?>0]
                            }

                        ]
                    }
                    new Chart(income).Bar(barData);
                </script>
            </canvas>
        </div>

    <? } else { ?>
        <div class="grafik_f">
            <canvas id="countries<?= $val['graph'] . $key2; ?>" width="800" height="300">

                <script>
                    var buyers = document.getElementById('countries<?=$val['graph'] . $key2;?>').getContext('2d');
                    var buyerData = {
                        labels: [<? foreach($val['pole'] as $is=>$is2){?>'<?=$val['value'][$is];?>',<?}?>],
                        datasets: [
                            {
                                fillColor: "<?=$val['color']['0'];?>",
                                strokeColor: "<?=$val['color']['1'];?>",
                                pointColor: "<?=$val['color']['0'];?>",
                                pointStrokeColor: "<?=$val['color']['1'];?>",
                                data: [<? foreach($val['pole'] as $is=>$is2){?><?=(int)$val['pole'][$is];?>,<?}?>]
                            }
                        ]
                    }
                    new Chart(buyers).Line(buyerData);
                </script>
            </canvas>
        </div>


    <? }
} ?>
<br>
<p> &nbsp; M <span class='m'></span>/V <span class='v'></span>/C <span class='c'></span></p>
<br>
<p class="pcolor">Лицензия: <?= $GLOBALS["foton_setting"]["license"]; ?></p>
<p class="pcolor">Версия ядра <?= $GLOBALS["foton_setting"]["coref"]; ?> <? if ($this->core->chmod_id([1,1])) { ?><span class='up_core'>Обновить</span><? } ?>
</p>
<p class="pcolor"> Login: <?= $_SESSION['login']; ?></p>
<p class="pcolor"> Права: <?=$data['typeuser'];?></p>
<p class="pcolor">Версия php: <?= phpversion(); ?></p>
<p class="pcolor">Версия mysql:<?= mysqli_get_client_info(); ?></p>
<p class="pcolor">Версия apache:<? //apache_get_version();?></p>
<p class="pcolor">OS: <?= php_uname(); ?></p>
<p class="pcolor">Для скрипта: <? echo ini_get('memory_limit'); ?></p>
<p class="pcolor">Средняя загрузка процессора: <?= passthru('cat /proc/loadavg'); ?></p>
<form action='' method='post' class='information'>

    <? if ($this->core->chmod_id([1,1])) { ?>
        <p class='pcolor pback' key='<?= $GLOBALS['foton_setting']['backup']; ?>'>Сделать бекап</p>
        <table class='tbl dop-tb'>
            <? foreach ($data['spbackup'] as $f) { ?>
                <tr>
                    <td><?= $f; ?></td>
                    <td><a href='/zip/?file=<?= $f; ?>' class='href-zip'>Скачать</a> <a href='/zip/?del=<?= $f; ?>'
                                                                                        class='href-zip'>Удалить</a></td>
                </tr>
            <? } ?>
        </table>
    <? } ?>
    <table class='tbl'>
        <tr>
            <td>Пароль 1</td>
            <td><?= $controller_class->passints()['pass1']; ?></td>
        </tr>
        <tr>
            <td>Пароль 2</td>
            <td><?= $controller_class->passints()['pass2']; ?></td>
        </tr>
    </table>
    <p class='h2'> Модели Сайта</p>
    <table class='tbl'>

        <?
        $ms = $controller_class->fileinfo('model', $GLOBALS['foton_setting']['sitedir']);
        foreach ($ms as $key => $val) {
            echo '<tr><td>Имя файла</td><td id="f-tb">' . $val['name'] . '</td></tr>';
            echo '<tr><td>Размер файла</td><td>' . ceil($val['size'] / 1024) . 'Kb</td></tr>';
            echo '<tr><td>Последнее время доступа</td><td>' . date('Y-m-d H:i', $val['atime']) . '</td></tr>';
            echo '<tr><td>Время изменения</td><td>' . date('Y-m-d H:i', $val['mtime']) . '</td></tr>';
            echo '<tr><td>Номер устройства</td><td>' . $val['dev'] . '</td></tr>';
            echo '<tr><td>Режим защиты</td><td>' . $val['mode'] . '</td></tr>';
        } ?>
    </table>
    <p class='h2'> Модели Панели</p>
    <table class='tbl'>

        <?
        $ms = $controller_class->fileinfo('model', $GLOBALS['foton_setting']['admindir']);
        foreach ($ms as $key => $val) {
            echo '<tr><td>Имя файла</td><td id="f-tb">' . $val['name'] . '</td></tr>';
            echo '<tr><td>Размер файла</td><td>' . ceil($val['size'] / 1024) . 'Kb</td></tr>';
            echo '<tr><td>Последнее время доступа</td><td>' . date('Y-m-d H:i', $val['atime']) . '</td></tr>';
            echo '<tr><td>Время изменения</td><td>' . date('Y-m-d H:i', $val['mtime']) . '</td></tr>';
            echo '<tr><td>Номер устройства</td><td>' . $val['dev'] . '</td></tr>';
            echo '<tr><td>Режим защиты</td><td>' . $val['mode'] . '</td></tr>';
        } ?>
    </table>
    <p class='h2'> Контроллеры Сайта</p>
    <table class='tbl'>
        <? $ms = $controller_class->fileinfo('controller', $GLOBALS['foton_setting']['sitedir']);
        foreach ($ms as $key => $val) {
            echo '<tr><td>Имя файла</td><td id="f-tb">' . $val['name'] . '</td></tr>';
            echo '<tr><td>Размер файла</td><td>' . ceil($val['size'] / 1024) . 'Kb</td></tr>';
            echo '<tr><td>Последнее время доступа</td><td>' . date('Y-m-d H:i', $val['atime']) . '</td></tr>';
            echo '<tr><td>Время изменения</td><td>' . date('Y-m-d H:i', $val['mtime']) . '</td></tr>';
            echo '<tr><td>Номер устройства</td><td>' . $val['dev'] . '</td></tr>';
            echo '<tr><td>Режим защиты</td><td>' . $val['mode'] . '</td></tr>';
        } ?>
    </table>
    <p class='h2'> Контроллеры Панели</p>
    <table class='tbl'>
        <? $ms = $controller_class->fileinfo('controller', $GLOBALS['foton_setting']['admindir']);
        foreach ($ms as $key => $val) {
            echo '<tr><td>Имя файла</td><td id="f-tb">' . $val['name'] . '</td></tr>';
            echo '<tr><td>Размер файла</td><td>' . ceil($val['size'] / 1024) . 'Kb</td></tr>';
            echo '<tr><td>Последнее время доступа</td><td>' . date('Y-m-d H:i', $val['atime']) . '</td></tr>';
            echo '<tr><td>Время изменения</td><td>' . date('Y-m-d H:i', $val['mtime']) . '</td></tr>';
            echo '<tr><td>Номер устройства</td><td>' . $val['dev'] . '</td></tr>';
            echo '<tr><td>Режим защиты</td><td>' . $val['mode'] . '</td></tr>';
        } ?>
    </table>
    <p class='h2'>Страницы сайта</p>
    <table class='tbl'>
        <? $ms = $controller_class->fileinfo('view',$GLOBALS['foton_setting']['sitedir']);
        foreach ($ms as $key => $val) {
            echo '<tr><td>Имя файла</td><td id="f-tb">' . $val['name'] . '</td></tr>';
            echo '<tr><td>Размер файла</td><td>' . ceil($val['size'] / 1024) . 'Kb</td></tr>';
            echo '<tr><td>Последнее время доступа</td><td>' . date('Y-m-d H:i', $val['atime']) . '</td></tr>';
            echo '<tr><td>Время изменения</td><td>' . date('Y-m-d H:i', $val['mtime']) . '</td></tr>';
            echo '<tr><td>Номер устройства</td><td>' . $val['dev'] . '</td></tr>';
            echo '<tr><td>Режим защиты</td><td>' . $val['mode'] . '</td></tr>';
        } ?>
    </table>
</form>

