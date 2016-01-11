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
                <div class="folt_cat_block no_filt clearFix">
                    <div class="flr">
                        <div class="title"><?php echo Core\Config::get('h1'); ?></div>
                        <?php echo Core\Config::get('brand_description'); ?>
                        <?php echo Core\Widgets::get('catalogSort'); ?>
                        <?php echo $_content; ?>
                    </div>
                </div>
                <div class="novelty clearFix">
                    <?php echo Core\Widgets::get('catalogViewed'); ?>
                </div>
                <div id="clonSeo"></div>
            </div>
        </div>
    </div>
    <?php echo Core\Widgets::get('hiddenData'); ?>
    <?php echo Core\Widgets::get('footer', array('counters' => Core\Arr::get($_seo, 'counters'), 'config' => $_config)); ?>
</body>
</html>