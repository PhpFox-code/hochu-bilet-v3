<div class="widget box">
    <div class="widgetHeader"><div class="widgetTitle"><i class="fa-reorder"></i>Добавить значение</div></div>
    <div class="widgetContent">
        <table class="table table-striped table-bordered table-hover checkbox-wrap">
            <thead>
                <tr>
                    <th class="align-center">Название</th>
                    <th class="align-center">Цвет</th>
                    <th class="align-center">Алиас</th>
                    <th class="align-center"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="align-center"><input type="text" id="sName" class="form-control" value="" /></td>
                    <td class="align-center"><input type="text" id="sColor" class="form-control hue" value="#ffffff" /></td>
                    <td class="align-center"><input type="text" id="sAlias" class="form-control" value="" /></td>
                    <td class="align-center">
                        <a title="Сохранить" id="sSave" href="#" class="btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;">
                            <i class="fa-fixed-width">&#xf0c7;</i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="widget box">
    <div class="widgetHeader"><div class="widgetTitle"><i class="fa-reorder"></i>Список значений</div></div>
    <div class="widgetContent" id="sValuesList">
        <table class="table table-striped table-bordered table-hover checkbox-wrap" data-specification="<?php echo Core\Route::param('id'); ?>">
            <thead>
                <tr>
                    <th class="align-center">Название</th>
                    <th class="align-center">Цвет</th>
                    <th class="align-center">Алиас</th>
                    <th class="align-center">Статус</th>
                    <th class="align-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $list as $obj ): ?>
                    <tr data-id="<?php echo $obj->id; ?>">
                        <td class="align-center"><input type="text" class="form-control sName" value="<?php echo $obj->name; ?>" /></td>
                        <td class="align-center"><input type="text" class="form-control sColor hue" value="<?php echo $obj->color; ?>" /></td>
                        <td class="align-center"><input class="form-control sAlias" type="text" value="<?php echo $obj->alias; ?>" /></td>
                        <td class="align-center">
                            <label class="ckbxWrap" style="top: 8px; left: 6px;">
                                <input class="sStatus" type="checkbox" value="1" <?php echo $obj->status ? 'checked' : ''; ?> />
                            </label>
                        </td>
                        <td class="align-center">
                            <a title="Сохранить изменения" href="#" class="sSave btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;"><i class="fa-fixed-width">&#xf0c7;</i></a>
                            <a title="Удалить" href="#" class="sDelete btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;"><i class="fa-trash-o"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="sParametersColor" data-id="<?php echo Core\Route::param('id'); ?>"></div>


