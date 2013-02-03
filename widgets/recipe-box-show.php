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
     <div class="recipe-box-widget-header">
         <?php 
          $recipe_box_url = get_the_recipe_box_url(); 
          $edit_box = '<a href="'.$recipe_box_url.'" taget="_blank" >'.get_the_recipe_box_title().'</a>'; 
         ?>
          <div class="widgettitle"><?php echo $edit_box; ?></div>
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
                    <th width="50%" ></th>
                    <th width="50%" ><?php //_e('Title', 'recipe-press'); ?></th>
               </tr>
          </thead>
          <tbody>
               <?php if ( $recipeData->posts->have_posts() ) : while ($recipeData->posts->have_posts()) : $recipeData->posts->the_post(); ?>
                      <tr id="recipe_box_entry_<?php the_id(); ?>" class="recipe-box-row">
                          <td>
                         <?php if ( function_exists('has_post_thumbnail') and has_post_thumbnail() ) : ?>
                              <a href="<?php the_permalink(); ?>">
                              <?php //the_post_thumbnail('recipe-press-thumb'); ?>
                              <?php the_post_thumbnail(array(50,50)); ?>
                                  
                         </a>
                         <?php else: ?>
                             <a href="<?php the_permalink(); ?>">
                              <img src="<?php echo plugins_url('/recipe-plus'); ?>/images/no_pic.png"  width="50" height="50" />
                             </a>
                         <?php endif; ?>
                         </td>
                         <td>
                           <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <br><span class="recipe-box-author"><?php _e('By: ', 'recipe-press'); ?> <?php the_author(); ?></span>
                         </td>
                         
                    </tr>
                    
               <?php endwhile; ?>
                <?php else:?>
                <tr> 
                   <td colspan="2"><?php _e('Box is empty now', 'recipe-press'); ?></td>
                </tr>
               <?php endif; ?>
          </tbody>
     </table>
</div>