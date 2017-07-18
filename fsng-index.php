<?php
/*
	Plugin Name: Flex Slider for Native Gallery
	Plugin URI: https://profiles.wordpress.org/sarankumar
	Description: A simple FlexSlider Wordpress plugin for Native Gallery
	Version: 1.7
	Author: Sarankumar
	Author URI: https://profiles.wordpress.org/sarankumar
	
	WordPress plugin that hacks the gallery[id="1,22,... etc "] shortcode to display a 
	clean basic flexslider instead of the default static thumbnails. No custom classes
	or extra posts necessary, just use the normal add media button and the nice gallery
	editor already available. 
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

 if(is_admin()){
	include( plugin_dir_path( __FILE__ ) . 'flex-admin.php');
}
//define constants for Plugin details
define('WF_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

// check whether plugin enabled or not
if(get_option('show_flexslider')==1){
add_action( 'wp_enqueue_scripts', 'flexslider_scripts' );
add_action('wp_head', 'fsng_addScript');
add_filter( 'the_content', 'fsng_replaceGallery' );
}

//add include files using Wordpress enqueue functions
function flexslider_scripts()
{
wp_enqueue_script('flexslider', WF_PATH.'jquery.flexslider-min.js', array('jquery')); 
wp_enqueue_style('flexslider_css',WF_PATH. 'flexslider.css');  
}

//hook flexslider js into the header of the wp-theme
function fsng_addScript(){
echo '<script type="text/javascript" charset="utf-8">
  jQuery(window).load(function() {
    jQuery(\'#carousel\').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 210,
	itemMargin: 0,
    asNavFor: \'#slider\'
  });
 
  jQuery(\'#slider\').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    sync: "#carousel"
    });
  });
</script>';
}	


//use reg expressions to get the post IDs from gallery shortcode
function fsng_getGalleryIDs(){

	$flexslider_image_size = get_option('flexslider_image_size');
	if(!isset($flexslider_image_size)){
		$flexslider_image_size='large';
	}
	$post_content = get_the_content();
	$hasGallery = preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
	$flexHtml = ' ';
	
	//create html list for the slider
	if(get_post_gallery()){
		$array_id = explode(",", $ids[1]);
		$flexHtml .= "<div id=\"slider\" class=\"flexslider\">\n";
		$flexHtml .= "<ul class=\"slides\">\n";
			foreach ($array_id as $id){
			$caption =  get_post($id);
			
			$flexHtml .= "<li>";
			$flexHtml .= wp_get_attachment_image( $id,$flexslider_image_size);

			if( !empty($caption->post_excerpt)){
				$flexHtml .= "<p class=\"flex-caption\">";
				$flexHtml .= $caption->post_excerpt;
				$flexHtml .= "</p>";
			}
			$flexHtml .= "</li>\n";
		}
		$flexHtml .= "</ul></div>";
$flexHtml .= "<div id=\"carousel\" class=\"flexslider\">\n";
		$flexHtml .= "<ul class=\"slides\">\n";
			foreach ($array_id as $id){
			$caption =  get_post($id);
			
			$flexHtml .= "<li>";
			$flexHtml .= wp_get_attachment_image( $id,$flexslider_image_size);

			if( !empty($caption->post_excerpt)){
				$flexHtml .= "<p class=\"flex-caption\">";
				$flexHtml .= $caption->post_excerpt;
				$flexHtml .= "</p>";
			}
			$flexHtml .= "</li>\n";
		}
		$flexHtml .= "</ul></div>";

	}
	else{
		$flexHtml = false;
	}
	return $flexHtml;
}

//filter that replaces Gallery shortcode with the newly generated flexslider html
function fsng_replaceGallery($content){

	global $post;
	$new_content = $content;
		if(is_singular()){ //make sure that the gallery is on a page/single post
			$newGalleryHtml = fsng_getGalleryIDs();

			if($newGalleryHtml != false){
				$new_content = preg_replace('/\[gallery.*ids=.(.*).\]/', $newGalleryHtml, $content);
			}
		}
	return wpautop($new_content);
	}


?>