<div class="new clearFix">
    <?php if ( is_file(HOST.Core\HTML::media('images/news/small/'.$obj->image)) AND $obj->show_image ): ?>
        <div class="news_img">
            <img src="<?php echo Core\HTML::media('images/news/big/'.$obj->image); ?>" alt="" />
        </div>
    <?php endif; ?>
    <div class="wTxt">
        <?php echo $obj->text; ?>
        <a href="<?php echo Core\HTML::link('news'); ?>" class="back_to_news"><span>вернуться к списку новостей</span></a>
    </div>
</div>