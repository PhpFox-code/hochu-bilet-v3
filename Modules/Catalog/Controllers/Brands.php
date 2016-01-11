<?php
    namespace Modules\Catalog\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\View;
    use Core\Config;
    use Core\Pager\Pager;
    use Core\Support AS support;
    use Core\Arr;
    use Core\Text;

    class Brands extends \Modules\Base {

        public function before() {
            parent::before();
            $this->setBreadcrumbs( 'Бренды', 'brands' );
            $this->_template = 'Text';
        }

        // Brands list page
        public function indexAction() {
            $this->_template = 'Text';
            Config::set( 'content_class', 'brands-list' );
            // Seo
            $this->_seo['h1'] = 'Бренды';
            $this->_seo['title'] = 'Бренды';
            $this->_seo['keywords'] = 'Бренды';
            $this->_seo['description'] = 'Бренды';
            // Get brands list
            $result = DB::select()->from('brands')->where('status', '=', 1)->order_by('name', 'ASC')->as_object()->execute();
            // Get alphabet
            $alphabet = Text::get_alphabet($result);
            $this->_content = View::tpl(array('alphabet' => $alphabet), 'Brands/List');
        }


        // Items page
        public function innerAction() {
            $this->_template = 'CatalogItemsWithoutFilter';
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Check for existance
            $brand = DB::select()->from('brands')->where('alias', '=', Route::param('alias'))->where('status', '=', 1)->as_object()->execute()->current();
            if( !$brand ) { return Config::error(); }
            // Seo
            $this->_seo['h1'] = $brand->h1;
            $this->_seo['title'] = $brand->title;
            $this->_seo['keywords'] = $brand->keywords;
            $this->_seo['description'] = $brand->description;
            $this->setBreadcrumbs( $brand->name );
            // Get count items per page
            $limit = (int) Arr::get($_GET, 'per_page') ? (int) Arr::get($_GET, 'per_page') : Config::get('limit');
            // Get sort type
            $sort = in_array(Arr::get($_GET, 'sort'), array('name', 'created_at', 'cost')) ? Arr::get($_GET, 'sort') : 'sort';
            $type = in_array(strtolower(Arr::get($_GET, 'type')), array('asc', 'desc')) ? strtoupper(Arr::get($_GET, 'type')) : 'ASC';
            // Get popular items
            $result = DB::select(array('catalog_images.image', 'image'), 'catalog.*')
                        ->from('catalog')
                        ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('1'))
                        ->where('catalog.brand_id', '=', $brand->id)
                        ->where('catalog.status', '=', 1)
                        ->order_by('catalog.'.$sort, $type)
                        ->limit($limit)
                        ->offset(($page - 1) * $limit)
                        ->as_object()->execute();
            // Set description of the brand to show it above the sort part
            Config::set('brand_description', View::tpl( array('brand' => $brand), 'Brands/Inner' ));
            // Count of parent groups
            $count = DB::select(array(DB::expr('COUNT(catalog.id)'), 'count'))
                        ->from('catalog')
                        ->where('brand_id', '=', $brand->id)
                        ->where('status', '=', 1)
                        ->as_object()->execute()->current()->count;
            // Generate pagination
            $pager = Pager::factory($page, $count, $limit)->create();
            // Render template
            $this->_content = View::tpl( array('result' => $result, 'pager' => $pager), 'Catalog/ItemsList' );
        }
        
    }