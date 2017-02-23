<?php
/*
Template Name: portfolio_qi_survey_list
*/
/**shows the qi survey list with links to individual surveys
 * uses custom_tcscript.js and processCustomAjax.php
 * @package WordPress
*/
get_header();
?>


<section class="content">
<div class="template_content">
          <h3><span>Your QI Surveys</span></h3>
	  <br>
          <article>
		<?php the_content(); ?>
				<!-- load from jquery -->
				<div id ="portfolio_qi_header"></div>
				<div id="portfolio_qi_listing"></div> 
         </article>
	
	
</div>
</section><!--/.content-->
						
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
