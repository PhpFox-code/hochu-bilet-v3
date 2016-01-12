<div class="toolbar no-padding">
        <div class="btn-group">
            <?php if( Core\User::caccess() == 'edit' ): ?>
                <a title="Сохранить" href="#" class="btn btn-lg text-success bs-tooltip btn-save"><i class="fa-check"></i> <span class="hidden-xx">Сохранить</span></a>
            <?php endif; ?>
            <a title="Закрыть" href="<?php echo $list_link ? $list_link : '/backend/' . Core\Route::controller() . '/index'; ?>" class="btn btn-lg text-danger bs-tooltip"><i class="fa-times-circle"></i> <span class="hidden-xx">Закрыть</span></a>
        </div>
</div>

<script>
    $('.toolbar').on('click', '.btn-save', function(e){
        e.preventDefault();
        $('form#myForm input[type="submit"]').click();
    });
</script>