<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * rp_importers.php - helper file for changing inflection of text.
 *
 * @package RecipePress
 * @subpackage helpers
 * @author GrandSlambert
 * @copyright 2009-2010
 * @access public
 */
class recipe_press_import extends recipePressCore {

     var $results;

     public function recipe_press_import($data = array()) {
          global $current_user;
          parent::recipePressCore();

          if ( isset($_FILES['import-file']) ) {
               $recipes = $this->$_POST['import-type']($_FILES['import-file'], $data['import-category']);

               if ( is_array($recipes) ) {
                    $this->results = '<ul>';

                    foreach ( $recipes as $recipe ) {
                         $this->results.= '<li>' . __('Importing recipe: ', 'recipe-press') . $recipe['title'] . '</li>';
                         $recipe = $this->input($recipe);

                         $post = array(
                              'post_title' => $recipe['title'],
                              'comment_status' => 'open',
                              'ping_status' => 'open',
                              'post_author' => $current_user->ID,
                              'post_content' => is_array($recipe['instructions']) ? implode("\n", $recipe['instructions']) : $recipe['instructions'],
                              'post_date' => date('Y-m-d H:i:s'),
                              'post_date_gmt' => gmdate('Y-m-d H:i:s'),
                              'post_excerpt' => $recipe['notes'],
                              'post_status' => $recipe['status'],
                              'post_type' => 'recipe',
                         );

                         remove_action('save_post', array(&$this, 'save_recipe'));

                         if ( $post = wp_insert_post($post) ) {
                              $postData = get_post($post);

                              /* Handle taxonomies */
                              if ( is_array($recipe['recipe-category']) ) {
                                   $terms = array();

                                   foreach ( $recipe['recipe-category'] as $tax ) {
                                        $tax = get_term($tax, 'recipe-category');
                                        if ( is_object($tax) ) {
                                             $terms[] = $tax->name;
                                        }
                                   }

                                   $affected = wp_set_object_terms($post, $terms, 'recipe-category');
                              }

                              /* Save custom fields */
                              add_post_meta($post, '_recipe_prep_time_value', $recipe['prep_time'], true);
                              add_post_meta($post, '_recipe_cook_time_value', $recipe['cook_time'], true);
                              add_post_meta($post, '_recipe_ready_time_value', $this->readyTime($recipe['prep_time'], $recipe['cook_time'], true), true);
                              add_post_meta($post, '_recipe_ready_time_value_raw', $this->readyTime($recipe['prep_time'], $recipe['cook_time'], false), true);
                              add_post_meta($post, '_recipe_servings_value', $recipe['servings'], true);
                              add_post_meta($post, '_recipe_serving_size_value', $recipe['serving_size'], true);

                              if ( !is_user_logged_in() ) {
                                   add_post_meta($post, 'recipe_author', $_POST['submitter'], true);
                                   add_post_meta($post, 'recipe_author_email', $_POST['submitter_email'], true);
                              }

                              /* Save ingredients */
                              $this->save_ingredients($post, $recipe['ingredients']);
                         }
                    }
                    $this->results.= '</ul>';
               }
          }
     }

     /**
      * Import recipes in the recipeml format.
      *
      * @param array $file
      * @param integer $category
      * @return array
      */
     public function recipeml($file, $category) {

          if ( $file['type'] == 'application/zip' ) {
               $allRecipes = array();
               $zip = zip_open($file['tmp_name']);

               while ($infile = zip_read($zip)) {
                    $data = zip_entry_read($infile, zip_entry_filesize($infile));
                    if ( $data = @simplexml_load_string($data) ) {
                         $recipes = $this->recipeml_read($data, $category);
                         $allRecipes = array_merge($allRecipes, $recipes);
                    }
               }

               zip_close($zip);
          } else {
               $data = simplexml_load_file($file['tmp_name']);
               $allRecipes = $this->recipeml_read($data, $category);
          }

          return $allRecipes;
     }

