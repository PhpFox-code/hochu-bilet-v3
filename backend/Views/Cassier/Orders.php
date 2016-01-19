<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th class="hidden-ss">Номер заказа</th>
        <th>Название события</th>
        <th>Примечание админа</th>
        <th>Количество мест</th>
        <th>Сумма заказа</th>
        <th>Дата</th>
        <th>Статус</th>
    </tr>
    </thead>

    <tbody>
    <?php
        $countOrders = 0;
        $countPlaces = 0;
        $totalCost = 0;
        $countBrone = 0;
        $countExpired = 0;
        $countPayed = 0;
    ?>
    <?php foreach ( $result as $obj ): ?>
        <?php $countOrders++; ?>
        <?php $places = count(array_filter(explode(',', $obj->seats_keys))); ?>
        <?php $cost = backend\Modules\Afisha\Models\Afisha::getTotalCost($obj); ?>
        <?php $countPlaces += $places; ?>
        <?php $totalCost += $cost; ?>

        <?php
            $status = null;
            if ($obj->status == 'success')
                $status = 'success';
            else if ($obj->created_at > time() - Core\Config::get('reserved_days') * 24 * 60 * 60
                AND $obj->status != 'success')
                $status = 'brone';
            else if ($obj->created_at < time() - Core\Config::get('reserved_days') * 24 * 60 * 60
                AND $obj->status != 'success')
                $status = 'expired';
        ?>
        <?php if( $status == 'brone' ): ?>
            <?php $class = 'info';
            $countBrone++; ?>
        <?php elseif( $status == 'expired' ): ?>
            <?php $class = 'warning';
            $countExpired++; ?>
        <?php elseif( $status == 'success' ): ?>
            <?php $class = 'success';
            $countPayed++; ?>
        <?php elseif( is_null($status) OR $status == ''): ?>
            <?php $class = 'danger'; ?>
        <?php else: ?>
            <?php $class = 'default'; ?>
        <?php endif; ?>

        <tr data-id="<?php echo $obj->id; ?>">
            <td class="hidden-ss">
                <a href="/backend/orders/edit/<?php echo $obj->id; ?>" target="_blank"><?php echo $obj->id; ?></a>
            </td>
            <td><a href="/backend/afisha/edit/<?php echo $afisha->id; ?>"><?php echo $afisha->name; ?></a></td>
            <td><?php echo $obj->admin_comment ?></td>
            <td><?php echo $places; ?></td>
            <td class="sum-column"><?php echo $cost; ?> грн</td>
            <td><?php echo date( 'd.m.Y H:i', $obj->created_at ); ?></td>
            <td>
                <span class="label label-<?php echo $class; ?> orderLabelStatus">
                    <span class="hidden-ss">
                        <?php echo (!is_null($status) AND $status != '') ? $pay_statuses[$status] : 'Не оплачено'; ?>
                    </span>
                </span>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Кол-во заказов: <strong><?php echo $countOrders; ?></strong></td>
            <td></td>
            <td></td>
            <td>Кол-во мест: <strong><?php echo $countPlaces; ?></strong></td>
            <td>Сумма: <strong><?php echo number_format($totalCost, 0, '.', '\''); ?>грн</strong></td>
            <td colspan="2">Кол-во<br>
                Забронированных: <strong><?php echo $countBrone;?></strong><br>
                Просроченных: <strong><?php echo $countExpired;?></strong><br>
                Оплаченных: <strong><?php echo $countPayed;?></strong>
            </td>
        </tr>
    </tfoot>
</table>