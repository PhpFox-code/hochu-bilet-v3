<?php if ( isset($result[$cur]) AND count($result[$cur]) ): ?>
    <?php if ($cur > 0): ?>
        <ol>
    <?php endif ?>
    <?php foreach ($result[$cur] as $obj): ?>
        <li class="dd-item dd3-item" data-id="<?php echo $obj->id; ?>">
            <div title="Переместить строку" class="dd-handle dd3-handle">Drag</div>
            <div class="dd3-content" style="padding-left: 40px">
                <table>
                    <tr data-id="<?php echo $obj->id; ?>">
                        <td class="checkbox-column">
                            <label><input type="checkbox"></label>
                        </td>
                        <td>
                            <a href="/backend/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>"><?php echo $obj->name ?></a>
                        </td>
                        <td width="45" valign="top" class="icon-column status-column">
                            <?php echo Core\View::widget(array( 'status' => $obj->status, 'id' => $obj->id ), 'StatusList'); ?>
                        </td>
                        <td class="nav-column">
                            <ul class="table-controls">
                                <li>
                                    <a title="Управление" class="bs-tooltip dropdownToggle" href="javascript:void(0);"><i class="fa-cog size14"></i></a>
                                    <ul class="dropdownMenu pull-right">
                                        <li>
                                            <a title="Редактировать" href="/backend/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>"><i class="fa-pencil"></i> Редактировать</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Удалить" onclick="return confirm('Это действие необратимо. Продолжить?');" href="/backend/<?php echo Core\Route::controller(); ?>/delete/<?php echo $obj->id; ?>"><i class="fa-trash-o text-danger"></i> Удалить</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
            <?php echo Core\View::tpl(array('result' => $result, 'tpl_folder' => $tpl_folder, 'cur' => $obj->id), $tpl_folder.'/Menu'); ?>
        </li>
    <?php endforeach; ?>
    <?php if ($cur > 0): ?>
        </ol>
    <?php endif ?>
<?php endif ?>