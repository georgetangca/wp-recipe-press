<?php

if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * recipe_box_tags.php - Template tags for the recipe box.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 2.2
 */
if ( !function_exists('get_the_recipe_box') ) {

     function get_the_recipe_box($args = array()) {
          global $RECIPEPRESSOBJ, $current_user;
          get_currentuserinfo();

          $defaults = array(
               'template' => 'main',
               'page' => get_query_var('box-page'),
          );

          $args = wp_parse_args($args, $defaults);

          switch ($args['page']) {
               default:
                    $recipeData = get_recipe_box_entries($current_user->ID);
                    $args['template'] = 'recipe-box/' . $args['template'];
          }

          ob_start();
          include ($RECIPEPRESSOBJ->get_template($args['template']));
          $output = ob_get_contents();
          ob_end_clean();

          return $output;
     }

}

if ( !function_exists('the_recipe_box') ) {

     function the_recipe_box($args = array()) {
          echo get_the_recipe_box($args);
     }

}

if ( !function_exists('get_recipe_box_entries') ) {

     function get_recipe_box_entries($user = NULL) {
          global $current_user, $recipeData;

          $user = (NULL === $user) ? $current_user->ID : $user;

          $entries = (array) get_user_meta($user, '_recipe_press_my_box', true);

          if ( $search = get_query_var('recipe_search') ) {
               global $wpdb;
               $query = '
                    select `ID`
                    from `' . $wpdb->posts . '`
                    where `post_type` = "recipe"
                         and (
                              `post_title` like "%' . get_query_var('search') . '%"
                              or `post_excerpt` like "%' . get_query_var('search') . '%"
                              or `post_content` like "%' . get_query_var('search') . '%"
                         )
                         and `ID` in (' . implode(',', array_keys($entries)) . ')
                         and `post_status` = "publish"
               ';
               $include = $wpdb->get_col($query);
          } else {
               $include = array_keys($entries);
          }

          $args = array(
               'post__in' => $include,
               'post_type' => 'recipe',
               'posts_per_page' => -1,
                
          );

          $posts = new WP_Query($args);

          $recipeData = (object) array('entries' => $entries, 'posts' => $posts);
          return $recipeData;
     }

}


if ( !function_exists('get_recipe_box_filter_entries') ) {

     function get_recipe_box_filter_entries($rule_array = NULL, $with_notes = NULL) {
          global $current_user, $recipeData;

          $user = $current_user->ID;

          $entries = (array) get_user_meta($user, '_recipe_press_my_box', true);
          /** George: Here the first of $entries is empty   **/
          $entries = array_slice($entries,1,count($entries),true);
          
          krsort($entries);
         // var_dump($entries);
          
          if(isset($rule_array['offset'])){
               if(isset($rule_array['posts_per_page'])){
                   $entries = array_slice($entries, $rule_array['offset'],$rule_array['posts_per_page'],true);
               } else {
                   $entries = array_slice($entries, $rule_array['offset']);
               }
          } elseif (isset($rule_array['posts_per_page'])) {
                $entries = array_slice($entries, 0, $rule_array['posts_per_page'],true);
          }
          
          
          if ($with_notes == 'with_notes') { //fitler the entry without notes
              foreach($entries as $key=>$val ){
                  if (empty($val['notes'])){
                      unset($entries[$key]);
                  }
              } 
          }
          
          $search = $rule_array['recipe_search'];
 
          
          if (!empty($search)) {
               global $wpdb;
               $query = '
                    select `ID`
                    from `' . $wpdb->posts . '`
                    where `post_type` = "recipe"
                         and (
                              `post_title` like "%' . $search . '%"
                              or `post_excerpt` like "%' . $search . '%"
                              or `post_content` like "%' . $search . '%"
                         )
                         and `ID` in (' . implode(',', array_keys($entries)) . ')                        
               ';
               $include = $wpdb->get_col($query);
          } else {
               $include = array_keys($entries);
          }
          if(count($include)==0) {
              $include = array('');
          }

          $args = array(
               'post__in'  => $include,
               'post_type' => 'recipe',
               'post_status' => 'any',
               'offset' =>'0',
              // 'posts_per_page' => -1
          );
          
         $args = wp_parse_args($args, $rule_array);
                 
         
          $posts = new WP_Query($args);
          
         

          $recipeData = (object) array('entries' => $entries, 'posts' => $posts);
         // return $recipeData;
         return $posts;
     }

}

