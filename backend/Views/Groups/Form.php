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
                            <input class="form-control translitSource valid" name="FORM[name]" type="text" value="<?php echo $obj->name; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            Алиас
                            <i class="fa-info-circle text-info bs-tooltip nav-hint" title="<b>Алиас (англ. alias - псевдоним)</b><br>Алиасы используются для короткого именования страниц. <br>Предположим, имеется страница с псевдонимом «<b>about</b>». Тогда для вывода этой страницы можно использовать или полную форму: <br><b>http://domain/?go=frontend&page=about</b><br>или сокращенную: <br><b>http://domain/about.html</b>"></i>
                        </label>
                        <div class="">
                            <div class="input-group">
                                <input class="form-control translitConteiner valid" name="FORM[alias]" type="text" value="<?php echo $obj->alias; ?>" />
                                <span class="input-group-btn">
                                    <button class="btn translitAction" type="button">Заполнить автоматически</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Группа</label>
                        <div class="">
                            <div class="controls">
                                <select class="form-control valid" name="FORM[parent_id]">
                                    <option value="0">Вехний уровень</option>
                                    <?php echo $tree; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Содержание</label>
                        <div class="">
                            <textarea class="tinymceEditor form-control" rows="20" name="FORM[text]"><?php echo $obj->text; ?></textarea>
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

            <div class="form-group">
                <label class="control-label">Изображение</label>
                <div class="">
                    <?php if (is_file( HOST . Core\HTML::media('images/catalog_tree/'.$obj->image))): ?>
                        <a href="<?php echo Core\HTML::media('images/catalog_tree/'.$obj->image); ?>" rel="lightbox">
                            <img src="<?php echo Core\HTML::media('images/catalog_tree/'.$obj->image); ?>" />
                        </a>
                        <br />
                        <a href="/backend/<?php echo Core\Route::controller(); ?>/delete_image/<?php echo $obj->id; ?>" onclick="return confirm('Это действие удалит изображение безвозвратно. Продолжить?');">Удалить изображение</a>
                    <?php else: ?>
                        <input type="file" name="file" />
                    <?php endif ?>
                </div>
            </div>

            <div class="widgetHeader myWidgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Допустимые параметры товаров
                </div>
            </div>
        
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <label class="control-label">Бренды</label>
                        <div class="">
                            <div class="controls">
                                <select class="form-control valid" name="BRANDS[]" multiple="10" style="height:150px;">
                                    <?php foreach( $brands as $brand ): ?>
                                        <option value="<?php echo $brand->id; ?>" <?php echo in_array($brand->id, $groupBrands) ? 'selected' : ''; ?>><?php echo $brand->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Размеры</label>
                        <div class="">
                            <div class="controls">
                                <select class="form-control valid" name="SIZES[]" multiple="10" style="height:150px;">
                                    <?php foreach( $sizes as $size ): ?>
                                        <option value="<?php echo $size->id; ?>" <?php echo in_array($size->id, $groupSizes) ? 'selected' : ''; ?>><?php echo $size->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Характеристики</label>
                        <div class="">
                            <div class="controls">
                                <select class="form-control valid" name="SPEC[]" multiple="10" style="height:150px;">
                                    <?php foreach( $specifications as $spec ): ?>
                                        <option value="<?php echo $spec->id; ?>" <?php echo in_array($spec->id, $groupSpec) ? 'selected' : ''; ?>><?php echo $spec->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
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