<div class="rowSection clearFix row-bg">
<!--     <div class="col-sm-6 col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual cyan">
                    <i class="fa-shopping-cart"></i>
                </div>
                <div class="title">
                    Заказы
                </div>
                <div class="value">
                    <?php echo $count_orders; ?>
                </div>
                <a href="/backend/orders/index" class="more">Подробнее <i class="pull-right fa-angle-right"></i></a>
            </div>
        </div>
    </div> -->
<!--     <div class="col-sm-6 col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual green">
                    <i class="fa-comments-o"></i>
                </div>
                <div class="title">
                    Отзывы к товарам
                </div>
                <div class="value">
                    <?php echo $count_comments; ?>
                </div>
                <a href="/backend/comments/index" class="more">Подробнее <i class="pull-right fa-angle-right"></i></a>
            </div>
        </div>
    </div> -->
<!--     <div class="col-sm-6 col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual yellow">
                    <i class="pull-right fa-fixed-width">&#xf11b;</i>
                </div>
                <div class="title">
                    Товары
                </div>
                <div class="value">
                    <?php echo $count_catalog; ?>
                </div>
                <a href="/backend/items/index" class="more">Подробнее <i class="pull-right fa-angle-right"></i></a>
            </div>
        </div>
    </div> -->
<!--     <div class="col-sm-6 col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual black">
                    <i class="fa-users"></i>
                </div>
                <div class="title">
                    Пользователи
                </div>
                <div class="value">
                    <?php echo $count_users; ?>
                </div>
                <a href="/backend/users/index" class="more">Подробнее <i class="pull-right fa-angle-right"></i></a>
            </div>
        </div>
    </div> -->
    <?php if (Core\User::get_access_for_controller('subscribers') != 'no'): ?>
        <div class="col-sm-6 col-md-3">
            <div class="statbox widget box box-shadow">
                <div class="widgetContent">
                    <div class="visual purple">
                        <i class="fa-envelope-o"></i>
                    </div>
                    <div class="title">
                        Подписчики
                    </div>
                    <div class="value">
                        <?php echo $count_subscribers; ?>
                    </div>
                    <a href="/backend/subscribers/index" class="more">Подробнее <i class="pull-right fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
<!--     <div class="col-sm-6 col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual blue">
                    <i class="fa-language"></i>
                </div>
                <div class="title">
                    Статьи
                </div>
                <div class="value">
                    <?php echo $count_articles; ?>
                </div>
                <a href="/backend/articles/index" class="more">Подробнее <i class="pull-right fa-angle-right"></i></a>
            </div>
        </div>
    </div> -->
<!--     <div class="col-sm-6 col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widgetContent">
                <div class="visual red">
                    <i class="fa-bullhorn"></i>
                </div>
                <div class="title">
                    Новости
                </div>
                <div class="value">
                    <?php echo $count_news; ?>
                </div>
                <a href="/backend/news/index" class="more">Подробнее <i class="pull-right fa-angle-right"></i></a>
            </div>
        </div>
    </div> -->
    <?php if (Core\User::get_access_for_controller('banners') != 'no'): ?>
        <div class="col-sm-6 col-md-3">
            <div class="statbox widget box box-shadow">
                <div class="widgetContent">
                    <div class="visual orange">
                        <i class="fa-puzzle-piece"></i>
                    </div>
                    <div class="title">
                        Банера
                    </div>
                    <div class="value">
                        <?php echo $count_banners; ?>
                    </div>
                    <a href="/backend/banners/index" class="more">Подробнее <i class="pull-right fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>