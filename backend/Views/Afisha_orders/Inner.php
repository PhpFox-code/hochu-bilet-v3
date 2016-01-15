<div class="rowSection clearFix row-bg">
	<div class="col-sm-6 col-md-4">
		<div class="statbox widget box box-shadow">
			<div class="widgetContent">
				<div class="visual green"><i class="fa-calendar"></i></div>
				<div class="title">Заказ создан</div>
				<div class="value"><?php echo date('d.m.Y H:i', $obj->created_at); ?></div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="statbox widget box box-shadow">
			<div class="widgetContent">
				<div class="visual cyan"><i class="fa-money"></i></div>
				<div class="title">Сумма заказа</div>
				<div class="value"><?php echo backend\Modules\Afisha\Models\Afisha::getTotalCost($obj); ?> грн</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="statbox widget box box-shadow">
			<div class="widgetContent">
				<div class="visual yellow"><i class="fa-dropbox"></i></div>
				<div class="title">Мест в заказе</div>
				<div class="value"><?php echo (int) count(array_filter(explode(',', $obj->seats_keys))); ?></div>
			</div>
		</div>
	</div>
</div>
<div class="rowSection column-2">
	<div class="col-md-7">
		<div class="widget">
			<div class="widgetHeader">
				<div class="widgetTitle"><i class="fa-credit-card"></i>Заказ <span class="label label-primary">№ <?php echo $obj->id; ?></span></div>
				<?php if (Core\User::caccess() == 'edit'): ?>
					<a onclick="return confirm('Это действие необратимо. Продолжить?');" href="/backend/orders/delete/<?php echo $obj->id; ?>" title="Удалить текущий заказ" >
						<i class="fa-trash-o"></i> Удалить текущий заказ</a>
				<?php endif; ?>
			</div>
			<div class="widgetContent">
				<div class="widget box">
					<div class="widgetHeader"><div class="widgetTitle"><i class="fa-clock-o"></i>Статус оплаты</div></div>
					<div class="widgetContent" style="padding-top: 0;">						
						<div class="rowSection">
							<div class="table-footer clearFix">
								<div class="col-md-12">
									<div class="input-group">
										<select class="form-control" name="order_status" id="order_status">
											<option value="" <?php echo is_null($obj->status) ? 'selected' : ''; ?>>Не оплачено</option>
											<option value="success" <?php echo $obj->status == 'success' ? 'selected' : ''; ?>>Оплачено</option>
										</select>
										<span class="input-group-btn">
											<div class="col-md-12">
											<button class="btn btn-primary" id="update_order_status" type="button" <?php echo (Core\User::caccess() != 'edit') ? 'disabled' : null ?>>Обновить</button>
											</div>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="widget box">
			<div class="widgetHeader">
				<div class="widgetTitle">
					Событие
				</div>
			</div>
			<div class="widgetContent">
				<div class="scrollSize">
					<table class="table table-hover tableFields">
						<thead>
							<tr>
								<th>Название</th>
								<th>
									Перейти на событие
								</th>
								<th>
									Место проведения
								</th>
								<th>
									Дата проведения
								</th>
								<th>
									Время проведения
								</th>
								<th>
									Описание
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<a href="/backend/afisha/edit/<?php echo $afisha->id ?>" title="<?php echo $afisha->name ?>"><?php echo $afisha->name ?></a>
								</td>
								<td>
									<div class="input-group input-width-small">
										<a href="/afisha/<?php echo $afisha->alias ?>">Просмтореть на сайте</a>
									</div>
								</td>
								<td>
									<span><?php echo $afisha->place ?></span>
								</td>
								<td>
									<div class="input-group input-width-small">
										<input type="text" class="form-control" value="<?php echo date('d.m.Y', $afisha->event_date) ?>">
									</div>
								</td>
								<td>
									<div class="input-group input-width-small">
										<input type="text" class="form-control" value="<?php echo $afisha->event_time ?>">
									</div>
								</td>
								<td>
									<div class="input-group input-width-small">
										<textarea name="descritpion"><?php echo strip_tags($afisha->text) ?></textarea>
									</div>
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="widget box">
			<div class="widgetHeader"><div class="widgetTitle"><i class="fa-user"></i>Заказчик</div></div>
			<?php if ($obj->is_admin): ?>
				<div class="widgetContent" id="form_admin_comment">
					<form class="form-vertical row-border">
						<div class="form-group">
							<label class="control-label">Комментарий</label>
							<div class="">
								<textarea class="form-control" rows="8" name="admin_comment"><?php echo $obj->admin_comment ?></textarea>
							</div>
						</div>
						<div class="form-actions textright">
							<button class="btn btn-primary" type="button" id="update_admin_comment" <?php echo (Core\User::caccess() != 'edit') ? 'disabled' : null ?>>Обновить</button>
						</div>
					</form>				
				</div>
			<?php else: ?>
				<div class="widgetContent" id="form_user_info">
					<form class="form-vertical row-border">
						<div class="form-group">
							<label class="control-label col-md-2">Имя</label>
							<div class="col-md-10">
								<input class="form-control" name="name" value="<?php echo $obj->name; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">Телефон</label>
							<div class="col-md-10">
								<input class="form-control" name="phone" value="<?php echo $obj->phone; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">E-mail</label>
							<div class="col-md-10">
								<input class="form-control" name="email" value="<?php echo $obj->email; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">Сообщение</label>
							<div class="col-md-10">
								<textarea class="form-control" name="message"><?php echo $obj->message ?></textarea>
							</div>
						</div>
						<div class="form-actions textright">
							<button class="btn btn-primary" type="button" id="update_user_info" <?php echo (Core\User::caccess() != 'edit') ? 'disabled' : null ?>>Обновить</button>
						</div>
					</form>				
				</div>
			<?php endif ?>
		</div>
	</div>
