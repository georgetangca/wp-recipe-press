<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * form-options.php - View for the submit form options box.
 *
 * @package RecipePress
 * @subpackage includes/settings
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
?>

<div class="postbox" style="float: left; width: 49%">
     <div class="table recipe-press-settings-table">
          <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
               <?php _e('Recipe Form Settings', 'recipe-press'); ?>
          </h3>
          <table class="form-table">
               <tbody>
                    <tr align="top">
                         <th scop="row" colspan="2">
                              <strong><?php _e('Important Notice', 'recipe-press'); ?> : </strong>
                              <?php printf(__('To display the recipe form, add the %1$s short code to any page or post. You must do this on the page you selected below for the form to display.', 'recipe-press'), '<strong>[recipe-form]</strong>'); ?>
                         </th>
                    </tr>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_form_redirect"><?php _e('Redirect URL', 'recipe-press'); ?></label></th>
                         <td>
                             <input type="input" name="<?php echo $this->optionsName; ?>[form-redirect]" id="recipe_press_form_redirect_fields" value="<?php echo $this->options['form-redirect']; ?>" />
                             <?php $this->help(__('What page to redirect a visitor to after a recipe has been submitted successfully.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_new_recipe_status"><?php _e('Status for User Recipes', 'recipe-press'); ?></label></th>
                         <td>
                              <select name="<?php echo $this->optionsName; ?>[new-recipe-status]" id="recipe_press_new_recipe_status">
                                   <option value="draft" <?php selected($this->options['new-recipe-status'], 'draft'); ?> ><?php _e('Draft', 'recipe-press'); ?></option>
                                   <option value="pending" <?php selected($this->options['new-recipe-status'], 'pending'); ?> ><?php _e('Pending Review', 'recipe-press'); ?></option>
                                   <option value="publish" <?php selected($this->options['new-recipe-status'], 'publish'); ?> ><?php _e('Published', 'recipe-press'); ?></option>
                              </select>
                              <?php $this->help(__('Select the default recipe status for new user submitted recipes.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_ingredients_fields"><?php _e('Number of ingredients fields on blank form', 'recipe-press'); ?></label></th>
                         <td>
                              <input type="input" name="<?php echo $this->optionsName; ?>[ingredients-fields]" id="recipe_press_ingredients_fields" value="<?php echo $this->options['ingredients-fields']; ?>" />
                              <?php $this->help(__('How many initial ingredients input lines to display on a blank form.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_instructions_fields"><?php _e('Number of directions fields on blank form', 'recipe-press'); ?></label></th>
                         <td>
                              <input type="input" name="<?php echo $this->optionsName; ?>[instructions-fields]" id="recipe_press_instruction_fields" value="<?php echo $this->options['instructions-fields']; ?>" />
                              <?php $this->help(__('How many initial directions input lines to display on a blank form.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    
                    
                    <tr align="top">
                         <th scope="row"><?php _e('Required Form Fields', 'recipe-press'); ?></th>
                         <td>
                              <table border="0">
                                   <tbody>
                                        <tr>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="title" <?php $this->checked($this->options['required-fields'], 'title'); ?> /> <?php _e('Title', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="image" <?php $this->checked($this->options['required-fields'], 'image'); ?> /> <?php _e('Image', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="notes" <?php $this->checked($this->options['required-fields'], 'notes'); ?> /> <?php _e('Notes', 'recipe-press'); ?></label></td>
                                         
                                        </tr>
                                        <tr>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="prep_time" <?php $this->checked($this->options['required-fields'], 'prep_time'); ?> /> <?php _e('Prep Time', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="cook_time" <?php $this->checked($this->options['required-fields'], 'cook_time'); ?> /> <?php _e('Cook Time', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="servings" <?php $this->checked($this->options['required-fields'], 'servings'); ?> /> <?php _e('Servings', 'recipe-press'); ?></label></td>
                                         
                                        </tr>
                                        <tr>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="ingredients" <?php $this->checked($this->options['required-fields'], 'ingredients'); ?> /> <?php _e('Ingredients', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="instructions" <?php $this->checked($this->options['required-fields'], 'instructions'); ?> /> <?php _e('Directions', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="nutrient_per_serving" <?php $this->checked($this->options['required-fields'], 'nutrient_per_serving'); ?> /> <?php _e('Servings', 'recipe-press'); ?></label></td>
                                        </tr>
                                        <tr>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="subtitle" <?php $this->checked($this->options['required-fields'], 'subtitle'); ?> /> <?php _e('Description', 'recipe-press'); ?> </label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="photo_credit" <?php $this->checked($this->options['required-fields'], 'photo_credit'); ?> /> <?php _e('Photo Credit', 'recipe-press'); ?></label></td>
                                             <td></td>
                                        </tr>
                                   </tbody>
                              </table>

                         </td>
                    </tr>
                    
                    </tbody>
               </table>
          </div>
     </div>
