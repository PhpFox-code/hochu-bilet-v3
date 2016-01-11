<?php
    namespace Modules\Catalog\Models;
    use Core\Cookie;
    
    class Catalog {

        public static function factory() {
            return new Catalog();
        }

        public function addViewed( $id ) {
            $ids = $this->getViewedIDs();
            if( !in_array($id, $ids) ) {
                $ids[] = $id;
                $json = json_encode($ids);
                $base64 = base64_encode($json);
                Cookie::set('viewed', $base64, 60*60*24*30);
                setcookie('viewed', $base64, 60*60*24*30, '/');
            }
            return;
        }

        public function getViewedIDs() {
            $ids = Cookie::get('viewed');
            if(!$ids) {
                $ids = array();
            } else {
                $ids = base64_decode($ids);
                $ids = json_decode($ids);
                if(!$ids) {
                    $ids = array();
                } else if( !is_array($ids) ) {
                    $ids = array();
                }
            }
            return $ids;
        }


        // public function redirect_if_bad_filter_link() {
        //     $filter = Arr::get($_GET, 'filter_array', array());
        //     if(!$filter) { return false; }
        //     $arr = array();
        //     foreach($filter AS $key => $val) {
        //         $arr[] = $key;
        //     }
        //     $nFilter = array();
        //     if(Arr::get($filter, 'brand')) {
        //         $list['brand'] = Builder::factory('brand')->where('alias', 'IN', Arr::get($filter, 'brand'))->order_by('name', 'ASC')->find_all();
        //         $vals = $this->get_filter_link_values_in_the_best_order('brand', $list);
        //         if($vals) {
        //             $nFilter['brand'] = $vals;
        //         }
        //         unset($filter['brand']);
        //     }
        //     if(Arr::get($filter, 'collections')) {
        //         $list['collections'] = Builder::factory('series')->where('alias', 'IN', Arr::get($filter, 'collections'))->order_by('name', 'ASC')->find_all();
        //         $vals = $this->get_filter_link_values_in_the_best_order('collections', $list);
        //         if($vals) {
        //             $nFilter['collections'] = $vals;
        //         }
        //         unset($filter['collections']);

        //     }
        //     $res = Builder::factory('specifications')->where('alias', 'IN', $arr)->order_by('name', 'ASC')->find_all();
        //     $ids = array();
        //     $al  = array();
        //     foreach($res AS $obj) { $ids[] = $obj->id; $al[$obj->id] = $obj->alias; }
        //     $spec = array();
        //     foreach($filter AS $_f) {
        //         foreach($_f AS $__f) {
        //             $spec[] = $__f;
        //         }
        //     }
        //     $_res = Builder::factory('specifications_values')->where('id_specifications', 'IN', $ids)->where('alias', 'IN', $spec)->order_by('name', 'ASC')->find_all();
        //     $list = array();
        //     foreach($_res AS $obj) {
        //         $list[$al[$obj->id_specifications]][] = $obj;
        //     }
        //     foreach($res AS $obj) {
        //         $vals = $this->get_filter_link_values_in_the_best_order($obj->alias, $list);
        //         if($vals) {
        //             $nFilter[$obj->alias] = $vals;
        //         }
        //     }
        //     $f = "";
        //     foreach($nFilter AS $key => $vals) {
        //         $f .= $key."=".$vals.'&';
        //     }
        //     $nFilter = substr($f, 0, -1);
        //     if(!$nFilter) {
        //         location(str_replace('/filter/'.Arr::get($_GET, 'filter'), '', Arr::get($_SERVER, 'REQUEST_URI')));
        //     }
        //     if($nFilter != Arr::get($_GET, 'filter')) {
        //         location(str_replace(Arr::get($_GET, 'filter'), $nFilter, Arr::get($_SERVER, 'REQUEST_URI')));
        //     }
        // }

        // public function get_filter_link_values_in_the_best_order($group, $list) {
        //     $filter = Arr::get(Arr::get($_GET, 'filter_array'), $group);
        //     $list = Arr::get($list, $group);
        //     if(!$filter || !$list) { return false; }
        //     $vals = array();
        //     foreach($list AS $obj) {
        //         $vals[] = $obj->alias;
        //     }
        //     return implode(',', $vals);
        // }

    }