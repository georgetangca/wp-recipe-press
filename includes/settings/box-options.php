<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * box-options.php - View for the Recipe Box Options tab
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 2.2
 */
?>

<div class="postbox" style="float: left; width: 49%">
     <div class="table recipe-press-settings-table">
          <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
               <?php _e('Recipe Box Options', 'recipe-press'); ?>
          </h3>
          <table id="image_size_table" class="form-table cp-table">
               <tbody>
                    <tr valign="top">
                         <th scope="row"><label for="recipe_press_use_recipe_box"><?php _e('Recipe Box is', 'recipe-press'); ?></label></th>
                         <td>
                              <label><input type="radio" name="<?php echo $this->optionsName; ?>[use-recipe-box]" value="1" <?php checked($this->options['use-recipe-box'], 1); ?> /> <?php _e('Enabled', 'recipe-press'); ?></label>
                              <label><input type="radio" name="<?php echo $this->optionsName; ?>[use-recipe-box]" value="0" <?php checked($this->options['use-recipe-box'], 0); ?> /> <?php _e('Disabled', 'recipe-press'); ?></label>
                         </td>
                    </tr>
                    <tr valign="top">
                         <th scope="row"><label for="recipe_press_box_slug"><?php _e('URL Slug', 'recipe-press'); ?></label></th>
                         <td>
                              <input name="<?php echo $this->optionsName; ?>[recipe-box-slug]" id="recipe_press_box_slug" type="text" value="<?php echo $this->options['recipe-box-slug']; ?>" />
                              <?php $this->help(__('This slug will be used to create the URL for the recipe box option.', 'recipe-press')); ?>
                              <a href="<?php echo get_option('home'); ?>/<?php echo $this->options['recipe-box-slug']; ?>"><?php _e('View on Site', 'recipe-press'); ?></a>
                         </td>
                    </tr>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_box_page"><?php _e('Recipe Box Page', 'recipe-press'); ?></label></th>
                         <td>
                              <?php wp_dropdown_pages(array('name' => $this->optionsName . '[recipe-box-page]', 'show_option_none' => __('None Selected', 'recipe-press'), 'selected' => $this->options['recipe-box-page'])); ?>
                              <?php $this->help(sprintf(__('Select a page to display the Recipe Box on if there is no template file for it. You must include the [%1$s] shortcode on this page.', 'recipe-press'), 'recipe-box')); ?>
                         </td>
                    </tr>
                    <tr valign="top">
                         <th scope="row"><label for="recipe_press_box_title"><?php _e('Box Title', 'recipe-press'); ?></label></th>
                         <td>
                              <input name="<?php echo $this->optionsName; ?>[recipe-box-title]" id="recipe_press_box_title" type="text" value="<?php echo $this->options['recipe-box-title']; ?>" />
                              <?php $this->help(__('This will be displayed at the top right of the recipe box.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    <tr valign="top">
                         <th scope="row"><label for="recipe_press_box_add_title"><?php _e('Add to box link', 'recipe-press'); ?></label></th>
                         <td>
                              <input name="<?php echo $this->optionsName; ?>[recipe-box-add-title]" id="recipe_press_box_add_title" type="text" value="<?php echo $this->options['recipe-box-add-title']; ?>" />
                              <?php $this->help(__('The title to use in the recipe controls to add a recipe to the Recipe Box.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    <tr valign="top">
                         <th scope="row"><label for="recipe_press_box_view-title"><?php _e('View box link', 'recipe-press'); ?></label></th>
                         <td>
                              <input name="<?php echo $this->optionsName; ?>[recipe-box-view-title]" id="recipe_press_box_view" type="text" value="<?php echo $this->options['recipe-box-view-title']; ?>" />
                              <?php $this->help(__('The title to use for the link to the View Recipe Box.', 'recipe-press')); ?>
                         </td>
                    </tr>
               </tbody>
          </table>
     </div>
</div>