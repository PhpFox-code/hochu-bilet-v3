<?php if( count($cart) ): ?>
	<div class="basket_page_main clearFix">
		<div class="fll basket_form">
			<div form="true" class="wForm regBlock" data-ajax="checkout">
				<div class="wBasketHead"><div class="wBasketTTL">Оформление заказа</div></div>		    
	            <div class="wFormRow">
	                <select name="payment" id="select10" data-rule-required="true">
	                	<option value="">Выберите способ оплаты</option>
	                	<?php foreach( $payment as $id => $name ): ?>
	                		<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
	                	<?php endforeach ?>
	                </select>
	            </div>      
	            <div class="wFormRow">
	                <select name="delivery" id="select11" data-rule-required="true">
	                	<option value="">Выберите способ доставки</option>
	                	<?php foreach( $delivery as $id => $name ): ?>
	                		<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
	                	<?php endforeach ?>
	                </select>
	                <input type="text" class="dostavka" name="number" placeholder="Введите номер отделения Новой Почты" data-rule-required="true">
	            </div>              
	            <div class="wFormRow">
	                <input type="text" name="name" data-rule-bykvu="true" placeholder="Имя получателя" data-rule-minlength="2" data-rule-required="true">
	                <label>Фамилия и Имя получателя</label>
	            </div>		
	            <div class="wFormRow">
	                <input type="tel" class="tel" name="phone" data-rule-phoneUA="true" maxlength="19" minlength="19" placeholder="Телефон" data-rule-data-rule-required="truetrue">
	                <label>Телефон</label>
	            </div>         
	            <div class="butt">
	                <button class="wSubmit enterReg_btn">подтвердить заказ</button>
	            </div>
	        </div>
		</div>
		<div class="wBasket wBasketModule main_b">
			<div class="wBasketWrapp">
				<div class="wBasketHead">
					<div class="wBasketTTL">Корзина</div>
				</div>
				<div class="wBasketBody">
					<ul class="wBasketList">
						<?php $amount = 0; ?>
						<?php foreach( $cart as $key => $item ): ?>
						    <?php $obj = Core\Arr::get( $item, 'obj' ); ?>
						    <?php if( $obj ): ?>
						        <li class="wb_item" data-size="<?php echo $obj->size_id; ?>" data-id="<?php echo $obj->id; ?>" data-count="<?php echo Core\Arr::get($item, 'count', 1) ?>" data-price="<?php echo $obj->cost; ?>">
						            <div class="wb_li">
						                <?php if( is_file(HOST.Core\HTML::media('images/catalog/medium/'.$obj->image)) ): ?>
						                    <div class="wb_side">
						                        <div class="wb_img">
						                            <a href="<?php echo Core\HTML::link('product/'.$obj->alias); ?>" class="wbLeave">
						                                <img src="<?php echo Core\HTML::media('images/catalog/medium/'.$obj->image); ?>" />
						                            </a>
						                        </div>
						                    </div>
						                <?php endif; ?>
						                <div class="wb_content">
						                    <div class="wb_row">
						                        <div class="wb_del"><span title="Удалить товар">Удалить товар</span></div>
						                        <div class="wb_ttl">
						                            <a href="<?php echo Core\HTML::link('product/'.$obj->alias); ?>" class="wbLeave">
						                                <?php echo $obj->name . ( $obj->size_id ? ', ' . $obj->size_name : '' ); ?>
						                            </a>
						                        </div>
						                    </div>
						                    <div class="wb_cntrl">
						                        <div class="wb_price_one"><p><span><?php echo $obj->cost; ?></span> грн.</p></div>
						                        <div class="wb_amount_wrapp">
						                            <div class="wb_amount">
						                                <input type="text" class="editCountItem" value="<?php echo Core\Arr::get($item, 'count', 1); ?>">
						                                <span data-spin="plus" class="editCountItem"></span>
						                                <span data-spin="minus" class="editCountItem"></span>
						                            </div>
						                        </div>
						                        <div class="wb_price_totl"><p><span><?php echo $obj->cost * Core\Arr::get($item, 'count', 1); ?></span> грн.</p></div>
						                    </div>
						                </div>
						            </div>
						        </li>
						        <?php $amount += $obj->cost * Core\Arr::get($item, 'count', 1); ?>
						    <?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="wBasketFooter">
					<div class="wb_footer">
	                    <div class="tar wb_footer_tot">                                 
	                        <div class="wb_total">Итого: <span id="topCartAmount"><?php echo $amount; ?></span> грн.</div>
	                    </div>
	                    <div class="flr wb_footer_go">
	                        <div class="wb_gobasket">
	                            <a href="<?php echo Core\HTML::link('catalog'); ?>" class="wb_butt"><span>Продолжить покупки</span></a>
	                        </div>
	                    </div>
	                </div>
				</div>
			</div>					
		</div>
	</div>
<?php else: ?>
	<p class="emptyCartBlock">Ваша корзина пуста. <a href="<?php echo Core\HTML::link('catalog'); ?>">Начните делать покупки прямо сейчас!</a></p>
<?php endif ?>