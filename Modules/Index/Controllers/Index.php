<?php
    namespace Modules\Index\Controllers;

    use Core\QB\DB;
    use Core\Config;
    use Core\Route;
    
    class Index extends \Modules\Base {

        public function indexAction() {
            $this->_template = 'Main';
            // Check for existance
            $page = DB::select('h1', 'title', 'keywords', 'description', 'text')
                    ->from('control')
                    ->where('alias', '=', 'index')
                    ->as_object()
                    ->execute()
                    ->current();
            if( !$page ) { return Config::error(); }
            // Seo
            $this->_seo['h1'] = $page->h1;
            $this->_seo['title'] = $page->title;
            $this->_seo['keywords'] = $page->keywords;
            $this->_seo['description'] = $page->description;
            // Render template
            $this->_content = $page->text;
        }

        public function after_paymentAction() {
            $this->_template = 'Text';
            // Check for existance
            $page = DB::select('h1', 'title', 'keywords', 'description', 'text')
                    ->from('control')
                    ->where('alias', '=', 'after_payment')
                    ->as_object()
                    ->execute()
                    ->current();
            if( !$page ) { return Config::error(); }

            // Seo
            $this->_seo['h1'] = $page->h1;
            $this->_seo['title'] = $page->title;
            $this->_seo['keywords'] = $page->keywords;
            $this->_seo['description'] = $page->description;
            // Render template
            $this->_content = $page->text;
        }

    }
    