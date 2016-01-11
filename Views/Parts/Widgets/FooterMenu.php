<div class="footerMenu">
    <?php if (count($menu[0])): ?>
        <ul class="colum">
            <?php foreach ($menu[0] as $key => $item): ?>
                <li><a href="<?php echo Core\HTML::link($item['url']) ?>"><?php echo $item['name'] ?></a></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
    <?php if (count($menu[1])): ?>
        <ul class="colum2">
            <?php foreach ($menu[1] as $key => $item): ?>
                <li><a href="<?php echo Core\HTML::link($item['url']) ?>"><?php echo $item['name'] ?></a></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>