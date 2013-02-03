<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * recipe-box-widget.php - sidebar widget for recipes box.
 *
 * @package RecipeBox
 * @subpackage widgets
 * @author George
 * @copyright 2012
 * @access public
 * @since 1.0
 */
class rp_Widget_Recipes_Box extends WP_Widget {

     var $options = array();

     /**
      * Constructor
      */
     function rp_Widget_Recipes_Box() {
          global $RECIPEPRESSOBJ;

          load_plugin_textdomain('recipe-press', false, dirname(dirname(plugin_basename(__FILE__))) . '/lang');

          /* translators: The description of the Recpipe List widget on the Appearance->Widgets page. */
          $widget_ops = array('description' => __('List recipes-box on your sidebar. By George.', 'recipe-press'));
          /* translators: The title for the Recipe List widget. */
          $this->WP_Widget('recipe_box_widget', __('RecipePlus &raquo; Box', 'recipe-press'), $widget_ops);

          $this->pluginPath = WP_CONTENT_DIR . '/plugins/' . plugin_basename(dirname(__FILE__)) . '/';
          $this->options = $RECIPEPRESSOBJ->loadSettings();
     }

     function defaults($args = array()) {
          $defaults = array(
               'title' => '',
               'items' => $this->options['widget-items'],
               'type' => $this->options['widget-type'],
               'sort_order' => $this->options['widget-sort'],
               'category' => false,
               'show-icon' => isset($args['items']) ? isset($args['show-icon']) : isset($this->options['widget-show-icon']),
               'icon-size' => $this->options['widget-icon-size'],
               'li-class' => 'recipe-box-class',
          );

          return wp_parse_args($args, $defaults);
     }

     /**
      * Widget code
      */
     function widget($args, $instance) {
          if (!is_user_logged_in()) return;
          
          global $current_user, $recipeData;
   
         if ( isset($instance['error']) && $instance['error'] ) {
               return;
          }

          extract($args, EXTR_SKIP);
          $instance = $this->defaults($instance);

          if ( $instance['items'] < 1 or $instance['items'] > 20 ) {
               $instance['items'] = $this->options['widget-items'];
          }
          
          echo $before_widget;
          
          if ( $instance['title'] ) {
               //echo $before_title . $instance['title'] . $after_title;
               echo $before_title . $instance['title'] . $after_title;
     
           }

          echo '<ul class="recipe-widget-box">';

          $recipeData = get_recipe_box_entries($current_user->ID);
          ob_start();
          include ('recipe-box-show.php');
          $output = ob_get_contents();
          ob_end_clean();
          
          echo $output;
          
          echo '</ul>';
          echo $after_widget;

     }
}
    
add_action('widgets_init', create_function('', 'return register_widget("rp_Widget_Recipes_Box");'));