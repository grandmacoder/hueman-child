<?php
/*
Template Name: single module progress3
*/
/**
 * shows the progress on a single module based on the qs courseid parameter
 *
 * @package WordPress
 */

/* Displays customize output of links for a category.
*/
/* Get roster if there are any if not show the create roster form
*/
get_header();
?>
<?php
	global $wpdb;
        $referring_page =$_SERVER['HTTP_REFERER'];
        $courseID = $_GET['courseid']; //from the url
	$userID = get_current_user_id();
	$moduleName = $wpdb->get_var("Select course_title from wp_wpcw_courses where course_id =" . $courseID);
	//will get all course data for the user, we weed it out in the foreach
        $courseData = WPCW_users_getUserCourseList($userID);
	//course activity text area answers
	$activityRows = $wpdb->get_results("select module_title,activity_id, post_id,activity_value,description from 
					wp_course_activities a, 
					wp_wpcw_user_progress u,
					wp_wpcw_units_meta m, 
					wp_wpcw_modules c
					where 
					u.unit_id = a.post_id
					and
					u.unit_id = m.unit_id
					and
					a.post_id =m.unit_id
					and
					m.parent_course_id and c.parent_course_id
					and 
					m.parent_module_id = c.module_id
					and 
					page_order > 0 
					and
					u.user_id = ". $userID ."
					and
					u.unit_id in (Select unit_id from wp_wpcw_units_meta where parent_course_id = ". $courseID .") order by module_id", OBJECT); 
	$num_activities = $wpdb->num_rows;
        if ($courseData){
         foreach ($courseData as $courseDataItem) {   //if it is the module id passed in and there has been some progress
		if ($courseDataItem->course_id == $courseID && $courseDataItem->course_progress > 0){
		$progressBar.="<div id=courseProgress><h3 class=template>Your progress on the " . $courseDataItem->course_title." Module:</h3>";
		$progressBar.= WPCW_stats_convertPercentageToBar($courseDataItem->course_progress, $courseDataItem->course_title);
		$progressBar.="</div>";
		//get the pre post test information
		  $sql= $wpdb->prepare("select quiz_title, quiz_type, quiz_correct_questions,quiz_question_total, parent_unit_id, quiz_show_answers
                    from 
                    wp_wpcw_user_progress_quizzes p,
                    wp_wpcw_quizzes q
                    where q.quiz_id =p.quiz_id
                    and q.parent_course_id = %d
                    and user_id = %d
                    order by quiz_type",  $courseID,$userID);
		//get the users activity answers for this module
		    $rows = $wpdb->get_results($sql,OBJECT);
		    if ($wpdb->num_rows > 0){
		    $quiz_survey_summary ="<div id ='quiz_survey_summary'><h3 class=template>Your tests, activities, and satisfaction survey <span style='padding-right:30px; margin-left: 40px;'><img src='/wp-content/uploads/2014/06/printer.png' class='printposttest' title='Print test and summary'> &nbsp;&nbsp;&nbsp;<img class='emailModuleSummary' src='/wp-content/uploads/2014/07/email-icon-vector.jpg' title='Email test and summary results' height='30' width='40' ><label class=sendtolabel>Send to: </label><input name=emailaddress id=sendtoaddress></span></h3>";
			    foreach ($rows as $item){
			        if ($item->quiz_show_answers == 'show_answers' || $item->quiz_type == 'survey'){
			        $quiz_survey_summary .= "<p class=template><strong>". $item->quiz_title ."</strong>&nbsp;&nbsp;&nbsp;";
				}
				else{
				 $quiz_survey_summary .= "<p><strong>". $item->quiz_title ."</strong> &nbsp;&nbsp;&nbsp;";
				}
			        if ($item->quiz_type <> 'survey'){$quiz_survey_summary .= "<span class=score> Score: " . $item->quiz_correct_questions ."/" . $item->quiz_question_total ."</span>&nbsp;&nbsp;&nbsp;";}
				if ($item->quiz_show_answers == 'show_answers' || $item->quiz_type == 'survey'){
				        
					if ($item->quiz_type <> 'survey'){
					$quiz_survey_summary .="<div id='showPostTestLink'><a href='#'>Review my post test</a></div>";
					 $postTestID =$item->parent_unit_id;
					}
					else{
					$quiz_survey_summary .="<a class=fancybox href=\"#fancypopup\"><BR>Review my satisfaction survey</a>";
					 $surveyID =$item->parent_unit_id;
					}
				}
			    }
		             if ($num_activities > 0){
			     $quiz_survey_summary.="<p class=template><strong>You have completed ".$num_activities." activities with this module.</strong><div id='showActivitySummaryLink'><a href='#'>Review my activities</a></div></p>";
			     }
		    $quiz_survey_summary .="<p></div>";
		    }
		    else{
		    $prepostsurvey="<h3 class=template>There currently is no pretest, posttest, or satisfaction surveys for " . $moduleName ."</h3>";
		    }
		   
		   //get the post quiz details
		   $quizDetails = WPCW_quizzes_getAssociatedQuizForUnit($postTestID);
		   $quizProgress = WPCW_quizzes_getUserResultsForQuiz(get_current_user_id(), $postTestID, $quizDetails->quiz_id);

		   // User has completed the test ... show the results.
		   if ($quizProgress)  
		   {
			$postTestBox .= WPCW_quizzes_showAllCorrectAnswers($quizDetails, $quizProgress);
		   }
		   
		   //get the satisfaction survey
		   $quizDetails = WPCW_quizzes_getAssociatedQuizForUnit($surveyID);
		   $quizProgress = WPCW_quizzes_getUserResultsForQuiz(get_current_user_id(), $surveyID, $quizDetails->quiz_id);

		   // User has completed the survey... show the results.
		   if ($quizProgress)  
		   {
			$surveyBox .= WPCW_quizzes_showAllCorrectAnswers($quizDetails, $quizProgress);
		   }
		   //output the activities and answers if there are any
		   if ($num_activities > 0){
		   $activityContent.="<hr style='background-color: #C17400; height: 5px;'>";
		           foreach ($activityRows as $item){
				$activityContent.="<p><a href='". get_site_url() ."/?p=". $item->post_id ."' target=_blank title='Click if you want to review this module activity'><strong>Activity ". $item->module_title ." : </strong></a>" . $item->description ."<br><strong>Your response:</strong> " . $item->activity_value."</p>";
			   }
		   }
		   else{
		        $activityContent.="<p>There were no activities with saved responses for ". $moduleName ."</p>";
		   }
		   
		}
		else{
                $progressBar .="<h3 class=template>You have not started the ".$moduleName. " module yet.</h3>Go to our modules listing to <a href='/online-training-modules/'>start your professional development training</a>.";
               }
	}
       }
        else{
       $progressBar .="<h3>You have not started the ". $moduleName. " module yet. </h3>Go to our modules listing to <a href='/online-training-modules/'>start your professional development training</a>.";
       }
       ?>
<?php $rightnow = time();?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory');?>/template_style.css?v=<?php echo $rightnow; ?>" media="screen" />
<section class="content">
<?php get_template_part('inc/page-title'); ?>	
    <div class="pad group">
		<article>
		<div style="padding-left: 30px;">
			<?php the_content(); 
			echo $progressBar;
			echo $quiz_survey_summary;
			
		      
                        echo "<div id = postTestSection>". $postTestBox ."</div>";
			echo "<div class=fancybox-hidden><div id=fancypopup style=\"width: 800px; height: 1100px;\">" . $surveyBox ."</div></div>";
		        echo "<div id=activityContentSection>". $activityContent ."</div>";
		
			?>
		</div><!--/.entry-->
	</article>
</div><!--/.pad-->
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 

