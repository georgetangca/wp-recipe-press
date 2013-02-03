<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * converter.php - The actual conversion code.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
?>

<div class="wrap">
     <div class="icon32" id="icon-recipe-press"><br/></div>
     <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Converting Old Recipes', 'recipe-press'); ?> </h2>

     <?php
     global $wpdb;

     $newcategories = array();

     if ( $_POST['recipe-press-options']['convert-categories'] ) {
          $categories = $wpdb->get_results('select * from `' . $wpdb->prefix . 'rp_categories`');
          echo '<p><em>' . sprintf(__('Converting %1$d categories', 'recipe-press'), count($categories)) . '...';

          foreach ( $categories as $category ) {
               $newcat = wp_insert_term($category->name, 'recipe-category', array('description' => $category->description, 'slug' => $category->slug));

               $newcategories[$category->id] = $category->name;
          }
     }

     echo 'Done</em></p>';

     $newRecipes = array();

     if ( $_POST['recipe-press-options']['convert-recipes'] ) {
          $recipes = $wpdb->get_results('select * from `' . $wpdb->prefix . 'rp_recipes`');
          echo '<p><em>' . sprintf(__('Converting %1$d recipes', 'recipe-press'), count($recipes)) . '...</em></p>';

          foreach ( $recipes as $recipe ) {
               echo "&nbsp;&nbsp;&nbsp;<em>" . $recipe->title . '...</em>';

               $post = array(
                    'post_title' => $recipe->title,
                    'post_status' => ($recipe->status == 'active') ? 'publish' : $recipe->status,
                    'post_author' => $recipe->user_id,
                    'post_content' => $recipe->instructions,
                    'post_excerpt' => $recipe->notes,
                    'post_name' => $recipe->slug,
                    'post_type' => 'recipe',
                    'post_date' => $recipe->added,
               );

               $post = wp_insert_post($post);

               wp_set_object_terms($post, $newcategories[$recipe->category], 'recipe-category');

               add_post_meta($post, '_recipe_prep_time_value', $recipe->prep_time, true);
               add_post_meta($post, '_recipe_cook_time_value', $recipe->cook_time, true);
               add_post_meta($post, '_recipe_ready_time_value', $recipe->ready_time, true);
               add_post_meta($post, '_recipe_servings_value', $recipe->servings, true);
               add_post_meta($post, '_recipe_featured_value', $recipe->featured, true);
               add_post_meta($post, '_recipe_servings_value', $recipe->servings, true);
               add_post_meta($post, '_recipe_serving_size_value', $recipe->servings_size, true);

               if ( $recipe->media_id > 0 ) {
                    add_post_meta($post, '_thumbnail_id', $recipe->media_id, true);
               }


               $ingredients = unserialize($recipe->ingredients);
               $order = 0;
               foreach ( $ingredients as $ingredient ) {
                    $ingredient['order'] = $order;
                    add_post_meta($post, '_recipe_ingredient_value', $ingredient, false);
                    add_post_meta($post, '_recipe_ingredient_list', $ingredient['item'], false);
                    ++$order;
               }

               $newRecipes[$recipe->id] = $post;

               echo 'done<br />';
          }
     }

     if ( $_POST['recipe-press-options']['convert-comments'] ) {
          $comments = $wpdb->get_results('select * from `' . $wpdb->prefix . 'rp_comments`');
          echo '<p><em>' . sprintf(__('Converting %1$d comments', 'recipe-press'), count($comments)) . '...</em></p>';

          foreach ( $comments as $comment ) {
               $data = array(
                    'comment_post_ID' => $newRecipes[$comment->recipe_id],
                    'comment_author' => $comment->autho,
                    'comment_author_email' => $comment->author_email,
                    'comment_author_url' => $comment->author_url,
                    'comment_content' => $comment->content,
                    'user_id' => $comment->user_id,
                    'comment_author_IP' => $comment->author_IP,
                    'comment_date' => $comment->date,
                    'comment_approved' => ($comment->status == 'active') ? 1 : 0
               );

               wp_insert_comment($data);
          }
     }

     $wpdb->query('drop table `' . $wpdb->prefix . 'rp_comments');
     $wpdb->query('drop table `' . $wpdb->prefix . 'rp_ingredients');
     $wpdb->query('drop table `' . $wpdb->prefix . 'rp_options');
     $wpdb->query('drop table `' . $wpdb->prefix . 'rp_categories');
     $wpdb->query('drop table `' . $wpdb->prefix . 'rp_recipes');
     ?>
</div>
