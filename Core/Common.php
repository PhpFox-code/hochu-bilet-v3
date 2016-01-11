<?php
    namespace Core;

    use Core\QB\DB;

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
            return (boolean) DB::select($field)->from($table)->limit(1)->find();
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

    }