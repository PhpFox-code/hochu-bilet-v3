<?php
    namespace Backend\Modules\Contacts\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\Common;
    use Core\QB\DB;
    use Core\Pager\Pager;

    class Callback extends \Backend\Modules\Base {

        public $tpl_folder = 'Callback';
        public $tablename  = 'callback';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Сообщения из формы заказа звонка';
            $this->_seo['title'] = 'Сообщения из формы заказа звонка';
            $this->setBreadcrumbs('Сообщения из формы заказа звонка', 'backend/'.Route::controller().'/index');
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
            if( $date_s ) { $result->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $result->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            if( $status !== NULL ) { $result->where( 'status', '=', $status ); }
            $result = $result->order_by('created_at', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $pager = Pager::factory( $page, $count, $this->limit )->create();

            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'delete' => 1 ) );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                    'pager' => $pager,
                    'pageName' => 'Сообщения из формы заказа звонка',
                ), $this->tpl_folder.'/Index');
        }

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/'.Route::action().'/'.Route::param('id'));
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', (int) Route::param('id'))->find();
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );

            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.(int) Route::param('id'));

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
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