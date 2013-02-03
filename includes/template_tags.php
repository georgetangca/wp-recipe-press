<?php

if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * template_tags.php - Additional template tags for RecipePress
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
/**
 * Conditionals
 */

if ( !function_exists('edit_recipe_post_link') ) {
    function edit_recipe_post_link( $link = null, $before = '', $after = '', $id = 0,  $url = null ) {
           global $current_user; 
           if ( !$post = &get_post( $id ) )
                    return;

           if($current_user->ID != $post->post_author)
               return;
                       
            $post_type_object = get_post_type_object( $post->post_type );
            if ( !$post_type_object )
                    return;
            
            if ( empty($url) and function_exists('bp_loggedin_user_domain')) { //for canadianfamily specific setting
                   $url = bp_loggedin_user_domain().'recipe_form'.'?recipe_action=recipe_edit&recipe_id='.$post->ID;    
            } elseif (empty($url)){
                $url = home_url(); //default as wp root url;  
            } else {
                $url .= '?recipe_action=recipe_edit&recipe_id='.$post->ID;
            }
            
            $link = '<a class="post-edit-link" href="' . $url . '" title="' . esc_attr( $post_type_obj->labels->edit_item ) . '">' . $link . '</a>';
            echo $before . apply_filters( 'edit_recipe_post_link', $link, $post->ID ) . $after;
    }

}



if ( !function_exists('use_recipe_taxonomy') ) {

     function use_recipe_taxonomy($tax = 'recipe-category') {
          global $RECIPEPRESSOBJ;
          return apply_filters('recipe_press_use_taxonomy', isset($RECIPEPRESSOBJ->options['taxonomies'][$tax]['active']) && $RECIPEPRESSOBJ->options['use-taxonomies']);
     }

}

if ( !function_exists('use_recipe_times') ) {

     function use_recipe_times() {
          global $RECIPEPRESSOBJ;
          return apply_filters('recipe_press_use_times', $RECIPEPRESSOBJ->options['use-times']);
     }

}

if ( !function_exists('use_recipe_servings') ) {

     function use_recipe_servings() {
          global $RECIPEPRESSOBJ;
          return apply_filters('recipe_press_use_servings', $RECIPEPRESSOBJ->options['use-servings']);
     }

}

if ( !function_exists('use_recipe_comments') ) {

     function use_recipe_comments() {
          global $RECIPEPRESSOBJ;
          return apply_filters('recipe_press_use_comments', $RECIPEPRESSOBJ->options['use-comments']);
     }

}

if ( !function_exists('get_recipe_time') ) {

     /**
      * Get the recipe time.
      *
      * @global <object> $RECIPEPRESSOBJ
      * @param <array> $args         Display time: 'single' for single line, 'double' (default) for double line
      * @param <int/object> $post    Should be a post ID, NOT a post object.
      * @return <string>             Text including time and (minutes/hours)
      */
     function get_recipe_time($args = array(), $post = NULL) {
          global $RECIPEPRESSOBJ;

          if ( is_int($post) ) {
               $post = get_post($post);
          } elseif ( !is_object($post) ) {
               global $post;
          }

          /* Leave for backward compatibility */
          if ( !is_array($args) ) {
               $args = array($args);
          }

          /* Prep the arguments */
          $defaults = array(
               'time' => 'prep',
               'type' => $RECIPEPRESSOBJ->options['time-display-type'],
               'title' => __('Prep Time', 'recipe-press'),
               'prefix' => ' : ',
               'suffix' => $RECIPEPRESSOBJ->options['minute-text'],
               'tag' => 'li',
               'class' => 'recipe-prep'
          );

          $args = wp_parse_args($args, $defaults);
          $output = '';

          if ( $time = get_post_meta($post->ID, '_recipe_' . $args['time'] . '_time_value', true) ) {
               if ( $args['tag'] ) {
                    $output.= '<' . $args['tag'] . ' id="recipe-' . $args['time'] . '-' . $post->ID . '" class="' . $args['class'] . '">';
               }
               $output.= '<span class="details-header details-header-prep">' . ucfirst($args['title']) . $args['prefix'] . '</span>';

               switch ($args['type']) {
                    case 'double':
                         $output.= '<br>' . $time . $args['suffix'];
                         break;
                    default:
                         $output.= ' ' . $time . $args['suffix'];
                         break;
               }

               if ( $args['tag'] ) {
                    $output.= '</' . $args['tag'] . '>';
               }

               return apply_filters('recipe-press-get_time', $output);
          }
     }

}

