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
    <div class="seoTxt" id="seoTxt">
        <div class="wSize wTxt">
            <?php echo trim(strip_tags(Core\Config::get('seo_text'))) ? Core\Config::get('seo_text') : ''; ?>
        </div>
    </div>
    <div class="wWrapper">
        <?php echo Core\Widgets::get('header'); ?>
        <div class="wConteiner">
            <div class="wSize">
                <?php echo $_breadcrumbs; ?>
                <div class="folt_cat_block clearFix cat_ul_block">
                    <div class="fll">
                        <?php echo Core\Widgets::get('catalogMenuLeft'); ?>
                    </div>
                    <div class="flr">
                        <?php echo $_content; ?>
                    </div>
                </div>
                <?php echo Core\Widgets::get('vk'); ?>
                <?php echo Core\Widgets::get('news'); ?>
                <?php echo Core\Widgets::get('articles'); ?>
                <div class="clear"></div>
                <div id="clonSeo"></div>
            </div>
        </div>
    </div>
    <?php echo Core\Widgets::get('hiddenData'); ?>
    <?php echo Core\Widgets::get('footer', array('counters' => Core\Arr::get($_seo, 'counters'), 'config' => $_config)); ?>
</body>
</html>