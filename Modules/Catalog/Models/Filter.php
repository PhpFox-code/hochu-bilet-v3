<?php
    namespace Modules\Catalog\Models;
    use Core\QB\DB;
    use Core\Route;
    use Core\Config AS conf;
    use Core\Arr;
    
    class Filter {

        static $_items = array(); // Array of all items in group

        // Instance method. I use it for not execute same big sql query twice ( filter, catalog items list )
        static function getFilteredItems() {
            if(!self::$_items) {
                $items = DB::select('catalog.*',
                                    array('brands.alias', 'brand_alias'),
                                    array('brands.name', 'brand_name'),
                                    array('models.alias', 'model_alias'),
                                    array('models.name', 'model_name'),
                                    array('sizes.alias', 'size_alias'),
                                    array('sizes.name', 'size_name'),
                                    array('specifications.alias', 'specification_alias'),
                                    array('specifications.name', 'specification_name'),
                                    array('specifications_values.alias', 'specification_value_alias'),
                                    array('specifications_values.name', 'specification_value_name')
                        )
                        ->from('catalog')
                        ->join('catalog_images', 'LEFT')->on('catalog_images.catalog_id', '=', 'catalog.id')->on('catalog_images.main', '=', DB::expr('"1"'))
                        ->join('brands', 'LEFT')->on('brands.id', '=', 'catalog.brand_id')->on('brands.status', '=', DB::expr('"1"'))
                        ->join('models', 'LEFT')->on('models.id', '=', 'catalog.model_id')->on('models.status', '=', DB::expr('"1"'))
                        ->join('catalog_sizes', 'LEFT')->on('catalog_sizes.catalog_id', '=', 'catalog.id')
                        ->join('sizes', 'LEFT')->on('sizes.id', '=', 'catalog_sizes.size_id')->on('sizes.status', '=', DB::expr('"1"'))
                        ->join('catalog_specifications_values', 'LEFT')->on('catalog_specifications_values.catalog_id', '=', 'catalog.id')
                        ->join('specifications', 'LEFT')->on('specifications.id', '=', 'catalog_specifications_values.specification_id')->on('specifications.status', '=', DB::expr('"1"'))
                        ->join('specifications_values', 'LEFT')->on('catalog_specifications_values.specification_value_id', '=', 'specifications_values.id')->on('specifications_values.status', '=', DB::expr('"1"'))
                        ->where('catalog.status', '=', '1')->where('catalog.parent_id', '=', Route::param('group'))
                        ->as_object()->execute();
                return self::$_items = $items;
            }
            return self::$_items;
        }

        /** 
         * Generate link with filter parameters
         * @param  [string] $key   [specification alias]
         * @param  [string] $value [specification value alias]
         * @return [string]        [link]
         */
        public static function generateLinkWithFilter($key, $value) {
            // Delimiters
            $delim = conf::get('filter.delimiter');
            $equal = conf::get('filter.equal');
            $enum = conf::get('filter.enum');
            // Devide url from GET parameters
            $uri = Arr::get($_SERVER, 'REQUEST_URI');
            $uri = explode('?', $uri);
            $link = $uri[0];
            $get = '';
            if (count($uri) > 1) {
                $get = '?'.$uri[1];
            }
            // Clear link from pagination
            if( (int) Route::param('page') ) {
                $link = str_replace('/page/'.(int) Route::param('page'), '', $link);
            }
            // If filter is empty - create it and return
            $filter = conf::get('filter_array');
            if( !$filter ) {
                return $link.'/filter/'.$key.$equal.$value.$get;
            }
            // Clear link from filter
            $link = str_replace('/filter/'.Route::param('filter'), '', $link);
            // Setup filter
            if( isset($filter[$key]) ) {
                if( !in_array($value, $filter[$key]) ) {
                    $filter[$key][] = $value;
                } else {
                    unset($filter[$key][array_search($value, $filter[$key])]);
                    if( empty($filter[$key]) OR (count($filter[$key]) == 1 AND !trim(end($filter[$key])))) {
                        unset($filter[$key]);
                    }
                }
            } else {
                $filter[$key] = array($value);
            }
            // Check for models that free from brands
            if(isset($filter['model']) AND !isset($filter['brand'])) {
                unset($filter['model']);
            }
            // Genrate link with filter
            $filter = Filter::generateFilter($filter);
            // Return link
            if($filter) {
                return $link.'/filter/'.$filter.$get;
            }
            return $link.$get;
        }


        /**
         *  Create filter part of URI
         *  @param  [array]  $array [associative array with filter elements]
         *  @return [string]        [part of new URI]
         */
        public static function generateFilter( $array ) {
            // Delimiters
            $delim = conf::get('filter.delimiter');
            $equal = conf::get('filter.equal');
            $enum = conf::get('filter.enum');
            // Sort filter elements
            $array = Filter::sortFilter( $array );
            // Generate and return filters part
            $link = array();
            foreach($array AS $key => $values) {
                $link[] = $key.$equal.implode($enum, $values);
            }
            return implode($delim, $link);
        }


        /**
         *  Sort our filter
         *  @param  [array] $array  Our filter in array
         *  @return [array]         Our filter but sorted!
         */
        public static function sortFilter( $array ) {
            $template = conf::get('sortable');
            $filter = array();
            foreach( $template AS $tpl ) {
                if( isset($array[$tpl]) AND !empty($array[$tpl]) AND !(count($array[$tpl]) == 1 AND !trim(end($array[$tpl]))) ) {
                    $filter[$tpl] = $array[$tpl];
                    sort($filter[$tpl]);
                    reset($filter[$tpl]);
                }
            }
            return $filter;
        }


        /**
         *  Check for existance parameter in the filter
         *  @param  [string] $element       alias of element of the filter
         *  @param  [string] $specification alias of the filter
         *  @return [string]                Word 'checked' or NULL
         */
        public static function checked($element, $specification) {
            if( in_array($element, Arr::get(conf::get('filter_array'), $specification, array())) ) {
                return 'checked';
            }
            return NULL;
        }


        /**
         *  Get a minimal number for price filter
         *  @param  [int] $realMin  real minimal number for current catalog group
         *  @return [int]           minimal number for value in price filter
         */
        public static function min( $realMin ) {
            if( !conf::get('filter_array') ) {
                return $realMin;
            }
            $filter = conf::get('filter_array');
            if( !isset($filter['min_cost']) ) {
                return $realMin;
            }
            $min = (int) $filter['min_cost'][0];
            if( $min < $realMin ) {
                return $realMin;
            }
            return $min;
        }


        /**
         *  Get a maximum number for price filter
         *  @param  [int] $realMin  real maximum number for current catalog group
         *  @return [int]           maximum number for value in price filter
         */
        public static function max( $realMax ) {
            if( !conf::get('filter_array') ) {
                return $realMax;
            }
            $filter = conf::get('filter_array');
            if( !isset($filter['max_cost']) ) {
                return $realMax;
            }
            $max = $filter['max_cost'][0];
            if( $max > $realMax ) {
                return $realMax;
            }
            return $max;
        }


        // Set to memory the algorithm of filter elements
        public static function setSortElements() {
            $sortable = array('brand', 'model', 'available', 'min_cost', 'max_cost', 'size');
            $result = DB::select('alias')->from('specifications')->order_by('alias')->as_object()->execute();
            foreach($result AS $obj) {
                $sortable[] = $obj->alias;
            }
            conf::set('sortable', $sortable);
        }


        // Set to memory filter as array
        public static function setFilterParameters() {
            if(!Route::param('filter')) { return false; }
            $fil    = Route::param('filter');
            $fil    = explode("&", $fil);
            $filter = Array();
            foreach($fil AS $g) {
                $g  = urldecode($g);
                $g  = strip_tags($g);
                $g  = stripslashes($g);
                $g  = trim($g);
                $s  = explode("=", $g);
                $filter[$s[0]] = explode(",", $s[1]);
            }
            conf::set('filter_array', $filter);
            return true;
        }


        // Get JOIN & WHERE parts of SQL query for items list
        public static function getSql() {
            $sql = array();
            $join = array();
            if( conf::get('filter_array') ) {
                $sortable = conf::get('sortable');
                $filter = conf::get('filter_array');
                if( isset($filter['brand']) ) {
                    $brands = array();
                    foreach($filter['brand'] AS $brand) {
                        $brands[] = $brand;
                    }
                    if(!empty($brands)) {
                        $sql[] = ' brands.alias IN ("'.implode('","',$brands).'") ';
                        $join[] = ' LEFT JOIN brands ON brands.id = catalog.brand_id AND brands.status = "1" ';
                    }
                }
                unset($sortable[array_search('brand', $sortable)]);
                if( isset($filter['model']) ) {
                    $models = array();
                    foreach($filter['model'] AS $model) {
                        $models[] = $model;
                    }
                    if (!empty($models)) {
                        $sql[] = ' models.alias IN ("'.implode('","',$models).'") ';
                        $join[] = ' LEFT JOIN models ON models.id = catalog.model_id AND models.status = "1" ';
                    }
                }
                unset($sortable[array_search('model', $sortable)]);
                if( isset($filter['available']) ) {
                    $available = array();
                    foreach($filter['available'] AS $av) {
                        if( $av == 1 OR $av == 2 OR $av == 0 ) {
                            $available[] = $av;
                        }
                    }
                    if(!empty($available)) {
                        $sql[] = ' catalog.available IN ("'.implode('","',$available).'") ';
                    }
                }
                unset($sortable[array_search('available', $sortable)]);
                if( isset($filter['min_cost']) ) {
                    $sql[] = ' catalog.cost >= "'.$filter['min_cost'][0].'" ';
                }
                unset($sortable[array_search('min_cost', $sortable)]);
                if( isset($filter['max_cost']) ) {
                    $sql[] = ' catalog.cost <= "'.$filter['max_cost'][0].'" ';
                }
                unset($sortable[array_search('max_cost', $sortable)]);
                if( isset($filter['size']) ) {
                    $sizes = array();
                    foreach($filter['size'] AS $size) {
                        $sizes[] = $size;
                    }
                    if(!empty($sizes)) {
                        $sql[] = ' sizes.alias IN ("'.implode('","',$sizes).'") ';
                        $join[] = ' LEFT JOIN catalog_sizes ON catalog_sizes.catalog_id = catalog.id LEFT JOIN sizes ON catalog_sizes.size_id = sizes.id AND sizes.status = "1" ';
                    }
                }
                unset($sortable[array_search('size', $sortable)]);
                foreach ($sortable as $key) {
                    if( isset($filter[$key]) ) {
                        $spec = array();
                        foreach($filter[$key] AS $sp) {
                            $spec[] = $sp;
                        }
                    }
                }
                if(!empty($spec)) {
                    $sql[] = ' specifications_values.alias IN ("'.implode('","',$spec).'") ';
                    $join[] = ' LEFT JOIN catalog_specifications_values ON catalog.id = catalog_specifications_values.catalog_id
                    LEFT JOIN specifications ON specifications.id = catalog_specifications_values.specification_id
                    LEFT JOIN specifications_values ON specifications_values.id = catalog_specifications_values.specification_value_id ';
                }
            }
            return array(
                'where' => count($sql) ? ' AND '.implode('AND', $sql) : '',
                'join' => count($join) ? ' '.implode(' ', $join) : '',
            );
        }


        // Get items list
        public static function getFilteredItemsList( $limit = 15, $offset = 0, $sort = 'catalog.id', $type = 'DESC' ) {
            $items = Filter::getFilteredItems();
            $result = array();
            foreach( $items AS $item ) {
                if ($item->brand_alias) {
                    $result[$item->id]['brand'][] = $item->brand_alias;
                }
                if ($item->model_alias) {
                    $result[$item->id]['model'][] = $item->model_alias;
                }
                if ($item->size_alias) {
                    $result[$item->id]['size'][] = $item->size_alias;
                }
                if ($item->size_alias) {
                    $result[$item->id]['available'][] = $item->available;
                }
                if ($item->cost) {
                    $result[$item->id]['cost'][] = $item->cost;
                }
                if($item->specification_value_alias AND $item->specification_alias) {
                    $result[$item->id][$item->specification_alias][] = $item->specification_value_alias;
                }
            }
            foreach ($result as $key => $value) {
                foreach ($value as $_key => $val) {
                    $result[$key][$_key] = array_unique($result[$key][$_key]);
                }
            }
            $list = array();
            $filter = conf::get('filter_array');
            foreach ($result as $item_id => $item) {
                $filtered = 0;
                if( $filter ) {
                    foreach ($filter as $key => $value) {
                        if( !isset($item[$key]) AND !in_array($key, array('min_cost', 'max_cost')) ) {
                            $filtered = 1;
                        } else {
                            switch ($key) {
                                case 'min_cost':
                                    if( $item['cost'][0] < $value[0] ) {
                                        $filtered = 1;
                                    }
                                    break;
                                case 'max_cost':
                                    if( $item['cost'][0] > $value[0] ) {
                                        $filtered = 1;
                                    }
                                    break;
                                default:
                                    $flag = 0;
                                    foreach ($item[$key] AS $element) {
                                        if( in_array($element, $value) ) {
                                            $flag = 1;
                                        }
                                    }
                                    if( !$flag ) {
                                        $filtered = 1;
                                    }
                                    break;
                            }
                        }
                    }
                }
                if( !$filtered ) {
                    $list[] = $item_id;
                }
            }
            if (!count($list)) {
                return array();
            }
            return DB::select('catalog.*', array('catalog_images.image', 'image'))
                        ->from('catalog')
                        ->join('catalog_images', 'LEFT')->on('catalog.id', '=', 'catalog_images.catalog_id')->on('catalog_images.main', '=', DB::expr('1'))
                        ->where('catalog.parent_id', '=', Route::param('group'))
                        ->where('catalog.status', '=', 1)
                        ->where('catalog.id', 'IN', $list)
                        ->order_by($sort, $type)
                        ->limit($limit)
                        ->offset($offset)
                        ->as_object()->execute();
        }


        // Get clickable filters and min/max costs, included current
        public static function getClickableFilterElements() {
            $items = Filter::getFilteredItems();
            $result = array();
            foreach( $items AS $item ) {
                if ($item->brand_alias) {
                    $result[$item->id]['brand'][] = $item->brand_alias;
                }
                if ($item->model_alias) {
                    $result[$item->id]['model'][] = $item->model_alias;
                }
                if ($item->size_alias) {
                    $result[$item->id]['size'][] = $item->size_alias;
                }
                if ($item->size_alias) {
                    $result[$item->id]['available'][] = $item->available;
                }
                if ($item->cost) {
                    $result[$item->id]['cost'][] = $item->cost;
                }
                if($item->specification_value_alias AND $item->specification_alias) {
                    $result[$item->id][$item->specification_alias][] = $item->specification_value_alias;
                }
            }
            foreach ($result as $key => $value) {
                foreach ($value as $_key => $val) {
                    $result[$key][$_key] = array_unique($result[$key][$_key]);
                }
            }
            $filter = conf::get('filter_array');
            $params = array();
            $costs = array();
            $sortable = conf::get('sortable');
            foreach ($result as $item_id => $item) {
                $costs[] = $item['cost'][0];
                foreach ($sortable as $sort) {
                    $filtered = 0;
                    $f = $filter;
                    if( isset($f[$sort]) ) {
                        unset($f[$sort]);
                    }
                    if( $f ) {
                        foreach ($f as $key => $value) {
                            if( !isset($item[$key]) AND !in_array($key, array('min_cost', 'max_cost')) ) {
                                $filtered = 1;
                            } else {
                                switch ($key) {
                                    case 'min_cost':
                                        if( $item['cost'][0] < $value[0] ) {
                                            $filtered = 1;
                                        }
                                        break;
                                    case 'max_cost':
                                        if( $item['cost'][0] > $value[0] ) {
                                            $filtered = 1;
                                        }
                                        break;
                                    default:
                                        $flag = 0;
                                        foreach ($item[$key] AS $element) {
                                            if( in_array($element, $value) ) {
                                                $flag = 1;
                                            }
                                        }
                                        if( !$flag ) {
                                            $filtered = 1;
                                        }
                                        break;
                                }
                            }
                        }
                    }
                    if( !$filtered AND isset($item[$sort]) ) {
                        foreach ($item[$sort] AS $value) {
                            $params[$sort][] = $value;
                        }
                    }
                }
            }
            foreach ($params as $key => $value) {
                $params[$key] = array_unique($value);
            }
            //newdie($params);
            $min = 999999;
            $max = 0;
            foreach ($costs as $cost) {
                if ($cost < $min) {
                    $min = $cost;
                }
                if ($cost > $max) {
                    $max = $cost;
                }
            }
            return array(
                'filter' => $params,
                'min' => $min,
                'max' => $max,
            );
        }


        // Get brands list for filter
        public static function getBrandsWidget() {
            return DB::select('brands.name', 'brands.alias', 'brands.id')
                    ->from('brands')
                    ->join('catalog', 'LEFT')->on('catalog.brand_id', '=', 'brands.id')
                    ->where('catalog.parent_id', '=', Route::param('group'))
                    ->where('catalog.status', '=', 1)
                    ->where('brands.status', '=', 1)
                    ->group_by('brands.id')
                    ->order_by('brands.name')
                    ->as_object()->execute();
        }


        // Get models list for filter
        public static function getModelsWidget() {
            $filter = conf::get('filter_array');
            if( Arr::get($filter, 'brand') ) {
                $brands = array();
                foreach( Arr::get($filter, 'brand') AS $brand ) {
                    $brands[] = ' brands.alias = "'.$brand.'" ';
                }
                if( count($brands) ) {
                    $brands = ' AND '.implode(' AND ', $brands);
                }
                unset($add['brand']);
            } else {
                $brands = '';
            }
            return DB::select('models.name', 'models.alias', 'models.id')
                    ->from('models')
                    ->join('catalog', 'LEFT')->on('catalog.model_id', '=', 'models.id')
                    ->join('brands', 'LEFT')->on('brands.id', '=', 'catalog.brand_id')->on('brands.status', '=', DB::expr('1'))
                    ->where('catalog.parent_id', '=', Route::param('group'))
                    ->where('catalog.status', '=', 1)
                    ->where('models.status', '=', 1)
                    ->where('brands.alias', 'IN', Arr::get($filter, 'brand', array()))
                    ->group_by('models.id')
                    ->order_by('models.name')
                    ->as_object()->execute();
        }


        // Get sizes list for filter
        public static function getSizesWidget() {
            return DB::select('sizes.name', 'sizes.alias', 'sizes.id')
                    ->from('sizes')
                    ->join('catalog_sizes', 'LEFT')->on('catalog_sizes.size_id', '=', 'sizes.id')
                    ->join('catalog', 'LEFT')->on('catalog.id', '=', 'catalog_sizes.catalog_id')
                    ->where('catalog.parent_id', '=', Route::param('group'))
                    ->where('catalog.status', '=', 1)
                    ->where('sizes.status', '=', 1)
                    ->group_by('sizes.id')
                    ->order_by('sizes.name')
                    ->as_object()->execute();
        }


        // Get specifications values list for filter
        public static function getSpecificationsWidget() {
            $result = DB::select(
                                'specifications_values.id',
                                'specifications_values.name', 
                                'specifications_values.color',
                                'specifications_values.alias',
                                array('specifications.name', 'specification_name'),
                                array('specifications.alias', 'specification_alias'),
                                array('specifications.id', 'specification_id'),
                                array('specifications.type_id', 'specification_type_id')
                        )
                        ->from('specifications_values')
                        ->join('catalog_specifications_values', 'LEFT')->on('catalog_specifications_values.specification_value_id', '=', 'specifications_values.id')
                        ->join('specifications', 'LEFT')->on('specifications.id', '=', 'catalog_specifications_values.specification_id')
                        ->join('catalog', 'LEFT')->on('catalog_specifications_values.catalog_id', '=', 'catalog.id')
                        ->where('catalog.parent_id', '=', Route::param('group'))
                        ->where('catalog.status', '=', 1)
                        ->group_by('specifications.alias')
                        ->group_by('specifications_values.alias')
                        ->order_by('specifications.name')
                        ->as_object()->execute();
            $specifications = array();
            $values = array();
            foreach( $result as $obj ) {
                $values[$obj->specification_alias][] = $obj;
                $specifications[$obj->specification_alias] = $obj->specification_name;
            }
            return array(
                'list' => $specifications,
                'values' => $values,
            );
        }


        // Generate input for filter
        public static function generateInput($filter, $obj, $alias, $type = 'simple') {
            $check = (isset($filter[$alias]) AND in_array($obj->alias, $filter[$alias]));
            $checked = Filter::checked($obj->alias, $alias);
            $disabled = (!$check AND !$checked) ? 'disabled' : '';
            $input = '';
            switch($type) {
                case 'color':
                    if( !$disabled ) {
                        $input .= '<a href="'.Filter::generateLinkWithFilter($alias, $obj->alias).'">';
                    }
                    $input .= '<input  id="'.$alias.$obj->alias.'" value="'.$obj->alias.'" type="checkbox" '.$checked.$disabled.' />';
                    $input .= '<ins><div style="background-color:'.$obj->color.'"></div></ins>';
                    if( !$disabled ) {
                        $input .= '</a>';
                    }
                    break;
                default:
                    $input .= '<label class="checkBlock" for="'.$alias.$obj->id.'">';
                    if( !$disabled ) {
                        $input .= '<a href="'.Filter::generateLinkWithFilter($alias, $obj->alias).'">';
                    }
                    $input .= '<input  id="'.$alias.$obj->alias.'" value="'.$obj->alias.'" type="checkbox" '.$checked.$disabled.' />';
                    $input .= '<ins></ins>';
                    $input .= '<p>'.$obj->name.'</p>';
                    if( !$disabled ) {
                        $input .= '</a>';
                    }
                    $input .= '</label>';
                    break;
            }
            return $input;
        }


        // Generate unique input for filter
        public static function generateElseInput($filter, $name, $value, $alias) {
            $check = (isset($filter[$alias]) AND in_array($value, $filter[$alias]));
            $checked = Filter::checked($value, $alias);
            $disabled = (!$check AND !$checked) ? 'disabled' : '';
            $input = '';

            $input .= '<label class="checkBlock" for="'.$alias.$value.'">';
            if( !$disabled ) {
                $input .= '<a href="'.Filter::generateLinkWithFilter($alias, $value).'">';
            }
            $input .= '<input  id="'.$alias.$value.'" value="'.$value.'" type="checkbox" '.$checked.$disabled.' />';
            $input .= '<ins></ins>';
            $input .= '<p>'.$name.'</p>';
            if( !$disabled ) {
                $input .= '</a>';
            }
            $input .= '</label>';

            return $input;
        }

    }
