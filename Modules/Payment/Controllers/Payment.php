<?php 
	namespace Modules\Payment\Controllers;

	use Core\Config;
	use Core\View;
	use Core\QB\DB;
	use Core\Route;
	use Core\Common;
	use Core\HTTP;

    class Payment extends \Modules\Base {

        public function before() {
            parent::before();
            $this->setBreadcrumbs( 'Оплата', 'payment' );
            $this->_template = 'Text';
        }

        // Page with payment link
        public function indexAction() {
            // Seo
            $this->_seo['h1'] = 'Оплата';
            $this->_seo['title'] = 'Оплата';
            $this->_seo['keywords'] = 'Оплата';
            $this->_seo['description'] = 'Оплата';

            // Check
            $order = DB::select()->from('afisha_orders')->where('id', '=', (int)Route::param('id'))->find();
            if (!$order) { return Config::error(); }
            $afisha = DB::select()->from('afisha')->where('id', '=', $order->afisha_id)->find();

            $amount = 0;

            $prices = DB::select()->from('prices')->where('afisha_id', '=', $afisha->id)->find_all();
            if (count($prices)) {
                $pricesId = array();
                foreach ($prices as $key => $value) {
                    $pricesId[] = $value->id;
                }
                $seats = DB::select('prices.price')
                    ->from('seats')
                    ->join('prices', 'LEFT')
                        ->on('prices.id', '=', 'seats.price_id')
                    ->where('price_id', 'IN', $pricesId)
                    ->where('view_key', 'IN', array_filter(explode(',', $order->seats_keys)))
                    ->find_all();
                if (count($seats)) {
                    foreach ($seats as $key => $value) {
                        $amount += $value->price;
                    }
                }
            }
            $percent = Config::get('liq_pay_percent');
            if ($percent > 0) {
                $amount = $amount + ($amount / 100 * $percent);
            }

            $dataArr = array(
            	'version'     => 3,
            	'public_key'  => Config::get('public_key'),
            	'amount'      => $amount,
            	'currency'    => 'UAH',
            	'description' => 'Оплата за покупку мест на мероприятии: '.$afisha->name,
            	'order_id'    => $order->id.time(),
                'result_url'  => 'http://'.$_SERVER['HTTP_HOST'].'/payment/end/'.$order->id,
            	'server_url'  => 'http://'.$_SERVER['HTTP_HOST'].'/payment/end/'.$order->id,
            	'language'    => 'ru',
            	// 'sadbox'      => 1
            );

            $data = base64_encode(json_encode($dataArr));
            $privateKey = Config::get('private_key');

            $signature = base64_encode( sha1( $privateKey . $data . $privateKey, 1 ) );
            // Render template
            $this->_content = View::tpl( array('data' => $data, 'signature' => $signature), 'Payment/Link' );
        }

        public function endAction() {
            $orderId = (int)Route::param('id');
            if ($orderId AND isset($_POST) AND count($_POST) > 0) {

                $data = json_decode(base64_decode($_POST['data']), true);

	        	$order = DB::select()->from('afisha_orders')->where('id', '=', $orderId)->find();
	        	if (!$order) {
	        		return Config::error();
	        	}
                // update status
                if ($data['status'] && $data['status'] == 'success') {
                    Common::update('afisha_orders', array('status' => 'success', 'updated_at' => time()))->where('id', '=', $orderId)->execute();

                    // Change status for seats
                    $prices = DB::select('id')->from('prices')->where('afisha_id', '=', $order->afisha_id)->find_all();
                    $pricesArr = array();
                    if (count($prices)) {
                        foreach ($prices as $key => $value) {
                            $pricesArr[] = $value->id;
                        }
                        $res2 = \Core\Common::update('seats', array('status' => 3))
                            ->where('view_key', 'IN', array_filter(explode(',', $order->seats_keys)))
                            ->where('price_id', 'IN', $pricesArr)
                            ->execute();
                    }
                }
        	}

//        	HTTP::redirect('after_payment/');
        	return;
        }
    }
