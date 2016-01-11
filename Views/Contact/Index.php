<div class="contact_block clearFix">
    <div class="fll">
        <div form="true" class="regBlock wForm" data-ajax="contacts" style="float: left; width: 100%;">
            <div class="wBasketHead"><div class="wBasketTTL">Отправить сообщение</div></div>                            
            <div class="wFormRow">
                <input type="text" name="name" placeholder="ФИО" data-rule-minlength="2" required="">
                <label>Имя</label>
            </div>
            <div class="wFormRow">
                <input type="email" name="email" placeholder="E-mail" required="">
                <label>E-mail</label>
            </div>
            <div class="wFormRow">
                <textarea placeholder="Сообщение" name="text" required=""></textarea>
                <label>Сообщение</label>
            </div>
            <div class="butt">
                <button class="wSubmit enterReg_btn" id="contactForm">отправить сообщение</button>
            </div>
        </div>
    </div>
    <div class="flr">
        <?php echo $text; ?>
    </div>
</div>