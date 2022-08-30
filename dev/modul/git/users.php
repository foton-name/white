<div class='body'>
    <p class='ph2'>Пользователи</p>

    <? foreach ($data['users'] as $user) { ?>
        <div class="user" ids="<?= $user['login']; ?>"><?= $user['name']; ?></div>
    <? } ?>
</div>