<?php

if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * recipe-press-core.php - RecipePress Core Class
 *
 * @package RecipePress
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 2.0.4
 */
class recipePressCore {

     var $menuName = 'recipe-plus';
     var $pluginName = 'Recipe Plus';
     var $version = '1.0';
     var $optionsName = 'recipe-plus-options';
     var $options = array();
     var $showCaptcha = false;
     var $recipeCreated = false;
     var $xmlURL = '';
     var $inUpdateOption = false;
     var $in_shortcode = false;
     var $publicForm = false;
     var $dir_sep = '/';

     /**
      * Initialize the plugin.
      */
     function recipePressCore() {
          /* Load Langauge Files */
          load_plugin_textdomain('recipe-plus', false, dirname(dirname(plugin_basename(__FILE__))) . '/lang');

          /* Plugin Settings */
          /* translators: The name of the plugin, should be a translation of "RecipePress" only! */
          $this->pluginName = __('Recipe Plus', 'recipe-press');

          /* Plugin Folders */
          $this->pluginPath = WP_PLUGIN_DIR . '/' . basename(dirname(dirname(__FILE__))) . '/';
          $this->pluginURL = WP_PLUGIN_URL . '/' . basename(dirname(dirname(__FILE__))) . '/';
          $this->templatesPath = WP_PLUGIN_DIR . '/' . basename(dirname(dirname(__FILE__))) . '/templates/';
          $this->templatesURL = WP_PLUGIN_URL . '/' . basename(dirname(dirname(__FILE__))) . '/templates/';
          $this->loadSettings();

          /* Add custom images sizes for RecipePress */
          foreach ( $this->options['image-sizes'] as $image => $size ) {
               add_image_size('recipe-press-' . $image, $size['width'], $size['height'], $size['crop']);
          }
     }

