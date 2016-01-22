<?php
/**
 * Created by PhpStorm.
 * User: nikolaev
 * Date: 22.01.2016
 * Time: 10:16
 */
namespace backend\Modules\Statistics\Models;

use Core\QB\DB;
use backend\Modules\Afisha\Models\Prices;
use backend\Modules\Afisha\Models\Orders;
use backend\Modules\Afisha\Models\Seats;

class Organizer
{
    public static function getOrganizers($status = null, $order_by = null)
    {
        $query = DB::select()->from('users')->where('role_id', '=', 9);
        if (null !== $status) {
            $query->where('status', '=', (int)$status);
        }
        if (null !== $order_by) {
            if (is_array($order_by) && count($order_by) == 2) {
                $query->order_by($order_by[0], $order_by[1]);
            } else {
                $query->order_by((string)$order_by);
            }
        }

        return $query->find_all();
    }

    public static function getOrganizerById($id, $status = null)
    {
        $query = DB::select()->from('users')->where('role_id', '=', 9)->where('id', '=', $id);
        if (null !== $status) {
            $query->where('status', '=', (int)$status);
        }

        return $query->find();
    }

    public static function getPosters(Array $params = array())
    {
        $query = DB::select()->from('afisha');
        if ($params['date_s']) {
            $query->where('created_at', '>=', (int)$params['date_s']);
        }
        if ($params['date_po']) {
            $query->where('created_at', '<=', (int)$params['date_po']);
        }
        if ($params['status']) {
            $query->where('status', '=', (int)$params['status']);
        }
        if ($params['event_id']) {
            $query->where('id', '=', (int)$params['event_id']);
        }
        if ($params['organizer_id']) {
            $query->where('organizer_id', '=', (int)$params['organizer_id']);
        }
        if ($params['order']) {
            if (is_array($params['order']) && count($params['order']) == 2) {
                $query->order_by((string)$params['order'][0], (string)$params['order'][1]);
            } else {
                $query->order_by((string)$params['order']);
            }
        }
        if ($params['group_by']) {
            $query->group_by((string)$params['group_by']);
        }

        return $query->find_all();
    }

    /*
     * Calculate detailed statistics for poster
     */
    public static function getDetailed($poster)
    {
        $prices = Prices::getList(array('afisha_id' => $poster->id, 'order_by' => 'price'));

        if (count($prices) == 0) {
            return null;
        }

        $adminOrders = Orders::getList(array('afisha_id' => $poster->id, 'admin_brone' => 0, 'creator_id' => 1));
        $siteOrders = Orders::getList(array('afisha_id' => $poster->id, 'creator_id' => null));

        $seatsByPrices = Seats::getList(array('grouping' => 'price_id'));
        $result = array();
        foreach ($prices as $key => $price) {
            $result[$key]['price'] = $price;

            $arrPriceSeats = array();
            foreach($seatsByPrices[$price->id] as $k => $v) {
                $arrPriceSeats[] = $v->view_key;
            }
            $soldQuantity = 0;

//            Coming
            $result[$key]['coming_quantity'] = count($arrPriceSeats);
            $result[$key]['coming_sum'] = count($arrPriceSeats) * $price->price;

//            Admin brone
            $result[$key]['admin_brone_quantity'] = 0;
            foreach ($adminOrders as $k => $v) {
                $seats = array_filter(explode(',', $v->seats_keys));
                foreach ((array)$seats as $seat) {
                    if (in_array($seat, $arrPriceSeats)) {
                        $result[$key]['admin_brone_quantity']++;
                        if ($v->status == 'success')
                            $soldQuantity++;
                    }
                }
            }
            $result[$key]['admin_brone_sum'] = $result[$key]['admin_brone_quantity'] * $price->price;

//            Site brone
            $result[$key]['site_brone_quantity'] = 0;
            foreach ($siteOrders as $k => $v) {
                $seats = array_filter(explode(',', $v->seats_keys));
                foreach ((array)$seats as $seat) {
                    if (in_array($seat, $arrPriceSeats)) {
                        $result[$key]['site_brone_quantity']++;
                        if ($v->status == 'success')
                            $soldQuantity++;
                    }
                }
            }
            $result[$key]['site_brone_sum'] = $result[$key]['site_brone_sum'] * $price->price;

//            Residue
            $result[$key]['residue_quantity'] = $result[$key]['coming_quantity'] - $result[$key]['admin_brone_quantity'] + $result[$key]['site_brone_quantity'];
            $result[$key]['residue_sum'] = $result[$key]['residue_quantity'] * $price->price;

//            Sold
            $result[$key]['sold_quantity'] = $soldQuantity;
            $result[$key]['sold_sum'] = $soldQuantity * $price->price;
        }

        return $result;
    }
}
