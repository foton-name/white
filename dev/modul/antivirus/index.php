<div class='form'>
    <div class='form2'>
        <h1>Antivirus</h1>
        <div class='head'>
            <div class='it-1'>Безопасный режим проверки</div>
            <div class='it-2'>Гибкая настройка</div>
            <div class='it-3'>Многоуровневая проверка</div>
            <div class='it-4'>Постоянные обновления баз сигнатур</div>
        </div>
        <br><br>

        <h2>Онлайн проверка</h2>
        <br><br>
        <div class="inp">

            <?
            foreach ($data['status'] as $key => $val) {
                ?>
                <fieldset>
                    <label class="label">
                        <input type="checkbox" class="check" value="<?= $key; ?>"><span
                                class="pseudocheckbox"><?= $val; ?></span>
                    </label>
                </fieldset>
            <? } ?>
        </div>


        <input type='button' class='button-inc' value='Начать сканирование'>
        <div class='res'></div>
        <div class='resfile'></div>
    </div>
</div>
<div class='uploads_back'></div>
<div class='okno-w'></div>