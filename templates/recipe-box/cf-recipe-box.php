<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * main.php - The main recipe box display.
 *
 * @package RecipePress
 * @subpackage templates/recipe-box
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 2.2
 */
?>

<?php
 // wp_register_script('recipe-filter-age-stage', (get_bloginfo('template_directory') . '/js/recipe-filter-food-recipes.js'), array('jquery'), '1.0' );
 // wp_enqueue_script('recipe-filter-age-stage');
?>

<?php   
   $search = $_POST['recipe_search'];     
?>

<script>
    <!-- JQUERYFOR CHECKBOX ARRAY -->
    
    var sh = "<?php echo $search; ?>";
    
    
    
jQuery(document).ready( function() {
	
	//hide #sorting_options
	jQuery("#sorting_options").hide();				
				 
	hideLoading();						 
	
		
	//show loading bar
	function showLoading(){
		jQuery("#loading").show("slow");
			
			
	}

	//hide loading bar
	function hideLoading(){
		jQuery("#loading").hide("slow");
	};
		
	 
	 
	//Check if checkboxes are already checked and load content 
	var arr = jQuery(".checkBox:checked").map(function() { 
                
		return this.value + "%2B";
		
				 
    }).get();
      
    //Sort
    var mysorted = jQuery('.active_sort').attr('title');   
												   
														
	
	jQuery.ajax({
		type: "POST",
		url: "/wp-content/plugins/recipe-plus/templates/recipe-box/recipe-box-filter.php",
		data:  "mydata="+arr+"&sorteddata="+mysorted+"&recipe_search="+sh,
		dataType: "text",
		success: 
		function(data) 
		{ 

			jQuery('#jquerystuff').empty().append(data);
			
			hideLoading();
			//jQuery("#jquerystuff").fadeIn("slow");
			jQuery("#jquerystuff").fadeIn("fast");
			
		}, 
		error: 
		function() 
		{ 
			jQuery("#jquerystuff").append("Oops...we had a problem loading the recipes.  Try again later"); 
		} 
	
	}); 
		 
		
			
	//Loading content function for changes to checkboxes 
    jQuery(function() {

		//Checkbox actions 			
        jQuery(":checkbox").live("change", function() {
        

			
			jQuery(".checkBox:checkbox").blur();
    		//jQuery(".checkBox:checkbox").focus();
			
			jQuery("#jquerystuff").fadeOut("slow");
			showLoading();
			
			
            var arr = jQuery(".checkBox:checked").map(function() { 
                
				return this.value + "%2B";
				
				
            }).get();

       		//Sort
   			var mysorted = jQuery('.active_sort').attr('title');

    
		 	jQuery.ajax({
				type: "POST",
				url: "/wp-content/plugins/recipe-plus/templates/recipe-box/recipe-box-filter.php",
                                data:  "mydata="+arr+"&sorteddata="+mysorted+"&recipe_search="+sh,
				dataType: "text",
				success: 
	    		function(data) 
	    		{ 
					
					
					jQuery('#jquerystuff').empty().append(data);
					
					hideLoading();
					//jQuery("#jquerystuff").fadeIn("slow");
					jQuery("#jquerystuff").fadeIn("fast");
				}, 
				error: 
	    		function() 
	    		{ 
	        		jQuery("#jquerystuff").append("Oops...we had a problem loading the recipes.  Try again later"); 
	    		} 
			
			});
		});
	});	
	
	
	 		
	//Loading content for links when clicked
	jQuery(function() {
		
		//Link actions 			
       jQuery('a[class=jquery_link]').live("click", function(e){
				
			e.preventDefault();
			
		
			var arrs = jQuery(this).attr('href');
			
			var splitValue = arrs.split("+");
								
			var inputvalue1 = "input[value="+splitValue[0]+"]";
			var inputvalue2 = "input[value="+splitValue[1]+"]";
			
			jQuery(inputvalue1).attr('checked', 'true');
			jQuery(inputvalue2).attr('checked', 'true');
			

			jQuery(inputvalue1).trigger('change');
			jQuery(inputvalue2).trigger('change');
		

		});
		
	});
			
				
	//Sort Button Links
	jQuery('a[class=sort_button]').live("click", function(e) {
 						
		e.preventDefault();


		//Remove "active_sort" class
		jQuery('#recipe_box_sorted_by li a').removeClass('active_sort');

		//Assign "active_sort" class
		jQuery(this).addClass('active_sort');
				
		
		jQuery("#jquerystuff").fadeOut("slow");
		showLoading();
		
		//Get checked boxes and load content 
		var arr = jQuery(".checkBox:checked").map(function() { 
			return this.value + "%2B";
	    }).get();
				
			
		//Sort
   		var mysorted = jQuery('.active_sort').attr('title');
  
	 	jQuery.ajax({
			type: "POST",
			url: "/wp-content/plugins/recipe-plus/templates/recipe-box/recipe-box-filter.php",
                        data:  "mydata="+arr+"&sorteddata="+mysorted+"&recipe_search="+sh,
			dataType: "text",
			success: 
			function(data) 
			{ 
		
				jQuery('#jquerystuff').empty().append(data);
				
				hideLoading();			
				
				jQuery("#jquerystuff").fadeIn("fast");
				
				
			}, 
			error: 
			function() 
			{ 
	    		jQuery("#jquerystuff").append("Oops...we had a problem loading the recipes.  Try again later"); 
			} 
		
		});

	}); //end sort button actions	
	
	

	//Back link actions 
	jQuery('a[class=back_button]').live("click", function(e) {
 						
		e.preventDefault();


		//Uncheck checkboxes
		jQuery(".checkBox").attr('checked', false);

		jQuery("#jquerystuff").fadeOut("slow");
		showLoading();
		
		var arr = jQuery(this).attr('href');
		//Sort
   		var mysorted = jQuery('.active_sort').attr('title');		
       
	//jQuery("#TestOutput").text(arr.join(','));
    

 	jQuery.ajax({
		type: "POST",
		url: "/wp-content/plugins/recipe-plus/templates/recipe-box/recipe-box-filter.php",
		data:  "mydata="+"&sorteddata="+mysorted+"&recipe_search="+sh,
		dataType: "text",
		success: 
		function(data) 
		{ 
					
			
					
					
			jQuery('#jquerystuff').empty().append(data);
			
			hideLoading();
			
			
			
			
			jQuery("#jquerystuff").fadeIn("fast");
			
			
		}, 
		error: 
		function() 
		{ 
    		jQuery("#jquerystuff").append("Oops...we had a problem loading the recipes.  Try again later"); 
		} 
	
	});

	}); //end back link actions
	
			

	
	
});//end jQuery ready
        
    	
 
