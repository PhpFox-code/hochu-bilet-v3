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
				<div class="value"><?php echo (int) $obj->amount; ?> грн</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="statbox widget box box-shadow">
			<div class="widgetContent">
				<div class="visual yellow"><i class="fa-dropbox"></i></div>
				<div class="title">Товаров в заказе</div>
				<div class="value"><?php echo (int) $obj->count; ?></div>
			</div>
		</div>
	</div>
</div>
<div class="rowSection column-2">
	<div class="col-md-7">
		<div class="widget">
			<div class="widgetHeader"><div class="widgetTitle"><i class="fa-credit-card"></i>Заказ <span class="label label-primary">№ <?php echo $obj->id; ?></span> <a href="/backend/<?php echo Core\Route::controller(); ?>/print/id/<?php echo $obj->id; ?>" target="_blank">Распечатать</a></div></div>
			<div class="widgetContent">
				<div class="widget box">
					<div class="widgetHeader"><div class="widgetTitle"><i class="fa-clock-o"></i>Статус</div></div>
					<div class="widgetContent" style="padding-top: 0;" data-ajax="orderStatus">						
						<div class="rowSection">
							<div class="table-footer clearFix">
								<div class="col-md-12">
									<div class="input-group">
										<select class="form-control" name="status">
											<?php foreach( $statuses as $id => $name ): ?>
												<option value="<?php echo $id; ?>" <?php echo $obj->status == $id ? 'selected' : ''; ?>><?php echo $name; ?></option>
											<?php endforeach; ?>
										</select>
										<span class="input-group-btn">
											<div class="col-md-12">
											<button class="btn btn-primary" type="button">Обновить</button>
											</div>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="widget box">
					<div class="widgetHeader"><div class="widgetTitle"><i class="fa-clock-o"></i>Доставка</div></div>
					<div class="widgetContent" data-ajax="orderDelivery">
						<form class="form-vertical row-border">
							<div class="form-group">
								<label class="control-label col-md-2">Способ</label>
								<div class="col-md-10">
									<select class="form-control" name="delivery">
										<?php foreach( $delivery as $id => $name ): ?>
											<option value="<?php echo $id; ?>" <?php echo $obj->delivery == $id ? 'selected' : ''; ?>><?php echo $name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group" <?php echo $obj->delivery != 2 ? 'style="display: none;"' : ''; ?>>
								<label class="control-label col-md-2">Отделение</label>
								<div class="col-md-10">
									<input class="form-control" name="number" value="<?php echo $obj->number; ?>" />
								</div>
							</div>
							<div class="form-actions textright">
								<button class="btn btn-primary" type="button">Обновить</button>
							</div>
						</form>				
					</div>
				</div>
				<div class="widget box">
					<div class="widgetHeader"><div class="widgetTitle"><i class="fa-clock-o"></i>Оплата</div></div>
					<div class="widgetContent" style="padding-top: 0;" data-ajax="orderPayment">						
						<div class="rowSection">
							<div class="table-footer clearFix">
								<div class="col-md-12">
									<div class="input-group">
										<select class="form-control" name="payment">
											<?php foreach( $payment as $id => $name ): ?>
												<option value="<?php echo $id; ?>" <?php echo $obj->payment == $id ? 'selected' : ''; ?>><?php echo $name; ?></option>
											<?php endforeach; ?>
										</select>
										<span class="input-group-btn">
											<div class="col-md-12">
											<button class="btn btn-primary" type="button">Обновить</button>
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
	</div>
	<div class="col-md-5">
		<?php if( $user->id ): ?>
			<div class="widget">
				<div class="widgetHeader">
					<div class="widgetTitle">
						<i class="fa-user"></i>Клиент
						<?php if( $user->name ): ?>
							<span class="label label-primary"><?php echo $user->name; ?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="widgetContent">
					<div class="widget box">
						<div class="widgetContent no-padding">
							<div class="pageInfo alert alert-info">
								<?php if( $user->email ): ?>
									<div class="rowSection">
										<div class="col-md-6"><strong><i class="fa-envelope"></i> Email</strong></div>
										<div class="col-md-6"><a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></div>
									</div>
								<?php endif; ?>
								<?php if( $user->created_at ): ?>
									<div class="rowSection">
										<div class="col-md-6"><strong><i class="fa-calendar"></i> Аккаунт зарегистрирован</strong></div>
										<div class="col-md-6"><?php echo date('d.m.Y H:i', $user->created_at); ?></div>
									</div>
								<?php endif; ?>
								<div class="rowSection">
									<div class="col-md-6"><strong><i class="fa-tags"></i> Выполненых заказов</strong></div>
									<div class="col-md-6">
										<span class="label label-primary"><?php echo (int) $user->orders; ?></span>
									</div>
								</div>
								<div class="rowSection">
									<div class="col-md-6"><strong><i class="fa-tags"></i> Количество товаров в выполненых заказах</strong></div>
									<div class="col-md-6">
										<span class="label label-primary"><?php echo (int) $user->count_items; ?></span>
									</div>
								</div>
								<div class="rowSection">
									<div class="col-md-6"><strong><i class="fa-money"></i> Сумма выполненых заказов</strong></div> 	
									<div class="col-md-6">
										<span class="label label-success"><?php echo (int) $user->amount; ?> грн.</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="widget box">
			<div class="widgetHeader"><div class="widgetTitle"><i class="fa-user"></i>Заказчик</div></div>
			<div class="widgetContent" data-ajax="orderUser">
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
					<div class="form-actions textright">
						<button class="btn btn-primary" type="button">Обновить</button>
					</div>
				</form>				
			</div>
		</div>
	</div>
