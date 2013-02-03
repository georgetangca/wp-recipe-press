<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * ingredient-form.php - Create the ingredient entry form on the admin side.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
global $ingredientID, $ingredient, $RECIPEPRESSOBJ;

if ( $this->publicForm ) {
     $public = '-public';
     $type = 'public';
} else {
     $public = '';
     $type = 'admin';
}

do_action('rp_form_before_ingredients');
?>
<div class="table">
     <table id="rp_ingredients" class="form-table editrecipe">
          <thead>
               <tr class="form-field">
                    <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-sort">&nbsp;</th>
                    <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-quantity"><?php _e('Quantity', 'recipe-press'); ?></th>
                    <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-size"><?php _e('Size', 'recipe-press'); ?></th>
                    <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-ingredient"><?php _e('Ingredient Description', 'recipe-press'); ?></th>
                   
                    </tr>
               </thead>
               <tbody id="rp_ingredients_body">
                    <tr id="rp_ingredient_null" style="display:none">
                         <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-sort" id="rp_drag_icon">
                              <img alt="<?php _e('Drag Ingredient', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/drag-icon.png'; ?>" style="cursor:pointer" />
                              <img alt="<?php _e('Delete Ingredient', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/delete.gif'; ?>" style="cursor:pointer" onclick="rp_delete_row('rp_ingredient_NULL');" />
                         </th>

                         <td id="rp_size_column">
                          <input type="text" class="recipe-press-ingredients-size"  name="ingredientsCOPY[NULL][size]" value="<?php __('No Size', 'recipe-press') ?>" />   
                             
                         <?php //wp_dropdown_categories(array('hierarchical' => false, 'taxonomy' => 'recipe-size', 'hide_empty' => false, 'name' => 'ingredientsCOPY[NULL][size]', 'orderby' => 'name', 'echo' => true, 'show_option_none' => __('No Size', 'recipe-press'))); ?>
                         <br />
                    </td>
                    <td id="rp_item_column">
                         <?php
                         $ingredientItem = '<input type="hidden" id="recipe_ingredient_NULL" name = "ingredients[NULL][item]" value="' . $ingredient['item'] . '" />';
                         $ingredientBox = '<input id="ingname_NULL" type="text" class="recipe-item-lookup recipe-press-ingredients' . $public . '" name="ingredients[NULL][new-ingredient]" value="" onkeypress="clear_ingredient_id(NULL)" />';
                         echo apply_filters('rp_ingredient_form_item', $ingredientItem);
                         echo apply_filters('rp_ingredient_form_name', $ingredientBox);
                        
                         ?>
                    </td>
                 </tr>
               <?php
                              $ingredientID = 1;
                              if ( !isset($ingredients) ) {
                                   $ingredients = $RECIPEPRESSOBJ->getIngredients();
                              }

                              foreach ( $ingredients as $id => $ingredient ) :
               ?>
                                   <tr id="rp_ingredient_<?php echo $ingredientID; ?>" class="rp_size_type_<?php echo $ingredient['size']; ?>" valign="top">
                                        <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-sort">
                                             <img alt="<?php _e('Drag Ingredient', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/drag-icon.png'; ?>" style="cursor:pointer" />
                                             <img alt="<?php _e('Delete Ingredient', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/delete.gif'; ?>" style="cursor:pointer" onclick="rp_delete_row('rp_ingredient_<?php echo $ingredientID; ?>');" />
                                        </th>

                                        <td>
                         <?php
                                        $value = isset($ingredient['quantity']) ? $ingredient['quantity'] : '';
                                        $quantityBox = '<input class="recipe-press-quantity" type="text" name="ingredients[' . $ingredientID . '][quantity]" value="' . $value . '" />';
                                        echo apply_filters('rp_ingredient_form_quantity', $quantityBox);
                         ?>
                              </td>
                              <td>

                              <?php //george add one here below begin ?>    
                              <input type="text" class="recipe-press-ingredients-size"  name = "<?php echo 'ingredients[' . $ingredientID . '][size]';?>"  id = "<?php echo 'ingredient_' . $ingredientID . '_size';?>"  value="<?php  echo $ingredient['size']; //__('No Size', 'recipe-press'); ?>" />   
                              <?php //end ?>
                              
                                        </td>
                                        
                                        <td>
                         <?php
                                             if ( isset($ingredient['item'])) {
                                                  $value = $ingredient['item'];
                                                  $term = get_term($value, 'recipe-ingredient');
                                                  if ( isset($term->name) ) {
                                                       $value = $term->name;
                                                  } else {
                                                       $value = '';
                                                  }
                                             } else {
                                                  $value = '';
                                                  $term = '';
                                             }

                                             /*
                                               $ingredientBox = wp_dropdown_categories(array('selected' => $ingredient['item'], 'hierarchical' => false, 'taxonomy' => 'recipe-ingredient', 'hide_empty' => false, 'name' => 'ingredients[' . $ingredientID . '][item]', 'id' => 'ingredient_' . $ingredientID . '_item', 'orderby' => 'name', 'echo' => false, 'show_option_none' => __('Add New Below', 'recipe-press'), 'class'=>'recipe-ingredient'));

                                               $ingredientBox = preg_replace("#<select([^>]*)>#", "<select$1 onchange='recipe_press_show_new_ingredient($ingredientID, this.value)'>", $ingredientBox);
                                              */

                                             $ingredientItem = '<input type="hidden" id="recipe_ingredient_' . $ingredientID . '" name = "ingredients[' . $ingredientID . '][item]" value="' . $ingredient['item'] . '" />';
                                             $ingredientBox = '<input id="ingname_' . $ingredientID . '" type="text" class="recipe-item-lookup recipe-press-ingredients' . $public . '" name="ingredients[' . $ingredientID . '][new-ingredient]" value="' . $value . '" onkeypress="clear_ingredient_id(' . $ingredientID . ')" />';
                                             echo apply_filters('rp_ingredient_form_item', $ingredientItem);
                                             echo apply_filters('rp_ingredient_form_name', $ingredientBox);
                         ?>
                        
                                             </td>

                    

                                                       </tr>
               <?php ++$ingredientID; ?>
               <?php endforeach; ?>
                                                       </tbody>
                                                  </table>
                                                  <p><a onclick="rp_add_ingredient('<?php echo $type; ?>')" style="cursor:pointer"><?php _e('Add Ingredient', 'recipe-press'); ?></a></p>
     <?php do_action('rp_form_after_ingredients'); ?>
</div>