     /**
      * Load plugin settings.
      */
     function loadSettings() {

          $options = get_option($this->optionsName);

          $defaults = array(
               /* Recipe Options */
               'use-plugin-permalinks' => false,
               'index-slug' => 'recipes',
               'identifier' => 'recipe',
               'permalink' => (get_option('permalink_structure')) ? '%identifier%' . get_option('permalink_structure') : '%identifier%/%postname%',
               'plural-name' => __('Recipes', 'recipe-press'),
               'singular-name' => __('Recipe', 'recipe-press'),
               'use-taxonomies' => false,
               'use-servings' => false,
               'use-times' => false,
               'use-thumbnails' => true,//george change as from false to true
               'use-featured' => true, //george change as from false to true
               'use-comments' => false,
               'use-trackbacks' => false,
               'use-custom-fields' => false,
               'use-revisions' => false,
               'use-nutritional-value' => false,
               'use-post-categories' => true, //george change from false to true 
               'use-post-tags' => true,   //george change from false to true 
               'use-categories' => false, /* Depreciated */
               'use-cuisines' => false, /* Depreciated */
               'plural-times' => false,
               /* Taxonomy Defaults */
               'taxonomies' => array(
                    'recipe-category' => array('slug' => 'category', 'plural' => __('Categories', 'recipe-press'), 'singular' => __('Recipe Category', 'recipe-press'), 'hierarchical' => true, 'active' => true /* george change from true to false */, 'default' => false, 'allow_multiple' => true, 'page' => false, 'builtin' => true, 'per-page' => 10),
                   // 'recipe-cuisine' => array('slug' => 'recipe-cuisine', 'plural' => __('Cuisines', 'recipe-press'), 'singular' => __('Cuisine', 'recipe-press'), 'hierarchical' => false, 'active' =>  false /* george change from true to false */, 'default' => false, 'allow_multiple' => true, 'page' => false, 'builtin' => true, 'per-page' => 10)
               ),
              
               'ingredient-slug' => 'recipe-ingredients',
               'ingredients-per-page' => 10,
               'ingredient-page' => 0,
               /* Image Sizes */
               'image-sizes' => array(
                    'image' => array('name' => 'RecipePress Image', 'width' => 250, 'height' => 250, 'crop' => isset($options['image-sizes']['image']['crop']) ? $options['image-sizes']['image']['crop'] : true, 'builtin' => true),
                    'thumb' => array('name' => 'RecipePress Thumbnail', 'width' => 50, 'height' => 50, 'crop' => isset($options['image-sizes']['thumb']['crop']) ? $options['image-sizes']['thumb']['crop'] : true, 'builtin' => true),
               ),
               /* Display Settings */
               'menu-position' => 5,
               'default-excerpt-length' => 20,
               'recipe-count' => get_option('posts_per_page'),
               'recipe-orderby' => 'title',
               'recipe-order' => 'asc',
               'add-to-author-list' => false,
               'disable-content-filter' => false,
               'custom-css' => (count($options) > 2) ? isset($options['custom-css']) : true,
               'hour-text' => __(' hour', 'recipe-press'),
               'minute-text' => __(' min', 'recipe-press'),
               'time-display-type' => 'double',
              
              /* Form Defaults */
               'form-page' => NULL,
               'form-redirect' => NULL,
               'use-form' => false,
               'form-identifier' => 'submit-recipe',
               'form-permalink' => '%identifier%',
               'form-extension' => false,
               'on-submit-redirect' => false,
               
               'new-recipe-status' => 'pending',
               'ingredients-fields' => 2,
               'instructions-fields' =>2, //George add here  
               'required-fields' => array('title'),//array('title', 'instructions'), need to add more 
               'submit-title' => 'Share a Recipe',
               'require-login' => false,
               'share-slug' => 'recipe-share', /* Depricated */

              
               /* Widget Defaults */
               'widget-orderby' => 'name',
               'widget-order' => 'asc',
               'widget-style' => 'list',
               'widget-show-count' => false,
               'widget-hide-empty' => (count($options) > 2) ? isset($options['widget-hide-empty']) : true,
               'widget-items' => 10,
               'widget-depth' => 0,
               'widget-pad-counts' => false,
               'widget-taxonomy' => 'recipe-category',
               'widget-type' => 'Newest',
               'widget-target' => NULL,
               'widget-show-icon' => false,
               'widget-icon-size' => 25,
               
               /* Printing Options */
               'use-recipe-print' => false,
               'default-print-template' => 'card-3x5',
               /* Sharing Options */
               'use-recipe-share' => false,
               'default-share-template' => 'share',
               
              /* Recipe Box Options */
               'use-recipe-box' => true,
               'recipe-box-slug' => 'recipe-box',
               'recipe-box-page' => true,//george change from false to true
               'recipe-box-title' => __('My Recipe Box', 'recipe-press'),
               'recipe-box-add-title' => __('Save to My Recipe Box', 'recipe-press'),
               'recipe-box-view-title' => __('View My Recipe Box', 'recipe-press'),
               
              /* Non-Configurable Settings */
               'menu-icon' => $this->pluginURL . 'images/icons/small_logo.png',
               /* Size Settings  - DEPRICATED FOR TAXONOMY USE */
               'standard' => array(
                    'ingredient-sizes' => array('bag', 'big', 'bottle', 'box', 'bunch', 'can', 'carton', 'container', 'count', 'cup', 'clove', 'dash', 'dozen', 'drop', 'envelope', 'fluid ounce', 'gallon', 'gram', 'head', 'jar', 'large', 'pound', 'leaf', 'link', 'liter', 'loaf', 'medium', 'ounce', 'package', 'packet', 'piece', 'pinch', 'pint', 'quart', 'scoop', 'sheet', 'slice', 'small', 'sprig', 'stalk', 'stick', 'strip', 'tablespoon', 'teaspoon', 'whole'),
                    'serving-sizes' => array('cup', 'quart', 'pint', 'gallon', 'dozen', 'serving', 'piece')
               ),
               'metric' => array(
                    'ingredient-sizes' => array('drop', 'dash', 'pinch', 'teaspoon', 'desert spoon', 'tablespoon', 'fluid ounce', 'pint', 'quart', 'gallon', 'pound', 'gram', 'stone', 'ton', 'milligram', 'kilogram'),
                    'serving-sizes' => array('quart', 'pint', 'gallon', 'serving', 'piece')
               ),
               /* Nutritional Markers */
               'nutritional-markers' => array(
                    'txt_glycemic_load' => array('name' => 'Glycemic Load'),
                    'txt_calories' => array('name' => 'Calories'),
                    'txt_total_fat' => array('name' => 'Total Fat', 'size' => 'g'),
                    'txt_saturated_fat' => array('name' => 'Saturated Fat', 'size' => 'g'),
                    'txt_polyunsaturated_fat' => array('name' => 'Polyunsaturated Fat', 'size' => 'g'),
                    'txt_monounsaturated_fat' => array('name' => 'Monounsaturated Fat', 'size' => 'g'),
                    'txt_cholesterol' => array('name' => 'Cholesterol', 'size' => 'mg'),
                    'txt_sodium' => array('name' => 'Sodium', 'size' => 'mg'),
                    'txt_potassium' => array('name' => 'Potassium', 'size' => 'mg'),
                    'txt_total_carbohydrate' => array('name' => 'Total Carbohydrates', 'size' => 'g'),
                    'txt_dietary_fiber' => array('name' => 'Dietary Fiber', 'size' => 'g'),
                    'txt_sugars' => array('name' => 'Sugars', 'size' => 'g'),
                    'txt_protein' => array('name' => 'Protein', 'size' => 'g'),
               ),
          );

          $this->options = wp_parse_args($options, $defaults);

          /* Handle renaming of built-in taxonomies */
          if ( isset($this->options['taxonomies']['recipe-categories']) ) {
               $this->options['taxonomies']['recipe-category'] = $this->options['taxonomies']['recipe-categories'];
               unset($this->options['taxonomies']['recipe-categories']);
          }

          if ( isset($this->options['taxonomies']['recipe-cuisines']) ) {
               $this->options['taxonomies']['recipe-cuisine'] = $this->options['taxonomies']['recipe-cuisines'];
               unset($this->options['taxonomies']['recipe-cuisines']);
          }

          if ( $this->options['use-thumbnails'] ) {
               add_theme_support('post-thumbnails');
          }

          $this->formFieldNames = array(
               'title' => __('Recipe Name', 'recipe-press'),
               'image' => __('Recipe Image', 'recipe-press'),
               'notes' => __('Recipe Notes', 'recipe-press'),
               'recipe-category' => $this->options['taxonomies']['recipe-category']['singular'],
               'recipe-cuisine' => $this->options['taxonomies']['recipe-cuisine']['singular'],
               'servings' => __('Servings', 'recipe-press'),
               'prep_time' => __('Prep Time', 'recipe-press'),
               'cook_time' => __('Cook Time', 'recipe-press'),
               'measure_type' => __('Measurement', 'recipe-press'),
               'ingredients' => __('Ingredients', 'recipe-press'),
               'directions' => __('Directions', 'recipe-press'),
               'nutrient_per_serving' => __('Nutrient Per Serving', 'recipe-press'), //george add here
               'photo_credit'=> __('Photo Credit', 'recipe-press'), //george add here
               'dek'=> __('Description', 'recipe-press'), //george add here 
               'author'=> __('Author', 'recipe-press'), //george add here
               
               'recaptcha' => __('Verify', 'recipe-press'),
               'submitter' => __('Name', 'recipe-press'),
               'submitter_email' => __('Email', 'recipe-press'),
          );

          /* Eliminate individual taxonomies */
          if ( $this->options['use-categories'] ) {
               $this->options['use-taxonomies'] = true;
               $this->options['taxonomies']['recipe-category'] = array(
                    'plural' => __('Categories', 'recipe-press'),
                    'singular' => __('Category', 'recipe-press'),
                    'hierarchical' => true,
                    'active' => true,
                    'page' => $this->options['categories-page'],
                    'converted' => true
               );
          }

          if ( $this->options['use-cuisines'] ) {
               $this->options['use-taxonomies'] = true;
               $this->options['taxonomies']['recipe-cuisine'] = array(
                    'plural' => __('Cuisines', 'recipe-press'),
                    'singular' => __('Cuisine', 'recipe-press'),
                    'hierarchical' => false,
                    'active' => true,
                    'page' => $this->options['cuisines-page'],
                    'converted' => true
               );
          }

          if ( is_array($this->options['taxonomies']) ) {
               foreach ( $this->options['taxonomies'] as $key => $taxonomy ) {
                    if ( isset($taxonomy['page']) ) {
                         $this->pageIDs[$key] = $taxonomy['page'];
                         $this->taxonomyPages[$key] = $taxonomy['page'];
                    }
               }
          } else {
               $this->options['taxonomies'] = array();
          }

          if ( isset($this->options['new-recipe-status']) and $this->options['new-recipe-status'] == 'active' ) {
               $this->options['new-recipe-status'] = 'publish';
          }

          return $this->options;
     }

   
     
