<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * recipe-share.php - The Template for displaying the public submit form.
 *
 * @package RecipePress
 * @subpackage templates
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
global $RECIPEPRESSOBJ;
?>

<?php  
  //George add for the form to process "add new"/"edit"
   $recipe_action  =  $_GET['recipe_action'];
   $recipe_id =  $_GET['recipe_id'];   
   global  $recipe_field_data_array;
  
   if(isset($recipe_action) and $recipe_action='recipe_edit') {
       if (isset($recipe_id)) {
           $recipe_field_data_array = $RECIPEPRESSOBJ->get_recipe_front_form_fields_data($recipe_id);
       } 
   }
?>

<div class="form recipe-form">
     <form class="validate recipe-validate" action="<?php the_permalink(); ?>?recipe-form=submitted" method="post" id="update" name="update" enctype="multipart/form-data" >
          <?php share_recipe_hidden_fields(); ?>
          <?php add_recipe_hidden_field_extension($recipe_action, $recipe_id); //george add to store the action and post id  ?>
         
          <?php wp_nonce_field('recipe-form-submit', 'recipe-form-nonce'); ?>
         
         
          <div class="table">
               <table class="<?php share_recipe_class_name('table'); ?>">
                    <tbody class="<?php share_recipe_class_name('tbody'); ?>">
                         <tr class="<?php share_recipe_class_name('row', 'title'); ?>">
                              <th valign="top" class="<?php share_recipe_class_name('th', 'title'); ?>">
                                   <?php share_recipe_form_label('title'); ?>
                              </th>
                              <td colspan="3" class="<?php share_recipe_class_name('td', 'title'); ?>">
                                   <?php share_recipe_form_field('title', 'text', $recipe_field_data_array['title']); ?>
                                  <?php //echo (do_shortcode('[wpal-author-logo]')); ?>
                              </td>
                         </tr>
                         
                          <?php foreach ( $RECIPEPRESSOBJ->options['taxonomies'] as $taxonomy => $settings ) : if ($settings['active']) : ?>

                                        <tr class="<?php share_recipe_class_name('row', $taxonomy); ?>">
                                             <th valign="top" class="<?php share_recipe_class_name('th', $taxonomy); ?>">
                                   <?php share_recipe_form_label($settings['singular'], true); ?>
                                   </th>
                                   <td colspan="3" class="<?php share_recipe_class_name('td', $taxonomy); ?>">
                                   <?php share_recipe_form_field($taxonomy, 'select'); ?>
                                   </td>
                              </tr>
                         <?php endif; endforeach;?>
                              
                         <tr class="<?php share_recipe_class_name('row', 'image'); ?>">
                              <th valign="top" class="<?php share_recipe_class_name('th', 'image'); ?>">
                                   <?php share_recipe_form_label('image'); ?>
                              </th>
                              <td colspan="3" class="<?php share_recipe_class_name('td', 'image'); ?>">
                                    <input id="upload_image" type="text" name="upload_image" value="" size="36" />
                                    <input id="upload_image_button" type="button" value="Upload Image" /><br>Enter an URL or upload an image for the recipe
                                   <?php //share_recipe_form_field('image', 'image', $recipe_field_data_array['image']); ?>
                              </td>
                         </tr>
                         <tr class="<?php share_recipe_class_name('row', 'notes'); ?>">
                              <th valign="top" class="<?php share_recipe_class_name('th', 'notes'); ?>">
                                   <?php share_recipe_form_label('notes'); ?>
                              </th>
                              <td colspan="3" class="<?php share_recipe_class_name('td', 'notes'); ?>">
                                   <?php share_recipe_form_field('notes', 'textarea', $recipe_field_data_array['notes']); ?>
                              </td>
                         </tr>
                         
                           <tr class="<?php share_recipe_class_name('row', 'prep_time'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'prep_time'); ?>">
                                   <?php share_recipe_form_label('prep_time'); ?>
                                   </th>
                                   <td class="<?php share_recipe_class_name('td', 'prep_time'); ?>">
                                   <?php share_recipe_form_field('prep_time', 'text', $recipe_field_data_array['prep_time']); ?> <?php echo $RECIPEPRESSOBJ->options['minute-text']; ?>
                                   </td>
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'cook_time'); ?>">
                                   <?php share_recipe_form_label('cook_time'); ?>
                                   </th>
                                   <td class="<?php share_recipe_class_name('td', 'cook_time'); ?>">
                                   <?php share_recipe_form_field('cook_time', 'text', $recipe_field_data_array['cook_time']); ?> <?php echo $RECIPEPRESSOBJ->options['minute-text']; ?>
                                   </td>
                              </tr>
                            

                             <tr class="<?php share_recipe_class_name('row', 'servings'); ?>">
                                             <th valign="top" class="<?php share_recipe_class_name('th', 'servings'); ?>">
                                   <?php share_recipe_form_label('servings'); ?>
                                   </th>
                                   <td colspan="3" class="<?php share_recipe_class_name('td', 'servings'); ?>">
                                   <?php share_recipe_form_field('servings', 'text', $recipe_field_data_array['servings'], 'recipe_servings'); ?>
                                   </td>
                              </tr>
                              
                              <!-- nutrient_per_serving -->
                               <tr class="<?php share_recipe_class_name('row', 'nutrient_per_serving'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'nutrient_per_serving'); ?>">
                                   <?php share_recipe_form_label('nutrient_per_serving'); ?>
                                   </th>
                                   
                                   <td colspan='3' class="<?php share_recipe_class_name('td', 'nutrient_per_serving'); ?>">
                                   <?php share_recipe_form_field('nutrient_per_serving', 'text', $recipe_field_data_array['nutrient_per_serving'], 'recipe_per_serving'); ?> 
                                   </td>
                                   
                              </tr>
                              
                              

                              
                              <tr class="<?php share_recipe_class_name('row', 'ingredients'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'ingredients'); ?>">
                                   <?php share_recipe_form_label('ingredients'); ?>
                                   </th>
                                   <td colspan="3" class="<?php share_recipe_class_name('td', 'ingredients'); ?>">
                                   <?php share_recipe_form_field('ingredients', 'ingredients' , $recipe_field_data_array['ingredients']); ?>
                                   </td>
                              </tr>

                             <!--george change here begin  --> 
                              <tr class="<?php share_recipe_class_name('row', 'directions'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'directions'); ?>">
                                   <?php share_recipe_form_label('directions'); ?>
                                   </th>
                                   <td colspan="3" class="<?php share_recipe_class_name('td', 'directions'); ?>">
                                   <?php share_recipe_form_field('instructions', 'directions',  $recipe_field_data_array['instructions']); ?>
                                   </td>
                              </tr>
                              
                             <!-- Dek -->
                              <tr class="<?php share_recipe_class_name('row', 'dek'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'dek'); ?>">
                                   <?php share_recipe_form_label('dek'); ?>
                                   </th>
                                   
                                   <td colspan='3' class="<?php share_recipe_class_name('td', 'dek'); ?>">
                                   <?php share_recipe_form_field('subtitle', 'text',$recipe_field_data_array['subtitle'] , 'recipe_subtitle'); ?> 
                                   </td>
                              </tr>
                              
                             <!-- photo_credit -->
                               <tr class="<?php share_recipe_class_name('row', 'photo_credit'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'photo_credit'); ?>">
                                   <?php share_recipe_form_label('photo_credit'); ?>
                                   </th>
                                   
                                   <td colspan='3' class="<?php share_recipe_class_name('td', 'photo_credit'); ?>">
                                   <?php share_recipe_form_field('photo_credit','text', $recipe_field_data_array['photo_credit'], 'recipe_photo_credit'); ?> 
                                   </td>
                               </tr>
                               
                               
                              
                              
                              <!-- author -->
                              <tr class="<?php share_recipe_class_name('row', 'author'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'author'); ?>">
                                   <?php share_recipe_form_label('author'); ?>
                                   </th>
                                   
                                   <td colspan='3' class="<?php share_recipe_class_name('td', 'author'); ?>">
                                   <?php share_recipe_form_field('author', 'text', $recipe_field_data_array['author'], 'recipe_author'); ?> 
                                   </td>
                                   
                              </tr>
                             <!-- george change end  --> 

                         <?php if ( $RECIPEPRESSOBJ->showCaptcha ) : ?>
                                             <tr class="<?php share_recipe_class_name('row', 'recaptcha'); ?>">
                                                  <th valign="top" class="<?php share_recipe_class_name('th', 'recaptcha'); ?>">
                                   <?php share_recipe_form_label('recaptcha'); ?>
                                        </th>
                                        <td colspan="4" class="<?php share_recipe_class_name('td', 'recaptcha'); ?>">
                                   <?php share_recipe_recaptcha_field(); ?>
                                        </td>

                                   </tr>
                         <?php endif; ?>

                         <?php if ( !$RECIPEPRESSOBJ->options['require-login'] and !is_user_logged_in() ) : ?>
                                                  
                                        <tr class="<?php share_recipe_class_name('row', 'submitter_email'); ?>">
                                             <th valign="top" scope="row" class="<?php share_recipe_class_name('th', 'submitter_email'); ?>">
                                   <?php share_recipe_form_label('submitter_email'); ?>
                                             </th>
                                             <td colspan="3" class="<?php share_recipe_class_name('td', 'submitter_email'); ?>">
                                   <?php share_recipe_form_field('submitter_email'); ?>
                                             </td>
                                        </tr>
                         <?php endif; ?>

                                             </tbody>
                                        </table>

               <?php share_recipe_submit_button(); ?>
          </div>
     </form>
</div>