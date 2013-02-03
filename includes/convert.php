<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * convert.php - View for the convert recipes page.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;" class="overDiv"></div>
<div class="wrap">
     <form method="post" action="<?php echo admin_url('edit.php?post_type=recipe&page=recipe-press-convert'); ?>" id="recipe_press_settings">
          <div class="icon32" id="icon-recipe-press"><br/></div>
          <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Convert Old Recipes', 'recipe-press'); ?> </h2>
          <div style="width:49%; float:left">

               <div class="postbox">
                    <h3 class="handl" style="margin:0;padding:3px;cursor:default;"><?php _e('Conversion Options', 'recipe-press'); ?></h3>
                    <div class="table">
                         <table class="form-table">
                              <tbody>
                                   <tr align="top">
                                        <td colspan="4"><?php printf(__('I have detected that you have convertd an older version of %1$s at some time. The old recipes and other items are still present in the system. Would you like to convert them?', 'recipe-press'), $this->pluginName); ?></td>
                                   </tr>
                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_convert_recipes"><?php _e('Convert Recipes?', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input name="<?php echo $this->optionsName; ?>[convert-recipes]" id="recipe_press_convert_recipes" type="checkbox" value="1" checked="checked" />
                                             <?php $this->help(__('Click this option to convert all existing recipes.', 'recipe-press')); ?>
                                        </td>
                                        <th scope="row"><label for="recipe_press_convert_categories"><?php _e('Convert Categories?', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input name="<?php echo $this->optionsName; ?>[convert-categories]" id="recipe_press_convert_categories" type="checkbox" value="1" checked="checked" />
                                             <?php $this->help(__('Click this option to convert your old recipe categories.', 'recipe-press')); ?>
                                        </td>
                                   </tr>
                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_convert_comments"><?php _e('Convert Comments?', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input name="<?php echo $this->optionsName; ?>[convert-comments]" id="recipe_press_convert_comments" type="checkbox" value="1" checked="checked" />
                                             <?php $this->help(__('Click this option to convert all existing recipe comments.', 'recipe-press')); ?>
                                        </td>
                                   </tr>
                                   <tr align="top">
                                        <td colspan="4"><?php printf(__('Notce: Running this conversion script will copy the data from the old install and remove all old data. Please make a backup of your old data before running this conversion.', 'recipe-press'), $this->pluginName); ?></td>
                                   </tr>
                                   <tr>
                                        <td colspan="4" style="text-align: center">
                                             <input type="hidden" name="details_noncename" id="convert_noncename" value="<?php echo wp_create_nonce('recipe_press_convert'); ?>" />
                                             <input type="submit" name="Submit" value="<?php _e('Start Conversion', 'recipe-press'); ?>" />
                                        </td>
                                   </tr>
                              </tbody>
                         </table>
                    </div>
               </div>
          </div>
     </form>
</div>