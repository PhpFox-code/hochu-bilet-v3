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
                        <label class="control-label">Дата</label>
                        <div class="">
                            <input type="text" name="FORM[date]" value="<?php echo $obj->date ? date('d.m.Y', $obj->date) : date('d.m.Y'); ?>" class="myPicker valid form-control input-width-medium" />
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
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <label class="control-label">Изображение</label>
                        <div class="">
                            <?php if (is_file( HOST . Core\HTML::media('images/news/original/'.$obj->image) )): ?>
                                <a href="<?php echo Core\HTML::media('images/news/original/'.$obj->image); ?>" rel="lightbox">
                                    <img src="<?php echo Core\HTML::media('images/news/small/'.$obj->image); ?>" />
                                </a>
                                <br />
                                <a href="/backend/<?php echo Core\Route::controller(); ?>/delete_image/<?php echo $obj->id; ?>">Удалить изображение</a>
                            <?php else: ?>
                                <input type="file" name="file" />
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Выводить на внутренней странице?</label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="show_image" value="0" type="radio" <?php echo !$obj->show_image ? 'checked' : ''; ?>>                            
                                Нет
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="show_image" value="1" type="radio" <?php echo $obj->show_image ? 'checked' : ''; ?>>
                                Да
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widgetHeader myWidgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Мета-данные
                </div>
            </div>
        
            <div class="widgetContent">
                
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <label class="control-label">
                            Заголовок страницы (h1)
                            <i class="fa-info-circle text-info bs-tooltip nav-hint liTipLink" title="Рекомендуется, чтобы тег h1 содержал ключевую фразу, которая частично или полностью совпадает с title" style="white-space: nowrap;"></i>
                        </label>
                        <div class="">
                            <input class="form-control" name="FORM[h1]" type="text" value="<?php echo $obj->h1; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            title
                            <i class="fa-info-circle text-info bs-tooltip nav-hint liTipLink" title="<p>Значимая для продвижения часть заголовка должна быть не более 12 слов</p><p>Самые популярные ключевые слова должны идти в самом начале заголовка и уместиться в первых 50 символов, чтобы сохранить привлекательный вид в поисковой выдаче.</p><p>Старайтесь не использовать в заголовке следующие знаки препинания – . ! ? – </p>" style="white-space: nowrap;"></i>
                        </label>
                        <div class="">
                            <input class="form-control" type="text" name="FORM[title]" value="<?php echo $obj->title; ?>" />
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label class="control-label">Ключевые слова (keywords)</label>
                        <div class="">
                            <textarea class="form-control" name="FORM[keywords]" rows="5"><?php echo $obj->keywords; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">Описание (description)</label>
                        <div class="">
                            <textarea class="form-control" name="FORM[description]" rows="5"><?php echo $obj->description; ?></textarea>
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

<script>
    $(function(){
        var pickerInit = function( selector ) {
            var date = $(selector).val();
            $(selector).datepicker({
                showOtherMonths: true,
                selectOtherMonths: false
            });
            $(selector).datepicker('option', $.datepicker.regional['ru']);
            var dateFormat = $(selector).datepicker( "option", "dateFormat" );
            $(selector).datepicker( "option", "dateFormat", 'dd.mm.yy' );
            $(selector).val(date);
        }
        pickerInit('.myPicker');
    });
</script>