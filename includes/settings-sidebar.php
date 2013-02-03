<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * footer.php - View for the footer of all special pages.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
?>

<div style="clear:both;">
     <div class="postbox">
          <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Permalink Instructions', 'recipe-press'); ?></h3>
          <div style="padding:8px;">
               <p>
                    <?php
                    printf(__('The permalink structure will be used to create the custom URL structure for your individual recipes. These follow WP\'s normal %1$s, but must also include the content type %2$s and at least one of these unique tags: %3$s or %4$s.', 'recipe-press'),
                            '<a href="http://codex.wordpress.org/Using_Permalinks" target="_blank">' . __('permalink tags', 'recipe-press') . '</a>',
                            '<strong>%identifier%</strong>',
                            '<strong>%postname%</strong>',
                            '<strong>%post_id%</strong>'
                    );
                    ?>
               </p>
               <p>
                    <?php _e('Allowed tags: %year%, %monthnum%, %day%, %hour%, %minute%, %second%, %postname%, %post_id%', 'recipe-press'); ?>
               </p>
               <p>
                    <?php
                    printf(__('For complete instructions on how to set up your permaliks, visit the %1$s.', 'recipe-press'),
                            '<a href="http://wiki.recipepress.net/wiki/Recipe_Permalinks" target="blank">' . __('Documentation Page', 'recipe-press') . '</a>'
                    );
                    ?>
               </p>
          </div>
     </div>
     <div class="postbox">
          <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Available Shortcodes', 'recipe-press'); ?></h3>
          <div style="padding:8px">
               <p><?php _e('There are several shortcodes available in RecipePress, but the most useful will likely be [recipe-list] and [recipe-form].', 'recipe-press'); ?></p>
               <ul>
                    <li><strong>[recipe-list]</strong>: <?php printf(__('Used to display a list of recipes. [%1$s]'), '<a href="http://wiki.recipepress.net/wiki/Recipe-list" target="_blank">' . __('Documentation', 'recipe-press') . '</a>'); ?></li>
                    <li><strong>[recipe-form]</strong>: <?php printf(__('Used to display the front end recipe form. [%1$s]'), '<a href="http://wiki.recipepress.net/wiki/Recipe-form" target="_blank">' . __('Documentation', 'recipe-press') . '</a>'); ?></li>
                    <li><strong>[recipe-show]</strong>: <?php printf(__('Used to display a single recipe. [%1$s]'), '<a href="http://wiki.recipepress.net/wiki/Recipe-show" target="_blank">' . __('Documentation', 'recipe-press') . '</a>'); ?></li>
                    <li><strong>[recipe-tax]</strong>: <?php printf(__('Used to display a list of entries in a give taxonomy (i.e. category or cuisine). [%1$s]'), '<a href="http://wiki.recipepress.net/wiki/Recipe-tax" target="_blank">' . __('Documentation', 'recipe-press') . '</a>'); ?></li>
               </ul>
          </div>
     </div>
     <div class="postbox">
          <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Taxonomy Settings', 'recipe-press'); ?></h3>
          <div style="padding:8px">
               <p><?php _e('If you want to have a page that lists your taxonomies, you need to do one of two things:', 'recipe-press'); ?></p>
               <p>
                    <strong><?php _e('Create Pages', 'recipe-press'); ?></strong>: <?php printf(__('Create individual pages for each taxonomy that will list the terms. These pages must have the [recipe-tax] short code on them. [%1$s]'), '<a href="http://wiki.recipepress.net/wiki/Recipe-tax" target="_blank">' . __('Documentation for shortcode', 'recipe-press') . '</a>'); ?>
               </p>
               <p>
                    <strong><?php _e('Create Template File', 'recipe-press'); ?></strong>: <?php printf(__('If you create a template file named `recipe-taxonomy.php` in your theme, all taxonomies will use this template to display a list of taxonomies. [%1$s]'), '<a href="http://wiki.recipepress.net/wiki/Template_File:_taxonomy-recipe.php" target="_blank">' . __('Documentation', 'recipe-press') . '</a>'); ?>
               </p>
               <p>
                    <strong><?php _e('Warning!' , 'recipe-press'); ?></strong> <?php _e('If you do not select a display page for a taxonomy and the template file does not exist, any calls to the site with the URL slug for the taxonomy will redirect to your default recipe list.' , 'recipe-press'); ?>
               </p>
          </div>
     </div>
</div>