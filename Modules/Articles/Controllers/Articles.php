<?php
    namespace Modules\Articles\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\View;
    use Core\Config;
    use Core\Pager\Pager;
    
    class Articles extends \Modules\Base {

        public function before() {
            parent::before();
            $this->setBreadcrumbs( 'Статьи', 'articles' );
            $this->_template = 'Text';
        }

        public function indexAction() {
            Config::set( 'content_class', 'news_block' );
            $page = !(int) Route::param('page') ? 1 : (int) Route::param('page');
            $result = DB::select()
                        ->from('articles')
                        ->where('status', '=', 1)
                        ->order_by('id', 'DESC')
                        ->limit((int) Config::get('limit_articles'))
                        ->offset(($page - 1) * (int) Config::get('limit_articles'))
                        ->find_all();
            // Count of articles
            $count = DB::select(array(DB::expr('COUNT(articles.id)'), 'count'))->from('articles')->where('status', '=', 1)->count_all();
            // Generate pagination
            $pager = Pager::factory($page, $count, Config::get('limit_articles'))->create();
            // Render template
            $this->_content = View::tpl( array( 'result' => $result, 'pager' => $pager ), 'Articles/List' );
        }

        public function innerAction() {
            Config::set( 'content_class', 'new_block' );
            // Check for existance
            $obj = DB::select()
                    ->from('articles')
                    ->where('alias', '=', Route::param('alias'))
                    ->where('status', '=', 1)
                    ->find();
            if( !$obj ) { return Config::error(); }
            // Seo
            $this->_seo['h1'] = $obj->h1;
            $this->_seo['title'] = $obj->title;
            $this->_seo['keywords'] = $obj->keywords;
            $this->_seo['description'] = $obj->description;
            $this->setBreadcrumbs( $obj->name );
            // Add plus one to views
            DB::update('articles')->set(array('views' => $obj->views + 1))->where('id', '=', $obj->id)->execute();
            // Render template
            $this->_content = View::tpl( array( 'obj' => $obj ), 'Articles/Inner' );
        }
        
    }