<?php if ( isset($result[$cur]) AND count($result[$cur]) ): ?>
    <?php if ($cur > 0): ?>
        <ol>
    <?php endif ?>
    <?php foreach ($result[$cur] as $obj): ?>
        <li class="dd-item dd3-item" data-id="<?php echo $obj->id; ?>">
            <div title="Переместить строку" class="dd-handle dd3-handle">Drag</div>
            <div class="dd3-content" style="padding-left: 40px">
				<table class="checkbox-wrap ">
		            <tr data-id="<?php echo $obj->id; ?>">
		                <td class="checkbox-column">
		                    <label><input type="checkbox"></label>
		                </td>
		                <td width="40%"><a href="/backend/<?php echo \Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>"><?php echo $obj->name; ?></a></td>
		                <td width="10%"><?php echo ($obj->p_name or strlen($obj->place_name) != 0) ? \Modules\Afisha\Models\Afisha::getItemPlace($obj) : '----' ?></td>
		                <td width="10%"><?php echo \Modules\Afisha\Models\Afisha::getItemPrice($obj) ?> </td>
		                <td width="20%"><a href="/afisha/<?php echo $obj->alias; ?>" target="_blank"><?php echo $obj->alias; ?></a></td>
		                <td width="10%"><?php echo date('d.m.Y', $obj->event_date) ?></td>
		                <td><?php echo $obj->main_show ? '<span style="color: green;">Да</span>' : '<span style="color: red">Нет</span>' ?></td>
		                <td width="45" valign="top" class="icon-column status-column">
		                    <?php echo \Core\View::widget(array( 'status' => $obj->status, 'id' => $obj->id ), 'StatusList'); ?>
		                </td>
		                <td class="nav-column">
		                    <ul class="table-controls">
		                        <li>
		                            <a class="bs-tooltip dropdownToggle" href="javascript:void(0);" title="Управление"><i class="fa-cog size14"></i></a>
		                            <ul class="dropdownMenu pull-right">
										<?php if(Core\User::caccess() == 'edit' OR Core\User::caccess() == 'view'): ?>
											<li>
												<a href="/backend/<?php echo \Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>" title="Редактировать"><i class="fa-pencil"></i> Редактировать</a>
											</li>
										<?php endif; ?>
										<?php if(Core\User::caccess() == 'edit'): ?>
											<li class="divider"></li>
											<li>
												<a onclick="return confirm('Это действие необратимо. Продолжить?');" href="/backend/<?php echo \Core\Route::controller(); ?>/delete/<?php echo $obj->id; ?>" title="Удалить"><i class="fa-trash-o text-danger"></i> Удалить</a>
											</li>
										<?php endif; ?>
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