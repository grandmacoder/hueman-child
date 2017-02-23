<?php
/*
Template Name: PDhub student progress
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
    $userID = get_current_user_id();
    $aOptions = get_option( 'user-group-meta' );

if (isset($_GET['id']) && is_numeric($_GET['id'])){
         $group_id=$_GET['id'];
	}
else{
die("sorry but that is bad input");
}
$no_roster_members="";
$content="";
$formItems="";
$terminfo=get_term_by( 'id', $group_id,'user-group');
//see if there is more than 1 member in the group
$members = get_objects_in_term( $group_id, 'user-group');
$term_taxonomy_id = $terminfo->term_taxonomy_id;
//get the users and module data based on the group
$course_ids = $_GET['course_ids'];

//build the form
	//build drop down menu for admin user to select different rosters for all roster leaders
	if(current_user_can( 'administrator' )){
		//get all users that are a part of PDhub group
		$pd_hub_user_group = tc_pdhub_get_users_group();
			$adminSelectList .= "<select id=pd_hub_roster_select>
			<option value=''>Select a roster</option>";
			foreach($pd_hub_user_group as $pd_hub_user){
			     //get all rosters for every user that has created a roster. They should be a roster leader.
				$user_rosters = tc_pdhub_get_roster_leaders($pd_hub_user->object_id);
				if($wpdb->num_rows > 0){
					$user_data = get_userdata($pd_hub_user->object_id);
					$user_name = $user_data->last_name.", ".$user_data->first_name;
					$adminSelectList .= "<optgroup label='".$user_name."'>";
				    foreach($user_rosters as $user_roster){
				         $adminSelectList .= "<option value=".$user_roster->term_id;
						 if ($user_roster->term_id == $group_id) {$adminSelectList .= " selected ";}
						 $adminSelectList .= ">".$user_roster->name."</option>";
					}
					$adminSelectList .= "</optgroup>";	
				}
		}
		$adminSelectList .=  "</select>";
	  }
			//get the modules and their images set up as checkboxes
			//if the course leaders state is MO then get their modules
			$user_state = get_user_meta($userID, 'state', true);
			if ($user_state=='MO'){ $module_audience ="MO";}
				else { $module_audience ="ALL";}
			$courseCBO=tc_get_course_select_list($module_audience);
			//loop through and build the checkboxes
			foreach ($courseCBO as $course){
				if ($course->course_id <> 38 && $course->course_id <> 39 ){
			      $checkboxItems.='<img src="'. $course->course_logo_path. '" rel="'. $course->course_id.'"/>
			      <input type="checkbox" name=course_ids id="'. $course->course_id.'" value="'. $course->course_id.'"/>';	
				}
			}
			//create the select form
			$formItems.="<div id='nestedAccordionModule'>
			 <h2>Click to choose the learning module(s) that you want to see progress on:</h2>
			 <div><form id=moduleProgressChoices name=moduleProgressChoices>";
			 if(current_user_can( 'administrator' )){
			 $formItems .=$adminSelectList;
			 }
			 $formItems.='<div id="selectable_images">';
			 $formItems.=$checkboxItems;
			 $formItems.='</div>';
			 $formItems.="<input type=hidden value=" .$group_id." name=roster_group id=roster_group>" ;
			 $formItems .="<br><input type=button id=btnSubmitModuleChoices value=Submit></form></div></div>";
	         $roster_module_report_rows= tc_pdhub_get_roster_summary_data_by_group($course_ids, $group_id);
	         $numRosterRecords = $wpdb->num_rows;
	//searched for courses but no one has started yet
	if ($numRosterRecords <=0 && $course_ids<>""){
       $content.="<br><h3>Roster Details</h3><br>";
		$content .="<p><strong> Roster name: </strong>". $terminfo->name .", ". $terminfo->description ."
		<BR><strong>Semester: </strong>" . $aOptions[$group_id]['group-semester'] .
		"<BR><strong>School: </strong>". stripslashes($aOptions[$group_id]['group-school'])."</p>";
	    $current_course_id =0;
		$i=0;
		$roster_module_report_0_rows = tc_pdhub_get_roster_0_percent_by_group($course_ids, $term_taxonomy_id);
		
		foreach ($roster_module_report_0_rows as $item){
		$current_course_id=$item->course_id;
	    if ( $current_course_id <> $previous_course_id ){
			if ($i <> 0){ $tableContent.="</table>";}
             // start a new table
			 $tableContent.="<br><h3>Module: ". $item->course_title."</h3>";
			 $tableContent.="<table class=basic_table><tr><th>Student</th><th>% Complete</th><th>Pre-test</th><th>Post Test</th><th>Summary Sheet</th></tr>";		 
		    }
		 $student_first=get_usermeta($item->object_id,'first_name',true);
		  $student_last=get_usermeta($item->object_id,'last_name',true);
		  $tableContent.="<tr><td>".$student_first ." " . $student_last."</td><td style='background-color:#e35563;'>0%</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>";		 	
		  $previous_course_id=$current_course_id;
		  $i++;
        }
		$tableContent.="</table>";
        $content.=$tableContent;
    }
    if($roster_module_report_rows){
	    $content.="<br><h3>Roster Details</h3><br>";
		$content .="<p><strong> Roster name: </strong>". $terminfo->name .", ". $terminfo->description ."
		<BR><strong>Semester: </strong>" . $aOptions[$group_id]['group-semester'] .
		"<BR><strong>School: </strong>". stripslashes($aOptions[$group_id]['group-school'])."</p>";
	    $current_course_id =0;
		$i=0;
		$sMatchedCourses="";
		foreach ($roster_module_report_rows as $item){
	    if (is_numeric($item->former_course_id)){
			$current_course_id = $item->former_course_id;
			}
		else{
			$current_course_id=$item->parent_course_id;
		}
		$sMatchedCourses.=$current_course_id.",";
	    if ( $current_course_id <> $previous_course_id ){
			 $roster_module_0_percent_rows= tc_pdhub_get_modules_not_started_data($current_course_id, $term_taxonomy_id);
			if ($i <> 0){ $tableContent.="</table>";}
             // start a new table
			 $tableContent.="<br><h3>Module: ". $item->course_title."</h3>";
			 $tableContent.="<table class=basic_table><tr><th>Student</th><th>% Complete</th><th>Pre-test</th><th>Post Test</th><th>Summary Sheet</th></tr>";		 
		    }
		 $student_first=get_usermeta($item->user_id,'first_name',true);
		 $student_last=get_usermeta($item->user_id,'last_name',true);
		
		 //handle if the test has been changed and the course id was set back to 0 for the test
		 if (is_numeric($item->former_course_id)){
		 $datacourseid = $item->former_course_id;}
		 else {$datacourseid = $item->parent_course_id;}
		 $studentCourseSummaryLink = "<a class='show_student_course_summary' href='#' data-courseid='".$datacourseid ."' data-userid='".$item->user_id."'>".  		  		$student_first ."  " .$student_last. "</a>";	
		 $studentCourseSummaryLink = $student_first ."  " .$student_last;
		 //pretest was on previous record and progress in 100 so we have a full record
		 if ($item->course_progress >= 95 && $pretestInfo <> ""){
		$quizDetails = WPCW_quizzes_getAssociatedQuizForUnit($item->parent_unit_id);
		 $quizProgress = WPCW_quizzes_getUserResultsForQuiz($item->user_id, $item->parent_unit_id, $quizDetails->quiz_id);
	     $postTestBox = WPCW_quizzes_showAllCorrectAnswers($quizDetails, $quizProgress);
		 $popUpPostTest="<a class=fancyboxorder data-order = ".$item->user_id.$i." href=\"#fancypopup".$item->user_id."\">Review post test</a><div class=fancybox-hidden><div id=fancypopup".$item->user_id.$i." style='width: 800px; height: 1100px;'>".$postTestBox."</div></div>";
		 if($item->completed_date <> ""){
		$date = date_create($item->completed_date);
		$complete_date = date_format($date, "m/d/Y");
		}
	     $summarySheetLink="<a class='print_summary_sheet' href='#'  data-courseid='" . $datacourseid. " 'data-userid='".$item->user_id."'>View student summary</a>";	
         $tableContent.="<tr><td>". $studentCourseSummaryLink . "</td><td style='background-color:#b4c898;'>". $item->course_progress."%</td><td>".$pretestInfo."</td><td>".$item->quiz_correct_questions."/".$item->quiz_question_total."<br>Taken on: ".$complete_date."<br>".$popUpPostTest."</td><td>".$summarySheetLink."</td></tr>";		 
		 //reset
		  $pretestInfo="";
		 }
		 else if($item->course_progress >=95 && $pretestInfo == ""){
		 $pretestInfo=$item->quiz_correct_questions."/".$item->quiz_question_total;			
		 }
		 elseif ($item->course_progress == 0 ){
		 $tableContent.="<tr><td >". $studentCourseSummaryLink. "</td><td style='background-color:#e35563;'>". $item->course_progress."%</td><td>No Pre-test</td><td>No Post-test</td></tr>";		 
		 }
		 else if (($item->course_progress < 95 and $item->course_progress > 0) && $pretestInfo == ""){
		 $tableContent.="<tr><td>". $studentCourseSummaryLink . "</td><td style='background-color:#dcb069;'>". $item->course_progress."%</td><td>".$item->quiz_correct_questions."/".$item->quiz_question_total."</td><td>No Post-test</td></tr>";		 
		 }
		  if ($previous_course_id<>$current_course_id){
			 foreach ( $roster_module_0_percent_rows as $item){
		 $student_first=get_usermeta($item->object_id,'first_name',true);
		 $student_last=get_usermeta($item->object_id,'last_name',true);
		 $tableContent.="<tr><td>".$student_first ." " . $student_last."</td><td style='background-color:#e35563;'>0%</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>";
		}
		}
		$previous_course_id=$current_course_id;
		$i++;
        }

		$tableContent.="</table>";
        $content.=$tableContent;
        $content.="<div id='summary_sheet' title='Summary Sheet'><div id='box_msg'><span style='font-family: Georgia; font-size: large;'></span></div></div>";
		$content.="<div id='student_course_summary' title='Student Course Summary'><div id='student_course_summary_box_msg'><span style='font-family: Georgia; font-size: large;'></span></div></div>";
	}
	
	//if you come out of this if statement and did not have data for all the courses, the rest of the courses have no progress
	$sMatchedCourses=rtrim($sMatchedCourses,',');
	$aMatchedCourses=explode(',',$sMatchedCourses);
	$aCourses = explode(',',$course_ids);
	$coursesNotStarted = array_diff( $aCourses,$aMatchedCourses);
	if (count($coursesNotStarted)> 0 && $roster_module_report_rows){
         $new_course_ids =  implode(",",$coursesNotStarted);
			$current_course_id =0;
			$i=0;
			$roster_module_report_0_rows = tc_pdhub_get_roster_0_percent_by_group($new_course_ids, $term_taxonomy_id);
			foreach ($roster_module_report_0_rows as $item){
			$current_course_id=$item->course_id;
			if ( $current_course_id <> $previous_course_id ){
				if ($i <> 0){ $tableContent2.="</table>";}
				 // start a new table
				 $tableContent2.="<br><h3>Module: ". $item->course_title."</h3>";
				 $tableContent2.="<table class=basic_table><tr><th>Student</th><th>% Complete</th><th>Pre-test</th><th>Post Test</th><th>Summary Sheet</th></tr>";		 
				}
			 $student_first=get_usermeta($item->object_id,'first_name',true);
			  $student_last=get_usermeta($item->object_id,'last_name',true);
			  $tableContent2.="<tr><td>".$student_first ." " . $student_last."</td><td style='background-color:#e35563;'>0%</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>";		 	
			  $previous_course_id=$current_course_id;
			  $i++;
			}
			$tableContent2.="</table>";
			$content.=$tableContent2;
}

//the only member is the user who created the roster
	if (count($members) <= 1){
	    $no_roster_members .= "<div style='padding-left: 30px;'><p style='color:red;font-weight:bold;'>Oops! No one has joined your roster yet.<br> 
		<a id='".$group_id."' class='manageStudentsOnRoster' title='Manage the members on this roster' href='/pd-hub-roster/?rosterid=".$group_id."&action=editroster'>Click here to invite participants/students to your roster.</a></p></div>";
	}

?>
<script src="<?php bloginfo('stylesheet_directory');?>/js/jquery.alerts.js" type="text/javascript"></script>
<script src='https://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory');?>/portfolio_module_style.css?v=<?php echo time(); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory');?>/jquery.alerts.css?v=<?php echo time(); ?>" media="screen" />

<section class="content">
<h3>Student Progress</h3>
    <div>
		<article>
		<div style="margin-left: -70px; padding-top: 20px;">
			<div id="messageArea"></div>
			<?php 
	        if ($no_roster_members == "" ){echo $formItems;}
			echo $content;
			echo $no_roster_members;
			?>
		</div><!--/.entry-->
	</article>
</div><!--/.pad-->
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
