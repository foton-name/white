<div class='content'><? $data['up_create_shablon']; ?>

    <div class='hiddes  shablon'><p>X</p>
        <form action='' method='post'>
            <div class='default'> Стандартный выбор</div>
            <input type='text' name='var' id='var' placeholder='переменные'>
            <input type='text' name='searchbaza2' id='searchbaza2' placeholder='Поиск таблицы'>
            <select id='table'>
                <option value='new-vars'>Переменная</option>
                <?
                for ($it = 0; $it < count($data['shablonphp2']['id']); $it++) {
                    ?>

                    <option id='<?= $data['shablonphp2']['id'][$it]; ?>'
                            desc-id='<?= $data['shablonphp2']['desc'][$it]; ?>'><?= $data['shablonphp2']['id'][$it]; ?></option>

                    <?


                }
                ?>
            </select>
            <br>

            <input type='text' name='func' class='func' placeholder='Функция'> <input type='text' name='kolvo' id='kolv'
                                                                                      placeholder='кол-во'>
            <br>
            <div id='u'></div>
            <input type='submit' value='Сохранить' class='class'></form>
    </div>
    <div class='hiddes2'></div>
    <input type="button" class="new" value="создать функцию">
    <div class='block'>
        <?
        foreach ($data['shablonphp'] as $filename) {
            ?>

            <span class='filetext'><?= $filename; ?></span>
        <?
        }
        ?></div>
</div>