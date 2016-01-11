<div class="lk_menu">
    <div class="menuElement <?php echo Core\Route::action() == 'index' ? 'current' : ''; ?>">
        <a href="<?php echo Core\HTML::link('user'); ?>">Личный кабинет</a>
    </div>
    <div class="menuElement <?php echo Core\Route::action() == 'orders' ? 'current' : ''; ?>">
        <a href="<?php echo Core\HTML::link('user/orders'); ?>">Мои заказы</a>
    </div>
    <div class="menuElement <?php echo Core\Route::action() == 'profile' ? 'current' : ''; ?>">
        <a href="<?php echo Core\HTML::link('user/profile'); ?>">Мои данные</a>
    </div>
    <div class="menuElement <?php echo Core\Route::action() == 'change_password' ? 'current' : ''; ?>">
        <a href="<?php echo Core\HTML::link('user/change_password'); ?>">Изменить пароль</a>
    </div>
</div>