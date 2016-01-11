<form id="myForm" class="rowSection validat" method="post" action="">
    <div class="col-md-7">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Данные для отправки
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Тема</label>
                        <div class="">
                            <input class="form-control translitSource valid" name="FORM[subject]" type="text" value="<?php echo $obj->subject; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Содержание <span style="color:red;">*</span></label>
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
                    Заменяемые данные
                </div>
            </div>
            <div class="pageInfo alert alert-info">
                <div class="rowSection">
                    <div class="col-md-6"><strong>Ссылка для отписки от рассылки</strong></div>
                    <div class="col-md-6">{{unsubscribe}}</div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Имя пользователя</strong></div>
                    <div class="col-md-6">{{user_name}}</div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Доменное имя сайта</strong></div>
                    <div class="col-md-6">{{site}}</div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Текущая дата в формате dd.mm.YYYY</strong></div>
                    <div class="col-md-6">{{date}}</div>
                </div>
            </div>
        </div>
    </div>
</form>