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


do_action('rp_form_before_ingredients');
?>
<div class="table">
    
              <!-- For Instruction --> 
              <table id="rp_ingredients" class="form-table editrecipe">
               <thead>
                  <tr class="form-field">
                     <td colspan="4"><input type="hidden" id="ing_value" name="ing_value"  value=""></td>
                  </tr>
               </thead>
               <tbody id="rp_ingredients_body">
                    <tr id="rp_ingredient_null" style="display:none">
                         <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-sort" id="rp_drag_icon">
                              <img title="<?php _e('Re-order ingredient', 'recipe-press'); ?>"  alt="<?php _e('Drag Ingredient', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/drag-icon.png'; ?>" style="cursor:pointer" />
                              <img alt="<?php _e('Delete Ingredient ', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/delete.gif'; ?>" style="cursor:pointer" onclick="rp_delete_row('rp_ingredient_NULL');" />
                         </th>

                         
                        <td id="rp_item_column">
                          <?php
                             $ingredientItem = '<input type="hidden" id="recipe_ingredient_NULL" name = "ingredients[NULL][item]" value="' . $ingredient['item'] . '" />';
                             $ingredientBox = '<input id="ingname_NULL" type="text" class="recipe-item-lookup recipe-press-ingredients" name="ingredients[NULL][new-ingredient]" value="" onkeypress="clear_ingredient_id(NULL)" />';
                             echo apply_filters('rp_ingredient_form_item', $ingredientItem);
                             echo apply_filters('rp_ingredient_form_item', $ingredientBox);
                             ?>
                        </td>

                    

                    </tr>
                  <?php     
                              $ingredientID = 1;
                              if ( !isset($ingredients) ) {
                                   $ingredients = $RECIPEPRESSOBJ->getIngredients();            
                              }
                              
                              if (is_array($ingredients) and count($ingredients)>0 ): 

                              foreach ( $ingredients as $id => $ingredient ) :
                 ?>
                                   <tr id="rp_ingredient_<?php echo $ingredientID; ?>"  valign="top">
                                        <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-sort">
                                             <img title="<?php _e('Re-order ingredient', 'recipe-press'); ?>"  alt="<?php _e('Drag Ingredient', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/drag-icon.png'; ?>" style="cursor:pointer" />
                                             <img alt="<?php _e('Delete Ingredient', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/delete.gif'; ?>" style="cursor:pointer" onclick="rp_delete_row('rp_ingredient_<?php echo $ingredientID; ?>');" />
                                        </th>

                         
                                        <td>
                                <?php
                                            
                                            if ( isset($ingredient['item'])) {
                                                  $term = $ingredient['item'];
                                                  
                                                  $value = $ingredient['new-ingredient'];
                                                  
                                             } else {
                                                  $value = '';
                                                  $term = '';
                                             }
                                              
                                             $ingredientItem = '<input type="hidden" id="recipe_ingredient_' . $ingredientID . '" name = "ingredients[' . $ingredientID . '][item]" value="' . $ingredient['item'] . '" />';
                                             $ingredientBox = '<input id="ingname_' . $ingredientID . '" type="text" class="recipe-item-lookup recipe-press-ingredients" name="ingredients[' . $ingredientID . '][new-ingredient]" value="' . $value . '" onkeypress="clear_ingredient_id(' . $ingredientID . ')" />';
                                             echo apply_filters('rp_ingredient_form_item', $ingredientItem);
                                             echo apply_filters('rp_ingredient_form_name', $ingredientBox);
                                ?>

                                             </td>

                                           </tr>
               <?php ++$ingredientID; ?>
               <?php endforeach; ?>
              <?php endif; ?>                             
                                     </tbody>
                                                          
               
                            </table> <!--Instruction End --> 
                     <p><a onclick="rp_add_ingredient('<?php echo $type; ?>')" style="cursor:pointer"><?php _e('Add Ingredient', 'recipe-press'); ?></a></p>
                                                                                               
                                                  
     <?php do_action('rp_form_after_ingredients'); ?>
                                                  
</div>