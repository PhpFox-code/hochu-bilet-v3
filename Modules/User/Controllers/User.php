<?php
    namespace Modules\User\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\View;
    use Core\Config;
    use Modules\User\Models\User AS U;
    use Core\HTTP;
    use Core\Message;
    
    class User extends \Modules\Base {

        public function before() {
            parent::before();
            $this->setBreadcrumbs( 'Личный кабинет', 'user' );
            $this->_template = 'Cabinet';
            $this->_seo['h1'] = 'Личный кабинет';
            $this->_seo['title'] = 'Личный кабинет';
            $this->_seo['keywords'] = 'Личный кабинет';
            $this->_seo['description'] = 'Личный кабинет';
        }


        public function indexAction() {
            if( !U::info() ) { return Config::error(); }
            $this->_content = View::tpl( array( 'user' => U::info() ), 'User/Index' );
        }


        public function logoutAction() {
            if( !U::info() ) { return Config::error(); }
            U::factory()->logout();
            Message::GetMessage(1, 'Возвращайтесь еще!');
            HTTP::redirect('/');
        }


        public function confirmAction() {
            if( U::info() ) { return Config::error(); }
            if( !Route::param('hash') ) { return Config::error(); }

            $user = U::factory()->get_user_by_hash( Route::param('hash') );
            if( !$user ) { return Config::error(); }
            if( $user->status ) {
                Message::GetMessage(0, 'Вы уже подтвердили свой E-Mail!');
                HTTP::redirect('/');
            }

            DB::update('users')->set(array('status' => 1, 'updated_at' => time()))->where('id', '=', $user->id)->execute();

            U::factory()->auth( $user, 0 );
            Message::GetMessage(1, 'Вы успешно зарегистрировались на сайте! Пожалуйста укажите остальную информацию о себе в личном кабинете для того, что бы мы могли обращаться к Вам по имени');
            HTTP::redirect('/user');
        }


        public function profileAction() {
            if( !U::info() ) { return Config::error(); }
            $this->addMeta('Редактирование личных данных');
            $this->_content = View::tpl( array( 'user' => U::info() ), 'User/Profile' );
        }


        public function ordersAction() {
            if( !U::info() ) { return Config::error(); }
            $this->addMeta('Мои заказы');
            $orders = DB::select('orders.*', array(DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'))
                        ->from('orders')
                        ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')
                        ->where('orders.user_id', '=', U::info()->id)
                        ->group_by('orders.id')
                        ->order_by('orders.created_at', 'DESC')
                        ->as_object()->execute();
            $this->_content = View::tpl( array( 'orders' => $orders, 'statuses' => Config::get('order.statuses') ), 'User/Orders' );
        }


        public function orderAction() {
            if( !U::info() ) { return Config::error(); }
            $this->addMeta('Заказ №' . Route::param('id'), true);
            $result = DB::select('orders.*', array(DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'), array(DB::expr('SUM(orders_items.count)'), 'count'))
                        ->from('orders')
                        ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')
                        ->where('orders.id', '=', Route::param('id'))
                        ->as_object()->execute()->current();
            $cart = DB::select('catalog.alias', 'catalog.id', 'catalog.name', 'catalog_images.image', 'orders_items.count', array('sizes.name', 'size_name'), array('orders_items.cost', 'price'), 'orders_items.size_id')
                        ->from('orders_items')
                        ->join('catalog', 'LEFT')->on('orders_items.catalog_id', '=', 'catalog.id')
                        ->join('catalog_images', 'LEFT')->on('catalog_images.main', '=', DB::expr('1'))->on('catalog_images.catalog_id', '=', 'catalog.id')
                        ->join('sizes', 'LEFT')->on('orders_items.size_id', '=', 'sizes.id')
                        ->where('orders_items.order_id', '=', Route::param('id'))
                        ->as_object()->execute();
            $this->_content = View::tpl( array( 
                        'obj' => $result,
                        'cart' => $cart,
                        'payment' => Config::get('order.payment'),
                        'delivery' => Config::get('order.delivery'),
                        'statuses' => Config::get('order.statuses'),
            ), 'User/Order' );
        }


        public function printAction() {
            $this->_template = 'Print';
            if( !U::info() ) { return Config::error(); }
            $result = DB::select('orders.*', array(DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'), array(DB::expr('SUM(orders_items.count)'), 'count'))
                        ->from('orders')
                        ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')
                        ->where('orders.id', '=', Route::param('id'))
                        ->as_object()->execute()->current();
            $cart = DB::select('catalog.alias', 'catalog.id', 'catalog.name', 'catalog_images.image', 'orders_items.count', array('sizes.name', 'size_name'), array('orders_items.cost', 'price'), 'orders_items.size_id')
                        ->from('orders_items')
                        ->join('catalog', 'LEFT')->on('orders_items.catalog_id', '=', 'catalog.id')
                        ->join('catalog_images', 'LEFT')->on('catalog_images.main', '=', DB::expr('1'))->on('catalog_images.catalog_id', '=', 'catalog.id')
                        ->join('sizes', 'LEFT')->on('orders_items.size_id', '=', 'sizes.id')
                        ->where('orders_items.order_id', '=', Route::param('id'))
                        ->as_object()->execute();
            $this->_content = View::tpl( array(
                'order' => $result,
                'list' => $cart,
                'payment' => Config::get('order.payment'),
                'delivery' => Config::get('order.delivery'),
                'statuses' => Config::get('order.statuses'),
            ), 'User/Print' );
        }


        public function change_passwordAction() {
            if( !U::info() ) { return Config::error(); }
            $this->addMeta('Изменить пароль');
            $this->_content = View::tpl( array(), 'User/ChangePassword' );
        }


        public function addMeta( $name, $order = false ) {
            Config::set( 'h1', $name );
            Config::set( 'title', $name );
            Config::set( 'keywords', $name );
            Config::set( 'description', $name );
            if( $order ) {
                $this->setBreadcrumbs( 'Личный кабинет', 'user/orders' );
            }
            $this->setBreadcrumbs( $name );
        }
        
    }