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
                         <th scope="row"><label for="recipe_press_form_page"><?php _e('Default form page', 'recipe-press'); ?></label></th>
                         <td>
                              <?php wp_dropdown_pages(array('name' => $this->optionsName . '[form-page]', 'show_option_none' => __('No Default', 'recipe-press'), 'selected' => (isset($this->options['form-page'])) ? $this->options['form-page'] : false)); ?>
                              <?php $this->help(__('If a page is selected, all links to the submit form created by the plugin will direct to this page. The form will not automatically be added to this page.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_form_redirect"><?php _e('Redirect Page', 'recipe-press'); ?></label></th>
                         <td>
                              <?php wp_dropdown_pages(array('name' => $this->optionsName . '[form-redirect]', 'show_option_none' => __('Home Page', 'recipe-press'), 'selected' => (isset($this->options['form-redirect'])) ? $this->options['form-redirect'] : false)); ?>
                              <?php $this->help(__('What page to redirect a visitor to after a recipe has been submitted successfully.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_use_form"><?php _e('Use public submit form?', 'recipe-press'); ?></label></th>
                         <td>
                              <input type="checkbox" name="<?php echo $this->optionsName; ?>[use-form]" id="recipe_press_use_form" value="1" <?php checked($this->options['use-form'], 1); ?> />
                              <?php $this->help(__('If checked, allows users to submit recipes from the front end.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_require_login"><?php _e('Require login for submit?', 'recipe-press'); ?></label></th>
                         <td>
                              <input type="checkbox" name="<?php echo $this->optionsName; ?>[require-login]" id="recipe_press_require_login" value="1" <?php checked($this->options['require-login'], 1); ?> />
                              <?php $this->help(__('If checked, users must log in to submit a recipe.', 'recipe-press')); ?>
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
                         <th scope="row"><?php _e('Required Form Fields', 'recipe-press'); ?></th>
                         <td>
                              <table border="0">
                                   <tbody>
                                        <tr>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="title" <?php $this->checked($this->options['required-fields'], 'title'); ?> /> <?php _e('Title', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="notes" <?php $this->checked($this->options['required-fields'], 'notes'); ?> /> <?php _e('Notes', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="recipe-category" <?php $this->checked($this->options['required-fields'], 'recipe-category'); ?> /> <?php _e('Category', 'recipe-press'); ?></label></td>
                                        </tr>
                                        <tr>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="servings" <?php $this->checked($this->options['required-fields'], 'servings'); ?> /> <?php _e('Servings', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="prep_time" <?php $this->checked($this->options['required-fields'], 'prep_time'); ?> /> <?php _e('Prep Time', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="cook_time" <?php $this->checked($this->options['required-fields'], 'cook_time'); ?> /> <?php _e('Cook Time', 'recipe-press'); ?></label></td>
                                        </tr>
                                        <tr>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="instructions" <?php $this->checked($this->options['required-fields'], 'instructions'); ?> /> <?php _e('Instructions', 'recipe-press'); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="submitter" <?php $this->checked($this->options['required-fields'], 'submitter'); ?> /> <?php _e('Name', 'recipe-press'); ?> <?php $this->help(__('Only displayed when a user is not logged in', 'recipe-press')); ?></label></td>
                                             <td><label><input type="checkbox" name="<?php echo $this->optionsName; ?>[required-fields][]" value="submitter_email" <?php $this->checked($this->options['required-fields'], 'submitter_email'); ?> /> <?php _e('Email', 'recipe-press'); ?> <?php $this->help(__('Only displayed when a user is not logged in', 'recipe-press')); ?></label></td>
                                        </tr>
                                   </tbody>
                              </table>

                         </td>
                    </tr>
                    <tr>
                         <td colspan="2">
                              <h4 class="handl" style="margin:0;padding:3px;cursor:default;">
                                   <?php _e('reCaptcha Settings', 'recipe-press'); ?>
                              </h4>
                              <?php _e('To use reCaptcha on the public form, enter your public and private keys here.', 'recipe-press'); ?>
                                   <a href="<?php echo recaptcha_get_signup_url(eregi_replace('http://', '', get_option('siteurl')), 'RecipePress for WordPress'); ?>" target="_blank"><?php _e('Get Keys', 'recipe-press'); ?></a>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_force_recaptcha"><?php _e('Force for All Users?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input type="checkbox" name="<?php echo $this->optionsName; ?>[force-recaptcha]" id="recipe_press_force_recaptcha" value="1" <?php checked(isset($this->options['force-recaptcha']), 1); ?> />
                              <?php $this->help(__('If checked, logged in users will see the reCaptcha.', 'recipe-press')); ?>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_recaptcha_public"><?php _e('Public Key', 'recipe-press'); ?></label></th>
                              <td><input type="text" name="<?php echo $this->optionsName; ?>[recaptcha-public]" id="recipe_press_recaptcha_public" value="<?php echo isset($this->options['recaptcha-public']) ? $this->options['recaptcha-public'] : ''; ?>" /></td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_recaptcha_private"><?php _e('Private Key', 'recipe-press'); ?></label></th>
                              <td><input type="text" name="<?php echo $this->optionsName; ?>[recaptcha-private]" id="recipe_press_recaptcha_private" value="<?php echo isset($this->options['recaptcha-private']) ? $this->options['recaptcha-private'] : ''; ?>" /></td>
                         </tr>
                    </tbody>
               </table>
          </div>
     </div>


     <div class="postbox" style="float: right; width: 49%">
          <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Instructions', 'recipe-press'); ?></h3>
     <div style="padding:8px">
          <p>Coming Soon.</p>
     </div>
</div>