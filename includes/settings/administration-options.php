<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * admin.php - View for the administration tab.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
?>

<div class="postbox" style="float: left; width: 49%">
     <div class="table recipe-press-settings-table">
          <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
               <?php _e('Administration', 'recipe-press'); ?>
          </h3>
          <table class="form-table cp-table">
               <tbody>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_reset_options"><?php _e('Reset to default: ', 'recipe-press'); ?></label></th>
                         <td><input type="checkbox" id="recipe_press_reset_options" name="confirm-reset-options" value="1" onclick="recipe_press_reset_confirm(this)" /></td>
                    </tr>

                    <tr align="top">
                         <th scope="row"><?php _e('Remove Recipes: ', 'recipe-press'); ?></th>
                         <td>
                              <label><input type="checkbox" id="remove_pending_recipes" name="remove-pending-recipes" value="1"  /> <?php _e('Pending only', 'recipe-press'); ?> </label>
                              <label><input type="checkbox" id="remove_all_recipes" name="remove-all-recipes" value="1" /> <?php _e('All Recipes', 'recipe-press'); ?> </label>
                         </td>
                    </tr>

                    <?php foreach ( $this->options['taxonomies'] as $tax => $settings ) : ?>
                         <tr align="top">
                              <th scope="row"><?php printf(__('Remove %1$s: ', 'recipe-press'), $settings['plural']); ?></th>
                              <td>
                                   <label><input type="checkbox" id="remove_empty_<?php echo $tax; ?>" name="remove-empty-<?php echo $tax; ?>" value="1"  /> <?php printf(__('Empty %1$s', 'recipe-press'), $settings['plural']); ?> </label>
                                   <label><input type="checkbox" id="remove_all_<?php echo $tax; ?>" name="remove-all-<?php echo $tax; ?>" value="1" /> <?php printf(__('All %1$s', 'recipe-press'), $settings['plural']); ?> </label>
                              </td>
                         </tr>
                    <?php endforeach; ?>

                         <tr align="top">
                              <th scope="row"><?php _e('Remove Recipe Sizes: ', 'recipe-press'); ?></th>
                              <td>
                                   <label><input type="checkbox" id="remove_empty_sizes" name="remove-empty-recipe-size" value="1"  /> <?php _e('Empty Sizes', 'recipe-press'); ?> </label>
                                   <label><input type="checkbox" id="remove_all_sizes" name="remove-all-recipe-size" value="1" /> <?php _e('All Sizes', 'recipe-press'); ?> </label>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><?php _e('Remove Recipe Servings: ', 'recipe-press'); ?></th>
                              <td>
                                   <label><input type="checkbox" id="remove_empty_measurements" name="remove-empty-recipe-serving" value="1"  /> <?php _e('Empty Serving Sizes', 'recipe-press'); ?> </label>
                                   <label><input type="checkbox" id="remove_all_measurements" name="remove-all-recipe-serving" value="1" /> <?php _e('All Serving Sizes', 'recipe-press'); ?> </label>
                              </td>
                         </tr><tr align="top">
                              <th scope="row"><?php _e('Remove Recipe Ingredients: ', 'recipe-press'); ?></th>
                              <td>
                                   <label><input type="checkbox" id="remove_empty_ingredients" name="remove-empty-recipe-ingredient" value="1"  /> <?php _e('Empty Ingredients', 'recipe-press'); ?> </label>
                                   <label><input type="checkbox" id="remove_all_ingredients" name="remove-all-recipe-ingredient" value="1" /> <?php _e('All Ingredients', 'recipe-press'); ?> </label>
                              </td>
                         </tr>                    <!--
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_backup_options"><?php _e('Back-up Options: ', 'recipe-press'); ?></label></th>
                              <td><input type="checkbox" id="recipe_press_backup_options" name="confirm-backup-options" value="1" onclick="backupOptions(this)" /></td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_restore_options"><?php _e('Restore Options: ', 'recipe-press'); ?></label></th>
                         <td><input type="file" id="recipe_press_restore_options" name="recipe-press-restore-options"/></td>
                    </tr>
                         -->
                    </tbody>
               </table>
          </div>
     </div>
