<form id="myForm" class="rowSection validat" method="post" action="" enctype="multipart/form-data">
    <div class="col-md-7">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Основные данные
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Опубликовано</label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="status" value="0" type="radio" <?php echo (!$obj->status AND $obj) ? 'checked' : ''; ?>>                            
                                Нет
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="status" value="1" type="radio" <?php echo ($obj->status OR !$obj) ? 'checked' : ''; ?>>
                                Да
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Название</label>
                        <div class="">
                            <input class="form-control translitSource" name="FORM[small]" type="text" value="<?php echo $obj->small; ?>" />
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="control-label">Крупным шрифтом</label>
                        <div class="">
                            <input class="form-control translitSource" name="FORM[big]" type="text" value="<?php #echo $obj->big; ?>" />
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="control-label">Ссылка</label>
                        <div class="">
                            <input class="form-control translitSource" name="FORM[url]" type="text" value="<?php echo $obj->url; ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="widget">
            <div class="widgetHeader myWidgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Изображение
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div class="">
                            <?php if (is_file( HOST.Core\HTML::media('images/banners/'.$obj->image) )): ?>
                                <a href="<?php echo Core\HTML::media('images/banners/'.$obj->image); ?>" rel="lightbox">
                                    <img src="<?php echo Core\HTML::media('images/banners/'.$obj->image); ?>" />
                                </a>
                                <br />
                                <?php if(Core\User::caccess() == 'edit'): ?>
                                    <a href="/backend/<?php echo Core\Route::controller(); ?>/delete_image/<?php echo $obj->id; ?>">Удалить изображение</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <input type="file" name="file" />
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ( $obj->id ): ?>
        <input type="hidden" name="id" value="<?php echo $obj->id; ?>" />
    <?php endif; ?>
</form>