<?php
    namespace Backend\Modules\User\Controllers;

    use backend\Modules\User\Models\Roles;
    use Core\Common;
    use Core\Config;
    use Core\Email;
    use Core\HTTP;
    use Core\Message;
    use Core\Route;
    use Core\Arr;
    use Core\Image;
    use Core\System;
    use Core\User;
    use Core\View;
    use Core\Pager\Pager;

    use backend\Modules\User\Models\Admins AS Model;
    use Core\Widgets;

    class Admins extends \Backend\Modules\Base {

        public $tpl_folder = 'Users/Admins';
        public $page;
        public $limit;
        public $offset;
        public $aroles = array();

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Администраторы';
            $this->_seo['title'] = 'Администраторы';
            $this->setBreadcrumbs('Администраторы', 'backend/admins/index');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $this->limit = Config::get('limit_backend');
            $this->offset = ($this->page - 1) * $this->limit;
            $aroles = Roles::getBackendUsersRoles();
            foreach( $aroles AS $obj ) {
                $this->aroles[$obj->id] = $obj->name;
            }
        }


        function indexAction () {
            $status = NULL;
            if ( isset($_GET['status']) ) { $status = Arr::get($_GET, 'status', 1); }
            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = Model::countRows($status);
            $result = Model::getRows($status, 'users.id', 'DESC', $this->limit, ($page - 1) * $this->limit);
            $pager = Pager::factory( $page, $count, $this->limit )->create();
            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'addLink' => '/backend/admins/add' ) );
            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'count' => $count,
                    'pager' => $pager,
                    'pageName' => $this->_seo['h1'],
                    'roles' => $this->aroles,
                ), $this->tpl_folder.'/Index');
        }


        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( Model::valid($post) ) {
                    $res = Model::insert(Model::$table, $post)->execute();
                    if($res[1]) {
                        User::factory()->update_password($res[0], Arr::get($_POST, 'password'));
                        Message::GetMessage(1, 'Вы успешно добавили данные!');
                        HTTP::redirect('backend/admins/add');
                    } else {
                        Message::GetMessage(0, 'Не удалось добавить данные!');
                    }
                }
                $result = Arr::to_object($post);
            } else {
                $result = array();
            }
            $this->_toolbar = Widgets::get( 'Toolbar/Edit', array('list_link' => '/backend/admins/index') );
            $this->_seo['h1'] = 'Добавление';
            $this->_seo['title'] = 'Добавление';
            $this->setBreadcrumbs('Добавление', 'backend/admins/add');
            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'roles' => $this->aroles,
                ), $this->tpl_folder.'/Form');
        }


        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( Model::valid($post) ) {
                    $res = Model::update(Model::$table, $post)->where('id', '=', Route::param('id'))->execute();
                    if($res) {
                        if( trim(Arr::get($_POST, 'password')) ) {
                            User::factory()->update_password(Route::param('id'), Arr::get($_POST, 'password'));
                        }
                        Message::GetMessage(1, 'Вы успешно изменили данные!');
                        HTTP::redirect('backend/admins/edit/'.Route::param('id'));
                    } else {
                        Message::GetMessage(0, 'Не удалось изменить данные!');
                    }
                }
                $result = Arr::to_object($post);
            } else {
                $result = Model::getRow(Route::param('id'));
            }
            if( isset($result->deleted) && $result->deleted ) {
                $this->_toolbar = Widgets::get( 'Toolbar/Edit', array('list_link' => '/backend/archive/admins') );
            } else {
                $this->_toolbar = Widgets::get( 'Toolbar/Edit', array('list_link' => '/backend/admins/index') );
            }
            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/admins/edit/'.Route::param('id'));
            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'roles' => $this->aroles,
                ), $this->tpl_folder.'/Form');
        }


        function sendAction() {
            $id = (int) Route::param('id');
            $user = Model::getRow($id);
            if(!$user) {
                Message::GetMessage(0, 'Пользователь не существуют!');
                HTTP::redirect('backend/admins/index');
            }
            if( $user->deleted || $user->status == 0 ) {
                Message::GetMessage(1, 'Пользователь удален или заблокирован!');
                HTTP::redirect('backend/admins/index');
            }
            if( !filter_var($user->email, FILTER_VALIDATE_EMAIL) ) {
                Message::GetMessage(1, 'E-Mail пользователя некорректен!');
                HTTP::redirect('backend/admins/index');
            }
            // Generate new password for user and save it to his account
            $password = User::factory()->generate_random_password();
            User::factory()->update_password($user->id, $password);

            // Send E-Mail to user with instructions how recover password
            $mail = Common::factory('mail_templates')->getRow(5);
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
            Message::GetMessage(1, 'Новый пароль отправлен на E-Mail пользователя!');
            HTTP::redirect('backend/admins/index');
        }


        function archiveAction() {
            $id = (int) Route::param('id');
            $page = Model::getRow($id);
            if(!$page) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/'.Route::controller().'/index');
            }
            if( $page->deleted ) {
                Message::GetMessage(1, 'Данные уже в архиве!');
                HTTP::redirect('backend/archive/admins');
            }
            Model::update(Model::$table, array('deleted' => 1))->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные перемещены в архив!');
            HTTP::redirect('backend/'.Route::controller().'/index');
        }


//        function deleteAction() {
//            $id = (int) Route::param('id');
//            $page = Model::getRow($id);
//            if(!$page) {
//                Message::GetMessage(0, 'Данные не существуют!');
//                HTTP::redirect('backend/admins/index');
//            }
//            Model::delete($id);
//            Message::GetMessage(1, 'Данные удалены!');
//            HTTP::redirect('backend/admins/index');
//        }

    }