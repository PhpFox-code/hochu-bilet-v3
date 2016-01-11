<div class="nameAfficheWrap">
    <span class="nameAffiche"><span>афиша мероприятий</span></span>
</div>
<div class="clear"></div>
<?php if (count($result)): ?>
     <div class="wrapPost">
        <?php foreach ($result as $key => $obj): ?>
            <div class="post">
                <div class="postW view">
                    <?php if( is_file(HOST.Core\HTML::media('images/afisha/medium/'.$obj->image)) ): ?>
                        <img src="<?php echo Core\HTML::media('images/afisha/medium/'.$obj->image); ?>" alt="<?php echo $obj->name; ?>">
                    <?php else: ?>
                        <img src="<?php echo Core\HTML::media('pic/no-image.jpg'); ?>" alt="<?php echo $obj->name ?>">
                    <?php endif ?>
                    <a href="<?php echo Core\HTML::link( 'afisha/'.$obj->alias ) ?>">
                    <div class="mask">
                        <ul>
                            <li><?php echo $obj->name ?></li>
                            <?php if ($obj->p_name OR strlen($obj->place_name) != 0): ?>
                                <li><?php echo Modules\Afisha\Models\Afisha::getItemPlace($obj) ?></li>
                            <?php else: ?>
                                <li>Не указано</li>
                            <?php endif ?>
                            <li><?php echo date('j', $obj->event_date) .' '. Core\Dates::month(date('n', $obj->event_date)) .' '. date('Y', $obj->event_date) . ' ' . $obj->event_time ?></li>
                        </ul>
                    </div>
                    <a href="<?php echo Core\HTML::link( 'afisha/' . $obj->alias ) ?>" class="buyButton">хочу билет</a>
                </div>
                <div class="substrate">
                    <?php if($obj->add_field): ?>
                        <span class="price"><?php echo $obj->add_field; ?></span>
                    <?php else: ?>
                        <span class="price"><?php echo Modules\Afisha\Models\Afisha::getItemPrice($obj) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
    <div class="clear"></div>
    <?php if (isset($pager) && strlen($pager) > 0): ?>
        <div class="afficheButStill"><a href="#" id="moreAffishe" data-page="2">Еще афиши...</a></div>
        <div class="clear"></div>
    <?php endif ?>
<?php else: ?>
    <h3>Афиши отсутствуют</h3>
<?php endif ?>