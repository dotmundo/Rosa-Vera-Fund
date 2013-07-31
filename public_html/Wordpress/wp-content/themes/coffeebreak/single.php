<?php get_header(); ?>
       
	<div id="featured">
        <div id="page-title" class="content">
            <h2 class="page"><?php _e('The Blog',woothemes); ?></h2>
            <a class="subscribe" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>">
                <img src="<?php bloginfo('template_directory'); ?>/images/ico-rss-48.png" alt="Subscribe" />
            </a>        
        </div>
    </div>
           
    <!-- Content Starts -->
	<div id="main-content" class="single">           
    <div class="content">
		<div class="col-left">
			<div id="main">
            
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <!-- Post Starts -->
                <div class="post wrap">

                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <p class="post-details"><?php _e('Posted on',woothemes); ?> <?php the_time('d. M, Y'); ?> <?php _e('by',woothemes); ?> <?php the_author_posts_link(); ?> <?php _e('in',woothemes); ?> <?php the_category(', ') ?></p>
                    
                    <?php the_content(); ?>
					<?php the_tags('<p class="tags">Tags: ', ', ', '</p>'); ?>
                    
                    <div id="comments">
                        <?php comments_template(); ?>
                    </div>

                </div>
                <!-- Post Ends -->
                                                    
			<?php endwhile; else: ?>
                <p><?php _e('Sorry, no posts matched your criteria.',woothemes); ?></p>
            <?php endif; ?>  
        
            </div><!-- main ends -->
        </div><!-- .col-left ends -->

        <?php get_sidebar(); ?>

	</div>
    </div><!-- Content Ends -->
		
<?php get_footer(); ?>