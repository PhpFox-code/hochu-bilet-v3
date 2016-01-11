<?php
    namespace Backend\Modules\Afisha\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\Support;
    use Core\Common;
    use Core\QB\DB;
    use Core\Pager\Pager;

    class Afisha_orders extends \Backend\Modules\Base {

        public $tpl_folder = 'Afisha_orders';
        public $tablename  = 'afisha_orders';
        public $statuses;
        public $delivery;
        public $payment;
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Размеры';
            $this->_seo['title'] = 'Размеры';
            $this->setBreadcrumbs('Размеры', 'backend/'.Route::controller().'/index');
            $this->limit = Config::get( 'limit_backend' );
            $this->delivery = Config::get('order.delivery');
            $this->payment = Config::get('order.payment');
            $this->statuses = Config::get('order.statuses');
        }


        function indexAction () {
            $date_s = NULL; $date_po = NULL; $status = NULL;

            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }
            if ( isset( $this->statuses[ Arr::get($_GET, 'status') ] ) ) { $status = Arr::get($_GET, 'status', 1); }

            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename);
            if( $date_s !== NULL ) { $count->where( 'created_at', '>=', $date_s ); }
            if( $date_po !== NULL ) { $count->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            if( $status !== NULL ) { $count->where( 'status', '=', $status ); }
            $count   = $count->count_all();

            $result = DB::select('orders.*', array(DB::expr('SUM(orders_items.count)'), 'count'), array(DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'))
                        ->from('orders')
                        ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', 'orders.id');
            if( $date_s ) { $result->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $result->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            if( $status !== NULL ) { $result->where( 'status', '=', $status ); }
            $result = $result->group_by('orders.id')->order_by('created_at', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $pager = Pager::factory( $page, $count, $this->limit )->create();

            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1 ) );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'pager' => $pager,
                    'status' => $status,
                    'date_s' => $date_s,
                    'date_po' => $date_po,
                    'statuses' => $this->statuses,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                ),'Orders/Index');
        }

        function editAction() {
            $result = DB::select('orders.*', array(DB::expr('SUM(orders_items.count)'), 'count'), array(DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'))
                ->from('orders')
                ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')
                ->where('orders.id', '=', Route::param('id'))->find();
            $cart = DB::select('catalog.*', 'catalog_images.image', 'orders_items.count', array('sizes.name', 'size_name'), array('orders_items.cost', 'price'), 'orders_items.size_id')
                ->from('orders_items')
                ->join('catalog', 'LEFT')->on('orders_items.catalog_id', '=', 'catalog.id')
                ->join('catalog_images', 'LEFT')->on('catalog_images.main', '=', DB::expr(1))->on('catalog_images.catalog_id', '=', 'catalog.id')
                ->join('sizes', 'LEFT')->on('orders_items.size_id', '=', 'sizes.id')
                ->where('orders_items.order_id', '=', Route::param('id'))->find_all();
            $user = DB::select('users.*', array(DB::expr('COUNT(DISTINCT orders.id)'), 'orders'), array(DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'), array(DB::expr('SUM(orders_items.count)'), 'count_items'))
                ->from('users')
                ->join('orders', 'LEFT')->on('orders.status', '=', DB::expr(1))->on('orders.user_id', '=', 'users.id')
                ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')
                ->where('users.id', '=', $result->user_id)->find();

            $this->_seo['h1'] = 'Заказ №' . Route::param('id');
            $this->_seo['title'] = 'Заказ №' . Route::param('id');
            $this->setBreadcrumbs('Заказ №' . Route::param('id'), 'backend/'.Route::controller().'/edit/'.(int) Route::param('id'));

            $this->_content = View::tpl(
                array(
                    'user' => $user,
                    'obj' => $result,
                    'cart' => $cart,
                    'statuses' => $this->statuses,
                    'payment' => $this->payment,
                    'delivery' => $this->delivery,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Inner');
        }

        function addAction(){
            $result = array();
            if( $_POST ) {
                $post = $_POST;
                if( !Arr::get($post, 'name') ) {
                    Message::GetMessage( 0, 'Имя не может быть пустым!' );
                } else if( !Arr::get($post, 'phone') OR !preg_match('/^\+38 \(\d{3}\) \d{3}\-\d{2}\-\d{2}$/', Arr::get($post, 'phone'), $matches) ) {
                    Message::GetMessage( 0, 'Укажите верный номер телефона! Формат: +38 (ХХХ) ХХХ-ХХ-ХХ' );
                } else if( Arr::get($post, 'delivery') == 2 AND !Arr::get($post, 'number') ) {
                    Message::GetMessage( 0, 'Номер отделения Новой почты не может быть пустым!' );
                } else {
                    $data = array(
                        'payment' => Arr::get($post, 'payment'),
                        'delivery' => Arr::get($post, 'delivery'),
                        'number' => Arr::get($post, 'number'),
                        'name' => Arr::get($post, 'name'),
                        'phone' => Arr::get($post, 'phone'),
                    );
                    $res = Common::insert($this->tablename, $data)->execute();
                    if ($res) {
                        HTTP::redirect('/backend/orders/edit/'.$res[0]);
                    } else {
                        HTTP::redirect('/backend/orders/add');
                    }                    
                }
                $result = Arr::to_object($post);
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            $this->_seo['h1'] = 'Добавление';
            $this->_seo['title'] = 'Добавление';
            $this->setBreadcrumbs('Добавление', 'backend/'.Route::controller().'/add');
            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'statuses' => $this->statuses,
                    'payment' => $this->payment,
                    'delivery' => $this->delivery,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Add');
        }

        function addPositionAction(){
            $result = array();
            if( $_POST ) {
                $post = $_POST;
                if( !Route::param('id') ) {
                    Message::GetMessage( 0, 'Нельзя добавить товар несуществующему заказу!' );
                } else if( !Arr::get($post, 'catalog_id') ) {
                    Message::GetMessage( 0, 'Нужно выбрать товар для добавления!' );
                } else if( !Arr::get($post, 'count') ) {
                    Message::GetMessage( 0, 'Укажите количество товара больше 0!' );
                } else {
                    $item = DB::select('cost')->from('catalog')->where('id', '=', Arr::get($post, 'catalog_id'))->find();
                    if(!$item) {
                        Message::GetMessage( 0, 'Нужно выбрать существующий товар для добавления!' );
                    } else {
                        $data = array(
                            'order_id' => (int) Route::param('id'),
                            'catalog_id' => Arr::get($post, 'catalog_id'),
                            'size_id' => Arr::get($post, 'size_id'),
                            'count' => Arr::get($post, 'count'),
                            'cost' => (int) $item->cost,
                        );
                        $res = Common::insert($this->tablename, $post)->execute();
                        Message::GetMessage( 1, 'Позиция добавлена!' );
                        HTTP::redirect('/backend/orders/add_position/'.Route::param('id'));
                    }
                }
                $result = Arr::to_object($post);
            }

            $back_link = '/backend/'.Route::controller().'/edit/'.(int) Route::param('id');
            $this->_toolbar = Widgets::get( 'Toolbar/Edit', array('list_link' => $back_link) );
            $this->_seo['h1'] = 'Добавление позиции в заказ №' . Route::param('id');
            $this->_seo['title'] = 'Добавление позиции в заказ №' . Route::param('id');
            $this->setBreadcrumbs('Заказ №' . (int) Route::param('id'), $back_link);
            $this->setBreadcrumbs('Добавление позиции в заказ №' . Route::param('id'), 'backend/'.Route::controller().'/add_position/'.(int) Route::param('id'));

            $sizes = DB::select('sizes.*')->from('sizes')
                        ->join('catalog_tree_sizes')->on('catalog_tree_sizes.size_id', '=', 'sizes.id')
                        ->where('catalog_tree_sizes.catalog_tree_id', '=', $result->parent_id)
                        ->order_by('sizes.name')
                        ->find_all();
            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'statuses' => $this->statuses,
                    'payment' => $this->payment,
                    'delivery' => $this->delivery,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'tree' => Support::getSelectOptions('Catalog/Select', 'catalog_tree', $result->parent_id),
                    'sizes' => $sizes,
                ), $this->tpl_folder.'/AddPosition');
        }

        function deleteAction() {
            $id = (int) Route::param('id');
            if(!$id) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/'.Route::controller().'/index');
            }
            $page = DB::select()->from($this->tablename)->where('id', '=', $id)->find();
            if(!$page) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/'.Route::controller().'/index');
            }

            DB::delete($this->tablename)->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/index');
        }


        function printAction() {
            $result = DB::select('orders.*', array(DB::expr('SUM(orders_items.count)'), 'count'), array(DB::expr('SUM(orders_items.cost * orders_items.count)'), 'amount'))
                ->from('orders')
                ->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')
                ->where('orders.id', '=', Route::param('id'))->find();
            $cart = DB::select('catalog.*', 'catalog_images.image', 'orders_items.count', array('sizes.name', 'size_name'), array('orders_items.cost', 'price'), 'orders_items.size_id')
                ->from('orders_items')
                ->join('catalog', 'LEFT')->on('orders_items.catalog_id', '=', 'catalog.id')
                ->join('catalog_images', 'LEFT')->on('catalog_images.main', '=', DB::expr(1))->on('catalog_images.catalog_id', '=', 'catalog.id')
                ->join('sizes', 'LEFT')->on('orders_items.size_id', '=', 'sizes.id')
                ->where('orders_items.order_id', '=', Route::param('id'))->find_all();
            echo View::tpl( array(
                'order' => $result,
                'list' => $cart,
                'payment' => Config::get('order.payment'),
                'delivery' => Config::get('order.delivery'),
                'statuses' => Config::get('order.statuses'),
            ), $this->tpl_folder.'/Print' );
            die;
        }

    }