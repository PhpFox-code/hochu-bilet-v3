<form id="myForm" class="rowSection validat" method="post" action="" enctype="multipart/form-data">
    <div class="col-md-10">
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
                        <label class="control-label">Название</label>
                        <div class="">
                            <input class="form-control translitSource valid" name="FORM[name]" type="text" value="<?php echo $obj->name; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            Алиас
                            <i class="fa-info-circle text-info bs-tooltip nav-hint" title="<b>Алиас (англ. alias - псевдоним)</b><br>Алиасы используются для короткого именования страниц. <br>Предположим, имеется страница с псевдонимом «<b>about</b>». Тогда для вывода этой страницы можно использовать или полную форму: <br><b>http://domain/?go=frontend&page=about</b><br>или сокращенную: <br><b>http://domain/about.\Core\html</b>"></i>
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
                        <label class="control-label">Название для печати</label>
                        <div class="">
                            <input class="form-control" name="FORM[print_name]" type="text" value="<?php echo $obj->print_name; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Название для печати (вторая строка)</label>
                        <div class="">
                            <input class="form-control" name="FORM[print_name_small]" type="text" value="<?php echo $obj->print_name_small; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Содержание</label>
                        <div class="">
                            <textarea class="tinymceEditor form-control" rows="20" name="FORM[text]"><?php echo stripslashes( $obj->text ); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    Дополнительные данные 
                </div>
            </div>
            <div class="widgetContent">
                <div class="col-md-2">
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
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Показывать на главной</label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="main_show" value="0" type="radio" <?php echo (!$obj->main_show AND $obj) ? 'checked' : ''; ?>>                            
                                Нет
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="main_show" value="1" type="radio" <?php echo ($obj->main_show OR !$obj) ? 'checked' : ''; ?>>
                                Да
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Дата</label>
                        <div class="">
                            <input type="text" name="FORM[event_date]" value="<?php echo $obj->event_date ? date('d.m.Y', $obj->event_date) : date('d.m.Y'); ?>" class="myPicker valid form-control input-width-medium" />
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Время</label>
                        <div class="">
                            <input type="text" name="FORM[event_time]" value="<?php echo $obj->event_time ? $obj->event_time : '19:00' ?>" class="valid form-control input-width-medium" />
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Место проведения</label>
                        <div>
                            <select name="FORM[place_id]" id="place_selector">
                                <?php foreach ($cities as $city): ?>
                                    <optgroup label="<?php echo $city['name'] ?>">

                                        <?php foreach ($city['places'] as $place): ?>
                                            <option value="<?php echo $place->id ?>" <?php echo $place->id == $obj->place_id ? 'selected' : '' ?>><?php echo $place->name ?></option>
                                        <?php endforeach ?>

                                    </optgroup>
                                <?php endforeach ?>
                                <option value="another" <?php echo is_null($obj->place_id) ? 'selected' : '' ?>>Другое</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>

                <div class="rowSection">
                    <div class="col-md-4" style="text-align: right;">Поле под афишей (вместо цены)</div>
                    <div class="col-md-8"><input type="text" name="FORM[add_field]" value="<?php echo $obj->add_field ?>" class="form-control" /></div>
                </div>
                <br>
                <div style="border: 2px solid #f7f7f8; padding 10px 0;">
                    <h2>Печать</h2>
                    <div class="rowSection">
                        <div class="col-md-4" style="text-align: right">
                            <button class="btn btn-primary" style="margin-left: 30px;" id="printPlace"
                                <?php echo (Core\User::caccess() == 'edit'
                                    OR Core\User::access()['afisha_print'] == 'edit') ? null : 'disabled'; ?>>Распечатать билет</button>
                            <label class="checkerWrap-inline">
                                <input name="print-type" value="base" type="radio" checked>Обычная</label>
                            <label class="checkerWrap-inline">
                                <input name="print-type" value="termo" type="radio">Термопринтер</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="selected-seats" id="tag2" />
                        </div>
                    </div>
                </div>
                <div style="border: 2px solid #f7f7f8; padding 10px 0;">
                    <h2>Бронь</h2>
                    <div class="rowSection">
                        <div class="col-md-4" style="text-align: right">
                            <button class="btn btn-primary" style="margin-left: 30px;" id="orderPlace"
                                <?php echo (Core\User::caccess() == 'edit'
                                    OR Core\User::access()['afisha_brone'] == 'edit') ? null : 'disabled'; ?>>Забронировать</button>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="selected-order-seats" id="tag2-2" />
                        </div>
                    </div>
                </div>
                
                <h4>Цены</h4>
                
                <div>
                    <div style="display: inline-block; background: #01ff23; width: 20px; height: 20px;"></div> - Оплаченные
                </div>
                
                <div>
                    <button class="btn btn-primary" id="addPrice" <?php echo Core\User::caccess() != 'edit' ? 'disabled' : null; ?>>Добавить цену</button>
                </div>
                <!-- <div class="col-md-8">
                </div> -->
                <div class="prices-list" style="padding-bottom: 10px;">
                </div>
                
                <div id="map_place"></div>
             
                <!-- Just template for one price row -->
                <div style="display:none" id="price_tpl">
                    <div class="one-price">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Стоимость</label>
                                <div class="">
                                    <input type="number" step="1" name="PLACES[cost][]" value="" class="form-control input-width-medium" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Цвет</label>
                                <div class="">
                                    <input type="text" name="PLACES[color][]" value="" class="form-control selColor" data-control="wheel"  data-position="bottom right" />
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="PLACES[place][]" class="place_list">
                        <div class="col-md2">
                            <button class="btn btn-info select_place_btn" style="margin-top:23px;">Выбрать места</button>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
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
                            <?php if (is_file( HOST.\Core\HTML::media('images/afisha/medium/'.$obj->image) )): ?>
                                <a href="<?php echo \Core\HTML::media('images/afisha/medium/'.$obj->image); ?>" rel="lightbox">
                                    <img src="<?php echo \Core\HTML::media('images/afisha/medium/'.$obj->image); ?>" />
                                </a>
                                <br />
                                <a href="/backend/<?php echo \Core\Route::controller(); ?>/delete_image/<?php echo $obj->id; ?>">Удалить изображение</a>
                            <?php else: ?>
                                <input type="file" name="file" />
                            <?php endif ?>
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

<div style="display:none" id="custom_place_fields">
    <div class="form-group">
        <label class="control-label">Город (область) проведения</label>
        <div>
            <select name="FORM[city_id]">
                <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $city['id'] ?>" <?php echo $city['id'] == $obj->city_id ? 'selected' : '' ?>><?php echo $city['name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Название места</label>
        <div class="">
            <input class="form-control valid" name="FORM[place_name]" type="text" value="<?php echo $obj->place_name; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Цена (от)</label>
        <div class="">
            <input class="form-control" name="FORM[cost_from]" type="number" step="10" value="<?php echo $obj->cost_from; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Цена (до)</label>
        <div class="">
            <input class="form-control " name="FORM[cost_to]" type="number" step="10" value="<?php echo $obj->cost_to; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Внешняя ссылка( Должна начинаться с <b>http://</b> или <b>https://</b> или <b>//</b>)</label>
        <div class="">
            <input class="form-control " name="FORM[url]" type="url" value="<?php echo $obj->url; ?>" />
        </div>
    </div>
</div>

<script>
    $(function(){
        // init
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