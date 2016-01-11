<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style type="text/css">
        a,abbr,acronym,address,applet,article,aside,audio,b,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,dfn,div,dl,dt,em,embed,
        fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,output,
        p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{margin:0;padding:0;border:0;font:inherit;font-size:100%;vertical-align:baseline}
        embed,img,object{max-width:100%}
        img {height: auto;}
        table{width:100%;border-collapse:collapse;border-spacing:0}
        td,td img{vertical-align:top}
        ::-moz-selection{text-shadow:none;color:#fff;background:#2597ff}
        ::-ms-selection{text-shadow:none;color:#fff;background:#2597ff}
        [hidden]{display:none}
        html, body {height: 100%;margin: 0;padding: 0;}
        html {display: block;font-family: sans-serif;font-size: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}
        body {
            color: #000;
            background: #fff;
            position: relative;
            min-width: 980px;
            font: 14px Arial,Helvetica,sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        @media print {
            * {
                text-shadow: none !important;
                color: #000 !important;
                background: transparent !important;
                box-shadow: none !important;
            }
            a, a:visited {text-decoration: underline;}
            a[href]:after {content: " (" attr(href) ") ";}
            abbr[title]:after {content: " (" attr(title) ") ";}
            a[href^="javascript:"]:after, a[href^="#"]:after {content: "";}
            pre, blockquote {border: 1px solid #999; page-break-inside: avoid;}
            thead {display: table-header-group;}
            tr, img {page-break-inside: avoid;}
            img {max-width: 100% !important;}
            @page {
                margin: 0.5cm;
            }
            p, h2, h3 {orphans: 3; widows: 3;}
            h2, h3 {page-break-after: avoid;}
        }


        .ticket_wrapper {
            height: 730px;
            margin-bottom: 300px;
            position: relative;
        }

        .ticket_wrapper .ticket {
            color: #000000;
            width: 742px;
            height: 341px;
            border: 1px solid #fff;
            position: absolute;
            top: 200px;
            left: -200px;
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg) ;
            -ms-transform: rotate(90deg);
            transform: rotate(90deg);
        }


        .ticket_wrapper .ticket > div {
            box-sizing: border-box;
        }

        .ticket_wrapper .left {
            float: left;
            width: 116px;
            height: 341px;
            border-right: 1px solid #fff;
            position: relative;
            padding: 5px 5px 5px 20px;
        }

        .ticket_wrapper .right {
            float: right;
            width: 125px;
            height: 341px;
            border-left: 1px solid #fff;
            position: relative;
            padding: 8px;
            padding: 5px 20px 5px 5px;
        }

        .ticket_wrapper .center {
            width: 501px;
            float: left;
            height: 341px;
            padding: 0 20px;
        }

        .ticket_wrapper .center .top {
            padding: 15px 0 0;
            margin: 0 0 40px;
        }

        .ticket_wrapper .center .top .title {
            text-align: center;
            color: #000;
            font: 16px/20px Arial, Helvetica, sans-serif;
        }

        .ticket_wrapper .center .top .title span {
            display: block;
            font-size: 13px;
        }
        .ticket_wrapper .center .middle {
            margin: 0 0 35px;
        }

        .wName_events {
            color: #000;
            text-align: center;
            margin: 0 0 20px;
            height: 91px;
        }

        .wName_events .name_events {
            font: bold 22px/23px Arial, Helvetica, sans-serif;
            max-height: 48px;
            overflow: hidden;
            margin: 0 0 5px;
        }

        .wName_events .sub_name_events {
            font: 14px/20px Arial, Helvetica, sans-serif;
            max-height: 38px;
            overflow: hidden;
        }

        .wInfo {
            width: 75%;
            margin: 0 auto;
        }

        .wInfo table tr td {
            width: 1%;
            padding: 2px 0;
            font: 14px Arial, Helvetica, sans-serif;
        }

        .wInfo table tr td span {
            font-weight: bold;
        }

        .wBarcode {
            text-align: right;
        }

        .wBarcode .barcode {
            display: inline-block;
        }

        .mini_info {
            position: absolute;
            top: 125px;
            left: -103px;
            font-size: 11px;
            width: 329px;
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg) ;
            -ms-transform: rotate(-90deg);
            transform: rotate(-90deg);
        }

        .wMiniName_events {
            margin: 0 0 5px;
        }
        .mini_info .name_events,
        .mini_info .sub_name_events {
            max-height: 26px;
            padding: 0px 47px 0px 0px;
            overflow: hidden;
        }

        .mini_info table {
            width: 260px;
            margin: 0 0 5px;
        }

        .mini_info .wBarcode {
            text-align: right;
            padding: 1px 10px 0 0;
        }
    </style>
</head>
<body onload="window.print()">

<?php if (count($tickets)): ?>
    <?php foreach ($tickets as $ticket): ?>
        <div class="ticket_wrapper">
            <div class="ticket">
                <div class="left">
                    <div class="mini_info">
                        <div class="wMiniName_events">
                            <div class="name_events"><?php echo $ticket->event_name ?></div>
                        </div>
                        <table>
                            <tr>
                                <td>Дата: <span><?php echo $ticket->event_just_date ?></span></td>
<!--                                <td>Ложа партер (правая)</td>-->
                                <td><?php echo $ticket->place_block ?></td>
                            </tr>
                            <tr>
                                <td>Начало: <spa><?php echo $ticket->event_time ?></span></td>
                                <td>Ряд: <span><?php echo $ticket->place_row ?></span></td>
                            </tr>
                            <tr>
                                <td>Цена: <span><?php echo $ticket->price ?> грн</span></td>
                                <td>Место: <span><?php echo $ticket->place_seat ?></span></td>
                            </tr>
                        </table>
                        <div class="wBarcode">
                            <div class="barcode">
                                <img src="http://hochu-bilet.com/Plugins/PhpBarcodeMaster/barcode.php?text=<?php echo $ticket->barcode ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="center">
                    <div class="top">
                        <div class="title">
                            <?php echo $ticket->event_place ?> <span><?php echo $ticket->event_address; ?> Тел: <?php echo $ticket->phone; ?></span>
                        </div>
                    </div>

                    <div class="middle">
                        <div class="wName_events">
                            <?php if (isset($ticket->print_name) && mb_strlen($ticket->print_name, 'UTF-8') > 0): ?>
                                <div class="name_events"><?php echo $ticket->print_name ?></div>
                                <div class="sub_name_events"><?php echo $ticket->print_name_small ?></div>
                            <?php else: ?>
                                <div class="name_events"><?php echo $ticket->event_name ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="wInfo">
                            <table>
                                <tr>
                                    <td>Дата: <span><?php echo $ticket->event_just_date ?></span></td>
                                    <td>Ложа партер (правая)</td>
                                </tr>
                                <tr>
                                    <td>Начало: <span><?php echo $ticket->event_time ?></span></td>
                                    <td>Ряд: <span><?php echo $ticket->place_row ?></span></td>
                                </tr>
                                <tr>
                                    <td>Цена: <span><?php echo $ticket->price ?> грн</span></td>
                                    <td>Место: <span><?php echo $ticket->place_seat ?></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="bottom">
                        <div class="wBarcode">
                            <div class="barcode">
                                <img src="http://hochu-bilet.com/Plugins/PhpBarcodeMaster/barcode.php?text=<?php echo $ticket->barcode ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right">
                    <div class="mini_info">
                        <div class="wMiniName_events">
                            <div class="name_events"><?php echo $ticket->event_name ?></div>
                        </div>
                        <table>
                            <tr>
                                <td>Дата: <span><?php echo $ticket->event_just_date ?></span></td>
                                <td><?php echo $ticket->place_block; ?></td>
                            </tr>
                            <tr>
                                <td>Начало: <span><?php echo $ticket->event_time ?></span></td>
                                <td>Ряд: <span><?php echo $ticket->place_row ?></span></td>
                            </tr>
                            <tr>
                                <td>Цена: <span><?php echo $ticket->price ?> грн</span></td>
                                <td>Место: <span><?php echo $ticket->place_seat ?></span></td>
                            </tr>
                        </table>
                        <div class="wBarcode">
                            <div class="barcode">
                                <img src="http://hochu-bilet.com/Plugins/PhpBarcodeMaster/barcode.php?text=<?php echo $ticket->barcode ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>

</body>
</html>
