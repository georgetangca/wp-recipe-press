<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * recipe-single.php - The Template for displaying all recipes.
 *
 * @package RecipePress
 * @subpackage templates
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

     <div class="recipe-header">
          <div class="recipe-press-image align-left">
               <?php
               if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                    the_post_thumbnail('recipe-press-image');
               }
               ?>
               <ul class="recipe-controls">
                    <?php the_recipe_controls(); ?>
               </ul><!-- .recipe-controls -->
               <div id="recipe_control_message" class="recipe-control-messages" style="display:none"></div>
          </div><!-- .recipe-press-image -->
          <div class="recipe-about">
               <div class="recipe-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
               </div><!-- .recipe-title -->
               <div class="recipe-meta">
                    <?php printf(__('<span>Posted</span> %1$s by %2$s', 'recipe-press'), get_the_date(), get_the_author()); ?>
               </div><!-- .recipe-meta -->
               <blockquote class="recipe-notes"><?php the_recipe_introduction(array('length' => '5000')); ?></blockquote>

               <div class="recipe-meta">
                    <?php
                    if ( use_recipe_taxonomy('recipe-category') ) {
                         _e('Posted in :', 'recipe-press');
                         the_terms(get_the_id(), 'recipe-category');
                    }
                    ?>

                    <?php
                    if ( use_recipe_taxonomy('recipe-cuisine') ) {
                         _e('Cuisines : ', 'recipe-press');
                         the_terms(get_the_id(), 'recipe-cuisine');
                    }
                    ?>
               </div>
          </div><!-- .recipe-about -->
     </div><!-- .recipe-header -->

     <?php if ( use_recipe_times ( ) ) : ?>
                         <div id="recipe-details-<?php the_ID(); ?>" class="recipe-section recipe-section-<?php the_id(); ?>">

                              <ul class="recipe-details">
               <?php the_recipe_prep_time(); ?>
               <?php the_recipe_cook_time(); ?>
               <?php the_recipe_ready_time(); ?>
                    </ul>
               </div><!-- #recipe-details -->
     <?php endif; ?>

     <?php if ( use_recipe_servings ( ) ) : ?>
                              <div id="recipe-servings-<?php the_ID(); ?>" class="recipe-servings recipe-servings-<?php the_ID(); ?>">
                                   <h4 class="recipe-section-title recipe-servings"><?php _e('Servings', 'recipe-press'); ?></h4>
          <?php the_recipe_servings(); ?>
                         </div><!-- .recipe-servings -->
     <?php endif; ?>

                              <div class="recipe-content">
                                   <h4 class="recipe-section-title recipe-ingredients"><?php _e('Ingredients', 'recipe-press'); ?> </h4>
          <?php the_recipe_ingredients(); ?>
                              <h4 class="recipe-section-title recipe-instructions"><?php _e('Directions', 'recipe-press'); ?></h4>
          <?php the_content(); ?>

          <?php the_recipe_nutrients(); ?>

          <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'recipe-press'), 'after' => '</div>')); ?>
                         </div><!-- .entry-content -->

     <?php do_action('after_recipe_content'); ?>
     <?php if ( get_the_author_meta('description') ) : // If a user has filled out their description, show a bio on their entries    ?>
                                   <div id="entry-author-info">
                                        <div id="author-avatar">
               <?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('recipe-press_author_bio_avatar_size', 60)); ?>
                              </div><!-- #author-avatar -->
                              <div id="author-description">
                                   <h2><?php printf(esc_attr__('About %s', 'recipe-press'), get_the_author()); ?></h2>
               <?php the_author_meta('description'); ?>
                                   <div id="author-link">
                                        <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                         <?php printf(__('View all posts by %s <span class="meta-nav">&rarr;</span>', 'recipe-press'), get_the_author()); ?>
                              </a>
                         </div><!-- #author-link	-->
                    </div><!-- #author-description -->
               </div><!-- #entry-author-info -->
     <?php endif; ?>

                                   <div class="entry-utility">
          <?php edit_post_link(__('Edit', 'recipe-press'), '<span class="edit-link">', '</span>'); ?>
     </div><!-- .entry-utility -->
</div><!-- #post-## -->