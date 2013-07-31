	<div class="fix"></div>

	<!-- footer Starts -->
	<div id="footer">
        <div class="content">
            <div class="col-left">
            	<h3><?php echo stripslashes(get_option('woo_footer_left_title')); ?></h3>
                <p><?php echo stripslashes(get_option('woo_footer_left')); ?></p>
                <div class="hr"></div>
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo(); ?>. <?php if ( get_option('woo_footer_credits') ) { echo stripslashes(get_option('woo_footer_credits')); } else { ?>All Rights Reserved. <a href="http://www.woothemes.com"><img src="<?php bloginfo('template_directory'); ?>/images/woothemes.png" width="74" height="19" alt="Woo Themes" /></a><?php } ?></p>
                
            </div>
            <div class="col-right">
            	<h3><?php echo stripslashes(get_option('woo_footer_right_title')); ?></h3>
                <p><?php echo stripslashes(get_option('woo_footer_right')); ?></p>
            </div>
        </div>
        <div class="fix"></div>
	</div>
	<!-- footer Ends -->
	
</div>
<?php wp_footer(); ?>

<?php $twitter = get_option("widget_twitterwidget"); ?>
<?php if ( $GLOBALS[twitter_widget] ) { ?>
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $twitter['username']; ?>.json?callback=twitterCallback2&amp;count=<?php echo $twitter['number']; ?>"></script>
<?php } ?>

</body>
</html>