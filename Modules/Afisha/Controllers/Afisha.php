<?php
    namespace Modules\Afisha\Controllers;

    use Core\QB\DB;
    use Core\QB\Database;
    use Core\Route;
    use Core\View;
    use Core\Config;
    use Core\Pager\Pager;
    use Core\Arr;
    use Core\Text;
    use Core\HTTP;

    use Modules\Afisha\Models\Afisha as Model;
    use Modules\Afisha\Models\Map;
    /**
      *     Controller with afisha actions:
      *     index, show, buy
      */
    class Afisha extends \Modules\Base {

        public $limit;
        public $sort;
        public $type;

        public function before() {
            parent::before();
            $this->_template = 'Afisha';
            $this->setBreadcrumbs( 'Афиша', 'afisha' );
            // Set parameters for list items by $_GET
            // Get count items per page
            $this->limit = (int) Arr::get($_GET, 'per_page') ? (int) Arr::get($_GET, 'per_page') : Config::get('limit');
            // Get sort type
            $this->sort = 'afisha.'.(in_array(Arr::get($_GET, 'sort'), array('name', 'created_at', 'cost')) ? Arr::get($_GET, 'sort') : 'id');
            $this->type = in_array(strtolower(Arr::get($_GET, 'type')), array('asc', 'desc')) ? strtoupper(Arr::get($_GET, 'type')) : 'DESC';
        }


        public function indexAction() {
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Seo
            $this->_seo['h1'] = 'Афиша';
            $this->_seo['title'] = 'Афиша';
            $this->_seo['keywords'] = 'Афиша';
            $this->_seo['description'] = 'Афиша';

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
            // list posts first page
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
                    ->limit($this->limit)
                    ->offset(($page - 1) * (int) $this->limit)
                    ->as_object()->execute();
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
            // Generate pagination
            $pager = Pager::factory($page, $count, $this->limit)->create();
            // Render template
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Afisha/List' );
        }

        public function showAction() {
            $item = Model::getItem(Route::param('alias'));
            if( !$item->id ) { return Config::error(); }

            // Add plus one to views
            DB::update('afisha')->set(array('views' => (int) $item->views + 1))->where('id', '=', $item->id)->execute();
            // Seo
            $this->setSeoForItem($item);
            // Render template
            $this->_content = View::tpl( array('obj' => $item), 'Afisha/Item' );
        }


        public function mapAction() {
            // Get afisha post and validate
            $item = Model::getItem(Route::param('alias'));
            if( !$item->id ) { return Config::error(); }

            // Seo
            $this->setSeoForItem($item, true);
            $this->setBreadcrumbs( 'Схема зала' );

            // Get seats for available places
            $seats = Model::getMapSeats($item->id);

            // Get prices list
            $prices = Model::getMapPrices($item->id, true);
            try {
                $map = Map::factory();
                $map->loadFile($item->p_filename);
                $map->addPrices($prices);
                $mapTpl = $map->parseDom($seats, true);
            } catch (\Exception $e) {
                if ($item->url != '') {
                    header('Location: '.$item->url);
                } else {
                    HTTP::redirect('brone?name='.$item->name);
                }
                exit;
            }
                        
            $this->_content = View::tpl(array('item' => $item, 'map' => $mapTpl), 'Map/Main');
        }

        // Set seo tags from template for items
        public function setSeoForItem($page, $breadAlias = false) {
            $tpl = DB::select()->from('seo')->where('id', '=', 3)->as_object()->execute()->current();
            $from = array('{{name}}', '{{date}}', '{{place}}');
            $to = array($page->name, date( 'd.m.Y', $page->event_date ), $page->p_name);
            $this->_seo['h1'] = str_replace($from, $to, $tpl->h1);
            $this->_seo['title'] = str_replace($from, $to, $tpl->title);
            $this->_seo['keywords'] = str_replace($from, $to, $tpl->keywords);
            $this->_seo['description'] = str_replace($from, $to, $tpl->description);
            if ($breadAlias === false) {
                $this->setBreadcrumbs( $page->name );
            }
            else {
                $this->setBreadcrumbs( $page->name, 'afisha/'.$page->alias );
            }
        }
    }