     /**
      * Collect recipe details from front end form.
      *
      * @global <type> $current_user
      * @param <type> $object
      * @return <type>
      */
     function input($data = NULL) {
          global $current_user;
          get_currentuserinfo();

          if ( !$data ) {
               $data = $_POST;
          }

          if ( count($data) == 0 ) {
               return array('ingredients' => array(), 'instructions' => array());
              
          }
          $ingredients = array();
          
          if ( isset($data['ingredients']) ) {
               $ingredientArray = $data['ingredients'];

               if ( is_array($ingredientArray) ) {
                    $j = 1;
                    foreach ( $ingredientArray as $id => $ingredient ) {
                         if ( $id != 'NULL' and (isset($ingredient['item'])) ) {
                              $ingredients[$j] = $ingredient;
                              $j++;
                         }
                    }
               }
          } else {
               $ingredients = array();
          }
          
          //Gorge add instruction here 
          $instructions = array();
          if ( isset($data['instructions']) ) {
               $instructionArray = $data['instructions'];

               if ( is_array($instructionArray) ) {
                    $j = 1;
                    foreach ( $instructionArray as $id => $instruction ) {
                         if ( $id != 'NULL' and (isset($instruction['item'])) ) {
                              $instructions[$j] = $instruction;
                              $j++;
                         }
                    }
                    
               }
          } else {
               $instructions = array();
          }

          return array(
               'title' => @$data['title'],
               'user_id' => @$data['user_id'],
               'notes' => @$data['notes'],
               'prep_time' => @$data['prep_time'],
               'cook_time' => @$data['cook_time'],
              // 'ready_time' => @$this->readyTime(),
              // 'ready_time_raw' => @$this->readyTime(NULL, NULL, false),
               'recipe-category' => @$data['recipe-category'],
              // 'recipe-cuisine' => @$data['recipe-cuisine'],
               'ingredients' => @$ingredients,
               'instructions' =>@$instructions,
               'servings' => @$data['servings'],
              // 'serving_size' => @$data['serving-size'],
               'status' => @$data['status'],
              // 'submitter' => @$data['submitter'],
              // 'submitter_email' => @$data['submitter_email'],
               
                //george add here
               'nutrient_per_serving' => @$data['nutrient_per_serving'],
               'photo_credit' => @$data['photo_credit'],
               'subtitle' => @$data['subtitle'],
               'author' => @$data['submitter'], //$data['author'],
              
               'image' =>@$data['image_ext'],
              
               'updated' => time(),
          );
     }

     
 /*  keep the orinal input here 
      function input($data = NULL) {
          global $current_user;
          get_currentuserinfo();

          if ( !$data ) {
               $data = $_POST;
          }

          if ( count($data) == 0 ) {
               return array('ingredients' => array());
              
          }
          $ingredients = array();

          if ( isset($data['ingredients']) ) {
               $ingredientArray = $data['ingredients'];

               if ( is_array($ingredientArray) ) {
                    foreach ( $ingredientArray as $id => $ingredient ) {
                         if ( $id != 'NULL' and (isset($ingredient['item'])) ) {
                              $ingredients[$id] = $ingredient;
                         }
                    }
               }
          } else {
               $ingredients = array();
          }

          return array(
               'title' => @$data['title'],
               'user_id' => @$data['user_id'],
               'notes' => @$data['notes'],
               'prep_time' => @$data['prep_time'],
               'cook_time' => @$data['cook_time'],
               'ready_time' => @$this->readyTime(),
               'ready_time_raw' => @$this->readyTime(NULL, NULL, false),
               'recipe-category' => @$data['recipe-category'],
               'recipe-cuisine' => @$data['recipe-cuisine'],
               'ingredients' => @$ingredients,
               'instructions' => @$data['instructions'],
               'servings' => @$data['servings'],
              // 'serving_size' => @$data['serving-size'],
               'status' => @$data['status'],
               'submitter' => @$data['submitter'],
               'submitter_email' => @$data['submitter_email'],
               'updated' => time(),
          );
     }
  * */

            
     /**
      * Method to save to recipe box.
      *
      * @param  $recipe_id, $user_id
      * @return true(Save Success) ; false (save fail)
      */
    function save_to_recipe_box($recipe_id, $user_id) {

       $usermeta = (array) get_user_meta( $user_id, '_recipe_press_my_box', true);

       if ( is_array($usermeta) and array_key_exists($recipe_id, $usermeta) ) {
            return false;
       } else {
            $usermeta[$recipe_id] = array(
                 'category' => 'new-addition',
                 'added' => time()
            );

            update_user_meta($user_id, '_recipe_press_my_box', $usermeta);
        }
        return true;
    } 
     

