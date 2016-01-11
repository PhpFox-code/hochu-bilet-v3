<form id="myForm" class="rowSection validat" method="post" action="">
    <input type="hidden" name="id" value="<?php echo $obj->id; ?>" />
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-horizontal row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>
                    <?php foreach ($result as $obj): ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label"><?php echo $obj->name; ?></label>
                            <div class="col-md-10">
                                <?php if ($obj->type == 1): ?>
                                    <textarea name="FORM[<?php echo $obj->id; ?>]" rows="10"><?php echo $obj->zna; ?></textarea>
                                <?php elseif($obj->type == 2): ?>
                                    <textarea name="FORM[<?php echo $obj->id; ?>]" class="tinymceEditor" rows="10"><?php echo $obj->zna; ?></textarea>
                                <?php else: ?>
                                    <input class="form-control valid" type="text" name="FORM[<?php echo $obj->id; ?>]" value="<?php echo $obj->zna; ?>"/>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</form>