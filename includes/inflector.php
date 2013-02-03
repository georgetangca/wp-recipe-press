<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * inflector.php - helper file for changing inflection of text.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */

class rp_inflector {

// Cached inflections
    static $cache = array();

    // Uncountable and irregular words
    static $uncountable;
    static $irregular;

    /**
     * Checks if a word is defined as uncountable.
     *
     * @param   string   word to check
     * @return  boolean
     */
    static function uncountable($str) {
        if (self::$uncountable === NULL) {
        // Cache uncountables
            self::$uncountable = self::cacheUncountable();

            // Make uncountables mirroed
            self::$uncountable = array_combine(self::$uncountable, self::$uncountable);
        }

        return isset(self::$uncountable[strtolower($str)]);
    }

    static function cacheUncountable() {
        return array (
        'access',
        'advice',
        'art',
        'baggage',
        'dances',
        'equipment',
        'fish',
        'fuel',
        'furniture',
        'food',
        'heat',
        'honey',
        'homework',
        'impatience',
        'information',
        'knowledge',
        'luggage',
        'money',
        'music',
        'news',
        'patience',
        'progress',
        'pollution',
        'research',
        'rice',
        'sand',
        'series',
        'sheep',
        'sms',
        'species',
        'toothpaste',
        'traffic',
        'understanding',
        'water',
        'weather',
        'work',
        'large',
        'medium',
        'small',
        );
    }

    static function cacheIrregular() {
        return array (
        'child' => 'children',
        'clothes' => 'clothing',
        'man' => 'men',
        'movie' => 'movies',
        'person' => 'people',
        'woman' => 'women',
        'mouse' => 'mice',
        'goose' => 'geese',
        'ox' => 'oxen',
        'leaf' => 'leaves',
        'whole' => 'whole',
        );
    }

    /**
     * Makes a plural word singular.
     *
     * @param   string   word to singularize
     * @param   integer  number of things
     * @return  string
     */
    static function singular($str, $count = NULL) {
    // Remove garbage
        $str = strtolower(trim($str));

        if (is_string($count)) {
        // Convert to integer when using a digit string
            $count = (int) $count;
        }

        // Do nothing with a single count
        if ($count === 0 OR $count > 1) {
             return $str;
        }

        // Cache key name
        $key = 'singular_'.$str.$count;

        if (isset(self::$cache[$key])) {
             return self::$cache[$key];
        }

        if (self::uncountable($str)) {
             return self::$cache[$key] = $str;
        }

        if (empty(self::$irregular)) {
        // Cache irregular words
            self::$irregular = self::cacheIrregular();
        }

        if ($irregular = array_search($str, self::$irregular)) {
            $str = $irregular;
        }
        elseif (preg_match('/[sxz]es$/', $str) OR preg_match('/[^aeioudgkprt]hes$/', $str)) {
        // Remove "es"
            $str = substr($str, 0, -2);
        }
        elseif (preg_match('/[^aeiou]ies$/', $str)) {
            $str = substr($str, 0, -3).'y';
        }
        elseif (substr($str, -1) === 's' AND substr($str, -2) !== 'ss') {
            $str = substr($str, 0, -1);
        }

        return self::$cache[$key] = $str;
    }

    /**
     * Makes a singular word plural.
     *
     * @param   string  word to pluralize
     * @return  string
     */
    static function plural($str, $count = NULL) {
    // Remove garbage
        $str = trim($str);

        if (is_string($count)) {
            /* Convert to integer when using a digit string */
            $count = (int) $count;
        }

       
        /* Do nothing with singular */
        if ($count === 1) {
             return $str;
        }

        /* Cache key name */
        $key = 'plural_'.$str.$count;

        if (isset(self::$cache[$key])) {
             return self::$cache[$key];
        }

        if (rp_inflector::uncountable($str)) {
             return self::$cache[$key] = $str;
        }

        if (empty(self::$irregular)) {
             /* Cache irregular words */
             self::$irregular = self::cacheIrregular();
        }

        if (isset(self::$irregular[$str])) {
            $str = self::$irregular[$str];
        } elseif ( (preg_match('/[sxz]$/', $str) and !preg_match('/[es]/', $str) ) OR preg_match('/[^aeioudgkprt]h$/', $str)) {
            $str .= 'es';
        } elseif (preg_match('/[^aeiou]y$/', $str) and !preg_match('/[ies]/', $str)) {
            /* Change "y" to "ies" */
            $str = substr_replace($str, 'ies', -1);
        } elseif ( !preg_match('/[es]$/', $str) and !preg_match('/[ies]$/', $str) ) {
            $str .= 's';
        }

        // Set the cache and return
        return self::$cache[$key] = $str;
    }

    /**
     * Makes a phrase camel case.
     *
     * @param   string  phrase to camelize
     * @return  string
     */
    static function camelize($str) {
        $str = 'x'.strtolower(trim($str));
        $str = ucwords(preg_replace('/[\s_]+/', ' ', $str));

        return substr(str_replace(' ', '', $str), 1);
    }

    /**
     * Makes a phrase underscored instead of spaced.
     *
     * @param   string  phrase to underscore
     * @return  string
     */
    static function underscore($str) {
        return strtolower(preg_replace('/\s+/', '_', trim($str)));
    }

    /**
     * Makes an underscored or dashed phrase human-reable.
     *
     * @param   string  phrase to make human-reable
     * @return  string
     */
    static function humanize($str) {
        return preg_replace('/[_-]+/', ' ', trim($str));
    }

    static function trim_excerpt($text, $length = NULL, $suffix = '...', $allowed_tags = 'p') {
        global $post;
        $allowed_tags_formatted = '';
        
        $tags = explode(',', $allowed_tags);

        foreach ($tags as $tag) {
            $allowed_tags_formatted.= '<'. $tag . '>';
        }

        if (!$length) {
        //return $text;
        }

        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text, $allowed_tags_formatted);
        $text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
        $words = explode(' ', $text, $length + 1);
        if (count($words) > $length) {
            array_pop($words);
            array_push($words, $suffix);
            $text = implode(' ', $words);
        }

        $tags = explode(',', $allowed_tags);
        foreach ($tags as $tag) {
            $text.= '</' . $tag . '>';
        }

        return $text;
    }

    /**
		Converts a string that represents a fraction to a double, this function can be
		called statically.
		@param $str The string to convert
		@return A floating point number
	*/
	static function strToFloat($str) {
		list($whole, $frac) = split(' ', $str);
		if (preg_match("/\//", $whole, $matches)) {
			$frac = $whole;
			$ret = 0;
		} else $ret = $whole;

		// Now deal with the fraction part
		if ($frac) {
			list($top,$bot) = split('\/', $frac);
			if ($top > 0 && $bot > 0)
				$ret += ($top / $bot);
		}
		return $ret;
	}
} // End inflector