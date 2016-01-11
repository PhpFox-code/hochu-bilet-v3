<?php
    namespace Backend\Modules;

    use Core\Arr;
    use Core\Config;
    use Core\View;
    use Core\System;
    use Core\Cron;
    use Core\Route;
    use Core\HTML;
    use Core\HTTP;
    use Core\QB\DB;
    use Modules\User\Models\User;

    class Base {

        protected $_template = 'Main';
        protected $_content;
        protected $_config = array();
        protected $_seo = array();
        protected $_breadcrumbs = array();
        protected $_filter = NULL;
        protected $_toolbar = NULL;


        public function before() {
            User::factory()->is_remember();
            $this->redirects();
            $this->config();
        }


        public function after() {
            $cron = new Cron;
            $cron->check();
            $this->render();
        }


        private function redirects() {
            if( !User::factory()->_admin AND Route::controller() != 'auth' AND Route::controller() != 'ajax' AND Route::controller() != 'form' ) {
                HTTP::redirect('backend/auth/login');
            }
        }


        private function config() {
            $result = DB::select('key', 'zna')->from('config')->where('status', '=', 1)->find_all();
            foreach($result AS $obj) {
                Config::set($obj->key, $obj->zna);
            }
            $this->setBreadcrumbs('Главная', 'backend');
        }


        private function render() {
            $this->_breadcrumbs = HTML::backendBreadcrumbs($this->_breadcrumbs);
            $data = array();
            foreach ($this as $key => $value) {
                $data[$key] = $value;
            }
            echo View::tpl($data, $this->_template);
            echo System::global_massage();
        }


        protected function setBreadcrumbs( $name, $link = NULL ) {
            $this->_breadcrumbs[] = array('name' => $name, 'link' => $link);
        }


        protected function generateParentBreadcrumbs( $id, $table, $parentAlias, $pre = '/' ) {
            $bread = $this->generateParentBreadcrumbsElement( $id, $table, $parentAlias, array() );
            if ( $bread ) {
                $bread = array_reverse( $bread );
            }
            foreach ( $bread as $obj ) {
                $this->setBreadcrumbs( $obj->h1, $pre.$obj->alias );
            }
        }


        protected function generateParentBreadcrumbsElement( $id, $table, $parentAlias, $bread ) {
            $page = DB::select('id', $parentAlias, 'alias', 'status', 'h1')->from($table)->where('id', '=', $id)->as_object()->execute()->current();
            if( is_object($page) AND $page->status ) {
                $bread[] = $page;
            }
            if( is_object($page) AND (int) $page->$parentAlias > 0 ) {
                return $this->generateParentBreadcrumbsElement( $page->$parentAlias, $table, $parentAlias, $bread );
            }
            return $bread;
        }

    }
