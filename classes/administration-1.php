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
class RecipePressAdmin extends recipePressCore {

     static $instance;

     /**
      * Initialize the class.
      */
     function RecipePressAdmin() {
          parent::recipePressCore();

          /* Administration Actions */
          add_action('admin_menu', array(&$this, 'admin_menu'));
          add_action('admin_init', array(&$this, 'admin_init'));
          add_action('admin_print_styles', array(&$this, 'admin_print_styles'));
          add_action('admin_print_scripts', array(&$this, 'admin_print_scripts'));
          add_action('save_post', array(&$this, 'save_recipe'));
          add_action('update_option_' . $this->optionsName, array(&$this, 'update_option'), 10, 2);
          add_action('right_now_content_table_end', array(&$this, 'right_now_content_table_end'));
          add_action('manage_pages_custom_column', array(&$this, 'manage_pages_custom_column'));

          /* Administration Filters */
          add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);
          add_filter('manage_edit-recipe_columns', array(&$this, 'manage_recipe_edit_columns'));
     }

     /**
      * Initialize the administration area.
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
               self::$instance = new RecipePressAdmin;
          }
          return self::$instance;
     }

     /**
      * Add the number of recipes to the Right Now on the Dasboard.
      */
     function right_now_content_table_end() {
          if ( !post_type_exists('recipe') ) {
               return false;
          }

          $num_posts = wp_count_posts('recipe');
          $num = number_format_i18n($num_posts->publish);
          $text = _n('Recipe', 'Recipes', intval($num_posts->publish), 'recipe-press');
          if ( current_user_can('edit_posts') ) {
               $num = "<a href='edit.php?post_type=recipe'>$num</a>";
               $text = "<a href='edit.php?post_type=recipe'>$text</a>";
          }
          echo '<td class="first b b-recipes">' . $num . '</td>';
          echo '<td class="t recipes">' . $text . '</td>';

          echo '</tr>';

          if ( $num_posts->pending > 0 ) {
               $num = number_format_i18n($num_posts->pending);
               $text = _n('Recipe Pending', 'Recipes Pending', intval($num_posts->pending), 'recipe-press');
               if ( current_user_can('edit_posts') ) {
                    $num = "<a href='edit.php?post_status=pending&post_type=recipe'>$num</a>";
                    $text = "<a href='edit.php?post_status=pending&post_type=recipe'>$text</a>";
               }
               echo '<td class="first b b-recipes">' . $num . '</td>';
               echo '<td class="t recipes">' . $text . '</td>';

               echo '</tr>';
          }
     }

     /**
      * Add extra columns to the edit recipes page.
      *
      * @param array $columns  Current columns.
      * @return array
      */
     function manage_recipe_edit_columns($columns) {
          $columns = array(
               'cb' => '<input type="checkbox" />',
               'thumbnail' => __('Image', 'recipe-press'),
               'title' => __('Recipe Title', 'recipe-press'),
               'intro' => __('Introduction', 'recipe-press')
          );

          foreach ( $this->options['taxonomies'] as $tax => $settings ) {
               $settings = $this->taxDefaults($settings);
               if ( $settings['active'] and taxonomy_exists($tax) ) {
                    $columns[$tax] = $settings['plural'];
               }
          }

          $columns['ingredients'] = __('Ingredients', 'recipe-press');

          if ( $this->options['use-featured'] ) {
               $columns['featured'] = __('Featured', 'recipe-press');
          }

          $columns ['author'] = __('Author', 'recipe-press');

          if ( $this->options['use-comments'] ) {
               $columns['comments'] = '<img src="' . get_option('siteurl') . '/wp-admin/images/comment-grey-bubble.png" alt="Comments">';
          }

          $columns['date'] = __('Date', 'recipe-press');

          return $columns;
     }

     /**
      * Display the content of the custom columns.
      *
      * @global object $post
      * @param string $column      Name of the column
      * @return string
      */
     function manage_pages_custom_column($column) {
          global $post;

          if ( $post->post_type != 'recipe' ) {
               return;
          }

          switch ($column) {
               case 'thumbnail':
                    if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                         the_post_thumbnail('recipe-press-thumb');
                    }
                    break;
               case 'intro':
                    echo rp_inflector::trim_excerpt($post->post_excerpt, 25);
                    break;
               case 'featured':
                    if ( get_post_meta($post->ID, '_recipe_featured_value', true) ) {
                         _e('Yes', 'recipe-press');
                    } else {
                         _e('No', 'recipe-press');
                    }
                    break;
               case 'ingredients':
                    echo get_the_term_list($post->ID, 'recipe-ingredient', '', ', ', '');
                    break;
          }

          /* Display taxonomies if taxonomy is active */
          if ( isset($this->options['taxonomies'][$column]) and taxonomy_exists($column) ) {
               echo get_the_term_list($post->ID, $column, '', ', ', '');
          }
     }

     /**
      * Admin Init Actino
      */
     function admin_init() {
          register_setting($this->optionsName, $this->optionsName);
          wp_register_style('recipePressAdminCSS', $this->pluginURL . 'includes/recipe-press-admin.css');
          wp_register_script('recipePressAdminJS', $this->pluginURL . 'js/recipe-press-admin.js');
          wp_register_script('recipePressOverlibJS', $this->pluginURL . 'js/overlib/overlib.js');
     }

     /**
      * Add the admin page for the settings panel.
      *
      * @global string $wp_version
      */
     function admin_menu() {
          global $wp_version, $wpdb;

          $pages = array();

          /* Set up the import page */
          $pages[] = add_submenu_page('edit.php?post_type=recipe', __('RecipePress Importer', 'recipe-press'), __('Import', 'recipe-press'), 'edit_posts', 'recipe-press-import', array(&$this, 'import'));

          /* Set up the settings page */
          $pages[] = add_submenu_page('edit.php?post_type=recipe', __('RecipePress Settings', 'recipe-press'), __('Settings', 'recipe-press'), 'edit_posts', $this->menuName, array(&$this, 'settings'));

          /* Set up the credit pages. */
          $pages[] = add_submenu_page('edit.php?post_type=recipe', __('RecipePress Credits', 'recipe-press'), __('Credits', 'recipe-press'), 'edit_posts', 'recipe-press-contributors', array(&$this, 'credits_page'));

          $tableName = $wpdb->prefix . 'rp_recipes';
          if ( $wpdb->get_var("SHOW TABLES LIKE '{$tableName}'") == $tableName ) {
               $pages[] = add_submenu_page('edit.php?post_type=recipe', 'Convert', 'Convert Recipes', 'edit_posts', 'recipe-press-convert', array(&$this, 'convert'));
          }

          foreach ( $pages as $page ) {
               add_action('admin_print_styles-' . $page, array(&$this, 'admin_styles'));
               add_action('admin_print_scripts-' . $page, array(&$this, 'admin_scripts'));
          }
     }

     /**
      * Settings management panel.
      */
     function settings() {
          include($this->pluginPath . 'includes/settings.php');
     }

     function admin_print_styles() {
          global $post;

          if ( is_object($post) and $post->post_type == 'recipe' ) {
               $this->admin_styles();
          }
     }

     function admin_print_scripts() {
          global $post;

          if ( is_object($post) and $post->post_type == 'recipe' ) {
               $this->admin_scripts();
          }
     }

     function admin_styles() {
          wp_enqueue_style('recipePressAdminCSS');
     }

     function admin_scripts() {
          wp_enqueue_script('recipePressAdminJS');
          wp_enqueue_script('recipePressOverlibJS');
          wp_enqueue_script('jquery.autocomplete');
     }

     /**
      * Method to handle special features on the settings pages.
      * 
      * @param array $old
      * @param array $new 
      */
     function update_option($old, $new) {
          remove_action('update_option_' . $this->optionsName, array(&$this, 'update_option'));

          if ( isset($_REQUEST['confirm-reset-options']) ) {
               delete_option($this->optionsName);
               update_option($this->optionsName, array('version' => $this->version));

               wp_redirect(admin_url('admin.php?page=recipe-press&tab=' . $_POST['active_tab'] . '&tax=' . $_POST['active_tax'] . '&reset=true'));
               exit();
          }

          /* Delete Recipes if checked */
          if ( isset($_POST['remove-pending-recipes']) or isset($_POST['remove-all-recipes']) ) {
               $args = array(
                    'post_type' => 'recipe',
                    'post_status' => 'pending',
                    'numberposts' => -1
               );

               if ( isset($_POST['remove-all-recipes']) ) {
                    $args['post_status'] = 'all';
               }

               $posts = get_posts($args);
               foreach ( $posts as $post ) {
                    wp_trash_post($post->ID);
               }
          }

          /* Remove taxonomy data if checked */

          $builtins = array('recipe-size', 'recipe-serving', 'recipe-ingredient');
          $taxonomies = array_keys($this->options['taxonomies']);

          foreach ( array_merge($taxonomies, $builtins) as $taxonomy ) {
               if ( isset($_POST['remove-empty-' . $taxonomy]) or isset($_POST['remove-all-' . $taxonomy]) ) {
                    $args = array(
                         'hide_empty' => false,
                         'pad_counts' => true
                    );

                    $terms = get_terms($taxonomy, $args);

                    foreach ( $terms as $term ) {
                         if ( isset($_POST['remove-all-' . $taxonomy]) or $term->count == 0 ) {
                              wp_delete_term($term->term_id, $taxonomy);
                         }
                    }
               }
          }
          unset($taxonomies);

          /* Remove taxonomies marked as delete */
          foreach ( $new['taxonomies'] as $tax => $settings ) {
               if ( !isset($settings['delete']) ) {
                    $taxonomies[$tax] = $settings;
               }
          }

          /* Create new taxonomy if entered */
          if ( isset($_POST['new_taxonomy']) and $_POST['new_taxonomy'] != '' ) {
               $name = rp_inflector::humanize($_POST['new_taxonomy']);
               $tax_args = array(
                    'slug' => $_POST['new-taxonomy'],
                    'singular' => ucwords($name),
                    'plural' => ucwords(rp_inflector::plural($name, 2)),
                    'active' => true
               );
               $taxonomies[$_POST['new_taxonomy']] = $this->taxDefaults($tax_args);
               $_POST['active_tax'] = $_POST['new_taxonomy'];
          }

          $new['taxonomies'] = $taxonomies;

          update_option($this->optionsName, $new);

          add_action('update_option_' . $this->optionsName, array(&$this, 'update_option'), 10);

          wp_redirect(admin_url('admin.php?page=recipe-press&tab=' . $_POST['active_tab'] . '&tax=' . $_POST['active_tax'] . '&updated=true'));
          exit();
     }

     /**
      * Save the meta boxes for a recipe.
      *
      * @global <object> $postoptions
      * @param <integer> $post_id
      * @return <integer>
      */
     function save_recipe($post_id) {
          global $post;

          if ( is_object($post) and $post->post_type == 'revision' ) {
               return;
          }

          do_action('rp_before_save');

          /* Save details */
          if ( isset($_POST['recipe_details']) and isset($_POST['details_noncename']) and wp_verify_nonce($_POST['details_noncename'], 'recipe_press_details') ) {
               $details = $_POST['recipe_details'];
               $details['recipe_ready_time'] = $this->readyTime();
               $details['recipe_ready_time_raw'] = $this->readyTime(NULL, NULL, false);

               foreach ( $details as $key => $value ) {
                    $key = '_' . $key . '_value';
                    if ( get_post_meta($post_id, $key) == "" ) {
                         add_post_meta($post_id, $key, $value, true);
                    } elseif ( $value != get_post_meta($post_id, $key . '_value', true) ) {
                         update_post_meta($post_id, $key, $value);
                    } elseif ( $value == "" ) {
                         delete_post_meta($post_id, $key, get_post_meta($post_id, $key, true));
                    }
               }
          }

          /* Turn off featured if not checked */
          if ( !isset($_POST['recipe_details']['recipe_featured']) ) {
               update_post_meta($post_id, '_recipe_featured_value', 0);
          }

          /* Turn off ingredient link if not checked */
          if ( !isset($_POST['recipe_details']['recipe_link_ingredients']) ) {
               update_post_meta($post_id, '_recipe_link_ingredients_value', 0);
          }

          /* Add nutritional Value */
          if ( isset($_POST['nutrient_details']) and isset($_POST['nutrients_noncename']) and wp_verify_nonce($_POST['nutrients_noncename'], 'recipe_press_nutrients') ) {
               $nutrients = serialize($_POST['nutrient_details']);

               if ( get_post_meta($post_id, '_recipe_nutrients_value') == "" ) {
                    add_post_meta($post_id, '_recipe_nutrients_value', $nutrients, false);
               } else {
                    update_post_meta($post_id, '_recipe_nutrients_value', $nutrients);
               }
          }

          if ( isset($_POST['ingredients']) and isset($_POST['ingredients_noncename']) and wp_verify_nonce($_POST['ingredients_noncename'], 'recipe_press_ingredients') ) {
               $this->save_ingredients($post_id, $_POST['ingredients']);
          }

          do_action('rp_after_save');

          return $post_id;
     }

     /**
      * Add a configuration link to the plugins list.
      *
      * @staticvar <object> $this_plugin
      * @param <array> $links
      * @param <array> $file
      * @return <array>
      */
     function plugin_action_links($links, $file) {
          static $this_plugin;

          if ( !$this_plugin ) {
               $this_plugin = plugin_basename(dirname(dirname(__FILE__))) . '/recipe-press.php';
          }

          if ( $file == $this_plugin ) {
               $settings_link = '<a href="' . get_admin_url() . 'edit.php?post_type=recipe&page=' . $this->menuName . '">' . __('Settings', 'recip-press') . '</a>';
               array_unshift($links, $settings_link);
          }

          return $links;
     }

     /**
      * Credits Page Handler
      */
     function credits_page() {
          include ($this->pluginPath . 'classes/credits.php');
     }

     /**
      * Credits Page Handler
      */
     function import() {
          include ($this->pluginPath . 'classes/recipe-import.php');
     }

     function checked($data, $value) {
          if (
                  (is_array($data) and in_array($value, $data) )
                  or $data == $value
          ) {
               echo 'checked="checked"';
          }
     }

}