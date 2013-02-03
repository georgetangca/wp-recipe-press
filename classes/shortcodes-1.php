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
class RecipePressShortCodes extends recipePressCore {

     static $instance;

     /**
      * Initialize the class.
      */
     function RecipePressShortCodes() {
          parent::recipePressCore();

          /* Add Shortcode */
          add_shortcode('recipe-form', array(&$this, 'recipe_form_shortcode'));
          add_shortcode('recipe-list', array(&$this, 'recipe_list_shortcode'));
          add_shortcode('recipe-show', array(&$this, 'recipe_show_shortcode'));
          add_shortcode('recipe-tax', array(&$this, 'recipe_tax_shortcode'));
          add_shortcode('recipe-box', array(&$this, 'recipe_box_shortcode'));
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
               self::$instance = new RecipePressShortCodes;
          }
          return self::$instance;
     }

     /**
      * Add the front end form to a page with this shortcode.
      *
      * @param <array> $atts
      * @return <string>
      */
     function recipe_form_shortcode($atts) {
          $defaults = array(
               'template' => 'recipe-share',
          );

          $atts = wp_parse_args($atts, $defaults);

          ob_start();
          require($this->get_template($atts['template']));
          $output = ob_get_contents();
          ob_end_clean();

          return $output;
     }

     /**
      * Display a list of recipes on a page using a shortcode.
      *
      * @param <array> $atts
      * @return <string>
      */
     function recipe_list_shortcode($atts) {
          global $recipes, $post, $old_post;
          $old_post = $post;

          $defaults = array(
               'template' => 'shortcode-list',
               'posts_per_page' => $this->options['recipe-count'],
               'featured' => false,
               'orderby' => 'title',
               'order' => 'ASC',
               'author' => NULL,
               'author_name' => NULL,
               'recipe-category' => NULL,
               'recipe-cuisine' => NULL,
               'tax_relation' => 'AND',
          );

          $atts = wp_parse_args($atts, $defaults);

          /* Backwards Compatibility */
          if ( isset($atts['recipe_category']) and !isset($atts['recipe-category']) ) {
               $atts['recipe-category'] = $atts['recipe_category'];
          }

          if ( isset($atts['recipe_cuisine']) and !isset($atts['recipe-cuisine']) ) {
               $atts['recipe-cuisine'] = $atts['recipe_cuisine'];
          }
          /* Arguments array for searching recipes. */
          $args = array(
               'post_type' => 'recipe',
               'posts_per_page' => $atts['posts_per_page'],
               'showposts' => $atts['posts_per_page'],
               'paged' => get_query_var('page'),
               'orderby' => $atts['orderby'],
               'order' => $atts['order'],
               'author' => $atts['author'],
               'author_name' => $atts['author_name'],
               'offset' => get_query_var('page') * $atts['posts_per_page'] - $atts['posts_per_page']
          );

          if ( version_compare(get_bloginfo('version'), '3.1', '>=') ) {
               if ( isset($atts['recipe-category']) ) {
                    $atts['tax_query']['relation'] = 'OR';

                    $atts['tax_query'][] = array(
                         'taxonomy' => 'recipe-category',
                         'field' => 'slug',
                         'terms' => explode(',', $attrs['recipe-category'])
                    );
               }

               if ( isset($atts['recipe-cuisine']) ) {
                    $atts['tax_query']['relation'] = 'OR';

                    $atts['tax_query'][] = array(
                         'taxonomy' => 'recipe-cuisine',
                         'field' => 'slug',
                         'terms' => explode(',', $attrs['recipe-cuisine'])
                    );
               }
          } else {
               if ( isset($atts['recipe-category']) ) {
                    $args['recipe-category'] = $atts['recipe-category'];
               }

               if ( isset($atts['recipe-cuisine']) ) {
                    $args['recipe-cuisine'] = $atts['recipe-cuisine'];
               }
          }

          switch ($atts['featured']) {
               case 'only':
                    $args['meta_key'] = '_recipe_featured_value';
                    $args['meta_value'] = '1';
                    break;
               case 'hide':
                    $args['meta_key'] = '_recipe_featured_value';
                    $args['meta_value'] = '1';
                    $args['meta_compare'] = '!=';
                    break;
          }

          /* Get posts */
          $recipes = new WP_Query($args);

          ob_start();
          require($this->get_template($atts['template']));
          $output = ob_get_contents();
          ob_end_clean();

          wp_reset_query();

          $post = $old_post;
          return $output;
     }

     function recipe_show_shortcode($atts) {
          global $wpdb, $post, $RECIPEPRESSOBJ;
          $tmp_post = $post;
          $RECIPEPRESSOBJ->in_shortcode = true;

          $defaults = array(
               'recipe' => NULL,
               'template' => 'recipe-single',
          );

          $atts = wp_parse_args($atts, $defaults);
          if ( !$atts['recipe'] ) {
               return __('Sorry, no recipe found', 'recipe-press');
          }

          $post = get_post($wpdb->get_var('select `id` from `' . $wpdb->prefix . 'posts` where `post_name` = "' . $atts['recipe'] . '" and `post_status` = "publish" limit 1'));
          setup_postdata($post);

          ob_start();
          include ($this->get_template($atts['template']));
          $output = ob_get_contents();
          ob_end_clean();

          $post = $tmp_post;
          return $output;
     }

     function recipe_tax_shortcode($atts) {
          global $wpdb, $post, $pagination, $RECIPEPRESSOBJ;
          $tmp_post = $post;
          $this->in_shortcode = true;
          $page = get_query_var('page');

          $defaults = array(
               'taxonomy' => 'recipe-category',
               'template' => 'recipe-taxonomy',
               'number' => 0,
               'offset' => 0,
               'orderby' => 'name',
               'order' => 'asc',
               'hide_empty' => true,
               'fields' => 'all',
               'slug' => false,
               'hierarchical' => true,
               'name__like' => '',
               'pad_counts' => false,
               'child_of' => NULL,
               'parent' => 0,
          );

          $atts = wp_parse_args($atts, $defaults);
          $taxonomy = $atts['taxonomy'];
          $atts['include'] = get_published_categories($taxonomy);

          /* Count all terms */
          $atts['fields'] = 'ids';
          $all_terms = get_terms($atts['taxonomy'], $atts);

          if ( $taxonomy == 'recipe-ingredient' ) {
               $pagination = array(
                    'total' => count($all_terms),
                    'pages' => ceil(count($all_terms) / $this->options['ingredients-per-page']),
                    'current-page' => max($page, 1),
                    'taxonomy' => __('Ingredients', 'recipe-press'),
                    'url' => get_permalink($this->options['ingredient-page']),
                    'per-page' => $this->options['ingredients-per-page']
               );
          } else {
               $this->options['taxonomies'][$taxonomy] = $this->taxDefaults($this->options['taxonomies'][$taxonomy]);

               $pagination = array(
                    'total' => count($all_terms),
                    'pages' => ceil(count($all_terms) / $this->options['taxonomies'][$taxonomy]['per-page']),
                    'current-page' => max($page, 1),
                    'taxonomy' => $this->options['taxonomies'][$taxonomy]['plural'],
                    'url' => get_permalink($this->options['taxonomies'][$taxonomy]['page']),
                    'per-page' => $this->options['taxonomies'][$taxonomy]['per-page']
               );
          }

          unset($atts['fields']);

          $atts['number'] = $pagination['per-page'];

          if ( $page > 1 ) {
               $atts['offset'] = $page * $atts['number'] - $atts['number'];
          } else {
               $atts['offset'] = 0;
          }



          $terms = get_terms($atts['taxonomy'], $atts);

          ob_start();
          include ($this->get_template($atts['template']));
          $output = ob_get_contents();
          ob_end_clean();

          $post = $tmp_post;
          return $output;
     }

     function recipe_box_shortcode($atts) {
          global $post, $current_user, $recipeData;
          get_currentuserinfo();
          $old_post = $post;
          $defaults = array(
               'template' => 'main',
               'page' => get_query_var('box-page'),
          );

          $atts = wp_parse_args($atts, $defaults);

          switch ($atts['page']) {
               default:
                    $recipeData = get_recipe_box_entries($current_user->ID);
                    $atts['template'] = 'recipe-box/' . $atts['template'];
          }

          ob_start();
          include ($this->get_template($atts['template']));
          $output = ob_get_contents();
          ob_end_clean();

          $post = $old_post;
          return $output;
     }

}