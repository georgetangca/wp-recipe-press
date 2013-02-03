<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * recipe-taxonomy.php - The Template for displaying all recipe categories.
 *
 * @package RecipePress
 * @subpackage templates
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
/* Make sure we have some terms to list */
if ( !is_array($terms) ) {
     foreach ( $this->options['taxonomies'] as $key => $taxonomy ) {
          if ( $post->ID == $taxonomy['page'] ) {
               $tax = $key;
          }
     }

     $terms = get_terms($tax, array('parent' => 0));
}

?>

<?php if ( $pagination['pages'] > 1 ) : ?>
     <div id="nav-above" class="navigation recipe-navigation cleared">
          <div class="nav-previous"><?php previous_taxonomies_link(__('<span class="meta-nav">&larr;</span>More Taxonomies', 'recipe-press')); ?></div>
          <div class="nav-next"><?php next_taxonomies_link(__('More Taxonomies <span class="meta-nav">&rarr;</span>', 'recipe-press')); ?></div>
     </div><!-- #nav-above -->
<?php endif; ?>

<?php foreach ( $terms as $id => $term ) : ?>
          <div id="recipe_taxonomy_<?php the_term_id($term); ?>" class="recipe-taxonomy-content">
               <div id="recipe_taxonomy_image_<?php the_term_id($term); ?>" class="recipe-taxonomy-image">
                    <a href="<?php echo get_term_link($term, $taxonomy); ?>"><?php the_term_thumbnail($term); ?></a>
               </div>
               <div id="recipe_taxonomy_about_<?php the_term_id($term); ?>" class="recipe-taxonomy-about">
                    <h3 class="recipe-category-title"><a href="<?php echo get_term_link($term, $taxonomy); ?>"><?php the_term_name($term); ?></a></h3>
                    <blockquote class="recipe-category-description"><?php the_term_description($term); ?></blockquote>
                    <div class="recipe-sample-list attach-bottom"><?php _e('Sample recipes', 'recipe-press'); ?>: <?php the_random_posts_list($term, array('after-link' => ' | ')); ?></div>
                    <div class="cleared"></div>
               </div>
               <div class="cleared"></div>
          </div>
<?php endforeach; ?>

<?php if ( $pagination['pages'] > 1 ) : ?>
               <div id="nav-below" class="navigation recipe-navigation cleared">
                    <div class="nav-previous"><?php previous_taxonomies_link(__('<span class="meta-nav">&larr;</span>More Taxonomies', 'recipe-press')); ?></div>
                    <div class="nav-next"><?php next_taxonomies_link(__('More Taxonomies <span class="meta-nav">&rarr;</span>', 'recipe-press')); ?></div>
               </div><!-- #nav-below -->
<?php endif; ?>

<div class="cleared"></div>
