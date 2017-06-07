<?php
/*
Template Name: qi_survey_results
*/
/**shows the qi survey results for a given survey or the results directly after the survey is taken
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
<div id ="qi_user_summary"></div>
<div id = "qi_score_explanation">
<div id="qi_wpProQuiz_header">
<div id="headerdisplay"></div>
</div>
<br><br>
<!-- load qi survey from jquery -->
<div id="qi_wpProQuiz_results"> </div> 

<div id="qi_footer_info">
<span style="font-size: 12px; "></br></br>	Morningstar, M.E. Erickson, A.G., Lattin, D.L. & Lee, H. (Revised June, 2012). Quality indicators of exemplary transition programs needs assessment summary [Assessment tool]. Lawrence, KS. University of Kansas, Department of Special Education. Retrieved from <a href="https://www.transitioncoalition.org">www.transitioncoalition.org</a>
		<br/><br/>
		<div align=center>Copyright &copy;2012</div>
</span></div><!--/.form wrapper_style-->

      </div><!--/.entry-->
	</article>
</div><!--/.pad-->
</section><!--/.content-->
						
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
