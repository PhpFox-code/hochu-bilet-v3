<header class="wHeader">
    <?php echo Core\Widgets::get('topMenu') ?>

    <div class="fonHeaderAffiche">        
        <div class="wSize">
            <div class="wrapHeader">
                <div class="logo <?php echo count($cities) == 0 ? 'no_cities' : '' ?>">
                    <a href="/">
                        <img src="<?php echo Core\HTML::media("pic/logo.png")?>" alt="Хочу билет" />
                    </a>
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
            
        </div>
        <!-- .wSize -->
    </div>
    <!-- .fonHeaderAffiche -->
</header>
<!-- .wHeader -->
        
<div class="clear"></div>