<?php
/*
Template Name: PDhub student QI
*/
/**
 * shows the progress on a students' qi if they are on the roster
 *
 * @package WordPress
 */
/* Get roster show qi surveys, no users on roster, link to roster manager
*/
get_header();
?>
<?php
    global $wpdb;
    $referring_page =$_SERVER['HTTP_REFERER'];
    $userID = get_current_user_id();
	$quiz_id = 5;
//get the extra values for the course --- need to automate this later.
//course logo image, course start page, 
//will get all course data for the user, we weed it out in the foreach
	$aOptions = get_option( 'user-group-meta' );
	if (isset($_GET['id']) && is_numeric($_GET['id'])){
         $group_id=$_GET['id'];
	}
	else{
	die();
	}
	$terminfo=get_term_by( 'id', $group_id,'user-group');
	$term_taxonomy_id = $terminfo->term_taxonomy_id;
	//get a count of all members in roster to build rows for table 
	$count_members_roster = tc_pdhub_student_progress_get_num_members($term_taxonomy_id);
	//build drop down menu for admin user to select different rosters for all roster leaders
	if($count_members_roster > 1){
		if(current_user_can( 'administrator' )){
		//get all users that are a part of PDhub group
		$pd_hub_user_group = tc_pdhub_get_users_group();
			$content .= "<form id ='pd_hub_roster_list' name='pd_hub_roster_list'>
						<select id = 'pd_hub_roster_select'>
						<option value=''>Select a roster</option>";
			foreach($pd_hub_user_group as $pd_hub_user){
			     //get all rosters for every user that has created a roster. They should be a roster leader.
				$user_rosters = tc_pdhub_get_roster_leaders($pd_hub_user->object_id);
				if($wpdb->num_rows > 0){
					$user_data = get_userdata($pd_hub_user->object_id);
					$user_name = $user_data->last_name.", ".$user_data->first_name;
					$content .= "<optgroup label='".$user_name."'>";
				
					foreach($user_rosters as $user_roster){
				
						$content .= "<option value='".$user_roster->term_id."'>".$user_roster->name."</option>";
						
					}
					$content .= "</optgroup>";	
				}
		}
		$content .= "</select>
		</form>
		<br>";		
		}
		//get the members for this roster
		$content.="<h3>Quality Indicators Survey</h3><br>";
                //create table 
		$content.="<div class=basic_table>";
		$content .="<table><tr><td width=200><strong>Student</strong></span></td><td width=300><strong> Time taken</strong></td><td width=300><strong>View</strong></td></tr>";
		//get all the ids of the members on this roster
		$roster = tc_pdhub_student_progress_get_module_progress($term_taxonomy_id);
		    foreach($roster as $roster_student) {
		    $userinfo = get_userdata( $roster_student->object_id);
		    $content .="<tr><td><span class='progress_roster_name'>".$userinfo->display_name."</td>";
		    //get the qi survey(s) for this user
		    $qi_surveys = tc_pdhub_student_progress_qi_surveys($roster_student->object_id, $quiz_id);
			if ($qi_surveys <> "none"){
		       foreach ($qi_surveys as $survey){
			    $timesCreated.= $survey->createdtime."<br>";
			    $surveyLinks.="<a href='/qi-results/?surveyref=".$survey->statistic_ref_id ."&qi_user=".$roster_student->object_id."' title='qi survey' target=_blank>QI Summary</a><br>";
                            }
	            $content.="<td>". $timesCreated."</td><td>". $surveyLinks."</td>";
		    }
		    else{
		    $content.="<td>No Survey</td><td>N/A</td>";
		    }
		}
		$content .= "</tr></table>";
	}
	else{
	$content.="<p style='color:red;font-weight:bold;'>Oops! No one has joined your roster yet.<br> 
		<a id='" .$group_id."' class='manageStudentsOnRoster' title='Manage the members on this roster' href='/pd-hub-roster/?rosterid=". $group_id."&action=editroster'>Click here to invite participants/students to your roster.</a></p>";
	}
?>

<section class="content">
    <div>
		<article>
		<div style="margin-left: -70px; padding-top: 40px;">
			<?php echo $content;?>
		</div><!--/.entry-->
	</article>
</div><!--/.pad-->
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
