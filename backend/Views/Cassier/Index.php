<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <?php echo Core\View::tpl(array('creators' => $creators, 'events' => $events), 'Cassier/Filter'); ?>

                <div class="rowSection">
                    <!-- Total orders count-->
                    <div class="col-md-6">
                        <div class="widget box">
                            <div class="widgetHeader">
                                <div class="widgetTitle">
                                    <i class="fa-reorder"></i>
                                    Заказы всех менеджеров
                                </div>
                            </div>
                            <div class="widgetContent">
                                <div id="totalOrders" data-json='<?php echo $jsonOrders; ?>'></div>
                            </div>
                        </div>
                    </div>
                    <!-- Total prices -->
                    <div class="col-md-6">
                        <div class="widget box">
                            <div class="widgetHeader">
                                <div class="widgetTitle">
                                    <i class="fa-reorder"></i>
                                    Заработки менеджеров
                                </div>
                            </div>
                            <div class="widgetContent">
                                <div id="totalPrices" data-json='<?php echo $jsonPrices; ?>'></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkbox-wrap">
                    <table class="table table-striped table-bordered orderList" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="hidden-ss">ID</th>
                                <th>Имя</th>
                                <th>E-mail</th>
                                <th>Телефон</th>
                                <th>Общее кол-во заказов</th>
                                <th>Кол-во выполненных заказов</th>
                                <th>Кол-во мест</th>
                                <th>Общая стоимость</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $result as $el ): ?>
                                <?php $obj = $el['user']; ?>
                                <tr data-id="<?php echo $obj->id; ?>">
                                    <td class="hidden-ss">
                                        <a href="/backend/cassier/inner/<?php echo $obj->id; ?>"><?php echo $obj->id; ?></a>
                                    </td>
                                    <td>
                                        <a href="/backend/cassier/inner/<?php echo $obj->id; ?>"><?php echo $obj->name; ?></a>
                                    </td>
                                    <td>
                                        <?php if ($obj->email): ?>
                                            <a href="mailto:<?php echo $obj->email; ?>"><?php echo $obj->email; ?></a>
                                        <?php else: ?>
                                            ----
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($obj->phone): ?>
                                            <a href="tel:<?php echo $obj->phone; ?>"><?php echo $obj->phone; ?></a>
                                        <?php else: ?>
                                            ----
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $el['totalOrders']; ?></td>
                                    <td><?php echo $el['totalSuccessOrders']; ?></td>
                                    <td><?php echo $el['countSeats']; ?></td>
                                    <td class="sum-column"><?php echo $el['totalPrice'] ?> грн</td>
                                    <td>
                                        <a href="/backend/cassier/inner/<?php echo $obj->id; ?>" title="Подробно">Подробно</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        var tOrders = $('#totalOrders').data('json');
        $('#totalOrders').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Распределение заказов по менеджерам'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.v:f} шт.</b>',
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: "Заказы",
                colorByPoint: true,
                data: tOrders
            }]
        });

        var tPrices = $('#totalPrices').data('json');
        $('#totalPrices').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Распределение заработков по менеджерам'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.price} грн.</b>',
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: "Заработки",
                colorByPoint: true,
                data: tPrices
            }]
        });
    });
</script>