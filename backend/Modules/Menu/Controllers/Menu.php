<?php
    namespace Backend\Modules\Menu\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\Common;
    use Core\QB\DB;

    class Menu extends \Backend\Modules\Base {

        public $tpl_folder = 'Menu';
        public $tablename  = 'sitemenu';

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Меню сайта';
            $this->_seo['title'] = 'Меню сайта';
            $this->setBreadcrumbs('Меню сайта', 'backend/'.Route::controller().'/index');
        }

        function indexAction () {
            $result = DB::select()->from($this->tablename)->order_by('sort', 'ASC')->find_all();

            $this->_filter = Widgets::get( 'Filter/Pages' );
            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1, 'delete' => 1 ) );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Index');
        }

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['updated_at'] = time();
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Наименование страницы не может быть пустым!');
                } else if( !trim(Arr::get($post, 'url')) ) {
                    Message::GetMessage(0, 'Ссылка не может быть пустой!');
                } else {
                    $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                    if($res) {
                        Message::GetMessage(1, 'Вы успешно изменили данные!');
                        HTTP::redirect('backend/'.Route::controller().'/edit/'.Arr::get($_POST, 'id'));
                    } else {
                        Message::GetMessage(0, 'Не удалось изменить данные!');
                    }
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result     = Arr::to_object($post);
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
                $post['created_at'] = time();
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Наименование страницы не может быть пустым!');
                } else if( !trim(Arr::get($post, 'url')) ) {
                    Message::GetMessage(0, 'Ссылка не может быть пустой!');
                } else {
                    $res = Common::insert($this->tablename, $post)->execute();
                    if($res) {
                        Message::GetMessage(1, 'Вы успешно добавили данные!');
                        HTTP::redirect('backend/'.Route::controller().'/add');
                    } else {
                        Message::GetMessage(0, 'Не удалось добавить данные!');
                    }
                }

                $result     = Arr::to_object($post);
            } else {
                $result     = array();
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
            DB::delete($this->tablename)->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/index');
        }
    }