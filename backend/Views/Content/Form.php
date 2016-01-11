<form id="myForm" class="rowSection validat" method="post" action="">
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
                        <label class="control-label">Наименование страницы</label>
                        <div class="">
                            <input class="form-control translitSource valid" name="FORM[name]" type="text" value="<?php echo $obj->name; ?>" />
                        </div>
                    </div>
                    <?php if ($obj->alias != 'index' && $obj->alias != 'contacts'): ?>
                        <div class="form-group">
                            <label class="control-label">
                                Псевдоним заголовка (Алиас)
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
                    <?php endif ?>
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
                        <label class="control-label">Контент</label>
                        <div class="">
                            <textarea class="tinymceEditor form-control" rows="20" name="FORM[text]"><?php echo stripslashes($obj->text); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="widget">
            <div class="widgetHeader">
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

            <div class="pageInfo alert alert-info">                 
                <div class="rowSection">
                    <div class="col-md-6"><strong>Опубликовано</strong></div>
                    <div class="col-md-6"><?php echo $obj->status == 1 ? 'Да' : 'Нет'; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Дата создания</strong></div>
                    <div class="col-md-6"><?php echo $obj->created_at ? date('d.m.Y H:i:s', $obj->created_at) : '---'; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Дата последнего изменения</strong></div>     
                    <div class="col-md-6"><?php echo $obj->updated_at ? date('d.m.Y H:i:s', $obj->updated_at) : '---'; ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php if ( $obj->id ): ?>
        <input type="hidden" name="id" value="<?php echo $obj->id; ?>" />
    <?php endif ?>
</form>