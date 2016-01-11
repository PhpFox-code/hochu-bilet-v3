<div class="inform_block">
    <div class="title">отзывы</div>
    <?php if( count($result) ): ?>
        <div class="otziv_slider">
            <ul>
                <?php foreach( $result as $obj ): ?>
                    <li>
                        <div class="name"><?php echo $obj->name; ?>, <span><?php echo $obj->city; ?></span></div>
                        <p><?php echo nl2br($obj->text); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="prev4"><div class="arrow"></div></div>
            <div class="next4"><div class="arrow"></div></div>
        </div>
    <?php else: ?>
        <p style="font-size: 16px; line-height: 20px; margin-bottom: 15px;">Ваш отзыв может быть первым!</p>
    <?php endif ?>

    <div class="leave_otziv_block">
        <div form="true" class="wForm" data-ajax="add_comment">
            <div class="title">оставь свой отзыв</div>
            <div class="wFormRow">
                <input type="text" name="name" placeholder="Имя" data-rule-bykvu="true" data-rule-minlength="2" required="">
                <label>Имя</label>
            </div>
            <div class="wFormRow">
                <input type="text" name="city" placeholder="Город" data-rule-minlength="2" required="">
                <label>Город</label>
            </div>
            <div class="wFormRow">
                <textarea name="text" placeholder="Ваш отзыв" required=""></textarea>
                <label>Ваш отзыв</label>
            </div>
            <input type="hidden" name="id" value="<?php echo Core\Route::param('id'); ?>" />
            <div class="tal">
                <button class="wSubmit enterReg_btn">отозваться</button>
            </div>
        </div>
    </div>
</div>