<form id="myForm" class="rowSection validat" method="post" action="">
    <div class="col-md-12" id="orderAddPosition">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-horizontal row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Количество</label>
                        <div class="col-md-10">
                            <input class="form-control valid" type="number" min="0" max="" name="count" value="<?php echo $obj->count; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Группа</label>
                        <div class="col-md-10">
                            <select name="parent_id" class="form-control">
                                <option value="0">Нет</option>
                                <?php echo $tree; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Размер</label>
                        <div class="col-md-10">
                            <select name="size_id" class="form-control">
                                <option value="0">Нет</option>
                                <?php foreach( $sizes as $size ): ?>
                                    <option value="<?php echo $size->id; ?>" <?php echo $size->id == $obj->size_id ? 'selected' : ''; ?>><?php echo $size->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="catalog_id" value="" />
                </div>
                <div class="form-group">
                    <label class="control-label">Список товаров</label>
                    <div id="setItemsHere">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>