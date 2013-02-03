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

<div class="form recipe-form">
     <form class="validate recipe-validate" action="<?php the_permalink(); ?>?recipe-form=submitted" method="post" id="update" name="update" enctype="multipart/form-data" >
          <?php share_recipe_hidden_fields(); ?>
          <?php wp_nonce_field('recipe-form-submit', 'recipe-form-nonce'); ?>
          <div class="table">
               <table class="<?php share_recipe_class_name('table'); ?>">
                    <tbody class="<?php share_recipe_class_name('tbody'); ?>">
                         <tr class="<?php share_recipe_class_name('row', 'title'); ?>">
                              <th valign="top" class="<?php share_recipe_class_name('th', 'title'); ?>">
                                   <?php share_recipe_form_label('title'); ?>
                              </th>
                              <td colspan="3" class="<?php share_recipe_class_name('td', 'title'); ?>">
                                   <?php share_recipe_form_field('title'); ?>
                              </td>
                         </tr>
                         <tr class="<?php share_recipe_class_name('row', 'image'); ?>">
                              <th valign="top" class="<?php share_recipe_class_name('th', 'image'); ?>">
                                   <?php share_recipe_form_label('image'); ?>
                              </th>
                              <td colspan="3" class="<?php share_recipe_class_name('td', 'image'); ?>">
                                   <?php share_recipe_form_field('image', 'image'); ?>
                              </td>
                         </tr>
                         <tr class="<?php share_recipe_class_name('row', 'notes'); ?>">
                              <th valign="top" class="<?php share_recipe_class_name('th', 'notes'); ?>">
                                   <?php share_recipe_form_label('notes'); ?>
                              </th>
                              <td colspan="3" class="<?php share_recipe_class_name('td', 'notes'); ?>">
                                   <?php share_recipe_form_field('notes', 'textarea'); ?>
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


                                        <tr class="<?php share_recipe_class_name('row', 'servings'); ?>">
                                             <th valign="top" class="<?php share_recipe_class_name('th', 'servings'); ?>">
                                   <?php share_recipe_form_label('servings'); ?>
                                   </th>
                                   <td colspan="3" class="<?php share_recipe_class_name('td', 'servings'); ?>">
                                   <?php share_recipe_form_field('servings'); ?>
                                   <?php share_recipe_form_field('serving-size', 'select'); ?>
                                   </td>
                              </tr>
                              <tr class="<?php share_recipe_class_name('row', 'prep_time'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'prep_time'); ?>">
                                   <?php share_recipe_form_label('prep_time'); ?>
                                   </th>
                                   <td class="<?php share_recipe_class_name('td', 'prep_time'); ?>">
                                   <?php share_recipe_form_field('prep_time'); ?> <?php echo $RECIPEPRESSOBJ->options['minute-text']; ?>
                                   </td>
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'cook_time'); ?>">
                                   <?php share_recipe_form_label('cook_time'); ?>
                                   </th>
                                   <td class="<?php share_recipe_class_name('td', 'cook_time'); ?>">
                                   <?php share_recipe_form_field('cook_time'); ?> <?php echo $RECIPEPRESSOBJ->options['minute-text']; ?>
                                   </td>
                              </tr>
                              <tr class="<?php share_recipe_class_name('row', 'ingredients'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'ingredients'); ?>">
                                   <?php share_recipe_form_label('ingredients'); ?>
                                   </th>
                                   <td colspan="3" class="<?php share_recipe_class_name('td', 'ingredients'); ?>">
                                   <?php share_recipe_form_field('ingredients', 'ingredients'); ?>
                                   </td>
                              </tr>

                              <tr class="<?php share_recipe_class_name('row', 'instructions'); ?>">
                                   <th valign="top" class="<?php share_recipe_class_name('th', 'instructions'); ?>">
                                   <?php share_recipe_form_label('instructions'); ?>
                                   </th>
                                   <td colspan="3" class="<?php share_recipe_class_name('td', 'instructions'); ?>">
                                   <?php share_recipe_form_field('instructions', 'textarea'); ?>
                                   </td>
                              </tr>

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
                                                  <tr class="<?php share_recipe_class_name('row', 'submitter'); ?>">
                                                       <th valign="top" scope="row" class="<?php share_recipe_class_name('th', 'submitter'); ?>">
                                   <?php share_recipe_form_label('submitter'); ?>
                                             </th>
                                             <td colspan="3" class="<?php share_recipe_class_name('td', 'submitter'); ?>">
                                   <?php share_recipe_form_field('submitter'); ?>

                                             </td>
                                        </tr>
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