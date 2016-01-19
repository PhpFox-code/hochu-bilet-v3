<form id="myForm" class="rowSection validat" method="post" action="" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Общая информация
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Название</label>
                        <div class="">
                            <input class="form-control valid" name="FORM[name]" type="text" value="<?php echo $obj->name; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Описание</label>
                        <div class="">
                            <input class="form-control" name="FORM[description]" type="text" value="<?php echo $obj->description; ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Доступ к разделам
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>
                    <?php foreach( Core\Config::get('access') AS $el ): ?>
                        <?php $ac = \Core\Arr::get($access, $el['controller'], 'no'); ?>
<!--                        Custom view -->
                        <?php if (array_key_exists('customView', $el)): ?>
                            <?php if ($el['customView'] == 'boolean'): ?>
                                <div class="form-group">
                                    <label class="control-label"><?php echo Core\Arr::get($el, 'name'); ?></label>
                                    <div class="">
                                        <label class="checkerWrap-inline">
                                            <input name="<?php echo $el['controller']; ?>" <?php echo $ac == 'no' ? 'checked' : NULL; ?> value="no" type="radio" />
                                            Нет
                                        </label>
                                        <?php if( \Core\Arr::get($el, 'edit', 1) ): ?>
                                            <label class="checkerWrap-inline">
                                                <input name="<?php echo $el['controller']; ?>" <?php echo $ac == 'edit' ? 'checked' : NULL; ?> value="edit" type="radio" />
                                                Да
                                            </label>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
<!--                        Default view -->
                        <?php else: ?>
                            <div class="form-group">
                                <label class="control-label"><?php echo Core\Arr::get($el, 'name'); ?></label>
                                <div class="">
                                    <label class="checkerWrap-inline">
                                        <input name="<?php echo $el['controller']; ?>" <?php echo $ac == 'no' ? 'checked' : NULL; ?> value="no" type="radio" />
                                        Нет прав
                                    </label>
                                    <?php if( \Core\Arr::get($el, 'view', 1) ): ?>
                                        <label class="checkerWrap-inline">
                                            <input name="<?php echo $el['controller']; ?>" <?php echo $ac == 'view' ? 'checked' : NULL; ?> value="view" type="radio" />
                                            Только просмотр
                                        </label>
                                    <?php endif; ?>
                                    <?php if( \Core\Arr::get($el, 'edit', 1) ): ?>
                                        <label class="checkerWrap-inline">
                                            <input name="<?php echo $el['controller']; ?>" <?php echo $ac == 'edit' ? 'checked' : NULL; ?> value="edit" type="radio" />
                                            Просмотр и редактирование
                                        </label>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</form>