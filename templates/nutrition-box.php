<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * nutrition-facts.php - The Template for displaying nutrition facts..
 *
 * @package RecipePress
 * @subpackage templates
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
?>
<div style="display: block;" id="nutri-info" display="block">
     <div class="title">
          Nutritional Information</div>
     <div class="rectitle">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
     <p>
          Servings Per Recipe:
          <?php the_recipe_servings(array('tag' => 'span')); ?>
     </p>
     <p>
          <strong>Amount Per Serving</strong></p>
     <p>
          Calories: <?php echo isset($nutrient['txt_calories']) ? $nutrient['txt_calories'] : ''; ?>
     </p>
     <ul>

          <li><strong>Total Fat: </strong> <?php echo $nutrient['txt_total_fat'] . $RECIPEPRESSOBJ->options['nutritional-markers']['txt_total_fat']['size']; ?></li>
          <li><strong>Cholesterol: </strong> <?php echo $nutrient['txt_cholesterol'] . $RECIPEPRESSOBJ->options['nutritional-markers']['txt_cholesterol']['size']; ?></li>
          <li><strong>Sodium: </strong> <?php echo $nutrient['txt_sodium'] . $RECIPEPRESSOBJ->options['nutritional-markers']['txt_sodium']['size']; ?></li>
          <li><strong>Total Carbs: </strong><?php echo $nutrient['txt_total_carbohydrate'] . $RECIPEPRESSOBJ->options['nutritional-markers']['txt_total_carbohydrate']['size']; ?></li>
          <li>&nbsp;&nbsp;&nbsp;&nbsp;<strong>Dietary Fiber: </strong><?php echo $nutrient['txt_dietary_fiber'] . $RECIPEPRESSOBJ->options['nutritional-markers']['txt_dietary_fiber']['size']; ?></li>
          <li><strong>Protein: </strong><?php echo $nutrient['txt_protein'] . $RECIPEPRESSOBJ->options['nutritional-markers']['txt_protein']['size']; ?></li>
     </ul>
</div>