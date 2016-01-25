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
                ->where('creator_id', '!=', null)->where('payer_id', '!=', null);
            $this->setFilter($totalCntOrders, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
            $totalCntOrders = $totalCntOrders->count_all();

            $totalOrdersPrice = 0;
            $totalOrdersPriceQuery = DB::select()->from('afisha_orders')->where('creator_id', '!=', null)->where('payer_id', '!=', null);
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
                    ->where('payer_id', '=', $cassier->id);
                $this->setFilter($allOrders, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
                $allOrders = $allOrders->count_all();
                $fullResult[$cassier->id]['totalOrders'] = $allOrders;

                $successOrders = DB::select(array(DB::expr('COUNT(*)'), 'count'))->from('afisha_orders')
                    ->where('payer_id', '=', $cassier->id)->where('status', '=', 'success');
                $this->setFilter($successOrders, $date_s, $date_po, $status, $eventId, $creatorId, 'afisha_orders');
                $successOrders = $successOrders->count_all();
                $fullResult[$cassier->id]['totalSuccessOrders'] = $successOrders;

                $orders = DB::select()->from('afisha_orders')->where('payer_id', '=', $cassier->id);
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

            $ordersQuery = DB::select()->from('afisha_orders')->where('payer_id', '=', $cassier->id);
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
                $query->where( $table.'.payer_id', '=', $creatorId );

            return $query;
        }
    }