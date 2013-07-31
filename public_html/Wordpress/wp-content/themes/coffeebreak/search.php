<?php get_header(); ?>
       
	<div id="featured">
        <div id="page-title" class="content">
            <h2 class="page"><?php _e('Search Results',woothemes); ?></h2>
        </div>
    </div>
           
    <!-- Content Starts -->
	<div id="main-content" class="archive">           
    <div class="content">
		<div class="col-left">
			<div id="main">
            
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <!-- Post Starts -->
                <div class="post wrap">

                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <p class="post-details"><?php _e('Posted on',woothemes); ?> <?php the_time('d. M, Y'); ?> <?php _e('by',woothemes); ?> <?php the_author_posts_link(); ?> <?php _e('in',woothemes); ?> <?php the_category(', ') ?></p>
                    
                    <?php the_excerpt(); ?>

                </div>
                <!-- Post Ends -->
                                                    
                <?php endwhile; else: ?>
                    <p><?php _e('Sorry, no posts matched your criteria.',woothemes); ?></p>
                <?php endif; ?>  
        
                <div class="more_entries">
                    <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
                    <div class="alignleft"><?php previous_posts_link(__('&laquo; Newer Entries ',woothemes)) ?></div>
                    <div class="alignright"><?php next_posts_link(__(' Older Entries &raquo;',woothemes)) ?></div>
                    <br class="fix" />
                    <?php } ?> 
                </div>		
                
            </div><!-- main ends -->
        </div><!-- .col-left ends -->

        <?php get_sidebar(); ?>

    </div><!-- Content Ends -->
    </div>
		
<?php get_footer(); ?>
