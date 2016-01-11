<?php
    namespace Backend\Modules\Catalog\Controllers;

    use Core\Support;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\Common;
    use Core\Files;
    use Core\HTTP;
    use Core\View;
    use Core\QB\DB;

    class Groups extends \Backend\Modules\Base {

        public $tpl_folder = 'Groups';
        public $tablename = 'catalog_tree';
        public $image = 'catalog_tree';

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Группы товаров';
            $this->_seo['title'] = 'Группы товаров';
            $this->setBreadcrumbs('Группы товаров', 'backend/'.Route::controller().'/index');
        }

        function indexAction () {
            $result = DB::select()->from($this->tablename)->order_by('sort', 'ASC')->find_all();
            $arr    = array();
            foreach($result AS $obj) {
                $arr[$obj->parent_id][] = $obj;
            }
            $this->_filter = Widgets::get( 'Filter/Pages', array( 'open' => 1 ) );
            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1 ) );
            $this->_content = View::tpl(
                array(
                    'result' => $arr,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'pageName' => 'Группы товаров',
                ), $this->tpl_folder.'/Index');
        }

        function editAction () {
            $groupBrands = Arr::get( $_POST, 'BRANDS', array() );
            $groupSizes = Arr::get( $_POST, 'SIZES', array() );
            $groupSpec = Arr::get( $_POST, 'SPEC', array() );
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Наименование страницы не может быть пустым!');
                } else if( !trim(Arr::get($post, 'alias')) ) {
                    Message::GetMessage(0, 'Алиас не может быть пустым!');
                } else {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'), Arr::get($_POST, 'id'));
                    $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                    if($res) {
                        $filename = Files::uploadImage($this->image);
                        if( $filename ){
                            DB::update($this->tablename)->set(array('image' => $filename))->where('id', '=', Arr::get($_POST, 'id'))->execute();
                        }
                        DB::delete('catalog_tree_brands')->where('catalog_tree_id', '=', Arr::get($_POST, 'id'))->execute();
                        foreach ($groupBrands as $brand_id) {
                            DB::insert('catalog_tree_brands', array('catalog_tree_id', 'brand_id'))->values(array(Arr::get($_POST, 'id'), $brand_id))->execute();
                        }
                        DB::delete('catalog_tree_sizes')->where('catalog_tree_id', '=', Arr::get($_POST, 'id'))->execute();
                        foreach ($groupSizes as $size_id) {
                            DB::insert('catalog_tree_sizes', array('catalog_tree_id', 'size_id'))->values(array(Arr::get($_POST, 'id'), $size_id))->execute();
                        }
                        DB::delete('catalog_tree_specifications')->where('catalog_tree_id', '=', Arr::get($_POST, 'id'))->execute();
                        foreach ($groupSpec as $specification_id) {
                            DB::insert('catalog_tree_specifications', array('catalog_tree_id', 'specification_id'))->values(array(Arr::get($_POST, 'id'), $specification_id))->execute();
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
                $res = DB::select()->from('catalog_tree_brands')->where('catalog_tree_id', '=', (int) Route::param('id'))->find_all();
                foreach ($res as $obj) {
                    $groupBrands[] = $obj->brand_id;
                }
                $res = DB::select()->from('catalog_tree_sizes')->where('catalog_tree_id', '=', (int) Route::param('id'))->find_all();
                foreach ($res as $obj) {
                    $groupSizes[] = $obj->size_id;
                }
                $res = DB::select()->from('catalog_tree_specifications')->where('catalog_tree_id', '=', (int) Route::param('id'))->find_all();
                foreach ($res as $obj) {
                    $groupSpec[] = $obj->specification_id;
                }
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
                    'tree' => Support::getSelectOptions('Groups/Select', 'catalog_tree', $result->parent_id, $result->id),
                    'brands' => DB::select()->from('brands')->order_by('name')->find_all(),
                    'sizes' => DB::select()->from('sizes')->order_by('name')->find_all(),
                    'specifications' => DB::select()->from('specifications')->order_by('name')->find_all(),
                    'groupBrands' => $groupBrands,
                    'groupSizes' => $groupSizes,
                    'groupSpec' => $groupSpec,
                ), $this->tpl_folder.'/Form');
        }

        function addAction () {
            $groupBrands = Arr::get( $_POST, 'BRANDS', array() );
            $groupSizes = Arr::get( $_POST, 'SIZES', array() );
            $groupSpec = Arr::get( $_POST, 'SPEC', array() );
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['created_at'] = time();
                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Наименование страницы не может быть пустым!');
                } else if( !trim(Arr::get($post, 'alias')) ) {
                    Message::GetMessage(0, 'Алиас не может быть пустым!');
                } else {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'));
                    $res = Common::insert($this->tablename, $post)->execute();
                    if($res) {
                        $id = $res[0];
                        $filename = Files::uploadImage($this->image);
                        if( $filename ){
                            DB::update($this->tablename)->set(array('image' => $filename))->where('id', '=', $id)->execute();
                        }
                        foreach ($groupBrands as $brand_id) {
                            DB::insert('catalog_tree_brands', array('catalog_tree_id', 'brand_id'))->values(array($id, $brand_id))->execute();
                        }
                        foreach ($groupSizes as $size_id) {
                            DB::insert('catalog_tree_sizes', array('catalog_tree_id', 'size_id'))->values(array($id, $size_id))->execute();
                        }
                        foreach ($groupSpec as $specification_id) {
                            DB::insert('catalog_tree_specifications', array('catalog_tree_id', 'specification_id'))->values(array($id, $specification_id))->execute();
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
                    'tree' => Support::getSelectOptions('Groups/Select', 'catalog_tree', $result->parent_id),
                    'brands' => DB::select()->from('brands')->order_by('name')->find_all(),
                    'sizes' => DB::select()->from('sizes')->order_by('name')->find_all(),
                    'specifications' => DB::select()->from('specifications')->order_by('name')->find_all(),
                    'groupBrands' => $groupBrands,
                    'groupSizes' => $groupSizes,
                    'groupSpec' => $groupSpec,
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
            $countChildGroups = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('catalog_tree')->where('parent_id', '=', $id)->count_all();
            if ( $countChildGroups ) {
                Message::GetMessage(0, 'Нельзя удалить эту группу, так как у нее есть подгруппы!');
                HTTP::redirect('backend/'.Route::controller().'/index');
            }
            $countChildItems = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('catalog')->where('parent_id', '=', $id)->count_all();
            if ( $countChildItems ) {
                Message::GetMessage(0, 'Нельзя удалить эту группу, так как в ней содержатся товары!');
                HTTP::redirect('backend/'.Route::controller().'/index');
            }
            Files::deleteImage($this->image, $page->image);
            DB::delete($this->tablename)->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/index');
        }

        function deleteimageAction() {
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