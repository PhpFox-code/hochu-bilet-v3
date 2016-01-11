<?php
    namespace Modules\Brone\Controllers;

    use Core\QB\DB;
    use Core\View;
    use Core\Config AS conf;

    class Brone extends \Modules\Base {

        public function indexAction() {
            $this->_template = 'TextForm';
            // Check for existance
            $page = DB::select('h1', 'title', 'keywords', 'description', 'text', 'name')
                    ->from('control')
                    ->where('alias', '=', 'book_tickets')
                    ->as_object()
                    ->execute()
                    ->current();
            if( !$page ) { return conf::error(); }
            // Seo
            $this->_seo['h1'] = $page->h1;
            $this->_seo['title'] = $page->title;
            $this->_seo['keywords'] = $page->keywords;
            $this->_seo['description'] = $page->description;
            $this->setBreadcrumbs( $page->name );
            // Render template
            $this->_content = View::tpl( array( 'text' => $page->text, 'kids' => array() ), 'Brone/Index' );
        }

    }
    