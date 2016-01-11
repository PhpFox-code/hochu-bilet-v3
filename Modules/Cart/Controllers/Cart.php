<?php
    namespace Modules\Cart\Controllers;

    use Modules\Cart\Models\Cart AS C;
    use Core\View;
    use Core\Config;
    
    class Cart extends \Modules\Base {

        public function before() {
            parent::before();
            $this->setBreadcrumbs( 'Корзина', 'cart' );
            $this->_template = 'Cart';
        }

        // Cart page with order form
        public function indexAction() {
            // Seo
            $this->_seo['h1'] = 'Корзина';
            $this->_seo['title'] = 'Корзина';
            $this->_seo['keywords'] = 'Корзина';
            $this->_seo['description'] = 'Корзина';
            // Get cart items
            $cart = C::factory()->get_list_for_basket();
            // Render template
            $this->_content = View::tpl( array('cart' => $cart, 'payment' => Config::get('order.payment'), 'delivery' => Config::get('order.delivery')), 'Cart/Index' );
        }

    }