if ( !function_exists('get_the_recipe_box_title') ) {

     function get_the_recipe_box_title($args = array()) {
          global $RECIPEPRESSOBJ;

          return $RECIPEPRESSOBJ->options['recipe-box-title'];
     }

}

if ( !function_exists('the_recipe_box_title') ) {

     function the_recipe_box_title($args = array()) {
          echo get_the_recipe_box_title($args);
     }

}

if ( !function_exists('get_the_recipe_box_search') ) {

     function get_the_recipe_box_search($args = array(), $action = NULL) {
          global $RECIPEPRESSOBJ;

          //george comment here
          //$action = get_option('home') . '/' . $RECIPEPRESSOBJ->options['recipe-box-slug'];
          
          if( !isset($action)) {
            $action = get_the_recipe_box_url();
          }
          
          $output = '<form name="recipe-box-search" method="post" action="' . $action . '">';
          
          //$output.= '<input name="search" class="widget-recipe-box-search" value="' . get_query_var('search') . '" type="text">';
          //$output.= '<input id="recipe_box_search" type="image" title="' . __('Search Recipe Box', 'recipe-press') . '" src="' . $RECIPEPRESSOBJ->pluginURL . '/images/icons/search-icon.png" width="24" height="24" />';
          $output.= '<input name="recipe_search"  placeholder="Search My Recipe Box" class="widget-recipe-box-search" value="" type="text">';
          $output.= '<button id="recipe_box_search" class="btn" title="' . __('Search Recipe Box', 'recipe-press') . '" >Search</button>';
          
          $output.= '</form>';

          return apply_filters('recipe_box_search_form', $output);
     }

}

if ( !function_exists('the_recipe_box_search') ) {

     function the_recipe_box_search($args=array()) {
          echo get_the_recipe_box_search($args);
     }

}

if ( !function_exists('get_the_recipe_box_date') ) {

     function get_the_recipe_box_date($args = array()) {
          global $recipeData;

          $defaults = array(
               'date-format' => get_option('date_format'),
          );

          $args = wp_parse_args($args, $defaults);

          return date($args['date-format'], $recipeData->entries[get_the_id()]['added']);
     }

}

if ( !function_exists('the_recipe_box_date') ) {

     function the_recipe_box_date($args = array()) {
          echo get_the_recipe_box_date($args);
     }

}

if ( !function_exists('get_the_recipe_box_notes_link') ) {

     function get_the_recipe_box_notes_link($args = array()) {
          global $recipeData;

          if ( !empty($recipeData->entries[get_the_id()]['notes']) ) {
               return '<a href="' . the_recipe_box_url() . '" onclick="return recipe_press_view_notes(' . get_the_id() . ')">My Notes &nbsp; 
                    <img src="'.plugins_url("/recipe-plus/images/icons/notes.gif").'" title="'.$recipeData->entries[get_the_id()]['notes'].'"  /></a>';
          } else {
               return '<a href="' . the_recipe_box_url() . '" onclick="return recipe_press_view_notes(' . get_the_id() . ')">Add Notes</a>';
          }
     }

}

if ( !function_exists('the_recipe_box_notes_link') ) {

     function the_recipe_box_notes_link($args =array()) {
          echo get_the_recipe_box_notes_link($args);
     }

}


