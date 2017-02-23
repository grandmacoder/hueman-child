<?php
/*
Template Name: legacy portfolio module template
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
        $referring_page =$_SERVER['HTTP_REFERER'];
        if(isset($_GET['courseid']) && is_numeric($_GET['courseid'])){
	$courseID = $_GET['courseid']; //from the url
	}else{
		header("Location: /");
		exit();
	}
	if (!isset($_GET['userID'])){
	$userID = get_current_user_id();
	}
	else{
	$userID = $_GET['userID'];
	}
	global $wpdb; 
	$checklistContent="";
	//get test scores for module based on module id and user id
	$module_data = tc_legacy_portfolio_module_template_get_tests($userID, $courseID);
	
	foreach($module_data as $data){
		$quiz_survey_summary.="<a href='/portfolio-module-progress/' title='back to module list' alt='back to module list'><img src='/wp-content/uploads/2014/01/ipad_L.png'> Back to my module list</a>
		<br><h5>Your tests and activites for " . $data->module_name." Module:<span style='margin-left: 10px;'>
		<img src='/wp-content/uploads/2014/06/printer.png' class='printposttest' title='Print test and summary'></span></h5>";
		$quiz_survey_summary.="<p><strong>( Transition Coalition no longer offers this module )</strong></p>";
		$module_name = $data->module_name;
		$pretest = $data->pre_test_score;
		$posttest = $data->post_test_score;
		$posttest_date = $data->post_test_date;
		if($pretest <> ""){
			 $quiz_survey_summary .= "<p class=template><strong>". $module_name ." Pre-Test </strong>&nbsp;&nbsp;&nbsp;";  
			 $quiz_survey_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $pretest ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$data->pre_test_date;
			 $quiz_summary .= "<p class=template><strong>". $module_name ." Pre-Test </strong>&nbsp;&nbsp;&nbsp;";  
			 $quiz_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $pretest ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$data->pre_test_date;
		}
		if($posttest <> ""){
			 $quiz_survey_summary .= "<p class=template><strong>". $module_name ." Post-Test </strong>&nbsp;&nbsp;&nbsp;";  
			 $quiz_survey_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $posttest ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$data->post_test_date;
			 $quiz_summary .= "<p class=template><strong>". $module_name ." Post-Test </strong>&nbsp;&nbsp;&nbsp;";  
			 $quiz_summary .= "<span class=score>&nbsp;&nbsp;&nbsp;Score: " . $posttest ."</span>&nbsp;&nbsp;&nbsp;Completed on: " .$data->post_test_date;
		}else{
			$quiz_survey_summary .="<br><span class=score>You have not completed the post test.</span><br>";
		}
	}
	
	//get the total activities for the module
	$total_activities = tc_legacy_portfolio_module_template_total_activities($courseID);
	//get the total activites for the user on the module
	$num_activities = tc_legacy_portfolio_module_template_user_activities($userID, $courseID);
	 if ($num_activities > 0){                                                                                                    
			     $quiz_survey_summary .= "<p class=template><strong>You have completed ".$num_activities."/".$total_activities." activities with this module.</strong></p>";
				    
				     $quiz_survey_summary .= "<h5>Review my activities</h5>";
				   }
				    
		//get the questions and answers for activities on module for the user	     
		$activity_answers = tc_legacy_portfolio_module_template_questions_answers($userID, $courseID);
		$last_session = 0;										
		foreach ($activity_answers as $answer){
			$session = $answer->ss_question_session;
			if($session <> $last_session){
				$session = $answer->ss_question_session;
				$activityContent.="<h4 style='font-weight:bold; font-size: 18pt; background-color:#3b8dbd;' title='Summary Sheet'>Session: ".$session ."</h4>";
			}
					
			$activityContent.="<p><strong>Activity question: </strong>" . $answer->questions ."<br><strong>Your response:</strong> " . $answer->answer."</p>";
			$last_session = $answer->ss_question_session;
			}
	$user_info = get_userdata($userID);
	$header = "<h3>".$module_name."</h3>";
    $header.=$user_info->first_name ." ". $user_info->last_name ."<br>";
	$header.=$user_info->user_email ."<br>";
    $header.="State: ". $user_info->state ."<br>";
	$summaryHeader.= $header;
	$summaryHeader .= $quiz_summary;
       ?>
<link rel='stylesheet' href='https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css'>      
<script src="<?php echo get_stylesheet_directory_uri();  ?>/js/jquery.alerts.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/portfolio_module_style.css?v=<?php echo time(); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/jquery.alerts.css?v=<?php echo time(); ?>" media="screen" />
<section class="content">
    <div class="pad group">
		<article>
		<?php the_content(); 
		echo "<div id='legacy_module_content'>". $quiz_survey_summary .$activityContent."</div>";
		echo "<div id=activityContentSection><hr>". $activityContent."</div>";
		echo "<div id=printSummaryHeader><hr>". $summaryHeader."</div>";
		?>
	</article>
</div><!--/.pad-->
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 

