<?php
    namespace Backend\Modules\Content\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\QB\DB;
    use Core\Support;
    use Core\Arr;
    use Core\Common;

    class Content extends \Backend\Modules\Base {

        public $tpl_folder = 'Content';
        public $tablename  = 'content';

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Страницы';
            $this->_seo['title'] = 'Страницы';
            $this->setBreadcrumbs('Страницы', 'backend/'.Route::controller().'/index');
        }

        function indexAction () {
            $result = DB::select()->from($this->tablename)->order_by('sort', 'ASC')->as_object()->execute();
            $arr = array();
            foreach($result AS $obj) {
                $arr[$obj->parent_id][] = $obj;
            }

            $this->_filter = Widgets::get( 'Filter/Pages', array( 'open' => 1 ) );
            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1 ) );

            $this->_content = View::tpl(
                array(
                    'result'        => $arr,
                    'tpl_folder'    => $this->tpl_folder,
                    'tablename'     => $this->tablename,
                ), $this->tpl_folder.'/Index');
        }

        function editAction () {
            if ($_POST) {
                $error = 0;
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Наименование страницы не может быть пустым!');
                    $error = 1;
                }
                if(isset($_POST['FORM']['alias'])) {
                    if( !trim(Arr::get($post, 'alias')) ) {
                        Message::GetMessage(0, 'Алиас не может быть пустым!');
                        $error = 1;
                    }
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'), Arr::get($_POST, 'id'));
                }
                if( !$error ) {
                    $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                    if($res) {
                        Message::GetMessage(1, 'Вы успешно изменили данные!');
                        HTTP::redirect('backend/'.Route::controller().'/'.Route::action().'/'.Route::param('id'));
                    } else {
                        Message::GetMessage(0, 'Не удалось изменить данные!');
                    }
                }
                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', (int) Route::param('id'))->as_object()->execute()->current();
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            Config::set( 'colls', 'column-2' );
            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/'.Route::action().'/'.Route::param('id'));

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'tree' => Support::getSelectOptions('Content/Select', 'content', $result->parent_id),
                ), $this->tpl_folder.'/Form');
        }
        
        function addAction () {
            if ($_POST) {
                $error = 0;
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Наименование страницы не может быть пустым!');
                    $error = 1;
                }
                if( !trim(Arr::get($post, 'alias')) ) {
                    Message::GetMessage(0, 'Алиас не может быть пустым!');
                    $error = 1;
                }
                if( !$error ) {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'));
                    $res = Common::insert($this->tablename, $post)->execute();
                    if($res) {
                        Message::GetMessage(1, 'Вы успешно добавили данные!');
                        HTTP::redirect('backend/'.Route::controller().'/'.Route::action());
                    } else {
                        Message::GetMessage(0, 'Не удалось добавить данные!');
                    }
                }
                $result = Arr::to_object($post);
            } else {
                $result = array();
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            Config::set( 'colls', 'column-2' );
            $this->_seo['h1'] = 'Добавление';
            $this->_seo['title'] = 'Добавление';
            $this->setBreadcrumbs('Добавление', 'backend/'.Route::controller().'/'.Route::action());

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'tree' => Support::getSelectOptions('Content/Select', 'content', $result->parent_id),
                ), $this->tpl_folder.'/Form');
        }

        function deleteAction() {
            $id = (int) Route::param('id');
            if(!$id) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/'.Route::controller().'/index');
            }
            $page = DB::select()->from($this->tablename)->where('id', '=', $id)->as_object()->execute()->current();
            if(!$page) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/'.Route::controller().'/index');
            }
            DB::update($this->tablename)->set(array( 'parent_id' => $page->parent_id ))->where('parent_id', '=', $id)->execute();
            DB::delete($this->tablename)->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/index');
        }
    }