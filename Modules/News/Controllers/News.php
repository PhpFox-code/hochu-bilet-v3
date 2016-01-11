<?php
    namespace Modules\News\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\View;
    use Core\Config;
    use Core\Pager\Pager;
    
    class News extends \Modules\Base {

        public function before() {
            parent::before();
            $this->setBreadcrumbs( 'Новости', 'news' );
            $this->_template = 'Text';
        }

        public function indexAction() {
            Config::set( 'content_class', 'news_block' );
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            $result = DB::select()
                        ->from('news')
                        ->where('date', '<=', time())
                        ->where('status', '=', 1)
                        ->order_by('date', 'DESC')
                        ->limit((int) Config::get('limit_articles'))
                        ->offset(($page - 1) * (int) Config::get('limit_articles'))
                        ->as_object()->execute();
            // Count of news
            $count = DB::select(array(DB::expr('COUNT(news.id)'), 'count'))->from('news')->where('status', '=', 1)->as_object()->execute()->current()->count;
            // Generate pagination
            $pager = Pager::factory($page, $count, Config::get('limit_articles'))->create();
            // Render template
            $this->_content = View::tpl( array( 'result' => $result, 'pager' => $pager ), 'News/List' );
        }

        public function innerAction() {
            Config::set( 'content_class', 'new_block' );
            // Check for existance
            $obj = DB::select()
                    ->from('news')
                    ->where('alias', '=', Route::param('alias'))
                    ->where('status', '=', 1)
                    ->where('date', '<=', time())
                    ->as_object()->execute()->current();
            if( !$obj ) { return Config::error(); }
            // Seo
            $this->_seo['h1'] = $obj->h1;
            $this->_seo['title'] = $obj->title;
            $this->_seo['keywords'] = $obj->keywords;
            $this->_seo['description'] = $obj->description;
            $this->setBreadcrumbs( $obj->name );
            // Add plus one to views
            DB::update('news')->set(array('views' => $obj->views + 1))->where('id', '=', $obj->id)->execute();
            // Render template
            $this->_content = View::tpl( array( 'obj' => $obj ), 'News/Inner' );
        }
        
    }