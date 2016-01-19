<?php
    namespace Backend\Modules\User\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;

    use backend\Modules\User\Models\Roles AS Model;

    class Roles extends \Backend\Modules\Base {

        public $tpl_folder = 'Users/Roles';

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Роли пользователей';
            $this->_seo['title'] = 'Роли пользователей';
            $this->setBreadcrumbs('Роли пользователей', 'backend/'.Route::controller().'/index');
        }


        function indexAction () {
            $result = Model::getBackendUsersRoles();
            $this->_toolbar = Widgets::get( 'Toolbar/ListOrders', array( 'add' => 1 ) );
            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => Model::$table,
                    'pageName' => $this->_seo['h1'],
                ), $this->tpl_folder.'/Index');
        }


        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                unset($_POST['FORM']);
                $access = $_POST;
                if( Model::valid($post) ) {
                    $post['alias'] = 'admin';
                    $res = Model::update(Model::$table, $post)->where('id', '=', Route::param('id'))->execute();
                    if($res) {
                        Model::setAccess($access, Route::param('id'));
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
                if($result->alias != 'admin') {
                    return Config::error();
                }
                $access = Model::getAccess(Route::param('id'));
            }
            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.(int) Route::param('id'));
            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'access' => $access,
                ), $this->tpl_folder.'/Form');
        }


        function addAction () {
            $access = array();
            if ($_POST) {
                $post = $_POST['FORM'];
                unset($_POST['FORM']);
                $access = $_POST;
                if( Model::valid($post) ) {
                    $post['alias'] = 'admin';
                    $res = Model::insert(Model::$table, $post)->execute();
                    if($res[1]) {
                        Model::setAccess($access, $res[0]);
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
                    'access' => $access,
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