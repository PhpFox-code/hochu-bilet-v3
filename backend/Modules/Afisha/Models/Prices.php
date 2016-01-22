<?php
/**
 * Created by PhpStorm.
 * User: nikolaev
 * Date: 22.01.2016
 * Time: 12:28
 */

namespace backend\Modules\Afisha\Models;

use Core\QB\DB;

class Prices
{
    private static $table = 'prices';

    public static function getList($filter = null)
    {
        $query = DB::select()->from(self::$table);
        if ($filter['id']) {
            $query->where('id', '=', $filter['id']);
        }
        if ($filter['afisha_id']) {
            $query->where('afisha_id', '=', $filter['afisha_id']);
        }
        if ($filter['price']) {
            $query->where('price', '=', $filter['price']);
        }

        if ($filter['order_by'] && count($filter['order_by']) == 2) {
            $query->order_by((string)$filter['order_by'][0], (string)$filter['order_by'][1]);
        } elseif ($filter['order_by']) {
            $query->order_by((string)$filter['order_by']);
        }

        return $query->find_all();
    }
}