<div class="partners">
    <div class="namePartnersP">
        <span class="namePartners">наши партнеры</span>
    </div>
    <div class="clear"></div>
    
    <div class="partnerBanner">
        <ul>
            <?php foreach ( $result as $obj ): ?>
                <?php if (is_file(HOST.Core\HTML::media('images/banners/'.$obj->image))): ?>
                    <li>
                        <?php if ( $obj->url ): ?>
                            <a href="<?php echo $obj->url ?>" target="_blank">
                        <?php endif; ?>
                            <img src="<?php echo Core\HTML::media('images/banners/'.$obj->image); ?>" alt="<?php echo $obj->small ?>">
                        <?php if ( $obj->url ): ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>