if ( !function_exists('get_recipe_prep_time') ) {

     /**
      * Get and display the recipe prep time.
      *
      * @param <array> $type     Dsiplay arguments.
      * @param <int> $post       ID of the post, NOT the post object.
      */
     function get_recipe_prep_time($args = array(), $post = NULL) {
          if ( !is_array($args) ) {
               $args = array('type' => $args);
          }

          $args['time'] = 'prep';
          $args['title'] = __('Prep Time', 'recipe-press');
          return apply_filters('recipe_pres_prep_time', get_recipe_time($args, $post));
     }

}

if ( !function_exists('the_recipe_prep_time') ) {

     /**
      * Display the recipe prep time.
      *
      * @param <array> $args     Display arguments.
      * @param <id> $post        The post ID, NOT the post object.
      */
     function the_recipe_prep_time($args = array(), $post = NULL) {
          echo get_recipe_prep_time($args, $post);
     }

}

if ( !function_exists('get_recipe_cook_time') ) {

     /**
      * Get and display the recipe cooking time.
      *
      * @param <array> $args     Display arguments.
      * @param <int> $post       ID of post, NOT the post object.
      * @return <string>         The cook time plus (minutes/hours)
      */
     function get_recipe_cook_time($args = array(), $post = NULL) {
          if ( !is_array($args) ) {
               $args = array('type' => $args);
          }

          $args['time'] = 'cook';
          $args['title'] = __('Cook Time', 'recipe-press');
          $args['class'] = 'cook-time';
          return apply_filters('recipe_press_cook_time', get_recipe_time($args, $post));
     }

}

if ( !function_exists('the_recipe_cook_time') ) {

     /**
      * Display the recipe cooking time.
      *
      * @param <array> $args     Display arguments.
      * @param <id> $post        The post ID, NOT the post object.
      */
     function the_recipe_cook_time($args = array(), $post = NULL) {
          echo get_recipe_cook_time($args, $post);
     }

}

if ( !function_exists('get_recipe_ready_time') ) {

     /**
      * Get the recipe ready time.
      *
      * @param <array> $args     Display arguments.
      * @param <int> $post       The post ID, NOT the post object.
      * @return <string>         The ready time.
      */
     function get_recipe_ready_time($args = array(), $post = NULL) {
          if ( !is_array($args) ) {
               $args = array('type' => $args);
          }

          $args['time'] = 'ready';
          $args['title'] = __('Ready Time', 'recipe-press');
          $args['class'] = 'ready-time';

          if ( !isset($args['suffix']) ) {
               $args['suffix'] = '';
          }

          return apply_filters('recipe_press_ready_time', get_recipe_time($args, $post));
     }

}

if ( !function_exists('the_recipe_ready_time') ) {

     /**
      * Display the recipe ready time.
      *
      * @param <array> $args     Display arguments.
      * @param <type> $post      ID of the post, NOT the post object.
      */
     function the_recipe_ready_time($args = array(), $post = NULL) {
          echo get_recipe_ready_time($args, $post);
     }

}

