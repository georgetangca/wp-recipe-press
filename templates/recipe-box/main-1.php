<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * main.php - The main recipe box display.
 *
 * @package RecipePress
 * @subpackage templates/recipe-box
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 2.2
 */
?>

<div id="recipe_box" class="recipe-box-wrapper">
     <div class="recipe-box-header">
          <div class="recipe-box-title"><?php the_recipe_box_title(); ?></div>
          <div class="recipe-box-search"><?php the_recipe_box_search(); ?></div>
          <div class="cleared" style="clear:both"></div>
     </div>

     <!-- Future Feature
     <ul class="recipe-box-tabs">
          <li class="recipe-box-recipes"><a href="">All Recipes</a></li>
          <li class="recipe-box-folders"><a href="">Folders</a></li>
          <li class="recipe-box-menus"><a href="">Menus</a></li>
     </ul>
     -->

     <table id="recipe_box_list" class="recipe-box-list">
          <thead>
               <tr>
                    <th class="recipe-box-list-image"></th>
                    <th class="recipe-box-list-title"><?php _e('Title', 'recipe-press'); ?></th>

                    <th class="recipe-box-list-title"><?php _e('Date Added', 'recipe-press'); ?></th>
                    <th class="recipe-box-list-title"><?php _e('My Notes', 'recipe-press'); ?></th>
               </tr>
          </thead>
          <tbody>
               <?php if ( $recipeData->posts->have_posts() ) : while ($recipeData->posts->have_posts()) : $recipeData->posts->the_post(); ?>
                         <tr id="recipe_box_entry_<?php the_id(); ?>" class="recipe-box-row">
                              <td>
                         <?php if ( function_exists('has_post_thumbnail') and has_post_thumbnail() ) : ?>
                              <a href="<?php the_permalink(); ?>">
                              <?php the_post_thumbnail('recipe-press-thumb'); ?>
                         </a>
                         <?php endif; ?>
                         </td>
                         <td>
                              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                              <br><span class="recipe-box-author"><?php _e('By: ', 'recipe-press'); ?> <?php the_author_posts_link(); ?></span>
                              <br>
                              <ul id="recipe_options_<?php the_id(); ?>" class="recipe-box-options">
                                   <li><a href="<?php the_permalink(); ?>"><?php _e('View', 'recipe-press'); ?></a></li>
                                   <li>&nbsp;|&nbsp;<a href="<?php the_recipe_box_url(); ?>" onclick="return recipe_box_remove_recipe(<?php the_id(); ?>, '<?php echo wp_create_nonce(get_the_title()); ?>')">Remove</a></li>
                                   <!-- Future Features 
                                   <li>&nbsp;|&nbsp;Add to folder </li>
                                   <li>&nbsp;|&nbsp;Add to menu </li>
                                   <-->
                              </ul>
                         </td>
                         <td><?php the_recipe_box_date(); ?></td>
                         <td><?php the_recipe_box_notes_link(); ?></td>
                    </tr>
                    <tr id="recipe_notes_<?php the_id(); ?>" style="display: none">
                         <td><?php _e('Recipe Notes', 'recipe-press'); ?></td>
                         <td colspan="3">
                              <?php the_recipe_notes_form(); ?>
                         </td>
                    </tr>
               <?php endwhile;
                         endif; ?>
          </tbody>
     </table>
</div>