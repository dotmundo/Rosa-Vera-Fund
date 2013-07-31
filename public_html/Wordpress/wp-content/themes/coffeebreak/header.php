<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>

<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
   
<!--[if IE 6]>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/menu.js"></script>
<![endif]-->
   
<?php if ( is_single() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

<!--[if lte IE 7]>
<script type="text/javascript">
jQuery(function() {
	var zIndexNumber = 1000;
	jQuery('div').each(function() {
		jQuery(this).css('zIndex', zIndexNumber);
		zIndexNumber -= 10;
	});
});
</script>
<![endif]-->

</head>

<body <?php body_class(); ?>>

<div id="wrap">
    <div id="top">
    	<div class="content">
        
            <div id="header">
            
            	<div id="logo">
            
				<?php if (get_option('woo_texttitle') <> "true") : $logo = get_option('woo_logo'); ?>
                    <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('description'); ?>">
                        <img class="logo" src="<?php if ( get_option('woo_logo') <> "" ) { echo get_option('woo_logo'); } else { bloginfo('template_directory'); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" />
                    </a>
                <?php endif; ?> 
                
                <?php if( is_singular() ) : ?>
                    <span class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></span>
                <?php else : ?>
                    <h1 class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
                <?php endif; ?>
                    <span class="site-description"><?php bloginfo('description'); ?></span>
                    
                </div><!-- /#logo -->
             
                <div id="nav">
                    <?php
                		if ( function_exists('has_nav_menu') && has_nav_menu('primary-menu') ) {
                			wp_nav_menu( array( 'depth' => 5, 'sort_column' => 'menu_order', 'container' => 'ul', 'theme_location' => 'primary-menu' ) );
                		} else {
                		?>
                    <ul>
					<?php 
                    if ( get_option('woo_custom_nav_menu') == 'true' && function_exists('woo_custom_navigation_output') ) {
                        woo_custom_navigation_output();
        
                    } else { ?>
                    
                        <li <?php if ( is_home() ) { ?> class="current_page_item" <?php } ?>><a href="<?php echo get_option('home'); ?>/"><span><?php _e('Home',woothemes); ?></span></a></li>
                        <?php if ( get_option('woo_addblog') == "true" ) { ?>
                        <li <?php if ( is_category() || is_search() || is_single() || is_tag() || is_search() || is_archive() ) { ?> class="current_page_item" <?php } ?>>
                            <a href="<?php echo get_option('home'); echo get_option('woo_blogcat'); ?>" title="Blog"><span><?php _e('Blog',woothemes); ?></span></a>
                            <?php if (get_option('woo_catmenu') == "true") { ?><ul><?php wp_list_categories('title_li=&child_of='.get_option('woo_blog_cat_id') ); ?></ul><?php } ?>
                        </li>
                        <?php } ?>				
                        <?php woo_menu( get_option('woo_menu_pages') ); ?>
                        
					<?php } ?>
                        
                    </ul>
                    <?php } ?>
                </div>
                <!--/nav1-->
                                                
            </div>
            
        </div>
    </div>
