<form id="myForm" class="rowSection validat" method="post" action="">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-horizontal row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Способ оплаты</label>
                        <div class="col-md-10">
                            <select name="payment" class="form-control">
                                <?php foreach ($payment as $id => $name): ?>
                                    <option value="<?php echo $id; ?>" <?php echo $obj->payment == $id ? 'selected' : ''; ?>><?php echo $name; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Способ доставки</label>
                        <div class="col-md-10">
                            <select name="delivery" class="form-control">
                                <?php foreach ($delivery as $id => $name): ?>
                                    <option value="<?php echo $id; ?>" <?php echo $obj->delivery == $id ? 'selected' : ''; ?>><?php echo $name; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="number" <?php echo $obj->delivery != 2 ? 'style="display: none;"' : ''; ?>>
                        <label class="col-md-2 control-label">Номер отделения Новой почты</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="number" value="<?php echo $obj->number; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Имя</label>
                        <div class="col-md-10">
                            <input class="form-control valid" type="text" name="name" value="<?php echo $obj->name; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Номер телефона</label>
                        <div class="col-md-10">
                            <input class="form-control valid" type="text" name="phone" value="<?php echo $obj->phone; ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function(){
        $('select[name="delivery"]').on('change', function(){
            if( $(this).val() == 2 ) {
                $('#number').show();
            } else {
                $('#number').hide();
            }
        });
    });
</script>