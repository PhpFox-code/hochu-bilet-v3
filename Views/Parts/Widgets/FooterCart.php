<footer class="wFooter basket_page">
	<div class="wSize">
		<div class="foot_bot clearFix">
			<div class="fll">
				<div class="logo_foot">
					<img src="<?php echo Core\HTML::media('pic/logo_foot.png'); ?>" alt="">
					<p><?php echo Core\Config::get('copyright'); ?></p>
				</div>
			</div>
			<div class="flr">
				<a href="http://wezom.com.ua" target="_blank" class="weZom"><span>Разработка сайта — студия</span></a>
			</div>
			<div class="flc">
                <?php if ( Core\Config::get('subscribe_text') ): ?>
                    <p><?php echo Core\Config::get('subscribe_text'); ?></p>
                <?php endif ?>
                <div class="foot_podp">
                    <div form="true" class="wForm regBlock" data-ajax="subscribe">
                        <div class="tar">
                            <button class="wSubmit enterReg_btn">подписаться</button>
                        </div>
                        <div class="wFormRow">
                            <input type="email" name="email" placeholder="E-mail" required="">
                            <label>E-mail</label>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<?php foreach (Core\Config::get('counters') as $counter): ?>
            <?php echo $counter; ?>
        <?php endforeach ?>
	</div>
</footer>