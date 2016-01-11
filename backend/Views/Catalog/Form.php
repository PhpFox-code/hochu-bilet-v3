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
                        <label class="control-label">Артикул</label>
                        <div class="">
                            <input class="form-control" name="FORM[artikul]" type="text" value="<?php echo $obj->artikul; ?>" />
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
                        <label class="control-label">Группа</label>
                        <div class="">
                            <div class="controls">
                                <select class="form-control valid" name="FORM[parent_id]" id="parent_id">
                                    <option value="">Не выбрано</option>
                                    <?php echo $tree; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Акция</label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="sale" value="0" type="radio" <?php echo !$obj->sale ? 'checked' : ''; ?>>                            
                                Нет
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="sale" value="1" type="radio" <?php echo $obj->sale ? 'checked' : ''; ?>>
                                Да
                            </label>
                        </div>
                    </div>
                    <div class="form-group costField">
                        <label class="control-label">Цена</label>
                        <div class="">
                            <input class="form-control valid" name="FORM[cost]" type="number" step="10" value="<?php echo $obj->cost; ?>" />
                        </div>
                    </div>
                    <div class="form-group hiddenCostField" <?php echo !$obj->sale ? 'style="display:none;"' : ''; ?>>
                        <label class="control-label">Старая цена</label>
                        <div class="">
                            <input class="form-control" name="FORM[cost_old]" type="number" step="10" value="<?php echo $obj->cost_old; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Новинка</label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="new" value="0" type="radio" <?php echo !$obj->new ? 'checked' : ''; ?>>                            
                                Нет
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="new" value="1" type="radio" <?php echo $obj->new ? 'checked' : ''; ?>>
                                Да
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Популярный товар</label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="top" value="0" type="radio" <?php echo !$obj->top ? 'checked' : ''; ?>>                            
                                Нет
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="top" value="1" type="radio" <?php echo $obj->top ? 'checked' : ''; ?>>
                                Да
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Наличие</label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="available" value="0" type="radio" <?php echo !$obj->available ? 'checked' : ''; ?>>                            
                                Нет в наличии
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="available" value="1" type="radio" <?php echo $obj->available == 1 ? 'checked' : ''; ?>>
                                Есть в наличии
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="available" value="2" type="radio" <?php echo $obj->available == 2 ? 'checked' : ''; ?>>
                                Под заказ
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Пол</label>
                        <div class="">
                            <label class="checkerWrap-inline">
                                <input name="sex" value="0" type="radio" <?php echo !$obj->sex ? 'checked' : ''; ?>>                            
                                Мужчинам
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="sex" value="1" type="radio" <?php echo $obj->sex == 1 ? 'checked' : ''; ?>>
                                Женщинам
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="sex" value="2" type="radio" <?php echo $obj->sex == 2 ? 'checked' : ''; ?>>
                                Унисекс
                            </label>
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
                    Характеристики
                </div>
            </div>
            <div class="widgetContent">
                <div class="form-group">
                    <label class="control-label">Бренд</label>
                    <div class="">
                        <div class="controls">
                            <select class="form-control" name="FORM[brand_id]" id="brand_id">
                                <option value="0">Нет</option>
                                <?php foreach( $brands as $brand ): ?>
                                    <option value="<?php echo $brand->id; ?>" <?php echo $brand->id == $obj->brand_id ? 'selected' : ''; ?>><?php echo $brand->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Модель</label>
                    <div class="">
                        <div class="controls">
                            <select class="form-control" name="FORM[model_id]" id="model_id">
                                <option value="0">Нет</option>
                                <?php foreach( $models as $model ): ?>
                                    <option value="<?php echo $model->id; ?>" <?php echo $model->id == $obj->model_id ? 'selected' : ''; ?>><?php echo $model->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Размеры</label>
                    <div class="">
                        <div class="controls">
                            <select class="form-control" name="SIZES[]" multiple="10" style="height:150px;" id="sizes">
                                <?php foreach( $sizes as $size ): ?>
                                    <option value="<?php echo $size->id; ?>" <?php echo in_array($size->id, $itemSizes) ? 'selected' : ''; ?>><?php echo $size->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-vertical row-border" id="specGroup">
                    <?php foreach ($specifications as $spec): ?>
                        <?php if (count($specValues[$spec->id])): ?>
                            <div class="form-group">
                                <label class="control-label"><?php echo $spec->name; ?></label>
                                <div class="">
                                    <div class="controls">
                                        <?php if ($spec->type_id == 3): ?>
                                            <select class="form-control" name="SPEC[<?php echo $spec->id; ?>][]" multiple="10" style="height:150px;">
                                                <?php foreach( $specValues[$spec->id] as $value ): ?>
                                                    <option value="<?php echo $value->id; ?>" <?php echo (isset($specArray[$spec->id]) AND in_array($value->id, $specArray[$spec->id])) ? 'selected' : ''; ?>><?php echo $value->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif ?>
                                        <?php if ($spec->type_id == 2 OR $spec->type_id == 1): ?>
                                            <select class="form-control" name="SPEC[<?php echo $spec->id; ?>]">
                                                <option value="0">Нет</option>
                                                <?php foreach( $specValues[$spec->id] as $value ): ?>
                                                    <option value="<?php echo $value->id; ?>" <?php echo (isset($specArray[$spec->id]) AND $specArray[$spec->id] == $value->id) ? 'selected' : ''; ?>><?php echo $value->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
            <?php if( $obj->id ): ?>
                <div class="pageInfo alert alert-info">
                    <div class="rowSection">
                        <div class="col-md-6"><strong>Опубликован</strong></div>
                        <div class="col-md-6"><?php echo $obj->status == 1 ? 'Да' : 'Нет'; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>Дата создания товара</strong></div>
                        <div class="col-md-6"><?php echo $obj->created_at ? date('d.m.Y H:i:s', $obj->created_at) : '---'; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>Дата последнего редактирования</strong></div>     
                        <div class="col-md-6"><?php echo $obj->updated_at ? date('d.m.Y H:i:s', $obj->updated_at) : '---'; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>Количество просмотров товара</strong></div>
                        <div class="col-md-6"><?php echo $obj->views; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>Количество заказов в один клик</strong></div>     
                        <div class="col-md-6"><?php echo $countSimpleOrders; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>Количество заказов с этим товаром</strong></div>     
                        <div class="col-md-6"><?php echo (int) $countOrders; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>Количество успешно купленных товаров</strong></div>     
                        <div class="col-md-6"><?php echo (int) $happyCount; ?></div>
                    </div>
                    <div class="rowSection">
                        <div class="col-md-6"><strong>Сумма успешно купленных товаров</strong></div>
                        <div class="col-md-6"><?php echo (int) $happyMoney; ?> грн</div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>

    <?php if ( $obj->id ): ?>
        <div class="rowSection">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widgetHeader myWidgetHeader">
                        <div class="widgetTitle">
                            <i class="fa-reorder"></i>
                            Загрузка фото
                        </div>
                    </div>

                    <div class="widgetContent">
                        <div class="form-vertical row-border">
                            <div class="form-group" style="display: inline-block; margin-right: 15px; border-right: 1px solid #ececec; vertical-align: top; padding-right: 15px;">
                                <div id="leftpanel" style="padding-left:25px;">
                                    <div id="actions">
                                        <span id="info-count-image">Изображений не выбрано</span><br/>
                                        Общий размер: <span id="info-size-image">0</span> Кб<br/><br/>
                                        <button id="upload-all-image">Загрузить все</button>
                                        <button id="cancel-all-image">Отменить все</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="display: inline-block;border-top: 0; vertical-align: top; padding-top: 5px;">
                                Чтобы добавить картинки, выберите их в поле<br/><br/>
                                <input type="file" name="price" id="file-field-image" /><br/><br/>
                                <span id="dropBox-label">... или просто перетащите их в область ниже &dArr;</span>
                            </div>
                            <div class="form-group">
                                <div id="image-container">
                                    <ul id="image-list"></ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="block title_1">Загруженные фото:</label>
                                <div id="uploaded-images" class="uploaded_images">
                                    <?=$show_images; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="id_good" name="id" value="<?php echo $obj->id; ?>" />
    <?php endif; ?>
