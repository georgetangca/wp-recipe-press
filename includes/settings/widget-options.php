<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * widget-settings.php - View for the Widget Settings box.
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
               <?php _e('Widget Settings', 'recipe-press'); ?>
          </h3>
          <table class="form-table">
               <tr align="top">
                    <th scope="row"><label for="recipe_press_widget_items"><?php _e('Default Items to Display', 'recipe-press'); ?></label></th>
                    <td>
                         <select name="<?php echo $this->optionsName; ?>[widget-items]" id="recipe_press_widget_items">
                              <?php
                              for ( $i = 1; $i <= 20; ++$i ) echo "<option value='$i' " . selected($this->options['widget-items'], $i) . ">$i</option>";
                              ?>
                         </select>
                         <?php $this->help(__('Default for new widgets.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_widget_type"><?php _e('Default List Widget Type', 'recipe-press'); ?></label></th>
                         <td>
                              <select name="<?php echo $this->optionsName; ?>[widget-type]" id="recipe_press_widget_type">
                                   <option value="newest" <?php selected($this->options['widget-type'], 'newest'); ?> ><?php _e('Newest Recipes', 'recipe-press'); ?></option>
                                   <option value="random" <?php selected($this->options['widget-type'], 'random'); ?> ><?php _e('Random Recipes', 'recipe-press'); ?></option>
                                   <option value="popular" <?php selected($this->options['widget-type'], 'popular'); ?> ><?php _e('Most Popular', 'recipe-press'); ?></option>
                                   <option value="featured" <?php selected($this->options['widget-type'], 'featured'); ?> ><?php _e('Featured', 'recipe-press'); ?></option>
                                   <option value="updated" <?php selected($this->options['widget-type'], 'updated'); ?> ><?php _e('Recently Updated', 'recipe-press'); ?></option>
                              </select>
                         <?php $this->help(__('Default link target when adding a new widget.', 'recipe-press')); ?>
                         </td>
                    </tr>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_widget_sort"><?php _e('Default List Widget Sort', 'recipe-press'); ?></label></th>
                         <td>
                              <select name="<?php echo $this->optionsName; ?>[widget-sort]" id="recipe_press_widget_sort">
                                   <option value="asc" <?php selected($this->options['widget-sort'], 'asc'); ?> ><?php _e('Ascending', 'recipe-press'); ?></option>
                                   <option value="desc" <?php selected($this->options['widget-sort'], 'desc'); ?> ><?php _e('Descending', 'recipe-press'); ?></option>
                              </select>
                         <?php $this->help(__('Default link target when adding a new widget.', 'recipe-press')); ?>
                         </td>
                    </tr>

                    <tr align="top">
                         <th scope="row"><label for="recipe_press_widget_target"><?php _e('Default Link Target', 'recipe-press'); ?></label></th>
                         <td>
                              <select name="<?php echo $this->optionsName; ?>[widget-target]" id="recipe_press_widget_target">
                                   <option value="0">None</option>
                                   <option value="_blank" <?php selected(isset($this->widgetTarget), '_blank'); ?>>New Window</option>
                                   <option value="_top" <?php selected(isset($this->widgetTarget), '_top'); ?>>Top Window</option>
                              </select>
                         <?php $this->help(__('Default link target when adding a new widget.', 'recipe-press')); ?>
                         </td>
                    </tr>

                    <tr valign="top">
                         <th scope="row"><label for="recipe_press_widget_show_icon"><?php _e('Show Icons?', 'recipe-press'); ?></label></th>
                         <td>
                              <input type="checkbox" name="<?php echo $this->optionsName; ?>[widget-show-icon]" id="recipe_press_widget_show_icon" value="1" <?php checked($this->options['widget-show-icon'], 1); ?> />
                         <?php $this->help(__('Check this option to show icons in widgets by default, can be turned of individually in each widget instance.', 'recipe-press')); ?>
                         </td>

                    </tr>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_widget_icon_size"><?php _e('Widget Icon Size', 'recipe-press'); ?></label></th>
                         <td>
                              <input type="input" name="<?php echo $this->optionsName; ?>[widget-icon-size]" id="recipe_press_widget_icon_size" value="<?php echo $this->options['widget-icon-size']; ?>" />
                         <?php $this->help(__('Default icon size for widgets, can be changed in each widget instance.', 'recipe-press')); ?>
                    </td>
               </tr>

          </table>
     </div>
</div>


               <div class="postbox" style="float: right; width: 49%">
                    <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Instructions', 'recipe-press'); ?></h3>
     <div style="padding:8px">
          <p>Coming Soon.</p>
     </div>
</div>