<?php
    namespace Modules;

    use Core\QB\DB;
    use Core\Arr;
    use Modules\User\Models\User;
    use Core\Config AS conf;
    use Core\View;
    use Core\System;
    use Modules\Cart\Models\Cart;
    use Core\Log;
    use Core\Email;
    use Core\Message;
    use Core\Text;
    
    class Form extends Base {

        protected $post;

        function before() {
            parent::before();
            if (!$_POST) {
                $this->error('Wrong request!');
            }
            $this->post = $this->getDataFromSerialize( $_POST['data'] ); // Get post by key => value format
        }

        public function selCityAction(){
            $idCity = Arr::get( $this->post, 'idCity' );
            if ($idCity) {
                $_SESSION['idCity'] = $idCity;
            } else {
                unset($_SESSION['idCity']);
            }

            $this->success('');
        }

        // Checkout your order
        public function checkoutAction(){
            // Check incomming data
            $payment = Arr::get( $this->post, 'payment' );
            if( !$payment ) {
                $this->error('Выберите способ оплаты!');
            }
            $delivery = Arr::get( $this->post, 'delivery' );
            if( !$delivery ) {
                $this->error('Выберите способ доставки!');
            }
            $number = Arr::get( $this->post, 'number' );
            if( $delivery == 2 AND !$number ) {
                $this->error('Укажите на какое отделение Новой почты везти товар!');
            }
            $name = Arr::get( $this->post, 'name' );
            if( !$name OR mb_strlen($name, 'UTF-8') < 2 ) {
                $this->error('Укажите ФИО получателя!');
            }
            $phone = trim(Arr::get($this->post, 'phone'));
            if( !$phone OR !preg_match('/^\+38 \(\d{3}\) \d{3}\-\d{2}\-\d{2}$/', $phone, $matches) ) {
                $this->error('Номер телефона введен неверно!');
            }

            // Check for bot
            $ip = System::getRealIP();
            $check = DB::select(array(DB::expr('COUNT(orders.id)'), 'count'))
                        ->from('orders')
                        ->where('ip', '=', $ip)
                        ->where('created_at', '>', time() - 30)
                        ->as_object()->execute()->current();
            // if( is_object($check) AND $check->count ) {
            //     $this->error('Вы только что оформили заказ! Пожалуйста, повторите попытку через несколько секунд');
            // }

            // Check for cart existance
            $count = Cart::factory()->_count_goods;
            if( !$count ) {
                $this->error('Вы ничего не выбрали для покупки!');
            }

            // Create order
            $data = array();
            $data['status'] = 0;
            $data['ip'] = $ip;
            $data['payment'] = $payment;
            $data['delivery'] = $delivery;
            $data['number'] = $delivery == 2 ? $number : '';
            $data['name'] = $name;
            $data['phone'] = $phone;
            $data['created_at'] = time();
            if( User::info() ) {
                $data['user_id'] = User::info()->id;
            }
            $keys = array(); $values = array();
            foreach ($data as $key => $value) {
                $keys[] = $key; $values[] = Text::xssClean($value);
            }
            $order_id = DB::insert('orders', $keys)->values($values)->execute();
            if( !$order_id ) {
                $this->error('К сожалению, создать заказ не удалось. Пожалуйста повторите попытку через несколько секунд');
            }
            $order_id = Arr::get($order_id, 0);

            // Add items to order
            $cart = Cart::factory()->get_list_for_basket();
            foreach( $cart AS $item ) {
                $obj = Arr::get($item, 'obj');
                $count = (int) Arr::get($item, 'count');
                $size_id = (int) Arr::get($item, 'size');
                if( $obj AND $count ) {
                    $data = array();
                    $data['order_id'] = $order_id;
                    $data['catalog_id'] = $obj->id;
                    $data['size_id'] = $size_id;
                    $data['count'] = $count;
                    $data['cost'] = $obj->cost;
                    $keys = array(); $values = array();
                    foreach ($data as $key => $value) {
                        $keys[] = $key; $values[] = $value;
                    }
                    DB::insert('orders_items', $keys)->values($values)->execute();
                }
            }

            // Create links
            $link_user = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/user/orders/id/' . $order_id;
            $link_admin = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/backend/orders/new/id/' . $order_id;

            // Save log
            $qName = 'Новый заказ';
            $url = '/backend/orders/edit/' . $order_id;
            Log::add( $qName, $url, 8 );

            // Get lists of delivery and payment from config file /config/order.php
            $d = conf::get('order.delivery');
            $p = conf::get('order.payment');

            // Send message to admin if need
            $mail = DB::select()->from('mail_templates')->where('id', '=', 11)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{name}}', '{{phone}}', '{{payment}}', '{{delivery}}', '{{admin_link}}', '{{user_link}}', '{{items}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), $ip, date('d.m.Y H:i'),
                    $name, $phone, $p[$payment], $d[$delivery] . ($delivery == 2 ? ', ' . $number : ''), $link_admin, $link_user,
                    View::tpl(array('cart' => $cart), 'Cart/ItemsMail'),
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text );
            }

            // Send message to user if need and logged in
            if( User::info() AND User::info()->email ) {
                $mail = DB::select()->from('mail_templates')->where('id', '=', 12)->where('status', '=', 1)->as_object()->execute()->current();
                if( $mail ) {
                    $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{name}}', '{{phone}}', '{{payment}}', '{{delivery}}', '{{admin_link}}', '{{user_link}}', '{{items}}' );
                    $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), $ip, date('d.m.Y H:i'),
                        $name, $phone, $p[$payment], $d[$delivery] . ($delivery == 2 ? ', ' . $number : ''), $link_admin, $link_user,
                        View::tpl(array('cart' => $cart), 'Cart/ItemsMail'),
                    );
                    $subject = str_replace($from, $to, $mail->subject);
                    $text = str_replace($from, $to, $mail->text);
                    Email::send( $subject, $text, User::info()->email );
                }
            }

            // Clear cart
            Cart::factory()->clear();

            // Set message and reload page
            Message::GetMessage(1, 'Вы успешно оформили заказ! Спасибо за то что вы с нами');
            $this->success( array('redirect' => User::info() ? $link_user : '/cart') );
        }


        // Ask a question about item
        public function questionAction() {
            // Check incomming data
            $id = Arr::get( $this->post, 'id' );
            if( !$id ) {
                $this->error('Такой товар не существует!');
            }
            $item = DB::select('alias', 'name', 'id')->from('catalog')->where('status', '=', 1)->where('id', '=', $id)->as_object()->execute()->current();
            if( !$item ) {
                $this->error('Такой товар не существует!');
            }
            $email = Arr::get( $this->post, 'email' );
            if( !$email OR !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $this->error('Вы неверно ввели E-Mail!');
            }
            $text = trim( strip_tags( Arr::get( $this->post, 'text' ) ) );
            if( !$text OR mb_strlen($text, 'UTF-8') < 5 ) {
                $this->error('Слишком короткий вопрос! Нужно хотя бы 5 символов');
            }
            $name = trim( strip_tags( Arr::get( $this->post, 'name' ) ) );
            if( !$name OR mb_strlen($name, 'UTF-8') < 2 ) {
                $this->error('Слишком короткое имя! Нужно хотя бы 2 символов');
            }

            // Check for bot
            $ip = System::getRealIP();
            $check = DB::select(array(DB::expr('catalog_questions.id'), 'count'))
                        ->from('catalog_questions')
                        ->where('ip', '=', $ip)
                        ->where('catalog_id', '=', $id)
                        ->where('created_at', '>', time() - 60)
                        ->as_object()->execute()->current();
            if( is_object($check) AND $check->count ) {
                $this->error('Вы только что задали вопрос по этому товару! Пожалуйста, повторите попытку через минуту');
            }

            // All ok. Save data
            $keys = array('ip', 'name', 'email', 'text', 'catalog_id', 'created_at');
            $values = array($ip, $name, $email, $text, $item->id, time());
            $lastID = DB::insert('catalog_questions', $keys)->values($values)->execute();
            $lastID = Arr::get($lastID, 0);

            // Create links
            $link = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/catalog/' . $item->alias;
            $link_admin = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/backend/catalog/new/id/' . $item->id;

            // Save log
            $qName = 'Вопрос о товаре';
            $url = '/backend/questions/edit/' . $lastID;
            Log::add( $qName, $url, 5 );

            // Send message to admin if need
            $mail = DB::select()->from('mail_templates')->where('id', '=', 9)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{question}}', '{{name}}', '{{email}}', '{{link}}', '{{admin_link}}', '{{item_name}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), $ip, date('d.m.Y H:i'),
                    $text, $name, $email, $link, $link_admin, $item->name,
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text );
            }

            // Send message to user if need
            $mail = DB::select()->from('mail_templates')->where('id', '=', 10)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{question}}', '{{name}}', '{{email}}', '{{link}}', '{{admin_link}}', '{{item_name}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), $ip, date('d.m.Y H:i'),
                    $text, $name, $email, $link, $link_admin, $item->name,
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text, $email );
            }

            $this->success('Вы успешно задали вопрос! Администратор ответит Вам в ближайшее время');
        }


        // Simple order by phone number
        public function order_simpleAction(){
            // Check incomming data
            $id = Arr::get( $this->post, 'id' );
            if( !$id ) {
                $this->error('Такой товар не существует!');
            }
            $item = DB::select('alias', 'name', 'id')->from('catalog')->where('status', '=', 1)->where('id', '=', $id)->as_object()->execute()->current();
            if( !$item ) {
                $this->error('Такой товар не существует!');
            }
            $phone = trim(Arr::get($this->post, 'phone'));
            if( !$phone OR !preg_match('/^\+38 \(\d{3}\) \d{3}\-\d{2}\-\d{2}$/', $phone, $matches) ) {
                $this->error('Номер телефона введен неверно!');
            }

            // Check for bot
            $ip = System::getRealIP();
            $check = DB::select(array(DB::expr('orders_simple.id'), 'count'))
                        ->from('orders_simple')
                        ->where('ip', '=', $ip)
                        ->where('catalog_id', '=', $id)
                        ->where('created_at', '>', time() - 60)
                        ->as_object()->execute()->current();
            if( is_object($check) AND $check->count ) {
                $this->error('Вы только что заказали этот товар! Пожалуйста, повторите попытку через минуту');
            }

            // All ok. Save data
            $keys = array('ip', 'phone', 'catalog_id', 'user_id', 'created_at');
            $values = array($ip, $phone, $item->id, User::info() ? User::info()->id : 0, time());
            $lastID = DB::insert('orders_simple', $keys)->values($values)->execute();
            $lastID = Arr::get($lastID, 0);

            // Create links
            $link = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/catalog/' . $item->alias;
            $link_admin = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/backend/catalog/new/id/' . $item->id;

            // Save log
            $qName = 'Заказ в один клик';
            $url = '/backend/simple/edit/' . $lastID;
            Log::add( $qName, $url, 7 );

            // Send message to admin if need
            $mail = DB::select()->from('mail_templates')->where('id', '=', 8)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{phone}}', '{{link}}', '{{admin_link}}', '{{item_name}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), $ip, date('d.m.Y H:i'),
                    $phone, $link, $link_admin, $item->name,
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text );
            }

            $this->success('Вы успешно оформили заказ в один клик! Оператор свяжется с Вами в скором времени');
        }


        // Add comment for item
        public function add_commentAction() {
            // Check incomming data
            $id = Arr::get( $this->post, 'id' );
            if( !$id ) {
                $this->error('Такой товар не существует!');
            }
            $item = DB::select('alias', 'name', 'id')->from('catalog')->where('status', '=', 1)->where('id', '=', $id)->as_object()->execute()->current();
            if( !$item ) {
                $this->error('Такой товар не существует!');
            }
            $name = Arr::get( $this->post, 'name' );
            if( !$name OR mb_strlen($name, 'UTF-8') < 2 ) {
                $this->error('Введено некорректное имя!');
            }
            $city = Arr::get( $this->post, 'city' );
            if( !$city OR mb_strlen($city, 'UTF-8') < 2 ) {
                $this->error('Введено некорректное название города!');
            }
            $text = trim( strip_tags( Arr::get( $this->post, 'text' ) ) );
            if( !$text OR mb_strlen($text, 'UTF-8') < 5 ) {
                $this->error('Слишком короткий коментарий! Нужно хотя бы 5 символов');
            }

            // Check for bot
            $ip = System::getRealIP();
            $check = DB::select(array(DB::expr('catalog_comments.id'), 'count'))
                        ->from('catalog_comments')
                        ->where('ip', '=', $ip)
                        ->where('catalog_id', '=', $id)
                        ->where('created_at', '>', time() - 60)
                        ->as_object()->execute()->current();
            if( is_object($check) AND $check->count ) {
                $this->error('Вы только что оставили отзыв об этом товаре! Пожалуйста, повторите попытку через минуту');
            }

            // All ok. Save data
            $keys = array('date', 'ip', 'name', 'city', 'text', 'catalog_id', 'created_at');
            $values = array(time(), $ip, $name, $city, $text, $item->id, time());
            $lastID = DB::insert('catalog_comments', $keys)->values($values)->execute();
            $lastID = Arr::get($lastID, 0);

            // Create links
            $link = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/catalog/' . $item->alias;
            $link_admin = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/backend/catalog/new/id/' . $item->id;

            // Save log
            $qName = 'Отзыв к товару';
            $url = '/backend/comments/edit/' . $lastID;
            Log::add( $qName, $url, 6 );

            // Send message to admin if need
            $mail = DB::select()->from('mail_templates')->where('id', '=', 7)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{name}}', '{{city}}', '{{text}}', '{{link}}', '{{admin_link}}', '{{item_name}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), $ip, date('d.m.Y H:i'),
                    $name, $city, $text, $link, $link_admin, $item->name,
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text );
            }

            $this->success('Вы успешно оставили отзыв о товаре. Он отобразится на сайте после проверки администратором');
        }


        // User authorization
        public function loginAction() {
            $email = Arr::get( $this->post, 'email' );
            $password = Arr::get( $this->post, 'password' );
            $remember = Arr::get( $this->post, 'remember' );
            if (!$password) {
                $this->error('Вы не ввели пароль!');
            }
            // Check user for existance and ban
            $user = User::factory()->get_user_by_email( $email, $password );
            if( !$user ) {
                $this->error('Вы допустили ошибку в логине и/или пароле!');
            }
            if( !$user->status ) {
                $this->error('Пользователь с указанным E-Mail адресом либо заблокирован либо не активирован. Пожалуйста обратитесь к Администратору для решения сложившейся ситуации' );
            }

            // Authorization of the user
            DB::update('users')->set(array('last_login' => time(), 'logins' => (int) $user->logins + 1, 'updated_at' => time()))->where('id', '=', $user->id)->execute();
            User::factory()->auth( $user, $remember );
            Message::GetMessage(1, 'Вы успешно авторизовались на сайте!');
            $this->success( array('redirect' => '/user', 'noclear' => 1) );
        }


        // User wants to edit some information
        public function edit_profileAction() {
            // Check incoming data
            $name = trim(Arr::get($this->post, 'name'));
            if( !$name OR mb_strlen($name, 'UTF-8') < 2 ) {
                $this->error('Введенное имя слишком короткое!');
            }
            $email = Arr::get( $this->post, 'email' );
            if( !$email OR !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $this->error('Вы неверно ввели E-Mail!');
            }
            $check = DB::select(array(DB::expr('COUNT(users.id)'), 'count'))
                        ->from('users')
                        ->where('email', '=', $email)
                        ->where('id', '!=', User::info()->id)
                        ->as_object()->execute()->current();
            if( is_object($check) AND $check->count ) {
                $this->error('Пользователь с указанным E-Mail адресом уже зарегистрирован!');
            }
            $phone = trim(Arr::get($this->post, 'phone'));
            if( !$phone OR !preg_match('/^\+38 \(\d{3}\) \d{3}\-\d{2}\-\d{2}$/', $phone, $matches) ) {
                $this->error('Номер телефона введен неверно!');
            }
            // Save new users data
            DB::update('users')->set(array('name' => $name, 'email' => $email, 'phone' => $phone, 'updated_at' => time()))->where('id', '=', User::info()->id)->execute();
            Message::GetMessage(1, 'Вы успешно изменили свои данные!');
            $this->success( array('redirect' => '/user/profile') );
        }


        // Change password
        public function change_passwordAction(){
            // Check incoming data
            $oldPassword = Arr::get($this->post, 'old_password');
            if( !User::factory()->check_password($oldPassword, User::info()->password) ) {
                $this->error('Старый пароль введен неверно!');
            }
            $password = trim(Arr::get($this->post, 'password'));
            if( mb_strlen($password, 'UTF-8') < conf::get('main.password_min_length') ) {
                $this->error('Пароль не может быть короче '.conf::get('main.password_min_length').' символов!');
            }
            if( User::factory()->check_password($password, User::info()->password) ) {
                $this->error('Нельзя поменять пароль на точно такой же!');
            }
            $confirm = trim(Arr::get($this->post, 'confirm'));
            if( $password != $confirm ) {
                $this->error('Поля "Новый пароль" и "Подтвердите новый пароль" должны совпадать!');
            }

            // Change password for new
            User::factory()->update_password(User::info()->id, $password);

            // Send email to user with new data
            $mail = DB::select()->from('mail_templates')->where('id', '=', 6)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{password}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), System::getRealIP(), date('d.m.Y H:i'),
                    $password
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text, $user->email );
            }

            $this->success( 'На указанный E-Mail адрес высланы новые данные для входа' );
        }


        // User registration
        public function registrationAction() {
            // Check incoming data
            $email = Arr::get( $this->post, 'email' );
            if( !$email OR !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $this->error('Вы неверно ввели E-Mail!');
            }
            $user = DB::select()->from('users')->where('email', '=', $email)->as_object()->execute()->current();
            if( $user ) {
                if ( $user->status ) {
                    $this->error('Пользователь с указанным E-Mail адресом уже зарегистрирован!');
                }
                $this->error('Пользователь с указанным E-Mail адресом уже зарегистрирован, но либо заблокирован либо не подтвердил свой E-Mail адрес. Пожалуйста обратитесь к Администратору для решения сложившейся ситуации');
            }
            $password = trim( Arr::get( $this->post, 'password' ) );
            if ( mb_strlen($password, 'UTF-8') < conf::get('main.password_min_length') ) {
                $this->error('Пароль не может содержать меньше '.conf::get('main.password_min_length').' символов!');
            }
            $agree = Arr::get( $this->post, 'agree' );
            if( !$agree ) {
                $this->error('Вы должны принять условия соглашения для регистрации на нашем сайте!');
            }

            // Create user data
            $data = array(
                'email' => $email,
                'password' => $password,
                'ip' => System::getRealIP(),
            );

            // Create user. Then send an email to user with confirmation link or authorize him to site
            $mail = DB::select()->from('mail_templates')->where('id', '=', 4)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                // Creating of the new user and set his status to zero. He need to confirm his email
                $data['status'] = 0;
                User::factory()->registration($data);
                $user = DB::select()->from('users')->where('email', '=', $email)->as_object()->execute()->current();

                // Save log
                $qName = 'Регистрация пользователя, требующая подтверждения';
                $url = '/backend/users/edit/' . $user->id;
                Log::add( $qName, $url, 1 );

                // Sending letter to email
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{link}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), Arr::get( $data, 'ip' ), date('d.m.Y'),
                    'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/user/confirm/hash/' . $user->hash,
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text, $user->email );

                // Inform user if mail is sended
                $this->success('Вам отправлено письмо подтверждения со ссылкой, кликнув по которой, Вы подтвердите свой адрес и будете автоматически авторизованы на сайте.');
            } else {
                // Creating of the new user and set his status to 1. He must be redirected to his cabinet
                $data['status'] = 1;
                User::factory()->registration($data);
                $user = DB::select()->from('users')->where('email', '=', $email)->as_object()->execute()->current();

                // Save log
                $qName = 'Регистрация пользователя';
                $url = '/backend/users/edit/' . $user->id;
                Log::add( $qName, $url, 1 );

                // Authorization of the user
                User::factory()->auth( $user, 0 );
                Message::GetMessage(1, 'Вы успешно зарегистрировались на сайте! Пожалуйста укажите остальную информацию о себе в личном кабинете для того, что бы мы могли обращаться к Вам по имени');
                $this->success( array('redirect' => '/user') );
            }
        }


        // Forgot password
        public function forgot_passwordAction() {
            // Check incoming data
            $email = Arr::get( $this->post, 'email' );
            if( !$email OR !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $this->error('Вы неверно ввели E-Mail!');
            }
            $user = DB::select('users')->where('email', '=', $email)->as_object()->execute()->current();
            if( !$user ) {
                $this->error('Пользователя с указанным E-Mail адресом не существует!');
            }
            if ( !$user->status ) {
                $this->error('Пользователь с указанным E-Mail адресом либо заблокирован либо не подтвердил E-Mail адрес. Пожалуйста обратитесь к Администратору для решения сложившейся ситуации');
            }

            // Generate new password for user and save it to his account
            $password = User::factory()->generate_random_password();
            User::factory()->update_password($user->id, $password);

            // Send E-Mail to user with instructions how recover password
            $mail = DB::select()->from('mail_templates')->where('id', '=', 5)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{password}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), System::getRealIP(), date('d.m.Y H:i'),
                    $password
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text, $user->email );
            }

            $this->success( 'На указанный E-Mail адрес выслан новый пароль для входа' );
            // $this->success(array('password' => $password));
        }


        // Send callback
        public function callbackAction() {
            // Check incoming data
            $name = trim(Arr::get($this->post, 'name'));
            if( !$name OR mb_strlen($name, 'UTF-8') < 2 ) {
                $this->error('Имя введено неверно!');
            }
            $phone = trim(Arr::get($this->post, 'phone'));
            if( !$phone OR !preg_match('/^\(\d{3}\) \d{3}\-\d{2}\-\d{2}$/', $phone, $matches) ) {
                $this->error('Номер телефона введен неверно!');
            }

            // Check for bot
            $ip = System::getRealIP();
            $check = DB::select(array(DB::expr('COUNT(callback.id)'), 'count'))
                        ->from('callback')
                        ->where('ip', '=', $ip)
                        ->where('created_at', '>', time() - 60)
                        ->as_object()->execute()->current();
            if( is_object($check) AND $check->count ) {
                $this->error('Нельзя так часто просить перезвонить! Пожалуйста, повторите попытку через минуту');
            }

            // Save callback
            $lastID = DB::insert('callback', array('name', 'phone', 'ip', 'status', 'created_at'))->values(array(Text::xssClean($name), $phone, $ip, 0, time()))->execute();
            $lastID = Arr::get($lastID, 0);

            // Save log
            $qName = 'Заказ звонка';
            $url = '/backend/callback/edit/' . $lastID;
            Log::add( $qName, $url, 3 );

            // Send E-Mail to admin
            $mail = DB::select()->from('mail_templates')->where('id', '=', 3)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{ip}}', '{{date}}', '{{name}}', '{{phone}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), $ip, date('d.m.Y H:i'),
                    $name, $phone
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text );
            }

            $this->success( 'Администрация сайта скоро Вам перезвонит!' );
        }


        // Subscribe user for latest news and sales
        public function subscribeAction() {
            // Check incoming data
            $name = trim(Arr::get($this->post, 'name'));
            if( !$name OR mb_strlen($name, 'UTF-8') < 2 ) {
                $this->error('Имя введено неверно!');
            }

            $email = Arr::get( $this->post, 'email' );
            if( !$email OR !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $this->error('Вы неверно ввели E-Mail!');
            }
            $check = DB::select(array(DB::expr('COUNT(subscribers.id)'), 'count'))
                        ->from('subscribers')
                        ->where('email', '=', $email)
                        ->as_object()->execute()->current();
            if( is_object($check) AND $check->count ) {
                $this->error('Вы уже подписаны на нашу рассылку!');
            }
            $hash = sha1( $email . microtime() ); // Generate subscribers hash

            // Save subscriber to the database
            $ip = System::getRealIP();
            $lastID = DB::insert('subscribers', array('name', 'email', 'ip', 'status', 'hash', 'created_at'))->values(array(Text::xssClean($name), $email, $ip, 1, $hash, time()))->execute();
            $lastID = Arr::get($lastID, 0);

            // Save log
            $qName = 'Подписчик';
            $url = '/backend/subscribers/edit/' . $lastID;
            Log::add( $qName, $url, 4 );

            // Send E-Mail to user
            $mail = DB::select()->from('mail_templates')->where('id', '=', 2)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{link}}', '{{email}}', '{{ip}}', '{{date}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), 
                    'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/unsubscribe/hash/' . $hash, $email,
                    $ip, date('d.m.Y H:i'),
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text, $email );
            }

            $this->success( 'Поздравляем! Вы успешно подписались на рассылку акций и новостей с нашего сайта!' );
        }


        // User want to contact with admin and use contact form
        public function contactsAction() {
            // Check incoming data
            $name = Text::xssClean( Arr::get( $this->post, 'name' ) );
            if( !$name ) {
                $this->error('Вы не указали имя!');
            }
            $text = Text::xssClean( Arr::get( $this->post, 'text' ) );
            if( ! $text ) {
                $this->error('Вы не написали текст сообщения!');
            }
            if( !filter_var( Arr::get( $this->post, 'email' ), FILTER_VALIDATE_EMAIL )  ) {
                $this->error('Вы указали неверный E-Mail!');
            }

            // Create data for saving
            $data = array();
            $data['text'] = nl2br( $text );
            $data['ip'] = System::getRealIP();
            $data['name'] = $name;
            $data['email'] = Arr::get($this->post, 'email');
            $data['created_at'] = time();

            // Chec for bot
            $check = DB::select(array(DB::expr('COUNT(contacts.id)'), 'count'))
                        ->from('contacts')
                        ->where('ip', '=', Arr::get($data, 'ip'))
                        ->where('created_at', '>', time() - 60)
                        ->as_object()->execute()->current();
            if( is_object($check) AND $check->count ) {
                $this->error('Нельзя так часто отправлять сообщения! Пожалуйста, повторите попытку через минуту');
            }

            // Save contact message to database
            $keys = array(); $values = array();
            foreach ($data as $key => $value) {
                $keys[] = $key; $values[] = $value;
            }
            $lastID = DB::insert('contacts', $keys)->values($values)->execute();
            $lastID = Arr::get($lastID, 0);

            // Save log
            $qName = 'Сообщение из контактной формы';
            $url = '/backend/contacts/edit/' . $lastID;
            Log::add( $qName, $url, 2 );

            // Send E-Mail to admin
            $mail = DB::select()->from('mail_templates')->where('id', '=', 1)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{name}}', '{{email}}', '{{text}}', '{{ip}}', '{{date}}' );
                $to = array(
                    Arr::get( $_SERVER, 'HTTP_HOST' ), 
                    Arr::get( $data, 'name' ), Arr::get( $data, 'email' ), Arr::get( $data, 'text' ),
                    Arr::get( $data, 'ip' ), date('d.m.Y H:i'),
                );
                
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text, conf::get('mail_director'));
            }

            $this->success( 'Сообщение отправлено!' );
        }

        // booking form
        public function broneAction() {
            // Check incoming data
            $event_name = Text::xssClean( Arr::get( $this->post, 'event_name' ) );
            if( ! $event_name ) {
                $this->error('Вы не указали название события!');
            }
            $name = Text::xssClean( Arr::get( $this->post, 'name' ) );
            if( ! $name ) {
                $this->error('Вы не указали имя!');
            }
            if( !filter_var( Arr::get( $this->post, 'email' ), FILTER_VALIDATE_EMAIL )  ) {
                $this->error('Вы указали неверный E-Mail!');
            }
            $phone = trim(Arr::get($this->post, 'phone'));
            if( !$phone OR !preg_match('/^\(\d{3}\) \d{3}\-\d{2}\-\d{2}$/', $phone, $matches) ) {
                $this->error('Номер телефона введен неверно!');
            }
            $text = Text::xssClean( Arr::get( $this->post, 'text' ) );
            if( ! $text ) {
                $this->error('Вы не написали текст сообщения!');
            }

            // Create data for saving
            $data = array();
            $data['text'] = nl2br($text);
            $data['ip'] = System::getRealIP();
            $data['event_name'] = $event_name;
            $data['name'] = $name;
            $data['phone'] = Arr::get($this->post, 'phone');
            $data['email'] = Arr::get($this->post, 'email');
            $data['created_at'] = time();

            // Chec for bot
            $check = DB::select(array(DB::expr('COUNT(brone.id)'), 'count'))
                        ->from('brone')
                        ->where('ip', '=', Arr::get($data, 'ip'))
                        ->where('created_at', '>', time() - 60)
                        ->as_object()->execute()->current();
            if( is_object($check) AND $check->count ) {
                $this->error('Нельзя так часто отправлять сообщения! Пожалуйста, повторите попытку через минуту');
            }

            // Save contact message to database
            $keys = array(); $values = array();
            foreach ($data as $key => $value) {
                $keys[] = $key; $values[] = $value;
            }
            $lastID = DB::insert('brone', $keys)->values($values)->execute();
            $lastID = Arr::get($lastID, 0);

            // Save log
            $qName = 'Сообщение из формы бронирования билетов';
            $url = '/backend/brone/edit/' . $lastID;
            Log::add( $qName, $url, 2 );

            // Send E-Mail to admin
            $mail = DB::select()->from('mail_templates')->where('id', '=', 13)->where('status', '=', 1)->as_object()->execute()->current();
            if( $mail ) {
                $from = array( '{{site}}', '{{event_name}}', '{{name}}', '{{email}}', '{{phone}}', '{{text}}', '{{ip}}', '{{date}}' );
                $to = array(
                    // conf::get('mail_director'),
                    Arr::get( $_SERVER, 'HTTP_HOST' ), 
                    Arr::get( $data, 'event_name' ), Arr::get( $data, 'name' ), Arr::get( $data, 'email' ), Arr::get($data, 'phone'), Arr::get( $data, 'text' ),
                    Arr::get( $data, 'ip' ), date('d.m.Y H:i'),
                );
                $subject = str_replace($from, $to, $mail->subject);
                $text = str_replace($from, $to, $mail->text);
                Email::send( $subject, $text );
            }

            $this->success( 'Сообщение отправлено!' );
        }

        // Order places
        public function orderAction() {
            // Check incoming data
            $name = Text::xssClean(Arr::get($this->post, 'name'));
            if( ! $name ) {
                $this->error('Вы не указали имя!');
            }
            $email = Text::xssClean(Arr::get($this->post, 'email'));
            if( ! $email OR !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                $this->error('Вы указали неверный e-mail!');
            }
            $phone = Text::xssClean(Arr::get($this->post, 'phone'));
            if( ! $phone OR !preg_match('/\(\d{3}\)\s\d{3}-\d{2}-\d{2}/', $phone, $matches) ) {
                $this->error('Вы указали неверный телефон!');
            }
            $places = Text::xssClean(Arr::get($this->post, 'seats'));
            $places = array_filter(explode(',', $places));

            if(!$places OR !is_array($places)) {
                $this->error('Вы не выбрали места!');
            }
            $message = nl2br(Text::xssClean(Arr::get($this->post, 'message', null)));
            $afishaId = (int) Text::xssClean(Arr::get($this->post, 'id'));

            // Get prices by afisha ID
            $prices = DB::select('id')
                ->from('prices')
                ->where('afisha_id', '=', $afishaId)
                ->find_all();
            if (count($prices) == 0) {
                $this->error('Ошибка создания заказа (выборка цен)');
            }


            $pricesIds = array();
            foreach ($prices as $price) {
                $pricesIds[] = $price->id;
            }

            // Generate seats id from places list
            $seats = DB::select('id')
                ->from('seats')
                ->where('view_key', 'IN', $places)
                ->where('price_id', 'IN', $pricesIds)
                ->and_where_open()
                    ->where('status', '=', 1)
                    ->or_where_open()
                        ->where('status', '=', 2)
                        ->where('reserved_at', '<', time() - (60 * 60 *24 * conf::get('reserved_days')) )
                    ->or_where_close()
                ->and_where_close()

                ->find_all();
            if (count($seats) == 0) {
                $this->error('Ошибка создания заказа (выборка мест)');
            }

            $seatsId = array();
            foreach ($seats as $seat) {
                $seatsId[] = $seat->id;
            }

            $data = array(
                'afisha_id' => $afishaId,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
                'seats_keys' => implode(',', $places),
                'created_at' => time(),
                'first_created_at' => time(),
                'updated_at' => time(),
                'ip' => System::getRealIP()
            );

            $res = \Core\Common::insert('afisha_orders', $data)->execute();
            if (!$res) {
                $this->error('ошибка создания заказа');
            }

            // Update status
            $res2 = DB::update('seats')
                ->set(array('status' => 2, 'reserved_at' => time()))
                ->where('id', 'IN', $seatsId)
                ->execute();

            $afisha = DB::select()->from('afisha')->where('id', '=', $afishaId)->find();

            $data['event_name'] = $afisha->name;
            
            // Send email messages for adimn and user
            Afisha\Models\Afisha::sendOrderMessageAdmin(array('id_order' => $res[0], 'order' => $data, 'order_text' => Arr::get($this->post, 'order')));
            Afisha\Models\Afisha::sendOrderMessageUser(array('id_order' => $res[0], 'order' => $data, 'order_text' => Arr::get($this->post, 'order')));

            // Save log
            $qName = 'Новый заказ';
            $url = '/backend/afisha_orders/edit/' . $res[0];
            Log::add( $qName, $url, 8 );

            $response = array();
            // Redirect to payment system
            if (Arr::get($this->post, 'action') == 'payment') {
                $response['redirect'] = \Core\HTML::link('payment/'.$res[0]);
            }
            else {
                $response['reload'] = true;
            }
            $response['response'] = 'Ваш заказ отправлен';
            return $this->success($response);
        }


        // Generate associative array from serializeArray data
        public function getDataFromSerialize( $data ) {
            $arr = array();
            foreach( $data AS $el ) {
                @$arr[ $el['name'] ] = $el['value'];
            }
            return $arr;
        }


        // Generate Ajax answer
        public function answer( $data ) {
            echo json_encode( $data );
            die;
        }


        // Generate Ajax success answer
        public function success( $data ) {
            if( !is_array( $data ) ) {
                $data = array(
                    'response' => $data,
                );
            }
            $data['success'] = true;
            $this->answer( $data );
        }


        // Generate Ajax answer with error
        public function error( $data ) {
            if( !is_array( $data ) ) {
                $data = array(
                    'response' => $data,
                );
            }
            $data['success'] = false;
            $this->answer( $data );
        }
        
    }