<?php if (!empty($images)): ?>
    <?php foreach($images as $im): ?>
        <?php if (is_file(HOST.Core\HTML::media('images/catalog/big/'.$im->image))): ?>
            <div class="catalog-image" data-image="<?=$im->id; ?>">
                <div class="preview-image">
                    <a href="<? echo Core\HTML::media('images/catalog/big/'.$im->image); ?>" data-lightbox="images">
                        <img src="<? echo Core\HTML::media('images/catalog/medium/'.$im->image); ?>" />
                    </a>               
                    <div class="delete-catalog-image" data-id="<?php echo $im->id; ?>"></div>                    
                </div>
                <div class="default-image">
                    <label for="def-img-<?=$im->id; ?>">Обложка</label>
                    <input style="opacity: 1; position: static;" id="def-img-<?=$im->id; ?>" type="radio" <?= $im->main == 1 ? 'checked="checked"' : ''; ?> name="default_image" value="<?=$im->id; ?>" />
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <div style="font-size: 16px; color: red;">Нет загруженных фото!</div>
<?php endif; ?>
