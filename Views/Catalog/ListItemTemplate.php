<li>
    <a href="<?php echo Core\HTML::link('product/'.$obj->alias); ?>" class="img_tovar">
        <?php if( is_file(HOST.Core\HTML::media('images/catalog/medium/'.$obj->image)) ): ?>
            <img src="<?php echo Core\HTML::media('images/catalog/medium/'.$obj->image); ?>" alt="<?php echo $obj->name; ?>">
        <?php else: ?>
            <img src="<?php echo Core\HTML::media('pic/no-photo.png'); ?>" alt="">
        <?php endif ?>
        <?php echo Core\Support::addItemTag($obj); ?>
    </a>
    <div class="tovar_kat"><?php echo $obj->sex == 2 ? 'Унисекс' : ($obj->sex == 1 ? 'Для женщин' : 'Для мужчин'); ?></div>
    <a href="<?php echo Core\HTML::link('product/'.$obj->alias); ?>" class="tovar_name"><span><?php echo $obj->name; ?></span></a>
    <?php if( $obj->sale ): ?>
        <div class="old_price"><span><?php echo $obj->cost_old; ?></span> грн</div>
    <?php endif; ?>
    <div class="tovar_price"><span><?php echo $obj->cost; ?></span> грн</div>
    <a href="<?php echo Core\HTML::link('product/'.$obj->alias); ?>" class="buy_but"><span>КУПИТЬ</span></a>
    <a href="#enterReg5" class="enterReg5 buy_for_click" data-id="<?php echo $obj->id; ?>"><span>КУПИТЬ В ОДИН КЛИК</span></a>
</li>