</div>
<div class="rowSection">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widgetHeader">
				<div class="widgetTitle"><i class="fa-shopping-cart"></i>Позиции <span class="label label-primary" id="orderPositionsCount"><?php echo count($cart); ?></span></div>
			</div>
			<div class="widgetContent">
				<table class="table tableOrderItems" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th width="1%" class="hidden-xs"></th>
							<th>Товар</th>
							<th>Цена</th>
							<th>Кол-во</th>
							<th>Итого</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody id="orderItemsList">
						<?php $amount = 0; ?>
						<?php foreach ($cart as $item): ?>
							<?php $amount += $item->cost * $item->count; ?>
							<tr>
								<td class="hidden-xs">
									<?php if( is_file(HOST.Core\HTML::media('images/catalog/small/'.$item->image)) ): ?>
										<a href="/backend/items/edit/<?php echo $item->id; ?>" class="tableOrderItemsThumb imageThumb" target="_blank">
											<img src="<?php echo Core\HTML::media('images/catalog/small/'.$item->image); ?>" class="w100">
										</a>
									<?php endif; ?>
								</td>
								<td>
									<a href="/backend/items/edit/<?php echo $item->id; ?>" target="_blank">
										<?php echo $item->name . ( $item->size_name ? ', ' . $item->size_name : '' ); ?>
									</a>
								</td>
								<td>
									<div class="tableOrderItemsCost middle"><?php echo $item->cost; ?></div>
									<div class="tableOrderItemsType middle">грн</div>
								</td>
								<td>
									<div class="input-width-mini">
										<input type="text" value="<?php echo $item->count; ?>" class="form-control spinner count" step="1" max="" min="0">
										<input type="hidden" class="catalog_id" value="<?php echo $item->id; ?>" />
										<input type="hidden" class="size_id" value="<?php echo (int) $item->size_id; ?>" />
									</div>
								</td>
								<td>
									<div class="tableOrderItemsSumm middle"></div>
									<div class="tableOrderItemsType middle">грн</div>
								</td>
								<td class="nav-column">
									<ul class="table-controls">
										<li>
											<a class="bs-tooltip dropdownToggle liTipLink" href="javascript:void(0);" title="Управление"><i class="fa-cog size14"></i></a>
											<ul class="dropdownMenu pull-right">
												<li><a title="Редактировать товар" href="/backend/items/edit/<?php echo $item->id; ?>" target="_blank"><i class="fa-pencil"></i> Редактировать товар</a></li>
												<li><a title="Просмотреть товар" href="/product/<?php echo $item->alias; ?>" target="_blank"><i class="fa-fixed-width">&#xf06e;</i> Посмотреть товар</a></li>
												<li class="divider"></li>
												<li><a href="#" title="Удалить позицию" class="orderPositionDelete"><i class="fa-trash-o text-danger"></i> Удалить позицию</a></li>
											</ul>
										</li>
									</ul>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<div class="rowSection">
					<div class="table-footer clearFix">
						<div class="col-md-12 textright">
							<button class="btn btn-default" type="button" href="/backend/orders/add_position/<?php echo $obj->id; ?>">Добавить товар</button>
							<button class="btn btn-primary" type="button" id="orderItems">Сохранить</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="rowSection">
	<div class="col-md-7"></div>
	<div class="col-md-5">
		<div class="widget">
			<div class="widgetContent no-padding">
				<table class="table table-hover ">
					<tbody>
						<tr>
							<td align="right"><strong>Итого</strong></td>
							<td align="right" id="orderAmount"><span><?php echo $amount; ?></span> грн</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<span id="orderParameters" data-id="<?php echo $obj->id; ?>"></span>