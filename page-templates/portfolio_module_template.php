<?php
/*
Template Name: portfolio module template
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
include_once 'wp-content/plugins/wp-courseware/pdf/pdf_certificates.inc.php';
?>
<?php

        $referring_page =$_SERVER['HTTP_REFERER'];
        if (isset($_GET['courseid']) && !is_numeric($_GET['courseid'])){
		header("Location: /");
		exit();
		}
		if (isset($_GET['userID']) && !is_numeric($_GET['userID'])){
		header("Location: /");
		exit();
		}
        $courseID = $_GET['courseid']; //from the url

		if (!isset($_GET['userID'])){
		$userID = get_current_user_id();
		}
		else{
		$userID = $_GET['userID'];
		}
	if ($courseID == 4){
	$v1_ppt_unit_ids ='3289,3905';
	$v1ppt = $wpdb->get_var($wpdb->prepare("select count(unit_id) from wp_wpcw_user_progress_quizzes where user_id =%d and unit_id in (". $v1_ppt_unit_ids .")", $userID ));
	}
	else if ($courseID == 17){
	$v1_ppt_unit_ids ='11538,11540';
	$v1ppt = $wpdb->get_var($wpdb->prepare("select count(unit_id) from wp_wpcw_user_progress_quizzes where user_id =%d and unit_id in (". $v1_ppt_unit_ids .")", $userID ));
	}
	else if ($courseID == 10){
	$v1_ppt_unit_ids ='4252,4300';
	$v1ppt = $wpdb->get_var($wpdb->prepare("select count(unit_id) from wp_wpcw_user_progress_quizzes where user_id =%d and unit_id in (". $v1_ppt_unit_ids .")", $userID ));
	}
	//else if ($courseID == 18){
	//$v1_ppt_unit_ids ='11612,11647';
	//$v1ppt = $wpdb->get_var($wpdb->prepare("select count(unit_id) from wp_wpcw_user_progress_quizzes where user_id =%d and unit_id in (". $v1_ppt_unit_ids .")", $userID ));
	//}
	$additional_link_content="";  
	$checklistContent="";
	$notCompletedList="";
	 $moduleName = tc_portfolio_module_get_name($courseID);
	//will get all course data for the user, we weed it out in the foreach
    $courseData = WPCW_users_getUserCourseList($userID);
	//get the total activities for the module based on the units postmeta
	$totalCourseActivities = tc_portfolio_module_get_activities($courseID);
	//course activity text area answers
	$activityAnswers = tc_portfolio_module_get_activity_answers($userID, $courseID);
	$num_activities = sizeof($activityAnswers);
    $post_test_id = $wpdb->get_var("select course_start_page_path from wp_wpcw_course_extras where course_id =" . $courseID);
	
	//get the posts that have checkbox items on them from the matrix table
          $checkboxPosts=tc_portfolio_module_get_check_boxes($courseID);
	     $num_checkbox_activities = sizeof($checkboxPosts);

	//get the last item that the user was working on.
	  $nextToCompleteID = tc_portfolio_module_get_last_activity($userID, $courseID);

	  if ($courseData){
        foreach ($courseData as $courseDataItem) {   //if it is the module id passed in and there has been some progress
	    if ($courseDataItem->course_id == $courseID && $courseDataItem->course_progress > 0){
		$summaryHeader="<h3>".  $courseDataItem->course_title." Summary</h3>";
		$progressBar.="<div id=courseProgress><h3 class=template>Your progress on the <a style='text-decoration:underline; color: #00225d;' href='". $post_test_id."'>" . $courseDataItem->course_title." Module:</a></h3>";
		$progressBar.="<div style='width: 300px;'>". WPCW_stats_convertPercentageToBar($courseDataItem->course_progress, $courseDataItem->course_title) ."</div>";
		$course_progress= $courseDataItem->course_progress;
		 
	//create a link to the certificate
		        $certificateDetails = WPCW_certificate_getCertificateDetails($userID, $courseID , false);
				if ($certificateDetails) {
					$certificateLink = WPCW_certificate_generateLink($certificateDetails->cert_access_key);
					$certButtonLink="<br><a href='" . $certificateLink."' class='fe_btn fe_btn_download'>Download Certificate</a>";
				}
			
	 $progressBar.=$certButtonLink;
	 $progressBar.="</div>";
	 //find out if there is pptest info on the legacy table
	 $module_data =tc_portfolio_module_get_legacy_ppt($userID, $courseID);
     if (sizeof($module_data) > 0){
		foreach($module_data as $data){
				$percentComplete=0;
				$quiz_survey_summary.="<div id ='quiz_survey_summary'><h3 class=template>Your tests and activities<span style='margin-left: 20px;'>
				<a class='print_summary_sheet_legacy' href='#' data-courseid='".$courseID."' data-userid='".$userID."' title='View and print summary sheet'>
				<img src='wp-content/uploads/2015/05/summary_sheet_icon.png' height='40' width='30'></a>
				&nbsp;&nbsp;<img id='". $courseID."' class='emailModuleSummary' src='/wp-content/uploads/2014/07/email-icon-vector.jpg' title='Email test and summary results' height='30' width='40'></span></h3>";
				$module_name = $data->module_name;
				$pretest = $data->pre_test_score;
				$posttest = $data->post_test_score;
				$posttest_date = $data->post_test_date;
				if ($pretest<> "" && $posttest <> ""){$percentComplete = 100;}
				if($pretest <> ""){
					 $quiz_survey_summary .= "<p><strong>".  $moduleName ." Pre-Test </strong>&nbsp;&nbsp;&nbsp;";  
					 $quiz_survey_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $pretest ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$data->pre_test_date;
					 $quiz_summary .= "<p><strong>". $module_name ." Pre-Test </strong>&nbsp;&nbsp;&nbsp;";  
					 $quiz_summary .= "<span class=score>&nbsp;Score: " . $pretest ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$data->pre_test_date;
					 $quiz_print_summary .= "<p><strong>Pre-Test </strong>&nbsp;";  
					 $quiz_print_summary .= "<span>&nbsp;Score: " . $pretest ."</span>&nbsp;Completed on: " .$data->pre_test_date;
				}
				if($posttest <> ""){
					 $quiz_survey_summary .= "<p><strong>".  $moduleName ." Post-Test </strong>&nbsp;&nbsp;&nbsp;";  
					 $quiz_survey_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $posttest ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$data->post_test_date;
					 $quiz_summary .= "<p><strong>".  $moduleName ." Post-Test </strong>&nbsp;&nbsp;&nbsp;";  
					 $quiz_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $posttest ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$data->post_test_date;
				     $quiz_print_summary .= "<p><strong>Post-Test </strong>&nbsp;";  
					 $quiz_print_summary .= "<span>&nbsp;Score: " . $posttest ."</span>&nbsp;Completed on: " .$data->post_test_date;
				}else{
					$quiz_survey_summary .="<br><span class=score>You have not completed the post test.</span><br>If you are ready, <a href='". $post_test_id."'> go to the bottom of the module listing to start the post test and survey.</a><br><br>";
				}
			//get the total activities for the module using the original module id
			if ($courseID == 11 ){$legacy_module_id = 4;}
			 elseif ($courseID == 13 ){$legacy_module_id = 5;}
			  elseif ($courseID == 14 ){$legacy_module_id = 2;}
			   elseif ($courseID == 10 ){$legacy_module_id = 3;}
			$total_activities = tc_legacy_portfolio_module_template_total_activities($legacy_module_id);
			//get the total activites for the user on the module
			$num_activities = tc_legacy_portfolio_module_template_user_activities($userID, $legacy_module_id);
			if ($percentComplete == 100){
			 $quiz_survey_summary .= "<p class=template><strong>You have completed all of the activities for this module.</strong></p>";
			}
			if ($num_activities > 0 && $percentComplete == 0){                                                                                                    
				$quiz_survey_summary .= "<p class=template><strong>You have completed ".$num_activities."/".$total_activities." activities with this module.</strong></p>";
			}
				//get the questions and answers for activities on module for the user	     
				$activity_answers = tc_legacy_portfolio_module_template_questions_answers($userID, $legacy_module_id);
			
				$last_session = 0;										
		             foreach ($activity_answers as $answer){
						$session = $answer->ss_question_session;
						if($session <> $last_session){
							$session = $answer->ss_question_session;
							$activityContent.="<h4 style='font-weight:bold; font-size: 18pt; background-color:#3b8dbd;' title='Summary Sheet'>Session: ".$session ."</h4>";
						}
						$activityContent.="<p><strong>Question: </strong>" . $answer->questions ."<br><strong>Response:</strong> " . $answer->answer."</p>";
						$last_session = $answer->ss_question_session;
						}
	       }
	 } //*********************************************************************************************************************************************************
	 else{  //process this from a wpcw record
	//get the pre post test information
    $test_data = tc_portfolio_module_get_prepost_test($userID, $courseID,$v1ppt, $v1_ppt_unit_ids ); 
	$numQuizzes = sizeof($test_data);
		    if ( $numQuizzes > 0){
			    if ($numQuizzes  >=2){
			    $quiz_survey_summary ="<div id ='quiz_survey_summary'><h3 class=template>Your tests and  activities<span style='margin-left: 20px;'>
			    <a class='print_summary_sheet' href='#' data-courseid='".$courseID."' data-userid='".$userID."' title='View and print summary sheet'><img src='wp-content/uploads/2015/05/summary_sheet_icon.png' height='40' width='30'></a>&nbsp;&nbsp;<img id='". $courseID."' class='emailModuleSummary' src='/wp-content/uploads/2014/07/email-icon-vector.jpg' title='Email test and summary results' height='30' width='40'></span></h3>";
			    }
			   else{
			     $quiz_survey_summary ="<div id ='quiz_survey_summary'><h3 class=template>Your module progess so far... </h3>";
                                }
		//if they are not finished then show the items that are left to complete
		 if ($course_progress < 100){
		   //if the number of completed units <> the number of units in the course - 2 (post test and survey) and $numQuizzes  < 2
		   $notCompletedList.="";
		   $totalCourseUnits = tc_portfolio_module_get_course_units($courseID);
		   $totalCourseUnits = $wpdb->num_rows;
		   $notCompletedUnits = tc_portfolio_module_get_courses_to_complete($courseID, $userID);
		   $numNotCompleted=sizeof($notCompletedUnits);
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
		   $additional_link_content = 'Here is the <a title="Print my Reflection and Implementation Plan" href="/reflection-and-implementation-plan/" target=_blank><strong>Reflection &amp; Implementation Plan</strong></a> <img src="/wp-content/uploads/2014/06/printer.png" alt="printer" width="18" height="18" /> that you created in this module. This plan can be used as a tool to implement some of the practices you have learned with respect to Self-Determination.';
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
					 $quiz_survey_summary .="<br><span class=score>You have not completed the post test.</span> You can take the post test when you have completed the module.";
					 $quiz_survey_summary .="<br>If you are ready, <a href='". $post_test_id."'> go to the bottom of the module listing to start the post test and survey.</a><br>";
					 $quiz_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $item->quiz_correct_questions ."/" . $item->quiz_question_total ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$item->quiz_completed_date;
					 $quiz_summary .="<br><span class=score>You have not completed the post test yet.</span> You can take the post test when you have completed the module. <br>";
					 $quiz_summary .= "<br>If you are ready, <a href='". $post_test_id."'> go to the bottom of the module listing to start the post test and survey.</a><br>";
					
					}
					else{
					$quiz_survey_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $item->quiz_correct_questions ."/" . $item->quiz_question_total ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$item->quiz_completed_date;
					$quiz_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $item->quiz_correct_questions ."/" . $item->quiz_question_total ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$item->quiz_completed_date ."<br>";
					}	
				}
				if ($item->quiz_show_answers == 'show_answers' || $item->quiz_type == 'survey'){
				        if ($item->quiz_type <> 'survey'){
					$quiz_survey_summary .="<div id='showPostTestLink'><img src='/wp-content/uploads/2015/05/test_icon.jpg' title='post test'><a href='#'>Review my post test</a></div>";
					 $postTestID =$item->parent_unit_id;
					}
					else{
					 $quiz_survey_summary .="<a class=fancybox href=\"#fancypopup\"><BR><img src='/wp-content/uploads/2015/05/survey_icon.png' title='satisfaction survey'>Review my satisfaction survey</a>";
					 $surveyID =$item->parent_unit_id;
					}
				}
			    }
		             if ($num_activities > 0){    
						 if ($num_activities >=$totalCourseActivities || $course_progress ==  100){
							   $quiz_survey_summary.="<p class=template><strong>You have completed all of the activities of this module.</strong></p>";
							   }	
						 else{
							  $quiz_survey_summary.="<p class=template><strong>You have completed ".$num_activities."/".$totalCourseActivities." activities of this module.</strong></p>";
						 }
						 if ($num_activities == $totalCourseActivities || $nextToCompleteID == null ){
						 //$quiz_survey_summary.="<div id='showActivitySummaryLink'><a href='#'>Review my activities</a></div>";
						 }
						 else{
							if ($nextToCompleteID > 0){
							//$quiz_survey_summary .="<p class=template><a href='?p=". $nextToCompleteID."'>Click here </a>to pick up where you left off on this module</a></p>";
							}
				        }
			     }
		    $quiz_survey_summary .="<p></div>";
		    }
		    else{
		    $prepostsurvey="<h3 class=template>There currently is no pretest, posttest, or satisfaction surveys for " . $moduleName ."</h3>";
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
				   //$activityContent.="<p><strong>Source: </strong><a href='". get_site_url() ."/?p=". $item->post_id ."' target=_blank title='Click if you want to review this module activity'><strong>Activity ". $item->module_title ." : </strong></a>" . $item->description ."<br><strong>Your response:</strong> " . $item->activity_value."</p>";
				   $activityContent.="<p><strong>Activity ". $item->module_title ." : </strong>" . $item->description ."<br><strong>Your response:</strong> " . $item->activity_value."</p>";
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
		   $totalCourseUnits = tc_portfolio_module_get_course_units($courseID);
		   $notCompletedUnits = tc_portfolio_module_get_courses_to_complete($courseID, $userID);
		   $numNotCompleted=sizeof($notCompletedUnits);
		   $totalCourseUnits = tc_portfolio_module_get_course_units($courseID);
		   $leftToComplete = ($totalCourseUnits - $numNotCompleted);
		   $leftToComplete = ($numNotCompleted-2);
		   if ($leftToComplete >= 2){
		   $notCompletedList.="<p><strong>Please complete the following module pages.</strong></p>";
                    //create a list of all except the last 2 items
			 foreach ($notCompletedUnits as $unit){
			         $theTitle = get_the_title($unit->unit_id);
				     if (strpos($theTitle, 'Satisfaction Survey') === false && strpos($theTitle, 'Post-test')=== false ){
				     $notCompletedList.="<p><strong>Please complete </strong><a href='". get_site_url() ."/?p=". $unit->unit_id ."' target=_blank title='Click if you want to work on this item'><strong>". $theTitle ." : </strong></a></p>";
			       }
                    }
                 }
		}
		}//end else it is saved as wpcw
	}//end foreach course data

 //finish the hidden summary header
	 //get the user information for the header
         $user_info = get_userdata($userID);
         $header.=$user_info->first_name ." ". $user_info->last_name ."<br>";
	     $header.=$user_info->user_email ."<br>";
         $header.="State: ". $user_info->state ."<br>";
	     $summaryHeader.= $header;
	     $summaryHeader.=$quiz_summary ;

    }//end if there is data for this course
        else{
       $progressBar .="<h3>You have not started any modules yet. </h3>Go to our modules listing to <a href='/online-training-modules/'>start your professional development training</a>.";
       }
       ?>
<script src='https://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
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
			echo "<div id='summary_sheet' title='Summary Sheet'>
                 <div id='box_msg'><span style='font-family: Georgia; font-size: large;'></span></div></div>";
			echo "<div class=fancybox-hidden><div id=fancypopup style=\"width: 800px; height: 1100px;\">" . $surveyBox ."</div></div>";
			echo "<div id='moduleDetailsContainer'>";
            echo "<div id = postTestSection>". $postTestBox ."</div>";
			if ($notCompletedList <> ""){
			echo "<div id=activitiesToCompleteSection><br><hr>". $notCompletedList ."</div>";
			}
			else{
		        echo "<div id=activityContentSection><hr>". $activityContent ."</div>";
		        }
			echo "</div>"; //end the container for tests and activities
			echo "<div id=printSummaryHeader><hr>". $summaryHeader."</div>";
			echo "<div id=print_module_sheet>". $summaryHeader .$activityContent ."</div>";
		    ?>
		</div><!--/.entry-->
	</article>
</section><!--/.content-->		
<?php get_sidebar(); ?>

<?php 	get_footer(); ?> 