if ( !function_exists('get_recipe_servings') ) {

     /**
      * Get the Recipe serving information.
      *
      * @global <object> $RECIPEPRESSOBJ
      * @param <array> $args         Display arguments
      * @param <int/ojbect> $post    Post ID or Object
      * @return <string>             Recipe serving text.
      */
     function get_recipe_servings($args = array(), $post = NULL) {
          global $RECIPEPRESSOBJ;

          if ( is_int($post) ) {
               $post = get_post($post);
          } elseif ( !is_object($post) ) {
               global $post;
          }

          $defaults = array(
               'tag' => 'div',
               'class' => 'recipe_servings_value'
          );

          $args = wp_parse_args($args, $defaults);

          if ( $servings = get_post_meta($post->ID, '_recipe_servings_value', true) ) {

               $size = get_post_meta($post->ID, '_recipe_serving_size_value', true);
               if ( (int) $size != 0 and $size != -1 ) {
                    $term = get_term_by('id', $size, 'recipe-serving');
                    if ( is_object($term) ) {
                         $size = $term->name;
                    }
               } elseif ( $size == -1 ) {
                    unset($size);
               }

               /* translators: Displayed before serving information on recipe display pages. */
               $output = '<' . $args['tag'] . ' class="' . $args['class'] . '">' . $servings . ' ';

               if ( isset($size) ) {
                    if ( calculateIngredientSize($servings) > 1 ) {
                         $output.= rp_inflector::plural($size);
                    } else {
                         $output.= rp_inflector::singular($size);
                    }
               }
               $output.= '</' . $args['tag'] . '>';

               return apply_filters('recipe_press_servings', $output);
          }
     }

}

if ( !function_exists('the_recipe_servings') ) {

     /**
      * Display the recipe serving information.
      *
      * @param <array> $args         Display arguments.
      * @param <int/object> $post    Post ID or Object
      */
     function the_recipe_servings($args = array(), $post = NULL) {
          echo get_recipe_servings($args, $post);
     }

}

if ( !function_exists('get_recipe_ingredients') ) {

     /**
      * Get the recipe ingredients text.
      *
      * @global <type> $RECIPEPRESSOBJ
      * @param <int/object> $post    Post ID or Object
      */
    function get_recipe_ingredients($post = NULL) {
          global $RECIPEPRESSOBJ;

          if ( is_int($post) ) {
               $post = get_post($post);
          } elseif ( !is_object($post) ) {
               global $post;
          }

          $ingredients_listed = $RECIPEPRESSOBJ->getIngredients($post);

          $content = '';

          if ($ingredients_listed != "") { 
             $content .=  '<h5>Ingredients</h5>';
             foreach ($ingredients_listed as $ingredient){
                    $content .= '<li>' .$ingredient['new-ingredient'] . '</li>';	
             }
         }
        return apply_filters('recipe_press_ingredients', $content);
     }

}

if ( !function_exists('the_recipe_ingredients') ) {

     /**
      * Display the recipe ingredients.
      *
      * @param <int/object> $post    Post ID or Object.
      */
     function the_recipe_ingredients($post = NULL) {
          echo get_recipe_ingredients($post);
     }

}

if ( !function_exists('get_recipe_nutrients') ) {

     /**
      * Get the recipe nutrients
      *
      * @global object $RECIPEPRESSOBJ
      * @param int/object $post    Post ID or Object.
      * @param string $template    The file name of the template to use, no extensions.
      */
     function get_recipe_nutrients($post = NULL, $template = 'nutrition-box') {
          global $RECIPEPRESSOBJ;

          if ( !$RECIPEPRESSOBJ->options['use-nutritional-value'] ) {
               return false;
          }

          $nutrient = array();

          if ( is_int($post) ) {
               $post = get_post($post);
          } elseif ( !is_object($post) ) {
               global $post;
          }

          $display = false;
          $nutrient = unserialize(get_post_meta($post->ID, '_recipe_nutrients_value', true));

          if ( !is_array($nutrient) ) {
               return false;
          }

          foreach ( $nutrient as $item ) {
               if ( $item != '' ) {
                    $display = true;
               }
          }

          if ( !$display ) {
               return false;
          }

          ob_start();
          require($RECIPEPRESSOBJ->get_template($template));
          $output = ob_get_contents();
          ob_end_clean();

          return apply_filters('recipe_press_nutrients', $output);
     }

}

if ( !function_exists('the_recipe_nutrients') ) {

     /**
      * Display the recipe nutrient information.
      *
      * @param <int/object> $post    Post ID or Object
      */
     function the_recipe_nutrients($post = NULL) {
          echo get_recipe_nutrients($post);
     }

}

