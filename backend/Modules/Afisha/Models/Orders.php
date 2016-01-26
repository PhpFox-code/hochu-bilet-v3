<?php
/**
 * Created by PhpStorm.
 * User: nikolaev
 * Date: 22.01.2016
 * Time: 12:33
 */

namespace backend\Modules\Afisha\Models;

use Core\QB\DB;

class Orders
{
    private static $table = 'afisha_orders';

    public static function getList($filter = array())
    {
        $query = DB::select()->from(self::$table);
        if (count($filter)) {
            foreach ($filter as $key => $value) {
                if (in_array($key, array('grouping', 'as_array'))) continue;
                $query->where($key, '=', $value);
            }
        }

        if ($filter['grouping']) {
            $result = array();
            foreach($query->find_all() as $obj) {
                $result[$obj->{$filter['grouping']}][] = $obj;
            }
            return $result;
        } else {
            if ($filter['as_array']) {
                return $query->as_assoc()->execute();
            } {
                return $query->find_all();
            }
        }
    }
}