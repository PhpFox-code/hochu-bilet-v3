<?php 
	namespace backend\Modules\Afisha\Models;

	use Core\QB\DB;
	use Modules\Afisha\Models\Map;

	class Afisha 
	{
		private static $costs = null;


		public static function getTotalCost($afisha){
			$seatsKeys = array_filter(explode(',', $afisha->seats_keys));
			if (count($seatsKeys) == 0) {
				return 0;
			}
			$cost = 0;

			$costs = self::getCosts();

			foreach ($seatsKeys as $key) {
				$cost += $costs[$afisha->afisha_id][$key];
			}
			return $cost;

		}

		private static function getCosts()
		{
			if (null === self::$costs) {
				$res = DB::select('prices.price', 'prices.afisha_id', 'seats.view_key')
					->from('prices')
					->join('seats', 'LEFT')
					->on('prices.id', '=', 'seats.price_id')->find_all();

				if ($res->count()) {
					foreach($res as $obj) {
						self::$costs[$obj->afisha_id][$obj->view_key] = $obj->price;
					}
				}
			}

			return self::$costs;
		}

		public static function getMapSeats($fileName, Array $seats = array())
		{
			$result = array();
			try {
				$dom = Map::factory()->loadFile($fileName)->getDomInstance();

				$gTag = $dom->getElementsByTagName('g');

				foreach ($gTag as $el) {
					$id = $el->getAttribute('id');
					if (in_array($id, $seats)) {
						if($el->parentNode->hasAttribute('data-plase'))
						{
							$place = $el->parentNode->getAttribute('data-plase');
						}
						elseif ($el->parentNode->parentNode->hasAttribute('data-plase'))
						{
							$place = $el->parentNode->parentNode->getAttribute('data-plase');
						}

						if ($place) {
							$dataInit = json_decode($el->getAttribute('data-init'));
							$place = str_replace(' / места:', '', $place);
							$place = trim($place);
							$result[$id] = array(
								'row' => $place,
								'seat' => $dataInit->seat
							);
						}
					}
				}
			} catch(\Exception $e) {
				die('Ошибка загрузки карты');
			}
			return $result;
		}
	}