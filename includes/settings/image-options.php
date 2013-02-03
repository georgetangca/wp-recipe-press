<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * image-options.php - View for the administration tab.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 2.1.1
 */
?>

<div class="postbox" style="float: left; width: 49%">
     <div class="table recipe-press-settings-table">
          <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
               <?php _e('Image Options', 'recipe-press'); ?>
          </h3>
          <table id="image_size_table" class="form-table cp-table">
               <tbody>
                    <tr valign="top">
                         <td colspan="5">
                              <p>
                                   <?php printf(__('This page allows you to manage the built in image sizes used for recipes. Image sizes lised below will be prefixed with "%1$s" when they are registered with WordPress.', 'recipe-press'), 'recipe-press'); ?>
                              </p>
                              <p>
                                   <?php _e('Please note that you will not be able to change the name or slug for the built in sizes.', 'recipe-press'); ?>
                              </p>
                              <p>
                                   <strong><?php _e('Warning', 'recipe-press'); ?>: </strong>
                                   <?php
                                   printf(__('If you change the sizes of the images on this page, your existing images will not automatically be resized. I suggest using the %1$s plugin by %2$s which will recreate all the image sizes. This only needs to be done if you change these image sizes.', 'recipe-press'),
                                           '<a href="http://wordpress.org/extend/plugins/regenerate-thumbnails/" target="_blank">' . __('Regenerate Thumbnails', 'pretty-sidebar-categories') . '</a>',
                                           '<a href="http://profiles.wordpress.org/users/Viper007Bond/" target="_blank">Viper007Bond</a>'
                                   );
                                   ?>
                              </p>
                         </td>
                    </tr>
                    <tr valign="top">
                         <th valign="top" scope="row"><?php _e('Image Name', 'recipe-press'); ?></th>
                         <th valign="top" scope="row"><?php _e('Slug', 'recipe-press'); ?></th>
                         <th valign="top" scope="row"><?php _e('Width', 'recipe-press'); ?></th>
                         <th valign="top" scope="row"><?php _e('Height', 'recipe-press'); ?></th>
                         <th valign="top" scope="row"><?php _e('Mode', 'recipe-press'); ?></th>
                    </tr>
                    <?php foreach ( $this->options['image-sizes'] as $name => $size ) : ?>
                                        <tr id="rp_image_size_<?php echo $name; ?>" valign="top">
                                             <th valign="top" scope="row">
                              <?php if ( $size['builtin'] ) : ?>
                                             <label for="image_width_<?php echo $name; ?>">
                                   <?php echo $size['name']; ?>
                                        </label>
                                        <input class="recipe-press-image-input recipe-press-image-name" type="hidden" name="<?php print $this->optionsName; ?>[image-sizes][<?php echo $name; ?>][name]" id="image_name_<?php echo $name; ?>" value="<?php echo $size['name']; ?>" />
                              <?php else : ?>
                                                  <input type="text" name="<?php print $this->optionsName; ?>[image-sizes][<?php echo $name; ?>][name]" id="image_name_<?php echo $name; ?>" value="<?php echo $size['name']; ?>" />
                              <?php endif; ?>
                                             </th>
                                             <td>
                                                  <input class="recipe-press-image-input recipe-press-image-slug" type="text" name="<?php print $this->optionsName; ?>[image-sizes][<?php echo $name; ?>][slug]" id="image_slug_<?php echo $name; ?>" value="<?php echo $name; ?>" onkeyup="change_image_slug('<?php echo $name; ?>', this)" <?php disabled($size['builtin'], true); ?> />
                                                  <input type="hidden" name="<?php print $this->optionsName; ?>[image-sizes][<?php echo $name; ?>][builtin]" id="image_builtin_<?php echo $name; ?>" value="<?php echo $size['builtin']; ?>" />
                                             </td>
                                             <td>
                                                  <input class="recipe-press-image-input recipe-press-image-width" type="text" name="<?php print $this->optionsName; ?>[image-sizes][<?php echo $name; ?>][width]" id="image_width_<?php echo $name; ?>" value="<?php echo $size['width']; ?>" />
                                             </td>
                                             <td>
                                                  <input class="recipe-press-image-input recipe-press-image-height" type="text" name="<?php print $this->optionsName; ?>[image-sizes][<?php echo $name; ?>][height]" id="image_height_<?php echo $name; ?>" value="<?php echo $size['height']; ?>" />
                                             </td>
                                             <td>
                                                  <select id="image_crop_<?php echo $name; ?>" name="<?php print $this->optionsName; ?>[image-sizes][<?php echo $name; ?>][crop]">
                                                       <option value="1" <?php selected($size['crop'], 1); ?>><?php _e('Hard Crop', 'recipe-press'); ?></option>
                                                       <option value="0" <?php selected($size['crop'], 0); ?>><?php _e('Proportional', 'recipe-press'); ?></option>
                                                  </select>
                                             </td>
                                        </tr>
                    <?php endforeach; ?>
                                             </tbody>
                                        </table>
                                        <input type="button" class="recipe-press-button" value="<?php _e('Add Size', 'recipe-press'); ?>" name="add-button" onclick="add_image_size()" />
                                   </div>
                              </div>


                       