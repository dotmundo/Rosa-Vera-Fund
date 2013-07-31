<?php get_header(); ?>
       
	<div id="featured">
        <div id="page-title" class="content">
			<?php if (is_category()) { ?><h2><?php _e('Archive for',woothemes); ?> '<?php echo single_cat_title(); ?>'</h2>
            <?php } elseif (is_day()) { ?><h2><?php _e('Archive for',woothemes); ?> <?php the_time('F jS, Y'); ?></h2>
            <?php } elseif (is_month()) { ?><h2><?php _e('Archive for',woothemes); ?> <?php the_time('F, Y'); ?></h2>
            <?php } elseif (is_year()) { ?><h2><?php _e('Archive for the year',woothemes); ?> <?php the_time('Y'); ?></h2>
            <?php } elseif (is_author()) { ?><h2><?php _e('Archive by Author',woothemes); ?> </h2>
            <?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?><h2><?php _e('Archives',woothemes); ?></h2>
            <?php } elseif (is_tag()) { ?><h2 ><?php _e('Tag Archives:',woothemes); ?> <?php echo single_tag_title('', true); ?></h2>	

            <?php } ?>
            <a class="subscribe" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>">
                <img src="<?php bloginfo('template_directory'); ?>/images/ico-rss-48.png" alt="Subscribe" />
            </a>        
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
                    
                    <?php if ( get_option('woo_the_content') == "true" ) the_content(); else the_excerpt(); ?>

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

    </div>
    </div><!-- Content Ends -->
		
<?php get_footer(); ?>