<?php
    namespace Core;

    class Arr {
        
        /**
         *      Convert an array to an object
         *      @param array $array - array with data
         */
        public static function to_object($array) {
            $json   = json_encode($array);
            $object = json_decode($json);
            return $object;
        }
		
		/**
		 * Retrieve a single key from an array. If the key does not exist in the
		 * array, the default value will be returned instead.
		 *
		 *     // Get the value "username" from $_POST, if it exists
		 *     $username = Arr::get($_POST, 'username');
		 *
		 *     // Get the value "sorting" from $_GET, if it exists
		 *     $sorting = Arr::get($_GET, 'sorting');
		 *
		 * @param   array   $array      array to extract from
		 * @param   string  $key        key name
		 * @param   mixed   $default    default value
		 * @return  mixed
		 */
		public static function get($array, $key, $default = NULL) {
			return isset($array[$key]) ? $array[$key] : $default;
		}
        
        /**
    	 * Retrieves multiple paths from an array. If the path does not exist in the
    	 * array, the default value will be added instead.
    	 *
    	 *     // Get the values "username", "password" from $_POST
    	 *     $auth = Arr::extract($_POST, array('username', 'password'));
    	 *
    	 *     // Get the value "level1.level2a" from $data
    	 *     $data = array('level1' => array('level2a' => 'value 1', 'level2b' => 'value 2'));
    	 *     Arr::extract($data, array('level1.level2a', 'password'));
    	 *
    	 * @param   array  $array    array to extract paths from
    	 * @param   array  $paths    list of path
    	 * @param   mixed  $default  default value
    	 * @return  array
    	 */
    	public static function extract($array, array $paths, $default = NULL) {
    		$found = array();
    		foreach ($paths as $path) {
    			Arr::set_path($found, $path, Arr::path($array, $path, $default));
    		}
    		return $found;
    	}
        
    }