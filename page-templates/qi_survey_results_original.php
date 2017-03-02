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
<div class="rounded_table">
<table width=900><tr><td style='font-size: 18px;font-weight:bold;color:#FFF;'>Explanation of Scores</td></tr>	
<tr><td style='text-align:left;'><P>The score for each domain is an average (mean) of your total responses to each quality indicator statement in that domain. The highest average for each domain is 3 and the lowest is 0.<br></p>

    <li style='list-style-type: circle; margin-left: 20px;'>The higher the overall domain score, the more quality indicators you've achieved in that domain.</li>
    <li style='list-style-type: circle; margin-left: 20px;'>The low domain scores are the domains you may want to target for change.</li>
    <li style='list-style-type: circle; margin-left: 20px;'>The domain average can help you identify which area of transition might be the most critical for you, your district, or state to begin planning around or making changes.</li>
<p><br>You can track your QI-2 Survey results under My Portfolio > My Surveys. This section keeps track of each time you’ve taken the QI-2, your scores each time you take it, and gives you access to print or email your QI-2 scores.</p>
</td>
</tr>
</table>
</div>
<br><br>
<!-- load qi survey from jquery -->
<div id="qi_wpProQuiz_results"> </div> 

<div id="qi_footer_info">
<span style="font-size: 12px; "></br></br>	Morningstar, M.E. Erickson, A.G., Lattin, D.L. & Lee, H. (Revised June, 2012). Quality indicators of exemplary transition programs needs assessment summary [Assessment tool]. Lawrence, KS. University of Kansas, Department of Special Education. Retrieved from <a href="http://www.transitioncoalition.org">www.transitioncoalition.org</a>
		<br/><br/>
		<div align=center>Copyright &copy;2012</div>
</span></div><!--/.form wrapper_style-->

      </div><!--/.entry-->
	</article>
</div><!--/.pad-->
</section><!--/.content-->
						
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
