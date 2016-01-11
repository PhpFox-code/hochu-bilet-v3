$(document).ready(function() {

    // Start map

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // вывод ценовых зон в класс .map_price
    if($('#price').length){
        var priceObject = $('#price').data("price"), // получение цен в объект {zona: цена}
            zonaHtml;
        zonaHtml = "<p>Цены:</p>";
        for (var i = 0; i < priceObject.length; i++) {  // перебор и добавление всех ценовых зон
            var item = priceObject[i];
            zonaHtml += "<span style='color: " + item.color + "'>" + item.price + " грн</span>"
        };
        $('.map_price').html(zonaHtml); // вывод полученной строки
    };

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    var map = { // объект в котором хранятся ряды и в каждом ряду объекты мест {row:[{seat}, ...], ...}
        
        // добавление/удаление выбранных рядов/мест в объект map
        bookSeat: function(seatObject, thisSeat) { // добавление-удаление
            var elemIndex,
            rowNumber = seatObject.row,
            seatNumber = seatObject.seat;

            if (this[rowNumber]) { // проверяем наличие ряда
                
                $.map(this[rowNumber], function(obj, index) { // перебираем места в ряду
                    if(obj.seat === seatNumber) {
                        elemIndex = index; // индекс совпадения
                        return;
                    }
                });

                if (elemIndex || elemIndex === 0) { // проверка наличия места в ряду
                    this[rowNumber].splice(elemIndex, 1); // удаляем по индексу место в массиве ряда
                    this.seatsSorting(rowNumber);
                    var removedClass = thisSeat.getAttribute('class').replace(new RegExp('(\\s|^)' + 'curr_seat' + '(\\s|$)', 'g'), '$2'); // удаляем класс - выбрано 'curr_seat'
                    thisSeat.setAttribute('class', removedClass);
                } else {
                    this[rowNumber].push(seatObject); // добавляем место в массив ряда
                    this.seatsSorting(rowNumber);
                    thisSeat.setAttribute('class', thisSeat.getAttribute('class') + ' ' + 'curr_seat'); // добавляем класс - выбрано 'curr_seat'
                }
                
            } else {
              this[rowNumber] = []; // создаем массив ряда
              this[rowNumber].push(seatObject); // добавляем место в массив ряда
              thisSeat.setAttribute('class', thisSeat.getAttribute('class') + ' ' + 'curr_seat'); // добавляем класс - выбрано 'curr_seat'
            };

        },



        seatsSorting: function (row) { // сортировка 
            this[row].sort(function(a, b) {
                return parseFloat(a.seat) - parseFloat(b.seat);
            });
        },

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // вывод накликаных мест
        showSeats: function() { // отрисовка
            var i, row, idRow, plase, rowHTML = "", allRowsHTML = "", HTMLseatID = "", priceHtml = 0, $selectedRow = $(".selected_row");
            for (row in this) { // перебор всех рядов

                if (typeof this[row] !== "function") {
                    if (this[row].length) { // проверка пустой ряд или нет
                        idRow = '#row' + row;
                        plase = $(idRow).data("plase");
                        rowHTML = "<p>" + plase; // начало строки с номером ряда
                        for (i = 0; i < this[row].length; i++) {  // вывод всех мест в ряду
                            rowHTML += "<span style='background-color:" + this[row][i]['color'] + ";'>" + this[row][i]['seat'] + " </span>"; // добавление в строку каждого места с его номером и зоной
                            HTMLseatID += this[row][i]['seatID'] + ","; // добавление id каждого выбранного места
                            priceHtml += parseInt(this[row][i]['price']); // сложение цены за место
                        }
                        rowHTML += "<span data-row='" + row + "' class='del_row'>X</span></p>"; // конец строки, кнопка удалить ряд, записывается номер ряда
                        allRowsHTML += rowHTML; // сложение в одну строку
                    } else { // если ряд пустой - пропускаем из цикла отрисовки
                        delete this[row];
                    };
                }
            }
            $selectedRow.html(allRowsHTML); // вывод рядов и мест
            $(".selected_kol span").html($('.seat.curr_seat').length); // вывод общего количества Выбрано мест:
            $(".selected_sum span").html(priceHtml); // вывод суммы
            $(".seat_id").val(HTMLseatID); // вывод id 

            
            if ($('.seat.curr_seat').length === 0) { // Экранирование кнопки когда не выбрано ни одного места
                $('.empty_seat').css({"display":"block"});
            } else {
                $('.empty_seat').css({"display":"none"});
            };

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // удаление выделенных мест и ряда

            $(".del_row").on("click", function() { // удаление ряда при клике
                var i;
                var delRow = $(this).data("row"); // получение номера ряда для удаления
                idRow = '#row' + delRow; // id для поиска в нем выбранных мест
                var elements = $(idRow).find(".curr_seat"); // массив с элементами содержащими .curr_seat

                for (i = 0; i < elements.length; i++) { // перебор и удаление .curr_seat у элементов #row№
                    var removedClass = elements[i].getAttribute('class').replace(new RegExp('(\\s|^)' + 'curr_seat' + '(\\s|$)', 'g'), '$2');
                    elements[i].setAttribute('class', removedClass);
                }

                delete map[delRow]; // удаление ряда из объекта
                map.showSeats(); // вывод без удаленного ряда
            });
        },
    };
    
    $(".seat").on("click", function() { // клик на место - сбор и передача данных о месте
        var $this = $(this);
        if(!$this.closest("*[class*='busy']").attr("class") && !$this.closest("*[class*='reserved']").attr("class")) { // проверка забронировано место или нет 
            var seatObject = $this.data("init"); // создание объекта место из данных дата-атрибута
            seatObject.seatID = $this.attr('id'); // добавление id в объект место
            map.bookSeat(seatObject, this); // передается объект место и само место клика
            map.showSeats(); // на отрисовку
        }
    });

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // hover при на ведении на место и показ цены
        $('.seat').tipsy({ 
            gravity: 'sw', 
            html: true, 
            opacity: 1, 
            title: function() { 
                var seatObj = $(this).data('init');
                if (seatObj['price'] &&
                    $(this).attr('class').indexOf('busy') === -1) {

                    if ($(this).attr('class').indexOf('reserved') !== -1) {
                        return '<span class="hover_seat_price">Резерв</span>';
                    } else {
                        return '<span class="hover_seat_price">' + seatObj['price'] + ' грн</span>';
                    }

                } else {
                    return '<span class="hover_seat_price">' + 'Занято' + '</span>';
                };  
            }
        });
        
    // End map

    if ($('.mfi').length) {
        $('.mfi').each(function(i, el) {
            var mfi = $(el);
            if (typeof mfi.data('inited') === 'undefined') {
                var mfi_src = mfi.attr('href') || mfi.data('href'),
                    mfi_mainClass = mfi.data('class') || 'zoom-in',
                    mfi_type = mfi.data('type') || 'inline';
                mfi.magnificPopup({
                    items: {
                        src: mfi_src,
                        type: mfi_type
                    },
                    fixedContentPos: true,
                    fixedBgPos: true,
                    overflowY: 'auto',
                    closeBtnInside: true,
                    preloader: true,
                    midClick: true,
                    removalDelay: 50,
                    mainClass: mfi_mainClass
                });
                mfi.data('inited', true);
            }
        });
    }

    if ($('#select').length) {
        $('#select').select2({
            minimumResultsForSearch: -1
        });
    }

    $(window).load(function() {
        if ($('#carousel').length) {
            $('#carousel').carouFredSel({
                items: 1,
                direction: "left",
                prev: "#prev",
                next: "#next",
                scroll: {
                    items: 1,
                    easing: "swing",
                    duration: 1000,
                    pauseOnHover: true
                }
            });
        }
    });


    function seoSet() {
        $('#clonSeo').height($('#wSeo').outerHeight());
        $('#wSeo').css({
            top: $('#clonSeo').offset().top
        });
    }

    if ($('#wSeo').length) {
        var ifrm = $('<iframe name="seoIframe" scrolling="no" style="position: absolute; left: 0; top: 0; width: 100% ; height: 100% ; z - index: -1;"></iframe>');
        ifrm.prependTo($("#wSeo"));
        var seoTimer;
        seoIframe.onresize = function() {
            clearTimeout(seoTimer);
            seoTimer = setTimeout(function() {
                seoSet();
            }, 200);
        }
        seoSet();
    }

    if ($('.orderCall').length) {
        $('.orderCall').magnificPopup({
            type: 'inline',
            midClick: true,
            removalDelay: 300,
            fixedContentPos: true,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: true,
            preloader: true,
            mainClass: 'zoom-in'
        });
    }

    if ($('.tel').length) {
        $(".tel").inputmask("mask", {
            "mask": "(999) 999-99-99",
            oncomplete: function() {
                $(this).data('valid', true);
            },
            onincomplete: function() {
                $(this).data('valid', false);
            }
        });
    }

    $(window).load(function() {
        if ($("#wSeo").length) {
            seoSet();
        }
    });

});