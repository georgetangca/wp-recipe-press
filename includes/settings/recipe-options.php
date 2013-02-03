<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * recipe-options.php - View for the Recipe Options box.
 *
 * @package RecipePress
 * @subpackage includes/settings
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
global $wp_version;

if ( version_compare($wp_version, '3.1', '>=') and !$this->options['use-plugin-permalinks'] ) {
     $display_permalinks = 'none';
} else {
     $display_permalinks = 'table-row';
}
?>

<div class="postbox" style="float: left; width: 49%">
     <div class="table recipe-press-settings-table">
          <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
               <?php _e('Recipe Options', 'recipe-press'); ?>
          </h3>
          <table class="form-table">
               <tbody>
                    <tr align="top">
                         <th scope="row"><label for="recipe_press_index_slug"><?php _e('Index Slug', 'recipe-press'); ?></label></th>
                         <td colspan="3">
                              <input type="text" name="<?php echo $this->optionsName; ?>[index-slug]" id="recipe_press_index_slug" value="<?php echo $this->options['index-slug']; ?>" />
                              <?php $this->help(esc_js(__('This will be used as the slug (URL) for the recipe index pages.', 'recipe-press'))); ?>
                              <a href="<?php echo get_option('home'); ?>/<?php echo $this->options['index-slug']; ?>"><?php _e('View on Site', 'recipe-press'); ?></a>
                         </td>
                    </tr>
                    <?php if ( version_compare($wp_version, '3.1', '>=') ) : ?>

                                   <tr align="top">
                                        <th scope="row"><label for="recipe_press_use_plugin_permalinks"><?php _e('Use plugin permalinks?', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input name="<?php echo $this->optionsName; ?>[use-plugin-permalinks]" id="recipe_press_use_plugin_permalinks" type="checkbox" value="1" <?php checked($this->options['use-plugin-permalinks'], 1); ?> onclick="recipe_press_show_permalinks(this)" />
                              <?php $this->help(__('Wordpress 3.1+ has a feature to list recipes on an index page. If you prefer to use your own permalink structure, check this box and the plugin will use the settings below.', 'recipe-press')); ?>
                              </td>
                         </tr>
                    <?php endif; ?>

                                   <tr id="recipe_press_identifier_row" align="top" style="display:<?php echo $display_permalinks; ?>">
                                        <th scope="row"><label for="recipe_press_identifier"><?php _e('Identifier', 'recipe-press'); ?></label></th>
                                        <td colspan="3">
                                             <input type="text" name="<?php echo $this->optionsName; ?>[identifier]" id="recipe_press_identifier" value="<?php echo $this->options['identifier']; ?>" />
                              <?php $this->help(esc_js(__('This will be used in the permalink structure to identify the custom type for recipes.', 'recipe-press'))); ?>
                              </td>
                         </tr>
                         <tr id="recipe_press_permalink_row" align="top" style="display:<?php echo $display_permalinks; ?>">
                              <th scope="row"><label for="recipe_press_permalink"><?php _e('Permalink Structure:', 'recipe-press'); ?></label></th>
                              <td colspan="3">
                                   <input class="widefat" type="text" name="<?php echo $this->optionsName; ?>[permalink]" id="recipe_press_permalink" value="<?php echo $this->options['permalink']; ?>" />
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_plural_name"><?php _e('Plural Name', 'recipe-press'); ?></label></th>
                              <td colspan="3">
                                   <input type="text" name="<?php echo $this->optionsName; ?>[plural-name]" id="recipe_press_plural_name" value="<?php echo $this->options['plural-name']; ?>" />
                              <?php $this->help(esc_js(__('Plural name to use in the menus for this plugin.', 'recipe-press'))); ?>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_singular_name"><?php _e('Singular Name', 'recipe-press'); ?></label></th>
                              <td colspan="3">
                                   <input type="text" name="<?php echo $this->optionsName; ?>[singular-name]" id="recipe_press_singular_name" value="<?php echo $this->options['singular-name']; ?>" />
                              <?php $this->help(esc_js(__('Singular name to use in the menus for this plugin.', 'recipe-press'))); ?>
                              </td>
                         </tr>

                         <tr align="top" style="display: none;"> <!--george hidden here -->
                              <th scope="row"><label for="recipe_press_use_taxonomies"><?php _e('Use Taxonomies?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-taxonomies]" id="recipe_press_use_taxonomies" type="checkbox" value="1" <?php checked($this->options['use-taxonomies'], 1); ?> />
                              <?php $this->help(__('Click this option to include the Taxonomies feature.', 'recipe-press')); ?>
                              </td>
                              <th scope="row" colspan="2"><a href="#" onclick="recipe_press_show_tab('taxonomies')"><?php _e('Manage Taxonomies', 'recipe-press'); ?></a></th>

                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_use_servings"><?php _e('Use Servings?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-servings]" id="recipe_press_use_servings" type="checkbox" value="1" <?php checked($this->options['use-servings'], 1); ?> />
                              <?php $this->help(__('Click this option to include the servings feature.', 'recipe-press')); ?>
                              </td>
                              <th scope="row"><label for="recipe_press_use_times"><?php _e('Use Times?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-times]" id="recipe_press_use_times" type="checkbox" value="1" <?php checked($this->options['use-times'], 1); ?> />
                              <?php $this->help(__('Click this option to include the prep and cook time feature.', 'recipe-press')); ?>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_use_thumbnails"><?php _e('Use Thumbnails?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-thumbnails]" id="recipe_press_use_thumbnails" type="checkbox" value="1" <?php checked($this->options['use-thumbnails'], 1); ?> />
                              <?php $this->help(__('Click this option to include the thumbnails feature.', 'recipe-press')); ?>
                              </td>
                              <th scope="row"><label for="recipe_press_use_featured"><?php _e('Use Featured?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-featured]" id="recipe_press_use_featured" type="checkbox" value="1" <?php checked($this->options['use-featured'], 1); ?> />
                              <?php $this->help(__('Click this option to include the featured recipes feature.', 'recipe-press')); ?>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_use_comments"><?php _e('Use Comments?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-comments]" id="recipe_press_use_comments" type="checkbox" value="1" <?php checked($this->options['use-comments'], 1); ?> />
                              <?php $this->help(__('Click this option to include the comments feature.', 'recipe-press')); ?>
                              </td>
                              <th scope="row"><label for="recipe_press_use_trackbacks"><?php _e('Use Trackbacks?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-trackbacks]" id="recipe_press_use_trackbacks" type="checkbox" value="1" <?php checked($this->options['use-trackbacks'], 1); ?> />
                              <?php $this->help(__('Click this option to include the trackback feature.', 'recipe-press')); ?>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="recipe_press_use_revisions"><?php _e('Use Revisions?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-revisions]" id="recipe_press_use_revisions" type="checkbox" value="1" <?php checked($this->options['use-revisions'], 1); ?> />
                              <?php $this->help(__('Click this option to include the revisions feature.', 'recipe-press')); ?>
                              </td>
                              <th scope="row"><label for="recipe_press_use_nutritional_value"><?php _e('Use Nutritional Value?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-nutritional-value]" id="recipe_press_use_nutritional_value" type="checkbox" value="1" <?php checked($this->options['use-nutritional-value'], 1); ?> />
                              <?php $this->help(__('Click this option to include the nutritional value feature.', 'recipe-press')); ?>
                              </td>
                         </tr>              
                         
                         <tr align="top" style="display: none;">
                              <th scope="row" colspan="4"><?php _e('These two use the built in category and post tags used in standard posts.', 'recipe_press', 'recipe-press'); ?></th>
                         </tr>
                         <tr align="top" style="display: none;">
                              <th scope="row"><label for="recipe_press_use_post_categories"><?php _e('Use Post Categories?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-post-categories]" id="recipe_press_use_post_categories" type="checkbox" value="1" <?php checked($this->options['use-post-categories'], 1); ?> />
                              <?php $this->help(__('Click this option to include the post categories feature.', 'recipe-press')); ?>
                              </td>
                              <th scope="row"><label for="recipe_press_use_post_tags"><?php _e('Use Post Tags?', 'recipe-press'); ?></label></th>
                              <td>
                                   <input name="<?php echo $this->optionsName; ?>[use-post-tags]" id="recipe_press_use_post_tags" type="checkbox" value="1" <?php checked($this->options['use-post-tags'], 1); ?> />
                              <?php $this->help(__('Click this option to include the post tags feature.', 'recipe-press')); ?>
                         </td>
                    </tr>
               </tbody>
          </table>
     </div>
</div>

<div class="postbox" style="width: 49%; float: right;">
          <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Permalink Instructions', 'recipe-press'); ?></h3>
          <div style="padding:8px;">
               <p>
                    <?php
                    printf(__('The permalink structure will be used to create the custom URL structure for your individual recipes. These follow WP\'s normal %1$s, but must also include the content type %2$s and at least one of these unique tags: %3$s or %4$s.', 'recipe-press'),
                            '<a href="http://codex.wordpress.org/Using_Permalinks" target="_blank">' . __('permalink tags', 'recipe-press') . '</a>',
                            '<strong>%identifier%</strong>',
                            '<strong>%postname%</strong>',
                            '<strong>%post_id%</strong>'
                    );
                    ?>
               </p>
               <p>
                    <?php _e('Allowed tags: ', 'recipe-press'); ?>
                    %year%, %monthnum%, %day%, %hour%, %minute%, %second%, %postname%, %post_id%
               </p>
               <p>
                    <?php
                    printf(__('For complete instructions on how to set up your permaliks, visit the %1$s.', 'recipe-press'),
                            '<a href="http://wiki.recipepress.net/wiki/Recipe_Permalinks" target="blank">' . __('Documentation Page', 'recipe-press') . '</a>'
                    );
                    ?>
               </p>
          </div>
     </div>