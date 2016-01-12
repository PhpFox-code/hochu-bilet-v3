<div style="display: none;">
    <!-- Order callback -->
    <div id="orderCall"  class="orderBlock">
        <div data-form="true" class="wForm contact_form" data-ajax="callback">
            <div class="h3T tac">Заказать звонок</div>
            <ul>
                <li> <input type="text" name="name" data-rule-word="true" data-msg-word="Вводите только буквы" placeholder="Имя" data-rule-minlength="2" data-msg-minlength="Введите больше двух символов" data-msg-required="Введите имя" required>
                    <div class="inpInfo">Имя</div>
                </li>
                
                <li> <input type="tel" name="phone" class="tel" data-rule-validTrue="true" data-msg-validTrue="Введите корректный телефон" placeholder="Телефон (xxx)xxx-xx-xx" data-msg-required="Заполните это поле" required>
                    <div class="inpInfo">Телефон</div>
                </li>
                
                <li> <button class="wSubmit">Отправить</button> </li>
            </ul>
        </div>
    </div>

    <!-- Order afisha places -->
    <div id="orderDemo2" class="mfiModal">
        <div data-form="true" class="wForm contact_form" data-ajax="order">
            <div class="mfiModal_head"><h3>Форма заказа билетов</h3></div>
            <div class="selected">
                <p class="selected_kol">Выбрано мест: <span>0</span></p>
                <div class="selected_row"></div>
                <p class="selected_sum">Сумма: <span>0</span> грн</p>
            </div>
            <input type="hidden" name="seats" class="seat_id" style="display: none !important;" />
            <ul>
                <li>
                    <input type="text" name="name" data-rule-word="true" data-msg-word="Вводите только буквы" placeholder="Имя" data-rule-minlength="2" data-msg-minlength="Введите больше двух символов" data-msg-required="Введите имя" required>
                    <div class="inpInfo">Имя</div>
                </li>
                <li>
                    <input type="email" name="email" data-msg-email="Введите корректный Email" data-msg-required="Введите Email" placeholder="Email:name@something.com" required />
                    <div class="inpInfo">Email</div>
                </li>
                <li>
                    <input type="tel" name="phone" class="tel" data-rule-validTrue="true" data-msg-validTrue="Введите корректный телефон" placeholder="Телефон (xxx)xxx-xx-xx" data-msg-required="Заполните это поле"  required>
                    <div class="inpInfo">Телефон</div>
                </li>
                <li>
                    <textarea name="message" cols="40" rows="6" placeholder="Сообщение" data-rule-minlength="5" data-msg-minlength="Введите не менее 5 символов"></textarea>
                </li>
                <li>
                    <button class="wSubmit" type="submit" name="action" value="brone">Забронировать</button>
                    <button class="wSubmit" type="submit" name="action" value="payment">Оплатить</button>
                </li>
            </ul>
            <input type="hidden" name="id" value="" id="afisha_id">
        </div>
        <!-- wForm -->
    </div>
</div>
