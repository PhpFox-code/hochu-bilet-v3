<header class="wHeader">
    <?php echo Core\Widgets::get('topMenu') ?>

    <div class="fonHeader">
        <ul id="parallax">
            <li class="layer" data-depth="-0.70" data-par="1"><img src="<?php echo Core\HTML::media("pic/layer_1.png")?>" height="151" width="212" alt=""/></li>
            <li class="layer" data-depth="0.55" data-par="2"><img src="<?php echo Core\HTML::media("pic/layer_2.png")?>" height="370" width="408" alt=""/></li>
            <li class="layer" data-depth="-0.40" data-par="3"><img src="<?php echo Core\HTML::media("pic/layer_3.png")?>" height="114" width="139" alt=""/></li>
            <li class="layer" data-depth="0.25" data-par="4"><img src="<?php echo Core\HTML::media("pic/layer_4.png")?>" height="263" width="309" alt=""/></li>
        </ul>
        <script src="<?php echo Core\HTML::media("js/parallax.js")?>"></script>
        <script>
            var scene = document.getElementById('parallax');
            var parallax = new Parallax(scene, {
                relativeInput: true,
                clipRelativeInput: true
            });
        </script>
        
        <div class="wSize">
            <div class="wrapHeader">
                <div class="logo <?php echo count($cities) == 0 ? 'no_cities' : '' ?>">
                    <img src="<?php echo Core\HTML::media("pic/logo.png")?>" alt="Хочу билет" />
                </div>
                <?php if (count($cities) > 0): ?>
                    <div class="select">
                        <span class="selectText">Выбрать город</span>
                        <select id="select" class="selCity">
                            <option value="0" <?php echo !isset($_SESSION['idCity']) ? 'selected' : null ?>>Все города</option>
                            <?php foreach ($cities as $key => $city): ?>
                                <option value="<?php echo $city->id ?>" <?php if (isset($_SESSION['idCity']) && $_SESSION['idCity'] == $city->id) echo 'selected' ?>><?php echo $city->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endif ?>
                <div class="contact">
                    <p class="phone wTxt"><?php echo Core\Config::get( 'phone1' ) ?></p>
                    <p class="secondPhone wTxt"><?php echo Core\Config::get( 'phone2' ) ?></p>
                    <span class="callButton"><a href="#orderCall" class="orderCall">Заказать звонок</a></span>
                </div>
                
                <div class="social">
                    <span class="socialText">Мы в социальных сетях:</span>
                    <ul class="listSocial">
                        <li><a href="<?php echo Core\Config::get( 'vk' ) ?>" rel="nofolow" class="vk"></a></li>
                        <li><a href="<?php echo Core\Config::get( 'ok' ) ?>" rel="nofolow" class="ok"></a></li>
                        <li><a href="<?php echo Core\Config::get( 'fb' ) ?>" rel="nofolow" class="facebook"></a></li>
                        <li><a href="<?php echo Core\Config::get( 'in' ) ?>" rel="nofolow" class="instagramm"></a></li>
                    </ul>
                </div>
                <!-- .social -->
            </div>
            <!-- .wrapHeader -->
            
            <div class="clear"></div>
            <?php if (count($slider)): ?>
                <div class="carousel">
                    <ul id="carousel">
                        <?php foreach ($slider as $key => $slide): ?>
                            <li>
                                <?php if (strlen($slide->url)): ?>
                                    <a href="<?php echo Core\HTML::link($slide->url) ?>">
                                <?php endif ?>

                                    <?php if ( is_file(HOST.Core\HTML::media('images/slider/big/'.$slide->image))): ?>
                                        <img src="<?php echo Core\HTML::media('images/slider/big/'.$slide->image); ?>" alt="<?php echo $slide->name ?>">
                                    <?php endif; ?>
                                
                                <?php if (strlen($slide->url)): ?>
                                    </a>
                                <?php endif ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                    <a id="prev" class="prev" href="#"></a>
                    <a id="next" class="next" href="#"></a>
                </div>
            <?php endif ?>
        </div>
        <!-- .wSize -->
    </div>
    <!-- .fonHeader -->
</header>
<!-- .wHeader -->
        
<div class="clear"></div>