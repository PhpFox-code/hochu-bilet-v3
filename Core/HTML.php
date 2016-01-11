<?php
    namespace Core;
    
    class HTML {

        /**
         *  Generate good link. Usefull in multilang sites
         *  @param  string $link - link
         *  @return string       - good link
         */
        public static function link( $link = '' ) {
            $link = trim($link, '/');
            if ($link == 'index') { $link = ''; }
            if (class_exists('I18n')) {
                if (!$link) {
                    if (\I18n::$default_lang !== \I18n::$lang) {
                        return '/'.\I18n::$lang;
                    }
                } else {
                    $link = \I18n::$lang.'/'.$link;
                }
            }
            return '/'.$link;
        }


        /**
         *  Generate breadcrumbs from array
         *  @param  array  $bread - array with names and links
         *  @return string        - breadcrumbs HTML
         */
        public static function breadcrumbs( $bread ) {
            if (count($bread) <= 1) { return ''; }
            $last = $bread[ count($bread) - 1 ];
            unset($bread[ count($bread) - 1 ]);
            $html = '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
            foreach ($bread as $value) {
                $html .= '<span class="mainButton" typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.HTML::link($value['link']).'">'.$value['name'].'</a></span>';
            }
            $html .= '<span class="textBreadcrumbs" typeof="v:Breadcrumb">'.$last['name'].'</span>';
            $html .= '</div>';
            return $html;
        }


        /**
         *  Generate breadcrumbs from array for backend
         *  @param  array  $bread - array with names and links
         *  @return string        - breadcrumbs HTML
         */
        public static function backendBreadcrumbs( $bread ) {
            if (count($bread) <= 1) { return ''; }
            $last = $bread[ count($bread) - 1 ];
            unset($bread[ count($bread) - 1 ]);
            if (!count($bread)) { return ''; }
            $first = $bread[0];
            unset($bread[0]);
            $html = '<div class="crumbs"><ul class="breadcrumb">';
            $html .= '<li><i class="fa-home"></i><a href="'.HTML::link($first['link']).'">&nbsp;'.$first['name'].'</a></li>';
            foreach ($bread as $value) {
                $html .= '<li><a href="'.HTML::link($value['link']).'">&nbsp;'.$value['name'].'</a></li>';
            }
            $html .= '<li class="current" style="color: #949494 !important;">&nbsp;'.$last['name'].'</li>';
            $html .= '</ul></div>';
            return $html;
        }


        /**
         * Create path to media in frontend
         * @param  string $filename - path to file
         * @return string
         */
        public static function media( $file ) {
            return '/Media/' . trim($file, '/');
        }


        /**
         * Create path to media in backend
         * @param  string $filename - path to file
         * @return string
         */
        public static function bmedia( $file ) {
            return '/backend/Media/' . trim($file, '/');
        }


        /**
         * Put die after <pre>
         * @param mixed $object - what we want to <pre>
         */
        public static function preDie( $object ) {
            echo '<pre>';
            print_r($object);
            echo '</pre>';
            die;
        }


        /**
         * Emulation of php function getallheaders()
         */
        public static function emu_getallheaders() {
            foreach($_SERVER as $h=>$v)
            if(ereg('HTTP_(.+)',$h,$hp))
                $headers[$hp[1]]=$v;
            return $headers;
        }


        /**
         * Convert special characters to HTML entities. All untrusted content
         * should be passed through this method to prevent XSS injections.
         *
         *     echo HTML::chars($username);
         *
         * @param   string  $value          string to convert
         * @param   boolean $double_encode  encode existing entities
         * @return  string
         */
        public static function chars($value, $double_encode = TRUE) {
            return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8', $double_encode);
        }

    }