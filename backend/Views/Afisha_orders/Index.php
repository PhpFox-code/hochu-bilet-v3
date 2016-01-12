<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <div class="filter">
                        <form action="" method="get" accept-charset="utf-8">
                            <div class="form-group" style="float: left; margin-right: 20px;">
                                <label class="form-label">Мероприятие</label>
                                <select name="event">
                                    <option value="0">Все</option>
                                    <?php foreach ($events as $key => $value): ?>
                                        <option value="<?php echo $value->id ?>" <?php echo $_GET['event'] == $value->id ? 'selected' : '' ?>><?php echo $value->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <input class="button btn-primary" style="float: left; margin-right: 20px;" type="submit" name="send" value="Поиск">
                            <a href="/backend/orders/index" style="float: left;">
                                <i class="fa-refresh"></i>
                                <span class="">Сбросить</span>
                            </a>
                            <div class="clear"></div>
                        </form>
                    </div>
                    <i class="fa-reorder"></i>
                    <?php echo $pageName; ?>
                    <span class="label label-primary"><?php echo $count; ?></span>
                </div>
                <div class="toolbar no-padding" id="ordersToolbar" data-uri="<?php echo Core\Arr::get($_SERVER, 'REQUEST_URI'); ?>">
                    <div class="btn-group">
                        <li class="btn btn-xs">
                            <a href="/backend/orders/index">
                                <i class="fa-refresh"></i>
                                <span class="hidden-xx">Сбросить</span>
                            </a>
                        </li>
                        <span class="btn btn-xs dropdownToggle dropdownSelect">
                             <i class="fa-filter"></i>
                             <span class="current-item"><?php echo isset($_GET['status']) ? $pay_statuses[ Core\Arr::get($_GET, 'status', 0) ] : 'Все'; ?></span>
                             <span class="caret"></span>
                        </span>
                        <ul class="dropdownMenu pull-right">
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', NULL); ?>">
                                    <i class="fa-filter"></i>Все
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', 'null'); ?>">
                                    <i class="fa-filter"></i>Не проведен
                                </a>
                            </li>
                            <?php foreach ( $pay_statuses as $id => $name ): ?>
                                <li>
                                    <a href="<?php echo Core\Support::generateLink('status', $id); ?>">
                                        <i class="fa-filter"></i><?php echo $name; ?>
                                    </a>
                                </li>    
                            <?php endforeach ?>
                        </ul>

                        <li title="Выберите дату или период" class="range rangeOrderList btn btn-xs bs-tooltip">
                            <a href="#">
                                <i class="fa-calendar"></i>
                                <span><?php echo Core\Support::getWidgetDatesRange(); ?></span>
                                <i class="caret"></i>
                            </a>
                        </li>

                    </div>
                </div>
            </div>

            <div class="widgetContent">
                <div class="checkbox-wrap">
                    <table class="table table-striped table-bordered orderList" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="hidden-ss">Номер заказа</th>
                                <th>Имя</th>
                                <th>Телефон</th>
                                <th>E-mail</th>
                                <th>Коммент</th>
                                <!-- <th>IP</th> -->
                                <th>Количество мест</th>
                                <th>Сумма заказа</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th></th>
                            </tr>
                        </thead>
                     
                        <tbody>
                            <?php foreach ( $result as $obj ): ?>
                                <tr data-id="<?php echo $obj->id; ?>">
                                    <td class="hidden-ss"><a href="/backend/orders/edit/<?php echo $obj->id; ?>"><?php echo $obj->id; ?></a></td>
                                    <td><?php echo $obj->is_admin ? '<b>Админ</b>' : $obj->name; ?></td>
                                    <td><a href="tel:<?php echo $obj->phone; ?>"><?php echo $obj->phone; ?></a></td>
                                    <td><a href="mailto:<?php echo $obj->email; ?>"><?php echo $obj->email; ?></a></td>
                                    <td><?php echo $obj->admin_comment ?></td>
                                    <!-- <td><?php #echo $obj->ip ? $obj->ip : '<span style="font-style: italic; color: #ccc;">( Администратор )</span>'; ?></td> -->
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
                                    <td>
                                        <ul class="table-controls">
                                            <li>
                                                <a class="bs-tooltip dropdownToggle" href="javascript:void(0);" title="Управление"><i class="fa-cog size14"></i></a>
                                                <ul class="dropdownMenu pull-right">
                                                    <?php if(Core\User::caccess() == 'edit' OR Core\User::caccess() == 'view'): ?>
                                                        <li>
                                                            <a href="/backend/orders/edit/<?php echo $obj->id; ?>" title="Редактировать"><i class="fa-pencil"></i> Редактировать</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if(Core\User::caccess() == 'edit'): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a onclick="return confirm('Это действие необратимо. Продолжить?');" href="/backend/orders/delete/<?php echo $obj->id; ?>" title="Удалить"><i class="fa-trash-o text-danger"></i> Удалить</a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </li>
                                        </ul>
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