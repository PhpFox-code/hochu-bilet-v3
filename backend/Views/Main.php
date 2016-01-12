<!DOCTYPE html>
<html>
<head>
    <?php echo Core\Widgets::get('head', $_seo); ?>
</head>
<body>
    <?php echo Core\Widgets::get('header'); ?>
    <div class="container fixedHeader">
        <?php echo Core\Widgets::get('sidebar'); ?>
        <div class="contentWrap">
            <div class="contentWrapMar">
                <?php echo $_breadcrumbs; ?>
                <div class="page-header clearFix">
                    <div class="page-title">
                        <h3>
                            <?php echo Core\Arr::get($_seo, 'h1'); ?>
                        </h3>
                        <span>
                            Здравствуйте, <?php echo Core\User::info()->name; ?>!
                        </span>
                    </div>
                    <?php echo $_toolbar; ?>
                </div>
                <div class="rowSection <?php echo Core\Config::get( 'collsClass' ); ?>">
                    <div class="col-md-12">
                        <div class="widget checkbox-wrap">
                            <?php echo $_filter; ?>
                            <div class="widgetContent">
                                <?php echo $_content; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="text-align: right;"><?php echo $_toolbar; ?></div>
                <?php echo Core\Widgets::get('footer'); ?>
            </div>
        </div>
    </div>
</body>
</html>