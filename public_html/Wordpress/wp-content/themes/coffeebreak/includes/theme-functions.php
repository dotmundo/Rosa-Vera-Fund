<?php 

// Show menu in header.php
// Exlude the pages from the slider
function woo_menu( $exclude="" ) {
    // Split the featured pages from the options, and put in an array
    if ( get_option('woo_ex_feat_pages') == "true" ) {
        $menupages = get_option('woo_feat_pages');
        $exclude = $menupages . ',' . $exclude;
    }
    // Split the main content pages from the options, and put in an array
    if ( get_option('woo_ex_main_pages') == "true" ) {
        $menupages = get_option('woo_main_pages');
        $exclude = $menupages . ',' . $exclude;
    }
    
    $pages = wp_list_pages('title_li=&echo=0&depth=3&exclude='.$exclude);
    $pages = preg_replace('%<a ([^>]+)>%U','<a $1><span>', $pages);
    $pages = str_replace('</a>','</span></a>', $pages);
    echo $pages;
}

/*-----------------------------------------------------------------------------------*/
/* WordPress 3.0 New Features Support */
/*-----------------------------------------------------------------------------------*/

if ( function_exists('wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu' ) ) );
}
    
        

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Slides */
/*-----------------------------------------------------------------------------------*/

add_action('init', 'woo_add_slides');
function woo_add_slides() 
{
  $labels = array(
    'name' => _x('Slides', 'post type general name', 'woothemes', woothemes),
    'singular_name' => _x('Slide', 'post type singular name', woothemes),
    'add_new' => _x('Add New', 'slide', woothemes),
    'add_new_item' => __('Add New Slide', woothemes),
    'edit_item' => __('Edit Slide', woothemes),
    'new_item' => __('New Slide', woothemes),
    'view_item' => __('View Slide', woothemes),
    'search_items' => __('Search Slides', woothemes),
    'not_found' =>  __('No slides found', woothemes),
    'not_found_in_trash' => __('No slides found in Trash', woothemes), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_icon' => get_template_directory_uri() .'/includes/images/slides.png',
    'menu_position' => null,
    'supports' => array('title','editor',/*'author','thumbnail','excerpt','comments'*/)
  ); 
  register_post_type('slide',$args);
}



/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Info Boxes */
/*-----------------------------------------------------------------------------------*/

add_action('init', 'woo_add_infoboxes');
function woo_add_infoboxes() 
{
  $labels = array(
    'name' => _x('Mini-Features', 'post type general name', woothemes),
    'singular_name' => _x('Mini-Feature', 'post type singular name', woothemes),
    'add_new' => _x('Add New', 'infobox', woothemes),
    'add_new_item' => __('Add New Mini-Feature', woothemes),
    'edit_item' => __('Edit Mini-Feature', woothemes),
    'new_item' => __('New Mini-Feature', woothemes),
    'view_item' => __('View Mini-Feature', woothemes),
    'search_items' => __('Search Mini-Features', woothemes),
    'not_found' =>  __('No Mini-Features found', woothemes),
    'not_found_in_trash' => __('No Mini-Features found in Trash', woothemes), 
    'parent_item_colon' => ''
  );
  
  $infobox_rewrite = get_option('woo_infobox_rewrite');
  if(empty($infobox_rewrite)) $infobox_rewrite = 'infobox';
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => array('slug'=> $infobox_rewrite),
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_icon' => get_template_directory_uri() .'/includes/images/box.png',
    'menu_position' => null,
    'supports' => array('title','editor',/*'author','thumbnail','excerpt','comments'*/)
  ); 
  register_post_type('infobox',$args);
}

/*-----------------------------------------------------------------------------------*/
/* 3. Add custom typograhpy to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('woo_custom_typography')) {
	function woo_custom_typography() {
	
		// Get options
		global $woo_options;
				
		// Reset	
		$output = '';
		
		// Add Text title and tagline if text title option is enabled
		if ( $woo_options['woo_texttitle'] == "true" ) {		
			
			if ( $woo_options['woo_font_site_title'] )
				$output .= '#logo .site-title a {'.woo_generate_font_css($woo_options['woo_font_site_title']).'}' . "\n";	
			if ( $woo_options['woo_font_tagline'] )
				$output .= '#logo .site-description {'.woo_generate_font_css($woo_options['woo_font_tagline']).'}' . "\n";	
		}

		// Output styles
		if ( $output <> "" ) {
		
			// Enable Google Fonts stylesheet in HEAD
			if (function_exists('woo_google_webfonts')) woo_google_webfonts();
			
			$output = "\n<!-- Woo Custom Typography -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
			
		}
			
	}
} 

if (!function_exists('woo_generate_font_css')) {
	// Returns proper font css output
	function woo_generate_font_css($option, $em = '1') {
		return 'font:'.$option["style"].' '.$option["size"].$option["unit"].'/'.$em.'em '.stripslashes($option["face"]).';color:'.$option["color"].';';
	}
}

add_action('wp_head','woo_custom_typography');			// Add custom typography to HEAD

    
?>