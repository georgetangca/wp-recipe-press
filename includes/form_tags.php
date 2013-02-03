<?php

if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * form_tags.php - Additional form tags for RecipePress
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 2.0
 */
if ( !function_exists('show_recipe_form') ) {

     /**
      * Load and display the recipe form.
      *
      * @global  $RECIPEPRESSOBJ
      * @param string $template    Filename of the template file, no extensions
      */
     function show_recipe_form($template = 'recipe-share') {
          global $RECIPEPRESSOBJ;

          include $RECIPEPRESSOBJ->get_template($template);
     }

}

if ( !function_exists('get_recipe_form_url') ) {

     /**
      * Get the URL for the default recipe form.
      *
      * @global $RECIPEPRESSOBJ $RECIPEPRESSOBJ
      * @return string
      */
     function get_recipe_form_url() {
          global $RECIPEPRESSOBJ;

          $url = get_page_link($RECIPEPRESSOBJ->options['form-page']);

          if ( $RECIPEPRESSOBJ->options['form-extension'] ) {
               $url.= '.' . $RECIPEPRESSOBJ->options['form-extension'];
          }
          return $url;
     }

}

if ( !function_exists('recipe_form_url') ) {

     /**
      * Display the URL to the recipe form.
      */
     function recipe_form_url() {
          echo get_recipe_form_url();
     }

}

if ( !function_exists('share_recipe_fields') ) {

     /**
      * Returns the name of a field.
      *
      * @global object $RECIPEPRESSOBJ
      * @param string $field
      * @param string $text
      * @return string
      */
     function share_recipe_fields($field, $text = NULL) {
          global $RECIPEPRESSOBJ;
          if ( isset($text) ) {
               return $field;
          } else {
               return $RECIPEPRESSOBJ->formFieldNames[$field];
          }
     }

}

if ( !function_exists('share_recipe_get_hidden_fields') ) {

     /**
      * Get the hidden fields for the recipe form.
      *
      * @global object $RECIPEPRESSOBJ
      * @param text $name
      * @param boolean $referer
      * @return string
      */
     function share_recipe_get_hidden_fields($name = '_wpnonce', $referer = true) {
          global $RECIPEPRESSOBJ;

          $output = '<input type="hidden" name="submit_noncename" id="submit_noncename" value="' . wp_create_nonce('recipe_press_submit') . '" />';
          $output.= '<input type="hidden" value="user-submit" name="action"/>';
          $output.= '<input type="hidden" name="user_id" id ="user_id" value="' . get_user_option('ID') . '" />';
          $output.= '<input type="hidden" name="status" value="' . $RECIPEPRESSOBJ->options['new-recipe-status'] . '" />';

          if ( is_user_logged_in ( ) ) {
               $output.= '<input type="hidden" name="submitter" value="' . get_user_option('display_name') . '" />';
              // $output.= '<input type="hidden" name="submitter_email" value="' . get_user_option('user_email') . '" />';
          }

          return $output;
     }

}

if (!function_exists('share_recipe_hidden_fields')) {
/**
 * Output the recipe form hidden fields.
 *
 * @param text $name
 * @param boolean $referer
 */
function share_recipe_hidden_fields($name = '_wpnonce', $referer = true) {
     echo share_recipe_get_hidden_fields($name, $referer);
}
}


if (!function_exists('add_recipe_hidden_field_extension')) {
/**
 * Output the recipe form hidden fields.
 *
 * @param text $name
 * @param boolean $referer
 */
    function add_recipe_hidden_field_extension($action = 'recipe_add_new', $recipe_id = NULL) {
        $output = '<input type="hidden" name="recipe_action" id="recipe_action" value="'.$action.'" />';
        $output.= '<input type="hidden" name="recipe_id" id="recipe_id" value="'.$recipe_id.'" />';
        echo $output;
    }
}


function share_recipe_get_class_name($type = 'table', $field = NULL, $class = NULL) {
     $output = 'recipe-press-' . $type;

     if ( $field ) {
          $output.= ' recipe-press-' . $type . '-' . $field;
     }

     if ( $class ) {
          $output.= ' ' . $class;
     }

     return $output;
}

