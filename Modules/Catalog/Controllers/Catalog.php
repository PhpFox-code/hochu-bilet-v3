<?php
    namespace Modules\Catalog\Controllers;

    use Core\QB\DB;
    use Core\QB\Database;
    use Core\Route;
    use Core\View;
    use Core\Config;
    use Core\Pager\Pager;
    use Core\Arr;
    use Modules\Catalog\Models\Filter;
    use Core\Text;

    /**
      *     Controller with catalog actions:
      *     index, groups, list, item, new, popular, sale, viewed
      */
    class Catalog extends \Modules\Base {

        public $limit;
        public $sort;
        public $type;

        public function before() {
            parent::before();
            $this->_template = 'Catalog';
            $this->setBreadcrumbs( 'Каталог', 'catalog' );
            // Set parameters for list items by $_GET
            // Get count items per page
            $this->limit = (int) Arr::get($_GET, 'per_page') ? (int) Arr::get($_GET, 'per_page') : Config::get('limit');
            // Get sort type
            $this->sort = 'catalog.'.(in_array(Arr::get($_GET, 'sort'), array('name', 'created_at', 'cost')) ? Arr::get($_GET, 'sort') : 'id');
            $this->type = in_array(strtolower(Arr::get($_GET, 'type')), array('asc', 'desc')) ? strtoupper(Arr::get($_GET, 'type')) : 'DESC';
        }


        // Catalog main page with groups where parent_id = 0
        public function indexAction() {
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Seo
            $this->_seo['h1'] = 'Каталог';
            $this->_seo['title'] = 'Каталог';
            $this->_seo['keywords'] = 'Каталог';
            $this->_seo['description'] = 'Каталог';
            // Get groups with parent_id = 0
            $result = DB::select()->from('catalog_tree')
                        ->where('parent_id', '=', 0)
                        ->where('status', '=', 1)
                        ->order_by('sort')
                        ->limit((int) Config::get('limit_groups'))
                        ->offset(($page - 1) * (int) Config::get('limit_groups'))
                        ->as_object()->execute();
            // Count of parent groups
            $count = DB::select(array(DB::expr('catalog_tree.id'), 'count'))
                        ->from('catalog_tree')
                        ->where('parent_id', '=', 0)
                        ->where('status', '=', 1)
                        ->as_object()->execute()->current()->count;
            // Generate pagination
            $pager = Pager::factory($page, $count, Config::get('limit_groups'))->create();
            // Render template
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/Groups' );
        }


        // Page with groups list
        public function groupsAction() {
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Check for existance
            $group = DB::select()->from('catalog_tree')->where('alias', '=', Route::param('alias'))->where('status', '=', 1)->as_object()->execute()->current();
            if( !$group ) { return Config::error(); }
            Route::factory()->setParam('group', $group->id);
            // Count of child groups
            $count = DB::select(array(DB::expr('catalog_tree.id'), 'count'))
                        ->from('catalog_tree')
                        ->where('parent_id', '=', $group->id)
                        ->where('status', '=', 1)
                        ->as_object()->execute()->current();
            if (!$count) {
                return $this->listAction();
            }
            $count = $count->count;
            // Seo
            $this->setSeoForGroup($group);
            // Add plus one to views
            DB::update('catalog_tree')->set(array('views' => (int) $group->views + 1))->where('id', '=', $group->id)->execute();
            // Get groups list
            $result = DB::select()
                        ->from('catalog_tree')
                        ->where('parent_id', '=', $group->id)
                        ->where('status', '=', 1)
                        ->order_by('sort')
                        ->limit((int) Config::get('limit_groups'))
                        ->offset(($page - 1) * (int) Config::get('limit_groups'))
                        ->as_object()->execute();
            // Generate pagination
            $pager = Pager::factory($page, $count, Config::get('limit_groups'))->create();
            // Render template
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/Groups' );
        }


        // Items list page. Inside group
        public function listAction() {
            $this->_template = 'ItemsList';
            Route::factory()->setAction('list');
            // Filter parameters to array if need
            Filter::setFilterParameters();
            // Set filter elements sortable
            Filter::setSortElements();
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Check for existance
            $group = DB::select()->from('catalog_tree')->where('alias', '=', Route::param('alias'))->where('status', '=', 1)->as_object()->execute()->current();
            if( !$group ) { return Config::error(); }
            // Seo
            $this->setSeoForGroup($group);
            // Add plus one to views
            DB::update('catalog_tree')->set(array('views' => (int) $group->views + 1))->where('id', '=', $group->id)->execute();
            // Get items list
            $result = Filter::getFilteredItemsList($this->limit, ($page - 1) * $this->limit, $this->sort, $this->type);
            // Generate filter add for sql queries
            $add = Filter::getSql();
            // Count of parent groups
            $count = DB::query(Database::SELECT, 'SELECT COUNT(DISTINCT catalog.id) AS count FROM catalog '.$add['join'].'WHERE catalog.parent_id = "'.$group->id.'" AND catalog.status = "1"'.$add['where'])->as_object()->execute()->current();
            if ($count) {
                $count = $count->count;
            } else {
                $count = 0;
            }
            // Generate pagination
            $pager = Pager::factory($page, $count, $this->limit)->create();
            // Render page
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/ItemsList' );
        }

        // Popular items list
        public function popularAction() {
            $this->_template = 'CatalogItemsWithoutFilter';
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Seo
            $this->_seo['h1'] = 'Популярные';
            $this->_seo['title'] = 'Популярные';
            $this->_seo['keywords'] = 'Популярные';
            $this->_seo['description'] = 'Популярные';
            $this->setBreadcrumbs( 'Популярные' );
            // Get popular items
            $result = DB::select('catalog_images.image', 'catalog.*')->from('catalog')
                        ->join('catalog_images', 'LEFT')
                        ->on('catalog_images.catalog_id', '=', 'catalog.id')
                        ->on('catalog_images.main', '=', DB::expr('1'))
                        ->where('top', '=', 1)
                        ->where('status', '=', 1)
                        ->order_by($this->sort, $this->type)
                        ->limit($this->limit)
                        ->offset(($page - 1) * $this->limit)
                        ->as_object()->execute();
            // Count of parent groups
            $count = DB::select(array(DB::expr('COUNT(catalog.id)'), 'count'))->from('catalog')->where('top', '=', 1)->where('status', '=', 1)->as_object()->execute()->current()->count;
            // Generate pagination
            $pager = Pager::factory($page, $count, $this->limit)->create();
            // Render template
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/ItemsList' );
        }


        // New items list
        public function newAction() {
            $this->_template = 'CatalogItemsWithoutFilter';
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Seo
            $this->_seo['h1'] = 'Новинки';
            $this->_seo['title'] = 'Новинки';
            $this->_seo['keywords'] = 'Новинки';
            $this->_seo['description'] = 'Новинки';
            $this->setBreadcrumbs( 'Новинки' );
            // Get new items
            $result = DB::select('catalog_images.image', 'catalog.*')->from('catalog')
                        ->join('catalog_images', 'LEFT')
                        ->on('catalog_images.catalog_id', '=', 'catalog.id')
                        ->on('catalog_images.main', '=', DB::expr('1'))
                        ->where('new', '=', 1)
                        ->where('new_from', '>', time() - Config::get('new_days') * 24 * 60 * 60)
                        ->where('status', '=', 1)
                        ->order_by($this->sort, $this->type)
                        ->limit($this->limit)
                        ->offset(($page - 1) * $this->limit)
                        ->as_object()->execute();
            // Count of parent groups
            $count = DB::select(array(DB::expr('COUNT(catalog.id)'), 'count'))->from('catalog')->where('new', '=', 1)->where('new_from', '>', time() - Config::get('new_days') * 24 * 60 * 60)->where('status', '=', 1)->as_object()->execute()->current()->count;
            // Generate pagination
            $pager = Pager::factory($page, $count, $this->limit)->create();
            // Render template
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/ItemsList' );
        }


        // Items for sale list
        public function saleAction() {
            $this->_template = 'CatalogItemsWithoutFilter';
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Seo
            $this->_seo['h1'] = 'Акции';
            $this->_seo['title'] = 'Акции';
            $this->_seo['keywords'] = 'Акции';
            $this->_seo['description'] = 'Акции';
            $this->setBreadcrumbs( 'Акции' );
            // Get items for sale
            $result = DB::select('catalog_images.image', 'catalog.*')->from('catalog')
                        ->join('catalog_images', 'LEFT')
                        ->on('catalog_images.catalog_id', '=', 'catalog.id')
                        ->on('catalog_images.main', '=', DB::expr('1'))
                        ->where('sale', '=', 1)
                        ->where('status', '=', 1)
                        ->order_by($this->sort, $this->type)
                        ->limit($this->limit)
                        ->offset(($page - 1) * $this->limit)
                        ->as_object()->execute();
            // Count of parent groups
            $count = DB::select(array(DB::expr('COUNT(catalog.id)'), 'count'))->from('catalog')->where('sale', '=', 1)->where('status', '=', 1)->as_object()->execute()->current()->count;
            // Generate pagination
            $pager = Pager::factory($page, $count, $this->limit)->create();
            // Render template
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/ItemsList' );
        }


        // Viewed items list
        public function viewedAction() {
            $this->_template = 'CatalogItemsWithoutFilter';
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Seo
            $this->_seo['h1'] = 'Недавно просмотренные товары';
            $this->_seo['title'] = 'Недавно просмотренные товары';
            $this->_seo['keywords'] = 'Недавно просмотренные товары';
            $this->_seo['description'] = 'Недавно просмотренные товары';
            $this->setBreadcrumbs( 'Недавно просмотренные товары' );
            // Get viewed items IDs array
            $ids = Catalog::factory()->getViewedIDs();
            // Check for empty array
            if( !$ids ) { $ids = array(0); }
            // Get viewed items list
            $result = DB::select('catalog_images.image', 'catalog.*')->from('catalog')
                        ->join('catalog_images', 'LEFT')
                        ->on('catalog_images.catalog_id', '=', 'catalog.id')
                        ->on('catalog_images.main', '=', DB::expr('1'))
                        ->where('id', 'IN', $ids)
                        ->order_by($this->sort, $this->type)
                        ->limit($this->limit)
                        ->offset(($page - 1) * $this->limit)
                        ->as_object()->execute();
            // Count of parent groups
            $count = DB::select(array(DB::expr('COUNT(catalog.id)'), 'count'))->from('catalog')->where('id', 'IN', $ids)->where('status', '=', 1)->as_object()->execute()->current()->count;
            // Generate pagination
            $pager = Pager::factory($page, $count, $this->limit)->create();
            // Render template
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/ItemsList' );
        }


        // Set seo tags from template for items groups
        public function setSeoForGroup($page) {
            $tpl = DB::select()->from('seo')->where('id', '=', 1)->as_object()->execute()->current();
            $from = array('{{name}}', '{{content}}');
            $text = trim(strip_tags($page->text));
            $to = array($page->name, $text);
            $res = preg_match_all('/{{content:[0-9]*}}/', $tpl->description, $matches);
            if($res) {
                $matches = array_unique($matches);
                foreach($matches[0] AS $pattern) {
                    preg_match('/[0-9]+/', $pattern, $m);
                    $from[] = $pattern;
                    $to[] = Text::limit_words($text, $m[0]);
                }
            }
            $title = str_replace($from, $to, $tpl->title)
                    .((Arr::get($_GET, 'sort') == 'cost' && Arr::get($_GET, 'type') == 'asc') ? ', От бютжетных к дорогим' : '')
                    .((Arr::get($_GET, 'sort') == 'cost' && Arr::get($_GET, 'type') == 'desc') ? ', От дорогих к бютжетным' : '')
                    .((Arr::get($_GET, 'sort') == 'created_at' && Arr::get($_GET, 'type') == 'desc') ? ', От новых моделей к старым' : '')
                    .((Arr::get($_GET, 'sort') == 'created_at' && Arr::get($_GET, 'type') == 'asc') ? ', От старых моделей к новым' : '')
                    .((Arr::get($_GET, 'sort') == 'name' && Arr::get($_GET, 'type') == 'asc') ? ', По названию от А до Я' : '')
                    .((Arr::get($_GET, 'sort') == 'name' && Arr::get($_GET, 'type') == 'desc') ? ', По названию от Я до А' : '')
                    .(Arr::get($_GET, 'page', 1) > 1 ? ', Страница '.Arr::get($_GET, 'page', 1) : '');
            $this->_seo['h1'] = str_replace($from, $to, $tpl->h1);
            $this->_seo['title'] = $title;
            $this->_seo['keywords'] = str_replace($from, $to, $tpl->keywords);
            $this->_seo['description'] = str_replace($from, $to, $tpl->description);
            $this->_seo['seo_text'] = $page->text;
            $this->generateParentBreadcrumbs( $page->parent_id, 'catalog_tree', 'parent_id', '/catalog/' );
            $this->setBreadcrumbs( $page->name );
        }

    }