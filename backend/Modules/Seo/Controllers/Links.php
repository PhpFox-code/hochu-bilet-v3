<?php
    namespace Backend\Modules\Seo\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\QB\DB;
    use Core\Pager\Pager;
    use Core\Arr;
    use Core\Common;

    class Links extends \Backend\Modules\Base {

        public $tpl_folder = 'SeoLinks';
        public $tablename  = 'seo_links';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Теги для ссылок';
            $this->_seo['title'] = 'Теги для ссылок';
            $this->setBreadcrumbs('Теги для ссылок', 'backend/'.Route::controller().'/index');
            $this->limit = Config::get('limit_backend');
        }
        
        function indexAction() {
            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all();
            $result = DB::select()->from($this->tablename)->order_by('id', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $pager = Pager::factory( $page, $count, $this->limit )->create();
            $this->_filter = Widgets::get( 'Filter/Pages' );
            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'addLink' => '/backend/seo/'.Route::controller().'/add', 'delete' => 1 ) );
            $this->_content = View::tpl(array(
                'result' => $result,
                'count' => $count,
                'tpl_folder' => $this->tpl_folder,
                'tablename' => $this->tablename,
                'pager' => $pager,
            ), $this->tpl_folder.'/Index');
        }

        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['created_at'] = time();
                $arr = explode('/', $post['link']);
                if( $arr[0] == 'http' ) {
                    unset($arr[0], $arr[1]);
                    $post['link'] = implode('/', $arr);
                }
                $post['link'] = '/'.trim($post['link'], '/');
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Поле "Название" не может быть пустым!');
                } else {
                    $res = Common::insert($this->tablename, $post)->execute();
                    if($res) {
                        Message::GetMessage(1, 'Вы успешно добавили данные!');
                        HTTP::redirect('backend/seo/'.Route::controller().'/add');
                    } else {
                        Message::GetMessage(0, 'Не удалось добавить данные!');
                    }
                }
                $result = Arr::to_object($post);
            } else {
                $result = array();
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit', array('list_link' => '/backend/seo/links/index') );

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

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['updated_at'] = time();
                $arr = explode('/', $post['link']);
                if( $arr[0] == 'http' ) {
                    unset($arr[0], $arr[1]);
                    $post['link'] = implode('/', $arr);
                }
                $post['link'] = '/'.trim($post['link'], '/');
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Поле "Название" не может быть пустым!');
                } else {
                    $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                    if($res) {
                        Message::GetMessage(1, 'Вы успешно изменили данные!');
                        HTTP::redirect('backend/seo/'.Route::controller().'/edit/'.Arr::get($_POST, 'id'));
                    } else {
                        Message::GetMessage(0, 'Не удалось изменить данные!');
                    }
                }
                $post['id'] = Arr::get($_POST, 'id');
                $result     = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', (int) Route::param('id'))->find();
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit', array('list_link' => '/backend/seo/links/index') );

            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.Route::param('id'));

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
                HTTP::redirect('backend/seo/'.Route::controller().'/index');
            }
            $page = DB::select()->from($this->tablename)->where('id', '=', $id)->find();
            if(!$page) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/seo/'.Route::controller().'/index');
            }

            DB::delete($this->tablename)->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/seo/'.Route::controller().'/index');
        }

    }