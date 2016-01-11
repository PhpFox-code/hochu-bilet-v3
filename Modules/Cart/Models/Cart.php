<?php
    namespace Modules\Cart\Models;
    use Core\QB\DB;
    use Core\HTML;
    use Core\Cookie;
    use Core\Arr;
    use Core\Text;
    
    class Cart {
        
        static    $_instance;

        public    $_cart_id = 0; // Cart ID in our system
        public    $_cart = array(); // Items in our cart
        public    $_count_goods = 0; // Count of items in our cart

        function __construct() {
            $this->set_cart_id();
            $this->check_cart();
            $this->recount();
        }

        static function factory() {
            if(self::$_instance == NULL) { self::$_instance = new self(); }
            return self::$_instance;
        }


        // Get goods list for basket from database
        public function get_list_for_basket() {
            $ids = $sids = array();
            foreach($this->_cart AS $key => $item) { 
                $ids[] = $item['id'];
                $sids[] = $item['size'];
            }
            if(!count($ids)) { $ids = array(0); }
            if(!count($sids)) { $sids = array(-1); }
            $result = DB::select('catalog.*', array('catalog_images.image', 'image'), array('sizes.id', 'size_id'), array('sizes.name', 'size_name'))
                        ->from('catalog')
                        ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('1'))
                        ->join('carts_items', 'LEFT')->on('carts_items.catalog_id', '=', 'catalog.id')
                        ->join('sizes', 'LEFT')->on('sizes.id', '=', 'carts_items.size_id')->on('sizes.status', '=', DB::expr('1'))
                        ->where('carts_items.cart_id', '=', $this->_cart_id)
                        ->where('catalog.status', '=', 1)
                        ->where('catalog.id', 'IN', $ids)
                        ->where('carts_items.size_id', 'IN', $sids)
                        ->as_object()->execute();
            $basket = $this->_cart;
            foreach($result AS $obj) {
                $basket[$obj->id . '-' . (int) $obj->size_id]['obj'] = $obj;
            }
            return $basket;
        }


        // Setting cart ID
        public function set_cart_id(){
            // Check cookie for existance of the cart
            $hash = Cookie::get('cart');
            if( !$hash ) { return $this->create_cart(); }
//          Remove old cart rows
            DB::delete('carts')->where('created_at', '<=', time() - 3600)->execute();
            // Check if our cookie not bad
            $cart = DB::select()->from('carts')->where('hash', '=', $hash)->as_object()->execute()->current();
            if( !$cart ) { return $this->create_cart(); }
            // Set cart_id
            $this->_cart_id = $cart->id;
            return true;
        }


        // Creation of the new cart
        public function create_cart() {
            // Generate hash of new cart for cookie
            $hash = sha1(microtime().Text::random());
            // Save cart into database
            $this->_cart_id = Arr::get(DB::insert('carts', array('hash', 'created_at'))->values(array($hash, time()))->execute(), 0);
            // Save cart to cookie
            Cookie::set('cart', $hash, 60*60*24*365);
            return true;
        }

        
        // Check existance of cart
        public function check_cart() {
            if(!$this->_cart_id) { return false; }
            $result = DB::select(array('carts_items.catalog_id', 'catalog_id'), array('carts_items.size_id', 'size_id'), array('carts_items.count', 'count'))
                        ->from('carts_items')
                        ->join('catalog', 'LEFT')->on('catalog.id', '=', 'carts_items.catalog_id')
                        ->join('sizes', 'LEFT')->on('sizes.id', '=', 'carts_items.size_id')->on('sizes.status', '=', DB::expr('1'))
                        ->where('catalog.status', '=', 1)
                        ->where('carts_items.cart_id', '=', $this->_cart_id)
                        ->order_by('carts_items.id', 'DESC')
                        ->as_object()->execute();

            foreach($result AS $obj) {
                $this->_cart[$obj->catalog_id . '-' . $obj->size_id] = Array(
                    "id" => $obj->catalog_id,
                    "size" => $obj->size_id,
                    "count" => $obj->count,
                );
            }
            return true;
        }


        // Count goods in cart
        public function recount() {
            $count = 0;
            foreach($this->_cart AS $b) {
                $count += (int) $b['count'];
            }
            $this->_count_goods = $count;
        }

        
        // Get full cost of all cart
        public function get_summa() {
            $summa = 0;
            if(empty($this->_cart)) { return 0; }
            $ids = array();
            foreach($this->_cart AS $b) {
                if(!in_array($b['id'], $ids)) {
                    $ids[] = $b['id'];
                }
            }
            $result = DB::select('cost', 'id')->from("catalog")->where("status", "=", 1)->where("id", "IN", $ids)->as_object()->execute();
            $items = array();
            foreach($result AS $obj) {
                $items[$obj->id] = $obj;
            }
            foreach($this->_cart AS $b) {
                $summa += $items[$b['id']]->cost * $b['count'];
            }
            return $summa;
        }

        
        /**
         *      Add goods to cart
         *      @param int $catalog_id - goods ID
         *      @param int $count - count goods in the cart
         */
        public function add($catalog_id, $size_id, $count = 1) {
            if(!Arr::get($this->_cart, $catalog_id . '-' . $size_id, false)) {
                $this->_cart[$catalog_id . '-' . $size_id] = array(
                    'id' => $catalog_id,
                    'size' => $size_id,
                    'count' => $count,
                );
                DB::insert('carts_items', array('catalog_id', 'size_id', 'cart_id', 'count'))
                    ->values(array($catalog_id, $size_id, $this->_cart_id, $count))
                    ->execute();
                $this->recount();
                return true;
            }
            foreach($this->_cart AS $key => $item) {
                if($item['id'] == $catalog_id AND $item['size'] == $size_id) {
                    $this->_cart[$key]['count'] = $this->_cart[$key]['count'] + $count;
                    DB::update('carts_items')
                        ->set(array('count' => $this->_cart[$key]['count']))
                        ->where('cart_id', '=', $this->_cart_id)
                        ->where('catalog_id', '=', $catalog_id)
                        ->where('size_id', '=', $size_id)
                        ->execute();
                    $this->recount();
                    return true;
                }
            }
            return false;
        }

        
        /**
         *      Change count in the cart
         *      @param int $catalog_id - goods ID
         *      @param int $count - new count in the cart
         */
        public function edit($catalog_id, $size_id, $count) {
            if(Arr::get($this->_cart, $catalog_id . '-' . $size_id, false)) {
                $this->_cart[$catalog_id . '-' . $size_id]['count'] = $count;
                DB::update('carts_items')
                    ->set(array('count' => $count))
                    ->where('cart_id', '=', $this->_cart_id)
                    ->where('catalog_id', '=', $catalog_id)
                    ->where('size_id', '=', $size_id)
                    ->execute();
                $this->recount();
                return true;
            }
            return false;
        }

        
        /**
         *      Delete goods from the cart
         *      @param $catalog_id - goods ID
         */
        public function delete($catalog_id, $size_id) {
            if(Arr::get($this->_cart, $catalog_id . '-' . $size_id, false)) {
                unset($this->_cart[$catalog_id . '-' . $size_id]);
                DB::delete('carts_items')
                    ->where("catalog_id", "=", $catalog_id)
                    ->where("size_id", "=", $size_id)
                    ->where("cart_id", "=", $this->_cart_id)
                    ->execute();
                $this->recount();
            }
        }
        

        // Total cleaning of the cart
        public function clear() {
            DB::delete('carts')->where("id", "=", $this->_cart_id)->execute();
            $this->_cart_id = 0;
            $this->_cart = Array();
            Cookie::delete('cart');
            $this->recount();
        }

    }