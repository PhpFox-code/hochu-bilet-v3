<?php
    namespace Backend\Modules\Afisha\Controllers;

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


    class Orders extends \Backend\Modules\Base {

        public $tpl_folder = 'Afisha_orders';
        public $tablename  = 'afisha_orders';
        public $pay_statuses;
        public $seat_statuses;
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Заказы';
            $this->_seo['title'] = 'Заказы';
            $this->setBreadcrumbs('Заказы', 'backend/afisha_orders/index');
            $this->limit = Config::get( 'limit_backend' );
            $this->pay_statuses = Config::get('order.pay_statuses');
            $this->seat_statuses = Config::get('order.seat_statuses');
        }


        function indexAction () {
            $date_s = NULL; $date_po = NULL; $status = NULL; $eventId = null; $creatorId = null;

            if ( Arr::get($_GET, 'date_s') )
                $date_s = strtotime( Arr::get($_GET, 'date_s') );
            if ( Arr::get($_GET, 'date_po') )
                $date_po = strtotime( Arr::get($_GET, 'date_po') );
            if ( isset( $this->pay_statuses[ Arr::get($_GET, 'status') ] ) )
                $status = Arr::get($_GET, 'status');
            if (Arr::get($_GET, 'event') != 0)
                $eventId = Arr::get($_GET, 'event');
            if (Arr::get($_GET, 'creator_id') != 0)
                $creatorId = Arr::get($_GET, 'creator_id');

//            Count
            $count = DB::select(array(DB::expr('COUNT(*)'), 'count'))->from($this->tablename);
            if( $date_s !== NULL ) { $count->where( $this->tablename.'.created_at', '>=', $date_s ); }
            if( $date_po !== NULL ) { $count->where( $this->tablename.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            if( $status !== NULL ) {
                switch ($status) {
                    case 'brone':
                        $count->where($this->tablename.'.created_at', '>', time() - Config::get('reserved_days') * 24 * 60 * 60)
                            ->where($this->tablename.'.status', '!=', 'success');
                        break;
                    case 'expired':
                        $count->where($this->tablename.'.created_at', '<', time() - Config::get('reserved_days') * 24 * 60 * 60)
                            ->where($this->tablename.'.status', '!=', 'success');
                        break;
                    case 'success':
                        $count->where( $this->tablename.'.status', '=', $status );
                        break;
                }
            }
            if ($eventId) { $count->where( $this->tablename.'.afisha_id', '=', $eventId ); }
            if ($creatorId) { $count->where( $this->tablename.'.creator_id', '=', $creatorId ); }
            $count = $count->count_all();

//            Pager
            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $pager = Pager::factory( $page, $count, $this->limit )->create();

//            Result
            $result = DB::select($this->tablename.'.*', array('users.name', 'creator_name'))->from($this->tablename)
                ->join('users', 'LEFT OUTER')
                    ->on('users.id', '=', $this->tablename.'.creator_id');
            if( $date_s ) { $result->where( $this->tablename.'.created_at', '>=', $date_s ); }
            if( $date_po ) { $result->where( $this->tablename.'.created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            if (User::info()->role_id != 2) { $result->where($this->tablename.'.creator_id', '=', User::info()->id);}
            if( $status !== NULL ) {
                switch ($status) {
                    case 'brone':
                        $result->where($this->tablename.'.created_at', '>', time() - Config::get('reserved_days') * 24 * 60 * 60)
                            ->where($this->tablename.'.status', '!=', 'success');
                        break;
                    case 'expired':
                        $result->where($this->tablename.'.created_at', '<', time() - Config::get('reserved_days') * 24 * 60 * 60)
                            ->where($this->tablename.'.status', '!=', 'success');
                        break;
                    case 'success':
                        $result->where( $this->tablename.'.status', '=', $status );
                        break;
                }
            }
            if ($eventId)
                $result->where( $this->tablename.'.afisha_id', '=', $eventId );
            if ($creatorId)
                $result->where( $this->tablename.'.creator_id', '=', $creatorId );
            $result = $result->order_by($this->tablename.'.created_at', 'DESC')
                ->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();

//            Creators
            $creators = DB::select()->from('users')->where('status', '=', 1)->find_all();

//            Render
            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'pager' => $pager,
                    'status' => $status,
                    'date_s' => $date_s,
                    'date_po' => $date_po,
                    'pay_statuses' => $this->pay_statuses,
                    'count' => $count,
                    'events' => DB::select()->from('afisha')->where('place_id', 'IS NOT', null)->find_all(),
                    'creators' => $creators,
                ),$this->tpl_folder.'/Index');
        }

        function editAction() {
            $result = DB::select()
                ->from($this->tablename)
                ->where('id', '=', Route::param('id'))
                ->find();

//            Set edit access for myself orders
            if ($result->creator_id == User::info()->id) {
                User::factory()->_current_access = 'edit';
            }

            $afisha = DB::select('afisha.*', array('places.name', 'place'), 'places.filename')
                ->from('afisha')
                ->join('places')
                    ->on('afisha.place_id', '=', 'places.id')
                ->where('afisha.id', '=', $result->afisha_id)
                ->find();

            // Generate and parse inner map
            $orderSeats = array();
            
            $viewKeys = array_filter(explode(',', $result->seats_keys));
            if (count($viewKeys)) {
                $prices = DB::select()->from('prices')->where('afisha_id', '=', $result->afisha_id)->find_all();
                if (count($prices)) {
                    $pricesIds = array();
                    foreach ($prices as $key => $value) {
                        $pricesIds[] = $value->id;
                    }
                    $seatsQuery = DB::select()
                        ->from('seats')
                        ->where('view_key', 'IN', $viewKeys)
                        ->where('price_id', 'IN', $pricesIds)
                        ->execute()
                        ->as_array();

                    foreach ($seatsQuery as $key => $value) {
                        $orderSeats[$value['view_key']] = $value;
                    }
                }
            }

            $seatsStr = array();
            if ($afisha) {
                $seats = \Modules\Afisha\Models\Afisha::getMapSeats($result->afisha_id);
                $mapObj = Map::factory()->loadFile($afisha->filename);
                $innerMap = $mapObj->parseDomOrder($orderSeats, $seats, true, true);

                $seatsArr = array();
                foreach ($seats as $seat) {
                    $seatsArr[] = $seat['view_key'];
                }

                try {
                    $dom = Map::factory()->loadFile($afisha->filename)->getDomInstance();
                    
                    $gTag = $dom->getElementsByTagName('g');

                    foreach ($gTag as $el) {
                        $id = $el->getAttribute('id');
                        if (in_array($id, $seatsArr)) {
                            if($el->parentNode->hasAttribute('data-plase'))
                            {
                                $place = $el->parentNode->getAttribute('data-plase');
                            } 
                            elseif ($el->parentNode->parentNode->hasAttribute('data-plase')) 
                            {
                                $place = $el->parentNode->parentNode->getAttribute('data-plase');
                            }

                            if ($place) {
                                $place = str_replace('(левая сторона)', '(лев. сторона)', $place);
                                $place = str_replace('(правая сторона)', '(пр. сторона)', $place);
                                $seatsStr[$id] = str_replace(array('места', 'Места'), 'место', $place);

                                $dataInit = json_decode($el->getAttribute('data-init'));
                                $seatsStr[$id] .= $dataInit->seat;

                            }
                        }
                    }
                } catch(\Exception $e) {
                    die('Ошибка загрузки карты');
                }
            }
            else {
                $innerMap = '';
                $afisha = Arr::to_object(array());
            }
            $map = View::tpl(array('map' => $innerMap), 'Map/Main');

            $this->_seo['h1'] = 'Заказ №' . Route::param('id');
            $this->_seo['title'] = 'Заказ №' . Route::param('id');
            $this->setBreadcrumbs('Заказ №' . Route::param('id'), 'backend/afisha_orders/edit/'.(int) Route::param('id'));

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'afisha' => $afisha,
                    'map' => $map,
                    'pay_statuses' => $this->pay_statuses,
                    'seat_statuses' => $this->seat_statuses,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'seatsStr' => $seatsStr,
                ), $this->tpl_folder.'/Inner');
        }

        function deleteAction() {
            $id = (int) Route::param('id');
            if(!$id) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/afisha_orders/index');
            }
            $page = DB::select()->from($this->tablename)->where('id', '=', $id)->find();
            if(!$page) {
                Message::GetMessage(0, 'Данные не существуют!');
                HTTP::redirect('backend/afisha_orders/index');
            }
            // Change statuses for all places
            $viewKeys = array_filter(explode(',', $page->seats_keys));
            if (count($viewKeys)) {
                $prices = DB::select()->from('prices')->where('afisha_id', '=', $page->afisha_id)->find_all();
                if (count($prices)) {
                    $pricesIds = array();
                    foreach ($prices as $key => $value) {
                        $pricesIds[] = $value->id;
                    }
                    Common::update('seats', array('status' => 1, 'reserved_at' => null))
                        ->where('view_key', 'IN', $viewKeys)
                        ->where('price_id', 'IN', $pricesIds)
                        ->execute();
                }
            }
            DB::delete($this->tablename)->where('id', '=', $id)->execute();

            Message::GetMessage(1, 'Данные удалены!');
            HTTP::redirect('backend/afisha_orders/index');
        }

        function printAction()
        {
            if (\Core\User::access()['order_print'] != 'edit') {
                $this->no_access();
            }
            $seats = (array) $_POST['SEATS'];
            $printType = $_POST['print-type'] ? $_POST['print-type'] : 'base';
            if (count($seats) == 0) {
                Message::GetMessage(0, 'Места не выбраны!');
                HTTP::redirect('backend/afisha_orders/edit/'.Route::param('id'));
            }

            $order = DB::select()
                ->from($this->tablename)
                ->where('id', '=', Route::param('id'))
                ->find();
            if (!$order) {
                return Config::error();
            }

            $afisha = DB::select('afisha.*', array('places.name', 'place'), 'places.filename', 'places.address', 'places.city_id')
                ->from('afisha')
                ->join('places')
                    ->on('afisha.place_id', '=', 'places.id')
                ->where('afisha.id', '=', $order->afisha_id)
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
                    if (in_array($id, $seats)) {
                        if($el->parentNode->hasAttribute('data-plase'))
                        {
                            $originalPlace = $el->parentNode->getAttribute('data-plase');
                        } 
                        elseif ($el->parentNode->parentNode->hasAttribute('data-plase')) 
                        {
                            $originalPlace = $el->parentNode->parentNode->getAttribute('data-plase');
                        }

                        if ($originalPlace) {
                            $place = str_replace('(левая сторона)', '(лев. сторона)', $originalPlace);
                            $place = str_replace('(правая сторона)', '(пр. сторона)', $place);
                            $place = str_replace(',', '<br />', $place);
                            $place = str_replace('ряд', 'ряд:', $place);
                            $seatStr[$id] = str_replace(array('места', 'Места'), 'место', $place);
                            
                            $dataInit = json_decode($el->getAttribute('data-init'));
                            $seatStr[$id] .= $dataInit->seat;

//                          For termo print
                            preg_match('#^(.*)?, ряд ([0-9]+)#', $originalPlace, $matches);

                            $termoSeatStr[$id]['block'] = $matches[1];
                            $termoSeatStr[$id]['row'] = $matches[2];
                            $termoSeatStr[$id]['seat'] = $dataInit->seat;

                            $termoSeatStr[$id]['block'] = str_replace('(левая сторона)', '(лев. сторона)', $termoSeatStr[$id]['block']);
                            $termoSeatStr[$id]['block'] = str_replace('(правая сторона)', '(пр. сторона)', $termoSeatStr[$id]['block']);


                        }
                    }
                }
            } catch(\Exception $e) {
                die('Ошибка загрузки карты');
            }
                
            $tickets = array();
            foreach ($seats as $seat) {
                if (User::info()->role_id != 2 && User::access()['afisha_print_unlimit'] == 'edit'
                    && strpos($order->printed_seats, $seat) !== false) continue;
                $priceRow = DB::select('price')
                    ->from('prices')
                    ->join('seats', 'LEFT')
                        ->on('prices.id', '=', 'seats.price_id')
                    ->where('afisha_id', '=', $order->afisha_id)
                    ->where('seats.view_key', '=', $seat)->find();


                $tickets[] = Arr::to_object(array(
                    'event_name' => $afisha->name,
                    'print_name' => $afisha->print_name,
                    'print_name_small' => $afisha->print_name_small,
                    'event_date' => date('d', $afisha->event_date). ' ' . Dates::month(date('m', $afisha->event_date)) . ' ' . date('Y', $afisha->event_date). ' в '.$afisha->event_time,
                    'event_place' => $afisha->place,
                    'event_just_date' => date('j', $afisha->event_date). ' ' . Dates::month(date('m', $afisha->event_date)) . ' ' . date('Y', $afisha->event_date),
                    'event_time' => $afisha->event_time,
                    'event_address' => $afisha->address,
                    'place_string' => $seatStr[$seat],
                    'place_block' => $termoSeatStr[$seat]['block'],
                    'place_row' => $termoSeatStr[$seat]['row'],
                    'place_seat' => $termoSeatStr[$seat]['seat'],
                    'price' => $priceRow->price,
                    'phone' => $city->phone,
                    'barcode' => $afisha->id.'-'.$order->id.'-'.$seat,
                ));
            }

//            Update print seats keys
            if (User::info()->role_id != 2 && User::access()['afisha_print_unlimit'] == 'edit') {
                $oldSeats = $order->printed_seats;
                $newSeats = array();
                if (strlen($oldSeats)) {
                    $oldSeats = explode(',', $oldSeats);
                    if (count($oldSeats)) {
                        $newSeats = (array) $oldSeats;
                    }
                }
                foreach ($seats as $seat) {
                    $newSeats[] = $seat;
                }
                $newSeats = array_unique($newSeats);
                $newSeats = implode(',', $newSeats);
                DB::update($this->tablename)->set(array('printed_seats' => $newSeats))->execute();
            }

            if ($printType == 'base') {
                echo View::tpl(array('tickets' => $tickets), 'Afisha_orders/Print');
            } else {
                echo View::tpl(array('tickets' => $tickets), 'Afisha_orders/PrintTermo');
            }
            die();
        }

    }