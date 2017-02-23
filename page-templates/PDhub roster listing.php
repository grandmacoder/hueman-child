<?php
/*
Template Name: PDhub roster listing
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
	$currentUser= wp_get_current_user();
	$currentUserID = $currentUser->ID;
	$dropdownlist = "";
	//create a dropdown menu with roster leaders for all users that have a roster. 
	//Admin user will be able to select a roster leader to view all of their rosters they have created
	if(current_user_can( 'administrator' )){
		//get all users that are a part of PD hub group -term_taxonomy_id = 38
		$pd_hub_user_group = tc_pdhub_get_users_group();
			$form_action = $PHP_SELF;
			$dropdownlist.= "<form id ='pd_hub_roster_list' name='pd_hub_roster_list' method='post'>
						<select id = 'pd_hub_roster_select'>
						<option value=''>Select a roster leader</option>";
			//list each roster leader in dropdown menu
			foreach($pd_hub_user_group as $pd_hub_user){
				//get all users that have at least one roster. They should be roster leaders
				$user_rosters = tc_pdhub_get_roster_leaders($pd_hub_user->object_id);
				if($wpdb->num_rows > 0){
					$user_data = get_userdata($pd_hub_user->object_id);
					$user_name = $user_data->last_name.", ".$user_data->first_name;
					$dropdownlist .= "<option value='".$pd_hub_user->object_id."'>".$user_name."</option>";		
				}
		}
		$dropdownlist.= "</select>
		</form>";		
	}//end if user is admin
	
	$aGroups=array();
	$aGroupNames = array();
	$aIsGroupOwner = array();
	$index=$_GET['id'];
	if($index > 0){
		$roster_leader = $index;
	}else{
		$roster_leader = $currentUserID;
	}
	//get all rosters the roster leader has created
	$user_rosters = tc_pdhub_get_rosters_for_roster_leader($roster_leader);
	$i = 0;
	if ($wpdb->num_rows > 0){
	//get the names for each roster
	foreach ($user_rosters as $roster){
		$aIsGroupOwner[$i] = $roster->term_order;
		$aGroups[$i] = $roster->term_id;
		$aGroupNames[$i] = $roster->name;
		$i++;
	}
	}
	//start building content for page display
	if (count($aGroups) > 0){
			if($dropdownlist != ""){
				$content .= $dropdownlist;
			}
			$content.="<div class=rounded_table>
			<table><tr><td style='font-weight:bold; color:#FFF;'>Roster</td><td style='font-weight:bold; color:#FFF;'>School</td><td style='font-weight:bold; color:#FFF;'>Join Link</td><td style='font-weight:bold; color:#FFF;'>Actions</td></tr>";
			$aOptions = get_option( 'user-group-meta' );
					for ($i=0; $i < count($aGroups); $i++){
					$randString ="";
					$randomOptions = ['a','b','c','d','e','f','g','h','i','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',0,1,2,3,4,5,6,7,8,9];
						for ($j=0; $j<5; $j++){
						$randString.= $randomOptions[rand(0,36)];
						}
					$randomCourseLink = get_site_url() .'/blog/joinroster/?jn=' . $aGroups[$i] ."_". $randString;
					//get the users in this group 
					$index = $aGroups[$i];
					//handle users of roster group owner  that have privilege to edit and delete rosters
					//if user is a group owner 1 = group owner, 2 = facilitator group owner, 4 = both student and facilitator group owner
					if ($aIsGroupOwner[$i] == 1 || $aIsGroupOwner[$i] == 2 || $aIsGroupOwner[$i] == 4 ){
					$content .=	"<tr><td style='text-align:left;'>". stripslashes($aGroupNames[$i]) ."-" .$aOptions[$index]['group-semester'].
					"<br><a href='/manage-roster-page/?groupid=".$aGroups[$i]."&action=editroster' class=manageStudentsOnRoster id=".$aGroups[$i]." 
					title='Manage the members on this roster'>Manage roster</a><br> 
					<a href='/student-progress/?id=".$aGroups[$i]. "' title='View module progress for roster'>Module Progress</a><br>
					<a href='/student-qi-progress/?id=".$aGroups[$i]. "' title='View student qi results'>Quality Indicators Survey</a><br>
					</td>
					<td style='text-align:left;'>".stripslashes($aOptions[$index]['group-school'])."</td>";																																																																													
		
					$content .="<td style='text-align:left;'>".$randomCourseLink."</td>
					<td style='text-align:left;'><a href='/pd-hub-roster/?rosterid=".$aGroups[$i]."&action=editroster' title='Edit the name, semester, or school for this roster'>Edit Roster</a><br>
					<a href='#' class=confirmDeleteRoster id=".$aGroups[$i]." title='Remove this roster'>Delete Roster</a>
					<form id=deleteRosterForm action='' hidden='hidden' method='post'>
					<h3 style='color:red; font-size: 14px;'>Are you sure you want to delete this roster?<br></h3>
					<input type=hidden id=action name=action value='delete_a_roster'>
					<input type=hidden id=rostergroupid name=rostergroupid value=''>
					</form>
					</td>";
					}
					$content .=	"</tr>";
					}//end foreach group
				$content.="</table>";
	} //end if rows are returned
	else{
	$content="There are currently no rosters associated with your account. Get started by <a href='/pd-hub-roster/'>creating a new roster</a>.";
}	
$content.="<br><strong>*Reminder to invite participants/students to join your  roster</strong>
		  <ol>
		  <li>Copy and paste the join link from the table above, into an email if you want to invite participants to join the roster.</li>
		  <li>You may use <a title='Example join text' href='/wp-content/uploads/2014/04/PD_Hub_Join_Link_Example_Letter.doc' target='_blank'>this document</a> as a guide for what to include in the email.</li>
		   </ol> </div>";
?>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<script src="<?php bloginfo('stylesheet_directory');?>/js/jquery.alerts.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory');?>/portfolio_module_style.css?v=<?php echo time(); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory');?>/jquery.alerts.css?v=<?php echo time(); ?>" media="screen" />
<?php wp_nav_menu(array(
    'theme_location' => 'template_submenu',
	'container'       => 'div',
	'container_id'    => 'submenucontainer',
	'menu_id' => 'submenuid',
));
?>
<section class="content">
<div class="template_content">
<h3><span>My Rosters</span></h3>
<br>
<article>
<?php echo $content;?>
</article>
</div>
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 