<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * taxonomies-options.php - View for the Recipe Options box.
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
               <?php _e('Taxonomies', 'recipe-press'); ?>
          </h3>

          <ul id="recipe_press_taxonomy_tabs">
               <?php foreach ( $this->options['taxonomies'] as $key => $taxonomy ) : $taxonomy = $this->taxDefaults($taxonomy); ?>

               <?php
                    if ( !isset($selectedTax) ) {
                         $selectedTax = $key;
                    }
               ?>
                    <li id="recipe_press_taxonomy_tab_<?php echo $key; ?>" class="recipe-press<?php echo ($selectedTax == $key) ? '-selected' : ''; ?>">
                         <a href="#top" onclick="recipe_press_show_tax('<?php echo $key; ?>')"><?php echo $taxonomy['plural']; ?></a>
                    </li>
               <?php endforeach; ?>
                    <li id="recipe_press_taxonomy_tab_ingredients" class="recipe-press<?php echo ($selectedTax == 'ingredients') ? '-selected' : ''; ?>"">
                        <a href="#top" onclick="recipe_press_show_tax('ingredients')"><?php _e('Ingredients', 'recipe-press'); ?></a>
                    </li>
                    <li id="recipe_press_taxonomy_tab_new" class="recipe-press">
                         <a href="#top" onclick="recipe_press_show_tax('new')"><?php _e('New Taxonomy', 'recipe-press'); ?></a>
                    </li>
               </ul>

               <div class="table">
               <?php foreach ( $this->options['taxonomies'] as $key => $taxonomy ) : $taxonomy = $this->taxDefaults($taxonomy); ?>
                         <table  id="recipe_press_taxonomy_<?php echo $key; ?>" class="form-table" style="display: <?php echo ($selectedTax == $key) ? '' : 'none'; ?>">
                              <tbody>
                                   <tr align="top">
                                        <th scope="row"><label for="<?php echo $key; ?>_slug"><?php _e('URL Slug', 'recipe-press'); ?></label></th>
                                        <td>
                                             <input type="text" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][slug]" id="<?php echo $key; ?>_slug" value="<?php echo isset($taxonomy['slug']) ? $taxonomy['slug'] : $key; ?>" />
                                   <?php $this->help(esc_js(sprintf(__('The URL slug to use for listing all of the items in "%1$s".', 'recipe-press'), isset($taxonomy['plural']) ? $taxonomy['plural'] : ''))); ?>
                                   <a href="<?php echo get_option('home'); ?>/<?php echo $taxonomy['slug']; ?>"><?php _e('View on Site', 'recipe-press'); ?></a>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="<?php echo $key; ?>_plural_name"><?php _e('Plural Name', 'recipe-press'); ?></label></th>
                              <td>
                                   <input type="text" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][plural]" id="<?php echo $key; ?>_plural_name" value="<?php echo isset($taxonomy['plural']) ? $taxonomy['plural'] : ''; ?>" />
                                   <?php $this->help(esc_js(sprintf(__('Plural name to use in the menus for this plugin for the taxonomy "%1$s".', 'recipe-press'), isset($taxonomy['plural']) ? $taxonomy['plural'] : ''))); ?>
                              </td>
                         </tr>
                         <tr align="top">
                              <th scope="row"><label for="<?php echo $key; ?>_singular_name"><?php _e('Singular Name', 'recipe-press'); ?></label></th>
                              <td>
                                   <input type="text" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][singular]" id="<?php echo $key; ?>_singular_name" value="<?php echo isset($taxonomy['singular']) ? $taxonomy['singular'] : ''; ?>" />
                                   <?php $this->help(esc_js(sprintf(__('Singular name to use in the menus for this plugin for the taxonomy "%1$s".', 'recipe-press'), isset($taxonomy['singular']) ? $taxonomy['singular'] : ''))); ?>
                              </td>
                         </tr>

                         <tr align="top">
                              <th scope="row"><label for="<?php echo $key; ?>_page"><?php _e('Display Page', 'recipe-press'); ?></label></th>
                              <td>
                                   <?php wp_dropdown_pages(array('name' => $this->optionsName . '[taxonomies][' . $key . '][page]', 'show_option_none' => __('No Default', 'recipe-press'), 'selected' => $taxonomy['page'])); ?>
                                   <?php $this->help(esc_js(sprintf(__('The page where this taxonomy will be listed. You must place the short code [%1$s] on this page to display the recipes. This will be the page that users will be directed to if the template file "%2$s" does not exist in your theme.', 'recipe-press'), 'recipe-tax tax=' . $key, 'taxonomy-recipe.php'))); ?>
                              </td>
                         </tr>


                         <tr align="top">
                              <th scope="row"><label for="<?php echo $key; ?>_per_page"><?php _e('Display how many per page', 'recipe-press'); ?></label></th>
                              <td>
                                   <select name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][per-page]" id="<?php echo $key; ?>_per_page">
                                        <?php for ( $count = 1; $count <= 25; ++$count ) : ?>
                                             <option value="<?php echo $count; ?>" <?php selected($taxonomy['per-page'], $count); ?>><?php echo $count; ?></option>

                                        <?php endfor; ?>
                                        </select>
                                   </td>

                              </tr>

                         <?php if ( $taxonomy['active'] ) : ?>
                                                  <tr align="top">
                                                       <th scope="row"><label for="<?php echo $key; ?>_default"><?php _e('Default', 'recipe-press'); ?></label></th>
                                                       <td>
                                   <?php wp_dropdown_categories(array('hierarchical' => $taxonomy['hierarchical'], 'taxonomy' => $key, 'show_option_none' => __('No Default', 'recipe-press'), 'hide_empty' => false, 'name' => $this->optionsName . '[taxonomies][' . $key . '][default]', 'id' => $key, 'orderby' => 'name', 'selected' => $taxonomy['default'])); ?>
                                             </td>
                                        </tr>
                         <?php endif; ?>

                                                  <tr align="top">
                                                       <th scope="row"><label for="<?php echo $key; ?>_hierarchical"><?php _e('Hierarchical?', 'recipe-press'); ?></label></th>
                                                       <td>
                                                            <input type="checkbox" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][hierarchical]" id="<?php echo $key; ?>_hierarchical" value="1" <?php checked($taxonomy['hierarchical'], 1); ?> />
                                   <?php $this->help(esc_js(sprintf(__('Should the taxonomy "%1$s" have a hierarchical structure like post tags?', 'recipe-press'), isset($taxonomy['singular']) ? $taxonomy['singular'] : ''))); ?>
                                             </td>
                                        </tr>
                                        <tr align="top">
                                             <th scope="row"><label for="<?php echo $key; ?>_multiple"><?php _e('Allow Multiple?', 'recipe-press'); ?></label></th>
                                             <td>
                                                  <input type="checkbox" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][multiple]" id="<?php echo $key; ?>_multiple" value="1" <?php checked($taxonomy['multiple'], 1); ?> />
                                   <?php $this->help(esc_js(sprintf(__('Should the taxonomy "%1$s" allow multiple selections on the public form?', 'recipe-press'), isset($taxonomy['singular']) ? $taxonomy['singular'] : ''))); ?>
                                             </td>
                                        </tr>
                                        <tr align="top">
                                             <th scope="row"><label for="<?php echo $key; ?>_active"><?php _e('Activate?', 'recipe-press'); ?></label></th>
                                             <td>
                                                  <input type="checkbox" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][active]" id="<?php echo $key; ?>_active" value="1" <?php checked($taxonomy['active'], 1); ?> />
                                   <?php $this->help(esc_js(sprintf(__('Should the taxonomy "%1$s" be active on the site?', 'recipe-press'), isset($taxonomy['singular']) ? $taxonomy['singular'] : ''))); ?>
                                             </td>
                                        </tr>
                                        <tr align="top">
                                             <th scope="row"><label for="<?php echo $key; ?>_delete"><?php _e('Delete?', 'recipe-press'); ?></label></th>
                                             <td>
                                                  <input type="checkbox" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][delete]" id="<?php echo $key; ?>_delete" value="1" onclick="confirmTaxDelete('<?php echo $taxonomy['plural']; ?>', '<?php echo $key; ?>');" />
                                   <?php $this->help(esc_js(sprintf(__('Delete the taxonomy %1$s? Will not remove the data, only removes the taxonomy options.', 'recipe-press'), isset($taxonomy['singular']) ? $taxonomy['singular'] : ''))); ?>
                                             </td>
                                        </tr>
                              </table>
               <?php endforeach; ?>
                                                  <table id="recipe_press_taxonomy_ingredients" class="form-table"  style="display: <?php echo ($selectedTax == 'ingredients') ? '' : 'none'; ?>">
                                                       <tr align="top">
                                                            <th scope="row"><label for="recipe_press_ingredient_slug"><?php _e('Ingredient Slug', 'recipe-press'); ?></label></th>
                                                            <td >
                                                                 <input type="text" name="<?php echo $this->optionsName; ?>[ingredient-slug]" id="recipe_press_ingredient_slug" value="<?php echo $this->options['ingredient-slug']; ?>" />
                              <?php $this->help(esc_js(__('The URL slug to use for listing all of the ingredients.', 'recipe-press'))); ?>
                                                  <a href="<?php echo get_option('home'); ?>/<?php echo $this->options['ingredient-slug']; ?>"><?php _e('View on Site', 'recipe-press'); ?></a>
                                             </td>
                                        </tr>

                                        <tr align="top">
                                             <th scope="row"><label for="recipe_press_ingredients_page"><?php _e('Display Page', 'recipe-press'); ?></label></th>
                                             <td>
                              <?php wp_dropdown_pages(array('name' => $this->optionsName . '[ingredient-page]', 'show_option_none' => __('No Default', 'recipe-press'), 'selected' => $this->options['ingredient-page'])); ?>
                              <?php $this->help(esc_js(sprintf(__('The page where this taxonomy will be listed. You must place the short code [%1$s] on this page to display the recipes. This will be the page that users will be directed to if the template file "%2$s" does not exist in your theme.', 'recipe-press'), 'recipe-tax tax=' . $key, 'taxonomy-recipe.php'))); ?>
                                             </td>
                                        </tr>
                                        <tr align="top">
                                             <th scope="row"><label for="recipe_press_ingrediens_per_page"><?php _e('Display how many per page', 'recipe-press'); ?></label></th>
                                             <td>
                                                  <select name="<?php echo $this->optionsName; ?>[ingredients-per-page]" id="recipe_press_ingrediens_per_page">
                                   <?php for ( $count = 1; $count <= 25; ++$count ) : ?>
                                                       <option value="<?php echo $count; ?>" <?php selected($this->options['ingredients-per-page'], $count); ?>><?php echo $count; ?></option>

                                   <?php endfor; ?>                                        </select>
                                             </td>

                                        </tr>
                                   </table>

                                   <table  id="recipe_press_taxonomy_new" class="form-table" style="display: none">
                                        <tr align="top">
                                             <th scope="row" class="rp-taxonomy-header" colspan="2"><?php _e('Add a new taxonomy?', 'recipe-press'); ?></th>
                                        </tr>
                                        <tr align="top">
                                             <th scope="row"><label for="new_taxonomy"><?php _e('Taxonomy Name', 'recipe-press'); ?></label></th>
                                             <td >
                                                  <input type="text" name="new_taxonomy" id="new_taxonomy" value="" onchange="updateTaxonomyField(this, this.value)" />
                              <?php $this->help(esc_js(__('The taxonomy name for the new taxonomy. You will not be able to change this!', 'recipe-press'))); ?>
                                                  </td>
                                             </tr>
                                             <tr align="top">
                                                  <th scope="row" colspan="2">
                              <?php
                                                       printf(__('<strong>Instructions</strong>: In the field above you need to enter the taxonomy object name. For example, categories is "recipe-category" This should not be one listed in the %1$s. It can contain ONLY letters, numbers, underscore (_) or dash (-). You will <strong>not</strong> be able to change this later. Click the "Save Settings" button to create the taxonomy, then set the options for the new taxonomy.', 'recipe-press'),
                                                               '<a href="http://codex.wordpress.org/Function_Reference/register_taxonomy#Reserved_Terms" target="_blank">' . __('Reserved Terms', 'recipe-press') . '</a>'
                                                       );
                              ?>
                                                  </th>
                                             </tr>
                                             </tbody>
                                        </table>
                                   </div>
                              </div>
                         </div>

                         <div class="postbox" style="float: right; width: 49%">
                              <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Taxonomy Settings', 'recipe-press'); ?></h3>
                              <div style="padding:8px">
                                   <p><?php _e('If you want to have a page that lists your taxonomies, you need to do one of two things:', 'recipe-press'); ?></p>
                                   <p>
                                        <strong><?php _e('Create Pages', 'recipe-press'); ?></strong>: <?php printf(__('Create individual pages for each taxonomy that will list the terms. These pages must have the [%1$s] short code on them. [%2$s]'), 'recipe-tax', '<a href="http://wiki.recipepress.net/wiki/Recipe-tax" target="_blank">' . __('Documentation for shortcode', 'recipe-press') . '</a>'); ?>
                                   </p>
                                   <p>
                                        <strong><?php _e('Create Template File', 'recipe-press'); ?></strong>: <?php printf(__('If you create a template file named `recipe-taxonomy.php` in your theme, all taxonomies will use this template to display a list of taxonomies. [%1$s]'), '<a href="http://wiki.recipepress.net/wiki/Template_File:_taxonomy-recipe.php" target="_blank">' . __('Documentation', 'recipe-press') . '</a>'); ?>
                                   </p>
                                   <p>
                                        <strong><?php _e('Warning!', 'recipe-press'); ?></strong> <?php _e('If you do not select a display page for a taxonomy and the template file does not exist, any calls to the site with the URL slug for the taxonomy will redirect to your default recipe list.', 'recipe-press'); ?>
          </p>
     </div>
</div>