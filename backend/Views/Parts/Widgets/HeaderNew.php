<li class="dropdown dropdownMenuHidden">
    <a class="dropdownToggle" href="#">
        <i class="fa-warning"></i>
        <!-- <span class="badge"><?php //echo $count; ?></span> -->
    </a>
    <ul class="dropdownMenu extended notification scrollbarHeight pull-right">
        <li class="title">
            <p>
                Всего у вас <?php echo $count; ?> уведомений
            </p>
        </li>
        <?php foreach($result as $obj): ?>
            <li>
                <a href="<?php echo $obj->link; ?>">
                    <?php echo Core\Log::icon($obj->type); ?>
                    <span class="message" title="<?php echo $obj->name; ?>"><?php echo $obj->name; ?></span>
                    <span class="time"><?php echo date('d.m.Y H:i', $obj->created_at); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
        <li class="footer">
            <a href="/backend/log/index">Смотреть все уведомления</a>
        </li>
    </ul>
</li>