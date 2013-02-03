<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * direction-form.php - Create the direction entry form on the admin side.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */

global $instructionID, $instruction, $RECIPEPRESSOBJ;


do_action('rp_form_before_instructions');
?>
<div class="table">
    
              <!-- For Instruction --> 
              <table id="rp_instructions" class="form-table editrecipe">
               <thead>
                  <tr class="form-field">
                    <td colspan="4"><input type="hidden" id="ins_value" name="ins_value"  value=""></td>   
                  </tr>
               </thead>
               <tbody id="rp_instructions_body">
                    <tr id="rp_instruction_null" style="display:none">
                         <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-sort" id="rp_instruction_drag_icon">
                              <img title="<?php _e('Re-order direction', 'recipe-press'); ?>" alt="<?php _e('Drag Direction', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/drag-icon.png'; ?>" style="cursor:pointer" />
                              <img alt="<?php _e('Delete Direction ', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/delete.gif'; ?>" style="cursor:pointer" onclick="rp_delete_instruction_row('rp_instruction_NULL');" />
                         </th>

                         
                        <td id="rp_instruction_item_column">
                             <?php
                             $instructionItem = '<input type="hidden" id="recipe_instruction_NULL" name = "instructions[NULL][item]" value="' . $instruction['item'] . '" />';
                             $instructionBox = '<input id="insname_NULL" type="text" class="recipe-item-lookup recipe-press-instructions" name="instructions[NULL][new-instruction]" value="" onkeypress="clear_instruction_id(NULL)" />';
                             echo apply_filters('rp_instruction_form_item', $instructionItem);
                             echo apply_filters('rp_instruction_form_name', $instructionBox);
                             ?>
                        </td>

                    

                    </tr>
                  <?php     
                              $instructionID = 1;
                              if ( !isset($instructions) ) {
                                   $instructions = $RECIPEPRESSOBJ->getInstructions();            
                              }
                              
                              if (is_array($instructions) and count($instructions)>0 ): 

                              foreach ( $instructions as $id => $instruction ) :
                 ?>
                                   <tr id="rp_instruction_<?php echo $instructionID; ?>"  valign="top">
                                        <th class="recipe-press-header<?php echo $public; ?> recipe-press-header-sort">
                                             <img title="<?php _e('Re-order direction', 'recipe-press'); ?>"  alt="<?php _e('Drag Direction', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/drag-icon.png'; ?>" style="cursor:pointer" />
                                             <img alt="<?php _e('Delete Direction', 'recipe-press'); ?>" src="<?php echo $this->pluginURL . 'images/icons/delete.gif'; ?>" style="cursor:pointer" onclick="rp_delete_instruction_row('rp_instruction_<?php echo $instructionID; ?>');" />
                                        </th>

                         
                                        <td>
                                <?php
                                            
                                            if ( isset($instruction['item'])) {
                                                  $term = $instruction['item'];
                                                  
                                                  $value = $instruction['new-instruction'];
                                                  
                                             } else {
                                                  $value = '';
                                                  $term = '';
                                             }
                                              
                                             $instructionItem = '<input type="hidden" id="recipe_instruction_' . $instructionID . '" name = "instructions[' . $instructionID . '][item]" value="' . $instruction['item'] . '" />';
                                             $instructionBox = '<input id="insname_' . $instructionID . '" type="text" class="recipe-item-lookup recipe-press-instructions" name="instructions[' . $instructionID . '][new-instruction]" value="' . $value . '" onkeypress="clear_instruction_id(' . $instructionID . ')" />';
                                             echo apply_filters('rp_Instruction_form_item', $instructionItem);
                                             echo apply_filters('rp_Instruction_form_name', $instructionBox);
                                ?>

                                             </td>

                                           </tr>
               <?php ++$instructionID; ?>
               <?php endforeach; ?>
              <?php endif; ?>                             
                                     </tbody>
                                                          
               
                            </table> <!--Instruction End --> 
                     <p><a onclick="rp_add_instruction('<?php echo $type; ?>')" style="cursor:pointer"><?php _e('Add Direction', 'recipe-press'); ?></a></p>
                                                                                               
                                                  
     <?php do_action('rp_form_after_instructions'); ?>
                                                  
</div>