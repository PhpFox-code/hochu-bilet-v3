<div class="fll">
    <div class="prevue_block clearFix">
        <div class="fll">
            <div class="prev3"><span></span></div>
            <div class="next3"><span></span></div>
            <ul>
                <?php foreach( $images as $im ): ?>
                    <?php if( is_file(HOST.Core\HTML::media('images/catalog/small/'.$im->image)) ): ?>
                        <li>
                            <div class="img_prevue" data-img-src="<?php echo Core\HTML::media('images/catalog/big/'.$im->image); ?>" data-img-src-original="<?php echo Core\HTML::media('images/catalog/original/'.$im->image); ?>">
                                <img src="<?php echo Core\HTML::media('images/catalog/small/'.$im->image); ?>" />
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="prevue_block_in">
            <div class="big_prevue fresco" data-fresco-group="gr1" href="<?php echo Core\HTML::media('images/catalog/original/'.$images[0]->image) ?>">
                <?php if( is_file(HOST.Core\HTML::media('images/catalog/big/'.$images[0]->image)) ): ?>
                    <img src="<?php echo Core\HTML::media('images/catalog/big/'.$images[0]->image); ?>" alt="" />
                <?php endif; ?>
                <?php echo Core\Support::addItemTag($obj); ?>
            </div>
        </div>
    </div>
    <?php echo Core\Widgets::get('socials'); ?>
    <?php echo Core\Widgets::get('comments'); ?>
</div>
<div class="flr">
    <div class="tovar_info">
        <div class="tovar_name"><h1><?php echo Core\Config::get('h1'); ?></h1></div>
        <?php if ($obj->sale): ?>
            <div class="old_price2">
                <span><?php echo $obj->cost_old; ?></span> грн
            </div>
        <?php endif ?>
        <div class="tovar_price">
            <?php echo $obj->cost; ?> <span>грн</span>
        </div>
        <?php if( $obj->available == 0 ): ?>
            <div class="tovar_vNall">
                <p>товара нет в наличии</p>
            </div>
        <?php endif; ?>
        <?php if( $obj->available == 1 ): ?>
            <div class="tovar_vNall">
                <div class="vNall_img"></div>
                <p>товар в наличии</p>
                <p><?php echo Core\Config::get('dostavka_est'); ?></p>
            </div>
        <?php endif; ?>
        <?php if( $obj->available == 2 ): ?>
            <div class="tovar_vNall noNall">
                <div class="vNall_img"></div>
                <p>товар под заказ</p>
                <?php echo Core\Config::get('dostavka_bron'); ?>
            </div>
        <?php endif; ?>
        <div class="sel_but clearFix">
            <div class="fll">
                <?php if ( count($sizes) ): ?>
                    <select id="select5">
                        <?php foreach ($sizes as $size): ?>
                            <option value="<?php echo $size->id; ?>"><?php echo $size->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a href="#enterReg3" class="popup_but enterReg3"><span>Как правильно подобрать размер</span></a>
                <?php endif; ?>
            </div>
            <div class="flr">
                <a href="#" class="buy_but addToCart" data-id="<?php echo $obj->id; ?>"><span>В КОРЗИНУ</span></a>
                <a href="#enterReg5" class="popup_but enterReg5" data-id="<?php echo $obj->id; ?>"><span>КУПИТЬ В ОДИН КЛИК</span></a>
            </div>
        </div>
    </div>
    <div class="tovar_info2">
        <div class="middle_title">характеристики товара</div>
        <div class="har_table">
            <table>
                <?php if( $obj->artikul ): ?>
                    <tr>
                        <td>артикул</td>
                        <td><?php echo $obj->artikul; ?></td>
                    </tr>
                <?php endif; ?>
                <?php if( $obj->brand_name ): ?>
                    <tr>
                        <td>бренд</td>
                        <td><a href="<?php echo Core\HTML::link('brands/'.$obj->alias); ?>"><?php echo $obj->brand_name; ?></a></td>
                    </tr>
                <?php endif; ?>
                <?php if( $obj->model_name ): ?>
                    <tr>
                        <td>модель</td>
                        <td><?php echo $obj->model_name; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>пол</td>
                    <td><?php echo $obj->sex == 2 ? 'Унисекс' : ( $obj->sex ? 'Женская модель' : 'Мужская модель' ); ?></td>
                </tr>
                <?php foreach ($specifications as $name => $value): ?>
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $value; ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
        <?php echo Core\Widgets::get('infoItemPage'); ?>
        <?php echo Core\Widgets::get('questionAboutItem'); ?>
    </div>
</div>