<?php
    namespace Backend\Modules\Catalog\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\Support;
    use Core\HTTP;
    use Core\HTML;
    use Core\View;
    use Core\Common;
    use Core\QB\DB;
    use Core\Pager\Pager;

    class Items extends \Backend\Modules\Base {

        public $tpl_folder = 'Catalog';
        public $tablename  = 'catalog';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Товары';
            $this->_seo['title'] = 'Товары';
            $this->setBreadcrumbs('Товары', 'backend/'.Route::controller().'/index');
            $this->limit = Config::get('limit_backend');
        }

        function indexAction () {
            $status = NULL;
            if ( isset($_GET['status']) ) { $status = Arr::get($_GET, 'status', 1); }

            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename);
            if( $status !== NULL ) { $count->where( 'status', '=', $status ); }
            $count = $count->count_all();
            $result = DB::select('catalog.*', 'catalog_images.image', array('catalog_tree.name', 'catalog_tree_name'), array('catalog_tree.id', 'catalog_tree_id'))
                        ->from($this->tablename)
                        ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('1'))
                        ->join('catalog_tree', 'LEFT')->on('catalog_tree.id', '=', 'catalog.parent_id');
            if( $status !== NULL ) { $result->where( 'status', '=', $status ); }
            $result = $result->order_by('catalog.created_at', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $pager = Pager::factory( $page, $count, $this->limit )->create();

            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1, 'delete' => 1 ) );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                    'pager' => $pager,
                    'pageName' => 'Товары',
                ), $this->tpl_folder.'/Index');
        }

        function editAction () {
            $itemSizes = Arr::get( $_POST, 'SIZES', array() );
            $specArray = Arr::get( $_POST, 'SPEC', array() );
            if ($_POST) {
                $post = $_POST['FORM'];

                // Set default settings for some fields
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['new'] = Arr::get( $_POST, 'new', 0 );
                $post['top'] = Arr::get( $_POST, 'top', 0 );
                $post['sale'] = Arr::get( $_POST, 'sale', 0 );
                $post['available'] = Arr::get( $_POST, 'available', 0 );
                $post['sex'] = Arr::get( $_POST, 'sex', 0 );
                $post['cost'] = (int) Arr::get( $post, 'cost', 0 );
                $post['cost_old'] = (int) Arr::get( $post, 'cost_old', 0 );
                $isNew = DB::select('new')->from($this->tablename)->where('id', '=', (int) Route::param('id'))->find();
                if( Arr::get( $post, 'new' ) AND (!$isNew OR !$isNew->new)) {
                    $post['new_from'] = time();
                }
                // Check form for rude errors
                if( !Arr::get( $post, 'alias' ) ) {
                    Message::GetMessage(0, 'Алиас не может быть пустым!');
                } else if( !Arr::get( $post, 'name' ) ) {
                    Message::GetMessage(0, 'Название не может быть пустым!');
                } else if( !Arr::get( $post, 'cost' ) ) {
                    Message::GetMessage(0, 'Цена не может быть пустой!');
                } else {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'), Arr::get($_POST, 'id'));
                    $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                    if($res) {
                        DB::delete('catalog_sizes')->where('catalog_id', '=', Arr::get($_POST, 'id'))->execute();
                        foreach ($itemSizes as $size_id) {
                            DB::insert('catalog_sizes', array('catalog_id', 'size_id'))->values(array(Arr::get($_POST, 'id'), $size_id))->execute();
                        }
                        DB::delete('catalog_specifications_values')->where('catalog_id', '=', Arr::get($_POST, 'id'))->execute();
                        foreach ($specArray as $key => $value) {
                            if( is_array($value) ) {
                                foreach ($value as $specification_value_id) {
                                    DB::insert('catalog_specifications_values', array('catalog_id', 'specification_value_id', 'specification_id'))->values(array(Arr::get($_POST, 'id'), $specification_value_id, $key))->execute();
                                }
                            } else if($value) {
                                DB::insert('catalog_specifications_values', array('catalog_id', 'specification_value_id', 'specification_id'))->values(array(Arr::get($_POST, 'id'), $value, $key))->execute();
                            }
                        }
                        Message::GetMessage(1, 'Вы успешно изменили данные!');
                        HTTP::redirect('/backend/'.Route::controller().'/edit/'.Arr::get($_POST, 'id'));
                    } else {
                        Message::GetMessage(0, 'Не удалось изменить данные!');
                    }
                }
                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', (int) Route::param('id'))->find();
                $res = DB::select()->from('catalog_sizes')->where('catalog_id', '=', (int) Route::param('id'))->find_all();
                foreach ($res as $obj) {
                    $itemSizes[] = $obj->size_id;
                }
                $res = DB::select('catalog_specifications_values.specification_id', 'specification_value_id', 'specifications.type_id')
                        ->from('catalog_specifications_values')
                        ->join('specifications')->on('catalog_specifications_values.specification_id', '=', 'specifications.id')
                        ->where('catalog_id', '=', (int) Route::param('id'))
                        ->find_all();
                foreach ($res as $obj) {
                    if( $obj->type_id == 3 ) {
                        $specArray[$obj->specification_id][] = $obj->specification_value_id;
                    } else {
                        $specArray[$obj->specification_id] = $obj->specification_value_id;
                    }
                }
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );

            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.(int) Route::param('id'));

            $images = DB::select()->from( 'catalog_images' )->where( 'catalog_id', '=', $result->id )->order_by('sort')->find_all();
            $show_images = View::tpl(array('images' => $images), $this->tpl_folder . '/UploadedImages');

            $brands = DB::select('brands.*')->from('brands')
                        ->join('catalog_tree_brands')->on('catalog_tree_brands.brand_id', '=', 'brands.id')
                        ->where('catalog_tree_brands.catalog_tree_id', '=', $result->parent_id)
                        ->order_by('brands.name')
                        ->find_all();
            $sizes = DB::select('sizes.*')->from('sizes')
                        ->join('catalog_tree_sizes')->on('catalog_tree_sizes.size_id', '=', 'sizes.id')
                        ->where('catalog_tree_sizes.catalog_tree_id', '=', $result->parent_id)
                        ->order_by('sizes.name')
                        ->find_all();
            $specifications = DB::select('specifications.*')->from('specifications')
                        ->join('catalog_tree_specifications')->on('catalog_tree_specifications.specification_id', '=', 'specifications.id')
                        ->where('catalog_tree_specifications.catalog_tree_id', '=', $result->parent_id)
                        ->order_by('specifications.name')
                        ->find_all();
            $arr = array(0);
            foreach($specifications AS $s) {
                $arr[] = $s->id;
            }
            $specValues = DB::select()->from('specifications_values')
                        ->where('specification_id', 'IN', $arr)
                        ->order_by('name')
                        ->find_all();
            $arr = array();
            foreach ($specValues as $obj) {
                $arr[$obj->specification_id][] = $obj;
            }

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'tree' => Support::getSelectOptions('Catalog/Select', 'catalog_tree', $result->parent_id),
                    'brands' => $brands,
                    'models' => DB::select()->from('models')->where('brand_id', '=', $result->brand_id)->order_by('name')->find_all(),
                    'show_images' => $show_images,
                    'countSimpleOrders' => DB::select(array(DB::expr('id'), 'count'))->from('orders_simple')->where('catalog_id', '=', $result->id)->count_all(),
                    'countOrders' => DB::select(array(DB::expr('COUNT(orders.id)'), 'count'))->from('orders')->join('orders_items', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')->where('orders_items.catalog_id', '=', $result->id)->count_all(),
                    'happyCount' => DB::select(array(DB::expr('COUNT(orders_items.id)'), 'count'))->from('orders_items')
                                        ->join('orders', 'LEFT')->on('orders_items.order_id', '=', 'orders.id')->on('orders.status', '=', DB::expr(1))
                                        ->where('catalog_id', '=', $result->id)
                                        ->count_all(),
                    'happyMoney' => DB::select(array(DB::expr('SUM(orders_items.count * orders_items.cost)'), 'amount'))
                                        ->from('orders_items')
                                        ->join('orders', 'LEFT')->on('orders.id', '=', 'orders_items.order_id')->on('orders.status', '=', DB::expr('1'))
                                        ->where('orders_items.catalog_id', '=', $result->id)
                                        ->group_by('orders_items.catalog_id')->find()->amount,
                    'itemSizes' => $itemSizes,
                    'sizes' => $sizes,
                    'specifications' => $specifications,
                    'specValues' => $arr,
                    'specArray' => $specArray,
                ), $this->tpl_folder.'/Form');
        }

        function addAction () {
            $itemSizes = Arr::get( $_POST, 'SIZES', array() );
            $specArray = Arr::get( $_POST, 'SPEC', array() );
            if ($_POST) {
                $post = $_POST['FORM'];

                // Set default settings for some fields
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['new'] = Arr::get( $_POST, 'new', 0 );
                $post['top'] = Arr::get( $_POST, 'top', 0 );
                $post['sale'] = Arr::get( $_POST, 'sale', 0 );
                $post['available'] = Arr::get( $_POST, 'available', 0 );
                $post['sex'] = Arr::get( $_POST, 'sex', 0 );
                $post['cost'] = (int) Arr::get( $post, 'cost', 0 );
                $post['cost_old'] = (int) Arr::get( $post, 'cost_old', 0 );
                $post['created_at'] = time();
                if( Arr::get( $post, 'new' ) ) {
                    $post['new_from'] = time();
                }
                // Check form for rude errors
                if( !Arr::get( $post, 'alias' ) ) {
                    Message::GetMessage(0, 'Алиас не может быть пустым!');
                } else if( !Arr::get( $post, 'name' ) ) {
                    Message::GetMessage(0, 'Название не может быть пустым!');
                } else if( !Arr::get( $post, 'cost' ) ) {
                    Message::GetMessage(0, 'Цена не может быть пустой!');
                } else {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'));
                    $res = Common::insert($this->tablename, $post)->execute();
                    if($res) {
                        $id = $res[0];
                        foreach ($itemSizes as $size_id) {
                            DB::insert('catalog_sizes', array('catalog_id', 'size_id'))->values(array($id, $size_id))->execute();
                        }
                        foreach ($specArray as $key => $value) {
                            if( is_array($value) ) {
                                foreach ($value as $specification_value_id) {
                                    DB::insert('catalog_specifications_values', array('catalog_id', 'specification_value_id', 'specification_id'))->values(array($id, $specification_value_id, $key))->execute();
                                }
                            } else if($value) {
                                DB::insert('catalog_specifications_values', array('catalog_id', 'specification_value_id', 'specification_id'))->values(array($id, $value, $key))->execute();
                            }
                        }
                        Message::GetMessage(1, 'Вы успешно добавили данные!');
                        HTTP::redirect('/backend/'.Route::controller().'/edit/'.$id);
                    } else {
                        Message::GetMessage(0, 'Не удалось добавить данные!');
                    }
                }
                $result = Arr::to_object($post);
                $parent_id = $result->parent_id;
                $models = DB::select()->from('models')->where('brand_id', '=', $result->brand_id)->find_all();
            } else {
                $result = array();
                $models = array();
                $parent_id = 0;
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );
            $this->_seo['h1'] = 'Добавление';
            $this->_seo['title'] = 'Добавление';
            $this->setBreadcrumbs('Добавление', 'backend/'.Route::controller().'/add');

            $brands = DB::select('brands.*')->from('brands')
                        ->join('catalog_tree_brands')->on('catalog_tree_brands.brand_id', '=', 'brands.id')
                        ->where('catalog_tree_brands.catalog_tree_id', '=', $parent_id)
                        ->order_by('brands.name')
                        ->find_all();
            $sizes = DB::select('sizes.*')->from('sizes')
                        ->join('catalog_tree_sizes')->on('catalog_tree_sizes.size_id', '=', 'sizes.id')
                        ->where('catalog_tree_sizes.catalog_tree_id', '=', $parent_id)
                        ->order_by('sizes.name')
                        ->find_all();

            $specifications = DB::select('specifications.*')->from('specifications')
                        ->join('catalog_tree_specifications')->on('catalog_tree_specifications.specification_id', '=', 'specifications.id')
                        ->where('catalog_tree_specifications.catalog_tree_id', '=', $result->parent_id)
                        ->order_by('specifications.name')
                        ->find_all();
            $arr = array(0);
            foreach($specifications AS $s) {
                $arr[] = $s->id;
            }
            $specValues = DB::select()->from('specifications_values')
                        ->where('specification_id', 'IN', $arr)
                        ->order_by('name')
                        ->find_all();
            $arr = array();
            foreach ($specValues as $obj) {
                $arr[$obj->specification_id][] = $obj;
            }

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'tree' => Support::getSelectOptions('Catalog/Select', 'catalog_tree', $result->parent_id),
                    'brands' => $brands,
                    'sizes' => $sizes,
                    'models' => $models,
                    'itemSizes' => $itemSizes,
                    'specifications' => $specifications,
                    'specValues' => $arr,
                    'specArray' => $specArray,
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
            $images = DB::select()->from( 'catalog_images' )->where( 'catalog_id', '=', $id )->find_all();
            foreach ( $images AS $im ) {
                @unlink( HOST . HTML::media('images/catalog/small/'.$im->image) );
                @unlink( HOST . HTML::media('images/catalog/medium/' . $im->image) );
                @unlink( HOST . HTML::media('images/catalog/big/' . $im->image) );
                @unlink( HOST . HTML::media('images/catalog/original/' . $im->image) );
            }
            DB::delete($this->tablename)->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/index');
        }

    }