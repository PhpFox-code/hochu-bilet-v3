<?php
    namespace Backend\Modules\Content\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\QB\DB;
    use Core\Common;

    class Control extends \Backend\Modules\Base {

        public $tpl_folder = 'Control';
        public $tablename  = 'control';

        function before() {
            parent::before();
            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            Config::set( 'colls', 'column-2' );
        }

        function indexAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/'.Route::action());
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', 1)->find();
            }

            $this->_seo['h1'] = 'Управление главной страницей';
            $this->_seo['title'] = 'Управление главной страницей';
            $this->setBreadcrumbs('Управление главной страницей', 'backend/'.Route::controller().'/index');

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Form');
        }

        function contactAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/'.Route::action());
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', 2)->find();
            }

            $this->_seo['h1'] = 'Управление страницей контактов';
            $this->_seo['title'] = 'Управление страницей контактов';
            $this->setBreadcrumbs('Управление страницей контактов', 'backend/'.Route::controller().'/index');

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Form');
        }

        function broneAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/'.Route::action());
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', 3)->find();
            }

            $this->_seo['h1'] = 'Управление страницей бронирование билетов';
            $this->_seo['title'] = 'Управление страницей бронирование билетов';
            $this->setBreadcrumbs('Управление страницей бронирование билетов', 'backend/'.Route::controller().'/index');

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Form');
        }
        
        function deliveryAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/'.Route::action());
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', 4)->find();
            }

            $this->_seo['h1'] = 'Управление страницей рассылка';
            $this->_seo['title'] = 'Управление страницей рассылка';
            $this->setBreadcrumbs('Управление страницей рассылка', 'backend/'.Route::controller().'/index');

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Form');
        }
        
        function mail_directorAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/'.Route::action());
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', 5)->find();
            }

            $this->_seo['h1'] = 'Управление страницей Письмо директору';
            $this->_seo['title'] = 'Управление страницей Письмо директору';
            $this->setBreadcrumbs('Управление страницей Письмо директору', 'backend/'.Route::controller().'/index');

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Form');
        }

         function after_paymentAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/'.Route::action());
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', 6)->find();
            }

            $this->_seo['h1'] = 'Управление страницей после оплаты';
            $this->_seo['title'] = 'Управление страницей после оплаты';
            $this->setBreadcrumbs('Управление страницей после оплаты', 'backend/'.Route::controller().'/index');

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Form');
        }

    }