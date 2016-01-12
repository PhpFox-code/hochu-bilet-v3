<?php
    namespace Core;

    use Core\QB\DB;
    use Core\Validation\Valid;
    use Core\QB\Database;

    class Common {

        /**
         * @param string $table - table in witch we insert data
         * @param array $data - associative array with insert data
         * @return DB object with part of the query
         */
        public static function insert( $table, $data ) {
            foreach ($data as $key => $value) {
                if ($value == 'null') {
                    $data[$key] = DB::expr('null');
                }
                else {
                    $data[$key] = stripslashes($value);
                }
            }
            if( !isset($data['created_at']) AND Common::checkField($table, 'created_at')) {
                $data['created_at'] = time();
            }
            $keys = $values = array();
            foreach( $data AS $key => $value ) {
                $keys[] = $key;
                $values[] = $value;
            }
            return DB::insert($table, $keys)->values($values);
        }


        /**
         * @param string $table - table in witch we update data
         * @param array $data - associative array with data to update
         * @return DB object with part of the query
         */
        public static function update( $table, $data ) {
            foreach ($data as $key => $value) {
                if ($value == 'null') {
                    $data[$key] = DB::expr('null');
                }
                else {
                    $data[$key] = stripslashes($value);
                }
            }
            if( !isset($data['updated_at']) AND Common::checkField($table, 'updated_at') ) {
                $data['updated_at'] = time();
            }
            return DB::update($table)->set($data);
        }


        /**
         * @param string $table - table for check field
         * @param string $field - check this field
         * @return bool
         */
        public static function checkField($table, $field) {
            $cResult = DB::query(Database::SELECT, 'SHOW FIELDS FROM `'.$table.'`')->execute();
            $found = FALSE;
            foreach( $cResult AS $arr ) {
                if( $arr['Field'] == $field ) {
                    $found = TRUE;
                }
            }
            return $found;
        }


        /**
         * @param string $table - table for check alias
         * @param string $value - checked alias
         * @param int $id - ID if need off current row with ID = $id
         * @return string - unique alias
         */
        public static function getUniqueAlias($table, $value, $id = NULL) {
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))
                ->from($table)
                ->where('alias', '=', $value);
            if( $id <> NULL ) {
                $count->where('id', '!=', $id);
            }
            $count = $count->count_all();
            if($count) {
                return $value.rand(1000, 9999);
            }
            return $value;
        }

        public static function setFilter($result) {
            if (!is_array(static::$filters)) {
                return $result;
            }
            foreach (static::$filters as $key => $value) {
                if (isset($key) && isset($_GET[$key]) && trim($_GET[$key])) {
                    $_GET[$key] = urldecode($_GET[$key]);
                    $get = strip_tags($_GET[$key]);
                    $get = trim($get);
                    if (!Arr::get($value, 'action', NULL)) {
                        $action = '=';
                    } else {
                        $action = Arr::get($value, 'action');
                    }
                    $table = false;
                    if (Arr::get($value, 'table', NULL)) {
                        $table = Arr::get($value, 'table');
                    } else if(Arr::get($value, 'table', NULL) === NULL) {
                        $table = static::$table;
                    }
                    if ($action == 'LIKE') {
                        $get = '%'.$get.'%';
                    }
                    if( Arr::get($value, 'field') ) {
                        $key = Arr::get($value, 'field');
                    }
                    if ($table !== false) {
                        $result->where($table.'.'.$key, $action, $get);
                    } else {
                        $result->where(DB::expr($key), $action, $get);
                    }
                }
            }
            return $result;
        }

        /**
         * @param null/integer $status - 0 or 1
         * @return int
         */
        public static function countRows($status = NULL, $filter = true) {
            $result = DB::select(array(DB::expr('COUNT('.static::$table.'.id)'), 'count'))->from(static::$table);
            if( $status !== NULL ) {
                $result->where(static::$table.'.status', '=', $status);
            }
            if( $filter ) {
                $result = static::setFilter($result);
            }
            return $result->count_all();
        }


        /**
         * @param mixed $value - value
         * @param string $field - field
         * @param null/integer $status - 0 or 1
         * @return object
         */
        public static function getRow($value, $field = 'id', $status = NULL) {
            $result = DB::select()->from(static::$table)->where($field, '=', $value);
            if( $status !== NULL ) {
                $result->where('status', '=', $status);
            }
            return $result->find();
        }


        /**
         * @param null/integer $status - 0 or 1
         * @param null/string $sort
         * @param null/string $type - ASC or DESC. No $sort - no $type
         * @param null/integer $limit
         * @param null/integer $offset - no $limit - no $offset
         * @return object
         */
         public static function getRows($status = NULL, $sort = NULL, $type = NULL, $limit = NULL, $offset = NULL, $filter = true) {
            $result = DB::select()->from(static::$table);
            if( $status !== NULL ) {
                $result->where('status', '=', $status);
            }
            if( $filter ) {
                $result = static::setFilter($result);
            }
            if( $sort !== NULL ) {
                if( $type !== NULL ) {
                    $result->order_by($sort, $type);
                } else {
                    $result->order_by($sort);
                }
            }
            $result->order_by('id', 'DESC');
            if( $limit !== NULL ) {
                $result->limit($limit);
                if( $offset !== NULL ) {
                    $result->offset($offset);
                }
            }
            return $result->find_all();
        }

        /**
         * @param array $data
         * @return bool
         */
        public static function valid($data = array()) {
            if( !static::$rules ) {
                return TRUE;
            }
            $valid = new Valid($data, static::$rules);
            $errors = $valid->execute();
            if( !$errors ) {
                return TRUE;
            }
            $message = Valid::message($errors);
            Message::GetMessage(0, $message, FALSE);
            return FALSE;
        }

        /**
         * @param mixed $value - value
         * @param string $field - field
         * @return object
         */
        public static function delete($value, $field = 'id') {
            return DB::delete(static::$table)->where($field, '=', $value)->execute();
        }
    }