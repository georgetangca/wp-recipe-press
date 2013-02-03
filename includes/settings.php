<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}
/**
 * settings.php - View for the Settings page.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
/* Flush the rewrite rules */
global $wp_rewrite;
$wp_rewrite->flush_rules();

if ( isset($_REQUEST['tab']) ) {
     $selectedTab = $_REQUEST['tab'];
} else {
     $selectedTab = 'recipe';
}

if ( isset($_REQUEST['tax']) and (array_key_exists($_REQUEST['tax'], $this->options['taxonomies']) or $_REQUEST['tax'] == 'ingredients') ) {
     $selectedTax = $_REQUEST['tax'];
} else {
     $tax_names = array_keys($this->options['taxonomies']);
     $selectedTax = $tax_names[0];
}

$tabs = array(
     'recipe' => __('Options', 'recipe-press'),
    // 'taxonomies' => __('Taxonomies', 'recipe-press'),
     'display' => __('Display Settings', 'recipe-press'),
     'image' => __('Image Settings', 'recipe-press'),
     'box' => __('Recipe Box', 'recipe-press'),
     'form' => __('Form Options', 'recipe-press'),
     'widget' => __('Widget Defaults', 'recipe-press'),
     'administration' => __('Administration', 'recipe-press')
);
?>

<form class="form" method="post" action="options.php" id="recipe_press_settings">
     <input type="hidden" name="<?php echo $this->optionsName; ?>[version]" value="<?php echo $this->version; ?>" />
     <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;" class="overDiv"></div>
     <div class="wrap">
          <div class="icon32" id="icon-recipe-press"><br/></div>
          <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Plugin Settings', 'recipe-press'); ?> </h2>
          <?php if ( isset($_REQUEST['reset']) ) : ?>
               <div id="settings-error-recipe-press_upated" class="updated settings-error">
                    <p><strong><?php _e('RecipePress settings have been reset to defaults.', 'recipe-press'); ?></strong></p>
               </div>
          <?php elseif ( isset($_REQUEST['updated']) ) : ?>
                    <div id="settings-error-recipe-press_upated" class="updated settings-error">
                         <p><strong><?php _e('RecipePress Settings Saved.', 'recipe-press'); ?></strong></p>
                    </div>
          <?php endif; ?>
          <?php settings_fields($this->optionsName); ?>
                    <input type="hidden" name="<?php echo $this->optionsName; ?>[random-value]" value="<?php echo rand(1000, 100000); ?>" />
                    <input type="hidden" name="active_tab" id="active_tab" value="<?php echo $selectedTab; ?>" />
                    <input type="hidden" name="active_tax" id="active_tax" value="<?php echo $selectedTax; ?>" />
                    <ul id="recipe_press_tabs">
               <?php foreach ( $tabs as $tab => $name ) : ?>
                         <li id="recipe_press_<?php echo $tab; ?>" class="recipe-press<?php echo ($selectedTab == $tab) ? '-selected' : ''; ?>" style="display: <?php echo ($tab == 'taxonomies' && !$this->options['use-taxonomies']) ? 'none' : 'block'; ?>">
                              <a href="#top" onclick="recipe_press_show_tab('<?php echo $tab; ?>')"><?php echo $name; ?></a>
                         </li>
               <?php endforeach; ?>
                         <li id="recipe_press_save" class="recipe-press-tab save-tab">
                              <a href="#top" onclick="recipe_press_settings_submit()"><?php _e('Save Settings', 'recipe-press'); ?></a>
                         </li>
                    </ul>


          <?php foreach ( $tabs as $tab => $name ) : ?>
                              <div id="recipe_press_box_<?php echo $tab; ?>" style="display: <?php echo ($selectedTab == $tab) ? 'block' : 'none'; ?>">
               <?php require_once('settings/' . $tab . '-options.php'); ?>
                         </div>
          <?php endforeach; ?>

          <?php include('footer.php'); ?>
</div>

<div style="clear: both;"></div>

</form>
