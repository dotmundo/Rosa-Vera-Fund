<?php
	global $woo_options;
	query_posts('post_type=slide&order=ASC&orderby=date');
	if (have_posts()) : $count = 0; 
?>


<div id="featured">
    <div id="loopedSlider">
        
        <ul class="nav-buttons">
                <li id="p"><a href="#" class="previous"><img src="<?php bloginfo('template_directory'); ?>/images/slider-arrow-left.png" alt="&lt;" /></a></li>
                <li id="n"><a href="#" class="next"><img src="<?php bloginfo('template_directory'); ?>/images/slider-arrow-right.png" alt="&gt;" /></a></li>
        </ul>    

         <div class="container" <?php if ( $woo_options['woo_slider_height'] ) echo 'style="height:'. $woo_options['woo_slider_height'] .'px;"'; ?>>  
             <div class="slides">  
           
	        <?php while (have_posts()) : the_post(); $count++; ?>		        					
              
			    <div id="slide-<?php echo $count; ?>" class="slide">                

				    <?php if ( get_post_meta($post->ID, 'slide_image', true) ) { ?>
                    <img src="<?php echo get_post_meta($post->ID, "slide_image", $single = true); ?>" alt="" class="alignright slider-image" />				
                    <?php } ?> 
                   				       
                    <?php the_content(); ?>

                </div>     

			<?php endwhile; ?>
			
             </div>    
        </div><!-- .container ends -->   
            
    </div><!-- .content ends -->
</div><!-- #featured ends -->

<?php endif; ?>

<script type="text/javascript">
<?php if ( is_home() ) { ?>
jQuery(window).load(function(){
    jQuery("#loopedSlider").loopedSlider({
<?php
	$autoStart = 0;
	$slidespeed = 600;
	$slidespeed = get_option("woo_slider_speed") * 1000;
	if ( get_option("woo_slider_auto") == "true" ) 
	   $autoStart = get_option("woo_slider_interval") * 1000;
	else 
	   $autoStart = 0;
	$autoheight = get_option('woo_slider_autoheight');
 ?>
        autoStart: <?php echo $autoStart; ?>, 
        slidespeed: <?php echo $slidespeed; ?>, 
        autoHeight: <?php echo $autoheight; ?>
    });
});
<?php } ?>
</script>
