<?php
/**
 * Unit Template Name: Final Steps Template
 *
 * Be sure to use the "Unit Template Name:" in the header.
 * To display the course unit content, be sure to inclue the loop.
 */
get_header();
?>
<?php
	$currentPostID=get_the_ID();
	$courseID =$wpdb->get_var("Select parent_course_id from wp_wpcw_units_meta where unit_id=" . $currentPostID);
               if (!isset($_GET['userID'])){
		$userID = get_current_user_id();
		}
		else{
		$userID = $_GET['userID'];
		}
	$additional_link_content="";  
	$checklistContent="";
	$notCompletedList="";
	$v1_ppt_unit_ids ='3289,3905';
	$v1ppt = $wpdb->get_var($wpdb->prepare("select count(unit_id) from wp_wpcw_user_progress_quizzes where user_id =%d and unit_id in (". $v1_ppt_unit_ids .")", $userID ));
	 $moduleName = tc_portfolio_module_get_name($courseID);
	//will get all course data for the user, we weed it out in the foreach
        $courseData = WPCW_users_getUserCourseList($userID);
	//get the total activities for the module based on the units postmeta
	$totalCourseActivities = tc_portfolio_module_get_activities($courseID);
	//course activity text area answers
	$activityAnswers = tc_portfolio_module_get_activity_answers($userID, $courseID);
	$num_activities = sizeof($activityAnswers);

	//get the posts that have checkbox items on them from the matrix table
     $checkboxPosts=tc_portfolio_module_get_check_boxes($courseID);
	 $num_checkbox_activities = sizeof($checkboxPosts);

	//get the last item that the user was working on.
	  $nextToCompleteID = tc_portfolio_module_get_last_activity($userID, $courseID);
	  
       if ($courseData){
        foreach ($courseData as $courseDataItem) {   //if it is the module id passed in and there has been some progress
	
		if ($courseDataItem->course_id == $courseID && $courseDataItem->course_progress > 0){
		$summaryHeader="<h3>".  $courseDataItem->course_title." Summary</h3>";
		$progressBar.="<div id=courseProgress><a href='/portfolio-module-progress/' title='back to module list' alt='back to module list'><img src='/wp-content/uploads/2014/01/ipad_L.png'> Back to my module list</a><h3 class=template>Your progress on the " . $courseDataItem->course_title." Module:</h3>";
		$progressBar.= WPCW_stats_convertPercentageToBar($courseDataItem->course_progress, $courseDataItem->course_title);
		$course_progress= $courseDataItem->course_progress;
		 
	//create a link to the certificate
		        $certificateDetails = WPCW_certificate_getCertificateDetails($userID, $courseID , false);
				if ($certificateDetails) {
					$certificateLink = WPCW_certificate_generateLink($certificateDetails->cert_access_key);
					$certButtonLink="<br><a href='" . $certificateLink."' class='fe_btn fe_btn_download'>Download Certificate</a>";
				}
	 $progressBar.=$certButtonLink;
	 $progressBar.="</div>";
		//get the pre post test information
		  $test_data = tc_portfolio_module_get_prepost_test($userID, $courseID,$v1ppt, $v1_ppt_unit_ids);
		    $numQuizzes = sizeof($test_data);
		    if (  $numQuizzes > 0){
			    if ($numQuizzes  >=2){
			    $quiz_survey_summary ="<div id ='quiz_survey_summary'><h3 class=template>Your tests and  activities<span style='margin-left: 20px;'><img src='/wp-content/uploads/2014/06/printer.png' class='printposttest' title='Print test and summary'> &nbsp;&nbsp;<img id='". $courseID."' class='emailModuleSummary' src='/wp-content/uploads/2014/07/email-icon-vector.jpg' title='Email test and summary results' height='30' width='40' ></span></h3>";
			    }
			   else{
			     $quiz_survey_summary ="<div id ='quiz_survey_summary'><h3 class=template>Your module progess so far... </h3>";
                                }
		 
		 //if they are not finished then show the items that are left to complete
		 if ($course_progress < 100){
		   //if the number of completed units <> the number of units in the course - 2 (post test and survey) and $numQuizzes  < 2
		   $notCompletedList.="";
		   $courseUnits = $wpdb->get_results("select * from wp_wpcw_units_meta where parent_course_id =". $courseID,OBJECT);
		   $totalCourseUnits = $wpdb->num_rows;
		   $notCompletedUnits = $wpdb->get_results("select unit_id from wp_wpcw_units_meta where parent_course_id = ". $courseID ." and unit_id not in (Select unit_id from wp_wpcw_user_progress where user_id =". $userID.")",OBJECT);
		   $numNotCompleted=$wpdb->num_rows;
		   $leftToComplete = ($totalCourseUnits - $numNotCompleted);
		   if ($leftToComplete > 2){
		     //create a list of all except the last 2 items
			 foreach ($notCompletedUnits as $unit){
			     $theTitle = get_the_title($unit->unit_id);
				 if (strpos($theTitle, 'Satisfaction Survey') === false && strpos($theTitle, 'Post-test')===false ){
				 $notCompletedList.="<p><strong>Please complete </strong><a href='". get_site_url() ."/?p=". $unit->unit_id ."' target=_blank title='Click if you want to work on this item'><strong>". $theTitle ." : </strong></a></p>";
			       }

			 }
			}
		 
		 }
		 //self determination module
		  if ($course_progress == 100 && $courseID == 15 ){
		   $additional_link_content = 'Here is the <a title="Print my Reflection and Implementation Plan" href="/generateReflectionImplementation.php"><strong>Reflection &amp; Implementation Plan</strong></a> <img src="http://wtest.transitioncoalition.org/wp-content/uploads/2014/06/printer.png" alt="printer" width="18" height="18" /> that you created in this module. This plan can be used as a tool to implement some of the practices you have learned with respect to Self-Determination.';
		  
		  }
		
		 //enhancing employment outcomes
		 if ($course_progress == 100 && $courseID == 12 ){
		 $additional_link_content = "<a title='Action Plan Summary' href='/action-plan-summary/' target='_blank'>Your action plan for enhancing your employment program/practices. </a>";
		 }
		
			    
			    foreach ($test_data as $item){
			        if ($item->quiz_show_answers == 'show_answers'){
				 $quiz_summary .="<p><strong>". $item->quiz_title ."</strong> &nbsp;&nbsp;&nbsp;";
				}
			        if ($item->quiz_show_answers == 'show_answers' || $item->quiz_type == 'survey'){
			        $quiz_survey_summary .= "<p class=template><strong>". $item->quiz_title ."</strong>&nbsp;&nbsp;&nbsp;";  
                               	}
				else{
				 $quiz_survey_summary .= "<p><strong>". $item->quiz_title ."</strong> &nbsp;&nbsp;&nbsp;";
				 $quiz_summary .="<p><strong>". $item->quiz_title ."</strong> &nbsp;&nbsp;&nbsp;";
	                        }
			        if ($item->quiz_type <> 'survey'){ //it is a quiz
					if ($numQuizzes < 2) { //the user has only completed the pretest
					 $quiz_survey_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $item->quiz_correct_questions ."/" . $item->quiz_question_total ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$item->quiz_completed_date;
					 $quiz_survey_summary .="<br><span class=score>You have not completed the post test.</span> You can take the post test when you have completed the module.<br>";
					 $quiz_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $item->quiz_correct_questions ."/" . $item->quiz_question_total ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$item->quiz_completed_date;
					 $quiz_summary .="<br><span class=score>You have not completed the post test.</span> You can take the post test when you have completed the module.<br><br>";
					
					}
					else{
					$quiz_survey_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $item->quiz_correct_questions ."/" . $item->quiz_question_total ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$item->quiz_completed_date;
					$quiz_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $item->quiz_correct_questions ."/" . $item->quiz_question_total ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$item->quiz_completed_date ."<br>";
					}	
				}
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
			     $quiz_survey_summary.="<p class=template><strong>You have completed ".$num_activities."/".$totalCourseActivities." activities with this module.</strong></p>";
				     if ($num_activities == $totalCourseActivities || $nextToCompleteID == null ){
				     $quiz_survey_summary.="<div id='showActivitySummaryLink'><a href='#'>Review my activities</a></div>";
				     }
				     else{
					if ($nextToCompleteID > 0){
					//$quiz_survey_summary .="<p class=template><a href='?p=". $nextToCompleteID."'>Click here </a>to pick up where you left off on this module</a></p>";
					}
				     }
			     }
		    $quiz_survey_summary .="<p></div>";
		    }
		   
		   
		   //get the post quiz details
		   $quizDetails = WPCW_quizzes_getAssociatedQuizForUnit($postTestID);
		   $quizProgress = WPCW_quizzes_getUserResultsForQuiz(get_current_user_id(), $postTestID, $quizDetails->quiz_id);
 
		   // User has completed the test and there are answers ... show the results.
		   if ($quizProgress && $quizProgress->quiz_data <> "")  {
		        $postTestBox .= WPCW_quizzes_showAllCorrectAnswers($quizDetails, $quizProgress);
		   }
		   else{ //remove the link to show the post test, we only saved pre and post scores when saving the legacy tests
		   $quiz_survey_summary=str_replace("<div id='showPostTestLink'><a href='#'>Review my post test</a></div>","",$quiz_survey_summary);
		   }
		   //get the satisfaction survey
		   $quizDetails = WPCW_quizzes_getAssociatedQuizForUnit($surveyID);
		   $quizProgress = WPCW_quizzes_getUserResultsForQuiz(get_current_user_id(), $surveyID, $quizDetails->quiz_id);

		   // User has completed the survey... show the results.
		   if ($quizProgress)  {
			$surveyBox .= WPCW_quizzes_showAllCorrectAnswers($quizDetails, $quizProgress);
		   }
		   
		   //output the activities and answers if there are any
		   if ($num_activities > 0){
		           foreach ($activityAnswers as $item){
				   $activityContent.="<p><strong>Source: </strong><a href='". get_site_url() ."/?p=". $item->post_id ."' target=_blank title='Click if you want to review this module activity'><strong>Activity ". $item->module_title ." : </strong></a>" . $item->description ."<br><strong>Your response:</strong> " . $item->activity_value."</p>";
			       }
		   }
		   else{
		        $activityContent.="<p>There were no activities with saved responses for ". $moduleName ."</p>";
		   }//end if activities
		   if ($num_checkbox_activities > 0){
		     foreach($checkboxPosts as $cbxpost){
					//get the checkbox items per post and the answers per user
					$userCheckboxSelections = tc_portfolio_module_get_check_box_selections($userID, $cbxpost->post_id);
					$sSelections = $userCheckboxSelections->activity_value;
					$aSelectedValues =  explode(',',$sSelections);
					//get the check box checked by user when doing the activity module
					$checklistItem = tc_portfolio_module_get_check_box_answers($cbxpost->post_id);
							$i=0;
							foreach ($checklistItem as $item){
							if (in_array($i,$aSelectedValues)){$bChecked='checked';}
								else {$bChecked='';
								}
							if ($i == 0){
							$checklistContent.="<hr><h3>Checklist items for <a style='font-size:14px' href='/?p=" . $cbxpost->post_id. "'>". $item->heading ."</a></h3>";
							}
							$checklistContent.="<p><input type='checkbox' id='". $i . "' name='compare' ". $bChecked."/><label id='label_" . $item->item_text . "' for='". $i . "'><span></span>". $item->item_text  ."</label>";
							$i++;
							}
					}
		   }//end if checklists
		    //if the number of completed units <> the number of units in the course - 2 (post test and survey) and $numQuizzes  < 2
		   $notCompletedList="";
		   $courseUnits = $wpdb->get_results("select * from wp_wpcw_units_meta where parent_course_id =". $courseID,OBJECT);
		   $totalCourseUnits = $wpdb->num_rows;
		   $notCompletedUnits = $wpdb->get_results("select unit_id from wp_wpcw_units_meta where parent_course_id = ". $courseID ." and unit_id not in (Select unit_id from wp_wpcw_user_progress where user_id =". $userID.")",OBJECT);
		   $numNotCompleted=$wpdb->num_rows;
		   $leftToComplete = ($numNotCompleted-2);
		   if ($leftToComplete > 2){
		   $notCompletedList.="<p><strong>Please complete the following module items.</strong></p>";
                  //create a list of all except the last 2 items
			 foreach ($notCompletedUnits as $unit){
			         $theTitle = get_the_title($unit->unit_id);
				     if (strpos($theTitle, 'Satisfaction Survey') === false && strpos($theTitle, 'Post-test')=== false ){
				     $notCompletedList.="<p><strong>Please complete </strong><a href='". get_site_url() ."/?p=". $unit->unit_id ."' target=_blank title='Click if you want to work on this item'><strong>". $theTitle ." : </strong></a></p>";
			       }
                         }
                  }
	      }
	
	 
	 }
	  //finish the hidden summary header
	 //get the user information for the header
         $user_info = get_userdata($userID);
         $header.=$user_info->first_name ." ". $user_info->last_name ."<br>";
	     $header.=$user_info->user_email ."<br>";
         $header.="State: ". $user_info->state ."<br>";
	     $summaryHeader.= $header;
	     $summaryHeader.=$quiz_summary ;
    }
        else{
       $progressBar .="<h3>You have not started any modules yet. </h3>Go to our modules listing to <a href='/online-training-modules/'>start your professional development training</a>.";
       }
       ?>
