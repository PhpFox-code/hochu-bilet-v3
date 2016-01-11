<?php
    namespace Backend\Modules\Log\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Arr;
    use Core\View;
    use Core\QB\DB;
    use Core\Pager\Pager;

    class Log extends \Backend\Modules\Base {

        public $tpl_folder = 'Log';
        public $tablename  = 'log';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Лента событий';
            $this->_seo['title'] = 'Лента событий';
            $this->setBreadcrumbs('Лента событий', 'backend/'.Route::controller().'/index');
            $this->limit = Config::get('limit_backend');
        }

        function indexAction () {
            $date_s = NULL; $date_po = NULL;
            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }

            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename);
            if( $date_s ) { $count->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $count->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            $count = $count->count_all();
            $result = DB::select()->from($this->tablename);
            if( $date_s ) { $result->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $result->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            $result = $result->order_by('created_at', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $pager = Pager::factory( $page, $count, $this->limit )->create();

            $this->_toolbar = Widgets::get( 'Toolbar/List' );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                    'pager' => $pager,
                    'pageName' => 'Лента событий',
                ), $this->tpl_folder.'/Index');
        }

    }