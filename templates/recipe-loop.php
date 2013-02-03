<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * recipe-loop.php - The Template for looping through recipes.
 *
 * @package RecipePress
 * @subpackage templates
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
/*
 * This template is used to display the each recipe in a list. You can copy this file to your
 * Theme folder and make changes if you wish. You can use standard template tags to display the
 * recipe information.
 */
?>

<div id="recipe-<?php the_ID(); ?>" class="recipe-box" <?php the_recipe_box_image(); ?>>

     <div class="recipe-about">
          <blockquote class="recipe-notes"><?php the_recipe_introduction(array('length' => '5000')); ?></blockquote>

          <div class="recipe-meta">
               <?php _e('Posted in', 'recipe-press'); ?>: <?php the_terms(get_the_id(), 'recipe-category'); ?> |
               <?php _e('Cuisines', 'recipe-press'); ?>: <?php the_terms(get_the_id(), 'recipe-cuisine'); ?>
          </div>
     </div><!-- .recipe-about -->

     <div class="cleared"></div>
</div><!-- #recipe-## -->