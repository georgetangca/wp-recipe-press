<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * recipe-print.php - The Template for printing all recipes.
 *
 * @package RecipePress
 * @subpackage templates
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.2
 */
if ( !$template = $wp_query->query_vars['print'] ) {
     $template = $this->options['default-print-template'];
}

if ( get_option('permalink_structure') ) {
     $urldivider = '?';
} else {
     $urldivider = '&';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php //language_attributes();        ?>>

     <head profile="http://gmpg.org/xfn/11">
          <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

          <title><?php
wp_title('&laquo;', true, 'right');
if ( get_query_var('cpage') ) {
     echo ' Page ' . get_query_var('cpage') . ' &laquo; ';
}
bloginfo('name'); ?>
          </title>
          <link rel="stylesheet" media="screen" type="text/css" href="<?php echo $this->get_template('print/' . $template, '.css', 'url'); ?>" />
          <link rel="stylesheet" media="print" type="text/css" href="<?php echo $this->get_template('print/' . $template . '-print', '.css', 'url'); ?>" />
          <?php wp_print_scripts('jquery'); ?>
               <script src="<?php echo $this->pluginURL; ?>js/columnizer/autocolumn.js" type="text/javascript" charset="utf-8"></script>

               <script type="text/javascript">
                    <!--//--><![CDATA[//><!--

                    function refresh(value) {
                         window.location = '<?php the_permalink(); ?><?php echo $urldivider; ?>print=' + value;
                    }

                    jQuery(function(){
                         var elem = jQuery('#card-size');
                         var boxWidth = elem.width();
                         var boxHeight = elem.height();

                         //alert (boxWidth + ' x ' + boxHeight);

                         jQuery('.columnize').columnize({
                              width : boxWidth,
                              height : boxHeight,
                              float: 'none',
                              ignoreImageLoading: true

                         });
                    });

                    function add_title_bars() {
                         alert ('No, I do not want to');
                    }
                    //--><!]]>
               </script>
          <?php wp_head(); ?>
          </head>
          <body class="print-recipes">

               <div id="print_recipe" class="print-size no-print">
                    <label for="select_template"><?php _e('Select Print Size', 'recipe-press'); ?></label>
                    <select id="print-templates" name="print" onchange="refresh(this.value)">
                         <option value="card-3x5" <?php selected($template, 'card-3x5'); ?>>3 X 5 Card</option>
                         <option value="card-4x6" <?php selected($template, 'card-4x6'); ?>>4 x 6 Card</option>
                         <option value="sheet-8x11" <?php selected($template, 'sheet-8x11'); ?>>8 X 11 Sheet</option>
                    </select>
                    for <a href="<?php the_permalink(); ?>" title="<?php printf(__('Return to %1$s', 'recipe-press'), get_the_title()); ?>"><?php the_title(); ?></a>
               </div>

               <div id="card-size" class="card-size"></div>
               <div id="recipe_card" class="recipe-size-<?php echo $template; ?> columnize">
               <?php require_once($this->get_template('print/' . $template)); ?>
          </div>

          <?php wp_footer(); ?>
     </body>
</html>

