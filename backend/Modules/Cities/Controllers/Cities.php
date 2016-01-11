<?php
    namespace Backend\Modules\Cities\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\Files;
    use Core\HTTP;
    use Core\HTML;
    use Core\View;
    use Core\Common;
    use Core\QB\DB;
    use Core\Pager\Pager;

    class Cities extends \Backend\Modules\Base {

        public $tpl_folder = 'Cities';
        public $tablename = 'cities';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Управление городами';
            $this->_seo['title'] = 'Управление городами';
            $this->setBreadcrumbs('Управление городами', 'backend/'.Route::controller().'/index');
            $this->limit = Config::get('limit_backend');
        }

        function indexAction () {
            $status = NULL;
            if ( isset($_GET['status']) ) { $status = Arr::get($_GET, 'status', 1); }

            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename);
            if( $status !== NULL ) { $count->where( 'status', '=', $status ); }
            $count = $count->count_all();
            // $result = DB::select()->from($this->tablename);
            // $result = $result->order_by('created_at', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $result = DB::select()->from($this->tablename);
            if( $status !== NULL ) { $result->where( 'status', '=', $status ); }

            $result = $result->order_by('sort', 'ASC')->find_all();
            $arr    = array();
            foreach($result AS $obj) {
                $arr[$obj->parent_id][] = $obj;
            }
            $pager = Pager::factory( $page, $count, $this->limit )->create();

            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1, 'delete' => 1 ) );
            $this->_content = View::tpl(
                array(
                    'result' => $arr,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                    'pager' => $pager,
                    'pageName' => 'Управление городами',
                ), $this->tpl_folder.'/Index');
        }

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/edit/'.Arr::get($_POST, 'id'));
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

        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::insert($this->tablename, $post)->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно добавили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/add');
                } else {
                    Message::GetMessage(0, 'Не удалось добавить данные!');
                }

                $result = Arr::to_object($post);
            } else {
                $result = array();
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            $this->_seo['h1'] = 'Добавление';
            $this->_seo['title'] = 'Добавление';
            $this->setBreadcrumbs('Добавление', 'backend/'.Route::controller().'/add');

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
            Files::deleteImage($this->image, $page->image);
            DB::delete($this->tablename)->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/index');
        }
    }