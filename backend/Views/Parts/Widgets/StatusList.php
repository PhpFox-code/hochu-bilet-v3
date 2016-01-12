<?php if( \Core\User::caccess() != 'edit' ): ?>
    <?php if ($status == 1): ?>
        <i class="fa-check green"></i>
    <?php else: ?>
        <i class="fa-dot-circle-o red"></i>
    <?php endif ?>
<?php endif; ?>
<?php if( \Core\User::caccess() == 'edit' ): ?>
    <a
        data-pub="<b>Снять с публикации</b><br>Опубликовано"
        data-unpub="<b>Опубликовать</b><br>Не опубликовано"
        title="<?php echo $status == 1 ? '<b>Снять с публикации</b><br>Опубликована' : '<b>Опубликовать новость</b><br>Не опубликовано'; ?>"
        data-status="<?php echo $status; ?>"
        data-id="<?php echo $id; ?>"
        href="#"
        class="setStatus bs-tooltip btn btn-xs <?php echo $status == 1 ? 'btn-success' : 'btn-danger' ?>"
    >
        <?php if ($status == 1): ?>
            <i class="fa-check"></i>
        <?php else: ?>
            <i class="fa-dot-circle-o"></i>
        <?php endif ?>
    </a>
<?php endif; ?>