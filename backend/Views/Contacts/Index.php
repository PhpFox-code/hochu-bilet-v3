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
                             <span class="current-item"><?php echo isset($_GET['status']) ? ( Core\Arr::get( $_GET, 'status' ) ? 'Прочитанные' : 'Непрочитанные' ) : 'Все'; ?></span>
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
                                    <i class="fa-filter"></i>Прочитанные
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Core\Support::generateLink('status', 0); ?>">
                                    <i class="fa-filter"></i>Непрочитанные
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
                                <th>Имя</th>
                                <th>E-Mail</th>
                                <th>Сообщение</th>
                                <th>IP</th>
                                <th>Дата</th>
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
                                    <td><a href="/backend/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>"><?php echo $obj->name; ?></a></td>
                                    <td><a href="mailto:<?php echo $obj->email; ?>"><?php echo $obj->email; ?></a></td>
                                    <td><?php echo Core\Text::limit_words( strip_tags($obj->text), 15 ); ?></td>
                                    <td><?php echo $obj->ip; ?></td>
                                    <td><?php echo $obj->created_at ? date( 'd.m.Y', $obj->created_at ) : '----'; ?></td>
                                    <td width="45" valign="top" class="icon-column status-column">
                                        <?php if( \Core\User::caccess() != 'edit' ): ?>
                                            <?php if ($obj->status == 1): ?>
                                                <i class="fa-check green"></i>
                                            <?php else: ?>
                                                <i class="fa-dot-circle-o red"></i>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if( \Core\User::caccess() == 'edit' ): ?>
                                            <a
                                                data-pub="<b>Отметить как непрочитанное</b><br>Прочитано"
                                                data-unpub="<b>Отметить как прочитано</b><br>Не прочитано"
                                                title="<?php echo $obj->status == 1 ? '<b>Отметить как непрочитанное</b><br>Прочитано' : '<b>Отметить как прочитано</b><br>Не прочитано'; ?>"
                                                data-status="<?php echo $obj->status; ?>"
                                                data-id="<?php echo $obj->id; ?>"
                                                href="#"
                                                class="setStatus bs-tooltip btn btn-xs <?php echo $obj->status == 1 ? 'btn-success' : 'btn-danger' ?>"
                                            >
                                                <?php if ($obj->status == 1): ?>
                                                    <i class="fa-check"></i>
                                                <?php else: ?>
                                                    <i class="fa-dot-circle-o"></i>
                                                <?php endif; ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="nav-column">
                                        <ul class="table-controls">
                                            <li>
                                                <a class="bs-tooltip dropdownToggle" href="javascript:void(0);" title="Управление"><i class="fa-cog size14"></i></a>
                                                <ul class="dropdownMenu pull-right">
                                                    <?php if(Core\User::caccess() == 'view' OR Core\User::caccess() == 'edit'): ?>
                                                        <li>
                                                            <a href="/backend/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>" title="Редактировать"><i class="fa-pencil"></i> Редактировать</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if(Core\User::caccess() == 'edit'): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a onclick="return confirm('Это действие необратимо. Продолжить?');" href="/backend/<?php echo Core\Route::controller(); ?>/delete/<?php echo $obj->id; ?>" title="Удалить"><i class="fa-trash-o text-danger"></i> Удалить</a>
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
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>