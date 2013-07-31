<?php
/*
Template Name: Fullwidth Page
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
        <div id="main" class="full">
        
        <?php if (have_posts()) : $count = 0; ?>
        <?php while (have_posts()) : the_post(); $count++; ?>
                                                                    
            <!-- Post Starts -->
            <div class="post wrap">
                
                <?php the_content(); ?>
                
                <div id="comments">
                    <?php //comments_template(); ?>
                </div>

            </div>
            <!-- Post Ends -->
                                                
        <?php endwhile; else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.',woothemes); ?></p>
        <?php endif; ?>  
    
		</div>
    </div>
    </div><!-- Content Ends -->
		
<?php get_footer(); ?>