if ( !function_exists('get_the_recipe_directions') ) {

     /**
      * Get the recipe directions.
      *
      * @global <type> $RECIPEPRESSOBJ
      * @param <int/object> $post    Post ID or Object.
      * @return <string>
      */
     function get_the_recipe_directions($post = NULL) {
          global $RECIPEPRESSOBJ;

          if ( is_int($post) ) {
               $post = get_post($post);
          } elseif ( !is_object($post) ) {
               global $post;
          }
          
          $content = '';
          $directions_listed = $RECIPEPRESSOBJ->getInstructions($post);
          $dir_number = 1;
          if ($directions_listed != "") { 
              $content .= '<h5>Directions</h5>';
              foreach ($directions_listed as $direction){
                  $content .= '<li><span>' . $dir_number . '.</span><p>' . $direction['new-instruction'] . '</p></li>';	
                  $dir_number++;
              }
          }
          return apply_filters('recipe_press_directions', $content);
     }
}

if ( !function_exists('the_recipe_directions') ) {

     /**
      * Display the recipe directions.
      *
      * @param <int/object> $post    Post ID or Object
      */
     function the_recipe_directions($post = NULL) {
          echo get_the_recipe_directions($post);
     }

}

if ( !function_exists('get_the_recipe_introduction') ) {

     /**
      * Get the recipe introduction.
      *
      * @global <type> $RECIPEPRESSOBJ
      * @param <array> $args         Display arguments.
      * @param <int/object> $post    Post ID or Object.
      * @return <string>
      */
     function get_the_recipe_introduction($args = array(), $post = NULL) {
          global $RECIPEPRESSOBJ;

          if ( is_int($post) ) {
               $post = get_post($post);
          } elseif ( !is_object($post) ) {
               global $post;
          }

          $defaults = array(
               'length' => $RECIPEPRESSOBJ->options['default-excerpt-length'],
               'suffix' => '...'
          );

          $args = wp_parse_args($args, $defaults);

          return apply_filters('recipe_press_introduction', rp_inflector::trim_excerpt($post->post_excerpt, $args['length'], $args['suffix']));
     }

}

if ( !function_exists('the_recipe_introduction') ) {

     /**
      * Display the recipe introduction.
      *
      * @param <array> $args         Display arguments.
      * @param <int/object> $post    Post ID or Object
      */
     function the_recipe_introduction($args = array(), $post = NULL) {
          echo get_the_recipe_introduction($args, $post);
     }

}

if ( !function_exists('get_the_recipe_taxonomy') ) {

     /**
      * Get information on a specific taxonomy.
      *
      * @global <object> $RECIPEPRESSOBJ
      * @param <string> $tax         The taxonomy name.
      * @param <array> $args         Display arguments.
      * @param <int/object> $post    Post ID or Object
      * @return <string>
      */
     function get_the_recipe_taxonomy($tax = NULL, $args = array(), $post = NULL) {
          global $RECIPEPRESSOBJ;

          if ( !taxonomy_exists($tax) ) {
               return false;
          }

          if ( is_int($post) ) {
               $post = get_post($post);
          } elseif ( !is_object($post) ) {
               global $post;
          }

          $defaults = array(
               'prefix' => $RECIPEPRESSOBJ->options['taxonomies'][$tax]['plural'] . ': ',
               'divider' => ', ',
               'before-category' => '',
               'after-category' => '',
               'suffix' => '',
          );

          $args = wp_parse_args($args, $defaults);

          if ( wp_get_object_terms($post->ID, 'recipe-category') ) {
               $cats = $args['prefix'] . get_the_term_list($post->ID, $tax, $args['before-category'], $args['divider'], $args['after-category']) . $args['suffix'];
               return apply_filters('recipe_press_taxonomy', $cats);
          }
     }

}

if ( !function_exists('get_the_recipe_category') ) {

     /**
      * Get the recipe category.
      *
      * @param <array> $args         Display arguments.
      * @param <int/object> $post    Post ID or Object.
      */
     function get_the_recipe_category($args = array(), $post = NULL) {
          if ( !isset($args['prefix']) ) {
               $args['prefix'] = __('Posted In: ', 'recipe-press');
          }
          return apply_filters('recipe_press_categories', get_the_recipe_taxonomy('recipe-category', $args, $post));
     }

}

