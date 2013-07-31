<?php

add_action('init','woo_global_options');
function woo_global_options() {
	// Populate WooThemes option in array for use in theme
	global $woo_options;
	$woo_options = get_option('woo_options');
}

add_action('admin_head','woo_options');  
if (!function_exists('woo_options')) {
function woo_options(){
// VARIABLES
$themename = "Coffee Break";
$manualurl = 'http://www.woothemes.com/support/theme-documentation/coffee-break/';
$shortname = "woo";

$GLOBALS['template_path'] = get_bloginfo('template_directory');

//Access the WordPress Categories via an Array
$woo_categories = array();  
$woo_categories_obj = get_categories('hide_empty=0');
foreach ($woo_categories_obj as $woo_cat) {
    $woo_categories[$woo_cat->cat_ID] = $woo_cat->cat_name;}
$categories_tmp = array_unshift($woo_categories, "Select a category:");    
       
//Access the WordPress Pages via an Array
$woo_pages = array();
$woo_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($woo_pages_obj as $woo_page) {
    $woo_pages[$woo_page->ID] = $woo_page->post_name; }
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:");       


//Testing 
$options_select = array("one","two","three","four","five"); 
$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five"); 

//Stylesheets Reader
$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

//More Options


$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");

// THIS IS THE DIFFERENT FIELDS
$options = array();   

$options[] = array( "name" => "General Settings",
					"icon" => "general",
                    "type" => "heading");
                        
$options[] = array( "name" => "Theme Stylesheet",
					"desc" => "Select your themes alternative color scheme.",
					"id" => $shortname."_alt_stylesheet",
					"std" => "default.css",
					"type" => "select",
					"options" => $alt_stylesheets);

$options[] = array( "name" => "Custom Logo",
					"desc" => "Upload a logo for your theme, or specify an image URL directly.",
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "upload");    
                                                                                     
$options[] = array( "name" => "Text Title",
					"desc" => "Enable if you want Blog Title and Tagline to be text-based. Setup title/tagline in WP -> Settings -> General.",
					"id" => $shortname."_texttitle",
					"std" => "false",
					"class" => "collapsed",
					"type" => "checkbox");

$options[] = array( "name" => "Site Title",
					"desc" => "Change the site title (must have 'Text Title' option enabled).",
					"id" => $shortname."_font_site_title",
					"std" => array('size' => '44','unit' => 'px','face' => 'Trebuchet MS','style' => 'bold','color' => '#fefefe'),
					"class" => "hidden",
					"type" => "typography");  

$options[] = array( "name" => "Site Description",
					"desc" => "Change the site description (must have 'Text Title' option enabled).",
					"id" => $shortname."_font_tagline",
					"std" => array('size' => '14','unit' => 'px','face' => 'Arial','style' => '','color' => '#aaaaaa'),
					"class" => "hidden last",
					"type" => "typography");  
                                                                                    				          
$options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16px x 16px <a href='http://www.faviconr.com/'>ico image</a> that will represent your website's favicon.",
					"id" => $shortname."_custom_favicon",
					"std" => "",
					"type" => "upload"); 
                                               
$options[] = array( "name" => "Tracking Code",
					"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");        

$options[] = array( "name" => "RSS URL",
					"desc" => "Enter your preferred RSS URL. (Feedburner or other)",
					"id" => $shortname."_feedburner_url",
					"std" => "",
					"type" => "text");
                    
$options[] = array( "name" => "Contact Form E-Mail",
					"desc" => "Enter your E-mail address to use on the Contact Form Page Template.",
					"id" => $shortname."_contactform_email",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Custom CSS",
                    "desc" => "Quickly add some CSS to your theme by adding it to this block.",
                    "id" => $shortname."_custom_css",
                    "std" => "",
                    "type" => "textarea");

$options[] = array(	"name" => "Featured Slider",
					"icon" => "slider",
					"type" => "heading");					

$options[] = array(	"name" => "Disable Featured Area",
					"desc" => "Check this if you don't want to use the featured area.",
					"id" => $shortname."_featured_disable",
					"std" => "false",
					"type" => "checkbox");

$options[] = array(    "name" => "Animation Speed",
                    "desc" => "The time in <b>seconds</b> the animation between frames will take.",
                    "id" => $shortname."_slider_speed",
                    "std" => 0.6,
                    "type" => "text");

$options[] = array(    "name" => "Auto Start",
                    "desc" => "Set the slider to start sliding automatically.",
                    "id" => $shortname."_slider_auto",
                    "std" => "false",
                    "type" => "checkbox");   
                    
$options[] = array(    "name" => "Auto Slide Interval",
                    "desc" => "The time in <b>seconds</b> each slide pauses for, before sliding to the next.",
                    "id" => $shortname."_slider_interval",
                    "std" => 4.0,
                    "type" => "text"); 

$options[] = array(    "name" => "Auto Height",
                    "desc" => "Set the slider to adjust height automatically.",
                    "id" => $shortname."_slider_autoheight",
                    "std" => "true",
                    "type" => "checkbox");   

$options[] = array(    "name" => "Initial Height",
                    "desc" => "Set the initial height of the slider in pixels e.g 320.",
                    "id" => $shortname."_slider_height",
                    "std" => "320",
                    "type" => "text");   

$options[] = array(	"name" => "Homepage",
					"icon" => "homepage",
					"type" => "heading");					

$options[] = array( "name" => "Mini-Features Area",
		          "desc" => "Enable the front page Mini-Features features area.",
		          "id" => $shortname."_main_pages",
		          "std" => "true",
		          "type" => "checkbox");
					
$options[] = array( "name" => "Custom permalink",
		          "desc" => "This option allows you to change the permalink on the individual mini-features pages. (e.g /infobox/pagename to /features/pagename/). Please update <a href='". admin_url('options-permalink.php')."'>Permalinks</a> after any changes.",
		          "id" => $shortname."_infobox_rewrite",
		          "std" => "infobox",
		          "type" => "text");                          
				

$options[] = array( "name" => "Homepage content #1",
		          "desc" => "(Optional) Select a page that you'd like to display on the front page <strong>above the mini features area</strong>.",
		          "id" => $shortname."_main_page1",
		          "std" => "Select a page:",
					"type" => "select",
					"options" => $woo_pages);   

$options[] = array( "name" => "Homepage content #2",
		          "desc" => "(Optional) Select a page that you'd like to display on the front page <strong>below the mini features area.</strong>",
		          "id" => $shortname."_main_page2",
		          "std" => "Select a page:",
					"type" => "select",
					"options" => $woo_pages);   

$options[] = array(	"name" => "Footer",
					"icon" => "footer",
					"type" => "heading");					

$options[] = array(	"name" => "Left Footer Title",
					"desc" => "Enter a title that you would like to display in your left footer",
					"id" => $shortname."_footer_left_title",
					"std" => "",
					"type" => "text");

$options[] = array(	"name" => "Left Footer",
					"desc" => "Enter text that you would like to display in your left footer. <br/>Use &lt;br /&gt; to add linespace.",
					"id" => $shortname."_footer_left",
					"std" => "",
					"type" => "textarea");

$options[] = array(	"name" => "Right Footer Title",
					"desc" => "Enter a title that you would like to display in your left footer.",
					"id" => $shortname."_footer_right_title",
					"std" => "",
					"type" => "text");

$options[] = array(	"name" => "Right Footer",
					"desc" => "Enter text that you would like to display in your right footer. <br/>Use &lt;br /&gt; to add linespace.",
					"id" => $shortname."_footer_right",
					"std" => "",
					"type" => "textarea");

$options[] = array(	"name" => "Credits Footer",
					"desc" => "Enter text that you would like to display as credits in the footer after your sitename.",
					"id" => $shortname."_footer_credits",
					"std" => "",
					"type" => "textarea");
                                                                                                 
$options[] = array( "name" => "Ad Widget (300x250px)",
					"icon" => "ads",
					"type" => "heading");

$options[] = array( "name" => "Adsense code",
					"desc" => "Enter your adsense code (or other ad network code) here.",
					"id" => $shortname."_ad_300_adsense",
					"std" => "",
					"type" => "textarea");

$options[] = array( "name" => "Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_300_image",
					"std" => "http://www.woothemes.com/ads/300x250b.jpg",
					"type" => "upload");

$options[] = array( "name" => "Destination URL",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_300_url",
					"std" => "http://www.woothemes.com",
					"type" => "text");    

update_option('woo_template',$options);      
update_option('woo_themename',$themename);   
update_option('woo_shortname',$shortname);
update_option('woo_manual',$manualurl);

                                     
// Add extra options through function
if ( function_exists("woo_options_add") )
  $options = woo_options_add($options);                                              

update_option('woo_template',$options);      
update_option('woo_themename',$themename);   
update_option('woo_shortname',$shortname);
update_option('woo_manual',$manualurl);

                                     
// Woo Metabox Options
$woo_metaboxes = array();

if( get_post_type() == 'post' || !get_post_type()){

$woo_metaboxes[] = array (	"name" => "image",
							"label" => "Image",
							"type" => "upload",
							"desc" => "Upload image for use with blog posts");
} // End post

if( get_post_type() == 'slide' || !get_post_type()){

  $woo_metaboxes["slide_image"] = array (
              "name" => "slide_image",
              "label" => "Slider Image",
              "type" => "upload",
              "desc" => "Upload image for use in the featured area on the homepage"
          );        

} // End slide

if( get_post_type() == 'infobox' || !get_post_type()){
	

$woo_metaboxes['mini'] = array (	
				"name" => "mini",
				"label" => "Mini-features Icon",
				"type" => "upload",
				"desc" => "Upload icon for use with the Mini-Feature on the homepage (optimal size: 32x32px) (optional)"
			);
 
$woo_metaboxes['mini_excerpt'] = array (	
				"name" => "mini_excerpt",
				"label" => "Mini-features Excerpt",
				"type" => "textarea",
				"desc" => "Enter the text to show in your Mini-Feature. "
			);

$woo_metaboxes['mini_readmore'] = array (	
				"name" => "mini_readmore",
				"std" => "",
				"label" => "Mini-features URL",
				"type" => "text",
				"desc" => "Add an URL for your Read More button in your Mini-Feature on homepage (optional)"
			);

} // End mini

if( get_post_type() == 'portfolio' || !get_post_type()){

$woo_metaboxes['portfolio'] = array (	"name" => "portfolio",
							"label" => "Portfolio Thumbnail",
							"type" => "upload",
							"desc" => "Upload an image for use in the portfolio (optimal size: 460x210)");

$woo_metaboxes['portfolio-large'] = array (	"name" => "portfolio-large",
							"label" => "Portfolio Large",
							"type" => "upload",
							"desc" => "Add an URL OR upload an image for use as the large portfolio image");

} // End portfolio

// Add extra metaboxes through function
if ( function_exists("woo_metaboxes_add") )
  $woo_metaboxes = woo_metaboxes_add($woo_metaboxes);
    
update_option('woo_custom_template',$woo_metaboxes);

}
}
?>