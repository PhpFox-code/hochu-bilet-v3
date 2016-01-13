<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">

                <?php echo Core\View::tpl(array('creators' => array(), 'events' => $events), 'Cassier/Filter'); ?>

                <div class="checkbox-wrap">
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
                            <?php foreach ( $result as $el ): ?>
                                <?php $obj = $el['order']; ?>
                                <?php $afisha = $el['afisha']; ?>
                                <tr data-id="<?php echo $obj->id; ?>">
                                    <td class="hidden-ss">
                                        <a href="/backend/orders/edit/<?php echo $obj->id; ?>" target="_blank"><?php echo $obj->id; ?></a>
                                    </td>
                                    <td><a href="/backend/afisha/edit/<?php echo $afisha->id; ?>"><?php echo $afisha->name; ?></a></td>
                                    <td><?php echo $obj->admin_comment ?></td>
                                    <td><?php echo count(array_filter(explode(',', $obj->seats_keys))); ?></td>
                                    <td class="sum-column"><?php echo backend\Modules\Afisha\Models\Afisha::getTotalCost($obj); ?> грн</td>
                                    <td><?php echo date( 'd.m.Y H:i', $obj->created_at ); ?></td>
                                    <td>
                                        <?php if( $obj->status == 'failture' ): ?>
                                            <?php $class = 'danger'; ?>
                                        <?php elseif( $obj->status == 'wait_secure' OR $obj->status == 'wait_accept' ): ?>
                                            <?php $class = 'info'; ?>
                                        <?php elseif( $obj->status == 'success' ): ?>
                                            <?php $class = 'success'; ?>
                                        <?php elseif( is_null($obj->status) OR $obj->status == ''): ?>
                                            <?php $class = 'danger'; ?>
                                        <?php else: ?>
                                            <?php $class = 'default'; ?>
                                        <?php endif; ?>
                                        <span title="<?php echo !is_null($obj->status) ? $pay_statuses[$obj->status] : 'Не оплачено'; ?>" class="label label-<?php echo $class; ?> orderLabelStatus bs-tooltip">
                                            <span class="hidden-ss"><?php echo (!is_null($obj->status) AND $obj->status != '') ? $pay_statuses[$obj->status] : 'Не оплачено'; ?></span>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php echo $pager; ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.orderLabelStatus').on('click', function(e){
            var it = $(this);
            var id = it.closest('tr').attr('data-id');
            var status = it.attr('data-status');
            var sess = it.attr('data-session');
            $.ajax({
                url: '/backend/ajax/changeStatusOrder.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    sess: sess,
                    status: status
                },
                success: function(data) {
                    if( data.success ) {
                        it.attr( 'data-status', data.st.id );
                        it.attr( 'title', data.st.name );
                        var cl = it.attr( 'data-class' );
                        it.attr( 'data-class', data.st.class );
                        it.removeClass( cl );
                        it.addClass( data.st.class );
                        it.find('span').html( data.st.name );
                    }
                }
            });
        });

        $('.ranges > ul > li.active').removeClass('active');
    });
</script>