     /**
      * Method to populate default taxonomy settings.
      *
      * @param array $tax
      * @return array
      */
     function taxDefaults($tax) {
          $defaults = array(
               'default' => false,
               'hierarchical' => false,
               'active' => false,
               'delete' => false,
               'allow_multiple' => false,
               'page' => false,
               'per-page' => 10,
          );

          /* Make sure the taxonomy has the singular and plural names. */
          if ( $tax['singular'] == '' ) {
               $tax['singular'] = ucwords(rp_inflector::humanize($tax['slug']));
          }

          if ( $tax['plural'] == '' ) {
               $tax['plural'] = rp_inflector::plural(ucwords(rp_inflector::humanize($tax['slug'])));
          }
          return wp_parse_args($tax, $defaults);
     }

     /**
      * Method to filter the output and add the recipe details.
      *
      * @global object $post
      * @global object $wp
      * @global object $current_user
      * @param string $content
      * @return string
      */
     function the_content_filter($content) {
          global $post, $wp, $current_user;
          get_currentuserinfo();

          $files = get_theme(get_option('current_theme'));

          if ( is_single ( ) ) {
               $template_file = get_stylesheet_directory() . '/single-recipe.php';
          } elseif ( is_archive ( ) ) {
               $template_file = get_stylesheet_directory() . '/archive-recipe.php';
          } else {
               $template_file = get_stylesheet_directory() . '/index-recipe.php';
          }

          if ( $post->post_type != 'recipe' or in_array($template_file, $files['Template Files']) or $this->in_shortcode ) {
               return $content;
          }

          remove_filter('the_content', array(&$this, 'the_content_filter'));

          if ( is_archive ( ) ) {
               $template = $this->get_template('recipe-archive');
          } elseif ( is_single ( ) ) {
               $template = $this->get_template('recipe-single');
          } elseif ( $post->post_type == 'recipe' and in_the_loop() ) {
               $template = $this->get_template('recipe-loop');
          } else {
               return $content;
          }

          ob_start();
          require ($template);
          $content = ob_get_contents();
          ob_end_clean();

          add_filter('the_content', array(&$this, 'the_content_filter'));

          return $content;
     }

     
     /**
      * Save the ingredients.
      *
      * @global object $post
      * @param string $post_id
      * @param array $ingredients
      */
     
