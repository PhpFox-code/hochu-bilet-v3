<?php
    namespace Backend\Modules\Seo\Controllers;

    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\QB\DB;
    use Core\Arr;
    use Core\Common;

    class Counters extends \Backend\Modules\Base {

        public $tpl_folder = 'SeoCounters';
        public $tablename = 'seo_counters';

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Счетчики';
            $this->_seo['title'] = 'Счетчики';
            $this->setBreadcrumbs('Страницы', 'backend/'.Route::controller().'/index');
        }

        function indexAction () {
            $result = DB::select()->from($this->tablename)->order_by('id', 'DESC')->as_object()->execute();
            $this->_filter = Widgets::get( 'Filter/Pages' );
            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'addLink' => '/backend/seo/'.Route::controller().'/add', 'delete' => 1 ) );
            $this->_content = View::tpl(
                array(
                    'result'        => $result,
                    'tpl_folder'    => $this->tpl_folder,
                    'tablename'     => $this->tablename,
                ), $this->tpl_folder.'/Index');
        }
        
        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['updated_at'] = time();
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

            $this->_toolbar = Widgets::get( 'Toolbar/Edit', array('list_link' => '/backend/seo/counters/index') );

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
        
        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['created_at'] = time();
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

            $this->_toolbar = Widgets::get( 'Toolbar/Edit', array('list_link' => '/backend/seo/counters/index') );

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