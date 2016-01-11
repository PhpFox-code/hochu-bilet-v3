<div class="myInformation">
    <div class="infoTitle">Ваше имя</div>
    <div class="infoValue">
        <?php if ($user->name): ?>
            <?php echo $user->name; ?>
        <?php else: ?>
            <a href="<?php echo Core\HTML::link('user/profile'); ?>">Не указано</a>
        <?php endif ?>
    </div>
</div>
<div class="myInformation">
    <div class="infoTitle">Электронная почтa</div>
    <div class="infoValue">
        <?php if ($user->email): ?>
            <?php echo $user->email; ?>
        <?php else: ?>
            <a href="<?php echo Core\HTML::link('user/profile'); ?>">Не указано</a>
        <?php endif ?>
    </div>
</div>
<div class="myInformation">
    <div class="infoTitle">Телефон</div>
    <div class="infoValue">
        <?php if ($user->phone): ?>
            <?php echo $user->phone; ?>
        <?php else: ?>
            <a href="<?php echo Core\HTML::link('user/profile'); ?>">Не указано</a>
        <?php endif ?>
    </div>
</div>
<div class="lkLinks">
    <div class="lkLink"><a href="<?php echo Core\HTML::link('user/profile'); ?>">Редактировать личные данные</a></div>
    <div class="lkLink"><a href="<?php echo Core\HTML::link('user/change_password'); ?>">Изменить пароль</a></div>
</div>