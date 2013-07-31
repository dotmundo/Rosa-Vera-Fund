<?php get_header(); ?>
<?php global $woo_options; ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
		           
			<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumb"><p>','</p></div>'); } ?>

            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
            
				<div <?php post_class(); ?> id="striped">

	                <h3 class="cat-title"><?php the_category(', ') ?>
					<span class="post-nav fr">
						<span class="prev"><?php previous_post_link('%link', 'Previous'); ?></span>
						<span class="next"><?php next_post_link('%link', 'Next'); ?></span>
			       	</span>
					</h3>
	                                        
	                <div class="entry" id="plate">
	
						<h1 class="title"><?php the_title(); ?></h1>
	
						<?php echo woo_get_embed('embed','580','420'); ?>
						<?php if ( $woo_options['woo_thumb_single'] == "true" && !woo_get_embed('embed','580','420') ) woo_image('width='.$woo_options['woo_single_w'].'&height='.$woo_options['woo_single_h'].'&class=thumbnail '.$woo_options['woo_thumb_single_align']); ?>
	
	                	<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
	
						<?php the_tags('<p class="tags">'.__('Tags: ', 'woothemes'), ', ', '</p>'); ?>
	
					</div>
                    
	            </div><!-- /.post -->
                
            <?php $comm = $woo_options['woo_comments']; if ( ($comm == "post" || $comm == "both") ) : ?>
                <?php comments_template('', true); ?>
            <?php endif; ?>
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
  				</div><!-- /.post -->             
           	<?php endif; ?>  
        
		</div><!-- /#main -->
				
        <?php get_sidebar(); ?>
		
    </div><!-- /#content -->
		
<?php get_footer(); ?>