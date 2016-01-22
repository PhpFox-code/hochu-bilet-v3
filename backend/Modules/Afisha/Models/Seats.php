<?php
/**
 * Created by PhpStorm.
 * User: nikolaev
 * Date: 22.01.2016
 * Time: 13:23
 */

namespace backend\Modules\Afisha\Models;

use Core\QB\DB;

class Seats
{
    private static $table = 'seats';

    public static function getList($filter = array())
    {
        $query = DB::select()->from(static::$table);
        if ($filter['id']) {
            $query->where('id', '=', $filter['id']);
        }
        if ($filter['view_key']) {
            $query->where('view_key', '=', $filter['view_key']);
        }
        if ($filter['price_id']) {
            $query->where('price_id', '=', $filter['price_id']);
        }
        if ($filter['status']) {
            $query->where('status', '=', $filter['status']);
        }

        if ($filter['order_by'] && count($filter['order_by']) == 2) {
            $query->order_by((string)$filter['order_by'][0], (string)$filter['order_by'][1]);
        } elseif ($filter['order_by']) {
            $query->order_by((string)$filter['order_by']);
        }

        if ($filter['grouping']) {
            $result = array();
            foreach($query->find_all() as $obj) {
                $result[$obj->{$filter['grouping']}][] = $obj;
            }
            return $result;
        } else {
            return $query->find_all();
        }
    }
}