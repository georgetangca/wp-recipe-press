<?php
if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
     die('You are not allowed to call this page directly.');
}

/**
 * nutrients-form.php - Create the nutrients entry form on the admin side.
 *
 * @package RecipePress
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2011
 * @access public
 * @since 1.0
 */
global $nutrientID, $nutrients;

if ( $this->publicForm ) {
     $public = '-public';
     $type = 'public';
} else {
     unset($public);
     $type = 'admin';
}

$nutrients = get_post_meta($post->ID, '_recipe_nutrients_value', true);
if ( !is_array($nutrients) ) {
     $nutrients = unserialize($nutrients);
}

do_action('rp_form_before_nutrients');
?>

<table id="rp_nutrients" class="form-table editrecipe" border="0">
     <thead>
          <tr>
               <th colspan="8" align="center" style="text-align:center"><strong><?php _e('Amount Per Serving', 'recipe-press'); ?></strong></th>
          </tr>
          <tr>
               <th colspan="8"><hr /></th>
          </tr>
     </thead>
     <tbody id="rp_nutrients_body">
          <tr>
               <?php $ictr = 0;
               foreach ( $this->options['nutritional-markers'] as $key => $value ) : ?>
                    <th style="white-space: nowrap"><label for="marker_<?php echo $key; ?>"><?php echo $value['name']; ?></label>:</th>
                    <td style="white-space:nowrap">
                         <input style="width:50px;" type="text" name="nutrient_details[<?php echo $key; ?>]" id="marker_<?php echo $key; ?>" value="<?php echo isset($nutrients[$key]) ? $nutrients[$key] : ''; ?>" />
                    <?php echo isset($value['size']) ? $value['size'] : ''; ?>
               </td>
               <td style="width:75px;"></td>
               <?php
                    ++$ictr;
                    if ( $ictr >= 3 ) : ?>
                    </tr>
                    <tr>
               <?php
                         $ictr = 0;
                    endif;
               endforeach;
               ?>
          </tr>
     </tbody>
</table>
<?php
               do_action('rp_form_after_nutrients');