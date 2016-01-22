<?php
    namespace Backend\Modules\Statistics\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\Support;
    use Core\Common;
    use Core\Dates;
    use Core\QB\DB;
    use Core\Pager\Pager;
    use Core\User;
    use Modules\Afisha\Models\Map;
    use backend\Modules\Afisha\Models\Afisha;


    class Cassier extends \Backend\Modules\Base {

        public $tpl_folder = 'Cassier';
        public $tablename  = 'users';
        public $pay_statuses;
        public $seat_statuses;
        public $limit;
        public $page;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Статистика по кассирам';
            $this->_seo['title'] = 'Статистика по кассирам';
            $this->setBreadcrumbs('Статистика по кассирам', 'backend/cassier/index');
            $this->limit = Config::get( 'limit_backend' );
            $this->pay_statuses = Config::get('order.pay_statuses');
            $this->seat_statuses = Config::get('order.seat_statuses');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
        }

        function indexAction () {
            if (User::info()->role_id != 2) {
                HTTP::redirect('/backend/cassier/inner/'.(User::info()->id));
            }
//            Set filter vars
            $date_s = NULL; $date_po = NULL; $status = NULL; $eventId = null; $creatorId = null;
            if ( Arr::get($_GET, 'date_s') )
                $date_s = strtotime( Arr::get($_GET, 'date_s') );
            if ( Arr::get($_GET, 'date_po') )
                $date_po = strtotime( Arr::get($_GET, 'date_po') );
            if ( isset( $this->pay_statuses[ Arr::get($_GET, 'status') ]) )
                $status = Arr::get($_GET, 'status', 1);
            if ( Arr::get($_GET, 'status') == 'null' )
                $status = 'null';
            if (Arr::get($_GET, 'event') != 0)
                $eventId = Arr::get($_GET, 'event');
            if (Arr::get($_GET, 'creator_id') != 0)
                $creatorId = Arr::get($_GET, 'creator_id');
            if (User::info()->role_id != 2)
                $creatorId = User::info()->id;

//            Select all admins
            $cassiers = DB::select(
                    $this->tablename.'.*',
                    array('users_roles.name', 'role_name')
                )
                ->from($this->tablename)
                ->join('users_roles')
                    ->on('users_roles.id', '=', $this->tablename.'.role_id')
                ->where('users_roles.alias', '!=', 'user')
                ->where($this->tablename.'.status', '=', 1);
            if ($creatorId) {
                $cassiers->where($this->tablename.'.id', '=', $creatorId);
            }
            $cassiers = $cassiers->find_all();

            $totalCntOrders = DB::select(array(DB::expr('COUNT(*)'), 'count'))->from('afisha_orders')
                ->where('creator_id', '!=', null);
            $this->setFilter($totalCntOrders, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
            $totalCntOrders = $totalCntOrders->count_all();

            $totalOrdersPrice = 0;
            $totalOrdersPriceQuery = DB::select()->from('afisha_orders')->where('creator_id', '!=', null);
            $this->setFilter($totalOrdersPriceQuery, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
            $totalOrdersPriceQuery = $totalOrdersPriceQuery->find_all();
            foreach ($totalOrdersPriceQuery as $order) {
                $totalOrdersPrice += Afisha::getTotalCost($order);
            }

//            Make array with all need data
            $fullResult = array();
            $jsonOrders = array();
            $jsonPrices = array();
            foreach ($cassiers as $key => $cassier) {
                $fullResult[$cassier->id]['user'] = $cassier;

                $allOrders = DB::select(array(DB::expr('COUNT(*)'), 'count'))->from('afisha_orders')
                    ->where('creator_id', '=', $cassier->id);
                $this->setFilter($allOrders, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
                $allOrders = $allOrders->count_all();
                $fullResult[$cassier->id]['totalOrders'] = $allOrders;

                $successOrders = DB::select(array(DB::expr('COUNT(*)'), 'count'))->from('afisha_orders')
                    ->where('creator_id', '=', $cassier->id)->where('status', '=', 'success');
                $this->setFilter($successOrders, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
                $successOrders = $successOrders->count_all();
                $fullResult[$cassier->id]['totalSuccessOrders'] = $successOrders;

                $orders = DB::select()->from('afisha_orders')->where('creator_id', '=', $cassier->id);
                $this->setFilter($orders, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
                $orders = $orders->find_all();


                $justSeats = 0;
                $totalPrice = 0;
                foreach ($orders as $order) {
                    $totalPrice += Afisha::getTotalCost($order);

                    $justSeats += count(array_filter(explode(',', $order->seats_keys)));
                }
                $fullResult[$cassier->id]['countSeats'] = $justSeats;
                $fullResult[$cassier->id]['totalPrice'] = number_format($totalPrice, 0, ',', ' ');

//                Json orders
                $jsonOrders[$key]['name'] = $cassier->name . ($cassier->email ? '<br/>('.$cassier->email.')' : '');
//                Value from 0.00 to 1 Percent equal.
                $jsonOrders[$key]['y'] = ($allOrders / $totalCntOrders);
                $jsonOrders[$key]['v'] = $allOrders;

//                Json prices
                $jsonPrices[$key]['name'] = $cassier->name . ($cassier->email ? '<br/>('.$cassier->email.')' : '');
                $jsonPrices[$key]['y'] = ($totalPrice / $totalOrdersPrice);
                $jsonPrices[$key]['price'] = number_format($totalPrice, 0, ',', ' ');
                $jsonPrices[$key]['cntSeats'] = $justSeats;

            }

//            Rendering
            $this->_content = View::tpl(
                array(
                    'result' => $fullResult,
                    'jsonOrders' => json_encode($jsonOrders),
                    'jsonPrices' => json_encode($jsonPrices),
                    'events' => DB::select()->from('afisha')->where('place_id', 'IS NOT', null)->find_all(),
                    'creators' => $cassiers,
                ),$this->tpl_folder.'/Index');
        }

        function innerAction () {
            if (User::info()->role_id != 2 && User::info()->id != Route::param('id')) {
                $this->no_access();
            }

//            Set filter vars
            $date_s = NULL; $date_po = NULL; $status = NULL; $eventId = null; $creatorId = null;
            if ( Arr::get($_GET, 'date_s') )
                $date_s = strtotime( Arr::get($_GET, 'date_s') );
            if ( Arr::get($_GET, 'date_po') )
                $date_po = strtotime( Arr::get($_GET, 'date_po') );
            if ( isset( $this->pay_statuses[ Arr::get($_GET, 'status') ] ) )
                $status = Arr::get($_GET, 'status', 1);
            if ( Arr::get($_GET, 'status') == 'null' )
                $status = 'null';
            if (Arr::get($_GET, 'event') != 0)
                $eventId = Arr::get($_GET, 'event');
            if (Arr::get($_GET, 'creator_id') != 0)
                $creatorId = Arr::get($_GET, 'creator_id');

//            Select current user
            $cassier = DB::select()
                ->from($this->tablename)
                ->where($this->tablename.'.id', '=', Route::param('id'))
                ->find();

            $this->_seo['h1'] = 'Статистика по ' . $cassier->name;
            $this->_seo['title'] = 'Статистика по ' . $cassier->name;
            $this->setBreadcrumbs('Статистика по ' . $cassier->name);

//            $totalCntOrders = DB::select(array(DB::expr('COUNT(*)'), 'count'))->from('afisha_orders')
//                ->where('creator_id', '=', $cassier->id);
//            $this->setFilter($totalCntOrders, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
//            $totalCntOrders = $totalCntOrders->count_all();

            $ordersQuery = DB::select()->from('afisha_orders')->where('creator_id', '=', $cassier->id);
            $this->setFilter($ordersQuery, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');

            $orders = $ordersQuery->order_by('created_at', 'DESC')->find_all();
//            $pager = Pager::factory( $this->page, $totalCntOrders, $this->limit )->create();

//            Make array with all need data
            $afishaGroups = array();
            foreach ($orders as $order) {
                $afisha = DB::select()->from('afisha')->where('id', '=', $order->afisha_id)->find();

                $afishaGroups[$order->afisha_id]['afisha'] = $afisha;
                $afishaGroups[$order->afisha_id]['orders'][$order->id] = $order;
            }

//            Rendering
            $this->_content = View::tpl(
                array(
                    'afishaGroups' => $afishaGroups,
                    'pay_statuses' => $this->pay_statuses,
                    'events' => DB::select()->from('afisha')->where('place_id', 'IS NOT', null)->find_all(),
                    'creators' => array(),
                    'pager' => '',
                    'tpl_folder' => $this->tpl_folder,
                ),$this->tpl_folder.'/Inner');
        }

        private function setFilter($query, $date_s, $date_po, $status, $eventId, $creatorId, $table) {
            if( $date_s !== NULL )
                $query->where( $table.'.created_at', '>=', $date_s );
            if( $date_po !== NULL )
                $query->where( $table.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1 );
            if( $status !== NULL ) {
                if ($status == 'null')
                    $query->where($table.'.status', 'IS', DB::expr('null'));
                else
                    $query->where( $table.'.status', '=', $status );
            }
            if ($eventId)
                $query->where( $table.'.afisha_id', '=', $eventId );
            if ($creatorId)
                $query->where( $table.'.creator_id', '=', $creatorId );

            return $query;
        }

//        function indexAction () {
////            Set filter vars
//            $date_s = NULL; $date_po = NULL; $status = NULL; $eventId = null; $creatorId = null;
//            if ( Arr::get($_GET, 'date_s') )
//                $date_s = strtotime( Arr::get($_GET, 'date_s') );
//            if ( Arr::get($_GET, 'date_po') )
//                $date_po = strtotime( Arr::get($_GET, 'date_po') );
//            if ( isset( $this->pay_statuses[ Arr::get($_GET, 'status') ] ) )
//                $status = Arr::get($_GET, 'status', 1);
//            if ( Arr::get($_GET, 'status') == 'null' )
//                $status = 'null';
//            if (Arr::get($_GET, 'event') != 0)
//                $eventId = Arr::get($_GET, 'event');
//            if (Arr::get($_GET, 'creator_id') != 0)
//                $creatorId = Arr::get($_GET, 'creator_id');
//
////            Count
//            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename);
//            if( $date_s !== NULL )
//                $count->where( $this->tablename.'.created_at', '>=', $date_s );
//            if( $date_po !== NULL )
//                $count->where( $this->tablename.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1 );
//            if (User::info()->role_id != 2)
//                $count->where($this->tablename.'.creator_id', '=', User::info()->id);
//            if( $status !== NULL ) {
//                if ($status == 'null')
//                    $count->where($this->tablename.'.status', 'IS', DB::expr('null'));
//                else
//                    $count->where( $this->tablename.'.status', '=', $status );
//            }
//            if ($eventId)
//                $count->where( $this->tablename.'.afisha_id', '=', $eventId );
//            if ($creatorId)
//                $count->where( $this->tablename.'.creator_id', '=', $creatorId );
//            $count   = $count->count_all();
//
////            Select result
//            $result = DB::select($this->tablename.'.*', array('users.name', 'creator_name'))->from($this->tablename)
//                ->join('users', 'LEFT OUTER')
//                    ->on('users.id', '=', $this->tablename.'.creator_id');
//
//            if( $date_s )
//                $result->where( $this->tablename.'.created_at', '>=', $date_s );
//            if( $date_po )
//                $result->where( $this->tablename.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1 );
//            if (User::info()->role_id != 2)
//                $result->where($this->tablename.'.creator_id', '=', User::info()->id);
//            if( $status !== NULL ) {
//                if ($status == 'null')
//                    $result->where($this->tablename.'.status', 'IS', DB::expr('null'));
//                else
//                    $result->where( $this->tablename.'.status', '=', $status );
//            }
//            if ($eventId)
//                $result->where( $this->tablename.'.afisha_id', '=', $eventId );
//            if ($creatorId)
//                $result->where( $this->tablename.'.creator_id', '=', $creatorId );
//            $result = $result->order_by($this->tablename.'.created_at', 'DESC')
//                ->limit($this->limit)
//                ->offset(($this->page - 1) * $this->limit)->find_all();
//            $pager = Pager::factory( $this->page, $count, $this->limit )->create();
//
////            Additional data
//            $creators = array();
//            $creators = DB::select()->from('users')->where('status', '=', 1)->find_all();
//
//            $allCassierOrders = array();
//            $totalOrdesJson = array();
//            $allOrders = DB::select($this->tablename.'.*', array('users.name', 'creator_name'))->from($this->tablename)
//                ->join('users', 'LEFT OUTER')
//                    ->on('users.id', '=', $this->tablename.'.creator_id')
//                ->where('creator_id', 'IS NOT', null)->find_all();
//            foreach($allOrders as $key => $order) {
//                $allCassierOrders[$order->creator_id][] = $order;
//
//                $totalOrdesJson[$order->creator_id]['name'] = $order->creator_name;
//                $totalOrdesJson[$order->creator_id]['y'] += 1;
//
//            }
//
//            foreach ($totalOrdesJson as $key => $value) {
//                $totalOrdesJson[$key]['y'] = (($value['y']) / $count );
//            }
//            print_r($totalOrdesJson);
//            $creatorsById = array();
//            foreach($creators as $creator) {
//                $creatorsById[$creator->id] = $creator;
//            }
//
//
//
////            Rendering
//            $this->_content = View::tpl(
//                array(
//                    'result' => $result,
//                    'pager' => $pager,
//                    'status' => $status,
//                    'pay_statuses' => $this->pay_statuses,
//                    'totalOrdersJson' => $totalOrdesJson,
//                    'allCassierOrders' => $allCassierOrders,
//                    'creatorsById' => $creatorsById,
//                    'countOrders' => $count,
//                    'totalCountOrders' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
//                    'events' => DB::select()->from('afisha')->where('place_id', 'IS NOT', null)->find_all(),
//                    'creators' => $creators,
//                ),$this->tpl_folder.'/Index');
//        }

//        function editAction() {
//            $result = DB::select()
//                ->from($this->tablename)
//                ->where('id', '=', Route::param('id'))
//                ->find();
//
////            Set edit access for myself orders
//            if ($result->creator_id == User::info()->id) {
//                User::factory()->_current_access = 'edit';
//            }
//
//            $afisha = DB::select('afisha.*', array('places.name', 'place'), 'places.filename')
//                ->from('afisha')
//                ->join('places')
//                    ->on('afisha.place_id', '=', 'places.id')
//                ->where('afisha.id', '=', $result->afisha_id)
//                ->find();
//
//            // Generate and parse inner map
//            $orderSeats = array();
//
//            $viewKeys = array_filter(explode(',', $result->seats_keys));
//            if (count($viewKeys)) {
//                $prices = DB::select()->from('prices')->where('afisha_id', '=', $result->afisha_id)->find_all();
//                if (count($prices)) {
//                    $pricesIds = array();
//                    foreach ($prices as $key => $value) {
//                        $pricesIds[] = $value->id;
//                    }
//                    $seatsQuery = DB::select()
//                        ->from('seats')
//                        ->where('view_key', 'IN', $viewKeys)
//                        ->where('price_id', 'IN', $pricesIds)
//                        ->execute()
//                        ->as_array();
//
//                    foreach ($seatsQuery as $key => $value) {
//                        $orderSeats[$value['view_key']] = $value;
//                    }
//                }
//            }
//
//            $seatsStr = array();
//            if ($afisha) {
//                $seats = \Modules\Afisha\Models\Afisha::getMapSeats($result->afisha_id);
//                $mapObj = Map::factory()->loadFile($afisha->filename);
//                $innerMap = $mapObj->parseDomOrder($orderSeats, $seats, true, true);
//
//                $seatsArr = array();
//                foreach ($seats as $seat) {
//                    $seatsArr[] = $seat['view_key'];
//                }
//
//                try {
//                    $dom = Map::factory()->loadFile($afisha->filename)->getDomInstance();
//
//                    $gTag = $dom->getElementsByTagName('g');
//
//                    foreach ($gTag as $el) {
//                        $id = $el->getAttribute('id');
//                        if (in_array($id, $seatsArr)) {
//                            if($el->parentNode->hasAttribute('data-plase'))
//                            {
//                                $place = $el->parentNode->getAttribute('data-plase');
//                            }
//                            elseif ($el->parentNode->parentNode->hasAttribute('data-plase'))
//                            {
//                                $place = $el->parentNode->parentNode->getAttribute('data-plase');
//                            }
//
//                            if ($place) {
//                                $place = str_replace('(левая сторона)', '(лев. сторона)', $place);
//                                $place = str_replace('(правая сторона)', '(пр. сторона)', $place);
//                                $seatsStr[$id] = str_replace(array('места', 'Места'), 'место', $place);
//
//                                $dataInit = json_decode($el->getAttribute('data-init'));
//                                $seatsStr[$id] .= $dataInit->seat;
//
//                            }
//                        }
//                    }
//                } catch(\Exception $e) {
//                    die('Ошибка загрузки карты');
//                }
//            }
//            else {
//                $innerMap = '';
//                $afisha = Arr::to_object(array());
//            }
//            $map = View::tpl(array('map' => $innerMap), 'Map/Main');
//
//            $this->_seo['h1'] = 'Заказ №' . Route::param('id');
//            $this->_seo['title'] = 'Заказ №' . Route::param('id');
//            $this->setBreadcrumbs('Заказ №' . Route::param('id'), 'backend/afisha_orders/edit/'.(int) Route::param('id'));
//
//            $this->_content = View::tpl(
//                array(
//                    'obj' => $result,
//                    'afisha' => $afisha,
//                    'map' => $map,
//                    'pay_statuses' => $this->pay_statuses,
//                    'seat_statuses' => $this->seat_statuses,
//                    'tpl_folder' => $this->tpl_folder,
//                    'tablename' => $this->tablename,
//                    'seatsStr' => $seatsStr,
//                ), $this->tpl_folder.'/Inner');
//        }
//
//        function deleteAction() {
//            $id = (int) Route::param('id');
//            if(!$id) {
//                Message::GetMessage(0, 'Данные не существуют!');
//                HTTP::redirect('backend/afisha_orders/index');
//            }
//            $page = DB::select()->from($this->tablename)->where('id', '=', $id)->find();
//            if(!$page) {
//                Message::GetMessage(0, 'Данные не существуют!');
//                HTTP::redirect('backend/afisha_orders/index');
//            }
//            // Change statuses for all places
//            $viewKeys = array_filter(explode(',', $page->seats_keys));
//            if (count($viewKeys)) {
//                $prices = DB::select()->from('prices')->where('afisha_id', '=', $page->afisha_id)->find_all();
//                if (count($prices)) {
//                    $pricesIds = array();
//                    foreach ($prices as $key => $value) {
//                        $pricesIds[] = $value->id;
//                    }
//                    Common::update('seats', array('status' => 1, 'reserved_at' => null))
//                        ->where('view_key', 'IN', $viewKeys)
//                        ->where('price_id', 'IN', $pricesIds)
//                        ->execute();
//                }
//            }
//            DB::delete($this->tablename)->where('id', '=', $id)->execute();
//
//            Message::GetMessage(1, 'Данные удалены!');
//            HTTP::redirect('backend/afisha_orders/index');
//        }
//
//        function printAction()
//        {
//            if (\Core\User::get_access_for_controller('order_print') != 'edit') {
//                $this->no_access();
//            }
//            $seats = (array) $_POST['SEATS'];
//            $printType = $_POST['print-type'] ? $_POST['print-type'] : 'base';
//            if (count($seats) == 0) {
//                Message::GetMessage(0, 'Места не выбраны!');
//                HTTP::redirect('backend/afisha_orders/edit/'.Route::param('id'));
//            }
//
//            $order = DB::select()
//                ->from($this->tablename)
//                ->where('id', '=', Route::param('id'))
//                ->find();
//            if (!$order) {
//                return Config::error();
//            }
//
//            $afisha = DB::select('afisha.*', array('places.name', 'place'), 'places.filename', 'places.address', 'places.city_id')
//                ->from('afisha')
//                ->join('places')
//                    ->on('afisha.place_id', '=', 'places.id')
//                ->where('afisha.id', '=', $order->afisha_id)
//                ->find();
//            if (!$afisha) {
//                return Config::error();
//            }
//
//            $city = DB::select()->from('cities')->where('id', '=', $afisha->city_id)->find();
//
//            $seatStr = array();
//            $termoSeatStr = array();
//            try {
//                $dom = Map::factory()->loadFile($afisha->filename)->getDomInstance();
//
//                $gTag = $dom->getElementsByTagName('g');
//
//                foreach ($gTag as $el) {
//                    $id = $el->getAttribute('id');
//                    if (in_array($id, $seats)) {
//                        if($el->parentNode->hasAttribute('data-plase'))
//                        {
//                            $originalPlace = $el->parentNode->getAttribute('data-plase');
//                        }
//                        elseif ($el->parentNode->parentNode->hasAttribute('data-plase'))
//                        {
//                            $originalPlace = $el->parentNode->parentNode->getAttribute('data-plase');
//                        }
//
//                        if ($originalPlace) {
//                            $place = str_replace('(левая сторона)', '(лев. сторона)', $originalPlace);
//                            $place = str_replace('(правая сторона)', '(пр. сторона)', $place);
//                            $place = str_replace(',', '<br />', $place);
//                            $place = str_replace('ряд', 'ряд:', $place);
//                            $seatStr[$id] = str_replace(array('места', 'Места'), 'место', $place);
//
//                            $dataInit = json_decode($el->getAttribute('data-init'));
//                            $seatStr[$id] .= $dataInit->seat;
//
////                          For termo print
//                            preg_match('#^(.*)?, ряд ([0-9]+)#', $originalPlace, $matches);
//
//                            $termoSeatStr[$id]['block'] = $matches[1];
//                            $termoSeatStr[$id]['row'] = $matches[2];
//                            $termoSeatStr[$id]['seat'] = $dataInit->seat;
//
//                            $termoSeatStr[$id]['block'] = str_replace('(левая сторона)', '(лев. сторона)', $termoSeatStr[$id]['block']);
//                            $termoSeatStr[$id]['block'] = str_replace('(правая сторона)', '(пр. сторона)', $termoSeatStr[$id]['block']);
//
//
//                        }
//                    }
//                }
//            } catch(\Exception $e) {
//                die('Ошибка загрузки карты');
//            }
//
//            $tickets = array();
//            foreach ($seats as $seat) {
//                if (User::info()->role_id != 2 && User::get_access_for_controller('afisha_print_unlimit') == 'edit'
//                    && strpos($order->printed_seats, $seat) !== false) continue;
//                $priceRow = DB::select('price')
//                    ->from('prices')
//                    ->join('seats', 'LEFT')
//                        ->on('prices.id', '=', 'seats.price_id')
//                    ->where('afisha_id', '=', $order->afisha_id)
//                    ->where('seats.view_key', '=', $seat)->find();
//
//
//                $tickets[] = Arr::to_object(array(
//                    'event_name' => $afisha->name,
//                    'print_name' => $afisha->print_name,
//                    'print_name_small' => $afisha->print_name_small,
//                    'event_date' => date('d', $afisha->event_date). ' ' . Dates::month(date('m', $afisha->event_date)) . ' ' . date('Y', $afisha->event_date). ' в '.$afisha->event_time,
//                    'event_place' => $afisha->place,
//                    'event_just_date' => date('j', $afisha->event_date). ' ' . Dates::month(date('m', $afisha->event_date)) . ' ' . date('Y', $afisha->event_date),
//                    'event_time' => $afisha->event_time,
//                    'event_address' => $afisha->address,
//                    'place_string' => $seatStr[$seat],
//                    'place_block' => $termoSeatStr[$seat]['block'],
//                    'place_row' => $termoSeatStr[$seat]['row'],
//                    'place_seat' => $termoSeatStr[$seat]['seat'],
//                    'price' => $priceRow->price,
//                    'phone' => $city->phone,
//                    'barcode' => $afisha->id.'-'.$order->id.'-'.$seat,
//                ));
//            }
//
////            Update print seats keys
//            if (User::info()->role_id != 2 && User::get_access_for_controller('afisha_print_unlimit') == 'edit') {
//                $oldSeats = $order->printed_seats;
//                $newSeats = array();
//                if (strlen($oldSeats)) {
//                    $oldSeats = explode(',', $oldSeats);
//                    if (count($oldSeats)) {
//                        $newSeats = (array) $oldSeats;
//                    }
//                }
//                foreach ($seats as $seat) {
//                    $newSeats[] = $seat;
//                }
//                $newSeats = array_unique($newSeats);
//                $newSeats = implode(',', $newSeats);
//                DB::update($this->tablename)->set(array('printed_seats' => $newSeats))->execute();
//            }
//
//            if ($printType == 'base') {
//                echo View::tpl(array('tickets' => $tickets), 'Afisha_orders/Print');
//            } else {
//                echo View::tpl(array('tickets' => $tickets), 'Afisha_orders/PrintTermo');
//            }
//            die();
//        }

    }