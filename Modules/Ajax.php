<?php
    namespace Modules;

    use Core\Arr;
    use Core\Support AS support;
    use Modules\Cart\Models\Cart;
    use Core\HTML;
    use Core\Config;
    use Core\Dates;
    use Core\QB\DB;
    use Core\Pager\Pager;
    
    class Ajax extends Base {

        // Add item to cart
        public function addToCartAction() {
            // Get and check incoming data
            $catalog_id = Arr::get($_POST, 'id', 0);
            if( !$catalog_id ) {
                $this->error('No such item!');
            }
            $size_id = (int) Arr::get($_POST, 'size', 0);
            // Add one item to cart
            Cart::factory()->add($catalog_id, $size_id);
            $result = Cart::factory()->get_list_for_basket();
            $cart = array();
            foreach( $result AS $item ) {
                $obj = Arr::get($item, 'obj');
                if( $obj ) {
                    $cart[] = array(
                        'id' => $obj->id,
                        'size_id' => (int) $obj->size_id,
                        'name' => $obj->name . ($obj->size_name ? ', ' . $obj->size_name : ''),
                        'cost' => $obj->cost,
                        'image' => is_file(HOST.HTML::media('images/catalog/medium/'.$obj->image)) ? HTML::media('images/catalog/medium/'.$obj->image) : '',
                        'alias' => $obj->alias,
                        'count' => Arr::get($item, 'count', 1),
                    );
                }
            }
            $this->success(array('cart' => $cart));
        }


        // Edit count items in the cart
        public function editCartCountItemsAction() {
            // Get and check incoming data
            $catalog_id = Arr::get($_POST, 'id', 0);
            if( !$catalog_id ) {
                $this->error('No such item!');
            }
            $count = Arr::get($_POST, 'count', 0);
            if( !$count ) {
                $this->error('Can\'t change to zero!');
            }
            $size_id = (int) Arr::get($_POST, 'size', 0);
            // Edit count items in cirrent position
            Cart::factory()->edit($catalog_id, $size_id, $count);
            $this->success(array('count' => (int) Cart::factory()->_count_goods));
        }


        // Delete item from the cart
        public function deleteItemFromCartAction() {
            // Get and check incoming data
            $catalog_id = Arr::get($_POST, 'id', 0);
            if( !$catalog_id ) {
                $this->error('No such item!');
            }
            $size_id = (int) Arr::get($_POST, 'size', 0);
            // Add one item to cart
            Cart::factory()->delete($catalog_id, $size_id);
            $this->success(array('count' => (int) Cart::factory()->_count_goods));
        }

        public function moreAffisheAction() {

            $page = (int) Arr::get($_POST, 'page');
            if (! isset($page)) {
                $this->error('Ошибка загрузки');
            }
            // list posts 
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
                ->where('afisha.event_date', '>', DB::expr(time()));
            
            if (isset($_SESSION['idCity'])) {
                $dbObj->where_open()
                    ->where('afisha.place_id', 'IN', $ids)
                    ->or_where('afisha.city_id', '=', $_SESSION['idCity'])
                ->where_close();
            }
            
            $result = $dbObj->group_by('afisha.id')
                    ->order_by('afisha.event_date')
                    ->limit(Config::get( 'limit' ))
                    ->offset(($page - 1) * (int) Config::get( 'limit' ))
                    ->execute()->as_array();
            
            foreach ($result as $key => $value) {
                $result[$key]['p_name'] = Afisha\Models\Afisha::getItemPlace($value, true);
                $result[$key]['cost'] = Afisha\Models\Afisha::getItemPrice($value, true);
                $result[$key]['event_date'] =  date('j', $value['event_date']) .' '. Dates::month(date('n', $value['event_date'])) .' '. date('Y', $value['event_date']);
                if ( ! is_file(HOST.HTML::media('images/afisha/medium/'.$value['image']))) {
                    $result[$key]['image'] = false;
                }
            }
            
            // Count of all posts
            $dbObj = DB::select(array(DB::expr('COUNT(afisha.id)'), 'count' ))
                        ->from('afisha');

            if (isset($_SESSION['idCity'])) {
                $dbObj->where_open()
                    ->where('afisha.place_id', 'IN', $ids)
                    ->or_where('afisha.city_id', '=', $_SESSION['idCity'])
                ->where_close();
            }
            $count = $dbObj->where('afisha.status', '=', 1)
                ->where('afisha.event_date', '>', DB::expr(time()))
                ->as_object()->execute()->current()->count;

            // Set view button more load
            $showBut = true;
            if ($count <= ( Config::get( 'limit' ) * $page ) )
                $showBut = false;
            
            // Render template
            $this->success( array('result' => $result, 'showBut' => $showBut) );
        }

        // Generate Ajax answer
        public function answer( $data ) {
            echo json_encode( $data );
            die;
        }


        // Generate Ajax success answer
        public function success( $data ) {
            if( !is_array( $data ) ) {
                $data = array(
                    'response' => $data,
                );
            }
            $data['success'] = true;
            $this->answer( $data );
        }


        // Generate Ajax answer with error
        public function error( $data ) {
            if( !is_array( $data ) ) {
                $data = array(
                    'response' => $data,
                );
            }
            $data['success'] = false;
            $this->answer( $data );
        }
        
    }