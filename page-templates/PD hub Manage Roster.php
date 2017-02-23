<?php
/*
Template Name: PDhub manage roster
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
	if (!is_numeric($_GET['groupid']) || !isset($_GET['groupid'])){
	die();
	}
	$groupid=$_GET['groupid'];
	$content .= "<div id='getGroupId' data-groupid='".$groupid."'></div>";
	$terminfo=get_term_by( 'id', $groupid,'user-group');
	$term_taxonomy_id = $terminfo->term_taxonomy_id;
        //get all the users with term_order = 1,2,3,4,5 with term taxonomy id. Roster roles: 1 = Roster Leader, 2 = Facilitator, 3 = Student, 4 = Both, 5 = Not assigned **
	$roster_members = $wpdb->get_results(
	$wpdb->prepare("Select object_id, term_order from wp_term_relationships 
	where term_order in (0,1,2,3,4,5)  
	and term_taxonomy_id =%d 
	order by term_order" ,
	$term_taxonomy_id, 
	OBJECT)
	);
	$num_members = $wpdb->num_rows;
	if ($num_members > 1){
	$content .="<br><h3>Manage Roster</h3><br>";
//start table for roster member list
foreach ($roster_members as $roster_leader){
if($roster_leader->term_order == 1){
	$userinfo = get_userdata( $roster_leader->object_id);
	$content .="<p><strong>Name of roster:  </strong>". $terminfo->name ."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<strong>Instructor: </strong>".$userinfo->display_name."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<strong> Email: </strong> ".$userinfo->user_email."</p>";
	}
}
//create form for roster list. Term_taxonmy_id used to assign rosters in jquery
$content.="<form id='assignRosterMembers' method='post' data-term_taxonomy_id=".$term_taxonomy_id." <div class=rounded_table>
	<table><tr><td style='font-weight:bold; color:#FFF;'>Name</td><td style='font-weight:bold; color:#FFF;'>Email
	</td><td style='font-weight:bold; color:#FFF;'>Delete</td><td style='font-weight:bold; color:#FFF;'>Assign Role</td></tr>";
//determine term_order=roster role 
foreach ($roster_members as $member){
//if term order= 0 then set it to 5--user was added to group manually without the join link
    if($member->term_order > 1){
	$userinfo = get_userdata( $member->object_id);
        $member_radio_buttons = "";
	$member_role = $member->term_order;
	
//jquery is used to set radio buttons for roster roles, based off user id and and member role in input type elements
//build data for table for list of roster members
	$content.="<tr><td style='text-align:left;'>".$userinfo->display_name."</td><td style='text-align:left;'>".$userinfo->user_email."</td><td style='text-align:left;'><a href='#' class='remove_student_from_roster' id =". $userinfo->ID."_".$groupid .">
	<img src='/wp-content/uploads/2014/08/erase.png' height=18 width=18>Remove ".$userinfo->display_name." from roster</a></td><td style='text-align:left;'>
	<input type='hidden' name='".$userinfo->ID."' id='member_role' value='".$member_role."'>
	<input type='radio' name='".$userinfo->ID."' value='2' >Instructor<input type='radio'name='".$userinfo->ID."' value='3'>Student<input type='radio' name='".$userinfo->ID."' value='4'>Both</td></tr>";
	}
	else if($member->term_order == 0){
	$userinfo = get_userdata( $member->object_id);
        $member_radio_buttons = "";
	$member_role =5;
	
//jquery is used to set radio buttons for roster roles, based off user id and and member role in input type elements
//build data for table for list of roster members
	$content.="<tr><td style='text-align:left;'>".$userinfo->display_name."</td><td style='text-align:left;'>".$userinfo->user_email."</td><td style='text-align:left;'><a href='#' class='remove_student_from_roster' id =". $userinfo->ID."_".$groupid .">
	<img src='/wp-content/uploads/2014/08/erase.png' height=18 width=18>Remove ".$userinfo->display_name." from roster</a></td><td style='text-align:left;'>
	<input type='hidden' name='".$userinfo->ID."' id='member_role' value='".$member_role."'>
	<input type='radio' name='".$userinfo->ID."' value='2' >Faciltator<input type='radio'name='".$userinfo->ID."' value='3'>Student<input type='radio' name='".$userinfo->ID."' value='4'>Both</td></tr>";
	}
   	
}//end for loop for roster member list

$content.="</table><input id='btnassignrostermembers' class='btnassignrostermembers' type = 'submit' value='Save assigned members'</input></form>";
$content .= "<p><br> Roles should be assigned to your roster according to the following:<br>
				<strong>&nbsp&nbsp&nbsp&nbspInstructors </strong> - can access/monitor student progress on modules and surveys.<br>
				<strong>&nbsp&nbsp&nbsp&nbspStudents</strong> - do not have access to view progress in PD hub.<br>
				<strong>&nbsp&nbsp&nbsp&nbspBoth </strong> - are instructors who are working on modules but also can view student progress.<br><br>";
}//end if there are any students on the roster
else{//Show invite members to roster information

$content .= "<p style='color:red;font-weight:bold;' >Oops! No one has joined your roster yet. Invite participants with following the steps below.</p><br>";
}//end else, show invite members 
$content .="<h3>Invite!</h3>";
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
<div>
	<article>
    <?php echo $content;?>
	<br>
	<span style="font-size: 16px;"><strong>Follow these easy steps. </strong></span><br><br>
	<img class="alignnone wp-image-9101 size-full" style="vertical-align: middle;" title="Copy content by clicking in box" src="/wp-content/uploads/2015/01/copyInvite.jpg" alt="copyInvite" width="30" height="30" /> 
	<strong>Step 1.</strong> Click in the box below to select text . Copy it.<br><br>
	<?php $joinMember = "<div id='joinLink'></div>"; ?>
	<textarea id="joinMember" rows="8" onClick="this.select();" cols="90"></textarea>
	</div><!--/.entry-->
	<img class="alignnone wp-image-9100 size-full" style="vertical-align: middle;" title="Paste text into an email message." src="/wp-content/uploads/2015/01/pasteInvite.jpg" alt="pasteInvite" width="24" height="30" /> 
	<strong>Step 2.</strong> Paste the text into an email invitation. If you would like you can add your own text to the email as well as the pasted text.<br class="none" /><br>
	<img class="alignnone wp-image-9099 size-full" style="vertical-align: middle;" title="Edit your message." src="/wp-content/uploads/2015/01/editeInvite.jpg" alt="editeInvite" width="30" height="30" /> 
	<strong>Step 3. </strong>Address email message to those you would like to invite to your roster. <br><br>
	<img class="alignnone wp-image-9102 size-full" style="vertical-align: middle;" title="Send the email." src="/wp-content/uploads/2015/01/sendInvite.jpg" alt="sendInvite" width="30" height="28" /> 
	<strong>Step 4.  </strong>Send the email invitation.<br class="none" />
	<span style="font-size: 16px;"><strong>When the recipients receive the email from you  and click on the join link in the email, they will be added to your roster.</strong></span><br>

	</article>
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 