     /**
      * Read in each entry in the RecipeML file.
      *
      * @global object $wpdb
      * @global object $current_user
      * @param array $data
      * @param integer $category
      * @return array
      */
     protected function recipeml_read($data, $category) {
          global $wpdb;

          $allRecipes = array();

          foreach ( $data->recipe as $recipe ) {
               unset($newRecipe);

               $newRecipe = array(
                    'title' => (string) $recipe->head->title,
                    'servings' => (int) $recipe->head->yield,
                    'recipe-category' => $category,
                    'instructions' => (string) $recipe->directions->step,
                    'notes' => '',
                    'prep_time' => '',
                    'cook_time' => '',
                    'serving-size' => '',
                    'status' => $_POST['status'],
                    'submitter' => '',
                    'submitter_email' => '',
                    'recipe-cuisine' => '',
               );

               /* Check to see if recipe fits into an existing category */
               $newRecipe['recipe-category'] = array();

               foreach ( $recipe->head->categories->cat as $category ) {
                    if ( $term = term_exists($category, 'recipe-category') or $term = term_exists(rp_inflector::plural($category, 2), 'recipe-category') ) {
                         if ( is_array($term) ) {
                              $newRecipe['recipe-category'][] = $term['term_id'];
                         }
                    } else {
                         $term = wp_insert_term($category, 'recipe-category');
                         if ( is_array($term) ) {
                              $newRecipe['recipe-category'][] = $term['term_id'];
                         }
                    }
               }

               $newIngredients = array();

               /* Method for getting ingredients when there are no divisions */
               foreach ( $recipe->ingredients->ing as $ingredient ) {
                    $newIngredient['quantity'] = (string) $ingredient->amt->qty;
                    $size = rp_Inflector::singular((string) $ingredient->amt->unit);
                    
                    if ( $term = term_exists($size, 'recipe-size') or $term = term_exists(rp_inflector::plural($size, 2), 'recipe-size') ) {
                         $newIngredient['size'] = $term['term_id'];
                    } elseif ( isset($size) and !empty($size) ) {
                         $term = wp_insert_term($size, 'recipe-size');
                         if ( !isset($term->errors) ) {
                              $newIngredient['size'] = $term['term_id'];
                         }
                    }

                    if ( $term = term_exists($ingredient->item, 'recipe-ingredient') or $term = term_exists(rp_inflector::plural($ingredient->item, 2), 'recipe-ingredient') ) {
                         if ( is_array($term) ) {
                              $newIngredient['item'] = $term['term_id'];
                         }
                    } else {
                         $term = wp_insert_term($ingredient->item, 'recipe-ingredient');
                         if ( is_array($term) ) {
                              $newIngredient['item'] = $term['term_id'];
                         }
                    }

                    array_push($newIngredients, $newIngredient);
               }

               /* Method for getting ingredients from division */
               $div = 'ing-div';
               foreach ( $recipe->ingredients->$div as $division ) {
                    if ( $title = (string) $division->title ) {
                         $newIngredient['quantity'] = 1;
                         
                         if ( $term = term_exists($title, 'recipe-ingredient') ) {
                              $newIngredient['item'] = $term['term_id'];
                         } else {
                              $term = wp_insert_term($title, 'recipe-ingredient');
                              $newIngredient['item'] = $term['term_id'];
                         }
                         array_push($newIngredients, $newIngredient);
                    }

                    foreach ( $division->ing as $ingredient ) {
                         $newIngredient['quantity'] = (string) $ingredient->amt->qty;
                         $size = rp_Inflector::singular((string) $ingredient->amt->unit);

                         if ( $term = term_exists($size, 'recipe-size') or $term = term_exists(rp_inflector::plural($size, 2), 'recipe-size') ) {
                              $newIngredient['size'] = $term['term_id'];
                         } elseif ( isset($size) and !empty($size) ) {
                              $term = wp_insert_term($size, 'recipe-size');
                              if ( !isset($term->errors) ) {
                                   $newIngredient['size'] = $term['term_id'];
                              }
                         }

                         if ( $term = term_exists($ingredient->item, 'recipe-ingredient') ) {
                              $newIngredient['item'] = $term['term_id'];
                         } else {
                              $term = wp_insert_term($ingredient->item, 'recipe-ingredient');
                              $newIngredient['item'] = $term['term_id'];
                         }

                         array_push($newIngredients, $newIngredient);
                    }
               }
               $newRecipe['ingredients'] = $newIngredients;
               array_push($allRecipes, $newRecipe);
          }

          return $allRecipes;
     }

