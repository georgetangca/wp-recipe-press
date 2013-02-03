<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * display-options.php - View for the Display Options box.
 *
 * @package RecipePress
 * @subpackage includes/settings
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
global $menu;
?>

<div class="postbox" style="float: left; width: 49%">
     <div class="table recipe-press-settings-table">
          <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
               <?php _e('Display Settings', 'recipe-press'); ?>
          </h3>
          <table class="form-table">
               <tbody>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_menu_position"><?php _e('Display in menu after', 'recipe-press'); ?></label></th>
                         <td>
                              <select name="<?php echo $this->optionsName; ?>[menu-position]" id="recipe_press_menu_position">
                                   <?php foreach ( $menu as $id => $value ) : if ( !empty($value[0]) and $value[5] != 'menu-posts-recipe' ) : ?>
                                             <option value="<?php echo $id; ?>" <?php selected($this->options['menu-position'], $id); ?> ><?php echo $value[0]; ?></option>

                                   <?php endif;
                                        endforeach; ?>
                                   </select>
                              <?php $this->help(__('Select where in the WordPress menu you want the Recipes menu to appear.', 'recipe-press')); ?>
                                   </td>
                              </tr>
                              <tr align="top">
                                   <th scope="row"><label for="recipe_press_add_to_author_list"><?php _e('Add recipes to author lists?', 'recipe-press'); ?></label></th>
                                   <td>
                                        <input name="<?php echo $this->optionsName; ?>[add-to-author-list]" id="recipe_press_add_to_author_list" type="checkbox" value="1" <?php checked($this->options['add-to-author-list'], 1); ?> />
                              <?php $this->help(__('Click this option to include the recipes by each author in their respective post list.', 'recipe-press')); ?>
                                   </td>
                              </tr>
                              <tr align="top">
                                   <th scope="row"><label for="recipe_press_default_excerpt_length"><?php _e('Default Excerpt Length', 'recipe-press'); ?></label></th>
                                   <td>
                                        <input type="text" name="<?php echo $this->optionsName; ?>[default-excerpt-length]" id="recipe_press_default_excerpt_length" value="<?php echo $this->options['default-excerpt-length']; ?>" />
                              <?php $this->help(esc_js(__('Default length of introduction excerpt when displaying in lists.', 'recipe-press'))); ?>
                                   </td>
                              </tr>
                              <tr align="top">
                                   <th scope="row"><label for="recipe_press_recipe_count"><?php _e('Recipes to display', 'recipe-press'); ?></label></th>
                                   <td>
                                        <select id="recipe_press_recipe_count" name="<?php echo $this->optionsName; ?>[recipe-count]">
                                             <option value="default" <?php selected($this->options['recipe-count'], 'default'); ?>><?php _e('Use default reading setting.', 'recipe-press'); ?></option>
                                   <?php for ( $count = 1; $count <= 25; ++$count ) : ?>
                                             <option value="<?php echo $count; ?>" <?php selected($this->options['recipe-count'], $count); ?>><?php echo $count; ?></option>

                                   <?php endfor; ?>
                                        </select>
                              <?php $this->help(esc_js(__('How many recipes to display per page on the listing pages.', 'recipe-press'))); ?>
                                        </td>
                                   </tr>
                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_recipe_orderby"><?php _e('Recipes Sort Field', 'recipe-press'); ?></label></th>
                                        <td>
                                             <select id="recipe_press_recipe_orderby" name="<?php echo $this->optionsName; ?>[recipe-orderby]">
                                                  <option value="date" <?php selected($this->options['recipe-orderby'], 'date'); ?>><?php _e('Date', 'recipe-press'); ?></option>
                                                  <option value="title" <?php selected($this->options['recipe-orderby'], 'title'); ?>><?php _e('Title', 'recipe-press'); ?></option>
                                                  <option value="rand" <?php selected($this->options['recipe-orderby'], 'rand'); ?>><?php _e('Random', 'recipe-press'); ?></option>
                                                  <option value="comment_count" <?php selected($this->options['recipe-orderby'], 'comment_count'); ?>><?php _e('Comment Count', 'recipe-press'); ?></option>
                                                  <option value="menu_order" <?php selected($this->options['recipe-orderby'], 'menu_order'); ?>><?php _e('Menu Order', 'recipe-press'); ?></option>

                                             </select>
                                             <select id="recipe_press_recipe_order" name="<?php echo $this->optionsName; ?>[recipe-order]">
                                                  <option value="asc" <?php selected($this->options['recipe-order'], 'asc'); ?>><?php _e('Ascending', 'recipe-press'); ?></option>
                                                  <option value="desc" <?php selected($this->options['recipe-order'], 'desc'); ?>><?php _e('Descending', 'recipe-press'); ?></option>
                                             </select>
                              <?php $this->help(esc_js(__('The listing order of recipes on the index page.', 'recipe-press'))); ?>
                                        </td>
                                   </tr>
                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_custom_css"><?php _e('Use Plugin CSS?', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input name="<?php echo $this->optionsName; ?>[custom-css]" id="recipe_press_custom_css" type="checkbox" value="1" <?php checked($this->options['custom-css'], 1); ?> />
                              <?php $this->help(__('Click this option to include the CSS from the plugin.', 'recipe-press')); ?>
                                        </td>
                                   </tr>
                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_disable_filter"><?php _e('Disable Content Filtering?', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input name="<?php echo $this->optionsName; ?>[disable-content-filter]" id="recipe_press_disable_filter" type="checkbox" value="1" <?php checked($this->options['disable-content-filter'], 1); ?> />
                              <?php $this->help(__('Click this option to completely disable any content filtering. Warning! Only do this if you have created template files and are having an issue with template display.', 'recipe-press')); ?>
                                        </td>
                                   </tr>
                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_time_display_type"><?php _e('Default time display', 'recipe-press'); ?></label></th>
                                        <td>
                                             <select name="<?php echo $this->optionsName; ?>[time-display-type]" id="recipe_press_time_display_type">
                                                  <option value="single" <?php selected($this->options['time-display-type'], 'single'); ?> ><?php _e('Single Line', 'recipe-press'); ?></option>
                                                  <option value="double" <?php selected($this->options['time-display-type'], 'double'); ?> ><?php _e('Two Line', 'recipe-press'); ?></option>
                                             </select>
                                        </td>
                                   </tr>
                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_hours_text"><?php _e('"Hours" Text', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input type="text" name="<?php echo $this->optionsName; ?>[hour-text]" id="recipe_press_hours_text" value="<?php echo $this->options['hour-text']; ?>" />
                              <?php $this->help(__('This is the text that will be used in the ready time box if more than 60 minutes is required. Leave as a singular word.', 'recipe-press')); ?>
                                        </td>
                                   </tr>
                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_minute_text"><?php _e('"Minutes" Text', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input type="text" name="<?php echo $this->optionsName; ?>[minute-text]" id="recipe_press_minute_text" value="<?php echo $this->options['minute-text']; ?>" />
                              <?php $this->help(__('This is the text that will be used in the ready time box if more than 60 minutes is required. Leave as a singular word.', 'recipe-press')); ?>
                                        </td>
                                   </tr>
                              </tbody>
                         </table>
                    </div>
               </div>