</div>
<div class="rowSection">
	<div class="col-md-8">
		<div class="widget box">
			<div class="widgetHeader">
				<div class="widgetTitle"><i class="fa-shopping-cart"></i>Места <span class="label label-primary" id="orderPositionsCount"><?php echo (int) count(array_filter(explode(',', $obj->seats_keys))) ?></span></div>
			</div>
			<div class="widgetContent">
				<div style="vertical-align: top">
					 <div style="display: inline-block; background: #01ff23; width: 20px; height: 20px;"></div> - Места текужего заказа
				</div>
				<div style="vertical-align: top">
					 <div style="display: inline-block; background: #E31E24; width: 20px; height: 20px;"></div> - Забронированные или олаченные
				</div>
				<div class="rowSection">
					<div class="col-md-9">
						<label for="tag2">Списко выбранных мест</label>
						<input type="text" class="form-control" id="tag2" value="<?php echo $obj->seats_keys ?>" />
					</div>
					<div class="col-md-3">
						<button class="btn btn-primary" style="margin-top: 30px;" id="update_seats" type="button"
							<?php echo (Core\User::caccess() != 'edit') ? 'disabled' : null ?>>Сохранить изменения</button>
					</div>
				</div>
				<br>
				<br>

				<?php echo $map ?>	
			
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="widget box">
			<div class="widgetHeader">
				<div class="widgetTitle"><i class="fa-print"></i>Печать билетов</div>
			</div>
			<div class="widgetContent">
				<?php echo (Core\User::info()->role_id != 2 && Core\User::access()['afisha_print_unlimit'] == 'edit')
					? '<i>Включено ограничение на печать билетов: 1 раз</i>'
					: null ?>
				<form action="<?php echo Core\HTML::link('backend/orders/print/'.$obj->id) ?>" method="post" autocomplete="off" target="_blank"
					  data-print-limit="<?php echo (Core\User::info()->role_id != 2 && Core\User::access()['afisha_print_unlimit'] == 'edit') ? 'true' : 'false' ?>">
					<div class="form-group">
						<?php $seats = array_filter(explode(',', $obj->seats_keys)); ?>
						<?php if (count($seats)): ?>
							<?php foreach ($seats as $seat): ?>
								<label class="checkerWrap ckbxWrap">
									<?php if (Core\User::info()->role_id != 2 && Core\User::access()['afisha_print_unlimit'] == 'edit'
										&& strpos($obj->printed_seats, $seat) !== false): ?>
										<input name="SEATS[]" value="<?php echo $seat ?>" type="checkbox" disabled="disabled">
									<?php else: ?>
										<input name="SEATS[]" value="<?php echo $seat ?>" type="checkbox" checked>
									<?php endif; ?>
									<span class=""><?php echo $seatsStr[$seat] ?></span>
								</label>
							<?php endforeach; ?>
						<?php endif ?>
					</div>
					<div class="form-actions">
						<label class="checkerWrap-inline">
							<input name="print-type" value="base" type="radio" checked>Обычная</label>
						<label class="checkerWrap-inline">
							<input name="print-type" value="termo" type="radio">Термопринтер</label>
						<input class="btn btn-primary print_order_tickets" type="submit" value="Печать"
							<?php echo (Core\User::caccess() == 'edit'
								OR (Core\User::access()['order_print'] == 'edit' && Core\User::info()->id == $obj->creator_id) ) ? null : 'disabled' ?> />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<span id="afishaOrderParameters" data-id="<?php echo $obj->id; ?>"></span>
<script>
	$(function(){
		$('.print_order_tickets').closest('form[data-print-limit="true"]').submit(function(e){
			setTimeout(function(){
				$('.print_order_tickets').closest('form').find('input[name^="SEATS"]:checked').each(function(){
					console.log($(this));
					$(this).prop('disabled', true).prop('checked', false).parent('label').removeClass('checked').addClass('disabled');
				});
			}, 100);
		});
	});
</script>