</form>

<?php if ( $obj->id ): ?>
    <script type="text/javascript" src="/Plugins/jsuploader/jquery.damnUploader.js"></script>
    <script type="text/javascript" src="/Plugins/jsuploader/interfaceImageApi.js"></script>

    <script>
        $(function(){
            $('#uploaded-images').on('click', 'input[name="default_image"]', function(){
                var id = $(this).val();
                var catalog_id = $('#id_good').val();
                $.ajax({
                    url: '/backend/ajax/set_default_image',
                    type: 'POST',
                    data: {
                        id: id,
                        catalog_id: catalog_id
                    }
                });
            });

            $('#uploaded-images').on('click', '.delete-catalog-image', function(){
                if( !confirm( 'Это действие удалит фото. Продолжить?' ) ) { return false; }

                var it = $(this);
                var id = it.data('id');
                $.ajax({
                    url: '/backend/ajax/delete_catalog_photo',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    complete: function(){
                        it.closest('div.catalog-image').remove();
                        if( $('#uploaded-images > div').length == 0 ) {
                            $('#uploaded-images').html('<div style="font-size: 16px; color: red;">Нет загруженных фото!</div>');
                        }
                    }
                });
            });

            $("#uploaded-images").sortable({                
                stop: function () {
                    var order = [];
                    $("#uploaded-images > .catalog-image").each(function() {
                        order.push($(this).attr('data-image'));
                    });         
                    $.ajax({
                        type: 'POST',
                        url: '/backend/ajax/sort_images',
                        dataType: 'JSON',
                        data: {
                            order : order                   
                        },
                        success: {}
                    });
                }       
            });
        });
    </script>
