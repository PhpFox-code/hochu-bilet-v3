<div class="textPage wTxt">
    <?php echo $text ?>
        
    <div data-form="true" class="wForm contact_form" data-ajax="subscribe">
        <ul>
            <li> <h3>Форма для подписи на рассылку</h3> </li>
            
            <li> <input type="text" name="name" data-rule-word="true" data-msg-word="Вводите только буквы" placeholder="Имя" data-rule-minlength="2" data-msg-minlength="Введите больше двух символов" data-msg-required="Введите имя" required>
                <div class="inpInfo">Имя</div>
            </li>
            
            <li> <input type="email" name="email" data-msg-email="Введите корректный Email" data-msg-required="Введите Email" placeholder="Email:name@something.com" required />
                <div class="inpInfo">Email</div>
            </li>
            
            <li> <button class="wSubmit" type="submit">Подписаться</button> </li>
        </ul>
    </div>
    <!-- wForm -->
    <div class="clear"></div>
</div>
<!-- .textPage -->