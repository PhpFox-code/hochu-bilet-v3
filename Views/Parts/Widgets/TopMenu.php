<div class="wSize">
    <nav class="menu">
        <ul>
            <?php foreach ($menu as $key => $item): ?>
                <?php if ($item->id == 3): ?>
                    <li class="buytickets">
                <?php elseif ($item->id == 5): ?>
                    <li class="distribution">
                <?php else: ?>
                    <li>
                <?php endif; ?>
                    <a href="<?php echo Core\HTML::link($item->url) ?>" <?php if ( stripos($_SERVER['REQUEST_URI'], '/'.$item->url )  !== false ) echo 'class="active"' ?>><?php echo $item->name ?></a>
                    
                    <?php if ($item->id == 6): ?>
                        <?php $pages = Core\QB\DB::select()->from('content')->where('status', '=', 1)->where('parent_id', '=', 2)->order_by('sort')->as_object()->execute() ?>
                        <?php if (count($pages)): ?>
                            <ul>
                                <?php foreach ($pages as $key => $page): ?>
                                    <li><a href="<?php echo Core\HTML::link($page->alias) ?>"><?php echo $page->name ?></a></li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    </nav>
    <!-- .menu -->
    <div class="clear"></div>
</div>
<!-- .wSize -->