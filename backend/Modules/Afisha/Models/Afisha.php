<?php 
	namespace backend\Modules\Afisha\Models;

	use Core\QB\DB;

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

	}