     public function mealmaster($file, $category) {

          $recipe_ctr = 0;
          $datalines = file($file['tmp_name']);

          /* Read each line */
          foreach ( $datalines as $line ) {
               /* if it's the Meal-Master line, continue */
               if ( preg_match('|(.*?)Meal-Master(.*?)|', $line) ) {

               } elseif ( preg_match('|Title:(.*?)\n|', $line, $title) ) {
                    /* if it's a title, create the recipe entry variables */
                    ++$recipe_ctr;

                    $recipes[$recipe_ctr] = array(
                         'title' => (string) trim($title[1]),
                         'servings' => 1,
                         'recipe-category' => '',
                         'ingredients' => '',
                         'instructions' => '',
                         'notes' => '',
                         'prep_time' => '',
                         'cook_time' => '',
                         'serving-size' => '',
                         'status' => $_POST['status'],
                         'submitter' => '',
                         'submitter_email' => '',
                         'recipe-cuisine' => '',
                    );
               } elseif ( preg_match('|Categories:(.*?)\n|', $line, $categories) ) {
                    /* if it's the categories, create a category array */
                    $categories = trim($categories[1]);
                    $categories = str_replace(', ', ',', $categories);
                    /* split the categories into a nifty array */
                    $categories = explode(',', $categories);

                    foreach ( $categories as $category ) {
                         if ( $term = term_exists($category, 'recipe-category') or $term = term_exists(rp_inflector::plural($category, 2), 'recipe-category') ) {
                              if ( is_array($term) ) {
                                   $recipes[$recipe_ctr]['recipe-category'][] = $term['term_id'];
                              }
                         } else {
                              $term = wp_insert_term($category, 'recipe-category');
                              if ( is_array($term) ) {
                                   $recipes[$recipe_ctr]['recipe-category'][] = $term['term_id'];
                              }
                         }
                    }
               } elseif ( preg_match('|Yield:(.*?)\n|', $line, $yield) or preg_match('|Servings:(.*?)\n|', $line, $yield) ) {
                    /* Grab the yield or servings line */
                    if ( is_array($yield[1]) ) {
                         list ($recipes[$recipe_ctr]['servings'], $recipes[$recipe_ctr]['serving-size']) = split(' ', trim($yield[1]));
                    }
               } elseif ( !preg_match('|-----\n|', $line) ) {
                    /* now anything else it finds that isn't the end-flag in the file should be the body */
                    $recipes[$recipe_ctr]['content'][] = $line;
               } else {
                    /* this should only take effect when it finds the end-flag "-----" */
               }
          }

          /* Separate ingredients from body */
          foreach ( $recipes as $id => $recipe ) {
               $inIngredient = false;
               $foundBody = false;
               $ingredientCtr = 1;

               foreach ( $recipe['content'] as $line ) {
                    $line = trim($line);

                    if ( empty($line) and $inIngredient and !$foundBody ) {
                         $foundBody = true;
                    } elseif ( !empty($line) and $inIngredient and !$foundBody ) {
                         $ingredients = preg_match('|(^[0-9 \/]*)([a-zA-Z]*)([a-zA-Z0-9- ]*);?(.*)|', $line, $parts);

                         $recipes[$id]['ingredients'][$ingredientCtr]['quantity'] = $parts[1];

                         if ( $term = term_exists($parts[2], 'recipe-size') or $term = term_exists(rp_inflector::plural($parts[2], 2), 'recipe-size') ) {
                              $recipes[$id]['ingredients'][$ingredientCtr]['size'] = $term['term_id'];
                         } elseif ( isset($parts[2]) and !empty($parts[2]) ) {
                              $term = wp_insert_term($parts[2], 'recipe-size');
                              if ( !isset($term->errors) ) {
                                   $recipes[$id]['ingredients'][$ingredientCtr]['size'] = $term['term_id'];
                              }
                         }

                         if ( $term = term_exists($parts[3], 'recipe-ingredient') or $term = term_exists(rp_inflector::plural($parts[3], 2), 'recipe-ingredient') ) {
                              $recipes[$id]['ingredients'][$ingredientCtr]['item'] = $term['term_id'];
                         } elseif ( isset($parts[3]) and !empty($parts[3]) ) {
                              $term = wp_insert_term($parts[3], 'recipe-ingredient');
                              if ( !isset($term->errors) ) {
                                   $recipes[$id]['ingredients'][$ingredientCtr]['item'] = $term['term_id'];
                              }
                         }

                         if ( isset($parts[4]) and !empty($parts[4]) ) {
                              $recipes[$id]['ingredients'][$ingredientCtr]['notes'] = $parts[4];
                         }
                         ++$ingredientCtr;
                    } elseif ( $foundBody ) {
                         $recipes[$id]['instructions'][] = $line;
                    } else {
                         $inIngredient = true;
                    }
               }
          }

          return $recipes;
     }

}

$rp_import = new recipe_press_import($_POST);
?>

