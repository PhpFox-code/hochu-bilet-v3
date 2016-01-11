<?php
    namespace Core;
    use Plugins\Profiler\Profiler;

    /**
     *  Class for routing on the site
     */
    class Route {
        
        static $_instance; // Singletone static variable 

        /**
         *  Singletone static method
         */
        static function factory() {
            if(self::$_instance == NULL) { self::$_instance = new self(); }
            return self::$_instance;
        }


        protected $_params; // Parameters for current page
        protected $_controller; // Current controller
        protected $_action; // Current action
        protected $_module; // Current module

        
        /**
         *  Get all parameters on current page
         */
        public static function params() {
            return Route::factory()->getParams();
        }

        
        /**
         *  Get one parameter by alias
         *  @param string $key - alias for parameter we need
         *  @return mixed      - parameter $key from $_params
         */
        public static function param( $key ) {
            return Route::factory()->getParam( $key );
        }


        /**
         *  Get current controller
         */
        public static function controller() {
            return Route::factory()->getController();
        }


        /**
         *  Get current action
         */
        public static function action() {
            return Route::factory()->getAction();
        }


        /**
         *  Get current module
         */
        public static function module() {
            return Route::factory()->getModule();
        }


        /**
         *  Real function to get all parameters on current page
         */
        public function getParams() {
            return $this->_params;
        }


        /**
         *  Set parameter to parameters array
         *  @param string $key   - alias for parameter we set
         *  @param string $value - value for parameter we set
         */
        public function setParam( $key, $value ) {
            $this->_params[$key] = $value;
        }


        /**
         *  Real function to get one parameter by alias
         *  @param string $key - alias for parameter we need
         *  @return mixed      - parameter $key from $_params if exists or NULL if doesn't exist
         */
        public function getParam( $key ) {
            return Arr::get($this->_params, $key, NULL);
        }


        /**
         *  Real function to get controller
         */
        public function getController() {
            return $this->_controller;
        }


        /**
         *  Real function to get action
         */
        public function getAction() {
            return $this->_action;
        }


        /**
         *  Real function to get module
         */
        public function getModule() {
            return $this->_module;
        }


        /**
         *  Set action
         */
        public function setAction($fakeAction) {
            return $this->_action = $fakeAction;
        }


        public $_defaultAction = 'index'; // Default action
        public $_uri; // Current URI
        public $_modules = array(); // Modules we include to project

        protected $_routes = array(); // Array with routes on the full site
        protected $_regular = 'a-zA-Z0-9-_\\\[\]\{\}\:\,\*'; // List of good signs in regular expressions in routes


        /**
         *  Foreplay
         */
        function __construct() {
            $this->setURI();
            $this->setModules();
            $this->setRoutes();
            $this->run();
            $this->setLanguage();
        }


        /**
         *  Uses for multilang sites
         */
        protected function setLanguage() {
            if ( class_exists('I18n') ) {
                if ( isset($this->_params['lang']) ) {
                    \I18n::lang($this->_params['lang']);
                } else {
                    $this->_params['lang'] = \I18n::$lang;
                }
            }
        }


        /**
         *  Set current URI
         */
        protected function setURI() {
            if(!empty($_SERVER['REQUEST_URI'])) {
                $tmp = explode('?', trim(Arr::get($_SERVER, 'REQUEST_URI'), '/'));
                $this->setGET(Arr::get($tmp, 1));
                return $this->_uri = $tmp[0];
            }
            if(!empty($_SERVER['PATH_INFO'])) {
                $tmp = explode('?', trim(Arr::get($_SERVER, 'PATH_INFO'), '/'));
                $this->setGET(Arr::get($tmp, 1));
                return $this->_uri = $tmp[0];
            }
            if(!empty($_SERVER['QUERY_STRING'])) {
                $tmp = explode('?', trim(Arr::get($_SERVER, 'QUERY_STRING'), '/'));
                $this->setGET(Arr::get($tmp, 1));
                return $this->_uri = $tmp[0];
            }
        }


        /**
         *  Set GET parameters
         *  @param string $get - all after "?" in current URI
         */
        protected function setGET( $get ) {
            $get = explode('&', $get);
            foreach ($get as $element) {
                $g = explode('=', $element);
                $_GET[Arr::get($g, 0)] = Arr::get($g, 1);
            }
        }


        /**
         *  Generate array with modules we need in this project
         */
        protected function setModules() {
            if (file_exists(HOST.APPLICATION.'/Config/modules.php')) {
                $this->_modules = require HOST.APPLICATION.'/Config/modules.php';
            }
        }


        /**
         *  Generate array with default routes and routes in all modules we include
         */
        protected function setRoutes() {
            // Routes from modules
            foreach ($this->_modules as $module) {
                $path = HOST.APPLICATION.'/Modules/'.$module.'/Routing.php';
                if(file_exists($path)) {
                    $config = require_once $path;
                    if (is_array($config) && !empty($config)) {
                        $this->_routes += $config;
                    }
                }
            }
            // Default routes
            $this->_routes += include_once HOST.APPLICATION.'/Config/routing.php';
        }


        /**
         *  Generate controller, action, parameters from url
         */
        protected function run() {
            foreach($this->_routes as $pattern => $route) {
                // Check if pattern same as current URI
                if( $pattern == $this->_uri ) {
                    return $this->set($route);
                }
                // Get array from elements of pattern and uri (explode them by "/")
                $_pattern = $pattern ? explode('/', $pattern) : array();
                $_uri = $this->_uri ? explode('/', $this->_uri) : $this->_uri;
                // Check if count of elements in pattern and uri the same
                // Continue if it is false
                if( count($_pattern) != count($_uri) ) {
                    continue;
                }
                // Check routing by key expressions
                $flag = true; // to check if route good. If true - good, false - bad
                $params = array(); // parameters list for current route
                foreach ($_uri as $key => $value) {
                    $_p = $_pattern[$key];
                    // Check if we have variable
                    if( preg_match('/^\{['.$this->_regular.']*\}$/', $_p) ) {
                        // Clear expression
                        $p = substr($_p, 1, -1);
                        // Get key for parameter and expression to check value
                        $p = explode(':', $p);
                        // If we don't have regular expression
                        if (count($p) == 1) {
                            $params[$p[0]] = $value;
                        // If we have regular expression
                        } else if (count($p) == 2) {
                            // If value is good for expression
                            if (preg_match('/^'.$p[1].'$/', $value)) {
                                $params[$p[0]] = $value;
                            // If value is bad for expression
                            } else {
                                $flag = false;
                            }
                        // If we have wrong pattern
                        } else {
                            $flag = false;
                        }
                    // Check if pattern is the same to value
                    } else if( $_p == $value ) {
                        continue;
                    // If something wrong with pattern or uri
                    } else {
                        $flag = false;
                    }
                }
                // Set parameters if this route is good
                if ($flag) {
                    return $this->set($route, $params);
                }
            }
            Config::error();
        }


        /**
         *  Set route parameters
         *  @param string $route  - current route
         *  @param array  $params - parameters for current route
         *  @return                 boolean
         */
        protected function set( $route, $params = array() ) {
            $array = explode('/', $route);
            // Set module
            if (isset($params['module'])) {
                $this->_module = Arr::get($params, 'module', NULL);
                unset($params['module']);
            } else {
                $this->_module = Arr::get($array, 0, NULL);
            }
            // Set controller
            if (isset($params['controller'])) {
                $this->_controller = Arr::get($params, 'controller', NULL);
                unset($params['controller']);
            } else {
                $this->_controller = Arr::get($array, 1, NULL);
            }
            // Set action
            if (isset($params['action'])) {
                $this->_action = Arr::get($params, 'action', NULL);
                unset($params['action']);
            } else {
                $this->_action = Arr::get($array, 2, $this->_defaultAction);
            }
            // Set else parameters
            foreach ($params as $key => $value) {
                $this->setParam($key, $value);
            }
            return true;
        }


        /**
         *  Start site. Initialize controller
         */
        public function execute() {
            if( !file_exists(HOST.APPLICATION.'/Modules/Base.php') ) {
                return Config::error();
            }
            require_once HOST.APPLICATION.'/Modules/Base.php';
            $module = ucfirst(Route::module());
            $controller = ucfirst(Route::controller());
            $action = Route::action();

            if( APPLICATION) { $path[] = str_replace('/', '', APPLICATION); }
            $path[] = 'Modules';
            if( $module ) { $path[] = $module; }
            $path[] = 'Controllers';
            $path[] = $controller;
            if( file_exists(HOST.'/'.implode('/', $path).'.php') ) {
                return $this->start($path, $action);
            }
            unset($path[count($path) - 2]);
            if( file_exists(HOST.'/'.implode('/', $path).'.php') ) {
                return $this->start($path, $action);
            }
            return Config::error();
        }


        /**
         *  Run controller->action
         */
        protected function start($path, $action) {
            $action .= 'Action';
            $controller = implode('\\', $path);
            $controller = new $controller;
            $controller->before();
            $token = Profiler::start('Profiler', 'Center');
            $controller->{$action}();
            Profiler::stop($token);
            $controller->after();
        }

    }