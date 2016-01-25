var generate = function( message, type ) {
    $(document).alert2({
        message: message,
        headerCOntent: false,
        footerContent: false,
        typeMessage: type //false or 'warning','success','info','danger'
    });
}

var loader = function( selector, type ) {
    if( type == 0 ) {
        $(selector).removeClass('refRel').removeClass('refShow');
    }
    if( type == 1 ) {
        var refPos = $(selector).css('position');
        if(refPos == 'static'){
            $(selector).addClass('refRel');
        }
        $(selector).addClass('refShow');
    }
}

$(document).ready(function(){
    var change_status = function( it, id ) {
        var current = it.data('status');
        var table = $('#parameters').data('table');
        $.ajax({
            url: '/backend/ajax/setStatus',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                current: current,
                table: table
            },
            success: function(data){
                it.data('status', data.status);
                var html;
                if(data.status == 1) {
                    it.attr('title', it.data('pub'));
                    html = '<i class="fa-check"></i>';
                    it.removeClass('btn-danger');
                    it.addClass('btn-success');
                } else {
                    it.attr('title', it.data('unpub'));
                    html = '<i class="fa-dot-circle-o"></i>';
                    it.removeClass('btn-success');
                    it.addClass('btn-danger');
                }
                it.html(html);
            }
        });
    }

    var pickerInit = function( selector ) {
        if(!$(selector).length) {
            return false;
        }
        $(selector).each(function(){
            var date = $(this).val();
            $(this).datepicker({
                showOtherMonths: true,
                selectOtherMonths: false
            });
            $(this).datepicker('option', $.datepicker.regional['ru']);
            var dateFormat = $(this).datepicker( "option", "dateFormat" );
            $(this).datepicker( "option", "dateFormat", 'dd.mm.yy' );
            $(this).val(date);
        });
    };
    pickerInit('.fPicker');


    $('.setStatus').on('click', function(e){
        e.preventDefault();
        var it = $(this);
        var id;
        if( it.attr( 'data-id' ) ) {
            id = it.attr( 'data-id' );
        } else {
            id = it.closest('li').data('id');
        }
        change_status( it, id );
    });

    $('.toolbar').on('click', '.delete-items', function(e){
        e.preventDefault();
        var ids = [];
        var id;
        $('input[type="checkbox"]').each(function(){
            if( $(this).prop('checked') ) {
                id = $(this).closest('li').data('id');
                if( id ) {
                    ids.push( id );
                } else {
                    ids.push( $(this).closest('tr').data('id') );
                }
            }
        });
        if( ids.length ) {
            if( !confirm( 'Это действие необратимо. Продолжить?' ) ) {
                return false;
            }
            loader( '.contentWrapMar', 1 );
            var table = $('#parameters').data('table');
            $.ajax({
                url: '/backend/ajax/deleteMass',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    ids: ids,
                    table: table
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        } else {
            generate( 'Нечего удалять!', 'warning' );
        }
    });

    $('.toolbar').on('click', '.publish', function(e){
        e.preventDefault();
        var ids = [];
        $('input[type="checkbox"]').each(function(){
            if( $(this).prop('checked') ) {
                id = $(this).closest('li').data('id');
                if( id ) {
                    ids.push( id );
                } else {
                    ids.push( $(this).closest('tr').data('id') );
                }
            }
        });
        if( ids.length ) {
            loader( '.contentWrapMar', 1 );
            var table = $('#parameters').data('table');
            var status = $(this).data('status');
            $.ajax({
                url: '/backend/ajax/setStatusMass',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    status: status,
                    ids: ids,
                    table: table
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        } else {
            generate( 'Нечему задавать статус!', 'warning' );
        }
    });

    if($('.changeField').length) {
        $('.changeField').on('click', function(e){
            e.preventDefault();
            var it = $(this);
            var id = it.data('id');
            var field = it.data('field');
            var table = $('#parameters').data('table');
            $.ajax({
                url: '/backend/ajax/change_field',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    field: field,
                    table: table
                },
                success: function(data){
                    if(data.current) {
                        it.text('Да');
                    } else {
                        it.text('Нет');
                    }
                }
            });
        });
    }

    if ( $('#orderParameters').length ) {
        $('button').on('click', function(){
            if( $(this).attr('href') ) {
                window.location.href = $(this).attr('href');
            } else {
                var it = $(this);
                var form = it.closest('.widgetContent');
                var action = form.data('ajax');
                if( action ) {
                    $.ajax({
                        url: '/backend/ajax/' + action,
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            data: form.find('input,select,textarea').serializeArray(),
                            id: $('#orderParameters').data('id')
                        }
                    });
                }
            }
        });
        $('select[name="delivery"]').on('change', function() {
            if( $(this).val() == 2 ) {
                $('input[name="number"]').closest('.form-group').show();
            } else {
                $('input[name="number"]').closest('.form-group').hide();
            }
        });

        var setAmount = function(){
            var amount = 0;
            var pos = 0;
            $('#orderItemsList tr').each(function(){
                var cost = parseInt( $(this).find('.tableOrderItemsCost').text() );
                var count = parseInt( $(this).find('.count').val() );
                amount += cost * count;
                pos += 1;
            });
            $('#orderAmount span').text(amount);
            $('#orderPositionsCount').text(pos);
        }
        $('#orderItems').on('click', function(){
            var it = $(this);
            var table = it.closest('.widgetContent').find('table');
            table.find('tr').each(function(){
                var it = $(this);
                var count = it.find('.count').val();
                var catalog_id = it.find('.catalog_id').val();
                var size_id = it.find('.size_id').val();
                $.ajax({
                    url: '/backend/ajax/orderItems',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        count: count,
                        catalog_id: catalog_id,
                        size_id: size_id,
                        id: $('#orderParameters').data('id')
                    },
                    success: function(data) {}
                });
            });
            setAmount();
        });
        $('.orderPositionDelete').on('click', function(e){
            e.preventDefault();
            var it = $(this).closest('tr');
            var count = it.find('.count').val();
            var catalog_id = it.find('.catalog_id').val();
            var size_id = it.find('.size_id').val();
            $.ajax({
                url: '/backend/ajax/orderPositionDelete',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    count: count,
                    catalog_id: catalog_id,
                    size_id: size_id,
                    id: $('#orderParameters').data('id')
                },
                success: function(data) {
                    if (data.success) {
                        it.remove();
                        setAmount();
                    }
                }
            });
        });
    }

    if($('#orderAddPosition').length) {
        var getItems = function() {
            $.ajax({
                url: '/backend/ajax/getItems',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    parent_id: $('select[name="parent_id"]').val()
                },
                success: function(data){
                    var html = '';
                    if (data.result.length) {
                        var obj;
                        for( var i = 0; i < data.result.length; i++ ) {
                            obj = data.result[i];
                            html += '<div class="item" data-id="'+obj.id+'">';
                            if ( obj.image ) {
                                html += '<img src="'+obj.image+'" />';
                            }
                            html += '<div class="itemName">'+obj.name+'</div>';
                            html += '<div class="itemCost">'+obj.cost+' грн</div>';
                            html += '</div>';
                        }
                    }
                    $('#setItemsHere').html(html);
                }
            });
        }
        getItems();
        $('select[name="parent_id"]').on('change', function(){
            getItems();
        });

        $('#setItemsHere').on('click', '.item', function(){
            if( $(this).hasClass('cur') ) {
                return false;
            }
            $('.item').removeClass('cur');
            $(this).addClass('cur');
            var catalog_id = $(this).data('id');
            $.ajax({
                url: '/backend/ajax/getItemSizes',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    catalog_id: catalog_id
                },
                success: function(data){
                    var html = '';
                    if (data.result.length) {
                        var obj;
                        for( var i = 0; i < data.result.length; i++ ) {
                            obj = data.result[i];
                            html += '<option value="'+obj.id+'">'+obj.name+'</option>';
                        }
                    } else {
                        html += '<option value="0">Нет</option>';
                    }
                    $('select[name="size_id"]').html(html);
                    $('input[name="catalog_id"]').val(catalog_id);
                }
            });
        });
    }

    if ($('#city').length) {
        $('#city').change(function(event) {
            var cityId = $(this).find('option:selected').val();
            $.ajax({
                url: '/backend/ajax/getPlaces',
                type: 'POST',
                dataType: 'json',
                data: {cityId: cityId},
                success: function(data){
                    $('.place').html(data.data);
                    $('.place').children('#place').trigger('change');
                }
            });
        });
    };

    $('.widgetContent').on('change', '#place', function(event) {
        var placeId = $(this).find('option:selected').val();
        $.ajax({
            url: '/backend/ajax/getSectors',
            type: 'POST',
            dataType: 'json',
            data: {placeId: placeId},
            success: function(data){
                $('.sectors').html(data.data);
            }
        });
    })/*.trigger('change')*/;
    
    $(window).load(function() {
        if ($('#place').length == 0) {
            $('#city').trigger('change');
        };
    });

    if ($('.printTable').length) {
        $('.printTable').click(function(e){
            e.preventDefault();

            $(this).closest('.eventWrapp').printElement();
        });
    }
});