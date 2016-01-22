<?php
    namespace Backend\Modules\Afisha\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\Files;
    use Core\Dates;
    use Core\HTTP;
    use Core\View;
    use Core\Common;
    use Core\QB\DB;
    use Core\Pager\Pager;
    use Core\User;
    use Modules\Afisha\Models\Map;

    class Afisha extends \Backend\Modules\Base {

        public $tpl_folder = 'Afisha';
        public $tablename  = 'afisha';
        public $image = 'afisha';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Афиши';
            $this->_seo['title'] = 'Афиши';
            $this->setBreadcrumbs('Афиши', 'backend/'.Route::controller().'/index');
            $this->limit = Config::get('limit_backend');
        }

        function indexAction () {
            $date_s = NULL; $date_po = NULL; $status = NULL; $name = null; $place = null; $city = null;
            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }
            if ( isset($_GET['status']) && $_GET['status'] != '' ) {
                $status = Arr::get($_GET, 'status') == 'published' ? 1 : 0;
            }
            if ( Arr::get($_GET, 'name') ) { $name = urldecode(Arr::get($_GET, 'name')); }
            if ( Arr::get($_GET, 'city') ) { $city = (int) Arr::get($_GET, 'city'); }
            if ( Arr::get($_GET, 'place') ) {
                $place = Arr::get($_GET, 'place') == 'null' ? null : (int) Arr::get($_GET, 'place');
            }
