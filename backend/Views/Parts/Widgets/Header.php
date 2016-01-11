<header class="navbar navbarPos">
    <div class="clearFix navbarIndent">
        <a class="fontLogo" href="/backend/index">
            <span class="top fontLogoName">wezom</span>
            <span class="top fontLogoTitle">
                <span class="fontLogoLabel">cms</span>
                <span class="fontLogoVersion">4.0</span>
            </span>
        </a>
        <a title="Переключить навигацию" class="tip toggleSidebar" href="#">
            <i class="fa-bars"></i>
        </a>
        <ul class="navbarNav">
            <?php echo Core\Widgets::get('headerNew'); ?>
            <?php echo Core\Widgets::get('headerContacts'); ?>
            <li class="dropdown dropdownMenuHidden">
                <a class="dropdownToggle" href="#">
                    <i class="fa-male"></i>
                    <span class="navText username"><?php echo Modules\User\Models\User::info()->name; ?></span>
                    <i class="fa-caret-down small"></i>
                </a>
                <ul class="dropdownMenu pull-right">
                    <li>
                        <a href="/backend/auth/edit">
                            <i class="fa-user"></i>
                            Профиль
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="/backend/auth/logout">
                            <i class="fa-key"></i>
                            Выйти
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>