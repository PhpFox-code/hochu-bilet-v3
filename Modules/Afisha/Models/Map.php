<?php 
	namespace Modules\Afisha\Models;


	class Map
	{
		// Instance current object
		private static $instance;

		// Path to folders with maps
		private $mapPath = '/Views/Map/Maps/';

		// DOMObject instance
		private $dom; 

		// Factory method
		public static function factory()
		{
			if (self::$instance === null) {
				self::$instance = new self();
			}
			return self::$instance;
		}


		/**
		* Load file
		*
		* @param string $filename
		*
		* @return object $this
		*/
		public function loadFile($filename) 
		{
			$fileFullPath = HOST.$this->mapPath.$filename.'.php';
			if (!is_file($fileFullPath)) {
				throw new \Exception("Ошибка загрузки файла: ".$filename, 1);
			}
			$this->dom = new \DOMDocument('1.0', 'UTF-8');
			$this->dom->load($fileFullPath);
			$this->redefineId();
			return $this;
		}


		/**
		* Parse DOM and replace element attributes
		*
		* @param Array $seats
		* @param Boolean $returnDom Default true. If true - returned dom tpl
		* @param Boolean $forAdmin Default false
		*
		* @return void || dom tpl
		*/
		public function parseDom(Array $seats, $returnDom = true, $forAdmin = false)
		{
			$gTag = $this->dom->getElementsByTagName('g');
			if ($gTag === null) {
				throw new \Exception("Ошибка поиска тега 'g'", 2);
			}
			foreach ($gTag as $el) {

				$id = $el->getAttribute('id');
				$dataInit = $el->getAttribute('data-init');

				if (strpos($id, 'seat') === false 
					AND strlen($dataInit) == 0) continue;

                // check seats
                if (array_key_exists($id, $seats))
                {
                	$currSeat = $seats[$id];
                	// set price
                	$el->setAttribute('class', 'seat');
                    $dInit = $el->getAttribute('data-init');
                    $arrInit = json_decode($dInit);
                    $arrInit->price = $currSeat['price'];
                    $arrInit->color = $currSeat['color'];

                    $el->setAttribute('data-init', json_encode($arrInit));

                    // Set color
                    // $el->childNodes->item(1)->setAttribute('style', 'fill: '.$currSeat['color']);
                    $el->getElementsByTagName('path')->item(0)->setAttribute('style', 'fill: '.$currSeat['color']);
                    if ($el->getElementsByTagName('path')->item(1)) {
                    	$el->getElementsByTagName('path')->item(1)->setAttribute('style', 'fill: '.$currSeat['color']);
                    }

                    // Set class for reserved
                    if ($currSeat['status'] == 2
                    	AND $currSeat['reserved_at'] > time() - 60 * 60 * 24 * \Core\Config::get('reserved_days')) 
                    {
                    	$old = $el->getAttribute('class');
                    	$el->setAttribute('class', $old.' reserved');
                    }

                    // Set class for payment
                    if ($currSeat['status'] == 3) 
                    {
                    	$old = $el->getAttribute('class');
                    	if ($forAdmin == true) {
                    		$el->setAttribute('class', $old.' payment');
                    	}
                    	else {
                    		$el->setAttribute('class', $old.' busy');
                    	}
                    }

                    // Set default class grey for admin
                    if ($forAdmin == true) {
                    	$oldClass = $el->getAttribute('class');
	                	$el->setAttribute('class', $oldClass.' grey');
                    }
                }
                // busy
                else {
                	$oldClass = $el->getAttribute('class');
                	if ($forAdmin == true) {
                		$el->setAttribute('class', $oldClass.' grey');
                	}
                	else{
	                	$el->setAttribute('class', $oldClass.' busy');
	                }
                }
			}

			if ($returnDom === true) {
				return $this->getDom();
			}
		}

		/**
	     * Redefine all elements ID in DOM 
	     * 
	     * @return Object    $this
	     */
	    private function redefineId()
	    {
	        foreach ($this->dom->getElementsByTagName('*') as $element) {
	            if ($element->hasAttribute('id')) {
	                $element->setIdAttribute('id', true);
	            }
	        }
	        return $this;
	    }

		/**
		* Parse DOM and replace element attributes
		*
		* @param Array $seats
		* @param Boolean $returnDom Default true. If true - returned dom tpl
		* @param Boolean $forAdmin Default false
		*
		* @return void || dom tpl
		*/
		public function parseDomOrder(Array $orderSeats, Array $seats, $returnDom = true, $forAdmin = false)
		{

			$gTag = $this->dom->getElementsByTagName('g');
			if ($gTag === null) {
				throw new \Exception("Ошибка поиска тега 'g'", 2);
			}
			foreach ($gTag as $el) {
				$id = $el->getAttribute('id');
				$dataInit = $el->getAttribute('data-init');

				if (strpos($id, 'seat') === false
					AND strlen($dataInit) == 0) continue;

                // check seats
                if (array_key_exists($id, $seats))
                {
                	$currSeat = $seats[$id];

                    // $el->childNodes->item(1)->setAttribute('style', 'fill: '.$currSeat['color']);
                    $el->getElementsByTagName('path')->item(0)->setAttribute('style', 'fill: '.$currSeat['color']);
                    if ($el->getElementsByTagName('path')->item(1)) {
                    	$el->getElementsByTagName('path')->item(1)->setAttribute('style', 'fill: '.$currSeat['color']);
                    }
                    
                	if (array_key_exists($id, $orderSeats)) {
                		$old = $el->getAttribute('class');
            			$el->setAttribute('class', $old.' payment');
                	}
                	else {
                		// set price
	                	$el->setAttribute('class', 'seat');
	                    $dInit = $el->getAttribute('data-init');
	                    $arrInit = json_decode($dInit);
	                    $arrInit->price = $currSeat['price'];
	                    $arrInit->color = $currSeat['color'];
	                    $arrInit->id    = $currSeat['id'];

	                    $el->setAttribute('data-init', json_encode($arrInit));

	                    // Set color

	                    // Set class for reserved
	                    if ($currSeat['status'] == 2
	                    	AND $currSeat['reserved_at'] > time() - 60 * 60 * 24 * \Core\Config::get('reserved_days')) 
	                    {
	                    	$old = $el->getAttribute('class');
	                    	$el->setAttribute('class', $old.' reserved');
	                    }

	                    // Set class for payment
	                    if ($currSeat['status'] == 3) 
	                    {
	                    	$old = $el->getAttribute('class');
                    		$el->setAttribute('class', $old.' reserved');
	                    }

	                    // Set default class grey for admin
	                    if ($forAdmin == true) {
	                  //   	$oldClass = $el->getAttribute('class');
		                	// $el->setAttribute('class', $oldClass.' grey');
	                    }
                	}
                }
                // busy
                else {
                	$oldClass = $el->getAttribute('class');
                	if ($forAdmin == true) {
                		$el->setAttribute('class', $oldClass.' grey');
                	}
                	else{
	                	$el->setAttribute('class', $oldClass.' busy');
	                }
                }
			}

			if ($returnDom === true) {
				return $this->getDom();
			}
		}

		/**
		* Add list block with color prices at head 
		*
		* @param array $prices
		* @return object $this
		*/
		public function addPrices(array $prices)
		{
			if (count($prices) == 0) {
				return $this;
			}
			$pricesStr = json_encode($prices);
			// Pars dom
			$defs = $this->dom->getElementsByTagName('defs');
			$defs->item(0)->setAttribute('data-price', $pricesStr);
			return $this;
		}

		/**
		* Return Dom tree
		*
		* @return string dom tpl
		*/
		public function getDom()
		{
			return $this->dom->saveHTML();
		}

		public function getDomInstance()
		{
			if ($this->dom === null) {
				throw new \Excetion('Dom object not created');
			}
			return $this->dom;
		}
	}