<!-- SCRIPT ZONE -->
<script>
    $(function(){
        if( $('#sParametersColor').length ) {
            // Start colorpicker
            var setMinicolor = function(){
                $('.hue').each( function() {
                    $(this).minicolors({
                        control: 'hue',
                        defaultValue: $(this).val(),
                        position: 'bottom right',
                        change: function(hex, opacity) {
                            if( !hex ) return;
                            if( opacity ) hex += ', ' + opacity;
                        },
                        theme: 'bootstrap'
                    });
                });
            }
            setMinicolor();
            // Specification id
            var sID = $('#sParametersColor').data('id');
            // Message with error for admin
            var generate_warning = function( message ) {
                $(document).alert2({
                    message: message,
                    headerCOntent: false,
                    footerContent: false,
                    typeMessage: 'warning' //false or 'warning','success','info','danger'
                });
            }
            // Set checkbox
            var checkboxStart = function( el ) {
                var parent = el.parent();
                if(parent.is('label')){
                    if(el.prop('checked')){
                        parent.addClass('checked');
                    } else {
                        parent.removeClass('checked');
                    }
                }
            }
            // Generate a row from object
            var generateTR = function( obj ) {
                var html = '';
                html  = '<tr data-id="'+obj.id+'">';
                html += '<td class="align-center">';
                html += '<input type="text" class="form-control sName" value="'+obj.name+'" />';
                html += '</td>';
                html += '<td class="align-center">';
                html += '<input type="text" class="form-control sColor hue" value="'+obj.color+'" />';
                html += '</td>';
                html += '<td class="align-center">';
                html += '<input class="form-control sAlias" type="text" value="'+obj.alias+'" />';
                html += '</td>';
                html += '<td class="align-center"><label class="ckbxWrap" style="top: 8px; left: 6px;">';
                if ( parseInt( obj.status ) == 1 ) {
                    html += '<input class="sStatus" type="checkbox" value="1" checked />';
                } else {
                    html += '<input class="sStatus" type="checkbox" value="1" />';
                }
                html += '</label></td>';
                html += '<td class="align-center">';
                html += '<a title="Сохранить изменения" href="#" class="sSave btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;"><i class="fa-fixed-width">&#xf0c7;</i></a>';
                html += '<a title="Удалить" href="#" class="sDelete btn btn-xs bs-tooltip liTipLink" style="white-space: nowrap; margin-top: 7px;"><i class="fa-trash-o"></i></a>';
                html += '</td>';
                html += '</tr>';
                return html;
            }
            // Add a row
            $('#sSave').on('click', function(e){
                e.preventDefault();
                loader($('#sValuesList'), 1);
                var name = $('#sName').val();
                var color = $('#sColor').val();
                var alias = $('#sAlias').val();
                $.ajax({
                    url: '/backend/ajax/addColorSpecificationValue',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        specification_id: sID,
                        name: name,
                        color: color,
                        alias: alias
                    },
                    success: function(data){
                        if( data.success ) {
                            var html = '';
                            if( data.result.length ) {
                                for( var i = 0; i < data.result.length; i++ ) {
                                    html += generateTR(data.result[i]);
                                }
                            }
                            $('#sValuesList tbody').html(html);
                            $('#sValuesList input[type=checkbox]').each(function(){ checkboxStart($(this)); });
                            $('#sValuesList input[type=checkbox]').on('change',function(){ checkboxStart($(this)); });
                            $('#sName').val('');
                            $('#sAlias').val('');
                            $('#sColor').val('#ffffff');
                            setMinicolor();
                        } else {
                            generate_warning(data.error);
                        }
                        loader($('#sValuesList'), 0);
                    }
                });
            });
            // Save a row
            $('#sValuesList').on('click', '.sSave', function(e){
                e.preventDefault();
                loader($('#sValuesList'), 1);
                var tr = $(this).closest('tr');
                var id = tr.data('id');
                var name = tr.find('.sName').val();
                var color = tr.find('.sColor').val();
                var alias = tr.find('.sAlias').val();
                var status = 0;
                if( tr.find('.sStatus').parent().hasClass('checked') ) {
                    status = 1;
                }
                $.ajax({
                    url: '/backend/ajax/editColorSpecificationValue',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        specification_id: sID,
                        name: name,
                        status: status,
                        id: id,
                        color: color,
                        alias: alias
                    },
                    success: function(data){
                        if( data.success ) {
                            var html = '';
                            if( data.result.length ) {
                                for( var i = 0; i < data.result.length; i++ ) {
                                    html += generateTR(data.result[i]);
                                }
                            }
                            $('#sValuesList tbody').html(html);
                            $('#sValuesList input[type=checkbox]').each(function(){ checkboxStart($(this)); });
                            $('#sValuesList input[type=checkbox]').on('change',function(){ checkboxStart($(this)); });
                            setMinicolor();
                        } else {
                            generate_warning(data.error);
                        }
                        loader($('#sValuesList'), 0);
                    }
                });
            });
            // Delete a row
            $('#sValuesList').on('click', '.sDelete', function(e){
                e.preventDefault();
                loader($('#sValuesList'), 1);
                var tr = $(this).closest('tr');
                var id = tr.data('id');
                $.ajax({
                    url: '/backend/ajax/deleteSpecificationValue',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id
                    },
                    success: function(data){
                        if( data.success ) {
                            tr.remove();
                        } else {
                            generate_warning(data.error);
                        }
                        loader($('#sValuesList'), 0);
                    }
                });
            });
        }
    });
</script>