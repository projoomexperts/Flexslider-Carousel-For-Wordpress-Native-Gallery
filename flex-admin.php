<?php
/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */
 
/**
 * Initializes the theme options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
add_action('admin_init', 'fsng_initialize_plugin_options');
function fsng_initialize_plugin_options() {
 
    // First, we register a section. This is necessary since all future options must belong to one. 
    add_settings_section(
        'general_settings_section',         // ID used to identify this section and with which to register options
        'Flexslider Gallery Options',                  // Title to be displayed on the administration page
        'fsng_general_options_callback', // Callback used to render the description of the section
        'media'                           // Page on which to add this section of options
    );
     
    // Next, we will introduce the fields for toggling the visibility of content elements.
    add_settings_field( 
        'show_flexslider',                      // ID used to identify the field 
        'Activate Flexslider',                           // The label to the left of the option interface element
        'fsng_toggle_header_callback',   // The name of the function responsible for rendering the option interface
        'media',                          // The page on which this option will be displayed
        'general_settings_section',         // The name of the section to which this field belongs
        array(                              // The array of arguments to pass to the callback. In this case, just a description.
            'Activate this setting to  enable flexslider for native wordpress Gallery.'
        )
    );
     
 add_settings_field(
 "flexslider_image_size",
  "Select Media Size",
   "flex_select_display", 
   "media", 
   "general_settings_section"
   );  
     
  
     
    // Finally, we register the fields with WordPress
    register_setting(
        'media',
        'show_flexslider'
    );
     
    register_setting(
        'media',
        'flexslider_image_size'
    );
     
  
     
} // end sandbox_initialize_theme_options
 
/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */
 
/**
 * This function provides a simple description for the General Options page. 
 *
 * It is called from the 'sandbox_initialize_theme_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function fsng_general_options_callback() {
    echo '<p>Customise settings for better performance</p>';
} // end sandbox_general_options_callback
 
/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */
 
/**
 * This function renders the interface elements for toggling the visibility of the header element.
 * 
 * It accepts an array of arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function fsng_toggle_header_callback($args) {
     
    // Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field
    $html = '<input type="checkbox" id="show_flexslider" name="show_flexslider" value="1" ' . checked(1, get_option('show_flexslider'), false) . '/>'; 
     
    // Here, we will take the first argument of the array and add it to a label next to the checkbox
    $html .= '<label for="show_header"> '  . $args[0] . '</label>'; 
     
    echo $html;
     
} // end sandbox_toggle_header_callback
function flex_select_display()
{
	
	$added_sizes = get_intermediate_image_sizes();
	$flexslider_image_size = get_option('flexslider_image_size');
	echo "<select name='flexslider_image_size' id='flexslider_image_size'>";	
	// $added_sizes is an indexed array, therefore need to convert it
	// to associative array, using $value for $key and $value
	foreach( $added_sizes as $key => $value) {
		if($value==$flexslider_image_size){
		echo  "<option value='$value' selected>".$value."</option>";
	}
	else{
		echo  "<option value='$value'>".$value."</option>";
	}

	}
	echo "</select>";
	
	
}




?>