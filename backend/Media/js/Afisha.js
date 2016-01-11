jQuery(document).ready(function($) {
    // load prices list
    $(function(){
        // get prices from server
        var id_afisha = $('input[name="id"]').val();
        $.ajax({
            url: '/backend/ajax/getPrices',
            type: 'POST',
            dataType: 'json',
            data: {
                id_afisha: id_afisha
            },
        })
        .always(function(data) {
            if (data.success) {
                for (var i = 0; i < data.result.length; i++) {
                    var oneRes = data.result[i];

                    var oneEl = $('#price_tpl > div').clone();
                    $(oneEl).find('input[name="PLACES[cost][]"]').val(oneRes.price);
                    $(oneEl).find('input[name="PLACES[color][]"]').val(oneRes.color);
                    $(oneEl).find('input[name="PLACES[place][]"]').val(oneRes.place_list);
                    $('.prices-list').append(oneEl);
                    //start colorpicker
                    $('.prices-list .selColor').each(function(){
                    	var it = $(this);
                        $(this).minicolors({
                            control: $(this).attr('data-control') || 'hue',
                            defaultValue: $(this).attr('data-defaultValue') || '',
                            inline: $(this).attr('data-inline') === 'true',
                            letterCase: $(this).attr('data-letterCase') || 'lowercase',
                            opacity: $(this).attr('data-opacity'),
                            position: $(this).attr('data-position') || 'bottom left',
                            change: function(hex, opacity) {
                                if( !hex ) return;
                                if( opacity ) hex += ', ' + opacity;
                                try {
			                    	// Change all colors in map
			                		var list_places = $(it).closest('.one-price').find('.place_list').val();
			                		// parse json
			                		var nList = JSON.parse(list_places);
			                		list_places = Object.keys(nList);
			                		if(list_places.length) {
			                			for (var i = 0; i < list_places.length; i++) {
			            					$('g#' + list_places[i]).children('path').css('fill', hex);
			            				};
			                		}
                                } catch(e) {}
                            },
                            theme: 'bootstrap'
                        });
                    });
                };
                $('#map_place').html(data.map);
            }
            else {
                console.log(data);
            }
        });
    });

    // Adding price row and init color
    $('#addPrice').click(function(event) {
        event.preventDefault();
        var oneEl = $('#price_tpl > div').clone();
        $(oneEl).find('.selColor').val('#888888');
        $('.prices-list').append(oneEl);
        //start colorpicker
        $('.prices-list .selColor').each(function(){
        	var it = $(this);
            $(this).minicolors({
                control: $(this).attr('data-control') || 'hue',
                defaultValue: $(this).attr('data-defaultValue') || '',
                inline: $(this).attr('data-inline') === 'true',
                letterCase: $(this).attr('data-letterCase') || 'lowercase',
                opacity: $(this).attr('data-opacity'),
                position: $(this).attr('data-position') || 'bottom left',
                change: function(hex, opacity) {
                    if( !hex ) return;
                    if( opacity ) hex += ', ' + opacity;
                    try {
                    	// Change all colors in map
                		var list_places = $(it).closest('.one-price').find('.place_list').val();
                		// parse json 
                		var nList = JSON.parse(list_places);
                		list_places = Object.keys(nList);
                		if(list_places.length) {
                			for (var i = 0; i < list_places.length; i++) {
            					$('g#' + list_places[i]).children('path').css('fill', hex);
            				};
                		}
                    } catch(e) {}
                },
                theme: 'bootstrap'
            });
        });
    });

    // Load map
    $('#place_selector').change(function(event) {
        // If another place
        if ($(this).val() == 'another') {
            $('.prices-list').html('');
            $('#map_place').html($('#custom_place_fields').html());
        }
        // Get map
        else {
            var id_place = $(this).val();
            var id_afisha = $('input[name="id"]').val();
            $('.place_list').val('');
            $.ajax({
                url: '/backend/ajax/getMap',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_place: id_place,
                    id_afisha: id_afisha
                },
            })
            .always(function(data) {
                if (data.success) {
                    $('#map_place').html(data.map);
                    initMap();
                }
                else {
                    $('#map_place').html(data.error);
                    console.log(data);
                }
            });        
        }
    }).trigger('change');


    // Event click button select places
    $(document).on('click', 'button.select_place_btn', function(event) {
		event.preventDefault();

		// Editable
		$('.one-price').removeClass('editable');
    	
    	// Clear all another buttons
    	$('button.select_place_btn').not($(this))
    		.html('Выбрать места')
    		.removeClass('btn-danger')
    		.addClass('btn-info');


    	if ($(this).hasClass('btn-danger')) {

    		$(this).addClass('btn-info').removeClass('btn-danger');
    		$(this).html('Выбрать места');
    	}
    	else {
			$(this).closest('.one-price').addClass('editable');
    		$(this).addClass('btn-danger').removeClass('btn-info');
    		$(this).html('Сохранить');
    	}
    });

    // Click on map
    $(document).on('click', 'path, text', function(event) {
        // Now work 
        // Get parent element with seat class
        if ($(this).parent().attr('class') !== undefined 
        	&& $(this).parent().attr('class').indexOf('seat') !== -1) 
        {
        	var $parent = $(this).parent();
        } else if ($(this).parent().parent().attr('class') !== undefined 
        	&& $(this).parent().parent().attr('class').indexOf('seat') !== -1) 
        {
        	var $parent = $(this).parent().parent();
        } else if ($(this).parent().parent().parent().attr('class') !== undefined 
        	&& $(this).parent().parent().parent().attr('class').indexOf('seat') !== -1) 
        {
        	var $parent = $(this).parent().parent().parent();
        } else {
        	console.warn('Can`t find parent element wiht class "seat"');
        	return false;
        }

        var parentClass = $parent.attr('class');
        if (parentClass.indexOf('reserved') !== -1 || parentClass.indexOf('payment') !== -1) {
            return false;
        };

        var dataInit = $parent.attr('data-init');
        dataInit = JSON.parse(dataInit);

    	var id = $parent.attr('id');

        if ($('.editable').length == 0 && $('.clicked').length == 0 && $('.order-clicked').length == 0) {
            return false;
        } 
        else if ($('.clicked').length) {
            if ( ! ("price" in dataInit)) {
                return false;
            };
            if ($('.selected-seats').val().indexOf(id) === -1) {
                $('.selected-seats').addTag(id);
            };
            return false;
        }
        else if ($('.order-clicked').length) {
            if ( ! ("price" in dataInit)) {
                return false;
            };            
            if ($('.selected-order-seats').val().indexOf(id) === -1) {
                $('.selected-order-seats').addTag(id);
                // Add fill color
                $parent.find('path').css('fill', '#000');
                $parent.find('text').css('fill', '#fff');
            } else {
                $('.selected-order-seats').removeTag(id);
                // Remove fill color
                $parent.find('path').css('fill', dataInit.color);
                $parent.find('text').attr('style', '');
            }
            return false;
        }


    	var color = $('.editable').find('.selColor').val();
    	var oldVal = $('.editable').find('.place_list').val();

    	if (oldVal == '' 
    		|| oldVal.length == 0
    		|| oldVal == '[]') {
    		var objOldVal = {};
    	}
    	else {
    		var objOldVal = JSON.parse(oldVal);

    	}
    	
    	// If exist element - remove it object
    	if (id in objOldVal) {
		    delete objOldVal[id];
            $('g#' + id).find('path').attr('fill', '');
		    $('g#' + id).find('path').css('fill', '');
		}
    	else {
    		objOldVal[id] = {
				"view_key"    : id,
				"status"      : 1,
				"reserved_at" : null
    		};
    	}
    	var keys = Object.keys(objOldVal);

    	for (var i = 0; i < keys.length; i++) {
    		
            $('g#' + keys[i]).find('path').attr('fill', color);
    		$('g#' + keys[i]).find('path').css('fill', color);
    	};
    		
    	$('.editable').find('.place_list').val(JSON.stringify(objOldVal));
    });

    // Init jquery zoom plugin for map
    function initMap() {
        // jQuery UI масштабирование, перетаскивание
        if($("#slider_zoom").length) {
            $("#slider_zoom").slider({
                orientation: "horizontal",
                value:100,
                min: 50,
                max: 600,
                slide: function( event, ui ) {
                    var svgWidth = ui.value + "%";
                    var margin = ui.value / 2;
                    $(".map_svg").css({"width": svgWidth, "height": svgWidth, "margin-left": 50 - margin + "%", "margin-top": 50 - margin + "%" });
                    $(".ui-slider-handle span").html(svgWidth);
                }
            });

            $(".reset_zoom").on("click", function() {
                $(".map_svg").css({"width": "100%", "height": "100%", "position": "relative", "left": "0", "top": "0", "margin-left": "0", "margin-top": "0" });
                $(".ui-slider-handle").css("left", "9.09090909090909%" );
                $(".ui-slider-handle span").html("100%");
            });
        };

        if($(".map_svg").length) {
            $( ".map_svg" ).draggable({
                cursor: "move"
            });
        };
    }

    if ($('.map_svg').length) {
        initMap();
    };

    ///////////
    // Print //
    ///////////
    function printRedirect(key) {
        if (!key) {
            alert('Не выбрно место для печати');
            return;
        };
        $('#printPlace').removeClass('clicked');
        var afishaId = $('input[name="id"]').val(),
            type = $('input[name="print-type"]:checked').val();
        window.open('/backend/afisha/'+afishaId+'/printTicket/'+key+'/'+type, '_blank');
    }
    $('#printPlace').click(function(event) {
        event.preventDefault();
        if($(this).hasClass('clicked')) {
            // Print
            $(this).switchClass('btn-danger', 'btn-primary')
                .html('Распечатать билет');

            printRedirect($('.selected-seats').val());
        } else {
            $(this).switchClass('btn-primary', 'btn-danger')
                .html('Печать');
        }
        $(this).toggleClass('clicked');
    });


    //////////////////
    // Create order //
    //////////////////
    function orderRedirect(key) {
        if (!key) {
            alert('Не выбрно место для создания заказа');
            return;
        };
        $('#orderPlace').removeClass('clicked');
        var afishaId = $('input[name="id"]').val();
        window.location.href = '/backend/afisha/'+afishaId+'/createOrder/'+key;
    }
    $('#orderPlace').click(function(event) {
        event.preventDefault();
        if($(this).hasClass('order-clicked')) {
            // Print
            $(this).switchClass('btn-danger', 'btn-primary')
                .html('Забронировать');

            orderRedirect($('.selected-order-seats').val());
        } else {
            $(this).switchClass('btn-primary', 'btn-danger')
                .html('Создать бронь');
        }
        $(this).toggleClass('order-clicked');
    });

    // --------------------------------------------------------------------------
    //                            Order work
    // --------------------------------------------------------------------------

    if ($('#afishaOrderParameters').length) {
        $('g.seat').click(function(event) {
            /**
            * Element was selected
            */
            if ($(this).attr('class').indexOf('payment') !== -1) {
                // Remove class payment
                var newClass = $(this).attr('class').replace('payment', '');
                $(this).attr('class', newClass);
                // Remove view_key (ID) from list
                $('#tag2').removeTag($(this).attr('id'));
            }
            /**
            * Element didn't selected
            */
            else if(
                $(this).attr('class').indexOf('grey') === -1 &&
                $(this).attr('class').indexOf('reserved') === -1 ) {
                // Add class
                var newClass = $(this).attr('class') + ' payment';
                $(this).attr('class', newClass);

                // Add view_key (ID) list
                $('#tag2').addTag($(this).attr('id'));
            }
        });


        // Save changes list view_key
        $('#update_seats').click(function(event) {
            event.preventDefault();

            var list = $('#tag2').val();
            var afisha_id = $('#afishaOrderParameters').data('id');

            if (list.length == 0) {
                alert('Необходимо выбрать хоть одно место');
                return;
            };

            $.ajax({
                url: '/backend/ajax/updateSeats',
                type: 'POST',
                dataType: 'json',
                data: {
                    list      : list,
                    afisha_id : afisha_id
                },
            })
            .always(function(data) {
                if (data.success === true) {
                    generate(data.message, 'success');
                    $('#update_order_status').trigger('click');
                    if (data.reload == true) {
                        setTimeout(function(){
                            location.reload();
                        }, 3000);
                    };
                }
                else {
                    generate(data.message, 'danger');
                }
            });
        });



        // Update order status
        $('#update_order_status').click(function(event) {
            event.preventDefault();

            var status = $('#order_status').val();
            var afisha_id = $('#afishaOrderParameters').data('id');

            $.ajax({
                url: '/backend/ajax/updateOrderStatus',
                type: 'POST',
                dataType: 'json',
                data: {
                    status: status,
                    afisha_id: afisha_id
                },
            })
            .always(function(data) {
                if (data.success === true) {
                    generate(data.message, 'success');
                    if (data.reload == true) {
                        setTimeout(function(){
                            location.reload();
                        }, 3000);
                    };
                }
                else {
                    generate(data.message, 'danger');
                }
            });
        });

        // Update user info
        $('#update_user_info').click(function(event) {
            event.preventDefault();

            var afisha_id = $('#afishaOrderParameters').data('id');

            var name = $('#form_user_info').find('input[name="name"]').val();
            var phone = $('#form_user_info').find('input[name="phone"]').val();
            var email = $('#form_user_info').find('input[name="email"]').val();
            var message = $('#form_user_info').find('textarea[name="message"]').val();

            $.ajax({
                url: '/backend/ajax/updateUserInfo',
                type: 'POST',
                dataType: 'json',
                data: {
                    afisha_id: afisha_id,
                    name : name,
                    phone : phone,
                    email : email,
                    message : message
                },
            })
            .always(function(data) {
                if (data.success === true) {
                    generate(data.message, 'success');
                    if (data.reload == true) {
                        setTimeout(function(){
                            location.reload();
                        }, 3000);
                    };
                }
                else {
                    generate(data.message, 'danger');
                }
            });
        });

        // Update admin comment
        $('#update_admin_comment').click(function(event) {
            event.preventDefault();

            var afisha_id = $('#afishaOrderParameters').data('id');

            var admin_comment = $('#form_admin_comment').find('textarea[name="admin_comment"]').val();

            $.ajax({
                url: '/backend/ajax/updateAdminComment',
                type: 'POST',
                dataType: 'json',
                data: {
                    afisha_id: afisha_id,
                    admin_comment : admin_comment
                },
            })
            .always(function(data) {
                if (data.success === true) {
                    generate(data.message, 'success');
                    if (data.reload == true) {
                        setTimeout(function(){
                            location.reload();
                        }, 3000);
                    };
                }
                else {
                    generate(data.message, 'danger');
                }
            });
        });
    };
});