function share_recipe_class_name($type = 'table', $field = NULL, $class = NULL) {
     echo share_recipe_get_class_name($type, $field, $class);
}

function share_recipe_get_form_label($field = NULL, $text = NULL, $class = NULL) {
     return '<label for="' . $field . '" class="recipe-press-label recipe-press-label-' . $field . ' ' . $class . '">' . share_recipe_fields($field, $text) . '</label>';
}

function share_recipe_form_label($field = NULL, $text = NULL, $class = NULL) {
     echo share_recipe_get_form_label($field, $text, $class);
}

function share_recipe_get_form_field($field = NULL, $type = 'text', $value = NULL, $class = NULL) {
     global $RECIPEPRESSOBJ;

     if ( !isset($value) and isset($_POST[$field]) ) {
          $value = $_POST[$field];
     }

     switch ($type) {
          case 'ingredients':
              if (isset($value)) {
                 $output = $RECIPEPRESSOBJ->get_ingredient_form($value);
              } else {  
                $recipe = $RECIPEPRESSOBJ->input();
                $output = $RECIPEPRESSOBJ->get_ingredient_form($recipe['ingredients']);
              }
               break;
           
          case 'directions':
               if (isset($value)) {
                    $output = $RECIPEPRESSOBJ->get_direction_form($value);
               } else {
                   $recipe = $RECIPEPRESSOBJ->input();
                   $output = $RECIPEPRESSOBJ->get_direction_form($recipe['instructions']);
               }
               break;
               
          case 'nutrient_box':
              //need to add nutrient_box later if necessary    
              break; 
           
          case 'select': //current remove the select tag. no use now 

               switch ($field) {
                    case 'serving-size':
                         if ( $_POST ) {
                              $selected = $_POST['serving-size'];
                         } else {
                              $selected = -1;
                         }
                         $output = rp_dropdown_categories(array('hierarchical' => false, 'taxonomy' => 'recipe-serving', 'hide_empty' => false, 'name' => $field, 'id' => $field, 'orderby' => 'name', 'selected' => $selected, 'echo' => false, 'show_option_none' => __('No size', 'recipe-press')));
                         break;
                    
                      default:
                         if ( $_POST ) {
                              if ( isset($_POST[$field]) ) {
                                   $selected = $_POST[$field];
                              } else {
                                   $selected = array();
                              }
                         } elseif ( isset($RECIPEPRESSOBJ->options['taxonomies'][$field]['default']) ) {
                              $selected = $RECIPEPRESSOBJ->options['taxonomies'][$field]['default'];
                         } else {
                              $selected = '';
                         }

                         if ( isset($RECIPEPRESSOBJ->options['taxonomies'][$field]['hierarchical']) ) {
                              $output = wp_dropdown_categories(array('hierarchical' => true, 'taxonomy' => $field, 'hide_empty' => false, 'name' => $field . '[]', 'id' => $field, 'orderby' => 'name', 'selected' => $selected, 'echo' => false));
                              
                              
                              if ( isset($RECIPEPRESSOBJ->options['taxonomies'][$field]['multiple']) ) {
                                   $output = preg_replace("#<select([^>]*)>#", "<select$1 multiple=true'>", $output);
                              }
                         } else {
                              $output = get_taxonomy_checkboxes($field, $selected);
                         }
                         break;
               }
               break;
               
          case 'textarea':
               $output = '<textarea class=" required ' . share_recipe_get_class_name($type, $field, $class) . '" id="' . $field . '" name="' . $field . '">' . $value . '</textarea>';
               break;
          
           case 'image':
               $output = '<input type="file"  class="upload" id="' . $field . '" name="' . $field . '" value="' . $value . '" />';
               $output.= '<input type="hidden" name="image_ext" id="image_ext" value="'.$value.'" />';
      
               break;
          
          default:
               $output = '<input type="text" class="' . share_recipe_get_class_name($type, $field, $class) . '" id="' . $field . '" name="' . $field . '" value="' . $value . '">';
               break;
     }

     if ( isset($RECIPEPRESSOBJ->errors[$field]) ) {
          $output.= '<br /><span class="recipe-press-error">' . sprintf(__('Missing required field: %1$s', 'recipe-press'), share_recipe_fields($field, $RECIPEPRESSOBJ->errors[$field])) . '</span>';
     }
     return $output;
}