<link rel='stylesheet' href='https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css'>      
<script src="<?php echo get_stylesheet_directory_uri();  ?>/js/jquery.alerts.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/portfolio_module_style.css?v=<?php echo time(); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/jquery.alerts.css?v=<?php echo time(); ?>" media="screen" />
<section class="content">
    <div class="pad group">
		<article>
		   <?php the_content(); 
			echo $progressBar;
			echo $quiz_survey_summary;
			echo $additional_link_content;
			echo "<div class=fancybox-hidden><div id=fancypopup style=\"width: 800px; height: 1100px;\">" . $surveyBox ."</div></div>";
			echo "<div id='moduleDetailsContainer'>";
            echo "<div id = postTestSection>". $postTestBox ."</div>";
			if ($notCompletedList <> ""){
			echo "<div id=activitiesToCompleteSection><br><hr>". $notCompletedList ."</div>";
			}
			else{
		        echo "<div id=activityContentSection><hr>". $activityContent . $checklistContent."</div>";
		    }
			echo "</div>"; //end the container for tests and activities
			echo "<div id=printSummaryHeader><hr>". $summaryHeader."</div>";
		    ?>
		</div><!--/.entry-->
	</article>
</section><!--/.content-->		
<?php get_sidebar(); ?>

<?php 	get_footer(); ?> 

