<footer class="wFooter">
    <div class="wSize">
        <div class="footer">
            <div class="footerLogo">
                <img src="<?php echo Core\HTML::media( 'pic/footer_logo.png' ) ?>" alt="Хочу билет" />
            </div>
            
            <?php echo Core\Widgets::get( 'footerMenu' ) ?>
            
            <div class="banner">
                <!-- <a href="#">
                    <img src="<?php #echo Core\HTML::media( 'pic/banner.gif' ) ?>" alt="baner" />
                </a> -->
            </div>
            <div class="clear"></div>
            
            <p class="wezom">Разработка сайта - <a href="http://wezom.mk.ua/" target="_blank">студия Wezom</a></p>
        </div>
    </div>
    <!-- .wSize -->
</footer>
<!-- .wFooter -->

<div class="clear"></div>

<footer class="wFooter2">
    <div class="wSize">
        <div class="wTxt">
            <span class="customText">&copy; <?php echo date('Y') ?> <?php echo Core\Config::get( 'copyright' ) ?></span>
        </div>
        <span class="textSocialF">Мы в социальных сетях:</span>
        <ul class="listSocialF">
            <li><a href="<?php echo Core\Config::get( 'vk' ) ?>" rel="nofolow" class="vk"></a></li>
            <li><a href="<?php echo Core\Config::get( 'ok' ) ?>" rel="nofolow" class="ok"></a></li>
            <li><a href="<?php echo Core\Config::get( 'fb' ) ?>" rel="nofolow" class="facebook"></a></li>
            <li><a href="<?php echo Core\Config::get( 'in' ) ?>" rel="nofolow" class="instagramm"></a></li>
        </ul>
    </div>
    <!-- .wSize -->
</footer>   
<!-- .wFooter2 -->