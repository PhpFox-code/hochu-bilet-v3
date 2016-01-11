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
                        <a href="/backend/<?php echo Core\Route::controller(); ?>/index">
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


        <div class="widget">
            <div class="widgetContent">
                <table class="table table-striped table-hover checkbox-wrap ">
                    <thead>
                        <tr>
                            <th class="checkbox-head">
                                <label><input type="checkbox"></label>
                            </th>
                            <th>IP</th>
                            <th>Товар</th>
                            <th>Имя</th>
                            <th>Город</th>
                            <th>Сообщение</th>
                            <th>Дата</th>
                            <th>Опубликовано?</th>
                            <th class="nav-column textcenter">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $result as $obj ): ?>
                            <tr data-id="<?php echo $obj->id; ?>">
                                <td class="checkbox-column">
                                    <label><input type="checkbox"></label>
                                </td>
                                <td><?php echo $obj->ip ? $obj->ip : '<i class="color: #ccc;">( Администратор )</i>'; ?></td>
                                <td>
                                    <?php if ( $obj->item_name ): ?>
                                        <a href="/backend/items/edit/<?php echo $obj->catalog_id ?>" target="_blank"><?php echo $obj->item_name; ?></a>
                                    <?php else: ?>
                                        <i style="color: #aaa;">( Удален )</i>
                                    <?php endif ?>
                                </td>
                                <td><a href="/backend/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id ?>"><?php echo $obj->name; ?></a></td>
                                <td><?php echo $obj->city; ?></td>
                                <td><?php echo $obj->text; ?></td>
                                <td><?php echo $obj->created_at ? date('d.m.Y', $obj->created_at) : '---'; ?></td>
                                <td width="45" valign="top" class="icon-column status-column">
                                    <?php echo Core\View::widget(array( 'status' => $obj->status, 'id' => $obj->id ), 'StatusList'); ?>
                                </td>
                                <td class="nav-column">
                                    <ul class="table-controls">
                                        <li>
                                            <a class="bs-tooltip dropdownToggle" href="javascript:void(0);" title="Управление"><i class="fa-cog size14"></i></a>
                                            <ul class="dropdownMenu pull-right">
                                                <li>
                                                    <a href="/backend/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>" title="Редактировать"><i class="fa-pencil"></i> Редактировать</a>
                                                </li>
                                                <li>
                                                    <a href="/backend/items/edit/<?php echo $obj->catalog_id; ?>" title="Перейти к товару"><i class="fa-inbox"></i> Перейти к товару</a>
                                                </li>
                                                <li class="divider"></li>
                                                <li>
                                                    <a onclick="return confirm('Это действие необратимо. Продолжить?');" href="/backend/<?php echo Core\Route::controller(); ?>/delete/<?php echo $obj->id; ?>" title="Удалить"><i class="fa-trash-o text-danger"></i> Удалить</a>
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
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>