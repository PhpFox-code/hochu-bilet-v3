<?php
    namespace Backend\Modules\User\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\User;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\Image;
    use Core\HTTP;
    use Core\View;
    use Core\Pager\Pager;

    use backend\Modules\User\Models\Users AS Model;

    class Users extends \Backend\Modules\Base {

        public $tpl_folder = 'Users';
        public $page;
        public $limit;
        public $offset;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Пользователи';
            $this->_seo['title'] = 'Пользователи';
            $this->setBreadcrumbs('Пользователи', 'backend/'.Route::controller().'/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = (int) Arr::get($_GET, 'limit', Config::get('limit_backend')) < 1 ?: Arr::get($_GET, 'limit', Config::get('limit_backend'));
            $this->offset = ($this->page - 1) * $this->limit;
        }


        function indexAction () {
            $status = NULL;
            if ( isset($_GET['status']) && $_GET['status'] != '' ) { $status = Arr::get($_GET, 'status', 1); }
            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = Model::countRows($status);
            $result = Model::getRows($status, 'id', 'DESC', $this->limit, ($page - 1) * $this->limit);
            $pager = Pager::factory( $page, $count, $this->limit )->create();
            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1, 'delete' => 1 ) );
            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                ), $this->tpl_folder.'/Index');
        }


        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['password'] = trim( Arr::get($_POST, 'password') );
                if( Model::valid($post) ) {
                    if( $post['password'] ) {
                        $post['password'] = User::factory()->hash_password($post['password']);
                    }
                    $res = Model::update(Model::$table, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                    if($res) {
                        Message::GetMessage(1, 'Вы успешно изменили данные!');
                        if(Arr::get($_POST, 'button', 'save') == 'save-close') {
                            HTTP::redirect('backend/'.Route::controller().'/index');
                        } else if(Arr::get($_POST, 'button', 'save') == 'save-add') {
                            HTTP::redirect('backend/'.Route::controller().'/add');
                        } else {
                            HTTP::redirect('backend/' . Route::controller() . '/edit/' . Route::param('id'));
                        }
                    } else {
                        Message::GetMessage(0, 'Не удалось изменить данные!');
                    }
                }
                $result = Arr::to_object($post);
            } else {
                $result = Model::getRow(Route::param('id'));
            }
            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.Route::param('id'));
            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                ), $this->tpl_folder.'/Form');
        }

        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['password'] = trim( Arr::get($_POST, 'password') );
                if( Model::valid($post) ) {
                    if( $post['password'] ) {
                        $post['password'] = User::factory()->hash_password($post['password']);
                    }
                    $res = Model::insert(Model::$table, $post)->execute();
                    if($res[1]) {
                        Message::GetMessage(1, 'Вы успешно добавили данные!');
                        if(Arr::get($_POST, 'button', 'save') == 'save-close') {
                            HTTP::redirect('backend/'.Route::controller().'/index');
                        } else if(Arr::get($_POST, 'button', 'save') == 'save-add') {
                            HTTP::redirect('backend/'.Route::controller().'/add');
                        } else {
                            HTTP::redirect('backend/' . Route::controller() . '/edit/' . $res[0]);
                        }
                    } else {
                        Message::GetMessage(0, 'Не удалось добавить данные!');
                    }
                }
                unset($post['password']);
                $result = Arr::to_object($post);
            } else {
                $result = Model::getRow(Route::param('id'));
            }
            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.Route::param('id'));
            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                ), $this->tpl_folder.'/Form');
        }


        function deleteAction() {
            $id = (int) Route::param('id');
            $page = Model::getRow($id);
            if(!$page) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/'.Route::controller().'/index');
            }
            Model::delete($id);
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/index');
        }

    }