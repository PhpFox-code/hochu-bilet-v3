<?php if( Core\User::caccess() == 'edit' ): ?>
    <div class="toolbar no-padding">
        <div class="btn-group">
            <a title="Разослать" href="#" class="btn btn-lg text-success bs-tooltip btn-save"><i class="fa-check"></i> <span class="hidden-xx">Разослать</span></a>
        </div>
    </div>
<?php endif; ?>
<script>
    $('.toolbar').on('click', '.btn-save', function(e){
        e.preventDefault();
        $('form#myForm input[type="submit"]').click();
    });
</script>