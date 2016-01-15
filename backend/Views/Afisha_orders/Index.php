<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <div class="filter">
                        <form action="" method="get" accept-charset="utf-8">
                            <div style="display: inline-block;">
                                <label class="control-label" style="display: block;">Мероприятие</label>
                                <select name="event">
                                    <option value="0">Все</option>
                                    <?php foreach ($events as $key => $value): ?>
                                        <option value="<?php echo $value->id ?>" <?php echo $_GET['event'] == $value->id ? 'selected' : '' ?>><?php echo $value->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <?php if (count($creators)): ?>
                                <div style="display: inline-block;">
                                    <label class="control-label" style="display: block;">Менеджер</label>
                                    <select name="creator_id">
                                        <option value="0">Все</option>
                                        <?php foreach ($creators as $key => $value): ?>
                                            <option value="<?php echo $value->id ?>" <?php echo $_GET['creator_id'] == $value->id ? 'selected' : '' ?>><?php echo $value->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div style="display: inline-block;vertical-align: top;">
                                <label class="control-label" style="display: block;">Дата от</label>
                                <input name="date_s" class="form-control fPicker" value="<?php echo Core\Arr::get($_GET, 'date_s', NULL); ?>">
                            </div>
                            <div style="display: inline-block;vertical-align: top;">
                                <label class="control-label" style="display: block;">Дата до</label>
                                <input name="date_po" class="form-control fPicker" value="<?php echo Core\Arr::get($_GET, 'date_po', NULL); ?>">
                            </div>
                            <div style="display: inline-block;">
                                <label class="control-label" style="display: block;">Статус</label>
                                <select name="status">
                                    <option value="">Все</option>
                                    <?php foreach ($pay_statuses as $k => $status): ?>
                                        <option value="<?php echo $k; ?>" <?php echo $_GET['status'] == $k ? 'selected' : '' ?>><?php echo $status; ?></option>
                                    <?php endforeach; ?>n
                                </select>
                            </div>
                            <input class="button btn-primary" style="display: inline-block;" type="submit" name="send" value="Поиск">
                            <a href="/backend/orders/index" style="display: inline-block;">
                                <i class="fa-refresh"></i>
                                <span class="">Сбросить</span>
                            </a>
                        </form>
                    </div>
                    <i class="fa-reorder"></i>
                    <?php echo $pageName; ?>
                    <span class="label label-primary"><?php echo $count; ?></span>
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
                                    <td>
                                        <?php if($obj->is_admin): ?>
                                            <b>Админ</b>
                                        <?php elseif ($obj->creator_id): ?>
                                            <b><?php echo $obj->creator_name; ?></b>
                                        <?php else: ?>
                                            <?php echo $obj->name; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><a href="tel:<?php echo $obj->phone; ?>"><?php echo $obj->phone; ?></a></td>
                                    <td><a href="mailto:<?php echo $obj->email; ?>"><?php echo $obj->email; ?></a></td>
                                    <td><?php echo $obj->admin_comment ?></td>
                                    <!-- <td><?php #echo $obj->ip ? $obj->ip : '<span style="font-style: italic; color: #ccc;">( Администратор )</span>'; ?></td> -->
                                    <td><?php echo count(array_filter(explode(',', $obj->seats_keys))); ?></td>
                                    <td class="sum-column"><?php echo backend\Modules\Afisha\Models\Afisha::getTotalCost($obj); ?> грн</td>
                                    <td><?php echo date( 'd.m.Y H:i', $obj->created_at ); ?></td>
                                    <td>
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
                                            <?php $class = 'info'; ?>
                                        <?php elseif( $status == 'expired' ): ?>
                                            <?php $class = 'warning'; ?>
                                        <?php elseif( $status == 'success' ): ?>
                                            <?php $class = 'success'; ?>
                                        <?php elseif( is_null($status) OR $status == ''): ?>
                                            <?php $class = 'danger'; ?>
                                        <?php else: ?>
                                            <?php $class = 'default'; ?>
                                        <?php endif; ?>

                                        <span class="label label-<?php echo $class; ?> orderLabelStatus">
                                            <span class="hidden-ss">
                                                <?php echo (!is_null($status) AND $status != '') ? $pay_statuses[$status] : 'Не оплачено'; ?>
                                            </span>
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