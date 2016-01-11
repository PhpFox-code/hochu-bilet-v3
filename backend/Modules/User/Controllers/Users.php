<?php
    namespace Backend\Modules\User\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\Image;
    use Core\HTTP;
    use Core\View;
    use Core\QB\DB;
    use Core\Pager\Pager;
    use Modules\User\Models\User;
    use Core\Common;

    class Users extends \Backend\Modules\Base {

        public $tpl_folder = 'Users';
        public $tablename  = 'users';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Пользователи';
            $this->_seo['title'] = 'Пользователи';
            $this->setBreadcrumbs('Пользователи', 'backend/'.Route::controller().'/index');
            $this->limit = Config::get('limit_backend');
        }


        function indexAction () {
            $date_s = NULL; $date_po = NULL; $status = NULL;
            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }
            if ( isset($_GET['status']) ) { $status = Arr::get($_GET, 'status', 1); }

            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename);
            if( $date_s ) { $count->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $count->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            if( $status !== NULL ) { $count->where( 'status', '=', $status ); }
            $count = $count->count_all();

            $result = DB::select()->from($this->tablename);
            if( $status !== NULL ) { $result->where( 'status', '=', $status ); }
            if( $date_s ) { $result->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $result->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            $result = $result->order_by('id', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $pager = Pager::factory( $page, $count, $this->limit )->create();

            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'delete' => 1 ) );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                    'pager' => $pager,
                    'pageName' => 'Пользователи',
                ), $this->tpl_folder.'/Index');
        }


        function editAction () {
            $result = DB::select()->from($this->tablename)->where('id', '=', (int) Route::param('id'))->where('role_id', '=', 1)->find();
            if(!$result) {
                return Config::error();
            }
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $password = trim( Arr::get($_POST, 'password') );
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Поле "Имя" не может быть пустым!');
                } else if( !trim(Arr::get($post, 'email')) OR !filter_var(Arr::get($post, 'email'), FILTER_VALIDATE_EMAIL) ) {
                    Message::GetMessage(0, 'Поле "E-Mail" введено некорректно!');
                } else if( !trim(Arr::get($post, 'phone')) OR !preg_match('/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', Arr::get($post, 'phone'), $matches) ) {
                    Message::GetMessage(0, 'Поле "Номер телефона" введено некорректно!');
                } else if(DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('users')->where('email', '=', Arr::get($post, 'email'))->where('id', '!=', Arr::get($_POST, 'id'))->count_all()) {
                    Message::GetMessage(0, 'Указанный E-Mail уже занят!');
                } else if($password AND mb_strlen($password, 'UTF-8') < Config::get('main.password_min_length')) {
                    Message::GetMessage(0, 'Пароль должен быть не короче '.Config::get('main.password_min_length').' символов!');
                } else {
                    $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                    if($res) {
                        if( $password ) {
                            User::factory()->update_password(Arr::get($_POST, 'id'), $password);
                        }
                        Message::GetMessage(1, 'Вы успешно изменили данные!');
                        HTTP::redirect('backend/'.Route::controller().'/index');
                    } else {
                        Message::GetMessage(0, 'Не удалось изменить данные!');
                    }
                }
                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );

            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.Route::param('id'));

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'happyCount' => DB::select(array(DB::expr('COUNT(orders.id)'), 'count'))->from('orders_items')
                                        ->join('orders', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')->on('orders.status', '=', DB::expr('1'))
                                        ->where('orders.user_id', '=', $result->id)
                                        ->count_all(),
                    'countOrders' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('orders')->where('user_id', '=', $result->id)->count_all(),
                    'happyMoney' => DB::select(array(DB::expr('SUM(orders_items.count * orders_items.cost)'), 'amount'))
                                        ->from('orders_items')
                                        ->join('orders', 'LEFT')->on('orders.id', '=', 'orders_items.order_id')->on('orders.status', '=', DB::expr(1))
                                        ->where('orders.user_id', '=', $result->id)
                                        ->group_by('orders_items.catalog_id')->find()->amount,
                ), $this->tpl_folder.'/Form');
        
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

    }