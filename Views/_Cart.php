<!DOCTYPE html>
<html lang="ru-ru" dir="ltr">
<!-- (c) студия Wezom | www.wezom.com.ua -->
<head>	
	<?php echo Core\Widgets::get('head', $_seo); ?>
    <?php foreach ( $_seo['metrika']['head'] as $metrika ): ?>
        <?php echo $metrika; ?>
    <?php endforeach ?>
</head>
<body>
</div>
	<?php foreach ( $_seo['metrika']['body'] as $metrika ): ?>
        <?php echo $metrika; ?>
    <?php endforeach ?>
	<div class="wWrapper basket_page">
		<?php echo Core\Widgets::get('headerCart'); ?>
		<div class="wConteiner">
			<div class="wSize" id="cartContentPart">
				<?php echo $_content; ?>
			</div>
		</div>
	</div>
	<?php echo Core\Widgets::get('hiddenData'); ?>
    <?php echo Core\Widgets::get('footer', array('counters' => Core\Arr::get($_seo, 'counters'), 'config' => $_config)); ?>
</body>
</html>