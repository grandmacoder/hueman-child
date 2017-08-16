<?php
/*
Template Name: Basic LERN list
*/
/**
 * shows the modules that a user has access to. If not logged in just shows the national modules
 *
 * @package WordPress
 */

/* Displays customize output of links for a category.
*/
/* Get roster if there are any if not show the create roster form
*/
get_header();
?>
<style>
.avatar_img{
text-align:top;
height:50px;
width:auto;
margin-right: 12px;
}
ul.circle {list-style-type: circle; padding-left: 30px;}
a:link  {color:#537c1b; font-weight: 500;}
a:visited {color:#537c1b; font-weight: 500;}
a:hover {color:#3b8dbd; font-weight: 500; text-decoration:underline; }
</style>
<?php
	global $wpdb;
         $userID = get_current_user_id();
         $courseData = tc_get_lern_course_data();
        
         if ($courseData){
		  $i=1;
	     foreach ($courseData as $courseDataItem) {   //if it is the module id passed in and there has been some progress
		 $registerlink="";
		     //get the course extra fields
			 $start_date = $courseDataItem->start_date;
		     $start_timestamp =strtotime($start_date);
			 $max_enrolled = $courseDataItem->max_enrolled;
			 //if the current time is before the startdate add the registration link
			 $course_id = $courseDataItem->course_id;
			 $num_enrolled = tc_get_lern_course_enrollment($course_id);
			 $coach_id = $courseDataItem->coach_id;
			 //split the coach info
			 $aCoachIDs=explode(",",$coach_id);
			 for ($k =0; $k< count($aCoachIDs); $k++){
			 $user_firstname = get_user_meta(  $aCoachIDs[$k],'first_name', true );   
			 $user_lastname = get_user_meta(   $aCoachIDs[$k],'last_name', true );
			 $user_avatar=get_user_meta (  $aCoachIDs[$k], 'wp_user_avatar', true);
			 $userdesc=get_user_meta( $aCoachIDs[$k], 'description', true);
			 $userrole = get_user_meta( $aCoachIDs[$k],'transition_profile_role', true);

			if ($user_avatar > 0){
				$avatar_post=get_post($user_avatar);
				 $avatar_path = $avatar_post->guid;
				}
				else{
				$avatar_path = 'http://1.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=32&amp;d=mm&amp;r=g&amp;forcedefault=1';
			    }
			$coachcontent.="<p style='padding-right:30px;'><img class='avatar_img' src='". $avatar_path."'><strong>Coach ". $user_firstname ." " . $user_lastname ." - " . $userrole. "</strong><br>". $userdesc ."</p><div style='padding-bottom: 15px;'></div>";
			 }
			 //otherwise state that this course is already in progress 
			 if ($courseDataItem->course_logo_path <> ""){
			 $courseLogo="<img src='". $courseDataItem->course_logo_path."'>";
			 }
			 if ($courseDataItem->course_desc <> ""){
			 //get the first part of the course description before the :
			 $courseDesc = 	 $courseDataItem->course_desc;
			 }
			 if ($courseDataItem->course_title <> ""){
				$end=strpos($start_date,' ');
				$short_date = substr($start_date,0,$end);
				$shorttimestamp = new DateTime($short_date);
				$short_date_formatted = date_format($shorttimestamp, 'F j, Y');
			    $course_title = "<strong>". $courseDataItem->course_title ."</strong>";
			 }
			 if ($courseDataItem->study_guide_path <> ""){
			  $intro_guide_path="<strong><a class='fancybox-iframe' href='". $courseDataItem->study_guide_path ."' title='Overview'>More information</a></strong>";
			 }
			 if (time() <  $start_timestamp ){ //check enrollment waitlist or enroll
				 if ($num_enrolled >= $max_enrolled){
				 $waitlistlink ="The course is currently full.  <a id='join-waiting-list' data-courseid='". $course_id ."'  href=#>Please join the waiting list.</a>"; 
                 $courseList.="<div class='content-column one_fourth'><div style='padding-right: 12px;'>".$courseLogo ."<hr style='height: 10px; color:#3b8dbd; border: 0; box-shadow: inset 0 12px 12px -12px rgba(59, 141, 189, 1); width: 90%;' /><strong><span style='color:#3b8dbd; font-size:25px; padding-right: 5px;'><i class='fa fa-sign-in'></i></span>". $waitlistlink."</strong><br><span style='color:#3b8dbd; font-size:25px; padding-right: 5px;'><i class='fa fa-info-circle'></i></span>". $intro_guide_path."</div></div><div class='content-column three_fourth last_column'>" . $course_title."<p>" .  $courseDesc ."<br>This LERN starts on " . $short_date_formatted."</p><br>" .$coachcontent."<hr></div>";				 
				 }
				 elseif ($num_enrolled < $max_enrolled){
				 $registerlink ="<a href='/tc-registration/?coursekey=". $courseDataItem->enrollment_key ."'>Register Now!</a>"; 
				 $courseList.="<div class='content-column one_fourth'><div style='padding-right: 12px;'>".$courseLogo ."<hr style='height: 10px; color:#3b8dbd; border: 0; box-shadow: inset 0 12px 12px -12px rgba(59, 141, 189, 1); width: 90%;' /><strong><span style='color:#3b8dbd; font-size:25px; padding-right: 5px;'><i class='fa fa-sign-in'></i></span>". $registerlink."</strong><br><span style='color:#3b8dbd; font-size:25px; padding-right: 5px;'><i class='fa fa-info-circle'></i></span>". $intro_guide_path."</div></div><div class='content-column three_fourth last_column'>" . $course_title."<p>" .  $courseDesc ."<br> This LERN starts on " . $short_date_formatted."</p><br>" .$coachcontent."<hr></div>";
				 }
			}
			elseif (time() > strtotime('+14 day', $start_timestamp)){  //course is over and not being offered 
			$notavailablelink ="<strong>This course is currently not being offered.</strong>";	 
			$courseList.="<div class='content-column one_fourth'><div style='padding-right: 12px;'>".$courseLogo ."</div></div><div class='content-column three_fourth last_column'>" . $course_title."<p>" .  $courseDesc ."<br><strong><span style='color:#3b8dbd; font-size:25px; padding-right: 5px;'><i class='fa fa-sign-in'></i></span>". $notavailablelink."</strong><br></div>";
			}
			else{  //course is in progress
				 $inprogresslink ="<strong>This course is currently in progress.</strong>";	 
				 $courseList.="<div class='content-column one_fourth'><div style='padding-right: 12px;'>".$courseLogo ."<hr style='height: 10px;  border: 0; box-shadow: inset 0 12px 12px -12px rgba(59, 141, 189, 1); width: 90%;' /><strong><span style='color:#3b8dbd; font-size:25px; padding-right: 5px;'><i class='fa fa-sign-in'></i></span>". $inprogresslink."</strong><br><span style='color:#3b8dbd; font-size:25px; padding-right: 5px;'><i class='fa fa-info-circle'></i></span>". $intro_guide_path."</div></div><div class='content-column three_fourth last_column'>" . $course_title."<p>" .  $courseDesc ."<br> This LERN starts on " . $short_date_formatted."</p><br>" .$coachcontent."<hr></div>";
			 }			
			
			}
     	 $i++;

		 }

		 
?>
<section class="content">
<h3><span>LERN Opportunities</span></h3>
<br>
<?php the_content(); 
echo "<br>";
echo  $courseList;
?>
<style>
#waitlist-form{display:none;}
</style>
<div id='waitlist-form'>
<div id="addtowaitinglist">
<div id='validatearea'>All fields are required.</div>
<form id="waitlistform" >
     <fieldset>
	  <label for="emailaddress">Email address</label><br>
      <input type="text" name="waitlistemail" id="waitlistemail" value="" size=40><br>
      <label for="waitlistname">Your Name</label><br>
      <input  name="waitlistname" id="waitlistname" value="" size=40><br>
	  <input type= hidden name="waitlistcourse" id="waitlistcourse" value="" ><br>
      <input type="button" id="submitwaitlist" value="Add to wait list"><br>
      </fieldset>
  </form>
</div>
</div>
</section><!--/.content-->
			
<?php get_sidebar(); ?>
<?php get_footer(); ?> 

