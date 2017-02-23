<?php
/*
Template Name: portfolio_qi_survey_results
*/
/**shows the qi survey list with links to individual surveys
 * uses custom_tcscript.js and processCustomAjax.php
 * @package WordPress
*/
get_header(); 
?>
<section class="content">
    <div class="pad group">
		<article>
		<div style="padding-left: 30px;">
			<?php the_content(); ?>
				<div style="clear:both";>
				<!-- load from jquery -->
				<br><br>
				<div id ="portfolio_qi_header"></div>
				<div id="portfolio_qi_listing"></div> 
		 </div><!--/.form wrapper_style-->

      </div><!--/.entry-->
	</article>
</div><!--/.pad-->
</section><!--/.content-->
						
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
