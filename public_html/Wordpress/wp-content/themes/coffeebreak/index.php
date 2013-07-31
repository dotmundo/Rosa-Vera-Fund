<?php get_header(); ?>
<?php global $woo_options; ?>

	<!-- Featured Slider -->
	<?php if ( $woo_options['woo_featured_disable'] <> "true" ) include( TEMPLATEPATH . '/includes/slider.php'); ?>
	<!-- Featured Slider end -->

	<div id="main-content" class="home">           
    <div class="content">
		<div class="col-left">
			<div id="main">
                                                                                    
		        <?php if ( $woo_options['woo_main_page1'] <> "Select a page:" ) { ?>
		        <div id="main-page1">
					<?php query_posts('page_id=' . get_page_id($woo_options['woo_main_page1'])); ?>
		            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>		        					
				    <?php the_content(); ?>
		            <?php endwhile; endif; ?>
		            <div class="fix"></div>
		        </div><!-- /#main-page1 -->
		        <?php } ?>
                
		        <?php if ( $woo_options['woo_main_pages'] == 'true' ) { ?>
	            <div id="mini-features">
		        <?php query_posts('post_type=infobox&order=ASC&posts_per_page=20'); ?>
		        <?php if (have_posts()) : while (have_posts()) : the_post(); $counter++; ?>		        					

					<?php if ( get_post_meta($post->ID, 'mini', true) ) { ?>
                    <img src="<?php echo get_post_meta($post->ID, "mini", $single = true); ?>" alt="" class="home-icon" />				
					<?php } ?> 
                                                         
                   <div <?php if ( get_post_meta($post->ID, 'mini', true) ) { ?>class="feature"<?php } ?>>
                       <h3><?php the_title(); ?></h3>
                       <p><?php echo get_post_meta($post->ID, 'mini_excerpt', true) ?></p>
                       <?php if ( get_post_meta($post->ID, 'mini_readmore', true) ) { ?><a href="<?php echo get_post_meta($post->ID, 'mini_readmore', $single = true); ?>" class="btn"><span><?php _e('Read More', woothemes); ?></span></a><?php } ?>
                    </div>
                    <div class="hr"></div>
        
                <?php endwhile; endif; ?>
	            </div><!-- /#mini-features -->
	            <?php } ?>
                    
		        <?php if ( $woo_options['woo_main_page2'] <> "Select a page:" ) { ?>
		        <div id="main-page2">
					<?php query_posts('page_id=' . get_page_id($woo_options['woo_main_page2'])); ?>
		            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>		        					
				    <?php the_content(); ?>
		            <?php endwhile; endif; ?>
		            <div class="fix"></div>
		        </div><!-- /#main-page2 -->
		        <?php } ?>
                                                                            
            </div><!-- main ends -->
        </div><!-- .col-left ends -->

        <?php get_sidebar(); ?>
	
    </div><!-- .content Ends -->
    </div><!-- #main-content ends -->
    	
<?php get_footer(); ?>