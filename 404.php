<?php get_header(); ?>
<section class="content">
<?php get_template_part('inc/page-title'); ?>
<div class="pad group">		
<div class="entry">
	<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = explode('/',$actual_link);
   // handle if this is a private forum or topic -- users get to here from links in the daily digest email
	if($actual_link[3]=="topic") { ?>
	<h3 class="article-title"><?php _e( 'Please use the log in form on the left to view the topic that you are seeking. Thanks.' ); ?></h3>
	<?php } 
	else {
	?>
	 <div class="notebox">
	<?php get_search_form(); ?>
	</div>
    <img src="/wp-content/uploads/2016/08/404-error.png"><br>
	<p><?php _e( 'The page you are trying to reach does not exist, or has been moved. <br>Please use the menus or the search box to find what you are looking for.', 'hueman' ); ?></p>
	<?php 
	} 
	?>		
</div>
</div><!--/.pad-->
</section><!--/.content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>