      function save_ingredients($post_id, $ingredients) {
          global $post;
          $detailkey = '_recipe_ingredient_value';
          delete_post_meta($post_id, $detailkey);                   
         
          if ( !is_array($ingredients) ) {
             return ; 
          }
          
        //need to care about where should do shift ?
          
          $saveIngredients = array();
          if ( is_array($ingredients) ) {
               $index = 1;
               foreach ( $ingredients as $id => $ingrident ) {
                   if ( $id != 'NULL' and (isset($ingrident['item'])) ) {
                       $saveIngredients[$index] = $ingrident;
                       $index++;
                    }
                }
          }
                  
          add_post_meta($post_id, $detailkey, $saveIngredients, false);          
     }
     
     

     /**
      * Retrieve a template file from either the theme or the plugin directory.
      *
      * @param <string> $template    The name of the template.
      * @return <string>             The full path to the template file.
      */
     function get_template($template = NULL, $ext = '.php', $type = 'path') {
          if ( $template == NULL ) {
               return false;
          }

          $themeFile = get_stylesheet_directory() . '/' . $template . $ext;
          $folder = '/';

          if ( !file_exists($themeFile) ) {
               $themeFile = get_stylesheet_directory() . '/recipe-press/' . $template . $ext;
               $folder = '/recipe-press/';
          }

          if ( file_exists($themeFile) and !$this->in_shortcode ) {
               if ( $type == 'url' ) {
                    $file = get_bloginfo('template_url') . $folder . $template . $ext;
               } else {
                    $file = get_stylesheet_directory() . $folder . $template . $ext;
               }
          } elseif ( $type == 'url' ) {
               $file = $this->templatesURL . $template . $ext;
          } else {
               $file = $this->templatesPath . $template . $ext;
          }

          return $file;
     }

     /**
      * Creates the ingredient form for both sides of the system.
      *
      * @param array $ingredients
      * @return string
      */
     
