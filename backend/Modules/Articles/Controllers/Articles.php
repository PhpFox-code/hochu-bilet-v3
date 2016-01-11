<?php
    namespace Backend\Modules\Articles\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\Files;
    use Core\HTTP;
    use Core\View;
    use Core\Common;
    use Core\QB\DB;
    use Core\Pager\Pager;

    class Articles extends \Backend\Modules\Base {

        public $tpl_folder = 'Articles';
        public $tablename  = 'articles';
        public $image = 'articles';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Статьи';
            $this->_seo['title'] = 'Статьи';
            $this->setBreadcrumbs('Статьи', 'backend/'.Route::controller().'/index');
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
            if( $status !== NULL ) { $result->where( 'status', '=', $status ); }
            if( $date_s ) { $result->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $result->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            $result = $result->order_by('id', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $pager = Pager::factory( $page, $count, $this->limit )->create();

            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1, 'delete' => 1 ) );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                    'pager' => $pager,
                    'pageName' => 'Статьи',
                ), $this->tpl_folder.'/Index');
        }

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['show_image'] = Arr::get( $_POST, 'show_image', 0 );
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Поле "Название" не может быть пустым!');
                } else if(!trim(Arr::get($post, 'alias'))) {
                    Message::GetMessage(0, 'Поле "Алиас" не может быть пустым!');
                } else {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'), Arr::get($_POST, 'id'));
                    $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                    if($res) {
                        $filename = Files::uploadImage($this->image);
                        if( $filename ){
                            DB::update($this->tablename)->set(array('image' => $filename))->where('id', '=', Arr::get($_POST, 'id'))->execute();
                        }
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
                $post['show_image'] = Arr::get( $_POST, 'show_image', 0 );
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Поле "Название" не может быть пустым!');
                } else if(!trim(Arr::get($post, 'alias'))) {
                    Message::GetMessage(0, 'Поле "Алиас" не может быть пустым!');
                } else {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'));
                    $res = Common::insert($this->tablename, $post)->execute();
                    if($res) {
                        $filename = Files::uploadImage('news');
                        if( $filename ){
                            DB::update($this->tablename)->set(array('image' => $filename))->where('id', '=', $res[0])->execute();
                        }
                        Message::GetMessage(1, 'Вы успешно добавили данные!');
                        HTTP::redirect('backend/'.Route::controller().'/add');
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

        function deleteImageAction() {
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
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/edit/'.$id);
        }
    }