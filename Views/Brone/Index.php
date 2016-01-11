<div class="textPage wTxt">
    <?php echo $text ?>
        
    <div data-form="true" class="wForm contact_form" data-ajax="brone">
        <ul>
            <li> <h3>Форма бронирование билетов</h3> </li>
            
            <li> <input type="text" name="event_name" value="<?php echo isset($_GET['name']) ? urldecode($_GET['name']) : '' ?>" placeholder="Название события" data-msg-required="Введите название события" required />
                <div class="inpInfo">Название события</div>
            </li>
            
            <li> <input type="text" name="name" data-rule-word="true" data-msg-word="Вводите только буквы" placeholder="Имя" data-rule-minlength="2" data-msg-minlength="Введите больше двух символов" data-msg-required="Введите имя" required>
                <div class="inpInfo">Имя</div>
            </li>
            
            <li> <input type="email" name="email" data-msg-email="Введите корректный Email" data-msg-required="Введите Email" placeholder="Email:name@something.com" required />
                <div class="inpInfo">Email</div>
            </li>
            
            <li> <input type="tel" name="phone" class="tel" data-rule-validTrue="true" data-msg-validTrue="Введите корректный телефон" placeholder="Телефон (xxx)xxx-xx-xx" data-msg-required="Заполните это поле"  required>
                <div class="inpInfo">Телефон</div>
            </li>
            
            <li> <textarea name="text" cols="40" rows="6" placeholder="Сообщение" data-rule-minlength="5" data-msg-minlength="Введите не менее 5 символов"></textarea> </li>
            
            <li> <button class="wSubmit" type="submit">Бронировать</button> </li>
        </ul>
    </div>
    <!-- wForm -->
    <div class="clear"></div>
</div>
<!-- .textPage -->