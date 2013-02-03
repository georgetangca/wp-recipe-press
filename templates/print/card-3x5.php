<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * card-3x5.php - The Template for printing a recipe on a 3x5 card.
 *
 * @package RecipePress
 * @subpackage templates/print
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
?>

<div id="recipe_<?php $post->post_name; ?>" class="card-3x5">

     <div class="recipe-press-image align-right">
          <?php
          if ( function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID) ) {
               echo get_the_post_thumbnail($post->ID, array(100, 100));
          }
          ?>
     </div>
     <div class="recipe-header">

          <div class="recipe-about">
               <div class="recipe-title"><?php the_title(); ?></div>
               <div class="recipe-meta">
                    <?php printf(__('<span>Posted</span> %1$s by %2$s', 'recipe-press'), get_the_date(), get_the_author($post)); ?>
               </div>
               <div class="recipe-detail">
                    <?php the_recipe_prep_time(array('type' => 'single', 'tag' => '', 'prefix' => '')); ?>
                    <?php the_recipe_cook_time(array('type' => 'single', 'tag' => '', 'prefix' => '')); ?>
                    <?php the_recipe_ready_time(array('type' => 'single', 'tag' => '', 'prefix' => '')); ?>
                    <?php _e('Servings: ', 'recipe_press'); ?><?php the_recipe_servings(array('tag' => 'span')); ?>
               </div>
          </div>
     </div>


     <div class="recipe-content recipe-ingredients">
          <h4 class="recipe-section-title"><?php _e('Ingredients', 'recipe-press'); ?> </h4>
          <?php the_recipe_ingredients(); ?>
               </div>
               <div class="recipe-content recipe-instructions">
                    <h4 class="recipe-section-title"><?php _e('Directions', 'recipe-press'); ?></h4>
          <?php the_recipe_directions(); ?>
               </div><!-- .entry-content -->

     <?php do_action('after_recipe_content'); ?>

     <div class="cleared" style="clear: both"></div>

</div>