<?php
    namespace Backend\Modules\Index\Controllers;

    use Core\View;
    use Core\QB\DB;

    class Index extends \Backend\Modules\Base {

        function indexAction () {
            $this->_seo['h1'] = 'Панель управления';
            $this->_seo['title'] = 'Панель управления';

            $count_catalog = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('catalog')->count_all();
            $count_orders = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('orders')->count_all();
            $count_comments = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('catalog_comments')->count_all();
            $count_subscribers = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('subscribers')->count_all();
            $count_users = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('users')->where('role_id', '!=', 2)->count_all();
            $count_banners = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('banners')->count_all();
            $count_articles = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('articles')->count_all();
            $count_news = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from('news')->count_all();

            $this->_content = View::tpl( array(
                'count_catalog' => $count_catalog,
                'count_orders' => $count_orders,
                'count_comments' => $count_comments,
                'count_subscribers' => $count_subscribers,
                'count_users' => $count_users,
                'count_banners' => $count_banners,
                'count_articles' => $count_articles,
                'count_news' => $count_news,
            ), 'Index/Main');
        }

    }