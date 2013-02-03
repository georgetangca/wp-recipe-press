<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * recipe-archive.php - The Template for displaying all recipe archives.
 *
 * @package RecipePress
 * @subpackage templates
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
global $wp_query;
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('recipe-box'); ?> <?php the_recipe_box_image(); ?>>

     <div class="entry-content">

          <div class="recipe-about">
               <div class="recipe-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
               </div><!-- .recipe-title -->
               <div class="recipe-meta">
                    <?php printf(__('<span>Posted</span> %1$s by %2$s', 'recipe-press'), get_the_date(), get_the_author()); ?>
               </div><!-- .recipe-meta -->
               <blockquote class="recipe-notes"><?php the_recipe_introduction(array('length' => '5000')); ?></blockquote>

               <div class="recipe-meta">
                    <?php _e('Posted in', 'recipe-press'); ?>: <?php the_terms(get_the_id(), 'recipe-category'); ?> |
                    <?php _e('Cuisines', 'recipe-press'); ?>: <?php the_terms(get_the_id(), 'recipe-cuisine'); ?>
               </div>
          </div><!-- .recipe-about -->

     </div><!-- .entry-content -->

     <div class="cleared"></div>
</div><!-- #post-## -->