var typeNotyMessage;
var generate = function( message, typeNotyMessage ){
    if( !typeNotyMessage ) {
        typeNotyMessage = 'alert';
    }
    noty({
        layout: 'bottomRight',
        text: message,
        timeout: 2500,
        type: typeNotyMessage
    });
}
jQuery(document).ready(function($) {
    $('.wSubmit').on('click', function(event) {
        var button = $(this);
        var form = button.closest('.wForm');
        form.validate({
            errorElement: "label",
            errorClass: "error",
            rules: {
                enter_email: {email: true},
                forget_email: {email: true},
                enter_pass: {minlength: 4},
                reg_pass: {minlength: 4},
                phone_num: {phoneUA: true},
                password: "required",
                confirm: {
                  equalTo: "#password"
                },
            },
            showErrors: function(errorMap, errorList) {
                if (errorList.length) {
                    var s = errorList.shift();
                    var n = [];
                    n.push(s);
                    this.errorList = n;
                }
                this.defaultShowErrors();
            },
            invalidHandler: function(form, validator) {
                $(validator.errorList[0].element).trigger('focus');
            }
        });
        form.valid();
        if (form.valid()) {
            form.addClass('success');
            if( form.data('ajax') ) {
                var data = form.find('input,textarea,select').serializeArray();
                data.push({ name: button.attr('name'), value: button.val() });
                if ($('.selected').length) {
                    var orderHtml = $('.selected').first().clone();
                    $(orderHtml).find('.del_row').remove();
                    $(orderHtml).find('.selected_row span').attr('style', '');
                    data.push({ name: 'order', value: $(orderHtml).html() });
                };
                $.ajax({
                    url: '/form/' + form.data('ajax'),
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        data: data
                    },
                    success: function(data){
                        if( data.success ) {
                            if (!data.noclear) {
                                form.find('input').each(function(){
                                    if( $(this).attr('type') != 'hidden' && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'submit' ) {
                                        $(this).val('');
                                    }
                                });
                                form.find('textarea').val('');
                            }
                            if ( data.response ) {
                                generate(data.response, 'success');
                            }
                        } else {
                            if ( data.response ) {
                                generate(data.response, 'warning');
                            }
                        }

                        if( data.redirect ) {
                            if (data.response) {
                                setTimeout(function(){
                                    window.location.href = data.redirect;
                                }, 3000);
                            }
                            else {
                                window.location.href = data.redirect;
                            }
                        }

                        if (data.reload === true) {
                            if (data.response) {
                                setTimeout(function(){
                                    location.reload();
                                }, 3000);
                            }
                            else {
                                location.reload();
                            }
                        };
                    }
                });
                return false;
            }
        } else {
            form.removeClass('success');
            return false;
        }
    });

    // change city
    if ($('.selCity').length) {
        $('.selCity').change(function(event) {
            var data = {
                0 : {
                    'name' : 'idCity',
                    'value': $(this).find('option:selected').val()
                }
            } 
            $.ajax({
                url: '/form/selCity',
                type: 'POST',
                dataType: 'json',
                data: {
                    data : data 
                },
                success: function(data) {
                    if (data.success) {
                        window.location.reload();
                    };
                }
            });
        });
    };

    if ($('#moreAffishe').length) {
        $('#moreAffishe').click(function(event) {
            event.preventDefault();
            var it = $(this);
            var page = $(it).attr('data-page');

            $.ajax({
                url: '/ajax/moreAffishe',
                type: 'POST',
                dataType: 'json',
                data: {page: page},
                success: function(data) {
                    if (data.success) {
                        
                        var view = '';
                        
                        for (var i = 0; i < data.result.length; i++) {
                            view += '<div class="post">';
                            view += '   <div class="postW view">';
                            if (data.result[i].image) {
                                view += '<img src="Media/images/afisha/medium/'+data.result[i].image+'" alt="'+data.result[i].name+'">';
                            }
                            else {
                                view += '<img src="Media/pic/no-image.jpg" alt="'+data.result[i].name+'">';
                            }
                            view += '<a href="/afisha/'+data.result[i].alias+'">';
                            view += '<div class="mask">';
                            view += '   <ul>';
                            view += '       <li>'+data.result[i].name+'</li>';
                            if (data.result[i].p_name) {
                                view += '   <li>'+data.result[i].p_name+'</li>';
                            }
                            else {
                                view += '   <li>Не указано</li>';
                            }
                            view += '      <li>'+data.result[i].event_date+'</li>';
                            view += '   </ul>';
                            view += '</div>';
                            view += '   <a href="/afisha/buy/'+data.result[i].alias+'" class="buyButton">купить билет</a>';
                            view += '</div>';
                            view += '   <div class="substrate">';
                            view += '       <span class="price">'+data.result[i].cost+'<span class="uah">грн</span></span>';
                            view += '   </div>';
                            view += '</div>';
                        };
                        $('.wrapPost').append(view);
                        $(it).attr('data-page', 1 + 1 * page );
                        if (data.showBut === false) {
                            $(it).parent('.afficheButStill').next('.clear').remove();
                            $(it).parent('.afficheButStill').remove();
                        };
                    }
                    else {
                        if ( data.response ) {
                            generate(data.response, 'warning');
                        }
                    }
                }
            });
        });
    };

    // Click for open order form 
    $('.buyButton2').click(function(event) {
        $('#afisha_id').val($(this).data('id'));
    });
});