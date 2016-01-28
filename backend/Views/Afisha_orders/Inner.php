<div class="rowSection clearFix row-bg">
	<div class="col-sm-6 col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widgetContent">
				<div class="visual green"><i class="fa-calendar"></i></div>
				<div class="title">Заказ создан</div>
				<div class="value"><?php echo date('d.m.Y H:i', $obj->first_created_at); ?></div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widgetContent">
				<div class="visual cyan"><i class="fa-money"></i></div>
				<div class="title">Сумма заказа</div>
				<div class="value"><?php echo backend\Modules\Afisha\Models\Afisha::getTotalCost($obj); ?> грн</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widgetContent">
				<div class="visual yellow"><i class="fa-dropbox"></i></div>
				<div class="title">Мест в заказе</div>
				<div class="value"><?php echo (int) count(array_filter(explode(',', $obj->seats_keys))); ?></div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-3">
		<div class="statbox widget box box-shadow">
			<div class="widgetContent">
				<div class="visual red"><i class="fa-clock-o"></i></div>
				<div class="title">Бронь закончится</div>
				<?php if($obj->status == 'success'): ?>
					<div class="value">Заказ выкуплен</div>
				<?php elseif ($obj->status != 'success' AND $obj->created_at < time() - Core\Config::get('reserved_days') * 24 * 60 * 60): ?>
					<div class="value">Бронь просрочена</div>
				<?php else: ?>
					<div class="value"><?php echo date('d.m.Y H:i', ($obj->created_at + Core\Config::get('reserved_days') * 24 * 60 * 60)); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<div class="rowSection column-2">
	<div class="col-md-7">
		<div class="widget">
			<div class="widgetHeader">
				<div class="widgetTitle"><i class="fa-credit-card"></i>Заказ <span class="label label-primary">№ <?php echo $obj->id; ?></span></div>
				<?php if (Core\User::info()->role_id == 2): ?>
					<a onclick="return confirm('Это действие необратимо. Продолжить?');" href="/backend/orders/delete/<?php echo $obj->id; ?>" title="Удалить текущий заказ" >
						<i class="fa-trash-o"></i> Удалить текущий заказ</a>
				<?php endif; ?>
			</div>
			<div class="widgetContent">
				<div class="widget box">
					<div class="widgetHeader"><div class="widgetTitle"><i class="fa-clock-o"></i>Статус оплаты</div></div>
					<div class="widgetContent" style="padding-top: 0;">	
						<?php if($obj->payer_id != 0 && Core\User::info()->role_id == 2): ?>
							<div class="">
								<span>Принял оплату:</span>
								<a href="/backend/users/edit/<?php echo $payer->id; ?>"><?php echo $payer->name; ?></a>
							</div>
						<?php endif; ?>
						<?php if($obj->status != 'success' AND $obj->created_at < time() - Core\Config::get('reserved_days') * 24 * 60 * 60): ?>
							<p class="info-block">Бронь просрочена</p>
						<?php endif; ?>
						<div class="rowSection">
							<div class="table-footer clearFix">
								<div class="col-md-12">
									<div class="input-group">
										<select class="form-control" name="order_status" id="order_status"
											<?php echo (($obj->status == 'success' AND Core\User::info()->role_id != 2)
												OR ($obj->status != 'success' AND $obj->created_at < time() - Core\Config::get('reserved_days') * 24 * 60 * 60)) ? 'disabled' : null ?>>
											<option value="" <?php echo is_null($obj->status) ? 'selected' : ''; ?>>Не оплачено</option>
											<option value="success" <?php echo $obj->status == 'success' ? 'selected' : ''; ?>>Оплачено</option>
										</select>
										<span class="input-group-btn">
											<div class="col-md-12">
											<button class="btn btn-primary" id="update_order_status" type="button"
												<?php echo (($obj->status == 'success' AND Core\User::info()->role_id != 2)
													OR ($obj->status != 'success' AND $obj->created_at < time() - Core\Config::get('reserved_days') * 24 * 60 * 60)) ? 'disabled' : null ?>>Обновить</button>
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
								<th>Перейти на событие</th>
								<th>Место</th>
								<th>Дата</th>
								<th>Время</th>
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
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<?php if($obj->status != 'success' AND $obj->created_at > time() - Core\Config::get('reserved_days') * 24 * 60 * 60): ?>
			<div class="widget box">
				<div class="widgetHeader">
					<div class="widgetTitle"><i class="fa-clock-o"></i>Продление брони</div>
				</div>
				<div class="widgetContent">
					<label class="control-label">Дата время</label>
					<div class="rowSection">
						<div class="col-md-5">
							<input type="text" name="date-brone" class="myPicker form-control"
								   value="<?php echo date('d.m.Y', ($obj->created_at + Core\Config::get('reserved_days') * 24 * 60 * 60)) ?>"/>
						</div>
						<div class="col-md-3">
							<input type="text" name="time-brone" class="form-control"
								   value="<?php echo date('H:i', ($obj->created_at + Core\Config::get('reserved_days') * 24 * 60 * 60)) ?>" />
						</div>
						<div class="col-md-4">
							<button class="btn btn-danger extend-brone">Продлить</button>
						</div>
					</div>

				</div>
			</div>
		<?php endif; ?>
		<div class="widget box">
			<?php if ($obj->is_admin && Core\User::info()->role_id == 2): ?>
				<div class="widgetHeader"><div class="widgetTitle"><i class="fa-user"></i>Примечание</div></div>
				<div class="widgetContent" id="form_admin_comment">
					<form class="form-vertical row-border">
						<div class="form-group">
							<label class="control-label">Комментарий</label>
							<div class="">
								<textarea class="form-control" rows="8" name="admin_comment"><?php echo $obj->admin_comment ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="checkerWrap-inline">
								<input name="admin-brone" id="admin_brone" value="1" type="checkbox" <?php echo $obj->admin_brone == 1 ? 'checked' : null; ?>>Бронь админа</label>
						</div>
						<div class="form-actions textright">
							<button class="btn btn-primary" type="button" id="update_admin_comment" <?php echo (Core\User::caccess() != 'edit') ? 'disabled' : null ?>>Обновить</button>
						</div>
					</form>
				</div>
			<?php else: ?>
				<div class="widgetHeader"><div class="widgetTitle"><i class="fa-user"></i>Заказчик</div></div>
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
							<button class="btn btn-primary" type="button" id="update_user_info" <?php echo (Core\User::caccess() != 'edit'
								OR ($obj->status == 'success' && Core\User::info()->role_id != 2)) ? 'disabled' : null ?>>Обновить</button>
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
						<input type="text" class="form-control" id="tag2" value="<?php echo $obj->seats_keys ?>"
							<?php echo (Core\User::caccess() != 'edit' OR ($obj->status == 'success' && Core\User::info()->role_id != 2)) ? 'disabled' : null ?> />
					</div>
					<div class="col-md-3">
						<button class="btn btn-primary" style="margin-top: 30px;" id="update_seats" type="button"
							<?php echo (($obj->status == 'success' && Core\User::info()->role_id != 2)
								OR ($obj->created_at < time() - Core\Config::get('reserved_days') * 24 * 60 * 60
									AND $obj->status != 'success' )) ? 'disabled' : null ?>>Сохранить изменения</button>
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
				<?php echo (Core\User::info()->role_id != 2 && Core\User::get_access_for_controller('afisha_print_unlimit') == 'edit')
					? '<i>Включено ограничение на печать билетов: 1 раз</i>'
					: null ?>
				<form action="<?php echo Core\HTML::link('backend/orders/print/'.$obj->id) ?>" method="post" autocomplete="off" target="_blank"
					  data-print-limit="<?php echo (Core\User::info()->role_id != 2 && Core\User::get_access_for_controller('afisha_print_unlimit') == 'edit') ? 'true' : 'false' ?>">

					<div class="form-group">
						<?php $seats = array_filter(explode(',', $obj->seats_keys)); ?>
						<?php if (count($seats)): ?>
							<?php foreach ($seats as $seat): ?>
								<label class="checkerWrap ckbxWrap">
									<?php if (Core\User::info()->role_id != 2 && Core\User::get_access_for_controller('afisha_print_unlimit') == 'edit'
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
						<?php if($obj->status != 'success' AND $obj->created_at < time() - Core\Config::get('reserved_days') * 24 * 60 * 60): ?>
							<p class="info-block">Бронь просрочена</p>
						<?php endif; ?>
						<?php if($obj->status != 'success' AND $obj->created_at > time() - Core\Config::get('reserved_days') * 24 * 60 * 60): ?>
							<p class="info-block">Заказ не оплачен</p>
						<?php endif; ?>
						<input class="btn btn-primary print_order_tickets" type="submit" value="Печать"
							<?php echo (Core\User::info()->role_id == 2
								OR ($obj->created_at > time() - Core\Config::get('reserved_days') * 24 * 60 * 60 AND $obj->status == 'success' )) ? null : 'disabled' ?>
						/>
					</div>
				</form>
			</div>
		</div>

		<?php if(Core\User::info()->role_id == 2): ?>
			<div class="widget box">
				<div class="widgetHeader">
					<div class="widgetTitle"><i class="fa-eraser"></i>Сбросить историю печати</div>
				</div>
				<div class="widgetContent">
					<p class="info-block">Используйте если кассиру нужно перепечатать билеты.
						<br>Для сброса истории печати билетов - отметьте необходимые билеты и нажмите Сбросить</p>
					<form action="" autocomplete="off" id="reset_seats">
						<div class="form-group">
							<?php $seats = array_filter(explode(',', $obj->seats_keys)); ?>
							<?php if (count($seats)): ?>
								<?php foreach ($seats as $seat): ?>
									<label class="checkerWrap ckbxWrap">
										<?php if (strpos($obj->printed_seats, $seat) !== false): ?>
											<input name="RESET_SEATS[]" value="<?php echo $seat ?>" type="checkbox" checked>
										<?php else: ?>
											<input name="RESET_SEATS[]" value="<?php echo $seat ?>" type="checkbox" disabled="disabled">
										<?php endif; ?>
										<span class=""><?php echo $seatsStr[$seat] ?></span>
									</label>
								<?php endforeach; ?>
							<?php endif ?>
						</div>

						<div class="form-actions">
							<input class="btn btn-warning reset_order_seats" type="submit" value="Сбросить"/>
						</div>
					</form>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<span id="afishaOrderParameters" data-id="<?php echo $obj->id; ?>"></span>
<script>
	$(function(){
		// init
		var pickerInit = function( selector ) {
			var date = $(selector).val();
			$(selector).datepicker({
				showOtherMonths: true,
				selectOtherMonths: false
			});
			$(selector).datepicker('option', $.datepicker.regional['ru']);
			var dateFormat = $(selector).datepicker( "option", "dateFormat" );
			$(selector).datepicker( "option", "dateFormat", 'dd.mm.yy' );
			$(selector).val(date);
		}
		pickerInit('.myPicker');

		$('.print_order_tickets').closest('form[data-print-limit="true"]').submit(function(e){
			setTimeout(function(){
				$('.print_order_tickets').closest('form').find('input[name^="SEATS"]:checked').each(function(){
					$(this).prop('disabled', true).prop('checked', false).parent('label').removeClass('checked').addClass('disabled');
				});
//				Disable change status button
				if ($('input[name^="SEATS"]:not(:disabled)').length == 0) {
					$('#update_order_status').prop('disabled', true);
					$('#order_status option[value="success"]').attr('selected', 'selected');
					$('#order_status').prop('disabled', true);
				}
			}, 100);
		});

//		Reset for admin
		if($('#reset_seats').length) {
			$('#reset_seats').submit(function(e){
				e.preventDefault();

				var seats = $('input[name="RESET_SEATS[]"]:checked').map(function() {
					return this.value;
				}).get();

				if (seats.length == 0) {
					generate('Не выбраны места для сброса', 'warning');
					return false;
				}

				if (!confirm('Вы действительно хотите сбросить историю печати? Это позволит кассирам распечатать билеты еще раз')) {
					return false;
				}

				$.post(
					'/backend/ajax/resetSeats',
					{
						afisha_id: $('#afishaOrderParameters').data('id'),
						seats: seats
					},
					function(data) {
						if (data.success == true) {
							generate(data.message, 'success');
							$('input[name="RESET_SEATS[]"]:checked').prop('disabled', true).prop('checked', false)
								.parent('label').removeClass('checked').addClass('disabled');
						} else {
							generate(data.message, 'warning');
						}
					},
					'json'
				);
			});
		}
	});
</script>