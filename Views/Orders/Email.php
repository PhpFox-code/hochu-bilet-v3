<table>
	<tr>
		<td>Имя:</td>
		<td><?php echo $order['name'] ?></td>
	</tr>
	<tr>
		<td>Email:</td>
		<td><?php echo $order['email'] ?></td>
	</tr>
	<tr>
		<td>Телефон:</td>
		<td><a href="tel:<?php echo $order['phone'] ?>"><?php echo $order['phone'] ?></a></td>
	</tr>
	<tr>
		<td>Сообщение:</td>
		<td><?php echo $order['message'] ?></td>
	</tr>
</table>

<p>Данные о заказе:</p>
<div>
	<?php echo $order_text ?>
</div>