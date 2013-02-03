
            <!-- This is the code loaded into recipe-filter.php -->
            <?php 
            
            $recipe_array = $_POST['mydata'];
	    $sorted_array = $_POST['sorteddata']; 
            $search         = $_POST['recipe_search'];
    
            $recipe_array = preg_replace( "/\,/", "", $recipe_array); 
            $trimmed = rtrim($recipe_array,"+");


            $offset_var = $_POST['offsetvar'];


            $path = $_SERVER['DOCUMENT_ROOT'];			

            //Switch ASC and DESC for title and date, respectively
            if ($sorted_array == "title"){
                    $the_order = "ASC";
            } elseif ($sorted_array == "date"){
                    $the_order = "DESC";
            } else{
                    $the_order = "ASC";
            }

            //Sorting for the post's rating
            if ($sorted_array == "highest_rating"){
                    $the_rating_order = "highest_rated";
                    $the_order = null;
            } else {
                    $the_rating_order = null;
            }
            
            
            if ($sorted_array == "with_notes"){
                $the_with_notes_order = "with_notes";
                $the_order = null;
            } else {
                $the_with_notes_order = null;
            }

             ?>


            <?php 
            define('WP_USE_THEMES', false);
            //require("$path/wp-blog-header.php"); 
            require("$path/wp-load.php"); 
            ?>

   	
            
<div id="recipe_result_wrapper">	
			
			
    	<?php 
         /*
         $loadmore_posts = new WP_Query(array( 'post_type' => array('recipes'), 'offset' => $offset_var, 'posts_per_page' => 10, 'r_sortby' => "$the_rating_order", 'order' => "$the_order", 'orderby' => "$sorted_array", 'tag' => ("$trimmed"))); 
         */
         
         $loadmore_posts =  get_recipe_box_filter_entries(
                    array(
                         'posts_per_page' =>10,
                         'r_sortby' => "$the_rating_order", 
			 'order' => "$the_order", 
			 'orderby' => "$sorted_array", 
                         'offset' => $offset_var,
                         'tag' => "$trimmed",
                         'recipe_search'=>"$search"
                        ),
                        $the_with_notes_order
                    );            
        ?>  
			
                
			<?php if ($loadmore_posts->have_posts()): while ($loadmore_posts->have_posts()) : $loadmore_posts->the_post(); ?>  				
                <div  id="recipe_box_entry_<?php the_id(); ?>"  class="recipe_box_topic_post_wrapper">
               
			   <?php //echo "my... ". $recipe_array; ?>
                            <div class="topic_photo">
       				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('topic-thumbnail', array('alt' => ''.get_the_title().'', 'title' => $recipeData->entries[get_the_id()]['notes'])); ?></a>
                            </div><!-- end .topic_photo -->
                
                            
                            <div class="recipe_topic_content" >
                        
                                <div class="topic_title">
                                    <a href="<?php the_permalink(); ?>" title=<?php echo $recipeData->entries[get_the_id()]['notes']; ?>  ><h3><?php echo the_title(); ?></h3></a>
        			</div><!-- end .topic_title -->
      			
                                <div class="topic_excerpt">
                                <?php echo get_post_meta($post->ID, '_recipe_subtitle', true); ?>
                                </div>

                                <div id="ratings">
                                   <?php if(function_exists('the_ratings')) { the_ratings(); } ?>
                                </div>
                                
                                <div class="recipe-meta">
                                    <?php _e('Status', 'recipe-press'); ?>: <?php _e(get_post_status(get_the_id()),'recipe-press'); ?>|
                                    <?php edit_recipe_post_link(__('Edit', 'recipe-press'), '<span class="edit-link">', '</span>' , get_the_id()); ?>
                                </div>
                           
                            </div>
                           
                            <div class="recipe_box_notes">
                                <?php the_recipe_box_notes_link(); ?> 
                                <div id="recipe_notes_<?php the_id(); ?>" style="display: none">
                                    <?php the_recipe_notes_form(); ?>                              
                                </div>
                            
                            </div>  
                       
                            <div class="recipe_box_delete">
                               <a href="<?php the_recipe_box_url(); ?>" 
                                  onclick="return recipe_box_remove_recipe(<?php the_id(); ?>, '<?php echo wp_create_nonce(get_the_title()); ?>')">
                                  <img src="<?php echo plugins_url('/recipe-plus/images/icons/delete.gif'); ?>" /></a>
                            </div>  

                </div><!-- end .recipe_box_topic_post_wrapper -->
            
          
            <?php endwhile; ?>
           
           <?php else: echo "<script>jQuery('#load_more').hide();</script><div class='recipe_box_topic_post_wrapper'><p>Sorry, that's all the recipes in those categories. Try selecting fewer categories or check back soon!</p></div>"; ?>
            
			<?php endif; ?> 


			


		


	        
	
