<div class="question_block">
    <div form="true" class="wForm" data-ajax="question">
        <div class="title">задать вопрос по товару</div>
        <div class="wFormRow">
            <input type="text" name="name" data-rule-bykvu="true" placeholder="Имя" data-rule-minlength="2" data-rule-required="true">
            <label>Имя</label>
        </div>
        <div class="wFormRow">
            <input type="email" name="email" data-rule-email="true" placeholder="E-mail" data-rule-minlength="2" data-rule-required="true">
            <label>E-mail</label>
        </div>
        <div class="wFormRow">
            <textarea name="text" placeholder="Ваш вопрос" data-rule-required="true"></textarea>
            <label>Ваш вопрос</label>
        </div>
        <input type="hidden" name="id" value="<?php echo Core\Route::param('id'); ?>" />
        <div class="tal">
            <button class="wSubmit enterReg_btn">спросить</button>
        </div>
    </div>
</div>