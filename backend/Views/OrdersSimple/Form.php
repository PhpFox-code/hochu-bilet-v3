<form id="myForm" class="rowSection validat" method="post" action="">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-horizontal row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Опубликовано</label>
                        <div class="col-md-10">
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
                    <?php if ($obj->created_at): ?>
                        <div class="form-group">
                            <label style="padding-top:0;" class="col-md-2 control-label">Дата</label>
                            <div class="col-md-10">
                                <?php echo date( 'd.m.Y H:i:s', $obj->created_at ); ?>
                            </div>
                        </div>
                    <?php endif ?>
                    <div class="form-group">
                        <label style="padding-top:0;" class="col-md-2 control-label">IP</label>
                        <div class="col-md-10">
                            <?php echo $obj->ip; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="padding-top:0;" class="col-md-2 control-label">Товар</label>
                        <div class="col-md-10">
                            <?php if (!$item): ?>
                                <span style="color: #ccc; font-style: italic;">( Удален )</span>
                            <?php else: ?>
                                <a href="/backend/catalog/new/id/<?php echo $obj->id; ?>" target="_blank"><?php echo $item->name; ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="padding-top:0;" class="col-md-2 control-label">Номер телефона</label>
                        <div class="col-md-10">
                            <a href="tel:<?php echo $obj->phone; ?>"><?php echo $obj->phone; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $obj->id; ?>">
</form>