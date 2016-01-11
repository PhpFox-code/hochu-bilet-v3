<div class="toolbar no-padding">
    <div class="btn-group">
        <?php if ($edit_link): ?>
            <a title="Редактирование" href="<?php echo $edit_link; ?>" class="btn btn-lg <?php echo $edit ? 'text-primary' : ''; ?> bs-tooltip"><i class="fa-fixed-width"></i> <span class="hidden-xx">Редактирование</span></a>
        <?php endif ?>
        <?php if ($dates_link): ?>
            <a title="Сеансы" href="<?php echo $dates_link; ?>" class="btn btn-lg <?php echo $dates ? 'text-primary' : ''; ?> bs-tooltip"><i class="fa-fixed-width"></i> <span class="hidden-xx">Сеансы</span></a>    
        <?php endif ?>
        <?php if ($comments_link): ?>
            <a title="Коментарии" style="margin-right: 30px;" href="<?php echo $comments_link; ?>" class="btn btn-lg <?php echo $comments ? 'text-primary' : ''; ?> bs-tooltip"><i class="fa-fixed-width"></i> <span class="hidden-xx">Коментарии</span></a>
        <?php endif ?>
        <a title="Сохранить" href="#" class="btn btn-lg text-success bs-tooltip btn-save"><i class="fa-check"></i> <span class="hidden-xx">Сохранить</span></a>
        <?php if ( $list_link ): ?>
            <a title="Закрыть" href="<?php echo $list_link; ?>" class="btn btn-lg text-danger bs-tooltip"><i class="fa-times-circle"></i> <span class="hidden-xx">Закрыть</span></a>
        <?php endif ?>
    </div>
</div>

<script>
    $('.toolbar').on('click', '.btn-save', function(e){
        e.preventDefault();
        $('form#myForm').submit();
    });
</script>