<div class="wrap">
     <div class="icon32" id="icon-recipe-press"><br/></div>
     <h2><?php echo $rp_import->pluginName; ?> &raquo; <?php _e('Import', 'recipe-press'); ?></h2>
     <p><?php _e('This page allows you to import recipes formated in the RecipeML Format..', 'recipe-press'); ?></p>
     <div class="col-wrap">
          <div style="clear:both; margin-top:10px;">
               <div class="postbox" style="width:49%; float: left;">
                    <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Import File', 'recipe-press'); ?></h3>
                    <div style="padding:8px">
                         <?php if ( strlen($rp_import->results) > 1 ) : ?>
                              <div class="import-results">
                                   <h4><?php _e('Import Results', 'recipe-press'); ?></h4>
                                   <p><?php echo $rp_import->results; ?></p>
                              </div>
                         <?php endif; ?>
                              <form class="recipe-import" action="<?php echo admin_url('edit.php?post_type=recipe&page=recipe-press-import'); ?>" method="post" id="recipe_import" name="recipe-import" enctype="multipart/form-data" >
                                   <table class="form-table">
                                        <tbody>
                                             <tr align="top">
                                                  <th scope="row"><label for="recipe_press_display_page"><?php _e('Recipe File Format', 'recipe-press'); ?></label></th>
                                                  <td>
                                                       <select name="import-type">
                                                            <option value="recipeml"><?php _e('RecipeML XML File', 'recipe-presss'); ?></option>
                                                            <option value="mealmaster"><?php _e('MealMaster File', 'recipe-presss'); ?></option>
                                                       </select>
                                                  </td>
                                             </tr>
                                             <tr valign="top">
                                                  <th scope="row"><label for="import-category"><?php _e('Default import category', 'recipe-press'); ?></label></th>
                                                  <td>
                                                  <?php
                                                  wp_dropdown_categories(array(
                                                       'hierarchical' => $rp_import->options['taxonomies']['recipe-category']['hierarchical'],
                                                       'taxonomy' => 'recipe-category',
                                                       'show_option_none' => __('No Default', 'recipe-press'),
                                                       'hide_empty' => false,
                                                       'name' => 'import-category',
                                                       'id' => 'import_category',
                                                       'orderby' => 'name',
                                                       'selected' => $rp_import->options['taxonomies']['recipe-category']['default'])
                                                  );
                                                  ?>
                                                  <?php $this->help(__('The importer will check if the recipe fits into an existing category, otherwise recipe will be added to this category.', 'recipe-press')); ?>
                                             </td>
                                        </tr>
                                        <tr align="top">
                                             <th scope="row"><label for="status"><?php _e('Status for User Recipes', 'recipe-press'); ?></label></th>
                                             <td>
                                                  <select name="status" id="status">
                                                       <option value="draft" <?php selected($this->options['new-recipe-status'], 'draft'); ?> ><?php _e('Draft', 'recipe-press'); ?></option>
                                                       <option value="pending" <?php selected($this->options['new-recipe-status'], 'pending'); ?> ><?php _e('Pending Review', 'recipe-press'); ?></option>
                                                       <option value="publish" <?php selected($this->options['new-recipe-status'], 'publish'); ?> ><?php _e('Published', 'recipe-press'); ?></option>
                                                  </select>
                                                  <?php $this->help(__('Select the default recipe status for new user submitted recipes.', 'recipe-press')); ?>
                                             </td>
                                        </tr>
                                        <tr valign="top">
                                             <th scope="row"><label for="import-file"><?php _e('File to import', 'recipe-press'); ?></label></th>
                                             <td>
                                                  <input type="file" name="import-file" value="" />
                                                  <?php $this->help(__('Choose either a single file or a zip containing a collection of files of the same type..', 'recipe-press')); ?>

                                             </td>
                                        </tr>
                                        <tr align="top">
                                             <td colspan="2" align="center">
                                                  <p class="submit">
                                                       <input type="submit" value="<?php _e('Import Recipe'); ?>" name="submit" class="button-primary"/>
                                                  </p>
                                             </td>
                                        </tr>
                                   </tbody>
                              </table>
                         </form>
                    </div>
               </div>
               <div class="postbox" style="width:49%; float: right;">
                    <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Instructions', 'recipe-press'); ?></h3>
                    <div style="padding:8px;">

                    </div>
               </div>
          </div>
     </div>

     <div class="cleared">
          <?php include($rp_import->pluginPath . 'includes/footer.php'); ?>
     </div>
</div>
