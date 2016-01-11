<?php 
	namespace Modules\Afisha\Models;

	use Core\QB\DB;
	use Core\Email;
	use Core\Arr;

	class Afisha
	{
		public static function getItem($alias, $status = 1)
		{
			$item = DB::select(
                    'afisha.*',
                    array('places.name', 'p_name'),
                    array('places.filename', 'p_filename'),
                    array(DB::expr('MIN(prices.price)'), 'p_from'),
                    array(DB::expr('MAX(prices.price)'), 'p_to')
                )
                ->from('afisha')
                ->join('places', 'left outer')
                    ->on('afisha.place_id', '=', 'places.id')
                    ->on('places.status', '=', DB::expr($status))
                ->join('prices', 'left outer')
                    ->on('afisha.id', '=', 'prices.afisha_id')
                ->where('afisha.status', '=', $status)
                ->where('afisha.alias', '=', $alias)
                ->where('afisha.event_date', '>', DB::expr(time()))
                ->find();
            return $item;
		}

		public static function getWidgetRows()
		{
			if (isset($_SESSION['idCity'])) {
                // select places id
                $places = DB::select('id')->from('places')->where('city_id', '=', $_SESSION['idCity'])->where('status', '=', DB::expr(1))->as_object()->execute();
                $ids = array();
                foreach ($places as $key => $value) {
                    $ids[] = $value->id;
                }
                if (count($ids) == 0) {
                    $ids[] = 0;
                }
            }

			$dbObj = DB::select(
                    'afisha.*',
                    array('places.name', 'p_name'),
                    array(DB::expr('MIN(prices.price)'), 'p_from'),
                    array(DB::expr('MAX(prices.price)'), 'p_to')
                )
                ->from('afisha')
                ->join('places', 'left outer')
                    ->on('afisha.place_id', '=', 'places.id')
                    ->on('places.status', '=', DB::expr(1))
                ->join('prices', 'left outer')
                    ->on('afisha.id', '=', 'prices.afisha_id')
                ->where('afisha.status', '=', 1)
                ->where('afisha.main_show', '=', 1)
                ->where('afisha.event_date', '>', DB::expr(time()));
            
            if (isset($_SESSION['idCity'])) {
            	$dbObj->where_open()
                	->where('afisha.place_id', 'IN', $ids)
                	->or_where('afisha.city_id', '=', $_SESSION['idCity'])
                ->where_close();
            }
            $result = $dbObj->group_by('afisha.id')
                ->order_by('afisha.event_date')
                ->as_object()->execute();
            return $result;
		}

		public static function getItemPrice($obj, $array = false)
		{
			if ($array !== false) {
				$obj = Arr::to_object($obj);
			}
			if (is_null($obj->place_id)) {
				if ($obj->cost_from == $obj->cost_to)
					return $obj->cost_from.' <span class="uah">грн</span>';

				return $obj->cost_from . ' - ' . $obj->cost_to.' <span class="uah">грн</span>';
			}
			else {
				if ($obj->p_from == 0 && $obj->p_to == 0)
					return 'Билеты проданы';

				if ($obj->p_from == $obj->p_to)
				return $obj->p_from.' <span class="uah">грн</span>';

				return $obj->p_from . ' - ' . $obj->p_to .' <span class="uah">грн</span>';
			}
		}

		public static function getItemPlace($obj, $array = false)
		{
			if ($array !== false) {
				$obj = Arr::to_object($obj);
			}
			if (is_null($obj->place_id)) {
				return $obj->place_name;
			}
			else {
				return $obj->p_name;
			}
		}

		public static function getMapPrices($itemId, $arrayOneLevel = false)
		{
			// Query
			$res = DB::select()
				->from('prices')
				->where('afisha_id', '=', $itemId)
				->execute()
				->as_array();
			if (count($res))
			{
				if ($arrayOneLevel !== false)
				{
					$newArr = array();
					foreach ($res as $key => $value) 
					{
						$newArr[] = array('price' => $value['price'], 'color' => $value['color']);
					}
					return $newArr;
				}
				return $res;
			}
			return array();

		}

		public static function getMapSeats($itemId)
		{
			$result = array();
			if ($itemId == null) {
				return $result;
			}
			// Query
			$res = DB::select('seats.*', 'prices.color', 'prices.price')
				->from('seats')
				->join('prices', 'LEFT')
					->on('prices.id', '=', 'seats.price_id')
				->where('prices.afisha_id', '=', $itemId)
				->execute()
				->as_array();
			if (count($res)) {
				foreach ($res as $key => $value) {
					$result[$value['view_key']] = $value;
				}
			}
			return $result;
		} 

		public static function getPlaceList($id, $stringMode = false)
		{
			$result = array();

			$res = DB::select('seats.view_key')
				->from('seats')
				->where('price_id', '=', $id)
				->execute()
				->as_array();
			if (count($res)) {
				foreach ($res as $key => $value) {
					$result[] = $value['view_key'];
				}
			}
			if ($stringMode) {
				return implode(',', $result);
			}
			return $result;
		}

		public static function getPlaceListJson($id)
		{
			$result = array();

			$res = DB::select('view_key', 'status', 'reserved_at')
				->from('seats')
				->where('price_id', '=', $id)
				->execute()
				->as_array();
			if (count($res)) {
				foreach ($res as $key => $value) {
					$result[$value['view_key']] = $value;
				}
			}
			return json_encode($result);
		}

		public static function sendOrderMessageAdmin(array $data)
		{
			$ip = \Core\System::getRealIP();
			$mail = DB::select()->from('mail_templates')->where('status', '=', 1)->where('id', '=', 11)->find();
			if (count($mail) == 0) {
				return false;
			}
			$dataTpl = \Core\View::tpl(array('order' => $data['order'], 'order_text' => $data['order_text']), 'Orders/Email');
			$from = array( '{{site}}', '{{order_number}}', '{{link_admin}}', '{{data}}', '{{ip}}', '{{date}}', '{{event_name}}' );
            $to = array(
                Arr::get( $_SERVER, 'HTTP_HOST' ), 
                $data['id_order'], '<a href="http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/backend/afisha_orders/edit/' . $data['id_order'].'">Ссылка</a>', $dataTpl,
                $ip, date('d.m.Y H:i'), $data['order']['event_name'],
            );
            $subject = str_replace($from, $to, $mail->subject);
            $text = str_replace($from, $to, $mail->text);
            Email::send( $subject, $text );
		}

		public static function sendOrderMessageUser(array $data)
		{
			$ip = \Core\System::getRealIP();
			$mail = DB::select()->from('mail_templates')->where('status', '=', 1)->where('id', '=', 12)->find();
			if (count($mail) == 0) {
				return false;
			}
			$dataTpl = \Core\View::tpl(array('order' => $data['order'], 'order_text' => $data['order_text']), 'Orders/Email');
			$from = array( '{{site}}', '{{order_number}}', '{{link_user}}', '{{data}}', '{{ip}}', '{{date}}', '{{event_name}}' );
            $to = array(
                Arr::get( $_SERVER, 'HTTP_HOST' ), 
                $data['id_order'], '<a href="http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/payment/' . $data['id_order'].'">Ссылка</a>', $dataTpl,
                $ip, date('d.m.Y H:i'), $data['order']['event_name'],
            );
            $subject = str_replace($from, $to, $mail->subject);
            $text = str_replace($from, $to, $mail->text);
            Email::send( $subject, $text, $data['order']['email'] );
		}
	}
