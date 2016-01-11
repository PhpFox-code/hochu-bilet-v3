<?php
    namespace Core;
    
    class HTTP {

        /**
         * Redirect user to other URI
         * @param string $url - URI we need to relocate user
         */
        public static function redirect($url = '') {
            if (APPLICATION) {
                header('Location: /'.trim($url, '/'));
            } else {
                header('Location: '.HTML::link($url));
            }
            exit(0);
        }

    }