if ( !function_exists('the_recipe_category') ) {

     /**
      * Display the recipe category.
      *
      * @param <array> $args         Display arguments.
      * @param <int/object> $post    Post ID or Object.
      */
     function the_recipe_category($args = array(), $post = NULL) {
          echo get_the_recipe_category($args, $post);
     }

}

if ( !function_exists('get_the_recipe_cuisine') ) {

     /**
      * Get the recipe cuisines.
      *
      * @param <array> $args         Display arguments.
      * @param <int/object> $post    Post ID or Object.
      */
     function get_the_recipe_cuisine($args = array(), $post = NULL) {
          return apply_filters('recipe_press_cuisines', get_the_recipe_taxonomy('recipe-cuisine', $args, $post));
     }

}

if ( !function_exists('the_recipe_cuisine') ) {

     /**
      * Display the recipe cuisines.
      *
      * @param <array> $args         Display arguments.
      * @param <int/object> $post    Post ID or Object.
      */
     function the_recipe_cuisine($args = array(), $post = NULL) {
          echo get_the_recipe_cuisine($args, $post);
     }

}

if ( !function_exists('calculateIngredientSize') ) {

     /**
      * Calcuate the ingredient size for display
      *
      * @param <array> $ingredient
      * @return <string>
      */
     function calculateIngredientSize($size) {
          $total = 0;

          $sizeSplit = preg_split("/[\s,]+/", $size);

          foreach ( $sizeSplit as $sizePart ) {
               if ( preg_match("/[\/]+/", $sizePart) ) {
                    $args = preg_split("/[\/]+/", $sizePart);
                    $results = $args[0] / $args[1];
               } else {
                    $results = $sizePart;
               }

               $total += $results;
          }

          return apply_filters('recipe_press_ingredient_size', $total);
     }

}

if ( !function_exists('get_recipe_controls') ) {

     function get_recipe_controls($args = array(), $post_id = NULL) {
          global $RECIPEPRESSOBJ;
          $post_id = ( NULL === $post_id ) ? get_the_ID() : $post_id;

          $defaults = array(
               'print' => true, /* change to this when the print options tab is active - $RECIPEPRESSOBJ->options['use-print'], */
               'share' => $RECIPEPRESSOBJ->options['use-recipe-share'],
               'recipe-box' => $RECIPEPRESSOBJ->options['use-recipe-box'],
               'print-link-image' => false,
               'add-link-image' => false,
               'view-link-image' => false
          );

          $args = wp_parse_args($args, $defaults);

          $output = '';

          /* Get print link */
          if ( $args['print'] ) {
               $output.= get_recipe_print_link($args);
          }

          /* Get Share Link */
          if ( $args['share'] ) {
               $output.= get_recipe_share_link($args);
          }

          /* Get Recipe Box Link */
          if ( $args['recipe-box'] ) {
               $output.= get_recipe_box_link($args);
          }

          return apply_filters('recipe_controls', $output);
     }

}

if ( !function_exists('the_recipe_controls') ) {

     function the_recipe_controls($args = array(), $post_id = NULL) {
          echo get_recipe_controls($args, $post_id);
     }

}


if ( !function_exists('get_recipe_print_link') ) {

     /**
      * Returns the link to "print recipe".
      *
      * @global $RECIPEPRESSOBJ $RECIPEPRESSOBJ
      * @global object $post
      * @param array $args
      * @param int/object $post
      * @return string
      */
     function get_recipe_print_link($args = array(), $post_id = NULL) {
          global $RECIPEPRESSOBJ;

          $post_id = ( NULL === $post_id ) ? get_the_ID() : $post_id;

          $defaults = array(
               'title' => __('Print', 'recipe-press'),
               'tag' => 'li',
               'class' => 'recipe-print-link',
               'target' => '_top',
               'popup' => true,
               'template' => $RECIPEPRESSOBJ->options['default-print-template'],
               'print-link-image' => false,
          );

          $args = wp_parse_args($args, $defaults);

          /* Check if we have an image for the add link */
          if ( $args['print-link-image'] ) {
               list($width, $height, $type, $attr) = getimagesize($args['print-link-image']);
               $printlink = '<img src="' . $args['print-link-image'] . '" ' . $attr . '>';
          } else {
               $printlink = $args['title'];
          }

          /* Check if pretty permalinks are in use and build appropriate links. */
          if ( get_option('permalink_structure') ) {
               $urldivider = '?';
          } else {
               $urldivider = '&';
          }

          $output = '<' . $args['tag'] . ' class="recipe-controls ' . $args['class'] . '">' . '<a href="' . get_permalink($post_id) . $urldivider . 'print=' . $args['template'] . '">' . $printlink . '</a></' . $args['tag'] . '>';

          return apply_filters('recipe_press_print_link', $output);
     }

}

