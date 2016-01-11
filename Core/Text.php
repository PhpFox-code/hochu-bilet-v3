<?php
    namespace Core;
    
    class Text {

        /**
         *  Generate array with RU and EN alphabet and with filtered items from $result object
         *  @param object $result - array with objects from query result 
         */
        static function get_alphabet($result) {
            $en = array('1-9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            $ru = array('А','Б','В','Г','Д','Е','Ж','З','И','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Э','Ю','Я');
            $_en = $_ru = array();
            foreach($result AS $obj) {
                $letter = Text::get_first_upper_letter($obj->name);
                if( in_array( (int) $letter, array(1,2,3,4,5,6,7,8,9) ) ) {
                    $_en['1-9'][] = $obj;
                } else if( in_array( $letter, $en ) ) {
                    $_en[$letter][] = $obj;
                } else if( in_array( $letter, $ru ) ) {
                    $_ru[$letter][] = $obj;
                } 
            }
            return array( 'en' => $en, 'ru' => $ru, 'res_en' => $_en, 'res_ru' => $_ru );
        }


        /**
         *  Get first letter and set uppercase to it
         *  @param  string $word - Some string
         *  @return string       - first letter of $word in uppercase
         */
        static function get_first_upper_letter($word) {
            $length = mb_strlen( $word, 'UTF-8' );
            $letter = mb_substr( $word, 0, - $length + 1, 'UTF-8' );
            $letter = strtoupper( $letter );
            return $letter;
        }



        /**
         * Change br to nl. Opposite to nl2br
         *
         *     $text = Text::translit($text);
         *
         * @param   string $text - phrase to change
         * @return  string
         */
        public static function br2nl($text) {
            return preg_replace('#<br(\s+)?\/?>#i', "\n", $text);
        }


        /**
         * Transliterate phrase from russian to en
         *
         *     $text = Text::translit($phrase);
         *
         * @param   string $phrase - phrase to translit
         * @return  string
         */
        public static function translit($phrase) {
            $ru   = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','і','є','ї','ґ',' ','"',"'","`",':','«','»','.',',','’','„','”','(',')','[',']','*','@','#','“','№','%');
            $en   = array('a','b','v','g','d','e','e','zh','z','i','j','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sh','sch','','y','','e','ju','ja','i','je','ji','g','_','','','','','','','','','','','','','','','','','','','','N','');
            $str  = '';
            $phrase = mb_strtolower($phrase, "UTF-8");
            $i = 0;
            foreach($ru as $_ru) {
                $phrase = str_replace($_ru, $en[$i], $phrase);
                $i++;
            }
            return $phrase;
        }
        
        /**
    	 * Limits a phrase to a given number of words.
    	 *
    	 *     $text = Text::limit_words($text);
    	 *
    	 * @param   string  $str        phrase to limit words of
    	 * @param   integer $limit      number of words to limit to
    	 * @param   string  $end_char   end character or entity
    	 * @return  string
    	 */
    	public static function limit_words($str, $limit = 100, $end_char = NULL)
    	{
    		$limit = (int) $limit;
    		$end_char = ($end_char === NULL) ? '…' : $end_char;
    
    		if (trim($str) === '')
    			return $str;
    
    		if ($limit <= 0)
    			return $end_char;
    
    		preg_match('/^\s*+(?:\S++\s*+){1,'.$limit.'}/u', $str, $matches);

    		// Only attach the end character if the matched string is shorter
    		// than the starting string.
    		return rtrim($matches[0]).((strlen($matches[0]) === strlen($str)) ? '' : $end_char);
    	}
    
    	/**
    	 * Limits a phrase to a given number of characters.
    	 *
    	 *     $text = Text::limit_chars($text);
    	 *
    	 * @param   string  $str            phrase to limit characters of
    	 * @param   integer $limit          number of characters to limit to
    	 * @param   string  $end_char       end character or entity
    	 * @param   boolean $preserve_words enable or disable the preservation of words while limiting
    	 * @return  string
    	 * @uses    UTF8::strlen
    	 */
    	public static function limit_chars($str, $limit = 100, $end_char = NULL, $preserve_words = FALSE)
    	{
    		$end_char = ($end_char === NULL) ? '…' : $end_char;
    
    		$limit = (int) $limit;
    
    		if (trim($str) === '' OR UTF8::strlen($str) <= $limit)
    			return $str;
    
    		if ($limit <= 0)
    			return $end_char;
    
    		if ($preserve_words === FALSE)
    			return rtrim(UTF8::substr($str, 0, $limit)).$end_char;
    
    		// Don't preserve words. The limit is considered the top limit.
    		// No strings with a length longer than $limit should be returned.
    		if ( ! preg_match('/^.{0,'.$limit.'}\s/us', $str, $matches))
    			return $end_char;
    
    		return rtrim($matches[0]).((strlen($matches[0]) === strlen($str)) ? '' : $end_char);
    	}
        
        /**
    	 * Finds the text that is similar between a set of words.
    	 *
    	 *     $match = Text::similar(array('fred', 'fran', 'free'); // "fr"
    	 *
    	 * @param   array   $words  words to find similar text of
    	 * @return  string
    	 */
    	public static function similar(array $words)
    	{
    		// First word is the word to match against
    		$word = current($words);
    
    		for ($i = 0, $max = strlen($word); $i < $max; ++$i)
    		{
    			foreach ($words as $w)
    			{
    				// Once a difference is found, break out of the loops
    				if ( ! isset($w[$i]) OR $w[$i] !== $word[$i])
    					break 2;
    			}
    		}
    
    		// Return the similar text
    		return substr($word, 0, $i);
    	}
        
        /**
    	 * Generates a random string of a given type and length.
    	 *
    	 *
    	 *     $str = Text::random(); // 8 character random string
    	 *
    	 * The following types are supported:
    	 *
    	 * alnum
    	 * :  Upper and lower case a-z, 0-9 (default)
    	 *
    	 * alpha
    	 * :  Upper and lower case a-z
    	 *
    	 * hexdec
    	 * :  Hexadecimal characters a-f, 0-9
    	 *
    	 * distinct
    	 * :  Uppercase characters and numbers that cannot be confused
    	 *
    	 * You can also create a custom type by providing the "pool" of characters
    	 * as the type.
    	 *
    	 * @param   string  $type   a type of pool, or a string of characters to use as the pool
    	 * @param   integer $length length of string to return
    	 * @return  string
    	 * @uses    UTF8::split
    	 */
        public static function random($type = NULL, $length = 8)
    	{
    		if ($type === NULL)
    		{
    			// Default is to generate an alphanumeric string
    			$type = 'alnum';
    		}
    
    		$utf8 = FALSE;
    
    		switch ($type)
    		{
    			case 'alnum':
    				$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    			break;
    			case 'alpha':
    				$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    			break;
    			case 'hexdec':
    				$pool = '0123456789abcdef';
    			break;
    			case 'numeric':
    				$pool = '0123456789';
    			break;
    			case 'nozero':
    				$pool = '123456789';
    			break;
    			case 'distinct':
    				$pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
    			break;
    			default:
    				$pool = (string) $type;
    				$utf8 = ! UTF8::is_ascii($pool);
    			break;
    		}
    
    		// Split the pool into an array of characters
    		$pool = ($utf8 === TRUE) ? UTF8::str_split($pool, 1) : str_split($pool, 1);
    
    		// Largest pool key
    		$max = count($pool) - 1;
    
    		$str = '';
    		for ($i = 0; $i < $length; $i++)
    		{
    			// Select a random character from the pool and add it to the string
    			$str .= $pool[mt_rand(0, $max)];
    		}
    
    		// Make sure alnum strings contain at least one letter and one digit
    		if ($type === 'alnum' AND $length > 1)
    		{
    			if (ctype_alpha($str))
    			{
    				// Add a random digit
    				$str[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
    			}
    			elseif (ctype_digit($str))
    			{
    				// Add a random letter
    				$str[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
    			}
    		}
    
    		return $str;
    	}

        /**
        * Filter var for XSS attacks
        *
        * @param string|number $string 
        *
        * @return string|number $result - cleaner result
        */
        public static function xssClean($string) {
            return htmlentities(strip_tags($string), ENT_QUOTES, 'UTF-8');
        }

    }