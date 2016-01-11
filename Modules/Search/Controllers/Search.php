<?php
    namespace Modules\Search\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\View;
    use Core\Arr;
    use Core\Config AS conf;
    use Core\Pager\Pager;
    
    class Search extends \Modules\Base {

        // Search list
        public function indexAction() {
            $this->_template = 'CatalogItemsWithoutFilter';
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Seo
            $this->_seo['h1'] = 'Поиск';
            $this->_seo['title'] = 'Поиск';
            $this->_seo['keywords'] = 'Поиск';
            $this->_seo['description'] = 'Поиск';
            $this->setBreadcrumbs( 'Поиск' );
            // Check query
            $query = Arr::get($_GET, 'query');
            if( !$query ) {
                return $this->_content = $this->noResults();
            }
            // Get count items per page
            $limit = (int) Arr::get($_GET, 'per_page') ? (int) Arr::get($_GET, 'per_page') : conf::get('limit');
            // Get sort type
            $sort = in_array(Arr::get($_GET, 'sort'), array('name', 'created_at', 'cost')) ? Arr::get($_GET, 'sort') : 'sort';
            $type = in_array(strtolower(Arr::get($_GET, 'type')), array('asc', 'desc')) ? strtoupper(Arr::get($_GET, 'type')) : 'ASC';
            // Get items list
            $result = DB::select(array('catalog_images.image', 'image'), 'catalog.*')
                        ->from('catalog')
                        ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('1'))
                        ->or_where_open()
                            ->or_where('catalog.name', 'LIKE', DB::expr('"%'.$query.'%"'))
                            ->or_where('catalog.artikul', 'LIKE', DB::expr('"%'.$query.'%"'))
                        ->or_where_close()
                        ->where('catalog.status', '=', 1)
                        ->order_by('catalog.'.$sort, $type)
                        ->limit($limit, ($page - 1) * $limit)
                        ->as_object()->execute();
            // Check for empty list
            if( !count($result) ) {
                return $this->_content = $this->noResults();
            }
            // Count of parent groups
            $count = DB::select(array(DB::expr('COUNT(catalog.id)'), 'count'))
                        ->from('catalog')
                        ->or_where_open()
                            ->or_where('catalog.name', 'LIKE', DB::expr('"%'.$query.'%"'))
                            ->or_where('catalog.artikul', 'LIKE', DB::expr('"%'.$query.'%"'))
                        ->or_where_close()
                        ->where('catalog.status', '=', 1)
                        ->as_object()->execute()->current()->count;
            // Generate pagination
            $pager = Pager::factory($page, $count, $limit)->create();
            // Render page
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/ItemsList' );
        }


        // This we will show when no results
        public function noResults() {
            return '<p>По Вашему запросу ничего не найдено!</p>';
        }

    }