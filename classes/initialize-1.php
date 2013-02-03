<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * initialize.php - Initialize the post types and taxonomies
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
class recipePress_Init extends recipePressCore {

     static $instance;

     /**
      * Initialize the plugin
      */
     function recipePress_Init() {
          global $wpdb;
          parent::recipePressCore();

          $inglist = $wpdb->get_results('select * from `' . $wpdb->prefix . 'postmeta` where `meta_key` = "_recipe_ingredient_list" order by `meta_value`');
          if ( count($inglist) >= 1 ) {
               add_action('admin_menu', array(&$this, 'add_upgrade_menu_option'));
               add_action('admin_notices', array(&$this, 'upgrade_warning'));
          } else {
               if ( $this->options['use-taxonomies'] ) {
                    add_action('init', array($this, 'setup_taxonomies'));
               }
               add_action('init', array(&$this, 'setup_sizes'));
               add_action('init', array(&$this, 'setup_serving_sizes'));
               add_action('init', array(&$this, 'setup_ingredients'));
               add_action('init', array(&$this, 'create_post_type'));
               add_action('init', array(&$this, 'setup_my_box'));

               /* WordPress Filters */
               add_filter('index_template', array($this, 'index_template'), 10, 1);
               add_filter('home_template', array($this, 'index_template'), 10, 1);
               add_filter('archive_template', array($this, 'archive_template'), 10, 1);

               /* Use built in categories and tags. */
               if ( $this->options['use-post-categories'] ) {
                    register_taxonomy_for_object_type('post_categories', 'recipe');
               }

               if ( $this->options['use-post-tags'] ) {
                    register_taxonomy_for_object_type('post_tag', 'recipe');
               }
          }
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
               self::$instance = new recipePress_Init;
          }
          return self::$instance;
     }

     /**
      *  Puts a warning on the admin side that an update is needed.
      */
     function upgrade_warning() {
          echo "<div id='recipe-press-warning' class='updated fade'><p><strong>" . __('RecipePress requires an update.') . "</strong> " . sprintf(__('You must <a href="%1$s">run the update tool</a> for it to work.'), admin_url() . "plugins.php?page=recipe-press-update") . "</p></div>";
     }

     /**
      * Adds an option to the plugins menu to perform the update to version 2.0
      */
     function add_upgrade_menu_option() {
          add_submenu_page('plugins.php', __('RecipePress Upgrade'), __('RecipePress Upgrade'), 'manage_options', 'recipe-press-update', array(&$this, 'update_function'));
     }

     /**
      * Updates older version to 2.0
      *
      * @global object $wpdb
      */
     function update_function() {
          global $wpdb;
?>
          <div class="wrap">
               <div class="icon32" id="icon-recipe-press"><br/></div>
               <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Update to version 2.0', 'recipe-press'); ?> </h2>
               <ul>
          <?php
          /* Register the post type */
          $this->create_post_type();

          /* Create default ingredient sizes */
          echo '<li>' . __('Creating new taxonomy for ingredient sizes and populating data.', 'recipe-press') . '</li>';
          $this->setup_sizes();
          $terms = get_terms('recipe-size', array('fields' => 'names', 'hide_empty' => false));

          /* If no sizes registered, add default sizes */
          if ( count($terms) == 0 ) {
               $sizes = array_unique(array_merge(
                                       $this->options['standard']['ingredient-sizes'],
                                       $this->options['metric']['ingredient-sizes']));

               foreach ( $sizes as $size ) {
                    if ( !in_array($size, $terms) ) {
                         wp_insert_term($size, 'recipe-size');
                    }
               }
          }

          /* Convert all recipes */
          $inglist = $wpdb->get_results('select * from `' . $wpdb->prefix . 'postmeta` where `meta_key` = "_recipe_ingredient_value" order by `post_id`');

          foreach ( $inglist as $ingredient ) {
               $oldDetails = $details = unserialize($ingredient->meta_value);
               $term = get_term_by('name', $details['size'], 'recipe-size');
               if ( is_object($term) ) {
                    $details['size'] = $term->term_id;
                    update_post_meta($ingredient->post_id, '_recipe_ingredient_value', $details, $oldDetails);
               }
          }

          /* Create default serving sizes. */
          echo '<li>' . __('Creating new taxonomy for serving sizes and populating data.', 'recipe-press') . '</li>';
          $this->setup_serving_sizes();
          $terms = get_terms('recipe-serving', array('fields' => 'names', 'hide_empty' => false));

          /* If no sizes registered, add default sizes */
          if ( count($terms) == 0 ) {
               $sizes = array_unique(array_merge(
                                       $this->options['standard']['serving-sizes'],
                                       $this->options['metric']['serving-sizes']));

               foreach ( $sizes as $size ) {
                    if ( !in_array($size, $terms) ) {
                         wp_insert_term($size, 'recipe-serving');
                    }
               }
          }

          /* Convert all serving sizes */
          $inglist = $wpdb->get_results('select * from `' . $wpdb->prefix . 'postmeta` where `meta_key` = "_recipe_serving_size_value" order by `post_id`', ARRAY_A);

          foreach ( $inglist as $ingredient ) {
               $term = get_term_by('name', $ingredient['meta_value'], 'recipe-serving');
               if ( is_object($term) ) {
                    $ingredient['meta_value'] = $term->term_id;
                    update_post_meta($ingredient['post_id'], '_recipe_serving_size_value', $details, $oldDetails);
               }
          }

          /* Update ingredients */
          echo '<li>' . __('Creating new taxonomy for ingredients and populating data.', 'recipe-press') . '</li>';
          $this->setup_ingredients();
          $ingredients = get_terms('recipe-ingredient', array('fields' => 'names', 'hide_empty' => false));

          if ( count($ingredients) == 0 ) {

               /* Create the ingredients */
               $inglist = $wpdb->get_results('select * from `' . $wpdb->prefix . 'postmeta` where `meta_key` = "_recipe_ingredient_list" order by `meta_value`');
               foreach ( $inglist as $ingredient ) {
                    $recipes[$ingredient->post_id]['ingredients'][] = $ingredient->meta_value;
               }

               foreach ( $recipes as $id => $recipe ) {
                    wp_set_object_terms($id, $recipe['ingredients'], 'recipe-ingredient');
                    delete_post_meta($id, '_recipe_ingredient_list');
               }
          }

          /* Convert the recipes */
          $inglist = $wpdb->get_results('select * from `' . $wpdb->prefix . 'postmeta` where `meta_key` = "_recipe_ingredient_value" order by `post_id`');

          foreach ( $inglist as $ingredient ) {
               $oldDetails = $details = unserialize($ingredient->meta_value);
               $term = get_term_by('name', $details['item'], 'recipe-ingredient');
               if ( is_object($term) ) {
                    $details['item'] = $term->term_id;
                    update_post_meta($ingredient->post_id, '_recipe_ingredient_value', $details, $oldDetails);
                    delete_post_meta($ingredient->post_id, '_recipe_ingredient_list');
               }
          }

          /* Make sure that all of the old ingredient data is removed. */
          $insurance = $wpdb->get_results('delete from `' . $wpdb->prefix . 'postmeta` where `meta_key` = "_recipe_ingredient_list" order by `meta_value`');

          /* Add version to settings */
          remove_action('update_option_' . $this->optionsName, array(&$this, 'update_option'), 10, 2);
          $this->options['version'] = $this->version;
          update_option($this->optionsName, $this->options);
          ?>
          <li><?php _e('Done Updating.', 'recipe-press'); ?></li>
     </ul>
</div>
<?php
     }

     /**
      * Create the post type.
      *
      * @global object $wp_rewrite
      */
     function create_post_type() {
          global $wp_version;

          $page = get_page($this->options['display-page']);
          $labels = array(
               'name' => $this->options['plural-name'],
               'singular_name' => $this->options['singular-name'],
               'add_new' => __('Add New', 'recipe-press'),
               'add_new_item' => sprintf(__('Add New %1$s', 'recipe-press'), $this->options['singular-name']),
               'edit_item' => sprintf(__('Edit %1$s', 'recipe-press'), $this->options['singular-name']),
               'edit' => __('Edit', 'recipe-press'),
               'new_item' => sprintf(__('New %1$s', 'recipe-press'), $this->options['singular-name']),
               'view_item' => sprintf(__('View %1$s', 'recipe-press'), $this->options['singular-name']),
               'search_items' => sprintf(__('Search %1$s', 'recipe-press'), $this->options['singular-name']),
               'not_found' => sprintf(__('No %1$s found', 'recipe-press'), $this->options['plural-name']),
               'not_found_in_trash' => sprintf(__('No %1$s found in Trash', 'recipe-press'), $this->options['plural-name']),
               'view' => sprintf(__('View %1$s', 'recipe-press'), $this->options['singular-name']),
               'parent_item' => sprintf(__('Parent %1$s', 'recipe-press'), $this->options['singular-name']),
               'parent_item_colon' => sprintf(__('Parent %1$s:', 'recipe-press'), $this->options['singular-name']),
          );
          $args = array(
               'labels' => $labels,
               'public' => true,
               'publicly_queryable' => true,
               'show_ui' => true,
               'query_var' => true,
               'capability_type' => 'page',
               'hierarchical' => true,
               'menu_position' => (int) $this->options['menu-position'],
               'menu_icon' => $this->options['menu-icon'],
               'supports' => array('title', 'editor', 'author', 'excerpt', 'page-attributes'),
               'register_meta_box_cb' => array(&$this, 'init_metaboxes'),
          );

          if ( $this->options['use-custom-fields'] ) {
               $args['supports'][] = 'custom-fields';
          }

          if ( $this->options['use-thumbnails'] ) {
               $args['supports'][] = 'thumbnail';
          }

          if ( $this->options['use-comments'] ) {
               $args['supports'][] = 'comments';
          }

          if ( $this->options['use-trackbacks'] ) {
               $args['supports'][] = 'trackbacks';
          }

          if ( $this->options['use-revisions'] ) {
               $args['supports'][] = 'revisions';
          }

          if ( $this->options['use-post-tags'] ) {
               $args['taxonomies'][] = 'post_tag';
          }

          if ( $this->options['use-post-categories'] ) {
               $args['taxonomies'][] = 'category';
          }

          if ( version_compare($wp_version, '3.1', '>=') and !$this->options['use-plugin-permalinks'] ) {
               $args['rewrite'] = true;
               $args['has_archive'] = $this->options['index-slug'];
          } else {
               $args['rewrite'] = false;
               $args['has_archive'] = false;

               /* Flush the rewrite rules */
               global $wp_rewrite;
               $this->recipe_rewrite_rules(array('identifier' => $this->options['identifier'], 'structure' => $this->options['permalink'], 'type' => 'recipe'));

               if ( isset($this->options['use-form']) and $this->options['use-form'] ) {
                    $this->recipe_rewrite_rules(array('identifier' => $this->options['form-identifier'], 'structure' => $this->options['form-permalink'], 'type' => 'form'));
               }

               $wp_rewrite->flush_rules();

               /* Add filters to handle custom pages */
               add_filter('post_type_link', array($this, 'post_link'), 10, 3);
          }

          register_post_type('recipe', $args);
     }

     /**
      * Handle index pages for the recipe post type.
      *
      * @global object $post
      * @param string $template
      * @return string
      */
     public function index_template($template) {
          global $post, $wp_query, $taxonomy, $terms, $tax, $pagination, $recipeData, $current_user;

          /* Handle Recipe Box Page */
          if ( $recipeBox = get_query_var('recipe-box') ) {
               $page = get_query_var('box-page');

               $replacement_template = get_query_template('recipe-box');
               if ( file_exists($replacement_template) ) {
                    add_filter('wp_title', array(&$this, 'recipe_box_title'));
                    return $replacement_template;
               } else {
                    if ( $this->options['recipe-box-page'] and get_page($this->options['recipe-box-page']) ) {
                         wp_redirect(get_permalink($this->options['recipe-box-page']));
                         exit();
                    } else {
                         wp_die(__('Warning: The site administrator has not set a page to load the recipe box on.', 'recipe-press'));
                    }
               }
          }

          /* Handle Taxonomy Page */
          if ( $taxonomy = get_query_var('recipe-taxonomy') ) {
               $page = get_query_var('page');

               $replacement_template = get_query_template('taxonomy-recipe');
               if ( file_exists($replacement_template) ) {
                    $atts = array(
                         'taxonomy' => $taxonomy,
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
                         'include' => get_published_categories($taxonomy)
                    );

                    $tax = get_taxonomy($taxonomy);

                    /* Count all terms */
                    $atts['fields'] = 'ids';
                    $all_terms = get_terms($atts['taxonomy'], $atts);

                    if ( $taxonomy == 'recipe-ingredient' ) {
                         $pagination = array(
                              'total' => count($all_terms),
                              'pages' => ceil(count($all_terms) / $this->options['ingredients-per-page']),
                              'current-page' => max($page, 1),
                              'taxonomy' => __('Ingredients', 'recipe-press'),
                              'url' => get_option('home') . '/' . $this->options['ingredient-slug'],
                              'per-page' => $this->options['ingredients-per-page']
                         );
                    } else {
                         $this->options['taxonomies'][$taxonomy] = $this->taxDefaults($this->options['taxonomies'][$taxonomy]);

                         $pagination = array(
                              'total' => count($all_terms),
                              'pages' => ceil(count($all_terms) / $this->options['taxonomies'][$taxonomy]['per-page']),
                              'current-page' => max($page, 1),
                              'taxonomy' => $this->options['taxonomies'][$taxonomy]['plural'],
                              'url' => get_option('home') . '/' . $this->options['taxonomies'][$taxonomy]['slug'],
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
                    add_filter('wp_title', array(&$this, 'recipe_taxonomy_page_title'));
                    return $replacement_template;
               } else {
                    $taxonomy = get_query_var('recipe-taxonomy');

                    if ( $taxonomy == 'recipe-ingredient' ) {
                         $pageID = $this->options['ingredient-page'];
                    } else {
                         $pageID = $this->options['taxonomies'][$taxonomy]['page'];
                    }

                    if ( $pageID and get_page($pageID) ) {
                         wp_redirect(get_permalink($pageID));
                    } else {
                         wp_redirect(get_option('home') . '/' . $this->options['index-slug']);
                    }
               }
          }

          if ( is_object($post) and $post->post_type == 'recipe' and $replacement_template = get_query_template('index-recipe') ) {
               return $replacement_template;
          } else {
               return $template;
          }
     }

     /**
      * Handle archive pages for the recipe post type
      *
      * @global object $post
      * @param string $template
      * @return string
      */
     public function archive_template($template) {
          global $post;
          if ( is_object($post) and $post->post_type == 'recipe' and $replacement_template = get_query_template('archive-recipe') ) {
               return $replacement_template;
          } else {
               return $template;
          }
     }

     /**
      * Filter to correct the title on the recipe box pages when using template files.
      *
      * This function can be overriden by adding a function named recipe_box_title
      * in your themes function file. The function will receive the generated title as an
      * argument. You need to return the text to display in the title.
      *
      * @param string $title
      * @return string
      */
     function recipe_box_title($title) {
          if ( function_exists('recipe_box_title') ) {
               return recipe_box_title($title);
          } else {
               return $this->options['recipe-box-title'] . ' | ' . get_bloginfo('name');
          }
     }

     /**
      * Filter to correct the title on index pages when using template files.
      *
      * This function can be overriden by adding a function named recipe_taxonomy_page_title
      * in your themes function file. The function will receive the generated title as an
      * argument. You need to return the text to display in the title.
      *
      * @param string $title
      * @return string
      */
     function recipe_taxonomy_page_title($title) {
          if ( function_exists('recipe_taxonomy_page_title') ) {
               return recipe_taxonomy_page_title($title);
          } else {
               if ( get_query_var('recipe-taxonomy') == 'recipe-ingredient' ) {
                    $title = __('Recipe Ingredients', 'recipe-press');
               } else {
                    $title = $this->options['taxonomies'][get_query_var('recipe-taxonomy')]['plural'];
               }
               return $title . ' | ' . get_bloginfo('name');
          }
     }

     /**
      * Rewrite rules for custom recipe permalinks.
      *
      * @global object $wp_rewrite
      * @param array $permastructure (identifier, structure, type)
      */
     function recipe_rewrite_rules($permastructure) {
          global $wp_rewrite;
          $structure = $permastructure['structure'];
          $front = substr($structure, 0, strpos($structure, '%'));
          $type_query_var = 'recipe';
          $structure = str_replace('%identifier%', $permastructure['identifier'], $structure);
          $rewrite_rules = $wp_rewrite->generate_rewrite_rules($structure, EP_NONE, true, true, true, true, true);

          /* build a rewrite rule from just the identifier if it is the first token */
          preg_match('/%.+?%/', $permastructure['structure'], $tokens);
          if ( $tokens[0] == '%identifier%' ) {
               $rewrite_rules = array_merge($wp_rewrite->generate_rewrite_rules($front . $this->options['index-slug'] . '/'), $rewrite_rules);
               $rewrite_rules[$front . $this->options['index-slug'] . '/?$'] = 'index.php?paged=1';
          }

          foreach ( $rewrite_rules as $regex => $redirect ) {
               if ( strpos($redirect, 'attachment=') === false ) {
                    /* don't set the post_type for attachments */
                    $redirect .= '&post_type=recipe';
               }

               if ( 0 < preg_match_all('@\$([0-9])@', $redirect, $matches) ) {
                    for ( $i = 0; $i < count($matches[0]); $i++ ) {
                         $redirect = str_replace($matches[0][$i], '$matches[' . $matches[1][$i] . ']', $redirect);
                    }
               }

               $redirect = str_replace('name=', $type_query_var . '=', $redirect);

               add_rewrite_rule($regex, $redirect, 'top');
          }
     }

     /**
      * Permalink handling for post_type
      *
      * @param string $permalink
      * @param object $post
      * @param bool $leavename
      * @return string
      */
     public function post_link($permalink, $id, $leavename = false) {
          if ( is_object($id) && isset($id->filter) && 'sample' == $id->filter ) {
               $post = $id;
          } else {
               $post = &get_post($id);
          }

          if ( empty($post->ID) || $post->post_type != 'recipe' )
               return $permalink;

          $rewritecode = array(
               '%identifier%',
               '%year%',
               '%monthnum%',
               '%day%',
               '%hour%',
               '%minute%',
               '%second%',
               $leavename ? '' : '%postname%',
               '%post_id%',
               '%category%',
               '%author%',
               $leavename ? '' : '%pagename%',
          );

          $permastructure = array('identifier' => $this->options['identifier'], 'structure' => $this->options['permalink']);
          $identifier = $permastructure['identifier'];
          $permalink = $permastructure['structure'];
          if ( '' != $permalink && get_option('permalink_structure') && !in_array($post->post_status, array('draft', 'pending', 'auto-draft')) ) {
               $unixtime = strtotime($post->post_date);

               $category = '';
               if ( strpos($permalink, '%category%') !== false ) {
                    $cats = get_the_category($post->ID);
                    if ( $cats ) {
                         usort($cats, '_usort_terms_by_ID'); // order by ID
                         $category = $cats[0]->slug;
                         if ( $parent = $cats[0]->parent )
                              $category = get_category_parents($parent, false, '/', true) . $category;
                    }
                    /* show default category in permalinks, without having to assign it explicitly */
                    if ( empty($category) ) {
                         $default_category = get_category(get_option('default_category'));
                         $category = is_wp_error($default_category) ? '' : $default_category->slug;
                    }
               }

               $author = '';
               if ( strpos($permalink, '%author%') !== false ) {
                    $authordata = get_userdata($post->post_author);
                    $author = $authordata->user_nicename;
               }

               $date = explode(" ", date('Y m d H i s', $unixtime));
               $rewritereplace =
                       array(
                            $identifier,
                            $date[0],
                            $date[1],
                            $date[2],
                            $date[3],
                            $date[4],
                            $date[5],
                            $post->post_name,
                            $post->ID,
                            $category,
                            $author,
                            $post->post_name,
               );
               $permalink = home_url(str_replace($rewritecode, $rewritereplace, $permalink));
               $permalink = user_trailingslashit($permalink, 'single');
          } else {
               $permalink = home_url('?p=' . $post->ID . '&post_type=' . urlencode('recipe'));
          }
          return $permalink;
     }

     /**
      * Set up all taxonomies.
      */
     function setup_taxonomies() {
          foreach ( $this->options['taxonomies'] as $key => $taxonomy ) {

               if ( isset($taxonomy['active']) and isset($taxonomy['plural']) ) {
                    if ( !isset($taxonomy['slug']) ) {
                         $rewrite = false;
                    } else {
                         $rewrite = array('slug' => $taxonomy['slug'], 'with_front' => true);
                    }

                    $labels = array(
                         'name' => $taxonomy['plural'],
                         'singular_name' => $taxonomy['singular'],
                         'search_items' => sprintf(__('Search %1$s', 'recipe-press'), $taxonomy['plural']),
                         'popular_items' => sprintf(__('Popular %1$s', 'recipe-press'), $taxonomy['plural']),
                         'all_items' => sprintf(__('All %1$s', 'recipe-press'), $taxonomy['plural']),
                         'parent_item' => sprintf(__('Parent %1$s', 'recipe-press'), $taxonomy['singular']),
                         'edit_item' => sprintf(__('Edit %1$s', 'recipe-press'), $taxonomy['singular']),
                         'update_item' => sprintf(__('Update %1$s', 'recipe-press'), $taxonomy['singular']),
                         'add_new_item' => sprintf(__('Add %1$s', 'recipe-press'), $taxonomy['singular']),
                         'new_item_name' => sprintf(__('New %1$s', 'recipe-press'), $taxonomy['singular']),
                         'add_or_remove_items' => sprintf(__('Add or remove %1$s', 'recipe-press'), $taxonomy['plural']),
                         'choose_from_most_used' => sprintf(__('Choose from the most used %1$s', 'recipe-press'), $taxonomy['plural'])
                    );

                    $args = array(
                         'hierarchical' => isset($taxonomy['hierarchical']),
                         'label' => $taxonomy['plural'],
                         'labels' => $labels,
                         'public' => true,
                         'show_ui' => true,
                         'rewrite' => $rewrite,
                    );

                    register_taxonomy($key, array('recipe'), $args);
                    $this->taxonomy_rewrite_rules($key, $taxonomy);
               }
          }
     }

     function taxonomy_rewrite_rules($taxonomy, $settings) {
          global $wp_rewrite;
          $type_query_var = $settings['slug'];
          //$structure = str_replace('%identifier%', $permastructure['identifier'], $structure);
          $rewrite_rules = $wp_rewrite->generate_rewrite_rules($settings['slug'], EP_NONE, true, true, true, true, true);
          $rewrite_rules[$settings['slug'] . '/?$'] = 'index.php?paged=1';

          foreach ( $rewrite_rules as $regex => $redirect ) {
               if ( strpos($redirect, 'attachment=') === false ) {
                    /* don't set the post_type for attachments */
                    $redirect .= '&post_type=recipe&recipe-taxonomy=' . $taxonomy;
               }

               if ( 0 < preg_match_all('@\$([0-9])@', $redirect, $matches) ) {
                    for ( $i = 0; $i < count($matches[0]); $i++ ) {
                         $redirect = str_replace($matches[0][$i], '$matches[' . $matches[1][$i] . ']', $redirect);
                    }
               }

               $redirect = str_replace('name=', $type_query_var . '=', $redirect);

               add_rewrite_rule($regex, $redirect, 'top');
          }
     }

     /**
      * Setup sizes taxonomy.
      */
     function setup_sizes() {
          $labels = array(
               'name' => __('Sizes', 'recipe-press'),
               'singular_name' => __('Size', 'recipe-press'),
               'search_items' => __('Search Sizes', 'recipe-press'),
               'popular_items' => __('Popular Sizes', 'recipe-press'),
               'all_items' => __('All Sizes', 'recipe-press'),
               'parent_item' => __('Parent Size', 'recipe-press'),
               'edit_item' => __('Edit Size', 'recipe-press'),
               'update_item' => __('Update Size', 'recipe-press'),
               'add_new_item' => __('Add Size', 'recipe-press'),
               'new_item_name' => __('New Size', 'recipe-press'),
               'add_or_remove_items' => __('Add or remove Sizes', 'recipe-press'),
               'choose_from_most_used' => __('Choose from the most used Sizes', 'recipe-press'),
          );

          $args = array(
               'hierarchical' => false,
               'label' => __('Sizes', 'recipe-press'),
               'labels' => $labels,
               'public' => true,
               'show_ui' => true,
               'capabilities' => array(
                    'assign_terms' => false
               ),
               'rewrite' => array('slug' => 'recipe-size'),
          );

          register_taxonomy('recipe-size', array('recipe'), $args);
     }

     /**
      * Setup sizes taxonomy.
      */
     function setup_serving_sizes() {
          $labels = array(
               'name' => __('Serving Sizes', 'recipe-press'),
               'singular_name' => __('Serving Size', 'recipe-press'),
               'search_items' => __('Search Serving Sizes', 'recipe-press'),
               'popular_items' => __('Popular Serving Sizes', 'recipe-press'),
               'all_items' => __('All Serving Sizes', 'recipe-press'),
               'parent_item' => __('Parent Serving Size', 'recipe-press'),
               'edit_item' => __('Edit Serving Size', 'recipe-press'),
               'update_item' => __('Update Serving Size', 'recipe-press'),
               'add_new_item' => __('Add Serving Size', 'recipe-press'),
               'new_item_name' => __('New Serving Size', 'recipe-press'),
               'add_or_remove_items' => __('Add or remove Serving Sizes', 'recipe-press'),
               'choose_from_most_used' => __('Choose from the most used Serving Sizes', 'recipe-press'),
          );

          $args = array(
               'hierarchical' => false,
               'label' => __('Serving Sizes', 'recipe-press'),
               'labels' => $labels,
               'public' => true,
               'show_ui' => true,
               'capabilities' => array(
                    'assign_terms' => false
               ),
               'rewrite' => array('slug' => 'recipe-serving'),
          );

          register_taxonomy('recipe-serving', array('recipe'), $args);

          return true;
     }

     /**
      * Setup ingredients taxonomy.
      */
     function setup_ingredients() {
          $labels = array(
               'name' => __('Ingredients', 'recipe-press'),
               'singular_name' => __('Ingredient', 'recipe-press'),
               'search_items' => __('Search Ingredients', 'recipe-press'),
               'popular_items' => __('Popular Ingredients', 'recipe-press'),
               'all_items' => __('All Ingredients', 'recipe-press'),
               'parent_item' => __('Parent Ingredient', 'recipe-press'),
               'edit_item' => __('Edit Ingredient', 'recipe-press'),
               'update_item' => __('Update Ingredient', 'recipe-press'),
               'add_new_item' => __('Add Ingredient', 'recipe-press'),
               'new_item_name' => __('New Ingredient', 'recipe-press'),
               'add_or_remove_items' => __('Add or remove Ingredients', 'recipe-press'),
               'choose_from_most_used' => __('Choose from the most used Ingredients', 'recipe-press'),
          );

          $args = array(
               'hierarchical' => false,
               'label' => __('Ingredients', 'recipe-press'),
               'labels' => $labels,
               'public' => true,
               'show_ui' => true,
               'capabilities' => array(
                    'assign_terms' => false
               ),
               'rewrite' => array('slug' => 'ingredient'),
          );

          register_taxonomy('recipe-ingredient', array('recipe'), $args);
          $this->taxonomy_rewrite_rules('recipe-ingredient', array('slug' => $this->options['ingredient-slug']));

          return true;
     }

     /**
      * Set up the My Recipe Box rewrite rules.
      *
      * @global object $wp_rewrite
      */
     function setup_my_box() {
          global $wp_rewrite;
          $wp_rewrite->flush_rules();

          $type_query_var = $this->options['recipe-box-slug'];
          $rewrite_rules = $wp_rewrite->generate_rewrite_rules($this->options['recipe-box-slug'], EP_NONE, true, false, false, true, true);
          $rewrite_rules[$this->options['recipe-box-slug'] . '/?$'] = 'index.php?paged=1';

          foreach ( $rewrite_rules as $regex => $redirect ) {
               if ( strpos($redirect, 'attachment=') === false ) {
                    /* don't set the post_type for attachments */
                    $redirect .= '&post_type=recipe&recipe-box=home';
               }

               if ( 0 < preg_match_all('@\$([0-9])@', $redirect, $matches) ) {
                    for ( $i = 0; $i < count($matches[0]); $i++ ) {
                         $redirect = str_replace($matches[0][$i], '$matches[' . $matches[1][$i] . ']', $redirect);
                    }
               }

               $redirect = str_replace('name=', $type_query_var . '=', $redirect);
               add_rewrite_rule($regex, $redirect, 'top');
          }
          $wp_rewrite->flush_rules();
     }

     /**
      * Adds additional meta boxes to the recipe edit screen.
      */
     function init_metaboxes() {
          add_meta_box('recipes_ingredients', __('Ingredients', 'recipe-press'), array(&$this, 'ingredients_box'), 'recipe', 'advanced', 'high');
          add_meta_box('recipes_details', __('Details', 'recipe-press'), array(&$this, 'details_box'), 'recipe', 'side', 'high');

          /* Display Nutritional value */
          if ( $this->options['use-nutritional-value'] ) :
               add_meta_box('recipes_nutrients', __('Nutrients', 'recipe-press'), array(&$this, 'nutrients_box'), 'recipe', 'advanced', 'high');
          endif;
     }

     /**
      * Sets up the box for entering ingredients.
      */
     function ingredients_box() {
          /* Use nonce for verification */
          echo '<input type="hidden" name="ingredients_noncename" id="ingredients_noncename" value="' . wp_create_nonce('recipe_press_ingredients') . '" />';
          include($this->pluginPath . 'includes/ingredient-form.php');
     }

     /**
      * Sets up the box for the recipe details.
      *
      * @global object $post
      */
     function details_box() {
          global $post;
          /* Use nonce for verification */
          echo '<input type="hidden" name="details_noncename" id="details_noncename" value="' . wp_create_nonce('recipe_press_details') . '" />';
          include ($this->pluginPath . 'includes/details-form.php');
     }

     /**
      * Sets up the nutrients box.
      *
      * @global object $post
      */
     function nutrients_box() {
          global $post;
          /* Use nonce for verification */
          echo '<input type="hidden" name="nutrients_noncename" id="nutrients_noncename" value="' . wp_create_nonce('recipe_press_nutrients') . '" />';
          include($this->pluginPath . 'includes/nutrients-form.php');
     }

}
