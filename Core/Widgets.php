<?php
    namespace Core;

    use Plugins\Profiler\Profiler;
    use Core\QB\DB;
    use Modules\Cart\Models\Cart;
    use Users;
    use Modules\Catalog\Models\Filter;
    use Modules\Catalog\Models\Catalog;

    /**
     *  Class that helps with widgets on the site
     */
    class Widgets {

        static $_instance; // Constant that consists self class

        public $_data = array(); // Array of called widgets
        public $_tree = array(); // Only for catalog menus on footer and header. Minus one query

        // Instance method
        static function factory() {
            if(self::$_instance == NULL) { self::$_instance = new self(); }
            return self::$_instance;
        }

        /**
         *  Get widget
         *  @param  [string] $name  [Name of template file]
         *  @param  [array]  $array [Array with data -> go to template]
         *  @return [string]        [Widget HTML]
         */
        public static function get( $name, $array = array() ) {
            $token = Profiler::start('Profiler', 'Widget '.$name);
            $w = Widgets::factory();
            if( isset( $w->_data[ $name ] ) ) {
                return $w->_data[ $name ];
            }
            if( method_exists( $w, $name ) ) {
                return $w->$name( $array );
            }
            Profiler::stop($token);
            return $w->common( $name, $array );
        }

        /**
         *  Common widget method. Uses when we have no widgets called $name
         *  @param  [string] $name  [Name of template file]
         *  @param  [array]  $array [Array with data -> go to template]
         *  @return [string]        [Widget HTML or NULL if template doesn't exist]
         */
        public function common( $name, $array ) {
            $filename = ucfirst($name);
            if( file_exists(HOST.APPLICATION.'/Views/Parts/Widgets/'.$filename.'.php') ) {
                return $this->_data[$name] = View::widget($array, $filename);
            }
            if( file_exists(HOST.APPLICATION.'/Views/Parts/'.$filename.'.php') ) {
                return $this->_data[$name] = View::part($array, $filename);
            }
            return NULL;
        }


        public function hiddenData( $array = array() ) {
            $cart = Cart::factory()->get_list_for_basket();
            return $this->_data['hiddenData'] = View::widget(array( 'cart' => $cart ), 'HiddenData');
        }


        public function comments( $array = array() ) {
            $id = Route::param('id');
            if( !$id ) { return $this->_data['comments'] = ''; }
            $result = DB::select()->from('catalog_comments')->where('status', '=', 1)->where('catalog_id', '=', $id)->order_by('date', 'DESC')->as_object()->execute();
            return $this->_data['comments'] = View::widget(array( 'result' => $result ), 'Comments');
        }


        // public function catalogFilter( $array = array() ) {
        //     $array = Filter::getClickableFilterElements();
        //     $brands = Filter::getBrandsWidget();
        //     $models = array();
        //     if(Arr::get(Config::get('filter_array'), 'brand')){
        //         $models = Filter::getModelsWidget();
        //     }
        //     $sizes = Filter::getSizesWidget();
        //     $specifications = Filter::getSpecificationsWidget();
        //     return $this->_data['catalogFilter'] = View::widget(array(
        //         'brands' => $brands,
        //         'models' => $models,
        //         'specifications' => $specifications,
        //         'sizes' => $sizes,
        //         'filter' => $array['filter'],
        //         'min' => $array['min'],
        //         'max' => $array['max'],
        //     ), 'CatalogFilter');
        // }


        // public function infoItemPage( $array = array() ) {
        //     $pages = array( 5, 6, 7, 8 );
        //     $result = DB::select()->from('content')->where('status', '=', 1)->where('id', 'IN', $pages)->order_by('sort')->as_object()->execute();
        //     return $this->_data['infoItemPage'] = View::widget(array( 'result' => $result ), 'InfoItemPage');
        // }


        // public function itemsViewed( $array = array() ) {
        //     $ids = Catalog::factory()->getViewedIDs();
        //     if( !$ids ) {
        //         return $this->_data['itemsViewed'] = '';
        //     }
        //     $result = DB::select('catalog.*', array('catalog_images.image', 'image'))
        //                 ->from('catalog')
        //                 ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('1'))
        //                 ->where('catalog.id', 'IN', $ids)
        //                 ->where('catalog.status', '=', 1)
        //                 ->limit(5)
        //                 ->as_object()->execute();
        //     if( !count($result) ) {
        //         return $this->_data['itemsViewed'] = '';
        //     }
        //     return $this->_data['itemsViewed'] = View::widget(array( 'result' => $result ), 'ItemsViewed');
        // }


        // public function itemsPopular( $array = array() ) {
        //     $result = DB::select('catalog.*', array('catalog_images.image', 'image'))
        //                 ->from('catalog')
        //                 ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('1'))
        //                 ->where('catalog.top', '=', 1)
        //                 ->where('catalog.status', '=', 1)
        //                 ->order_by(DB::expr('rand()'))
        //                 ->limit(5)
        //                 ->as_object()->execute();
        //     if( !count($result) ) {
        //         return $this->_data['itemsPopular'] = '';
        //     }
        //     return $this->_data['itemsPopular'] = View::widget(array( 'result' => $result ), 'ItemsPopular');
        // }


        // public function itemsNew( $array = array() ) {
        //     $result = DB::select('catalog.*', array('catalog_images.image', 'image'))
        //                 ->from('catalog')
        //                 ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('1'))
        //                 ->where('catalog.new', '=', 1)
        //                 ->where('catalog.new_from', '>', time() - Config::get('new_days') * 24 * 60 * 60)
        //                 ->where('catalog.status', '=', 1)
        //                 ->order_by(DB::expr('rand()'))
        //                 ->limit(5)
        //                 ->as_object()->execute();
        //     if( !count($result) ) {
        //         return $this->_data['itemsNew'] = '';
        //     }
        //     return $this->_data['itemsNew'] = View::widget(array( 'result' => $result ), 'ItemsNew');
        // }


        // public function itemsSame( $array = array() ) {
        //     $result = DB::select('catalog.*', array('catalog_images.image', 'image'))
        //                 ->from('catalog')
        //                 ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('1'))
        //                 ->where('catalog.parent_id', '=', Route::param('group'))
        //                 ->where('catalog.status', '=', 1)
        //                 ->where('catalog.id', '!=', Route::param('id'))
        //                 ->order_by(DB::expr('rand()'))
        //                 ->limit(5)
        //                 ->as_object()->execute();
        //     if( !count($result) ) {
        //         return $this->_data['itemsSame'] = '';
        //     }
        //     $alias = DB::select('alias')->from('catalog_tree')->where('id', '=', Route::param('group'))->as_object()->execute()->current()->alias;
        //     return $this->_data['itemsSame'] = View::widget(array( 'result' => $result, 'alias' => $alias ), 'ItemsSame');
        // }


        // public function catalogMenuLeft( $array = array() ) {
        //     if( !empty($this->_tree) ) {
        //         $result = $this->_tree;
        //     } else {
        //         $result = DB::select()->from('catalog_tree')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
        //         $this->_tree = $result;
        //     }
        //     $arr = array();
        //     foreach( $result as $obj ) {
        //         $arr[$obj->parent_id][] = $obj;
        //     }
        //     $rootParent = Support::getRootParent($result, Route::param('group'));
        //     return $this->_data['catalogMenuLeft'] = View::widget(array( 'result' => $arr, 'root' => $rootParent ), 'CatalogMenuLeft');
        // }


        // public function catalogMenuTop( $array = array() ) {
        //     if( !empty($this->_tree) ) {
        //         $result = $this->_tree;
        //     } else {
        //         $result = DB::select()->from('catalog_tree')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
        //         $this->_tree = $result;
        //     }
        //     $arr = array();
        //     foreach( $result as $obj ) {
        //         $arr[$obj->parent_id][] = $obj;
        //     }
        //     return $this->_data['catalogMenuTop'] = View::widget(array( 'result' => $arr ), 'CatalogMenuTop');
        // }


        // public function catalogMenuBottom( $array = array() ) {
        //     if( !empty($this->_tree) ) {
        //         $result = $this->_tree;
        //     } else {
        //         $result = DB::select()->from('catalog_tree')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
        //         $this->_tree = $result;
        //     }
        //     $arr = array();
        //     foreach( $result as $obj ) {
        //         $arr[$obj->parent_id][] = $obj;
        //     }
        //     return $this->_data['catalogMenuBottomp'] = View::widget(array( 'result' => $arr ), 'CatalogMenuBottom');
        // }


        public function slider( $array = array() ) {
            $result = DB::select()->from('slider')->where('status', '=', 1)->order_by(DB::expr('RAND()'))->as_object()->execute();
            print_r($result);
            if( !count( $result ) ) {
                return $this->_data['slider'] = '';
            }
            return $this->_data['slider'] = View::widget(array( 'result' => $result ), 'Slider');
        }


        public function banners( $array = array() ) {
            $result = DB::select()->from('banners')->where('status', '=', 1)->order_by(DB::expr('rand()'))->limit(2)->as_object()->execute();
            if( !count( $result ) ) {
                return $this->_data['banners'] = '';
            }
            return $this->_data['banners'] = View::widget(array( 'result' => $result ), 'Banners');
        }


        public function news( $array = array() ) {
            $obj = DB::select()->from('news')->where('status', '=', 1)->order_by('date', 'DESC')->as_object()->execute()->current();
            if( !count( $obj ) ) {
                return $this->_data['news'] = '';
            }
            return $this->_data['news'] = View::widget(array( 'obj' => $obj ), 'News');
        }


        public function articles( $array = array() ) {
            $result = DB::select()->from('articles')->where('status', '=', 1)->order_by('id', 'DESC')->limit(Config::get('limit_articles_main_page'))->as_object()->execute();
            if( !count( $result ) ) {
                return $this->_data['articles'] = '';
            }
            return $this->_data['articles'] = View::widget(array( 'result' => $result ), 'Articles');
        }


        public function info( $array = array() ) {
            $result = DB::select()->from('content')->where('status', '=', 1)->where('id', 'IN', array( 5, 6, 7, 8 ))->order_by('sort')->as_object()->execute();
            if( !count( $result ) ) {
                return $this->_data['info'] = '';
            }
            return $this->_data['info'] = View::widget(array( 'result' => $result ), 'Info');
        }


        public function headerCart( $array = array() ) {
            $contentMenu = DB::select()->from('sitemenu')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
            return $this->_data['headerCart'] = View::widget(array( 'contentMenu' => $contentMenu ), 'HeaderCart');
        }


        public function footer( $array = array() ) {
            if( APPLICATION ) {
                return $this->footerBackend();
            }
            // $contentMenu = DB::select()->from('sitemenu')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
            // $array['contentMenu'] = $contentMenu;
            return $this->_data['footer'] = View::widget(array(), 'Footer');
        }

        public function footerMenu () {
            $contentMenu = DB::select()->from('sitemenu')->where('status', '=', 1)->order_by('sort')->execute()->as_array();
            $cnt = count($contentMenu);
            $center = ceil($cnt / 2);
            $menu = array_chunk($contentMenu, $center);
                
            return $this->_data['footerMenu'] = View::widget(array('menu' => $menu), 'FooterMenu');
        }


        public function mainHeader( $array = array() ) {
            if( APPLICATION ) {
                return $this->headerBackend();
            }
            $array['slider'] = DB::select()->from('slider')->where('status', '=', 1)->order_by(DB::expr('rand()'))->as_object()->execute();
            $array['cities'] = DB::select()->from('cities')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
            return $this->_data['header'] = View::widget($array, 'MainHeader');
        }

        public function header( $array = array() ) {
            if( APPLICATION ) {
                return $this->headerBackend();
            }
            
            $array['cities'] = DB::select()->from('cities')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
            return $this->_data['header'] = View::widget($array, 'Header');
        }


        public function sidebar( $array = array() ) {
            if( APPLICATION ) {
                return $this->sidebarBackend();
            }
        }

        public function crumbs( $array = array() ) {
            if( APPLICATION ) {
                return $this->crumbsBackend();
            }
        }


        /******** prog ********/
        public function topMenu () {
            $menu = DB::select()->from('sitemenu')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
            return $this->_data['topMenu'] = View::widget(array(
                    'menu' => $menu
                ), 'TopMenu');
        }

        public function mainAfisha () {
            // list posts 
            $result = \Modules\Afisha\Models\Afisha::getWidgetRows();
            if (count($result) == 0) {
                return $this->_data['mainAfisha'] = '';
            }
            
            return $this->_data['mainAfisha'] = View::widget(array(
                'result' => $result
                ), 'MainAfisha');
        }

        ##################### BACKEND ONLY ##########################
        public function sidebarBackend( $array = array() ) {
            $result = DB::select()->from('menu')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
            $arr = array();
            foreach( $result AS $obj ) {
                $arr[ $obj->id_parent ][] = $obj;
            }
            return $this->_data['sidebar'] = View::widget(array( 'result' => $arr ), 'Sidebar');
        }


        public function crumbsBackend( $array = array() ) {
            $count_orders = 0;
            $result = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('orders')->where( 'status', '=', 0 )->as_object()->execute()->current();
            if ($result) {
                $count_orders = $result->count;
            }
            $count_comments = 0;
            $result = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('catalog_comments')->where( 'status', '=', 0 )->as_object()->execute()->current();
            if ($result) {
                $count_comments = $result->count;
            }
            return $this->_data['crumbs'] = View::widget(array( 'cc' => $count_comments, 'co' => $count_orders ), 'Crumbs');
        }


        public function footerBackend( $array = array() ) {
            return $this->_data['footer'] = View::widget(array(), 'Footer');
        }


        public function headerBackend( $array = array() ) {
            return $this->_data['header'] = View::widget(array(), 'Header');
        }


        public function headerContacts( $array = array() ) {
            $contacts = DB::select()->from('contacts')->where('status', '=', 0)->limit(5)->as_object()->execute();
            $cContacts = 0;
            $result = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('contacts')->where( 'status', '=', 0 )->as_object()->execute()->current();
            if ($result) {
                $cContacts = $result->count;
            }
            return $this->_data['headerContacts'] = View::widget(array(
                'contacts' => $contacts,
                'cContacts' => $cContacts,
            ), 'HeaderContacts');
        }


        public function headerNew( $array = array() ) {
            $count = 0;
            $result = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('log')->where( 'deleted', '=', 0 )->as_object()->execute()->current();
            if ($result) {
                $count = $result->count;
            }
            $result = DB::select()->from('log')->where('deleted', '=', 0)->limit(7)->as_object()->execute();
            return $this->_data['headerNew'] = View::widget(array(
                'count' => $count,
                'result' => $result,
            ), 'HeaderNew');
        }


    }