jQuery(document).ready(function(){
	    //select all the a tag with name equal to modal
		jQuery('a[name=modal]').click(function(e) {
			//Cancel the link behavior
			e.preventDefault();
			//Get the A tag
			var id = jQuery(this).attr('href');
		
			//Get the screen height and width
			var maskHeight = jQuery(document).height();
			var maskWidth = jQuery(window).width();
		
			//Set height and width to mask to fill up the whole screen
			jQuery('#mask').css({'width':maskWidth,'height':maskHeight});
			
			//transition effect		
			jQuery('#mask').fadeIn(100);	
			//jQuery('#mask').fadeTo("slow",0.8);	
		
			//Get the window height and width
			var winH = jQuery(window).height();
			var winW = jQuery(window).width();
	              
			//Set the popup window to center
			jQuery(id).css('top',  winH/2-jQuery(id).height()/2);
			jQuery(id).css('left', winW/2-jQuery(id).width()/2);
		
			//transition effect
			jQuery(id).fadeIn(100); 
		
		});
		
		//if close button is clicked
		jQuery('.window .close').click(function (e) {
			//Cancel the link behavior
			e.preventDefault();
			jQuery('#mask, .window').hide();
		});		
		
		//if mask is clicked
		jQuery('#mask').click(function () {
			jQuery(this).hide();
			jQuery('.window').hide();
		});			
	            
});//end jQuery


