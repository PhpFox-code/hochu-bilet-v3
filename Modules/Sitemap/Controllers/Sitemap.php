<?php
    namespace Modules\Sitemap\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\View;
    use Core\Config AS conf;
    
    class Sitemap extends \Modules\Base {

        // Search list
        public function indexAction() {
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            // Seo
            $this->_seo['h1'] = 'Карта сайта';
            $this->_seo['title'] = 'Карта сайта';
            $this->_seo['keywords'] = 'Карта сайта';
            $this->_seo['description'] = 'Карта сайта';
            $this->setBreadcrumbs( 'Карта сайта' );

            // Get pages
            $result = DB::select()->from('content')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
            $pages = array();
            foreach ($result as $obj) {
                $pages[$obj->parent_id][] = $obj;
            }

            // Get catalog groups
            $result = DB::select()->from('catalog_tree')->where('status', '=', 1)->order_by('sort')->as_object()->execute();
            $groups = array();
            foreach ($result as $obj) {
                $groups[$obj->parent_id][] = $obj;
            }

            // Get catalog groups
            $brands = DB::select()->from('brands')->where('status', '=', 1)->order_by('sort')->as_object()->execute();

            // Get news
            $news = DB::select()->from('news')->where('status', '=', 1)->order_by('date', 'DESC')->as_object()->execute();

            // Get articles
            $articles = DB::select()->from('articles')->where('status', '=', 1)->order_by('id', 'DESC')->as_object()->execute();
            
            // Render page
            $this->_content = View::tpl( array('pages' => $pages, 'groups' => $groups, 'news' => $news, 'articles' => $articles, 'brands' => $brands), 'Sitemap/Index' );
        }

    }