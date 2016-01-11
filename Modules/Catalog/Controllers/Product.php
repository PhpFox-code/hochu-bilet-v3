<?php
    namespace Modules\Catalog\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\View;
    use Core\Config;
    use Modules\Catalog\Models\Catalog;
    use Core\HTML;

    /**
      *     Controller with product action
      */
    class Product extends \Modules\Base {

        public function before() {
            parent::before();
            $this->_template = 'Item';
        }

        // Show item inner page
        public function indexAction() {
            // Get item information from database
            $item = DB::select('catalog.*', array('brands.name', 'brand_name'), array('brands.alias', 'brand_alias'), array('models.name', 'model_name'), array('catalog_tree.name', 'parent_name'))
                        ->from('catalog')
                        ->join('catalog_tree', 'LEFT')->on('catalog.parent_id', '=', 'catalog_tree.id')
                        ->join('brands', 'LEFT')->on('catalog.brand_id', '=', 'brands.id')->on('brands.status', '=', DB::expr('1'))
                        ->join('models', 'LEFT')->on('catalog.model_id', '=', 'models.id')->on('models.status', '=', DB::expr('1'))
                        ->where('catalog.status', '=', 1)
                        ->where('catalog.alias', '=', Route::param('alias'))
                        ->as_object()->execute()->current();
            if( !$item ) { return Config::error(); }
            Route::factory()->setParam('id', $item->id);
            Route::factory()->setParam('group', $item->parent_id);
            // Add to cookie viewed list
            Catalog::factory()->addViewed($item->id);
            // Add plus one to views
            DB::update('catalog')->set(array('views' => (int) $item->views + 1))->where('id', '=', $item->id)->execute();
            // Seo
            $this->setSeoForItem($item);
            // Get images
            $images = DB::select('image')->from('catalog_images')->where('catalog_id', '=', $item->id)->order_by('sort')->as_object()->execute();
            // Get item sizes
            $sizes = DB::select('sizes.*')->from('sizes')
                        ->join('catalog_sizes', 'LEFT')->on('catalog_sizes.size_id', '=', 'sizes.id')
                        ->where('catalog_sizes.catalog_id', '=', $item->id)
                        ->where('sizes.status', '=', 1)
                        ->order_by('sizes.name')
                        ->as_object()->execute();
            // Get current item specifications list
            $specifications = DB::select('specifications.*')->from('specifications')
                                ->join('catalog_tree_specifications', 'LEFT')->on('catalog_tree_specifications.specification_id', '=', 'specifications.id')
                                ->where('catalog_tree_specifications.catalog_tree_id', '=', $item->parent_id)
                                ->where('specifications.status', '=', 1)
                                ->order_by('specifications.name')
                                ->as_object()->execute();
            $res = DB::select()->from('specifications_values')
                    ->join('catalog_specifications_values', 'LEFT')->on('catalog_specifications_values.specification_value_id', '=', 'specifications_values.id')
                    ->where('catalog_specifications_values.catalog_id', '=', $item->id)
                    ->where('status', '=', 1)
                    ->where('catalog_specifications_values.specification_value_id', '!=', 0)
                    ->as_object()->execute();
            $specValues = array();
            foreach( $res AS $obj ) {
                $specValues[$obj->specification_id][] = $obj;
            }
            $spec = array();
            foreach ($specifications as $obj) {
                if( isset($specValues[$obj->id]) AND is_array($specValues[$obj->id]) AND count($specValues[$obj->id]) ) {
                    if( $obj->type_id == 3 ) {
                        $spec[$obj->name] = '';
                        foreach($specValues[$obj->id] AS $o) {
                            $spec[$obj->name] .= $o->name.', ';
                        }
                        $spec[$obj->name] = substr($spec[$obj->name], 0, -2);
                    } else {
                        $spec[$obj->name] = $specValues[$obj->id][0]->name;
                    }
                }
            }
            // Render template
            $this->_content = View::tpl( array('obj' => $item, 'images' => $images, 'sizes' => $sizes, 'specifications' => $spec), 'Catalog/Item' );
        }

        // Set seo tags from template for items
        public function setSeoForItem($page) {
            $tpl = DB::select()->from('seo')->where('id', '=', 2)->as_object()->execute()->current();
            $from = array('{{name}}', '{{group}}', '{{brand}}', '{{model}}');
            $to = array($page->name, $page->parent_name, $page->brand_name, $page->model_name);
            $this->_seo['h1'] = str_replace($from, $to, $tpl->h1);
            $this->_seo['title'] = str_replace($from, $to, $tpl->title);
            $this->_seo['keywords'] = str_replace($from, $to, $tpl->keywords);
            $this->_seo['description'] = str_replace($from, $to, $tpl->description);
            $this->setBreadcrumbs( 'Каталог', '/catalog' );
            $this->generateParentBreadcrumbs( $page->parent_id, 'catalog_tree', 'parent_id', '/catalog/' );
            $this->setBreadcrumbs( $page->name );
        }

    }