if ( !function_exists('get_recipe_box_link') ) {

     function get_recipe_box_link($args = array(), $post_id = NULL) {
          global $RECIPEPRESSOBJ, $post, $current_user;
          get_currentuserinfo();

          $post_id = ( NULL === $post_id ) ? get_the_ID() : $post_id;
          $post = get_post($post_id);

          $defaults = array(
               'title' => $RECIPEPRESSOBJ->options['recipe-box-add-title'],
               'view-box-title' => $RECIPEPRESSOBJ->options['recipe-box-view-title'],
               'tag' => 'span', //george change tag <li > as <span>
               'class' => 'recipe-box-link',
               'text-only' => false,
               'add-link-image' => false,
               'view-link-image' => false
          );
          $args = wp_parse_args($args, $defaults);

          /* Check if we have an image for the add link */
          if ( $args['add-link-image'] ) {
               list($width, $height, $type, $attr) = getimagesize($args['add-link-image']);
               $addlink = '<img src="' . $args['add-link-image'] . '" ' . $attr . '>';
          } else {
               $addlink = $args['title'];
          }

          /* Check if we have an image for the view link */
          if ( $args['view-link-image'] ) {
               list($width, $height, $type, $attr) = getimagesize($args['view-link-image']);
               $viewlink = '<img src="' . $args['view-link-image'] . '" ' . $attr . '>';
          } else {
               $viewlink = $args['view-box-title'];
          }

          /* Only show the links if the user is logged in */
          if ( is_user_logged_in ( ) ) {
               $favorites = (array) get_user_meta($current_user->ID, '_recipe_press_my_box', true);

               if ( $args['text-only'] ) {
                    $prefix = '';
                    $suffix = '';
               } else {
                    $prefix = '<span id="recipe-box-link" class="recipe-controls ' . $args['class'] . '">';
                    $suffix = '</span>';
               }

               if ( array_key_exists($post_id, $favorites) ) {
                    /* In the box - view link */
                    $output = '<a href="' . get_the_recipe_box_url() . '">' . $viewlink . '</a>';
                    return apply_filters('recipe_press_in_box_link', $prefix . $output . $suffix);
               } else {
                    /* Not in the box - add link */
                    $custom = get_post_custom($post_id);        
                    $recipe_child_flag = $custom["_recipe_child_flag"][0];        
  	
                    if($recipe_child_flag) { //if it is the child recipe,can't show "add to recipe-box" 
                        $output = '';
                    } else {
                        $output = '<a href="' . get_the_recipe_box_url() . '" onclick="return recipe_press_add_to_box(' . $post_id . ', \'' . wp_create_nonce($post->post_title) . '\');">' . $addlink . '</a>';
                    }
                    
                    return apply_filters('recipe_press_add_to_box_link', $prefix . $output . $suffix);
               }
          }
     }

}

if ( !function_exists('the_recipe_box_link') ) {

     /**
      * Displays the link to the recipe box.
      *
      * @param <type> $args
      * @param <type> $post_id
      */
     function the_recipe_box_link($args = array(), $post_id = NULL) {
          echo get_recipe_box_link($args, $post_id);
     }

}

if ( !function_exists('get_the_recipe_box_url') ) {

     function get_the_recipe_box_url() {
          global $RECIPEPRESSOBJ;

          $replacement_template = get_query_template('recipe-box');
          
        /*
          if ( file_exists($replacement_template) ) {
               $link = get_option('home') . '/' . $RECIPEPRESSOBJ->options['recipe-box-slug'];
          } else {
               $link = get_permalink($RECIPEPRESSOBJ->options['recipe-box-page']);
          }
          */

          
          if ( file_exists($replacement_template) ) {
               $link = get_option('home') . '/' . $RECIPEPRESSOBJ->options['recipe-box-slug'];
          } else if(function_exists('bp_loggedin_user_domain')) { //for canadianfamily specific setting
               $link = bp_loggedin_user_domain().'recipes_box';
          } else {
               $link = get_permalink($RECIPEPRESSOBJ->options['recipe-box-page']);
          }
          
          return $link;
     }

}

if ( !function_exists('the_recipe_box_url') ) {

     function the_recipe_box_url() {
          return get_the_recipe_box_url();
     }

}

if ( !function_exists('get_the_recipe_notes_form') ) {

     function get_the_recipe_notes_form($args = array()) {
          global $recipeData;

          $notes = (!empty($recipeData->entries[get_the_id()]['notes'])) ? $recipeData->entries[get_the_id()]['notes'] : __('No notes', 'recipe-press');
          $notesfield = '<textarea id="recipe_box_notes_field_' . get_the_id() . '" class="recipe-box-notes-field">' . $notes . '</textarea>';
          $notesfield.= '<div class="recipe-box-notes-buttons">';
          $notesfield.= '<input type="button" onclick="recipe_press_save_notes(' . get_the_id() . ')" value="' . __('Save Notes', 'recipe-press') . '" />';
          $notesfield.= '<input type="button" onclick="recipe_press_close_notes(' . get_the_id() . ')" value="' . __('Cancel', 'recipe-press') . '" />';
          $notesfield.= '</div>';

          return $notesfield;
     }

}

if ( !function_exists('the_recipe_notes_form') ) {

     function the_recipe_notes_form($args = array()) {
          echo get_the_recipe_notes_form($args);
     }

}