</script>    


  <!-- My Recipe Box Title -->
  <?php if ( !empty($search)){ ?>
    <div id="recipe-box-search-title">
     <span class="search-title"> Search Result For: "<?php  echo $search; ?>" &nbsp;&nbsp;</span> <a href="">Clear the result</a>     
   </div>  
   <?php } ?>      

  <div id="profile_title">
        <span class ="recipe-box-top-title"><?php do_action( 'bp_template_recipe_box_title' );?></span>
        <div class="recipe-box-search"><?php the_recipe_box_search(); ?></div>
        <div style="clear:both;"></div></br>

        <!-- <p><a href="<?php echo bp_loggedin_user_domain() ?>recipe_form">Submit your own recipe</a></p> -->
		
  </div>

  
  
   <div id="boxes">
                            <!-- Customize your modal window here -->
                            <div id="dialog_meals" class="window dialog">
                            <!-- close button is defined as close class -->
                                    <a href="#" class="close"><img src="<?php bloginfo('template_url'); ?>/images/close.png" /></a>

                        <!-- MEAL TYPES -->
                        <div class="more_control_wrapper">

                            <p>Select even more <strong>MEAL TYPE</strong> filters:</p>

                            <ul class="more_control_list_wrapper">

                                <li class="more_control_list">  

                                    <ul>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="lunch"/>Lunch</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="side"/>Side</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="starter"/>Starter</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="brunch"/>Brunch</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="beverage"/>Beverage</li>
                                    </ul>

                                </li>	

                             </ul>

                        </div><!-- end .more_control_wrapper-->

                            </div><!-- end #dialog_meals -->

                    <!-- Customize your modal window here -->
                            <div id="dialog_ingredients" class="window dialog">
                            <!-- close button is defined as close class -->
                                    <a href="#" class="close"><img src="<?php bloginfo('template_url'); ?>/images/close.png" /></a>

                        <!-- MORE MAIN INGREDIENTS -->
                        <div class="more_control_wrapper">

                            <p>Select even more <strong>MAIN INGREDIENT</strong> filters:</p>

                            <ul class="more_control_list_wrapper">

                                <li class="more_control_list">  

                                    <ul>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="rice-and-grains"/>Rice and Grains</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="fruit"/>Fruit</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="potatoes"/>Potatoes</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="beans-and-lentils"/>Beans and Lentils</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="eggs"/>Eggs</li>
                                    </ul>

                                </li>	
                                <li class="more_control_list">   

                                    <ul>

                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="beef"/>Beef</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="fish"/>Fish</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="turkey"/>Turkey</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="dairy"/>Dairy</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="greens"/>Greens</li>

                                    </ul>

                                </li>	
                                <li class="more_control_list">    

                                    <ul>
                                            <li><input type="checkbox" class="checkBox" name="meal_type" value="pork"/>Pork</li>
                                            <li><input type="checkbox" class="checkBox" name="meal_type" value="seafood"/>Seafood</li>
                                            <li><input type="checkbox" class="checkBox" name="meal_type" value="tofu-and-soy"/>Tofu and Soy</li>
                                    </ul>

                                </li>	

                             </ul>

                        </div><!-- end .more_control_wrapper-->

                            </div><!-- end #dialog_ingredients -->


                    <!-- Customize your modal window here -->
                            <div id="dialog_occasion" class="window dialog">
                            <!-- close button is defined as close class -->
                                    <a href="#" class="close"><img src="<?php bloginfo('template_url'); ?>/images/close.png" /></a>

                        <!-- MORE MAIN OCCASION -->
                        <div class="more_control_wrapper">

                            <p>Select even more <strong>OCCASION</strong> filters:</p>

                            <ul class="more_control_list_wrapper">

                                <li class="more_control_list">  

                                    <ul>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="thanksgiving"/>Thanksgiving</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="easter"/>Easter</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="mother's-day"/>Mother's Day</li>
                                        <li><input type="checkbox" class="checkBox" name="meal_type" value="father's-day"/>Father's Day</li>
                                    </ul>

                                </li>	

                             </ul>

                        </div><!-- end .more_control_wrapper-->

                            </div><!-- end #dialog_occasion -->


                    <!-- Customize your modal window here -->
                            <div id="dialog_ages" class="window dialog">
                            <!-- close button is defined as close class -->
                                    <a href="#" class="close"><img src="<?php bloginfo('template_url'); ?>/images/close.png" /></a>

                        <!-- MORE AGES & STAGES -->
                        <div class="more_control_wrapper">
                            <p>Select even more <strong>AGES &amp; STAGES</strong> filters:</p>
                            <ul class="more_control_list_wrapper">
                                <li class="more_control_list">  
                                    <ul>
                                     <li><input type="checkbox" id="Ages_Stages_13-16" class="checkBox" name="meal_type" value="ages-stages-13-16"/>13-16</li>
                                    </ul>

                                </li>	                    

                             </ul>

                        </div><!-- end .more_control_wrapper-->

                            </div><!-- end #dialog_ages -->

                            <div id="mask"></div>

                </div><!-- end #boxes -->

   <div id="recipe-box-wrapper">        	
              <!--More Check Start -->
              <!-- RECIPES PAGE "MORE" CHECKBOXES : HIDDEN BY DEFAULT -->
              
                <!--More check End -->

		<!-- Include the recipe checkboxes -->
		<div class="recipe_control_wrapper">
                           <p>Select one or more of the following filters to find the recipes you&#39;	re looking for:</p>

                            <ul class="recipe_control_list_wrapper">
                                <li class="recipe_control_list">  
                                    <p>MEAL TYPE</p>
                                        <ul>
                                        <li><input type="checkbox" id="Dinner" class="checkBox" name="meal_type" value="dinner"/>Dinner</li>
                                        <li><input type="checkbox" id="School Lunch" class="checkBox" name="meal_type" value="school-lunch"/>School Lunch</li>
                                        <li><input type="checkbox" id="Dessert" class="checkBox" name="meal_type" value="dessert"/>Dessert</li>
                                        <li><input type="checkbox" id="Breakfast" class="checkBox" name="meal_type" value="breakfast"/>Breakfast</li>
                                        <li><input type="checkbox" id="Snack" class="checkBox" name="meal_type" value="snack"/>Snack</li>
                                    </ul>
                                    <a href="#dialog_meals" name="modal" class="more_click">See more</a>
                                </li>	
                                <li class="recipe_control_list">   
                                    <p>MAIN INGREDIENT</p>
                                        <ul>
                                        <li><input type="checkbox" id="Chicken" class="checkBox" name="meal_type" value="chicken"/>Chicken</li>
                                        <li><input type="checkbox" id="Cheese" class="checkBox" name="meal_type" value="cheese"/>Cheese</li>
                                        <li><input type="checkbox" id="Pasta" class="checkBox" name="meal_type" value="pasta"/>Pasta</li>
                                        <li><input type="checkbox" id="Chocolate" class="checkBox" name="meal_type" value="chocolate"/>Chocolate</li>
                                        <li><input type="checkbox" id="Vegetables" class="checkBox" name="meal_type" value="vegetables"/>Vegetables</li>
                                    </ul>
                                    <a href="#dialog_ingredients" name="modal" class="more_click">See more</a>
                                </li>	
                                <li class="recipe_control_list">    
                                    <p>OCCASION</p>
                                        <ul>
                                        <li><input type="checkbox" id="Bake Sale" class="checkBox" name="meal_type" value="bake-sale"/>Bake Sale</li>
                                        <li><input type="checkbox" id="Birthday" class="checkBox" name="meal_type" value="birthday"/>Birthday</li>
                                        <li><input type="checkbox" id="Family Gathering" class="checkBox" name="meal_type" value="family-gathering"/>Family Gathering</li>
                                        <li><input type="checkbox" id="Christmas" class="checkBox" name="meal_type" value="christmas"/>Christmas</li>
                                                        <li><input type="checkbox" id="Halloween" class="checkBox" name="meal_type" value="halloween"/>Halloween</li>
                                    </ul>
                                    <a href="#dialog_occasion" name="modal" class="more_click">See more</a>
                                </li>	
                                <li class="recipe_control_list">  
                                    <p>PREP TIME</p>
                                        <ul>
                                        <li><input type="checkbox" id="Under 10 Mins" class="checkBox" name="meal_type" value="under-10-mins"/>Under 10 Mins</li>
                                        <li><input type="checkbox" id="Under 20 Mins" class="checkBox" name="meal_type" value="under-20-mins"/>Under 20 Mins</li>
                                        <li><input type="checkbox" id="Under 30 Mins" class="checkBox" name="meal_type" value="under-30-mins"/>Under 30 Mins</li>
                                        <li><input type="checkbox" id="30 minutes or more" class="checkBox" name="meal_type" value="30-minutes-or-more"/>30 Mins Plus</li>
                                    </ul>
                                    <!-- <a href="#dialog_prep" name="modal" class="more_click">See more</a> -->
                                </li>	
                                <li class="recipe_control_list">  
                                    <p>AGES &amp; STAGES</p>
                                        <ul>
                                        <li><input type="checkbox" id="Ages_Stages_0-1" class="checkBox" name="meal_type" value="ages-stages-0-1"/>0-1</li>
                                        <li><input type="checkbox" id="Ages_Stages_1-2" class="checkBox" name="meal_type" value="ages-stages-1-2"/>1-2</li>
                                        <li><input type="checkbox" id="Ages_Stages_3-5" class="checkBox" name="meal_type" value="ages-stages-3-5"/>3-5</li>
                                        <li><input type="checkbox" id="Ages_Stages_6-8" class="checkBox" name="meal_type" value="ages-stages-6-8"/>6-8</li>
                                        <li><input type="checkbox" id="Ages_Stages_9-12" class="checkBox" name="meal_type" value="ages-stages-9-12"/>9-12</li>
                                    </ul>
                                    <a href="#dialog_ages" name="modal" class="more_click">See more</a>
                                </li>	

                             </ul>    
                        </div><!-- end .recipe_control_wrapper-->
			
	      	<!-- Sorting options -->
			<div id="sorting_options">
				<div id="showing"><!-- results are in recipe-filter.php --></div>
				
				<div id="recipe_box_sorted_by">
				<p>Sort by:</p>
				<ul>
					<li class="sort_list_first">
					<a href="#" title="date" class="sort_button active_sort">MOST RECENT</a>
					</li>
					<li class="sort_list rate_smile">
					<a href="#" title="highest_rating" class="sort_button"><p>HIGHEST RATED</p></a>
					<img src="<?php bloginfo('template_url'); ?>/images/rating_on.gif" />
					</li>
					<li class="sort_list">
					<a href="#" title="title" class="sort_button">TITLE</a>
					</li>
                                        <li class="sort_list">
					<a href="#" title="with_notes" class="sort_button"><p>WITH NOTES</p></a> 
                                        <img src="<?php echo plugins_url("/recipe-plus/images/icons/notes.gif"); ?>" >
					</li>
				</ul>
				</div>
			
			</div><!-- end #sorting_options -->
			
		
		<div id="loading">
                    <img src="<?php bloginfo('template_url'); ?>/images/twitterload.gif" />
                </div>
            
			<div class="topic_main_wrapper">
                            <div id="jquerystuff">
                                <div class="recipe_box_topic_post_wrapper">
                                                        <!-- Iinital content is loaded via the recipe-filter-food-recipes.php file  -->
                                </div><!-- end .recipe_box_topic_post_wrapper -->
                                
                                
          
                            </div><!-- end jQuery -->
			</div><!-- end .topic_main_wrapper -->

			
			<!-- end .topic_sidebar -->
      		
      </div><!-- end #article_wrapper -->