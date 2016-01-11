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
        <?php echo Core\Widgets::get('header', array('config' => $_config)); ?>
        
        <div class="wConteiner">
            <div class="wSize">
                <?php echo $_breadcrumbs ?>
                <div class="textPage">
                    <h1 class="h1T"><?php echo Core\Arr::get($_seo, 'h1') ?></h1>
                    <div class="wTxt clearFix">
                         <div class="page_404">
                            <p>404</p>
                            <p>Страница не найдена. Начните с <a href="<?php echo Core\HTML::link(); ?>">Главной страницы</a>.</p>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>            
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
    
    <?php echo Core\Widgets::get('footer', array('counters' => Core\Arr::get($_seo, 'counters'), 'config' => $_config)); ?>
    <?php echo Core\Widgets::get('hiddenData'); ?>
</body>
</html>