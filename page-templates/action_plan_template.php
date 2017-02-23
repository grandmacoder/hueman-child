<?php
/*
Template Name: action plan template
*/
/**
 * shows the action plan for a user that is working on the enhancing employment outcomes module
 *
 * @package WordPress
 */

/* Displays customize output of action plan activity items
*/

get_header();
?>
<?php
	global $wpdb;
        $referring_page =$_SERVER['HTTP_REFERER'];
        $courseID = 12;
        $userID = get_current_user_id();
	$aTitles=array('Career Assessment','School-based Activities','Work-site Experiences','School-business Partnerships','On-site Trainging and Support','Post-secondary Education and Training');
	//this data is particular to specific posts
	$aPostIDs = array(6048,6090,6107,6306,6605,6607);
	$aChanges=array();
	$aOutcomes=array();
	$aSteps=array();
	//get the module name based on course id
	$moduleName = tc_action_plan_get_module_name($courseID);
	//will get all course data for the user, we weed it out in the foreach
        $courseData = WPCW_users_getUserCourseList($userID);
    //this data is particular to specific posts
	$postsToCheck ='6048,6090,6107,6306,6605,6607';
	$activity_str = "%will you take%";
	//get course activity text area answers
	$activityRows = tc_action_plan_get_course_activity_answers($userID, $aPostIDs,$activity_str);
	//create 4 arrays title, changes, steps, outcomes
	$stepscount=0;
        foreach ($activityRows as $item){
		
	$aCurrentPostIDs[$i] = $item->post_id;
	$i++;
	}
	if ($wpdb->num_rows == count($aTitles)){ //all were answered
	foreach ($activityRows as $item){
        $aSteps[$stepscount] = $item->activity_value;
	$stepscount++;
        }
	}
	elseif ($wpdb->num_rows == 0){ //none were answered
         for ($i=0; $i < count($aPostIDs); $i++){
		$aSteps[$stepscount] = "<strong><a  style='text-align:center;' href='/?p=". $aPostIDs[$i]."'>N/A</a></strong>";
		$stepscount++;
	}
	}
	else{   //some were answered
	        for ($i=0; $i < count($aPostIDs); $i++){
		if ($aPostIDs[$i] == $aCurrentPostIDs[$i]){
		 $aSteps[$stepscount] = $item->activity_value;
		}
		else{
		$aSteps[$stepscount] = "<strong><a style='text-align:center;' href='/?p=". $aPostIDs[$i]."'>N/A</a></strong>";
		}
		$stepscount++;
		}
	}
	//get user anticipated outcomes for action plan summary
	$activity_str = '%anticipated%';
	$activityRows = tc_action_plan_get_course_activity_answers($userID, $aPostIDs,$activity_str); 
       $outcomescount=0;
        foreach ($activityRows as $item){
	$aCurrentPostIDs[$i] = $item->post_id;
	$i++;
	}
	if ($wpdb->num_rows == count($aTitles)){ //all were answered
	foreach ($activityRows as $item){
        $aOutcomes[$outcomescount] = $item->activity_value;
	$outcomescount++;
        }
	}
	elseif ($wpdb->num_rows == 0){ //none were answered
         for ($i=0; $i < count($aPostIDs); $i++){
		$aOutcomes[$outcomescount] = "<strong><a  style='text-align:center;' href='/?p=". $aPostIDs[$i]."'>N/A</a></strong>";
		$outcomescount++;
	}
	}
	else{   //some were answered
	        for ($i=0; $i < count($aPostIDs); $i++){
		if ($aPostIDs[$i] == $aCurrentPostIDs[$i]){
		 $aOutcomes[$outcomescount] = $item->activity_value;
		}
		else{
		$aOutcomes[$outcomescount] = "<strong><a  style='text-align:center;' href='/?p=". $aPostIDs[$i]."'>N/A</a></strong>";
		}
		$outcomescount++;
		}
	}
	
	$activity_str = '%need to be made%';
	$activityRows = tc_action_plan_get_course_activity_answers($userID, $aPostIDs,$activity_str);
	//create 4 arrays title, changes, steps, outcomes
	$changescount=0;
	foreach ($activityRows as $item){
	$aCurrentPostIDs[$i] = $item->post_id;
	$i++;
	}
	if ($wpdb->num_rows == count($aTitles)){ //all were answered
	foreach ($activityRows as $item){
        $aChanges[$changescount] = $item->activity_value;
	$changescount++;
        }
	}
	elseif ($wpdb->num_rows == 0){ //none were answered
         for ($i=0; $i < count($aPostIDs); $i++){
		$aChanges[$changescount] = "<strong><a  style='text-align:center;' href='/?p=". $aPostIDs[$i]."'>N/A</a></strong>";
		$changescount++;
	}
	}
	else{   //some were answered
	        for ($i=0; $i < count($aPostIDs); $i++){
		if ($aPostIDs[$i] == $aCurrentPostIDs[$i]){
		 $aChanges[$changescount] = $item->activity_value;
		}
		else{
		$aChanges[$changescount] = "<strong><a style='text-align:center;' href='/?p=". $aPostIDs[$i]."'>N/A</a></strong>";
		}
		$changescount++;
		}
	}
	
	$print_actionplan="<div id ='printActionPlan'><span style='margin-left: 20px;'><img src='/wp-content/uploads/2014/06/printer.png' class='printactionplan' title='Print Action Plan'></div>";
	//build a table
	$contentStringPrint = "<table border=1><tr><th>Subject</th><th>Changes/Improvements</th><th>Action Steps</th><th>Anticipated Outcomes</th></tr>";
	$contentString="<table class=basic_table><tr><th>Subject</th><th>Changes/Improvements</th><th>Action Steps</th><th>Anticipated Outcomes</th></tr>";
	for ($i = 0; $i < count($aTitles); $i++){
	$contentString.="<tr><td>". $aTitles[$i] ."</td><td>". $aChanges[$i]. "</td><td>". $aSteps[$i] ."</td><td>". $aOutcomes[$i] ."</td><td></tr>";
	$contentStringPrint .= "<tr><td>". $aTitles[$i] ."</td><td>". $aChanges[$i]. "</td><td>". $aSteps[$i] ."</td><td>". $aOutcomes[$i] ."</td><td></tr>";
	}
	$contentString.="</table>";
	$contentStringPrint.="</table>";
	?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory');?>/portfolio_module_style.css?v=<?php echo time(); ?>" media="screen" />
<section class="content">
<?php get_template_part('inc/page-title'); ?>	
    <div class="pad group">
		<article>
		<div style="padding-left: 30px;">
			<?php the_content(); 
			echo $print_actionplan;
			echo "<div id='actionPlanSummary'><div style='text-align:center;font-size: 15px; font-weight: bold;'>Enhancing Employment Outcomes Action Plan</br></br>
Within the next 6 months, you identified that you would address the following </br>CHANGES through ACTIONS with ANTICIPATED OUTCOMES.</div></br></br>" . $contentString . "</div>";
echo "<div id='actionPlanSummaryPrint'><img src='http://transitioncoalition.org/transition/images/tclogo_500px_white.jpg' width='200'><div style='text-align:center;font-size: 15px; font-weight: bold;'>Enhancing Employment Outcomes Action Plan</br></br>
Within the next 6 months, you identified that you would address the following </br>CHANGES through ACTIONS with ANTICIPATED OUTCOMES.</div></br></br>" . $contentStringPrint . "</div>";
			
				?>
				
		
		</div><!--/.entry-->
	</article>
</div><!--/.pad-->
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 

