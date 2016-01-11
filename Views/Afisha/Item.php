<div class="titleMeasure">
    <span class="namePartners"><span><?php echo $obj->name ?></span></span>
</div>

<div class="measure">
    <div class="imgMeasure">
        <?php if( is_file(HOST.Core\HTML::media('images/afisha/medium/'.$obj->image)) ): ?>
            <img src="<?php echo Core\HTML::media('images/afisha/medium/'.$obj->image); ?>" alt="<?php echo $obj->name; ?>">
        <?php else: ?>
            <img src="<?php echo Core\HTML::media('pic/no-image.jpg'); ?>" alt="<?php echo $obj->name ?>">
        <?php endif ?>
    </div>

    <div class="wrapInfoMeasure">
        <div class="InfoMeasure">
            <div class="itemInfo">
                <p class="dateMeasure"><?php echo date('j', $obj->event_date) .' '. Core\Dates::month(date('n', $obj->event_date)) .' '. date('Y', $obj->event_date) ?></p>
                <p>Состоится в: <span class="nameMeasure">
                    <?php if ($obj->p_name OR strlen($obj->place_name) != 0): ?>
                        <?php echo Modules\Afisha\Models\Afisha::getItemPlace($obj) ?>
                    <?php else: ?>
                        Не указано
                    <?php endif; ?>
                </span></p>
                <p class="startTime">Начало в: <span class="nameMeasure"><?php echo $obj->event_time ?></span></p>
                <?php if($obj->add_field): ?>
                    <span class="priceMeasure"><?php echo $obj->add_field; ?></span>
                <?php else: ?>
                    <p>Цена билета: <span class="priceMeasure"></span><?php echo Modules\Afisha\Models\Afisha::getItemPrice($obj) ?></p>
                <?php endif; ?>
            </div>
            <span class="butMeasure">
                <a href="<?php echo Core\HTML::link( 'afisha/map/'.$obj->alias ) ?>" class="buyButton2">хочу билет</a>
                <div class="pluso_block" style="margin: 36px 0 0 47px;">
                    <script type="text/javascript">(function() {
                      if (window.pluso)if (typeof window.pluso.start == "function") return;
                      if (window.ifpluso==undefined) { window.ifpluso = 1;
                        var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                        s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                        s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                        var h=d[g]('body')[0];
                        h.appendChild(s);
                      }})();</script>
                    <div class="pluso" data-background="transparent" data-options="medium,square,line,horizontal,nocounter,theme=01" data-services="facebook,vkontakte,odnoklassniki"></div> 
                </div>
            </span>
        </div>
        <div class="textMeasure wTxt">
            <?php echo $obj->text ?>
        </div>
        <div class="backButton"><a href="<?php echo Core\HTML::link( 'afisha' ) ?>">&#9668;Назад</a></div>
    </div>
</div>