function get_taxonomy_checkboxes($tax = 'recipe-cuisine', $selected = array()) {
     $terms = get_terms($tax, array('hide_empty' => false));

     if ( !is_array($selected) ) {
          $selected = array($selected);
     }

     $output = '';

     foreach ( $terms as $term ) {
          $output.= '<label class="recipe-press-' . $tax . '-public">
        <input id="' . $tax . '_' . $term->slug . '" name="' . $tax . '[' . $term->slug . ']" type="checkbox" value="' . $term->slug . '" ' . checked(in_array($term->slug, $selected), true, false) . '> ' . $term->name . '
        </label>';
     }

     return $output;
}

function get_cuisines_checkboxes($selected = array(), $tax = 'recipe-cuisine') {
     return get_taxonomy_checkboxes($tax, $selected);
}

function share_recipe_form_field($field = NULL, $type = 'text', $value = NULL, $class = NULL) {
     echo share_recipe_get_form_field($field, $type, $value, $class);
}

function share_recipe_get_recaptcha_field() {
     global $RECIPEPRESSOBJ;

     if ( $RECIPEPRESSOBJ->showCaptcha ) {
          $output = recaptcha_get_html($RECIPEPRESSOBJ->options['recaptcha-public']);
          $output.= '<input type="hidden" name="check_captcha" value="1" readonly="readonly" />';

          if ( $errors['validate']->is_valid ) {
               $output.= '<br /><span class="recipe-press-error">' . sprintf(__('Please enter the words above.', 'recipe-press')) . '</span>';
          }
     } elseif ( $errors['validate']->is_valid ) {
          $output = __('Thank you for entering valid captcha text.', 'recipe-press');
     }

     return $output;
}

function share_recipe_recaptcha_field() {
     echo share_recipe_get_recaptcha_field();
}

function share_recipe_get_submit_button($title = NULL) {
     if ( $title == NULL ) {
          $title = __('Submit Recipe', 'recipe-press');
     }
     return '
            <p class="submit">
                <input type="submit" value="' . $title . '" name="submit" onclick="javascript:return recipe_form_check();" class="button-primary"/>
            </p>
            ';
}

function share_recipe_submit_button($title = NULL) {
     echo share_recipe_get_submit_button();
}

function recipe_dropdown_pages($args = '') {

     $args = apply_filters('before_recipe_dropdown_pages', $args);

     $defaults = array(
          'depth' => 0, 'child_of' => 0,
          'selected' => 0, 'echo' => 1,
          'name' => 'page_id', 'id' => '',
          'show_option_none' => '',
          'show_option_no_change' => '',
          'option_none_value' => '',
          'post_type' => array('page', 'post', 'recipe')
     );

     $r = wp_parse_args($args, $defaults);

     extract($r, EXTR_SKIP);

     $pages = array();

     if ( !is_array($post_type) ) {
          $post_type = array($post_type);
     }

     foreach ( $post_type as $type ) {
          $r = array(
               'post_type' => $type,
               'numberposts' => -1,
               'selected' => null,
          );
          $results[$type] = get_posts($r);
     }

     $output = '';
     $name = esc_attr($name);
     // Back-compat with old system where both id and name were based on $name argument
     if ( empty($id) )
          $id = $name;

     if ( !empty($results) ) {
          $output = "<select name=\"$name\" id=\"$id\">\n";
          if ( $show_option_no_change )
               $output .= "\t<option value=\"-1\">$show_option_no_change</option>";
          if ( $show_option_none )
               $output .= "\t<option value=\"" . esc_attr($option_none_value) . "\">$show_option_none</option>\n";

          // $output .= walk_page_dropdown_tree($pages, $depth, $r);

          foreach ( $results as $ptype => $result ) {
               $output .= '<optgroup label="' . ucfirst($ptype) . '">';
               $output .= walk_page_dropdown_tree($result, $depth, $r);
               $output .= '</optgroup>';
          }

          $output .= "</select>\n";
     }

     $output = apply_filters('recipe_dropdown_pages', $output);

     if ( $echo )
          echo $output;

     return $output;
}