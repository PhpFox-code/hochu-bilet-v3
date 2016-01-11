<form id="myForm" class="rowSection validat" method="post" action="" enctype="multipart/form-data">
    <div class="col-md-7">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Личные данные
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
                        <label class="control-label">Имя</label>
                        <div class="">
                            <input class="form-control translitSource valid" name="FORM[name]" type="text" value="<?php echo $obj->name; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">E-Mail</label>
                        <div class="">
                            <input class="form-control translitSource valid" name="FORM[email]" type="text" value="<?php echo $obj->email; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            Номер телефона
                            <i class="fa-info-circle text-info bs-tooltip nav-hint liTipLink" title="Формат номера телефона: +38 (ХХХ) ХХХ-ХХ-ХХ, где Х - цифра от 0 до 9" style="white-space: nowrap;"></i>
                        </label>
                        <div class="">
                            <input class="form-control translitSource valid" name="FORM[phone]" type="text" value="<?php echo $obj->phone; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            Пароль
                            <i class="fa-info-circle text-info bs-tooltip nav-hint liTipLink" title="Если нет необходимости менять пароль, просто оставьте это поле пустым, тогда он не изменится" style="white-space: nowrap;"></i>
                        </label>
                        <div class="">
                            <input class="form-control translitSource" name="password" type="password" />
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
                    Статистика
                </div>
            </div>
        
            <div class="pageInfo alert alert-info">
                <div class="rowSection">
                    <div class="col-md-6"><strong>IP</strong></div>
                    <div class="col-md-6"><?php echo $obj->ip; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Опубликован</strong></div>
                    <div class="col-md-6"><?php echo $obj->status == 1 ? 'Да' : 'Нет'; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Дата создания аккаунта</strong></div>
                    <div class="col-md-6"><?php echo $obj->created_at ? date('d.m.Y H:i:s', $obj->created_at) : '---'; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Дата последней авторизации</strong></div>     
                    <div class="col-md-6"><?php echo $obj->last_login ? date('d.m.Y H:i:s', $obj->last_login) : '---'; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Количество авторизаций на сайте</strong></div>     
                    <div class="col-md-6"><?php echo (int) $obj->logins; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Количество проведенных заказов</strong></div>     
                    <div class="col-md-6"><?php echo (int) $countOrders; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Количество купленных товаров</strong></div>     
                    <div class="col-md-6"><?php echo (int) $happyCount; ?></div>
                </div>
                <div class="rowSection">
                    <div class="col-md-6"><strong>Общая сумма покупок</strong></div>     
                    <div class="col-md-6"><?php echo (int) $happyMoney; ?> грн</div>
                </div>
            </div>
        </div>
    </div>
    <?php if ( $obj->id ): ?>
        <input type="hidden" name="id" value="<?php echo $obj->id; ?>" />
    <?php endif; ?>
</form>