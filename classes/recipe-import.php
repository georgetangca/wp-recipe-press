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
               $recipes = $this->t_20minclub_recipe($_FILES['import-file'], $data['import-category']);
               
			//var_dump($recipes);
			   
               if ( is_array($recipes)) {
                    $this->results = '<ul>';

                    foreach ( $recipes as $recipe ) {
					
                         $this->results.= '<li>' . __('Importing recipe: ', 'recipe-press') . $recipe['title'] . '</li>';
						 
						//  echo('update!!!!!!!!!!!!!'.'<br/>');
							//	var_dump($recipe);
						//	echo('!!!!!!!!!!!!'.'<br/>');
								
                         //$recipe = $this->input($recipe);
						 
						// echo('afterinput!!!!!!!!!!!!!'.'<br/>');
						//		var_dump($recipe);
						//		echo('!!!!!!!!!!!!'.'<br/>');
								
						 

                         $post = array(
                              'post_title' => $recipe['title'],
							  'post_name' => $recipe['slug'],
							  
                              'comment_status' => 'open',
                              'ping_status' => 'open',
                              'post_author' => $recipe['user_id'], // $current_user->ID, ?????? george doubt 
                              //'post_content' => $recipe['notes'],
                              
                              'post_date' => date('Y-m-d H:i:s'),
                              'post_date_gmt' => gmdate('Y-m-d H:i:s'),
                             // 'post_excerpt' => $recipe['notes'],
                              'post_status' => $recipe['status'],
                              'post_type' => 'recipe',
							 'ext_id' => $recipe['id'],
							  'ext_src' => '20msc'
							 // 'ext_id_parent' => $recipe['recipe_id']
							  
                         );
                         
                        // if (empty($post['post_author'])){
                         //    $post['post_author']  = $current_user->ID;
                        // }

                         remove_action('save_post', array(&$this, 'save_recipe'));

                         if ( $post = wp_insert_post($post) ) {
                              $postData = get_post($post);
								echo("<br/>importing taxonomies");
							//	var_dump($recipe['recipe-category']);
                              /* Handle taxonomies */

                              if ( is_array($recipe['recipe-category']) ) {
                             

                                   $affectedc = wp_set_object_terms($post, $recipe['recipe-category'], 'category');
								//   echo "<br/>"; var_dump($affectedc);
								     $affectedt = wp_set_object_terms($post, $recipe['recipe-category'], 'post_tag');
								//   echo "<br/>"; var_dump($affectedt);
                              }

                              /* Save custom fields */
                              
                              /* Keep the orginal one from 20minutes */
								
							  
                                update_post_meta($post, '_recipe_tmsc_id', $recipe['id']);
                                update_post_meta($post, '_recipe_tmsc_parent_id', $recipe['recipe_id']);
                                update_post_meta($post, '_recipe_slug', $recipe['slug']);
                                update_post_meta($post, '_recipe_copy_1stparty_site', $recipe['copy_1stparty_site']);
                                update_post_meta($post, '_recipe_copy_1stparty_newsletter', $recipe['copy_1stparty_newsletter']);
                                update_post_meta($post, '_recipe_copy_1stparty_promos', $recipe['copy_1stparty_promos']);
								
                               // update_post_meta($post, '_recipe_image_1stparty_site', $recipe['image_1stparty_site']);
								
                                update_post_meta($post, '_recipe_image_1stparty_newsletter', $recipe['image_1stparty_newsletter']);
                                update_post_meta($post, '_recipe_image_1stparty_promos', $recipe['image_1stparty_promos']);
                                update_post_meta($post, '_recipe_image_3rdparty', $recipe['image_3rdparty']);
                                update_post_meta($post, '_recipe_tmsc', $recipe['tmsc']);
				
							   /*20minutes End */     

                               if ($recipe['image_id']) {
                                    
                                     update_post_meta($post, '_thumbnail_id', $recipe['image_id']);
                                }
       
                                update_post_meta($post, '_recipe_prep', $recipe['prep_time']);
                                update_post_meta($post, '_recipe_cook', $recipe['cook_time']);
                                update_post_meta($post, '_recipe_servings', $recipe['servings']);
                               // update_post_meta($post, '_recipe_serving_size_value', recipe['serving_size']); //serving size remove
                                update_post_meta($post, "_recipe_nutrients", $recipe["nutrient_per_serving"]);
                                update_post_meta($post, "_recipe_photo_credit", $recipe["photo_credit"]);
                                update_post_meta($post, "_recipe_author", $recipe["author"]);
                               // update_post_meta($post, "ratings_users", $recipe["ratings_users"]);
                               // update_post_meta($post, "ratings_average", $recipe["ratings_average"]);
                               // update_post_meta($post, "ratings_score", $recipe["ratings_score"]); 
                               // update_post_meta($post, "_recipe_subtitle", $recipe["subtitle"]); 
							   
							   
								update_post_meta($post, "_recipe_subtitle", $recipe["notes"]); 
									//corey added
							   
								update_post_meta($post, "_recipe_submitter", $recipe["submitter_id"]); 
								update_post_meta($post, "_recipe_submitter_email", $recipe["submitter_email"]);
								
								update_post_meta($post, "_recipe_cuisine", $recipe["cusine"]);
								
								update_post_meta($post, "_recipe_main", $recipe["main"]); 
								update_post_meta($post, "_recipe_type", $recipe["type"]); 
								update_post_meta($post, "_recipe_preparation", $recipe["preparation"]); 
								update_post_meta($post, "_recipe_hints", $recipe["hints"]); 

                              /* Save ingredients */
                              $this->save_ingredients($post, $recipe['ingredients']);
							 //var_dump($recipe['instructions']);
                              $this->save_instructions($post, $recipe['instructions']);
                         }
                    }
                    $this->results.= '</ul>';
               }
          }
     }

     
      /**
      * Import recipes in the from 20 minutes club format.      *
      * @param array $file
      * @param integer $category
      * @return array
      * @author :George
      */
     public function t_20minclub_recipex($file, $category) {
         if(empty($file['tmp_name'])){
             return false;    
         }
         
          if ( $file['type'] == 'application/zip' ) {
               $allRecipes = array();
               $zip = zip_open($file['tmp_name']);

               while ($infile = zip_read($zip)) {
                    $data = zip_entry_read($infile, zip_entry_filesize($infile));
                    if ( $data = @simplexml_load_string($data) ) {
                         $recipes = $this->t_20minclub_recipe_read($data, $category);
                         $allRecipes = array_merge($allRecipes, $recipes);
                    }
               }

               zip_close($zip);
          } else {
				$allRecipes = array();
               $data = simplexml_load_file($file['tmp_name']);
			   

               $allRecipes = $this->t_20minclub_recipe_read($data, $category);

			   
          }

          return $allRecipes;
     }
	 
	 public function t_20minclub_recipe($file, $category) {
         if(empty($file['tmp_name'])){
             return false;    
         }
         
         
			   $allRecipes = array();
				
               $data = simplexml_load_file($file['tmp_name']);
			   

               $allRecipes = $this->t_20minclub_recipe_read($data, $category);

	

          return $allRecipes;
     }
     
     
     /**
      * Read in each entry in the canadianfamily file.
      *
      * @global object $wpdb
      * @global object $current_user
      * @param array $data
      * @param integer $category
      * @return array
      * @author: George
      */
     protected function t_20minclub_recipe_read($data, $category) {
          global $wpdb;
		  
		 // var_dump($data);//ok to here
		  
          $allRecipes = array();
		  
		  
			$i = 0;
		  
          foreach ( $data->{'recipes'} as $recipe ) {
               unset($newRecipe);
			   
				
			   
			   //echo("count:".$i."-".$recipe->slug."-".$recipe->id."<br/>");
			   $i = $i + 1;
			   
               $image_id = $this->get_recipe_imageid((string)$recipe->image_url);

               $newRecipe = array(
                 
                   //below keep for 20minutes 
					//part_id
					'id'             => (int) $recipe->id,
					//parent_id
                   'recipe_id'      => (int) $recipe->recipe_id,
				   
                   'title'   => (string) $recipe->title,
				   //nice name
				   'slug'           => (string) $recipe->slug,
				   
				    'submitter_id' => (string) $recipe->submitter_id,
					'submitter_email' => (string) $recipe->submitter_email,
					
					'cusine' => (string) $recipe->cusine,
					'main' => (string) $recipe->main,
					'type' => (string) $recipe->type,
					'preparation' => (string) $recipe->preparation,
								
					'subtitle' => (string)$recipe->headline,
					 'notes'   => (string) $recipe->dek, 
				   'author' => (string)$recipe->byline,
					'photo_credit' => (string)$recipe->photographer, 
                   
					
                   'copy_1stparty_site' => (string) $recipe->copy_1stparty_rights_site,
                   'copy_1stparty_newsletter' => (string) $recipe->copy_1stparty_rights_newsletter,
                   'copy_1stparty_promos' => (string) $recipe->copy_1stparty_rights_promos,
				   
				   'copy_3rdparty_rights' => (string) $recipe->copy_3rdparty_rights,
				   
        
                   'image_1stparty_newsletter' => (string) $recipe->image_1stparty_rights_newsletter,
                   'image_1stparty_promos' => (string) $recipe->image_1stparty_rights_promos,
				   
                   'image_3rdparty' => (string) $recipe->image_3rdparty_rights,
				   
				   'nutrient_per_serving' => (string)$recipe->nutrients, //null
				   
				   'prep_time' => (string) $recipe->prep_time,
                   'cook_time' => (string) $recipe->cooking_time,
				   
                   'recipe-category' => $category, //? need input 
                   'servings' => (string) $recipe->serves_min, 
				   
                   'tmsc' => (string) $recipe->tmsc,
                
                 
                    'image_url' => (string)$recipe->image_url,
                   
                    'image_id' => (int)$image_id,
					
					'hints' => (string)$recipe->how_to,
  
					'user_id' => 1,

                    'instructions'  => '',
                    'ingredients'   => '',
                   
                   
					'ispublic' => (string) $recipe->is_public,
                   
                    'status' => 'pending',
                   
                    'updated' => (string)$recipe->entry_date,                   
                   
               );
			  // echo('read****************'.'<br/>');
			  // var_dump($newRecipe);
			//	echo('****************'.'<br/>');
				
               /* Check to see if recipe fits into an existing category */
               $newRecipe['recipe-category'] = array();
			   
			   $pcats = array();
			 
			   array_push($pcats, "20msc-import"); 
			   
			   if((string) $recipe->cusine != '')
				{
			   array_push($pcats, (string) $recipe->cusine); 
				}
				
				
				if((string) $recipe->main != '')
				{
			   array_push($pcats, (string) $recipe->main); 
				}
				
				if((string) $recipe->type != '')
				{
			   array_push($pcats, (string) $recipe->type); 
				}
				
				if((string) $recipe->preparation != '')
				{
			   array_push($pcats, (string) $recipe->preparation); 
				}
			   
			   
			  
			
				//echo("<br/>checking categories:");
				//var_dump($pcats);
			
              /*
			  foreach ( $pcats as $category ) {
					 echo term_exists($category);
					if($category != '')
					{
						if ( $term = term_exists($category) or $term = term_exists(rp_inflector::plural($category, 2)) ) {
							 if ( $term ) {
								  $newRecipe['recipe-category'][] = $term;
							 }
						} else {
							 $term = wp_insert_term($category, 'recipe-category');
							 if ( $term ) {
								  $newRecipe['recipe-category'][] = $term;
							 }
						}
					}
						
			   }
			   */
			   
			   $newRecipe['recipe-category'] = $pcats;
			   
			  // echo("<br/>newrecipe-categories:");
			  // var_dump($newRecipe['recipe-category']);
               
               $arrayIngredient =  $newIngredient = $newIngredients = array();

               /* Method for getting ingredients when there are no divisions */
               $newIngredients =  explode(":", $recipe->ingredients);
               $j = 1;         
               
               array_push($arrayIngredient,$newIngredient);//just for compatible with later this->input function no actual meaning 
               
               foreach ( $newIngredients as $ingredient ) {
                     $newIngredient['item'] = $j ;
                     $newIngredient['new-ingredient'] = $ingredient;
                     $j++;
                     array_push($arrayIngredient,$newIngredient);
               } 
              $newRecipe['ingredients'] = $arrayIngredient;
              
              $arrayInstruction = $newInstruction = $newInstrunctions = array();

               /* Method for getting ingredients when there are no divisions */
               $newInstrunctions =  explode(':', $recipe->instructions);
               $j = 1;
               
               array_push($arrayInstruction,$newInstruction);//just for compatible with later this->input function no actual meaning 
               
               foreach ( $newInstrunctions as $instruction ) {
                     $newInstruction['item'] = $j ;
                     $newInstruction['new-instruction'] = $instruction;
                     $j++;
                     array_push($arrayInstruction,$newInstruction);
               } 
              $newRecipe['instructions'] = $arrayInstruction;
              
               array_push($allRecipes, $newRecipe);
          }

          return $allRecipes;
     }
     

     protected function get_recipe_imageid($filename) {
		
	 
       if(empty($filename))return NULL;
       
       global $wpdb;
       $wp_filetype = wp_check_filetype(basename($filename), null );
       $args = array(
             'post_type' => 'attachment',
             'post_mime_type' => $wp_filetype['type'],
             'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
             'post_content' => '',
             'post_status' => 'inherit',                                   
          ); 
		  
		 
		  
       $query = '
            SELECT `ID`
            FROM `' . $wpdb->posts . '`
            WHERE `post_type` = "attachment"
                 AND `post_title` = "' . $args['post_title'].'"
                 AND `post_content` = "" 
                 AND `post_mime_type` = "'.$wp_filetype['type'].'"
                 AND `post_status` = "inherit"
       ';

      $image_list =  $wpdb->get_col($query); 
	

	  
      $image_id = array_pop($image_list);
      return $image_id;      
    }
     
     
     
}



$rp_import = new recipe_press_import($_POST);
?>

<div class="wrap">
     <div class="icon32" id="icon-recipe-press"><br/></div>
     <h2><?php echo $rp_import->pluginName; ?> &raquo; <?php _e('Import', 'recipe-press'); ?></h2>
     <p><?php _e('This page allows you to import recipes formated from "20Minutes Club"..', 'recipe-press'); ?></p>
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
                                                            <option value="t_20minclub_recipe"><?php _e('20 Minute Recipe File', 'recipe-presss'); ?></option>
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
