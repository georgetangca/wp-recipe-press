<?php 
  $search         = $_POST['recipe_search'];
?>	

<script>
		//Load More Functionality
		
		//<- Sets the inital offset to zero
		var s = 0, per_page = 10; 
                var sh = "<?php echo $search; ?>";

		//Show loading icon
		function showLoading(){
			jQuery("#loading_more").show("slow");
					
		}
		
		//Hide loading bar
		function hideLoading(){
			jQuery("#loading_more").hide("slow");
		}
		
		
		jQuery(document).ready(function(){
                    
			jQuery('#load_more').click(function(e){

			//Increase s by 10, to get the next 10 posts
			s = s + per_page;
	
			showLoading();
			
			//Check if checkboxes are already checked and load content 
			var arr = jQuery(":checkbox:checked").map(function() { 
                    
				return this.value + "%2B";
		 
        	}).get();

			//Sort
    		var mysorted = jQuery('.active_sort').attr('title');			
			
			e.preventDefault();
			
				jQuery.ajax({
					type: "POST",
					url: "/wp-content/plugins/recipe-plus/templates/recipe-box/recipe-box-filter-more.php",
                                        
                                        data:  "mydata="+arr+"&offsetvar="+s+"&sorteddata="+mysorted+"&recipe_search="+sh,
					dataType: "text",
					success: function(data) {
							jQuery('#load_more_content').append(data);
							hideLoading();
						}
				});				
				
				jQuery.ajax({
					type: "POST",
					url: "/wp-content/plugins/recipe-plus/templates/recipe-box/recipe-box-filter.php",
                                        data:  "numbershowing="+s,
					dataType: "text",
					success: function(data) {
							var addResults = per_page + s;
							jQuery('#number_showing').text(addResults);
							
					}
				});	

			
			}); //end .click
	
	
		});//end jQuery
		</script>
			
            
        <?php 
        $recipe_array = $_POST['mydata'];
        $sorted_array = $_POST['sorteddata'];
      	//$number_showing = $_POST['numbershowing'];
        $search         = $_POST['recipe_search'];
        
		
        $recipe_array = preg_replace( "/\,/", "", $recipe_array); 
        $trimmed = rtrim($recipe_array,"+");

        //echo $trimmed;


        $path = $_SERVER['DOCUMENT_ROOT'];			

        //Switch ASC and DESC for title and date, respectively
        if ($sorted_array == "title"){
                $the_order = "ASC";
        }elseif ($sorted_array == "date"){
                $the_order = "DESC";
        }else{
                $the_order = "ASC";
        }

        //Sorting for the post's rating
        if ($sorted_array == "highest_rating"){
                $the_rating_order = "highest_rated";
                $the_order = null;
        }else{
                $the_rating_order = null;
        }

        if ($sorted_array == "with_notes"){
                $the_with_notes_order = "with_notes";
                $the_order = null;
        }else{
                $the_with_notes_order = null;
        }

        ?>


        <?php 
        define('WP_USE_THEMES', false);
        require("$path/wp-load.php");
        ?>
		
	
                
        <?php 
    	//QUERY FOR ALL RECIPES (not limited by 10 posts)
        //QUERY FOR ALL RECIPES (not limited by 10 posts)
    	
        $all_posts =  get_recipe_box_filter_entries(array('tag'=>"$trimmed", 'recipe_search'=>"$search" ));
        
        	
	//START LOOP
    	if ($all_posts->have_posts()): while ($all_posts->have_posts()) : $all_posts->the_post(); 
    	
    	//GETS ALL TAGS FOR RETURNED RECIPES.  (Creates seperate arrays)
    	$get_tags = wp_get_post_tags($post->ID);
    	
    	foreach ($get_tags as $the_tags){
    		$tagnames[] = $the_tags->slug;
    	}
    	
    	//END LOOP
	endwhile; endif; 
    	
    	//Eliminate duplicate tags, creating array with all tags in posts in the loop
    	if($tagnames!= ""){
            $unique_all_tags = array_unique($tagnames);
	}
	?>
				
        <script>
        jQuery(document).ready(function(){

                //Convert php array to javasrcipt array
                var tagArray = ["spacer1","spacer2"];
                <?php 
                if(is_array($unique_all_tags) and count($unique_all_tags)>0 ) {
                    foreach($unique_all_tags as $key){ ?>

                    tagArray.push("<?php echo $key ?>");

                <?php 
                     } 
                 }
                ?>

                //Go through each input and compare the id to see if it's in the array
                //Disable the checkbox if it's not
                jQuery('.recipe_control_list input, .more_control_list input').each(function () {
                var currentList = jQuery(this);

                //
                if (jQuery.inArray(currentList.val(), tagArray) <= 0) {
                        currentList.attr('disabled', true);
                        currentList.parent("li").css("color", "#CCCCCC");      		
                } else {
                        currentList.attr('disabled', false);
                        currentList.parent("li").css("color", "#000000");
                }
                });

    });//end jQuery
        </script>
                
                
                
                
        <?php if($trimmed != ''){ ?>
        
        <!-- Slide down the sorting options -->
        <script>
        jQuery("#sorting_options").show();
        </script>        
        
		<a class="back_button" href=""></a>
						
		<div id="recipe_result_wrapper">	
		
		

    	
    	<?php
    	//The Checked Recipe Query
    	rewind_posts();
    	    
            $posts_returned =  get_recipe_box_filter_entries(
                    array(
                         'posts_per_page' =>10,
                         'r_sortby' => "$the_rating_order", 
			 'order' => "$the_order", 
			 'orderby' => "$sorted_array", 
			 'tag' => ("$trimmed"),
                         'recipe_search' =>"$search"
                        ),
                        $the_with_notes_order
                    );
            
		?>      
		
				
		<div id="showing_results">
                    Showing <span id="number_showing"><?php echo $posts_returned->post_count ?></span> of <?php echo $posts_returned->found_posts ?> recipes
                </div>
		
		<?php if ($posts_returned->have_posts()): while ($posts_returned->have_posts()) : $posts_returned->the_post(); ?>
                
                <div  id="recipe_box_entry_<?php the_id(); ?>"  class="recipe_box_topic_post_wrapper" >
               
                    <?php //echo "my... ". $recipe_array; ?>
                	
                       <div class="topic_photo">
       				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('topic-thumbnail', array('alt' => ''.get_the_title().'', 'title' => $recipeData->entries[get_the_id()]['notes'])); ?></a>
        		</div><!-- end .topic_photo -->
                        
                        <div class="recipe_topic_content" >
                            <div class="topic_title">
                                    <a href="<?php the_permalink(); ?>" title= <?php echo $recipeData->entries[get_the_id()]['notes']; ?> ><h3><?php echo the_title(); ?></h3></a>
                            </div><!-- end .topic_title -->

                            <div class="topic_excerpt">
                            <?php echo get_post_meta($post->ID, '_recipe_subtitle', true); ?>
                            </div>

                            <div id="ratings">
                            <?php if(function_exists('the_ratings')) { the_ratings(); } ?>
                            </div>
                            
                            <div class="recipe-meta">
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
                      
                
                </div><!-- end .recipe_box_recipe_box_topic_post_wrapper -->
            
            

      
                <?php endwhile; ?>
        
        
        <div id="load_more_content"></div>	
     
        <div id="load_more">
			
        	
			<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/load-more.png" /></a>
			<div id="loading_more">
        		<img src="<?php bloginfo('template_url'); ?>/images/twitterload.gif" />
        	</div>	
		</div>
        
       <?php else: ?>

       <?php echo '<div class="recipe_box_topic_post_wrapper"><p>Sorry, we don\'t have any recipes in those categories yet. Try selecting fewer categories or check back soon!</p></div>'; ?>
        
		<?php endif; ?> 

	</div><!-- end #recipe_result_wrapper -->


	<!-- ELSE -->
	<?php } else { //This is the content loaded if there are no check boxes checked?>
	
		<?php// echo "Tags: " . $trimmed . "  ,Order: " . $the_order . "  ,Sortedby: " . $sorted_array?>
		
		
		<!-- Slide down the sorting options -->
	    <script>
	    jQuery("#sorting_options").show();
	    jQuery("#showing_results").css('top', '-66px'); //need because back butt isn't there
	    
	    //Remove all DISABLED 
            /*
                jQuery('.recipe_control_list input, .more_control_list input').each(function () {
                }
    		var currentList = jQuery(this);
    
    		currentList.attr('disabled', false);
    		currentList.parent("li").css("color", "#000000");
		});
             */   
	    </script>     
	
        <?php
    	//The Checked Recipe Query
    	rewind_posts();
    	/*
		$all_tags = new WP_Query(array( 
		//	'post_type' => array('recipes'), 
                    	'post_type' => array('recipe'), 
                    
			'posts_per_page' => 10, 
			'r_sortby' => "$the_rating_order", 
			'order' => "$the_order", 
			'orderby' => "$sorted_array", 
		)); 
          */      
                
            $all_posts =  get_recipe_box_filter_entries(
                    array(
                         'posts_per_page' =>10,
                         'r_sortby' => "$the_rating_order", 
			 'order' => "$the_order", 
			 'orderby' => "$sorted_array", 
                         'recipe_search' =>"$search"
                        ),
                        $the_with_notes_order
                    );    
           
                global $recipeData;
                
		?>
            
            
        
		<div id="showing_results">
                    Showing <span id="number_showing"><?php echo $all_posts->post_count ?></span> of <?php echo $all_posts->found_posts ?> recipes
                </div>
		
		<?php if ($all_posts->have_posts()): while ($all_posts->have_posts()) : $all_posts->the_post(); ?>
                <div  id="recipe_box_entry_<?php the_id(); ?>"  class="recipe_box_topic_post_wrapper" >
               
                    <?php //echo "my... ". $recipe_array; ?>
                	
                       <div class="topic_photo">
       				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('topic-thumbnail', array('alt' => ''.get_the_title().'', 'title' => $recipeData->entries[get_the_id()]['notes'])); ?></a>
        		</div><!-- end .topic_photo -->
                        
                        <div class="recipe_topic_content" >
                            <div class="topic_title">
                                    <a href="<?php the_permalink(); ?>" title= <?php echo $recipeData->entries[get_the_id()]['notes']; ?> ><h3><?php echo the_title(); ?></h3></a>
                            </div><!-- end .topic_title -->

                            <div class="topic_excerpt">
                            <?php echo get_post_meta($post->ID, '_recipe_subtitle', true); ?>
                            </div>

                            <div id="ratings">
                            <?php if(function_exists('the_ratings')) { the_ratings(); } ?>
                            </div>
                            
                            <div class="recipe-meta">
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
                      
                
                </div><!-- end .recipe_box_recipe_box_topic_post_wrapper -->
            
                
          
            <?php endwhile; ?>
            
             <div id="load_more_content">
			
	     </div>	
        
        
        <div id="load_more">
			
        	
		<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/load-more.png" /></a>
			<div id="loading_more">
        		<img src="<?php bloginfo('template_url'); ?>/images/twitterload.gif" />
        	</div>	
		</div>

           <?php else: echo '<div class="recipe_box_topic_post_wrapper"><p>Sorry, we don\'t have any recipes in those categories yet. Try selecting fewer categories or check back soon!</p></div>'; ?>
            
			<?php endif; ?> 
	
        <?php } ?>
