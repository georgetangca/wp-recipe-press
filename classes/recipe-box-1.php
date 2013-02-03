<?php

if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * administration.php - RecipePress Administration Class
 *
 * @package RecipePress
 * @subpackage classes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 2.0.4
 */
class recipe_press_recipe_box extends recipePressCore {

     static $instance;

     /**
      * Initialize the class.
      */
     function recipe_press_recipe_box() {
          parent::recipePressCore();

          /* Setup AJAX */
          add_action('wp_ajax_recipe_press_add_to_box', array(&$this, 'recipe_press_add_to_box'));
          add_action('wp_ajax_recipe_press_remove_from_box', array(&$this, 'recipe_press_remove_from_box'));
          add_action('wp_ajax_recipe_press_save_notes', array(&$this, 'save_recipe_box_notes'));
     }

     /**
      * Add a recipe to the users recipe box.
      *
      * @global object $current_user
      */
     function recipe_press_add_to_box() {
          global $current_user;
          get_currentuserinfo();

          $post_id = $_REQUEST['id'];
          $post = get_post($post_id);

          if ( wp_verify_nonce($_REQUEST['nonce'], $post->post_title) ) {

               $usermeta = (array) get_user_meta($current_user->ID, '_recipe_press_my_box', true);

               if ( is_array($usermeta) and array_key_exists($post_id, $usermeta) ) {
                    $status = 'Duplicate';
                    $message = esc_js(sprintf(__('%1$s is already listed in your recipe-box.', 'recipe-press'), $post->post_title));
               } else {
                    $status = 'Added';
                    $message = esc_js(sprintf(__('%1$s has been added to your recipe-box.', 'recipe-press'), $post->post_title));
                    $usermeta[$post_id] = array(
                         'category' => 'new-addition',
                         'added' => time()
                    );

                    update_user_meta($current_user->ID, '_recipe_press_my_box', $usermeta);
               }

               echo json_encode(array('status' => $status, 'message' => $message, 'link' => get_recipe_box_link(array('text-only' => true), $post_id)));
          } else {
               echo json_encode(array('status' => 'error', 'message' => 'No Nonce Match'));
          }

          die();
     }

     /**
      * Remove a recipe from the box
      * 
      * @global object $current_user
      */
     function recipe_press_remove_from_box() {
          global $current_user;
          get_currentuserinfo();

          $post_id = $_REQUEST['id'];
          $post = get_post($post_id);

          if ( wp_verify_nonce($_REQUEST['nonce'], $post->post_title) ) {
               $usermeta = (array) get_user_meta($current_user->ID, '_recipe_press_my_box', true);
               unset($usermeta[$_REQUEST['id']]);
               update_user_meta($current_user->ID, '_recipe_press_my_box', $usermeta);
               echo json_encode(array('status' => 'success', 'message' => __('Recipe successfully removed from ', 'recipe-press') . $this->options['recipe-box-title']));
          } else {
               echo json_encode(array('status' => 'error', 'message' => 'No Nonce Match'));
          }

          die();
     }

     /**
      * Save the notes for a recipe in the recipe box.
      *
      * @global object $current_user
      */
     function save_recipe_box_notes() {
          global $current_user;
          get_currentuserinfo();

          $usermeta = (array) get_user_meta($current_user->ID, '_recipe_press_my_box', true);
          $usermeta[$_REQUEST['id']]['notes'] = $_REQUEST['value'];
          update_user_meta($current_user->ID, '_recipe_press_my_box', $usermeta);

          die();
     }

     /**
      * Initialize the shortcodes.
      */
     static function initialize() {
          $instance = self::get_instance();
     }

     /**
      * Returns singleton instance of object
      *
      * @return instance
      */
     static function get_instance() {
          if ( is_null(self::$instance) ) {
               self::$instance = new recipe_press_recipe_box;
          }
          return self::$instance;
     }

}