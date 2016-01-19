<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    <?php echo $pageName; ?>
                    <span class="label label-primary"><?php echo $count; ?></span>
                </div>
                <div class="toolbar no-padding" id="ordersToolbar" data-uri="<?php echo Core\Arr::get($_SERVER, 'REQUEST_URI'); ?>">
                    <div class="btn-group">
                        <li class="btn btn-xs">
                            <a href="/backend/admins/index">
                                <i class="fa-refresh"></i>
                                <span class="hidden-xx">Сбросить</span>
                            </a>
                        </li>
                        <span class="btn btn-xs dropdownToggle dropdownSelect">
                             <i class="fa-filter"></i>
                             <span class="current-item"><?php echo isset($_GET['status']) ? ( Core\Arr::get( $_GET, 'status' ) ? 'Опубликованы' : 'Неопубликованы' ) : 'Все'; ?></span>
                             <span class="caret"></span>
                        </span>
                        <ul class="dropdownMenu pull-right">
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', NULL); ?>">
                                    <i class="fa-filter"></i>Все
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', 1); ?>">
                                    <i class="fa-filter"></i>Опубликованы
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', 0); ?>">
                                    <i class="fa-filter"></i>Неопубликованы
                                </a>
                            </li>
                        </ul>
<!--                        <li title="Выберите дату или период" class="range rangeOrderList btn btn-xs bs-tooltip">-->
<!--                            <a href="#">-->
<!--                                <i class="fa-calendar"></i>-->
<!--                                <span>--><?php //echo Core\Support::getWidgetDatesRange(); ?><!--</span>-->
<!--                                <i class="caret"></i>-->
<!--                            </a>-->
<!--                        </li>-->
                    </div>
                </div>
            </div>
            <div class="widget">
                <div class="widgetContent">
                    <table class="table table-striped table-hover checkbox-wrap ">
                        <thead>
                        <tr>
                            <td class="checkbox-column">
                                <label><input type="checkbox"></label>
                            </td>
                            <th>Имя</th>
                            <th>Логин</th>
                            <th>Дата регистрации</th>
                            <th>Статус</th>
                            <th class="nav-column textcenter">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $result as $obj ): ?>
                            <tr data-id="<?php echo $obj->id; ?>">
                                <td class="checkbox-column">
                                    <label><input type="checkbox"></label>
                                </td>
                                <td><a href="/backend/admins/edit/<?php echo $obj->id; ?>"><?php echo $obj->name; ?></a></td>
                                <td><?php echo $obj->login; ?></td>
                                <td><?php echo $obj->created_at ? date( 'd.m.Y', $obj->created_at ) : '----'; ?></td>
                                <td width="45" valign="top" class="icon-column status-column">
                                    <?php echo Core\View::widget(array( 'status' => $obj->status, 'id' => $obj->id ), 'StatusList'); ?>
                                </td>
                                <td class="nav-column">
                                    <ul class="table-controls">
                                        <li>
                                            <a class="bs-tooltip dropdownToggle" href="javascript:void(0);" title="Управление"><i class="fa-cog size14"></i></a>
                                            <ul class="dropdownMenu pull-right">
                                                <li>
                                                    <a title="Generate and send new log in information" href="/backend/<?php echo Core\Route::controller(); ?>/send/<?php echo $obj->id; ?>"><i class="fa-envelope"></i> Generate and send...</a>
                                                </li>
                                                <li>
                                                    <a href="/backend/admins/edit/<?php echo $obj->id; ?>" title="Редактировать"><i class="fa-pencil"></i> Редактировать</a>
                                                </li>
                                                <li class="divider"></li>
                                                <li>
                                                    <a title="Archive" onclick="return confirm('Move to archive?');" href="/backend/<?php echo Core\Route::controller(); ?>/archive/<?php echo $obj->id; ?>"><i class="fa-archive text-danger"></i> Archive</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>