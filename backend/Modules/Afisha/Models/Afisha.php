<?php 
	namespace backend\Modules\Afisha\Models;

	use Core\QB\DB;

	class Afisha 
	{


		public static function getTotalCost($afisha){
			$seatsKeys = array_filter(explode(',', $afisha->seats_keys));
			if (count($seatsKeys) == 0) {
				return 0;
			}
			$cost = 0;
			foreach ($seatsKeys as $key) {
				$res = DB::select('price')
					->from('prices')
					->join('seats', 'LEFT')
						->on('prices.id', '=', 'seats.price_id')
					->where('seats.view_key', '=', $key)
					->where('prices.afisha_id', '=', $afisha->afisha_id)
					->find();
				if ($res) {
					$cost += $res->price;
				}
			}
			return $cost;

		}
	}