     function get_ingredient_form($ingredients = array()) {
          if ( !$ingredients ) {
               $ingredients = $this->emptyIngredients($this->options['ingredients-fields']);
          }

          $this->publicForm = true;

          $file = $this->pluginPath . 'includes/ingredient-form.php';
          ob_start();
          require($file);
          $output = ob_get_contents();
          ob_end_clean();

          return $output;
     }
 
     /**
      * Get the ingredients stored in the post meta.
      *
      * @global <object> $post   If no ID is specified, use the preloaded post object.
      * @param <integer> $post   ID of the post, NOT the post object.
      * @return <array>
      */
     function getIngredients($post = NULL) {
          if ( !$post ) {
               global $post;
          }

          $ingredients = get_post_meta($post->ID, '_recipe_ingredient_value');

          if ( count($ingredients) < 1 ) {
               return $this->emptyIngredients($this->options['ingredients-fields']);
          } else {
               $ings = array();
               $ings = array_pop($ingredients);          
               ksort($ings);
               return $ings;
          }
     }

     /**
      * Return an empty array for creating ingredients form on new posts.
      *
      * @param <integer> $count
      * @return <array>
      */     
     function emptyIngredients($count = 5) {
          $ingredients = array();
           for ( $ctr = 0; $ctr < $count; ++$ctr ) {
               $ingredients[$ctr]['item'] = 0;
          }
          return $ingredients;
     }

     /**
      * Calculate the ready time for a recipe.
      *
      * @param <integer> $prep   The prep time.
      * @param <integer> $cook   The cook time.
      * @return <string>         Formatted ready time.
      */
     function readyTime($prep = NULL, $cook = NULL, $formatted = true) {
          if ( !isset($prep) ) {
               $prep = isset($_POST['recipe_details']['recipe_prep_time']) ? $_POST['recipe_details']['recipe_prep_time'] : 0;
          }

          if ( !isset($cook) ) {
               $cook = isset($_POST['recipe_details']['recipe_cook_time']) ? $_POST['recipe_details']['recipe_cook_time'] : 0;
          }

          $hplural = '';
          $mplural = '';

          $total = $prep + $cook;

          if ( $total > 60 ) {
               $hours = floor($total / 60);

               if ( $hours > 1 and $this->options['plural-times'] )
                    $hplural = 's';
               else
                    $mplural = '';

               $hours = $hours . ' ' . $this->options['hour-text'] . $hplural . ', ';
          } else {
               $hours = '';
          }

          $mins = $total - ( $hours * 60);

          if ( $mins > 1 and $this->options['plural-times'] )
               $mplural = 's';
          else
               $mplural = '';

          if ($formatted) {
               return $hours . $mins . ' ' . $this->options['minute-text'] . $mplural;
          } else {
               return $total;
          }
     }

     /**
      * Create a help icon on the administration pages.
      *
      * @param <string> $text
      */
     function help($text) {
          echo '<img src="' . $this->pluginURL . 'images/icons/help.jpg" align="absmiddle"onmouseover="return overlib(\'' . $text . '\');" onmouseout="return nd();" />';
     }

     /**
      * Displayes any data sent in textareas.
      *
      * @param <type> $input
      */
     function debug($input) {
          $contents = func_get_args();

          foreach ( $contents as $content ) {
               print '<textarea style="width:49%; height:250px; float: left;">';
               print_r($content);
               print '</textarea>';
          }

          echo '<div style="clear: both"></div>';
     }

     
     
    //***************************************************************************
    //     
    //add instruction operation

     /*
     * Notes: below function can be optimised later,just need to pass object id to differatite ingrident or instruction or anything else 
     * 
     */
     
     
       /**
      * Creates the instruction form for both sides of the system.
      *
      * @param array $ingredients
      * @return string
      */
     function get_direction_form($instructions = array()) {
          if ( !$instructions ) {
               $instructions = $this->emptyinstructions($this->options['instructions-fields']);
          }

          $file = $this->pluginPath . 'includes/direction-form.php';
          ob_start();
          require($file);
          $output = ob_get_contents();
          ob_end_clean();

          return $output;
     }

     
     
