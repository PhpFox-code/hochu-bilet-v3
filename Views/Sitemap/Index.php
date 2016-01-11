<ul class="sitemap">
    <?php foreach($pages[0] AS $obj): ?>
        <li><a href="<?php echo Core\HTML::link($obj->alias); ?>"><?php echo $obj->name; ?></a>
            <?php echo Core\View::tpl(array('result' => $pages, 'cur' => $obj->id, 'add' => ''), 'Sitemap/Recursive'); ?>
        </li>
    <?php endforeach; ?>
    <li><a href="<?php echo Core\HTML::link('catalog'); ?>">Каталог</a>
        <?php echo Core\View::tpl(array('result' => $groups, 'cur' => 0, 'add' => '/catalog'), 'Sitemap/Recursive'); ?>
    </li>
    <li><a href="<?php echo Core\HTML::link('new'); ?>/new">Новинки</a></li>
    <li><a href="<?php echo Core\HTML::link('popular'); ?>/popular">Популярные</a></li>
    <li><a href="<?php echo Core\HTML::link('sale'); ?>/sale">Акции</a></li>
    <li><a href="<?php echo Core\HTML::link('viewed'); ?>/viewed">Просмотренные</a></li>
    <li><a href="<?php echo Core\HTML::link('brands'); ?>/brands">Бренды</a>
        <?php if(count($brands)): ?>
            <ul>
                <?php foreach($brands AS $obj): ?>
                    <li><a href="<?php echo Core\HTML::link('brands/'.$obj->alias); ?>"><?php echo $obj->name; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
    <li><a href="<?php echo Core\HTML::link('news'); ?>">Новости</a>
        <?php if(count($news)): ?>
            <ul>
                <?php foreach($news AS $obj): ?>
                    <li><a href="<?php echo Core\HTML::link('news/'.$obj->alias); ?>"><?php echo $obj->name; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
    <li><a href="<?php echo Core\HTML::link('articles'); ?>">Статьи</a>
        <?php if(count($articles)): ?>
            <ul>
                <?php foreach($articles AS $obj): ?>
                    <li><a href="<?php echo Core\HTML::link('articles/'.$obj->alias); ?>"><?php echo $obj->name; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
    <li><a href="<?php echo Core\HTML::link('sitemap'); ?>">Карта сайта</a></li>
</ul>