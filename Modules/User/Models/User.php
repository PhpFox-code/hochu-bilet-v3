<?php
	namespace Modules\User\Models;
    use Core\QB\DB;
    use Core\HTTP;
    use Core\Text;
    use Core\Arr;
    use Core\Cookie;

	class User {
        
		static    $_instance;
        
		protected $_tbl           = 'users';  // Users table name
        protected $_session_user  = 'user';   // Users session name
        
        private   $_salt          = 'xQ1=\G(8E=1A~)?8;7]]E/U*,kbm=aII'; // Salt
        private   $_hash_type     = 'sha256'; // Hash type

        public    $_info;
        public    $_admin         = false;

		function __construct() {
		    $this->_info = $this->get_logged_user_information();
            if( $this->_info AND $this->_info->role_id == 2 ) {
                $this->_admin = true;
            }
		}

        function __destruct() {}

		static function factory() {
            if(self::$_instance == NULL) { self::$_instance = new user(); }
            return self::$_instance;
        }

        static function info() {
            return User::factory()->_info;
        }

        static function admin() {
            return User::factory()->_admin;
        }
        
        /**
         *      If the user logged in, it will return his information, otherwise - return false
         */
        public function get_logged_user_information() {
            if(!isset($_SESSION[$this->_session_user])) { return false; }
            if((int) $_SESSION[$this->_session_user] == 0) { return false; }
            return DB::select()->from($this->_tbl)->where('id', '=', $_SESSION[$this->_session_user])->where('status', '=', 1)->as_object()->execute()->current();
        }
        
        /**
         *      Get user by login, password and status if exists
         *      @param $login - user login
         *      @param $password - user password
         *      @param $status - user status
         */
        public function get_user_if_isset($login = NULL, $password = NULL, $status = NULL) {
            if($login == NULL) { return false; }
            $result = DB::select()->from($this->_tbl)->where('login', '=', $login);
            if($status !== NULL) { $result->where('status', '=', $status); }
            if($password !== NULL) { $result->where('password', '=', $this->hash_password($password)); }
            return $result->as_object()->execute()->current();
        }

        /**
         *      Get user by email, password and status if exists
         *      @param $email - user email
         *      @param $password - user password
         *      @param $status - user status
         */
        public function get_user_by_email($email = NULL, $password = NULL, $status = NULL) {
            if($email == NULL) { return false; }
            $result = DB::select()->from( $this->_tbl )->where('email', '=', $email);
            if($status !== NULL) { $result->where('status', '=', $status); }
            if($password !== NULL) { $result->where('password', '=', $this->hash_password($password)); }
            return $result->as_object()->execute()->current();
        }
        
        /**
         *      Get user by hash and status (0, 1) if exists
         *      @param $hash - user hash
         *      @param $status - user status
         */
        public function get_user_by_hash($hash = NULL, $status = NULL) {
			if($hash == NULL) { return false; }
            $result = DB::select()->from( $this->_tbl )->where('hash', '=', $hash);
            if($status !== NULL) { $result->where('status', '=', $status); }
            return $result->as_object()->execute()->current();
        }
        
        /**
         *      Generate a random password
         */
        public static function generate_random_password() {
            return Text::random('alnum', 8);
        }
        
        /**
         *      Generate password hash
         *      @param $password - desired password
         *      @param $salt - Salt for hash. If empty - use salt default
         */
        public function hash_password($password = NULL, $salt = NULL) {
			if($password == NULL) { return false; }
            if($salt == NULL) { $salt = $this->_salt; }
            return hash($this->_hash_type, $password.$salt);
        }
        
        /**
         *      Update users password
         *      @param $id - ID of the user who needs a new password
         *      @param $password - desired password
         *      @param $salt - Salt for hash. If empty - use salt default
         */
        public function update_password($id = NULL, $password = NULL, $salt = NULL) {
			if($id == NULL) { return false; }
			if($password == NULL) { return false; }
            return DB::update($this->_tbl)->set(array('password' => $this->hash_password($password, $salt), 'updated_at' => time()))->where('id', '=', $id)->execute();
        }
        
        /**
         *      Compare the passwords
         *      @param $password - Users entered password
         *      @param $hash_from_db - Hash from the database
         *      @param $salt - Salt for hash. If empty - use salt default
         */
        public function check_password($password = NULL, $hash_from_db = NULL, $salt = NULL) {
			if($password == NULL) { return false; }
			if($hash_from_db == NULL) { return false; }
            if($salt == NULL) { $salt = $this->_salt; }
            $password_hash = hash($this->_hash_type, $password.$salt);
            if($password_hash == $hash_from_db) { return true; }
            return false;
        }
        
        /**
         *      Generate users hash
         *      @param $login - user login/email
         *      @param $password - user password
         *      @param $salt - Salt for hash. If empty - use salt default
         */
        public function hash_user($login = NULL, $password = NULL, $salt = NULL) {
			if($password == NULL) { return false; }
			if($login == NULL) { return false; }
            if($salt == NULL) { $salt = $this->_salt; }
            $hash = hash($this->_hash_type, $login.$password.$salt);
            return $hash;
        }
        
        /**
         *      Checking hash...
         *      @param $check_hash - verifiable hash
         *      @param $login - user login/email
         *      @param $password - user password
         *      @param $salt - Salt for hash. If empty - use salt default
         */
        public function check_hash($checked_hash = NULL, $login = NULL, $password = NULL, $salt = NULL) {
			if($checked_hash == NULL) { return false; }
			if($login == NULL) { return false; }
			if($password == NULL) { return false; }
            if($salt == NULL) { $salt = $this->_salt; }
            $hash = hash($this->_hash_type, $login.$password.$salt);
            if($hash == $checked_hash) { return true; }
            return false;
        }
        
        /**
         *      Auth user
         *      @param object $user - user information
         *      @param boolean $remember - remember user ?
         */
        public function auth($user, $remember = false) {
            $_SESSION[$this->_session_user] = $user->id;
            $json = json_encode(array(
                'remember'  => (int) $remember,
                'exit'      => 0,
                'id'        => $user->id,
            ));
            Cookie::set('user', base64_encode($json), 60*60*24*7);
            return true;
        }
        
        /**
         *      Logout from user private panel
         */
        public function logout() {
            if($_SESSION[$this->_session_user]) {
                unset($_SESSION[$this->_session_user]);
                $cookie = base64_decode(Cookie::get('user'));
                $cookie = json_decode($cookie);
                $json   = json_encode(array(
                    'remember'  => (int) $cookie->remember,
                    'exit'      => 1,
                    'id'        => $cookie->id,
                ));
                Cookie::set('user', base64_encode($json), 60*60*24*7);
            }
        }
        
        /**
         *      Check if user want to remember his password
         *      If true - auth him
         */
        public function is_remember() {
            if( User::info() ) { return false; }
            if(!isset($_COOKIE['user'])) { return false; }
            $cookie = base64_decode(Cookie::get('user'));
            $cookie = json_decode($cookie);
            if(!isset($cookie->remember) || intval($cookie->remember) == 0) { return false; }
            if(!isset($cookie->id) || (int) $cookie->id == 0) { return false; }
            if(isset($cookie->exit) && intval($cookie->exit) == 1) { return false; }
            if(!isset($cookie->exit)) {
                $json = json_encode(array(
                    'remember'  => (int) $cookie->remember,
                    'exit'      => 0,
                    'id'        => $cookie->id,
                ));
                Cookie::set('user', base64_encode($json), 60*60*24*7);
            }
            $user = DB::select()->from($this->_tbl)->where('id', '=', $cookie->id)->where('status', '=', 1)->as_object()->execute()->current();
            if(!$user) { return false; }
            $lgn  = $this->auth($user, $cookie->remember);
            if( $lgn ) {
                HTTP::redirect(Arr::get($_SERVER, 'REQUEST_URI'));
            }
            return false;
        }
        
        /**
         *      User registration
         *      @param array $data - user data from POST
         */
        public function registration($data = array()) {
            $data['hash'] = $this->hash_user($data['email'], $data['password']);
            $data['password'] = $this->hash_password($data['password']);
            $data['created_at'] = time();
            $keys = array();
            $values = array();
            foreach ($data as $key => $value) {
                $keys[] = $key;
                $values[] = $value;
            }
            return DB::insert($this->_tbl, $keys)->values($values)->execute();
        }

	}