if ( !function_exists('recipe_print_link') ) {

     /**
      * Display the link to "print recipe".
      * @param array $args
      * @param int/object $post
      */
     function recipe_print_link($args = array(), $post = NULL) {
          echo get_recipe_print_link($args, $post);
     }

}

if ( !function_exists('get_recipe_share_link') ) {

     function get_recipe_share_link($args = array(), $post_id = NULL) {
          global $RECIPEPRESSOBJ;
          $post_id = ( NULL === $post_id ) ? get_the_ID() : $post_id;

          $defaults = array(
               'title' => __('Share', 'recipe-press'),
               'tag' => 'li',
               'class' => 'recipe-share-link',
               'template' => $RECIPEPRESSOBJ->options['default-share-template']
          );

          $args = wp_parse_args($args, $defaults);

          return '<li class="recipe-controls ' . $args['class'] . '">' . $args['title'] . '</li>';
     }

}

if ( !function_exists('the_recipe_share_link') ) {

     function the_recipe_share_link($args = array(), $post_id = NULL) {
          echo get_recipe_share_link($args, $post_id);
     }

}

if ( !function_exists('get_the_recipe_box_image') ) {

     /**
      * Retrieves the recipe image URL to use in style settings.
      *
      * @global object $post
      * @param integer/object $post
      * @return string
      */
     function get_the_recipe_box_image($post = NULL) {
          if ( is_int($post) ) {
               $post = get_post($post);
          } elseif ( !is_object($post) ) {
               global $post;
          }

          if ( $headerImage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'recipe-press-image') ) {
               return 'style="background: url(' . $headerImage[0] . ') no-repeat top left" onclick="window.location = \'' . get_permalink($post->ID) . '\'" ';
          } else {
               return false;
          }
     }

}

if ( !function_exists('the_recipe_box_image') ) {

     /**
      * Gets the URL for the recipe image to use in style settings.
      * 
      * @param integer $post 
      */
     function the_recipe_box_image($post = NULL) {
          echo get_the_recipe_box_image($post);
     }

}

if ( !function_exists('get_the_next_recipe_link') ) {

     function get_the_next_recipe_link($args = array()) {
          global $recipes, $old_post;
          $current_page = get_query_var('page');

          $defaults = array(
               'link' => __('&larr; More Recipes', 'recipe-press'),
          );

          $args = wp_parse_args($args, $defaults);

          if (get_option('permalink_structure')) {
               $urldivider = '?';
          } else {
               $urldivider = '&';
          }

          if ($current_page > 1) {
               $previous = --$current_page;
               echo '<a href="'. get_permalink($old_post->ID) . $urldivider . 'page=' . $previous . '">' . $args['link'] . '</a>';
          }
     }

}

if ( !function_exists('the_next_recipe_link') ) {

     function the_next_recipe_link($args = array()) {
          echo get_the_next_recipe_link($args);
     }

}

if ( !function_exists('get_the_previous_recipe_link') ) {

     function get_the_previous_recipe_link($args = array()) {
          global $recipes, $old_post;
          $current_page = get_query_var('page');

          $defaults = array(
               'link' => __('More Recipes &rarr;', 'recipe-press'),
          );

          $args = wp_parse_args($args, $defaults);

          if (get_option('permalink_structure')) {
               $urldivider = '?';
          } else {
               $urldivider = '&';
          }

          if ($current_page < (int) $recipes->max_num_pages) {
               $next = max(++$current_page, 2);
               echo '<a href="'. get_permalink($old_post->ID) . $urldivider . 'page=' . $next . '">' . $args['link'] . '</a>';
          }
     }

}

