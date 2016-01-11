<div class="textPage wTxt">
	<h1><?php echo $item->name ?></h1>
	<?php echo Core\Config::get('map_text') ?>
</div>
<div class="backButton tal"><a href="/afisha/<?php echo $item->alias ?>">◄Назад к афише</a></div>
<!-- .textPage -->
<div class="map_price"></div>
<div class="slider_wrap">
	<div class="reset_zoom">1:1</div>   
    <div id="slider_zoom">
        <div class="ui-slider-handle"><span>100%</span></div>
    </div>
    <div class="clear"></div>
</div>
<div class="map_svg_wrap <?php #echo strtolower($item->filename) ?>">
	<div class="map_svg">
		
		
		<?php echo $map ?>
		
	</div>
</div>
<div class="selected">
    <p class="selected_kol">Выбрано мест: <span>0</span></p>
    <div class="selected_row"></div>
    <p class="selected_sum">Сумма: <span>0</span> грн</p>
</div>
<div class="mapBtn">
	<span class="wBtn mfi buyButton2" data-href="#orderDemo2" data-id="<?php echo $item->id ?>">Купить</span>
	<div class="empty_seat"></div>
</div>		
<div class="clear"></div>