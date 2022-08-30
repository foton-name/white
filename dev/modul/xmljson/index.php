<div class='content'><p class='white centerp ppx'>XML</p>
    <form action='' method='post' class='nones'>
        <select name='include-files' class='files-sl'>
            <? foreach ($data['parsexml'] as $key => $val) { ?>
                <option value='<?= $key; ?>'><?= $val; ?></option>
            <? } ?>
        </select>
        <input type='hidden' name='from' value='xml'>
        <input type='submit' value='Редактировать' class='files-but'>
    </form>
    <p class='white centerp ppx'>Json</p>
    <form action='' method='post' class='nones'>
        <select name='include-files' class='files-sl'>
            <? foreach ($data['parsejson'] as $key => $val) { ?>
                <option value='<?= $key; ?>'><?= $val; ?></option>
            <? } ?>
        </select>
        <input type='hidden' name='from' value='json'>
        <input type='submit' value='Редактировать' class='files-but'>
    </form><?

    if ($data['includefiles'] !== false) {
        ?>
        <form action='' method='post' id='inc-file' class='xmljson'>

        <input type='hidden' name='include-files' value='<?= $this->request->p['include-files']; ?>'>

        <input type='hidden' name='from' value='<?= $this->request->p['from']; ?>'>
        <textarea name='include-files-text' id='inc-text'><?= $data['includefiles']; ?></textarea>
        <input type='submit' name='include-files-del' value='Удалить'>
        <input type='submit' name='include-files-up' value='Сохранить'>

        </form><? } else {
        ?>
        <form action='' method='post' id='inc-file' class='xmljson'>


            <select name='from'>
                <option>json</option>
                <option>xml</option>
            </select>
            <input type='text' name='include-files-text' placeholder='Название страницы лат'>

            <input type='submit' id="create_f" name='include-files-create' value='Создать'>
        </form>
    <? } ?>
</div>