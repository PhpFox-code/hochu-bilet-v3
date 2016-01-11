<li class="dropdown dropdownMenuHidden">
    <a class="dropdownToggle" href="#">
        <i class="fa-envelope"></i>
        <?php if ($cContacts): ?>
            <span class="badge"><?php echo $cContacts; ?></span>
        <?php endif ?>
    </a>
    <ul class="dropdownMenu extended pull-right">
        <li class="title">
            <?php if ( $cContacts ): ?>
                <p>У вас <?php echo $cContacts; ?> новых сообщений</p>
            <?php else: ?>
                <p>Новых сообщений нет</p>
            <?php endif ?>
        </li>
        <?php foreach ( $contacts as $obj ): ?>
            <li>
                <a href="/backend/contacts/edit/<?php echo $obj->id; ?>">
                    <span class="photo"></span>
                    <span class="subject">
                        <span class="from"><?php echo $obj->name; ?></span>
                        <span class="time"><?php echo date('d.m.Y H:i', $obj->created_at); ?></span>
                    </span>
                    <span class="text"><?php echo Core\Text::limit_words( strip_tags( $obj->text ), 10 ); ?></span>
                </a>
            </li>
        <?php endforeach ?>
        <li class="footer">
            <a href="/backend/contacts/index">Смотреть все сообщения</a>
        </li>
    </ul>
</li>