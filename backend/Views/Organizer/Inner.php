<div class="rowSection">
	<div class="col-md-12">
		<div class="widget">
			<div class="widgetContent">

				<?php echo Core\View::tpl(array('creators' => array(), 'events' => $events), $tpl_folder.'/Filter'); ?>

				<div class="checkbox-wrap">
					<?php foreach($result as $key => $item): ?>
						<?php $poster = $item['poster']; ?>
						<div style="margin-bottom: 20px;" class="eventWrapp">
							<div class="eventName">
								<a class="fll" href="/backend/afisha/edit/<?php echo $poster->id; ?>"><?php echo $poster->name ?></a>
								<a class="flr export" href="/backend/organizer/export/<?php echo $poster->id; ?>" target="_blank">Экспорт в Excel</a>
								<span class="flr">&nbsp;</span>
								<a class="flr printTable" href="#">Распечатать</a>
							</div>
							<div class="eventOrders">
								<table class="table table-striped table-bordered organizer_stat" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th rowspan="2" style="vertical-align: middle">Цена билета</th>
											<th colspan="2">Приход</th>
											<th colspan="2">Бронь админа</th>
											<th colspan="2">Бронь на сайте</th>
											<th colspan="2">Остаток</th>
											<th colspan="2">Продано</th>
										</tr>
										<tr>
											<th>кол-во</th>
											<th>сума</th>
											<th>кол-во</th>
											<th>сума</th>
											<th>кол-во</th>
											<th>сума</th>
											<th>кол-во</th>
											<th>сума</th>
											<th>кол-во</th>
											<th>сума</th>
										</tr>
									</thead>
									<tbody>
										<?php $totComingQuantity = $totComingSum = $totAdminQuantity = $totAdminSum =
											$totSiteQuantity = $totSiteSum = $totResidueQuantity = $totResidueSum =
											$totSoldQuantity = $totSoldSum = 0;  ?>
										<?php foreach ( $item['detailed'] as $key => $el ): ?>
											<tr>
												<td><?php echo $el['price']->price; ?></td>

												<td><?php echo $el['coming_quantity'] ?></td>
												<td><?php echo $el['coming_sum'] ?></td>

												<td><?php echo $el['admin_brone_quantity'] ?></td>
												<td><?php echo $el['admin_brone_sum'] ?></td>

												<td><?php echo $el['site_brone_quantity'] ?></td>
												<td><?php echo $el['site_brone_sum'] ?></td>

												<td><?php echo $el['residue_quantity'] ?></td>
												<td><?php echo $el['residue_sum'] ?></td>

												<td><?php echo $el['sold_quantity'] ?></td>
												<td><?php echo $el['sold_sum'] ?></td>

												<?php $totComingQuantity += $el['coming_quantity']; ?>
												<?php $totComingSum += $el['coming_sum']; ?>
												<?php $totAdminQuantity += $el['admin_brone_quantity']; ?>
												<?php $totAdminSum += $el['admin_brone_sum']; ?>
												<?php $totSiteQuantity += $el['site_brone_quantity']; ?>
												<?php $totSiteSum += $el['site_brone_sum']; ?>
												<?php $totResidueQuantity += $el['residue_quantity']; ?>
												<?php $totResidueSum += $el['residue_sum']; ?>
												<?php $totSoldQuantity += $el['sold_quantity']; ?>
												<?php $totSoldSum += $el['sold_sum']; ?>
										<?php endforeach ?>
									</tbody>
									<tfoot>
									<tr>
										<td></td>
										<td><?php echo $totComingQuantity; ?></td>
										<td><?php echo $totComingSum; ?></td>
										<td><?php echo $totAdminQuantity; ?></td>
										<td><?php echo $totAdminSum; ?></td>
										<td><?php echo $totSiteQuantity; ?></td>
										<td><?php echo $totSiteSum; ?></td>
										<td><?php echo $totResidueQuantity; ?></td>
										<td><?php echo $totResidueSum; ?></td>
										<td><?php echo $totSoldQuantity; ?></td>
										<td><?php echo $totSoldSum; ?></td>
									</tr>
									</tfoot>
								</table>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
