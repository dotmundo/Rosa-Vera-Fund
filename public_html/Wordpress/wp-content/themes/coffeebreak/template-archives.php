<?php
/*
Template Name: Archives Page
*/
?>
<?php get_header(); ?>
       
	<div id="featured">
        <div id="page-title" class="content">
            <h2 class="page"><?php the_title(); ?></h2>
        </div>
    </div>
           
    <!-- Content Starts -->
	<div id="main-content" class="page">           
    <div class="content">
		<div class="col-left">
			<div id="main">
            
                <div class="post">
                    <h2><?php _e('The Last 30 Posts',woothemes); ?></h2>
        
                    <ul>
                        <?php query_posts('showposts=30'); ?>
                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                            <?php $wp_query->is_home = false; ?>
                            <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php the_time('j F Y') ?> - <?php echo $post->comment_count ?> comments</li>
                        
                        <?php endwhile; endif; ?>	
                    </ul>				
                </div>
                
                <div class="post">
            
                    <h2><?php _e('Categories',woothemes); ?></h2>
        
                    <ul>
                        <?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>	
                    </ul>	
                </div>                    

                <div class="post">
                    <h2><?php _e('Monthly Archives',woothemes); ?></h2>
        
                    <ul>
                        <?php wp_get_archives('type=monthly&show_post_count=1') ?>	
                    </ul>				
                </div>                    
                
            </div><!-- main ends -->
        </div><!-- .col-left ends -->

        <?php get_sidebar(); ?>

    </div>
    </div><!-- Content Ends -->
		
<?php get_footer(); ?>