//            Count
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename);
            if( $date_s ) { $count->where( $this->tablename.'.event_date', '>=', $date_s ); }
            if( $date_po ) { $count->where( $this->tablename.'.event_date', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            if( $status !== NULL ) { $count->where( $this->tablename.'.status', '=', $status ); }
            if( $name !== NULL ) { $count->where( $this->tablename.'.name', 'LIKE', "%$name%" ); }
            if( $_GET['place'] != '' ) { $count->where( $this->tablename.'.place_id', '=', $place ); }
            if( $city !== NULL ) {
                $count->where_open()
                    ->where( $this->tablename.'.city_id', '=', $city )
                    ->or_where($this->tablename.'.place_id', 'IN', DB::expr('(SELECT id FROM places WHERE city_id = '.$city.')'))
                ->where_close();
            }
            $count = $count->count_all();

//            Pager
            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $pager = Pager::factory( $page, $count, $this->limit )->create();

//            Result
            $result = DB::select(
                            $this->tablename.'.*',
                            array('places.name', 'p_name'),
                            array(DB::expr('MIN(prices.price)'), 'p_from'),
                            array(DB::expr('MAX(prices.price)'), 'p_to')
                        )
                        ->from($this->tablename)
                        ->join('places', 'left outer')
                            ->on($this->tablename.'.place_id', '=', 'places.id')
                            ->on('places.status', '=', DB::expr(1))
                        ->join('prices', 'left outer')
                            ->on($this->tablename.'.id', '=', 'prices.afisha_id')
                        ->group_by('afisha.id');

            if( $status !== NULL ) { $result->where( $this->tablename.'.status', '=', $status ); }
            if( $date_s ) { $result->where( $this->tablename.'.event_date', '>=', $date_s ); }
            if( $date_po ) { $result->where( $this->tablename.'.event_date', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            if( $name !== NULL ) { $result->where( $this->tablename.'.name', 'LIKE', "%$name%" ); }
            if( $_GET['place'] != '' ) { $result->where( $this->tablename.'.place_id', '=', $place ); }
            if( $city !== NULL ) {
                $result->where_open()
                    ->where( $this->tablename.'.city_id', '=', $city )
                    ->or_where($this->tablename.'.place_id', 'IN', DB::expr('(SELECT id FROM places WHERE city_id = '.$city.')'))
                    ->where_close();
            }
            $result = $result->order_by($this->tablename.'.sort', 'asc')->limit($this->limit)
                ->offset(($page - 1) * $this->limit)->find_all();
            $arr = array();
            foreach ($result as $obj) {
                $arr[$obj->parent_id][] = $obj;
            }

            $this->_toolbar = Widgets::get( 'Toolbar/List', array( 'add' => 1, 'delete' => 1 ) );

            $this->_content = View::tpl(
                array(
                    'result' => $arr,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                    'pager' => $pager,
                    'pageName' => 'Афиши',
                    'places' => DB::select()->from('places')->find_all(),
                    'cities' => DB::select()->from('cities')->order_by('sort')->find_all(),
                ), $this->tpl_folder.'/Index');
        }

        function editAction () {
            if ($_POST) {
                
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['main_show'] = Arr::get( $_POST, 'main_show', 0 );
                $post['event_date'] = strtotime($post['event_date'].' '.$post['event_time']);
                if ($post['place_id'] == 'another') {
                    $post['place_id'] = 'null';
                } else {
                    $post['city_id'] = 'null';
                }

                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Поле "Название" не может быть пустым!');
                } else if(!trim(Arr::get($post, 'alias'))) {
                    Message::GetMessage(0, 'Поле "Алиас" не может быть пустым!');
                } else {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'), Arr::get($_POST, 'id'));
                    $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();

                    $places = $_POST['PLACES']; // Get list places
                    DB::delete('prices')->where('afisha_id', '=', Arr::get($_POST, 'id'))->execute();
                    if (count($places['cost']) > 1) {
                        foreach ($places['cost'] as $key => $cost)
                        {
                            if ($cost == '') continue;
                            // insert new price
                            $resPrice = DB::insert('prices', array('afisha_id', 'price', 'color'))
                                ->values(array(Arr::get($_POST, 'id'), $cost, $places['color'][$key]))->execute();
                            
                            // insert all places (seats) 
                            if ($resPrice AND $places['place'][$key] != '') {
                                $priceId = $resPrice[0];

                                DB::delete('seats')->where('price_id', '=', $priceId)->execute();
                                $seatsStr = $places['place'][$key];
                                $seatsArr = json_decode($seatsStr);
                                if (count($seatsArr)) {
                                    foreach ($seatsArr as $seat) {
                                        DB::insert('seats', array('price_id', 'view_key', 'status', 'reserved_at'))
                                            ->values(array($priceId, $seat->view_key, $seat->status, $seat->reserved_at))->execute();
                                    }
                                }
                            }
                        }
                    }
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

            // current city ID
            $currentCityId =DB::select()->from('places')->where('id', '=', $result->place_id)->as_object()->execute()->current()->city_id;
            
            // select places list
            $allPlaces = DB::select()->from('places')->where('city_id', '=', $currentCityId)->as_object()->execute();
            if (count($allPlaces)) {
                $vPlaces = View::tpl(array('result' => $allPlaces, 'current' => $result->place_id), 'Afisha/SelectPlace');
            }
            
            $prices = DB::select()->from('prices')->where('afisha_id', '=', (int) Route::param('id'))->as_object()->execute();

            $arr = array();
            foreach ($prices as $key => $item) {
                $arr[$item->sector_id]['status'] = $item->status;
                $arr[$item->sector_id]['price'] = $item->price;
            }

            $cities = array();
            $citiesObj = DB::select()->from('cities')->find_all();
            foreach ($citiesObj as $key => $city) {
                $cities[$key]['id'] = $city->id;
                $cities[$key]['name'] = $city->name;
                $cities[$key]['places'] = DB::select()->from('places')->where('city_id', '=', $city->id)->find_all();
            }

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'cities' => $cities,
                    'currentCityId' => $currentCityId,
                    'places_list' => DB::select()->from('places')->where('status', '=', 1)->find_all(),
                    'places' => $vPlaces,
                    'sectors' => $vSectors,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'organizers' => DB::select()->from('users')->where('status', '=', 1)->where('role_id', '=', 9)->find_all(),
                ), $this->tpl_folder.'/Form');
        }
        
        function addAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $post['main_show'] = Arr::get( $_POST, 'main_show', 0 );
                $post['event_date'] = strtotime( Arr::get( $_POST['FORM'], 'event_date' ).' '. Arr::get( $_POST['FORM'], 'event_time' ));
                $post['place_id'] = Arr::get( $post, 'place_id', null );
                if ($post['place_id'] == 'another') {
                    $post['place_id'] = 'null';
                } else {
                    $post['city_id'] = 'null';
                }

                if( !trim(Arr::get($post, 'name')) ) {
                    Message::GetMessage(0, 'Поле "Название" не может быть пустым!');
                } else if(!trim(Arr::get($post, 'alias'))) {
                    Message::GetMessage(0, 'Поле "Алиас" не может быть пустым!');
                } else {
                    $post['alias'] = Common::getUniqueAlias($this->tablename, Arr::get($post, 'alias'));
                    $res = Common::insert($this->tablename, $post)->execute();

                    $places = $_POST['PLACES']; // Get list places
                    if (count($places['cost']) > 1) {
                        foreach ($places['cost'] as $key => $cost)
                        {
                            if ($cost == '') continue;
                            // insert new price
                            $resPrice = DB::insert('prices', array('afisha_id', 'price', 'color'))
                                ->values(array($res[0], $cost, $places['color'][$key]))->execute();
                            // insert all places (seats) 
                            if ($resPrice AND $places['place'][$key] != '') {
                                $priceId = $resPrice[0];
                                $seatsStr = $places['place'][$key];
                                $seatsArr = json_decode($seatsStr);
                                if (count($seatsArr)) {
                                    foreach ($seatsArr as $seat) {
                                        DB::insert('seats', array('price_id', 'view_key', 'status', 'reserved_at'))
                                            ->values(array($priceId, $seat->view_key, $seat->status, $seat->reserved_at))->execute();
                                    }
                                }
                            }
                        }
                    }

                    if($res) {
                        $filename = Files::uploadImage($this->image);
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

            $cities = array();
            $citiesObj = DB::select()->from('cities')->find_all();
            foreach ($citiesObj as $key => $city) {
                $cities[$key]['name'] = $city->name;
                $cities[$key]['places'] = DB::select()->from('places')->where('city_id', '=', $city->id)->find_all();
            }

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'cities' => $cities,
                    'tpl_folder' => $this->tpl_folder,
                    'places_list' => DB::select()->from('places')->where('status', '=', 1)->find_all(),
                    'tablename' => $this->tablename,
                    'organizers' => DB::select()->from('users')->where('status', '=', 1)->where('role_id', '=', 9)->find_all(),
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
            DB::delete('prices')->where('afisha_id', '=', $id)->execute();
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
            DB::update($this->tablename)->set(array('image' => null))->where('id', '=', $id)->execute();
            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/'.Route::controller().'/edit/'.$id);
        }

        function printTicketAction()
        {
            if (User::get_access_for_controller('afisha_brone') != 'edit') {
                $this->no_access();
            }
            $key = Route::param('key');
            $keys = (array) explode(',', $key);
            if (count($keys) == 0) {
                Message::GetMessage(0, 'Места не выбраны!');
                HTTP::redirect('backend/afisha/index');
            }

            $printType = Route::param('printType') ? Route::param('printType') : 'base';

            $afisha = DB::select('afisha.*', array('places.name', 'place'), 'places.filename', 'places.address', 'places.city_id')
                ->from('afisha')
                ->join('places')
                    ->on('afisha.place_id', '=', 'places.id')
                ->where('afisha.id', '=', (int)Route::param('id'))
                ->find();
            if (!$afisha) {
                return Config::error();
            }

            $city = DB::select()->from('cities')->where('id', '=', $afisha->city_id)->find();

            $seatStr = array();
            $termoSeatStr = array();
            try {
                $dom = Map::factory()->loadFile($afisha->filename)->getDomInstance();
                
                $gTag = $dom->getElementsByTagName('g');

                foreach ($gTag as $el) {
                    $id = $el->getAttribute('id');
                    if (in_array($id, $keys)) {

                        if($el->parentNode->hasAttribute('data-plase'))
                        {
                            $originalPlace = $el->parentNode->getAttribute('data-plase');
                        } 
                        elseif ($el->parentNode->parentNode->hasAttribute('data-plase')) 
                        {
                            $originalPlace = $el->parentNode->parentNode->getAttribute('data-plase');
                        }

                        if ($originalPlace) {
                            $place = $originalPlace;
                            $place = str_replace(array('места', 'Места'), 'место', $place);
                            $place = str_replace('(левая сторона)', '(лев. сторона)', $place);
                            $place = str_replace('(правая сторона)', '(пр. сторона)', $place);
                            $place = str_replace(',', '<br />', $place);
                            $place = str_replace('ряд', 'ряд:', $place);
                            $seatStr[$id] = $place;

                            $dataInit = json_decode($el->getAttribute('data-init'));
                            $seatStr[$id] .= $dataInit->seat;

//                          For termo print
                            preg_match('#^(.*)?, ряд ([0-9]+)#', $originalPlace, $matches);

                            $termoSeatStr[$id]['block'] = $matches[1];
                            $termoSeatStr[$id]['block'] = str_replace('(левая сторона)', '(лев. сторона)', $termoSeatStr[$id]['block']);
                            $termoSeatStr[$id]['block'] = str_replace('(правая сторона)', '(пр. сторона)', $termoSeatStr[$id]['block']);

                            $termoSeatStr[$id]['row'] = $matches[2];
                            $termoSeatStr[$id]['seat'] = $dataInit->seat;
                        }
                    }
                }
            } catch(\Exception $e) {
                die('Ошибка загрузки карты');
            }
                
            $tickets = array();

            foreach ($keys as $key) {
                $priceRow = DB::select('price', 'seats.id')
                    ->from('prices')
                    ->join('seats', 'LEFT')
                        ->on('prices.id', '=', 'seats.price_id')
                    ->where('afisha_id', '=', $afisha->id)
                    ->where('seats.view_key', '=', $key)->find();


                $tickets[] = Arr::to_object(array(
                    'event_name' => $afisha->name,
                    'print_name' => $afisha->print_name,
                    'print_name_small' => $afisha->print_name_small,
                    'event_date' => date('j', $afisha->event_date). ' ' . Dates::month(date('m', $afisha->event_date)) . ' ' . date('Y', $afisha->event_date). ' в '.$afisha->event_time,
                    'event_place' => $afisha->place,
                    'event_just_date' => date('j', $afisha->event_date). ' ' . Dates::month(date('m', $afisha->event_date)) . ' ' . date('Y', $afisha->event_date),
                    'event_time' => $afisha->event_time,
                    'event_address' => $afisha->address,
                    'place_string' => $seatStr[$key],
                    'place_block' => $termoSeatStr[$key]['block'],
                    'place_row' => $termoSeatStr[$key]['row'],
                    'place_seat' => $termoSeatStr[$key]['seat'],
                    'price' => $priceRow->price,
                    'phone' => $city->phone,
                    'barcode' => $afisha->id.'-'.$key,
                ));
            }

            if ($printType == 'base') {
                echo View::tpl(array('tickets' => $tickets), 'Afisha_orders/Print');
            } else {
                echo View::tpl(array('tickets' => $tickets), 'Afisha_orders/PrintTermo');
            }
            die();
        }

        function createOrderAction()
        {
            if (User::get_access_for_controller('afisha_brone') != 'edit') {
                $this->no_access();
            }
            $key = Route::param('key');
            $keys = (array) explode(',', $key);
            $keys = array_filter($keys);
            if (count($keys) == 0) {
                Message::GetMessage(0, 'Места не выбраны!');
                HTTP::redirect('backend/afisha/index');
            }

            $afisha = DB::select('afisha.*', array('places.name', 'place'), 'places.filename', 'places.address')
                ->from('afisha')
                ->join('places')
                    ->on('afisha.place_id', '=', 'places.id')
                ->where('afisha.id', '=', (int)Route::param('id'))
                ->find();
            if (!$afisha) {
                return Config::error();
            }

                
            // Get prices by afisha ID
            $prices = DB::select('id')
                ->from('prices')
                ->where('afisha_id', '=', $afisha->id)
                ->find_all();
            if (count($prices) == 0) {
                Message::GetMessage(0, 'Ошибка создания заказа (выборка цен)');
                HTTP::redirect('backend/afisha/index');
            }

            $pricesIds = array();
            foreach ($prices as $price) {
                $pricesIds[] = $price->id;
            }

            // Generate seats id from places list
            $seats = DB::select('id')
                ->from('seats')
                ->where('view_key', 'IN', $keys)
                ->where('price_id', 'IN', $pricesIds)
                ->and_where_open()
                    ->where('status', '=', 1)
                    ->or_where_open()
                        ->where('status', '=', 2)
                        ->where('reserved_at', '<', time() - (60 * 60 *24 * Config::get('reserved_days')) )
                    ->or_where_close()
                ->and_where_close()

                ->find_all();

            if (count($seats) == 0) {
                Message::GetMessage(0, 'Ошибка создания заказа (выборка мест)');
                HTTP::redirect('backend/afisha/index');
            }

            $seatsId = array();
            foreach ($seats as $seat) {
                $seatsId[] = $seat->id;
            }
            $orderType = (int)Route::param('orderType');
            $data = array(
                'afisha_id' => $afisha->id,
                'is_admin' => User::info()->role_id == 2 ? 1 : 0,
                'admin_brone' => $orderType,
                'creator_id' => User::info()->id,
                'seats_keys' => implode(',', $keys),
                'created_at' => time(),
                'status' => null     // Если нужно оформлять заказ сразу как оплаченый, нужно на 454 изменить значение
            );
            
            $res = DB::insert('afisha_orders', array_keys($data))->values(array_values($data))->execute();

            if ($res) {
                // Update status
                $res2 = DB::update('seats')
                    ->set(array('status' => ($orderType == 1 ? 3 : 2), 'reserved_at' => time()))
                    ->where('id', 'IN', $seatsId)
                    ->execute();

                Message::GetMessage(1, 'Заказ успешно создан!');
                HTTP::redirect('backend/afisha_orders/edit/'.$res[0]);
            } else {
                Message::GetMessage(0, 'Ошибка создания заказа!');
                HTTP::redirect('backend/afisha/index');
            }
        }
    }