if ( !function_exists('the_previous_recipe_link') ) {

     function the_previous_recipe_link($args = array()) {
          echo get_the_previous_recipe_link($args);
     }

}


if ( !function_exists('show_recipe_contents') ) {
  function show_recipe_contents($recipe_post= null){
      
   if($recipe_post == null) {
      global $post;
      $recipe_post = $post;
  }    
 ?> 
    <div class="recipe-content">      
      <?php echo $recipe_post->post_content; ?>
    </div>
      
    <div id="recipe_servings">
      <?php 
            $servings = get_post_meta($recipe_post->ID, '_recipe_servings', true); 
            if ($servings != "") { ?>
      <h5>Servings</h5>
      <?php 
				
            echo $servings;
            }?>
    </div>
    
    <div id="recipe_prep">
      <?php 
            $prep = get_post_meta($recipe_post->ID, '_recipe_prep', true); 
            if ($prep != "") { ?>
      <h5>Prep Time</h5>
      <?php 
				
                echo $prep;
				 if (is_numeric($prep)) {
				echo " minutes";
				}
				
				
				
            }?>
    </div>
      
      
    <div id="recipe_cook">
      <?php 
            $cook = get_post_meta($recipe_post->ID, '_recipe_cook', true); 
            if ($cook != "") { ?>
      <h5>Cook Time</h5>
      <?php 
				
                echo $cook;
				if (is_numeric($cook)) {
				echo " minutes";
				}
            }?>
    </div>   
      
   

   <div class="recipe-content">      
      <?php //echo $recipe_post->post_content; ?>
    </div>

    <div id="recipe_ingredients">
       <?php the_recipe_ingredients($recipe_post); //function from the plugin  ?>
    </div>
	
	
	<?php
	 global $recipes; 
   $sub_posts =  recipePressCore::get_recipe_sub_data($recipe_post->ID);
   if(is_array($sub_posts) and count($sub_posts)> 0 ){
       $part_number = 1;
       foreach($sub_posts as $val){
          show_recipe_child_contents($val, $part_number);
          $part_number++;
       }       
    } 
	
	
	?>
	

      
    <div id="recipe_directions">
     <?php the_recipe_directions($recipe_post); //function from the plugin ?>
    </div>
      
    <div id="recipe_nutrients">
      <?php 
            $nutrients = get_post_meta($recipe_post->ID, '_recipe_nutrients', true); 
            if ($nutrients != "") { ?>
      <h5>Nutrients Per Serving</h5>
      <?php 
				
             echo $nutrients;
            }?>
    </div>
<?php
  
  }
}
?>

<?php

if ( !function_exists('show_recipe_child_contents') ) {
  function show_recipe_child_contents($recipe_post, $part_number){
    if($recipe_post == null) return ;     
  ?> 
    
    <div class="recipe-content" style="margin-top:10px;">      
      <?php echo "<h5>".$recipe_post->post_title."</h5>"; ?>
    </div>

   
    <div id="recipe_ingredients">
       <?php the_recipe_ingredients($recipe_post); //function from the plugin  ?>
    </div>
      
   
      
   
<?php 
  }
}



if ( !function_exists('show_the_recipe_notes') ) {
   function show_the_recipe_notes( $recipe_post = NULL ){
     $notes = NULL;  
     if($recipe_post == NULL) {
          global $post;
          $recipe_post = $post;
      }
      
      if(is_user_logged_in()){
          global $current_user;
          get_currentuserinfo();

          $usermeta = (array) get_user_meta($current_user->ID, '_recipe_press_my_box', true);
          $notes = $usermeta["$recipe_post->ID"]['notes'] ;
          
      }
      
      if(!empty ($notes)){
        echo '<span class="highlight">My Notes:</span></br>';
        echo '<p>'.$notes.'</p>';
      }
    }
}