<?php endif; ?>

<script>
    $(function(){
        $('input[name="sale"]').on('change', function(){
            var val = parseInt( $(this).val() );
            if( val ) {
                var cost = $('input[name="FORM[cost]"]').val();
                var cost_old = $('input[name="FORM[cost_old]"]').val();
                $('input[name="FORM[cost]"]').val(cost_old);
                $('input[name="FORM[cost_old]"]').val(cost);
                $('.hiddenCostField').show();
            } else {
                var cost = $('input[name="FORM[cost]"]').val();
                var cost_old = $('input[name="FORM[cost_old]"]').val();
                $('input[name="FORM[cost]"]').val(cost_old);
                $('input[name="FORM[cost_old]"]').val(cost);
                $('.hiddenCostField').hide();
            }
        });

        $('#parent_id').on('change', function(){
            var catalog_tree_id = $(this).val();
            $.ajax({
                url: '/backend/ajax/getSpecificationsByCatalogTreeID',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    catalog_tree_id: catalog_tree_id
                },
                success: function(data){
                    var html = '<option value="0">Нет</option>';
                    for(var i = 0; i < data.brands.length; i++) {
                        html += '<option value="' + data.brands[i].id + '">' + data.brands[i].name + '</option>';
                    }
                    $('#brand_id').html(html);
                    html = '';
                    for(var i = 0; i < data.sizes.length; i++) {
                        html += '<option value="' + data.sizes[i].id + '">' + data.sizes[i].name + '</option>';
                    }
                    $('#sizes').html(html);
                    html = '';
                    for(var i = 0; i < data.specifications.length; i++) {
                        var spec = data.specifications[i];
                        if( data.specValues[spec.id].length ) {
                            var values = data.specValues[spec.id];
                            html += '<div class="form-group"><label class="control-label">'+spec.name+'</label>';
                            html += '<div class=""><div class="controls">';
                            if( parseInt( spec.type_id ) == 3 ) {
                                html += '<select class="form-control" name="SPEC['+spec.id+'][]" multiple="10" style="height:150px;">';
                                for( var j = 0; j < values.length; j++ ) {
                                    var val = values[j];
                                    html += '<option value="'+val.id+'">'+val.name+'</option>';
                                }
                                html += '</select>';
                            }
                            if( parseInt( spec.type_id ) == 2 || parseInt( spec.type_id ) == 1 ) {
                                html += '<select class="form-control" name="SPEC['+spec.id+']">';
                                html +='<option value="0">Нет</option>';
                                for( var j = 0; j < values.length; j++ ) {
                                    var val = values[j];
                                    html += '<option value="'+val.id+'">'+val.name+'</option>';
                                }
                                html += '</select>';
                            }
                            html += '</div></div></div>';
                        }
                    }
                    $('#specGroup').html(html);
                }
            });
        });

        $('#brand_id').on('change', function(){
            var brand_id = $(this).val();
            $.ajax({
                url: '/backend/ajax/getModelsByBrandID',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    brand_id: brand_id
                },
                success: function(data){
                    var html = '<option value="0">Нет</option>';
                    for(var i = 0; i < data.options.length; i++) {
                        html += '<option value="' + data.options[i].id + '">' + data.options[i].name + '</option>';
                    }
                    $('#model_id').html(html);
                }
            });
        });
    });
</script>