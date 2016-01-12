<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    <?php echo $pageName; ?>
                    <span class="label label-primary"><?php echo $count; ?></span>
                </div>
                <div class="toolbar no-padding" id="ordersToolbar" data-uri="<?php echo 'backend'.Core\Arr::get($_SERVER, 'REQUEST_URI'); ?>">
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
                                <th>Изображение</th>
                                <th>Название</th>
                                <!-- <th>Крупным</th> -->
                                <th>Ссылка</th>
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
                                    <td>
                                        <?php if (is_file(HOST.Core\HTML::media('images/banners/'.$obj->image))): ?>
                                            <a href="/backend/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>">
                                                <img src="<?php echo Core\HTML::media('images/banners/'.$obj->image); ?>" alt="<?php echo $obj->name; ?>" width="50">
                                            </a>
                                        <?php else: ?>
                                            ----
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php if ($obj->small): ?>
                                            <?php echo $obj->small; ?>
                                        <?php else: ?>
                                            ----
                                        <?php endif ?>
                                    </td>
                                    <!-- <td>
                                        <?php #if ($obj->big): ?>
                                            <?php #echo $obj->big; ?>
                                        <?php #else: ?>
                                            -
                                        <?php #endif ?>
                                    </td> -->
                                    <td>
                                        <?php if ($obj->url): ?>
                                            <a href="<?php echo $obj->url; ?>" target="_blank"><?php echo $obj->url; ?></a>
                                        <?php else: ?>
                                            ----
                                        <?php endif ?>
                                    </td>
                                    <td width="45" valign="top" class="icon-column status-column">
                                        <?php echo Core\View::widget(array( 'status' => $obj->status, 'id' => $obj->id ), 'StatusList'); ?>
                                    </td>
                                    <td class="nav-column">
                                        <ul class="table-controls">
                                            <li>
                                                <a title="Управление" class="bs-tooltip dropdownToggle" href="javascript:void(0);"><i class="fa-cog size14"></i></a>
                                                <ul class="dropdownMenu pull-right">
                                                    <?php if(Core\User::caccess() == 'edit' OR Core\User::caccess() == 'view'): ?>
                                                        <li>
                                                            <a title="Редактировать" href="/backend/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>"><i class="fa-pencil"></i> Редактировать</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if(Core\User::caccess() == 'edit'): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a title="Удалить" onclick="return confirm('Это действие необратимо. Продолжить?');" href="/backend/<?php echo Core\Route::controller(); ?>/delete/<?php echo $obj->id; ?>"><i class="fa-trash-o text-danger"></i> Удалить</a>
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