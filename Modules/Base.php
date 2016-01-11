<?php
    namespace Modules;

    use Core\Arr;
    use Core\Config;
    use Core\View;
    use Core\System;
    use Core\Cron;
    use Core\Route;
    use Core\HTML;
    use Core\QB\DB;
    use Modules\User\Models\User;

    class Base {

        protected $_template = 'Text';
        protected $_content;
        protected $_config = array();
        protected $_seo = array();
        protected $breadcrumbs = array();

        public function before() {
            User::factory()->is_remember();
            $this->config();
        }


        public function after() {
            $cron = new Cron;
            $cron->check();
            $this->seo();
            $this->render();
        }


        private function config() {
            Config::set( 'content_class', 'wTxt' );
            $result = DB::select('key', 'zna')->from('config')->where('status', '=', 1)->as_object()->execute();
            foreach($result AS $obj) {
                Config::set($obj->key, $obj->zna);
            }
            $result = DB::select('script', 'place')->from('seo_metrika')->where('status', '=', 1)->as_object()->execute();
            foreach ( $result AS $obj ) {
                $this->_seo['metrika'][ $obj->place ][] = $obj->script;
            }
            $result = DB::select('script')->from('seo_counters')->where('status', '=', 1)->as_object()->execute();
            foreach($result AS $obj) {
                $this->_seo['counters'][] = $obj->script;
            }
            $this->setBreadcrumbs('Главная', '');
        }


        private function seo() {
            if ( !Config::get( 'error' ) ) {
                $seo = DB::select('h1', 'title', 'keywords', 'description', 'text')
                        ->from('seo_links')
                        ->where('status', '=', 1)
                        ->where('link', '=', Arr::get( $_SERVER, 'REQUEST_URI' ))
                        ->as_object()->execute()->current();
                if ($seo) {
                    $this->_seo['h1'] = $seo->h1;
                    $this->_seo['title'] = $seo->title;
                    $this->_seo['keywords'] = $seo->keywords;
                    $this->_seo['description'] = $seo->description;
                    $this->_seo['seo_text'] = $seo->text;
                }
            } else {
                $this->_seo['h1'] = 'Ошибка 404! Страница не найдена';
                $this->_seo['title'] = 'Ошибка 404! Страница не найдена';
                $this->_seo['keywords'] = 'Ошибка 404! Страница не найдена';
                $this->_seo['description'] = 'Ошибка 404! Страница не найдена';
                $this->_seo['seo_text'] = NULL;
            }
        }


        private function render() {
            if( Config::get( 'error' ) ) {
                $this->_template = '404';
            }
            $this->_breadcrumbs = HTML::breadcrumbs($this->_breadcrumbs);
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
