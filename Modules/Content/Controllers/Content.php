<?php
    namespace Modules\Content\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\View;
    use Core\Config;

    class Content extends \Modules\Base {

        public function indexAction() {
            // Check for existance
            $page = DB::select()
                    ->from('content')
                    ->where('alias', '=', Route::param('alias'))
                    ->where('status', '=', 1)
                    ->as_object()
                    ->execute()
                    ->current();
            if( !$page ) { return Config::error(); }
            // Seo
            $this->_seo['h1'] = $page->h1;
            $this->_seo['title'] = $page->title;
            $this->_seo['keywords'] = $page->keywords;
            $this->_seo['description'] = $page->description;
            $this->generateParentBreadcrumbs( $page->parent_id, 'content', 'parent_id' );
            $this->setBreadcrumbs( $page->name );
            // Add plus one to views
            DB::update('content')->set(array('views' => $page->views + 1))->where('id', '=', $page->id)->execute();
            // Get content page children
            $kids = DB::select()->from('content')->where('status', '=', 1)->where('parent_id', '=', $page->id)->order_by('sort', 'ASC')->as_object()->execute();
            // Render template
            $this->_content = View::tpl( array( 'text' => $page->text, 'kids' => $kids ), 'Content/Page' );
        }

    }
    