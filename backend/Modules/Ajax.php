<?php
    namespace Backend\Modules;

    use Core\Arr;
    use Core\HTML;
    use Core\Message;
    use Core\QB\DB;
    use Core\Support;
    use Core\View;
    use Core\Config;
    use Core\Files;
    use Core\User;
    
    class Ajax extends Base {

        public function getModelsByBrandIDAction() {
            $brand_id = (int) Arr::get($_POST, 'brand_id');
            $models = DB::select()->from('models')
                        ->where('brand_id', '=', $brand_id)
                        ->order_by('name')
                        ->find_all();
            $options = array();
            foreach ($models as $model) {
                $options[] = array('name' => $model->name, 'id' => $model->id);
            }
            die(json_encode(array(
                'options' => $options,
            )));
        }

        public function getSpecificationsByCatalogTreeIDAction() {
            $catalog_tree_id = (int) Arr::get($_POST, 'catalog_tree_id');
            $result = DB::select('brands.*')->from('brands')
                        ->join('catalog_tree_brands', 'LEFT')
                        ->on('catalog_tree_brands.brand_id', '=', 'brands.id')
                        ->where('catalog_tree_brands.catalog_tree_id', '=', $catalog_tree_id)
                        ->order_by('name')
                        ->find_all();
            $brands = array();
            foreach ($result as $obj) {
                $brands[] = array('name' => $obj->name, 'id' => $obj->id);
            }
            $result = DB::select('sizes.*')->from('sizes')
                        ->join('catalog_tree_sizes', 'LEFT')
                        ->on('catalog_tree_sizes.size_id', '=', 'sizes.id')
                        ->where('catalog_tree_sizes.catalog_tree_id', '=', $catalog_tree_id)
                        ->order_by('name')
                        ->find_all();
            $sizes = array();
            foreach ($result as $obj) {
                $sizes[] = array('name' => $obj->name, 'id' => $obj->id);
            }
            $_specifications = DB::select('specifications.*')->from('specifications')
                                ->join('catalog_tree_specifications', 'LEFT')
                                ->on('catalog_tree_specifications.specification_id', '=', 'specifications.id')
                                ->where('catalog_tree_specifications.catalog_tree_id', '=', $catalog_tree_id)
                                ->order_by('specifications.name')
                                ->find_all();
            $arr = array(0);
            $specifications = array();
            foreach($_specifications AS $s) {
                $arr[] = $s->id;
                $specifications[] = $s;
            }
            $specValues = DB::select()->from('specifications_values')
                            ->where('specification_id', 'IN', $arr)
                            ->order_by('name')
                            ->find_all();
            $arr = array();
            foreach ($specValues as $obj) {
                $arr[$obj->specification_id][] = $obj;
            }
            die(json_encode(array(
                'brands' => $brands,
                'sizes' => $sizes,
                'specifications' => $specifications,
                'specValues' => $arr,
            )));
        }

        public function setStatusAction() {
            if (!isset($_POST['id'])) { die ('Не указаны данные записи'); }

            $status = (int) Arr::get( $_POST, 'current', 0 );
            if( $status ) {
                $status = 0;
            } else {
                $status = 1;
            }
            $id = Arr::get( $_POST, 'id', 0 );
            $table = Arr::get( $_POST, 'table', 0 );

            DB::update($table)->set(array('status' => $status))->where('id', '=', $id)->execute();

            die(json_encode(array(
                'status' => $status
            )));
        }

        public function deleteMassAction() {
            if (!isset($_POST['ids'])) { die ('Не указаны данные записи'); }
            $ids = Arr::get( $_POST, 'ids', 0 );
            $table = Arr::get( $_POST, 'table', 0 );
            if( !empty( $ids ) ) {
                if( $table == 'news' OR $table == 'articles' ) {
                    foreach( $ids AS $id ) {
                        $images = DB::select()->from( $table )->where( 'id', '=', $id )->find_all();
                        foreach ( $images AS $im ) {
                            Files::deleteImage($table, $im->image);
                        }
                    }
                } else if( $table == 'catalog' ) {
                    foreach( $ids AS $id ) {
                        $images = DB::select()->from('catalog_images')->select('image')->where( 'catalog_id', '=', $id )->find_all();
                        foreach ( $images AS $im ) {
                            Files::deleteImage($table, $im->image);
                        }
                    }
                } else if( $table == 'slider' ) {
                    foreach( $ids AS $id ) {
                        $images = DB::select()->from( $table )->where( 'id', '=', $id )->find_all();
                        foreach ( $images AS $im ) {
                            Files::deleteImage($table, $im->image);
                        }
                    }
                } else if( $table == 'banners' ) {
                    foreach( $ids AS $id ) {
                        $images = DB::select()->from( $table )->where( 'id', '=', $id )->find_all();
                        foreach ( $images AS $im ) {
                            Files::deleteImage($table, $im->image);
                        }
                    }
                } else if( $table == 'brands' ) {
                    foreach( $ids AS $id ) {
                        $images = DB::select()->from( $table )->where( 'id', '=', $id )->find_all();
                        foreach ( $images AS $im ) {
                            Files::deleteImage($table, $im->image);
                        }
                    }
                }
                DB::delete( $table )->where( 'id', 'IN', $ids )->execute();
                Message::GetMessage( 1, 'Данные удалены!' );
            }
            die(json_encode(array(
                'success' => true
            )));
        }

        public function setStatusMassAction() {
            if (!isset($_POST['ids'])) { die ('Не указаны данные записи'); }

            $status = (int) Arr::get( $_POST, 'status', 0 );
            $ids = Arr::get( $_POST, 'ids', 0 );
            $table = Arr::get( $_POST, 'table', 0 );

            if( !empty( $ids ) ) {
                DB::update( $table )->set( array( 'status' => $status ) )->where( 'id', 'IN', $ids )->execute();
                Message::GetMessage( 1, 'Статусы изменены!' );
            }

            die(json_encode(array(
                'success' => true
            )));
        }

        public function getURIAction() {
            $uri = Arr::get( $_POST, "uri" );
            $date_s = Arr::get( $_POST, "from" );
            $date_po = Arr::get( $_POST, "to" );

            $uri = Support::generateLink( 'date_s', $date_s, $uri );
            $uri = Support::generateLink( 'date_po', $date_po, $uri );

            die(json_encode(array(
                'success' => true,
                'uri' => $uri,
            )));
        }

        public function sortableAction() {
            $table = Arr::get( $_POST, 'table' );
            $json = Arr::get( $_POST, 'json' );
            $arr = json_decode( stripslashes($json), true );

            function saveSort( $arr, $table, $parentID, $i = 0 ) {
                $noInner = array( 'sitemenu', 'slider', 'catalog', 'brands' );
                foreach( $arr AS $a ) {
                    if( !in_array( $table, $noInner ) ) {
                        $data = array( 'sort' => $i, 'parent_id' => $parentID );
                    } else {
                        $data = array( 'sort' => $i );
                    }
                    $id = Arr::get( $a, 'id' );
                    DB::update( $table )
                        ->set( $data )
                        ->where( 'id', '=', $id )
                        ->execute();
                    $i++;
                    $children = Arr::get( $a, 'children', array() );
                    if( count( $children ) ) {
                        if( in_array( $table, $noInner ) ) {
                            $i = saveSort( $children, $table, $id, $i );
                        } else {
                            saveSort( $children, $table, $id );
                        }
                    }
                }
                return $i;
            }
            saveSort( $arr, $table, 0 );

            die(json_encode(array(
                'success' => true,
            )));
        }

        public function loginAction() {
            $login = Arr::get( $_POST, 'login' );
            $password = Arr::get( $_POST, 'password' );
            $remember = Arr::get( $_POST, 'remember' );

            $u = User::factory();

            $user = $u->get_user_if_isset( $login, $password, 1 );
            if( !$user OR $user->role == 'user' ) {
                die(json_encode(array(
                    'msg' => 'Логин или пароль введены неверно!',
                )));
            }
            $u->auth( $user, $remember );

            die(json_encode(array(
                'success' => true,
            )));
        }

        public function change_fieldAction() {
            $id = (int) Arr::get($_POST, 'id');
            $field = Arr::get($_POST, 'field');
            $table = Arr::get($_POST, 'table');
            if (!$id) { die ('Не указаны данные записи'); }

            $old = DB::select($field)->from($table)->where('id', '=', $id)->find();
            if( !$old ) { die('No data to change!'); }
            $old = $old->$field;
            $new = $old == 1 ? 0 : 1;
            $data = array();
            $data[$field] = $new;
            if( $table == 'catalog' AND $field == 'new' ) {
                $data['new_from'] = time();
            }
            DB::update($table)->set($data)->where('id', '=', $id)->execute();

            die(json_encode(array(
                'current' => $new
            )));
        }

####################### Catalog uploader actions

        public function set_default_imageAction() {
            $id = Arr::get( $_POST, 'id' );
            $catalog_id = Arr::get( $_POST, 'catalog_id' );
            DB::update( 'catalog_images' )->set( array( 'main' => 0 ) )->where( 'catalog_id', '=', $catalog_id )->execute();
            DB::update( 'catalog_images' )->set( array( 'main' => 1 ) )->where( 'id', '=', $id )->execute();
            die;
        }

        public function delete_catalog_photoAction() {
            $id = (int) Arr::get($_POST, 'id');
            if (!$id) die('Error!');
            
            $image = DB::select('image')->from( 'catalog_images' )->where( 'id', '=', $id )->find()->image;
            DB::delete( 'catalog_images' )->where( 'id', '=', $id )->execute();

            Files::deleteImage('catalog', $image);

            die(json_encode(array(
                'status' => true,
            )));
        }

        public function sort_imagesAction() {
            $order = Arr::get($_POST, 'order');
            if (!is_array($order)) die('Error!');
            $updated = 0;
            foreach($order as $key => $value) {
                $value = (int) $value;
                $order = $key + 1;
                DB::update( 'catalog_images' )->set( array( 'sort' => $order ) )->where( 'id', '=', $value )->execute();
                $updated++;
            }
            die(json_encode(array(
                'updated' => $updated,
            )));
        }

        public function get_uploaded_imagesAction() {
            $id = (int) Arr::get($_POST, 'id');
            if( !$id ) die('Error!');
            $images = DB::select()->from( 'catalog_images' )->where( 'catalog_id', '=', $id )->order_by('sort')->find_all();
            if ($images) {
                $show_images = View::tpl(array( 'images' => $images ), 'Catalog/UploadedImages');
            } else {
                $show_images = 0;
            }
            die(json_encode(array(
                'images' => $show_images,
            )));
        }

        public function upload_imagesAction() {
            if (empty($_FILES['file'])) die('No File!');
            $confirm = false;

            $arr = explode('/', Arr::get($_SERVER, 'HTTP_REFERER'));
            $id_good = (int) end($arr);   

            $headers = HTML::emu_getallheaders();
            if(array_key_exists('Upload-Filename', $headers)) {
//                $data = file_get_contents('php://input');
                $name = $headers['Upload-Filename'];
            } else {
                $name = $_FILES['file']['name'];
            }

            $name = explode('.', $name);    
            $ext = strtolower(end($name));
            
            if (!in_array($ext, Config::get('images.types'))) die('Not image!');

            $filename = Files::uploadImage('catalog');

            $has_main = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from( 'catalog_images' )->where( 'catalog_id', '=', $id_good )->where( 'main', '=', 1 )->count_all();
            $data = array(
                'catalog_id' => $id_good,
                'image' => $filename,
            );
            if( !$has_main ) { $data['main'] = 1; }
            $keys = $values = array();
            foreach($data as $key => $value) {
                $keys[] = $key; $values[] = $value;
            }
            DB::insert( 'catalog_images', $keys )->values( $values )->execute();


            die(json_encode(array(
                'confirm' => true,
            )));
        }

########## ORDER ACTIONS

        // Generate associative array from serializeArray data
        public function getDataFromSerialize( $data ) {
            $arr = array();
            foreach( $data AS $el ) {
                $arr[ $el['name'] ] = $el['value'];
            }
            return $arr;
        }


        // Change status of the order
        public function orderStatusAction(){
            if( !Arr::get($_POST, 'id') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            $post = $this->getDataFromSerialize(Arr::get($_POST, 'data'));
            $statuses = Config::get('order.statuses');
            if( !isset($statuses[Arr::get($post, 'status')]) OR !isset($post['status']) ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            $post['id'] = Arr::get($_POST, 'id');
            DB::update('orders')->set(array('status' => Arr::get($post, 'status')))->where('id', '=', Arr::get($post, 'id'))->execute();
            die(json_encode(array(
                'success' => true,
            )));
        }


        // Change delivery settings
        public function orderDeliveryAction() {
            if( !Arr::get($_POST, 'id') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            $post = $this->getDataFromSerialize(Arr::get($_POST, 'data'));
            $delivery = Config::get('order.delivery');
            if( !isset($delivery[Arr::get($post, 'delivery')]) OR !isset($post['delivery']) ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            if( Arr::get($post, 'delivery') == 2 AND !Arr::get($post, 'number') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            $post['id'] = Arr::get($_POST, 'id');
            $data = array('delivery' => Arr::get($post, 'delivery'));
            if( Arr::get($post, 'delivery') == 2 ) {
                $data['number'] = Arr::get($post, 'number');
            }
            DB::update('orders')->set($data)->where('id', '=', Arr::get($post, 'id'))->execute();
            die(json_encode(array(
                'success' => true,
            )));
        }


        // Change payment settings
        public function orderPaymentAction(){
            if( !Arr::get($_POST, 'id') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            $post = $this->getDataFromSerialize(Arr::get($_POST, 'data'));
            $payment = Config::get('order.payment');
            if( !isset($payment[Arr::get($post, 'payment')]) OR !isset($post['payment']) ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            $post['id'] = Arr::get($_POST, 'id');
            DB::update('orders')->set(array('payment' => Arr::get($post, 'payment')))->where('id', '=', Arr::get($post, 'id'))->execute();
            die(json_encode(array(
                'success' => true,
            )));
        }


        // Change user information
        public function orderUserAction(){
            if( !Arr::get($_POST, 'id') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            $post = $this->getDataFromSerialize(Arr::get($_POST, 'data'));
            $post['id'] = Arr::get($_POST, 'id');
            DB::update('orders')->set(array(
                'name' => Arr::get($post, 'name'),
                'phone' => Arr::get($post, 'phone'),
            ))->where('id', '=', Arr::get($post, 'id'))->execute();
            die(json_encode(array(
                'success' => true,
            )));
        }


        // Change items information
        public function orderItemsAction() {
            $post = $_POST;
            if( !Arr::get($post, 'id') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            if( !Arr::get($post, 'catalog_id') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            DB::update('orders_items')->set(array(
                'count' => Arr::get($post, 'count'),
            ))
            ->where('order_id', '=', Arr::get($post, 'id'))
            ->where('catalog_id', '=', Arr::get($post, 'catalog_id'))
            ->where('size_id', '=', Arr::get($post, 'size_id'))
            ->execute();
            die(json_encode(array(
                'success' => true,
            )));
        }


        // Delete item position from the order
        public function orderPositionDeleteAction() {
            $post = $_POST;
            if( !Arr::get($post, 'id') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            if( !Arr::get($post, 'catalog_id') ) {
                die(json_encode(array(
                    'success' => false,
                )));
            }
            DB::delete('orders_items')
                ->where('order_id', '=', Arr::get($post, 'id'))
                ->where('catalog_id', '=', Arr::get($post, 'catalog_id'))
                ->where('size_id', '=', Arr::get($post, 'size_id'))
                ->execute();
            die(json_encode(array(
                'success' => true,
            )));
        }


        // Get items by parent_id
        public function getItemsAction() {
            $id = Arr::get($_POST, 'parent_id');
            $result = DB::select('catalog.*', 'catalog_images.image')->from('catalog')
                    ->join('catalog_images')
                    ->on('catalog_images.catalog_id', '=', 'catalog.id')
                    ->on('catalog_images.main', '=', DB::expr(1))
                    ->where('parent_id', '=', $id)
                    ->order_by('created_at', 'DESC')
                    ->find_all();
            $data = array();
            foreach( $result AS $obj ) {
                $data[] = array(
                    // 'url' => '/catalog/'.$obj->alias,
                    'image' => is_file(HOST.HTML::media('images/catalog/medium/'.$obj->image)) ? HTML::media('images/catalog/medium/'.$obj->image) : '',
                    'name' => $obj->name,
                    'cost' => $obj->cost,
                    'id' => $obj->id,
                );
            }
            die(json_encode(array(
                'success' => true,
                'result' => $data,
            )));
        }


        // Get sizes by catalog_id
        public function getItemSizesAction() {
            $catalog_id = (int) Arr::get($_POST, 'catalog_id');
            $result = DB::select('sizes.*')->from('sizes')
                        ->join('catalog_sizes')
                        ->on('catalog_sizes.size_id', '=', 'sizes.id')
                        ->on('catalog_sizes.catalog_id', '=', DB::expr($catalog_id))
                        ->order_by('name')
                        ->find_all();
            $data = array();
            foreach( $result AS $obj ) {
                if( (int) $obj->id ) {
                    $data[] = array(
                        'id' => $obj->id,
                        'name' => $obj->name,
                    );
                }
            }
            die(json_encode(array(
                'success' => true,
                'result' => $data,
            )));
        }


        // Add simple specification
        public function addSimpleSpecificationValueAction(){
            $post = $_POST;
            // Check data
            $name = Arr::get($post, 'name');
            $alias = Arr::get($post, 'alias');
            $specification_id = Arr::get($post, 'specification_id');
            if( !$name OR !$alias OR !$specification_id ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Вы ввели не все данные',
                )));
            }
            // Get count of rows with the same alias and specification_id
            $count = DB::select(array(DB::expr('COUNT(specifications_values.id)'), 'count'))->from('specifications_values')
                        ->where('specification_id', '=', $specification_id)
                        ->where('alias', '=', $alias)
                        ->count_all();
            // Error if such alias exists
            if( $count ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Измените алиас. Такой уже есть',
                )));
            }
            // Trying to save data
            $result = DB::insert('specifications_values', array('name', 'alias', 'specification_id', 'status', 'created_at'))
                        ->values(array($name, $alias, $specification_id, 1, time()))->execute();
            // Error if failed saving
            if( !$result ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Ошибка на сервере. Повторите попытку позднее',
                )));
            }
            // Get full list of values for current specification
            $result = DB::select()->from('specifications_values')
                        ->where('specification_id', '=', $specification_id)
                        ->order_by('name')
                        ->find_all();
            $arr = array();
            foreach( $result AS $obj ) {
                $arr[] = $obj;
            }
            // Answer
            die(json_encode(array(
                'success' => true,
                'result' => $arr,
            )));
        }


        // Edit simple specification
        public function editSimpleSpecificationValueAction(){
            $post = $_POST;
            // Check data
            $name = Arr::get($post, 'name');
            $alias = Arr::get($post, 'alias');
            $status = Arr::get($post, 'status');
            $id = Arr::get($post, 'id');
            $specification_id = Arr::get($post, 'specification_id');
            if( !$name OR !$alias OR !$id OR !$specification_id ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Вы ввели не все данные',
                )));
            }
            // Get count of rows with the same alias and specification_id
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('specifications_values')
                        ->where('specification_id', '=', $specification_id)
                        ->where('alias', '=', $alias)
                        ->where('id', '!=', $id)
                        ->count_all();
            // Error if such alias exists
            if( $count ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Измените алиас. Такой уже есть',
                )));
            }
            // Trying to save data
            $result = DB::update('specifications_values')->set(array(
                'name' => $name,
                'alias' => $alias,
                'status' => $status,
            ))->where('id', '=', $id)->execute();
            // Error if failed saving
            if( !$result ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Ошибка на сервере. Повторите попытку позднее',
                )));
            }
            // Get full list of values for current specification
            $result = DB::select()->from('specifications_values')
                        ->where('specification_id', '=', $specification_id)
                        ->order_by('name')
                        ->find_all();
            $arr = array();
            foreach( $result AS $obj ) {
                $arr[] = $obj;
            }
            // Answer
            die(json_encode(array(
                'success' => true,
                'result' => $arr,
            )));
        }


        // Delete value for anyone specification
        public function deleteSpecificationValueAction(){
            $post = $_POST;
            // Check data
            $id = Arr::get($post, 'id');
            if( !$id ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Вы ввели не все данные',
                )));
            }
            // Trying to delete value
            $result = DB::delete('specifications_values')->where('id', '=', $id)->execute();
            // Error if failed saving
            if( !$result ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Ошибка на сервере. Повторите попытку позднее',
                )));
            }
            // Answer
            die(json_encode(array(
                'success' => true,
            )));
        }


        // Add simple specification
        public function addColorSpecificationValueAction(){
            $post = $_POST;
            // Check data
            $name = Arr::get($post, 'name');
            $color = Arr::get($post, 'color');
            $alias = Arr::get($post, 'alias');
            $specification_id = Arr::get($post, 'specification_id');
            if( !$name OR !$alias OR !$specification_id OR !preg_match('/^#[0-9abcdef]{6}$/', $color, $matches) ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Вы ввели не все данные',
                )));
            }
            // Get count of rows with the same alias and specification_id
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('specifications_values')
                        ->where('specification_id', '=', $specification_id)
                        ->where('alias', '=', $alias)
                        ->count_all();
            // Error if such alias exists
            if( $count ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Измените алиас. Такой уже есть',
                )));
            }
            // Trying to save data
            $result = DB::insert('specifications_values', array('name', 'alias', 'specification_id', 'status', 'color'))
                        ->values(array($name, $alias, $specification_id, 1, $color))->execute();
            // Error if failed saving
            if( !$result ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Ошибка на сервере. Повторите попытку позднее',
                )));
            }
            // Get full list of values for current specification
            $result = DB::select()->from('specifications_values')
                        ->where('specification_id', '=', $specification_id)
                        ->order_by('name')
                        ->find_all();
            $arr = array();
            foreach( $result AS $obj ) {
                $arr[] = $obj;
            }
            // Answer
            die(json_encode(array(
                'success' => true,
                'result' => $arr,
            )));
        }


        // Edit simple specification
        public function editColorSpecificationValueAction(){
            $post = $_POST;
            // Check data
            $name = Arr::get($post, 'name');
            $color = Arr::get($post, 'color');
            $alias = Arr::get($post, 'alias');
            $status = Arr::get($post, 'status');
            $id = Arr::get($post, 'id');
            $specification_id = Arr::get($post, 'specification_id');
            if( !$name OR !$alias OR !$id OR !$specification_id OR !preg_match('/^#[0-9abcdef]{6}$/', $color, $matches) ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Вы ввели не все данные',
                )));
            }
            // Get count of rows with the same alias and specification_id
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('specifications_values')
                        ->where('specification_id', '=', $specification_id)
                        ->where('alias', '=', $alias)
                        ->where('id', '!=', $id)
                        ->count_all();
            // Error if such alias exists
            if( $count ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Измените алиас. Такой уже есть',
                )));
            }
            // Trying to save data
            $result = DB::update('specifications_values')->set(array(
                'name' => $name,
                'alias' => $alias,
                'status' => $status,
                'color' => $color,
            ))->where('id', '=', $id)->execute();
            // Error if failed saving
            if( !$result ) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Ошибка на сервере. Повторите попытку позднее',
                )));
            }
            // Get full list of values for current specification
            $result = DB::select()->from('specifications_values')
                        ->where('specification_id', '=', $specification_id)
                        ->order_by('name')
                        ->find_all();
            $arr = array();
            foreach( $result AS $obj ) {
                $arr[] = $obj;
            }
            // Answer
            die(json_encode(array(
                'success' => true,
                'result' => $arr,
            )));
        }
        

        // public function getPlacesAction(){
        //     $post = $_POST;
        //     // Check data
        //     $cityId = Arr::get($post, 'cityId');

        //     if (!isset($cityId)) {
        //         die(json_encode(array(
        //             'success' => false,
        //             'error' => 'Ошибка обработки',
        //         )));
        //     }
        //     $result = DB::select()->from('places')->where('city_id', '=', $cityId)->as_object()->execute();
        //     if (count($result)) {
        //         $data = View::tpl(array('result' => $result), 'Afisha/SelectPlace');
        //     }
        //     die(json_encode(array(
        //         'data' => $data,
        //     )));
        // }

        // public function getSectorsAction(){
        //     $post = $_POST;
        //     // Check data
        //     $placeId = Arr::get($post, 'placeId');

        //     if (!isset($placeId)) {
        //         die(json_encode(array(
        //             'success' => false,
        //             'error' => 'Ошибка обработки',
        //         )));
        //     }
        //     $result = DB::select()->from('sectors')->where('parent_id', '=', $placeId)->as_object()->execute();
        //     if (count($result)) {
        //         $data = View::tpl(array('result' => $result), 'Afisha/ListPlaces');
        //     }
        //     die(json_encode(array(
        //         'data' => $data,
        //     )));
        // }

         public function getMapAction(){
            $post = $_POST;
            // Check data
            $id_place = Arr::get($post, 'id_place');
            $id_afisha = Arr::get($post, 'id_afisha');

            if (!isset($id_place)) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Ошибка обработки',
                )));
            }
            $result = DB::select()->from('places')->where('id', '=', $id_place)->find();

            if (!$result) {
                 die(json_encode(array(
                    'success' => false,
                    'error' => 'Ошибка выборки места с БД',
                )));
            }

            $seats = \Modules\Afisha\Models\Afisha::getMapSeats($id_afisha);

            // getMap
            try {
                $obj = \Modules\Afisha\Models\Map::factory()->loadFile($result->filename);
                // parse Data
                $map = $obj->parseDom($seats, true, true);
                $mapTpl = View::tpl(array('map' => $map), 'Map/Main');
                die(json_encode(array(
                    'success' => true,
                    'map' => $mapTpl,
                )));
                
            }
            catch(\Exception $e) {
                die(json_encode(array(
                    'success' => false,
                    'error' => $e->getMessage(),
                    'code'  => $e->getCode()
                )));
            }
        }


        public function getPricesAction(){
            $post = $_POST;
            // Check data
            $id_afisha = Arr::get($post, 'id_afisha');

            if (!isset($id_afisha)) {
                die(json_encode(array(
                    'success' => false,
                    'error' => 'Ошибка обработки',
                )));
            }

            $result = DB::select()
                ->from('prices')
                ->where('afisha_id', '=', $id_afisha)
                ->execute()
                ->as_array();
            foreach ($result as $key => $value) {
                $result[$key]['place_list'] = \Modules\Afisha\Models\Afisha::getPlaceListJson($value['id'], true);
            }

            if (count($result)) {
                die(json_encode(array('success' => true, 'result' => $result)));
            }
            die(json_encode(array(
                'success' => false,
                'data' => 'Ошибка поиска данных',
            )));
        }


        public function updateSeatsAction(){
            $post = $_POST;

            $afisha_id = $post['afisha_id'];
            $list_keys = $post['list'];

            if (!$afisha_id OR !$list_keys) {
                die(json_encode(array('success' => false, 'message' => 'Ошибка получения данных')));
            }

            $afisha = DB::select()->from('afisha_orders')->where('id', '=', (int)$afisha_id)->find();
            $prices = DB::select('id')->from('prices')->where('afisha_id', '=', $afisha->afisha_id)->find_all();
            $pricesArr = array();
            if (count($prices)) {
                foreach ($prices as $key => $value) {
                    $pricesArr[] = $value->id;
                }
                $res2 = \Core\Common::update('seats', array('status' => 1, 'reserved_at' => DB::expr(NULL)))
                    ->where('view_key', 'IN', array_filter(explode(',', $afisha->seats_keys)))
                    ->where('price_id', 'IN', $pricesArr)
                    ->execute();
            }

            $res = \Core\Common::update('afisha_orders', array('seats_keys' => $list_keys))->where('id', '=', (int)$afisha_id)->execute();
            if ($res) {
                die(json_encode(array('success' => true, 'message' => 'Данные сохранены', 'reload' => true)));
            }
            else {
                die(json_encode(array('success' => false, 'message' => 'Ошибка обновления данных')));
            }
        }


        public function updateOrderStatusAction(){
            $post = $_POST;

            $afisha_id = $post['afisha_id'];
            $status = $post['status'];
            if ($status == '') {
                $status = null;
            }

            if ($status == 'success') {
                $seatsStatus = 3;
            }
            else {
                $seatsStatus = 2;
            }

            $data = array('status' => $seatsStatus);
            if ($seatsStatus == 2) {
                $data['reserved_at'] = time();
            }

            if (!$afisha_id) {
                die(json_encode(array('success' => false, 'message' => 'Ошибка получения данных')));
            }

            $res = \Core\Common::update('afisha_orders', array('status' => $status))->where('id', '=', (int)$afisha_id)->execute();

            // Get current order
            $afisha = DB::select()->from('afisha_orders')->where('id', '=', (int)$afisha_id)->find();

            $prices = DB::select('id')->from('prices')->where('afisha_id', '=', $afisha->afisha_id)->find_all();
            $pricesArr = array();
            if (count($prices)) {
                foreach ($prices as $key => $value) {
                    $pricesArr[] = $value->id;
                }
                $res2 = \Core\Common::update('seats', $data)
                    ->where('view_key', 'IN', array_filter(explode(',', $afisha->seats_keys)))
                    ->where('price_id', 'IN', $pricesArr)
                    ->execute();
            }

            // if ($res) {
            die(json_encode(array('success' => true, 'message' => 'Данные сохранены', 'reload' => false)));
            // }
            // else {
            //     die(json_encode(array('success' => false, 'message' => 'Ошибка обновления данных')));
            // }
        }

        public function updateUserInfoAction(){
            $post = $_POST;

            $afisha_id = $post['afisha_id'];

            $data = array(
                'name' => $post['name'],
                'phone' => $post['phone'],
                'email' => $post['email'],
                'message' => nl2br($post['message'])
            );

            if (!$afisha_id) {
                die(json_encode(array('success' => false, 'message' => 'Ошибка получения данных')));
            }

            $res = \Core\Common::update('afisha_orders', $data)->where('id', '=', (int)$afisha_id)->execute();
            if ($res) {
                die(json_encode(array('success' => true, 'message' => 'Данные сохранены', 'reload' => false)));
            }
            else {
                die(json_encode(array('success' => false, 'message' => 'Ошибка обновления данных')));
            }
        }
        
        public function updateAdminCommentAction(){
            $post = $_POST;

            $afisha_id = $post['afisha_id'];

            $data = array(
                'admin_comment' => nl2br($post['admin_comment'])
            );

            if (!$afisha_id) {
                die(json_encode(array('success' => false, 'message' => 'Ошибка получения данных')));
            }

            $res = \Core\Common::update('afisha_orders', $data)->where('id', '=', (int)$afisha_id)->execute();
            if ($res) {
                die(json_encode(array('success' => true, 'message' => 'Данные сохранены', 'reload' => false)));
            }
            else {
                die(json_encode(array('success' => false, 'message' => 'Ошибка обновления данных')));
            }
        }


    }