<!DOCTYPE html>
<html lang="ru-ru" dir="ltr">
<!-- (c) студия Wezom | www.wezom.com.ua -->
<head>
    <?php echo Core\Widgets::get('head', $_seo); ?>
    <?php foreach ( $_seo['metrika']['head'] as $metrika ): ?>
        <?php echo $metrika; ?>
    <?php endforeach ?>
</head>
<body>
    <?php foreach ( $_seo['metrika']['body'] as $metrika ): ?>
        <?php echo $metrika; ?>
    <?php endforeach ?>
    <div class="wWrapper">
        <?php if ($_content): ?>
            <div id="wSeo">
                <div class="wSize">
                    <div class="seo wTxt">
                        <h1><?php echo $_seo['h1'] ?></h1>
                        <?php echo $_content; ?>
                    </div>
                </div>
                <!-- .wSize -->
            </div>
            <!-- .wSeo -->
            <div class="clear"></div>
        <?php endif ?>
        <?php echo Core\Widgets::get('mainHeader', array('config' => $_config)); ?>
        
        <div class="wConteiner">
            <div class="wSize">
                <?php echo Core\Widgets::get('mainAfisha'); ?>
                <?php echo Core\Widgets::get('banners'); ?>

            </div>
            <div class="clear"></div>
            <div id="clonSeo" class="clonSeo"></div>
            <!-- SEO place -->
        </div>
    </div>
    <div class="clear"></div>
    
    <?php echo Core\Widgets::get('footer', array('counters' => Core\Arr::get($_seo, 'counters'), 'config' => $_config)); ?>
    <?php echo Core\Widgets::get('hiddenData'); ?>
</body>
</html>