<?php if( Core\User::caccess() == 'edit' ): ?>
    <div class="toolbar no-padding">
        <div class="btn-group">
            <?php if ( $add ): ?>
                <a href="/backend/<?php echo Core\Route::controller(); ?>/add" class="btn btn-lg"><i class="fa-plus"></i> Создать</a>
            <?php endif ?>
            <a title="Отметить как прочитанные" href="#" data-status="1" class="publish btn btn-lg text-success bs-tooltip"><i class="fa-check"></i> <span class="hidden-xx">Прочитано</span></a>
            <a title="Отметить как непрочитанные" href="#" data-status="0" class="publish btn btn-lg text-danger bs-tooltip"><i class="fa-circle-o"></i> <span class="hidden-xx">Не прочитано</span></a>
            <?php if ( $delete ): ?>
                <a title="Удалить" href="#" class="btn btn-lg text-warning bs-tooltip delete-items"><i class="fa-fixed-width">&#xf00d;</i> <span class="hidden-xx">Удалить</span></a>
            <?php endif ?>
        </div>
    </div>
<?php endif; ?>