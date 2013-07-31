<?php get_header(); ?>
       
	<div id="featured">
        <div id="page-title" class="content">
            <h2 class="page"><?php _e('404 - Page Not Found',woothemes); ?></h2>
        </div>
    </div>
           
    <!-- Content Starts -->
	<div id="main-content" class="page">           
    <div class="content">
		<div class="col-left">
			<div id="main">
            
                <!-- Post Starts -->
                <div class="post wrap">

                    <p><?php _e('The page you trying to reach does not exist, or has been moved. Please use the menus or the search box to find what you are looking for.',woothemes); ?></p>

                </div>
                <!-- Post Ends -->
                                                                   
            </div><!-- main ends -->
        </div><!-- .col-left ends -->

        <?php get_sidebar(); ?>

    </div><!-- Content Ends -->
		
<?php get_footer(); ?>