     /**
      * Get the instructions stored in the post meta.
      *
      * @global <object> $post   If no ID is specified, use the preloaded post object.
      * @param <integer> $post   ID of the post, NOT the post object.
      * @return <array>
      */
     function getInstructions($post = NULL) {
          if ( !$post ) {
               global $post;
          }

          $instructions = get_post_meta($post->ID, '_recipe_instruction_value');
          
          if ( count($instructions) < 1 ) {
               return $this->emptyinstructions($this->options['instructions-fields']); //from admin stage opition
          } else {
          
              // george add here for temporily
              $ings = array_pop($instructions);          
               ksort($ings);
               return $ings;
          }
     }

     /**
      * Return an empty array for creating instructions form on new posts.
      *
      * @param <integer> $count
      * @return <array>
      */
     function emptyinstructions($count = 2) {
         //$count should set as opition value from admin stage setting 
         $instructions = array();
          for ( $ctr = 0; $ctr < $count; ++$ctr ) {
               $instructions[$ctr]['item'] = 0;
          }

          return $instructions;
     }

     
     /**
      * Save the ingredients.
      *
      * @global object $post
      * @param string $post_id
      * @param array $ingredients
      */
     function save_instructions($post_id, $instructions) {
          global $post;
          $detailkey = '_recipe_instruction_value';
          delete_post_meta($post_id, $detailkey);                   
         
          if ( !is_array($instructions) ) {
             return ; 
          }
          //need to care about where should do shift ?
          
          $saveInsructions = array();
          if ( is_array($instructions) ) {
               $index = 1;
               foreach ( $instructions as $id => $instruction ) {
                   if ( $id != 'NULL' and (isset($instruction['item'])) ) {
                       $saveInsructions[$index] = $instruction;
                       $index++;
                    }
                }
          }
          
          
          add_post_meta($post_id, $detailkey, $saveInsructions, false);
          
     }

        
      /**
      * Get the recipe form field data stored in the post meta.
      *
      * @global <object> $post   If no ID is specified, use the preloaded post object.
      * @param <integer> $post   ID of the post, NOT the post object.
      * @return <array>
      */
     function get_recipe_front_form_fields_data($recipe_id = NULL) {
         $recipe_field_data_array;
         
         if ( !$recipe_id ) {
               global $post;
               $recipe_id = $post->ID;              
          }
          
          $recipe_post = get_post($recipe_id); 
          $custom   = get_post_custom($recipe_id);
          $img_show = wp_get_attachment_image_src($custom["_thumbnail_id"][0], array(200,200));
       
          $recipe_field_data_array = array(
              'title' => $recipe_post->post_title,
              'notes' => $recipe_post->post_content,
              'image' => $img_show[0],
              'prep_time' => $custom["_recipe_prep"][0],
              'cook_time' => $custom["_recipe_cook"][0],
              'servings' => $custom["_recipe_servings"][0],
             // 'serving_size' => $custom["_recipe_serving_size_value"][0],
              'nutrient_per_serving' => $custom["_recipe_nutrients"][0],
              'photo_credit' => $custom["_recipe_photo_credit"][0],
              'subtitle'    => $custom['_recipe_subtitle'][0],
              'author' => $custom["_recipe_author"][0],
              'ratings_users' => $custom["ratings_users"][0],
              'ratings_average' => $custom["ratings_average"][0],
              'ratings_score' => $custom["ratings_score"][0],
              'instructions' => $this->getInstructions($recipe_post),
              'ingredients' => $this->getIngredients($recipe_post),
          );
          
          return $recipe_field_data_array;
          
     }

     
      //if failure, return null
      function get_recipe_sub_data($recipe_id = NULL) {
         $sub_posts = null;
          
          if ( !$recipe_id ) {
               global $post;
               $recipe_id = $post->ID;              
          }
          
        $custom = get_post_custom($recipe_id);        
        $recipe_child_flag = $custom["_recipe_child_flag"][0];        
  	
        if($recipe_child_flag) return;
        
        $recipe_children = $custom["_recipe_children_id"][0]; /*$custom gets all fileds for the post, then $recipe_photo_credit gets just the photo credit fields*/
  	
        
        $sub_recipes = explode(',',$recipe_children);
        
        if(is_array($sub_recipes) and count($sub_recipes)>0) {        
            foreach($sub_recipes as $val){
                if(!empty($val)) {
                    $tmp_post = get_post($val);
                    if($tmp_post !== null) {
                        $sub_posts[] = $tmp_post;
                    }
                }
                
            }            
        }
       
        return $sub_posts;
     }
    
     
} //Class end 


