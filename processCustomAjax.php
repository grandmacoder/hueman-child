<?php
if(!isset($wpdb))
{
    require_once('../../../wp-config.php');
    require_once('../../../wp-load.php');
    require_once('../../../wp-includes/wp-db.php');
}
global $wpdb;
if ($_GET['action'] =='deletetopic'){
wp_delete_post( $_GET['postid'] );	
$deleteassociation = $wpdb->delete( 'wp_postmeta',
                    array( 'meta_key' =>'course_unit_discussion_topics', 'meta_value' => $_GET['postid'] ),
                    array( '%s','%s' ) );
}
//if we are drawing the progress graph
if($_POST['action'] == 'get_course_percentages' ){
$courseid = $_POST['courseid'];
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
$progressbystudent="";
$courseCoachID=$wpdb->get_var($wpdb->prepare("Select coach_id  from wp_wpcw_course_extras where course_id= %d",$courseid)); 
$rows = $wpdb->get_results($wpdb->prepare("select * from wp_wpcw_user_courses where course_id = %d and user_id <> %d and user_id not in (%s) order by course_progress",$courseid,$current_id, $courseCoachID, OBJECT));
if ($wpdb->num_rows <= 0){
$hasdata=0;
$progressbystudent="<h4>No one has enrolled yet for this LERN topic. Check back later to track progress.</h4>";
$returnvars =array(
        "progressbystudent" => $progressbystudent,
		"hasdata" =>$hasdata
    );
//return early
print json_encode($returnvars);
}
else{
$divisor =$wpdb->num_rows;
$parts=5; //the ranges 0-20 20-40 40-60 60-80 80-100
$returnarray=array();
$studentname=array("","","","","");
$numstudentsarray=array('0','0','0','0','0');
$progressarray=array();
$i=0;
foreach ($rows as $row){
//get the users first and last name
$userfirst = get_user_meta($row->user_id,'first_name',true);
$userlast = get_user_meta($row->user_id,'last_name',true);
$progress=$userfirst ." " . $userlast .", " . $row->course_progress ."% complete.<br>";
if ($i==0){
$progressbystudent.="<h4>Percent complete by student</h4>";
}
   if ($row->course_progress < 20)	{
	$numstudentsarray[0]++;
	$studentnamestring0.= $userfirst ." " . $userlast .",";
	if ($numstudentsarray[0] % 4 == 0){$studentnamestring0.= "<br>"; }
    $studentname[0]="";
	$studentname[0]=$studentnamestring0;
    }
	elseif ($row->course_progress >=20 && $row->course_progress < 40)	{
	$numstudentsarray[1]++;
	$studentnamestring1.= $userfirst ." " . $userlast .",";
	if ($numstudentsarray[1] % 4 == 0){$studentnamestring1.= "<br>"; }
	$studentname[1]="";
	$studentname[1]=$studentnamestring1;
	}
	elseif ($row->course_progress >=40 && $row->course_progress < 60)	{
	$numstudentsarray[2]++;
	$studentnamestring2.= $userfirst ." " . $userlast .",";
	if ($numstudentsarray[2] % 4 == 0){$studentnamestring2.= "<br>"; }
	$studentname[2]="";
	$studentname[2]=$studentnamestring2;
	}
	elseif ($row->course_progress >=60 && $row->course_progress < 80)	{
	$numstudentsarray[3]++;
	$studentnamestring3.= $userfirst ." " . $userlast .",";
	if ($numstudentsarray[3] % 4 == 0){$studentnamestring3.= "<br>"; }
	$studentname[3]="";
	$studentname[3]=$studentnamestring3;
	}
	else{
	$numstudentsarray[4]++;
	$studentnamestring4.= $userfirst ." " . $userlast .",";
	if ($numstudentsarray[4] % 4 == 0){$studentnamestring4.= "<br>"; }
	$studentname[4]="";
	$studentname[4]=$studentnamestring4;
	}
	$progressarray[$i]=$progress;
	$progress="";
$i++;
}
for ($i=0; $i < $parts; $i++ ){
$addonarray =array(
        "percent" => $numstudentsarray[$i]/$divisor * 100,
        "names" =>  $studentname[$i],
		"numstudents"=>	$numstudentsarray[$i],
    );
$returnarray[$i]=$addonarray;	
}
for ($i=0; $i < count($progressarray); $i++){
$progressbystudent.=$progressarray[$i];	
}
$returnvars = array(
            "progress" =>$returnarray,
			"progressbystudent" =>$progressbystudent,
		);
print json_encode( $returnvars);
}
}		  
else if ($_POST['action'] == 'addatopic'){
$topic_title = $_POST['topic-title'];
$topic_description = $_POST['topic-description'];	
$course_id=$_POST['course_id'];	
$user_id=$_POST['userid'];	
//create a comment topic post
$post = array( //our wp_insert_post args
		'post_title'	=> $topic_title,
		'post_content'	=> $topic_description,
		'post_status'	=> 'publish',
		'post_type' 	=> 'comment_topic',
		'comment_status' =>'open',
		'post_author' => $user_id,
	);
$new_post_id = wp_insert_post($post);
//get the network post for the course id that was sent in
$networkPostID =$wpdb->get_var("select unit_id from wp_wpcw_units_meta where parent_course_id = ". $course_id ." and unit_id in (select ID from wp_posts where post_title like '%network%')"); 
//set the post meta on the post id that was sent in
add_post_meta($networkPostID, 'course_unit_discussion_topics', $new_post_id); 
if ($new_post_id > 0){$success = $new_post_id; }
else{$success=0;}
$returnvars = array(
			"topic_title" =>$topic_title,
			"topic_description"=>$topic_description,
			"courseid"=>$course_id,
			"userid"=>$user_id,
			"success"=>$success,
		   );
print json_encode($returnvars);
}
else if ($_POST['action'] =='get_associated_topics'){
$courseid = $_POST['courseid'];	
$returnhtml="<p></p>";
//get posts with comments allowed on this course
//get postmeta comment topics on posts that are in this course
$commentpostrows = $wpdb->get_results($wpdb->prepare("select meta_value from wp_postmeta where meta_key='%s' and post_id in (select unit_id from wp_wpcw_units_meta where parent_course_id = %d)",'course_unit_discussion_topics',$courseid,OBJECT));
$i=0;
foreach ($commentpostrows as $row){
	if ($i > 1){
    $post=get_post($row->meta_value);
	$title = get_post_field( 'post_title', $post->ID);
	$content =  get_post_field( 'post_content', $post->ID);
	$returnhtml.='<div id='.$post->ID.'><a href="#" onclick="return deletetopic('.$post->ID.');"><img src="/wp-content/uploads/2014/10/deleteicon.png" height=20 width=20>Remove this topic</a><strong>&nbsp;|&nbsp'.$title .'</strong></a>,  '.$content.'</div>';
    }
	$i++;	
}
$returnvars =array(
        "returnhtml" => $returnhtml,
    );
print json_encode($returnvars);
}
else if ($_POST['action'] =='savetowaitlist'){
$courseid = $_POST['waitlistcourse'];
$email = $_POST['waitlistemail'];
$name = $_POST['waitlistname'];

$rows = $wpdb->insert("wp_wpcw_waitinglist", array(
   "course_id" => $courseid,
   "user_email" => $email,
   "user_name" => $name,
));
$returnvars = array(
            "success" =>$rows,
		);
print json_encode($returnvars);
}
else if ($_POST['action']=='get_course_main_page'){
$courseid = $_POST['courseid'];
$intropagepath=$wpdb->get_var($wpdb->prepare("select course_start_page_path from wp_wpcw_course_extras where course_id = %d",$courseid));
$returnarray=array("intropagepath"=>$intropagepath);
print json_encode($returnarray);	
}
else if ($_POST['action'] =='get_qa_by_student'){
$courseid = $_POST['courseid'];
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
$coachids= $wpdb->get_var($wpdb->prepare("select coach_id from wp_wpcw_course_extras where course_id = %d", $courseid)); 
$aCoachIDs=explode(",",$coachids);
//get the post id with the engage questions on it
$postid = $wpdb->get_var("select unit_id from wp_wpcw_units_meta m, wp_posts p where unit_id = ID and post_title like '%engage%' and post_status= 'publish' and parent_course_id =".$courseid);
//now get the data for the first answers
$q1answers=$wpdb->get_results($wpdb->prepare("select * from wp_course_activities where selfgrade > 0 and post_id = %d and page_order = %d",$postid, 1));
if ($wpdb->num_rows > 0){
$returnhtml="<br><br><table class='basic_table'><th>Student</th><th>Answer</th><th>Self-score</th>";
$i=0;
		foreach ($q1answers as $answer){
		$userid= $answer->user_id;
		  if (!in_array($userid,$aCoachIDs)){
			if ($i==0){$returnhtml.="<tr><td colspan='3' style='width:100%'><strong>".$answer->description."</strong></td></tr>";}
			  $user_firstname = get_user_meta($answer->user_id,'first_name', true ); 
              $user_lastname = get_user_meta( $answer->user_id,'last_name', true ); 
			  //get the avatar
	          $user_avatar=get_user_meta ($answer->user_id, 'wp_user_avatar', true);
	          if ($user_avatar > 0){
	          $avatarpath = get_the_guid($user_avatar);
	          }
	         else{
	         $avatarpath ='http://1.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=32&amp;d=mm&amp;r=g&amp;forcedefault=1';
	         }
			 $selfgradeval =$answer->selfgrade;	
			$student="<tr><td><abbr title='".$user_firstname." ".$user_lastname."' rel='tooltip'><img src='".$avatarpath."' height=30 width=30></abbr><br><span style='font-size: 12px;'>". $user_firstname." ".$user_lastname."</span></td>";
            $answer="<td>". $answer->activity_value ."</td>";
        	
				if ($selfgradeval == 1){$selfgrade="<td style='background-color:rgba(164,179,87,1);'>Mastered</td>";}
						  elseif ($selfgradeval == 2){$selfgrade="<td style='background-color:rgba(241,231,103,1);'>Proficient</td>";}
							   elseif ($selfgradeval == 3){$selfgrade="<td style='background-color:rgba(248,80,50,1);'>Developing</td>";}
							          else{$selfgrade="<td style='background-color:rgba(248,80,50,1);'>None</td>";}

		   $returnhtml.=$student.$answer.$selfgrade."</tr>";
		  $i++;
		  }
		}
$returnhtml.="</table>";
}//we have answers for Q1
else{
$returnhtml.="<p>No one has answered any of the questions yet.</p>";	
}
//get q2 answers
$q2answers=$wpdb->get_results($wpdb->prepare("select * from wp_course_activities where selfgrade > 0 and post_id = %d and page_order = %d",$postid, 2));
if ($wpdb->num_rows > 0){
$returnhtml.="<br><br><table class='basic_table'><th>Student</th><th>Answer</th><th>Self-score</th>";
$i=0;
		foreach ($q2answers as $answer){
		$userid= $answer->user_id;
		  if (!in_array($userid,$aCoachIDs)){
			if ($i==0){$returnhtml.="<tr><td colspan='3' style='width:100%'><strong>".$answer->description."</strong></td></tr>";}
			  $user_firstname = get_user_meta($answer->user_id,'first_name', true ); 
              $user_lastname = get_user_meta( $answer->user_id,'last_name', true ); 
			  //get the avatar
	          $user_avatar=get_user_meta ($answer->user_id, 'wp_user_avatar', true);
	          if ($user_avatar > 0){
	          $avatarpath = get_the_guid($user_avatar);
	          }
	         else{
	         $avatarpath ='http://1.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=32&amp;d=mm&amp;r=g&amp;forcedefault=1';
	         }
			 $selfgradeval =$answer->selfgrade;	
			$student="<tr><td><abbr title='".$user_firstname." ".$user_lastname."' rel='tooltip'><br><span style='font-size: 12px;'>". $user_firstname." ".$user_lastname."</span><img src='".$avatarpath."' height=30 width=30></abbr></td>";
            $answer="<td>". $answer->activity_value ."</td>";
        	
				if ($selfgradeval == 1){$selfgrade="<td style='background-color:rgba(164,179,87,1);'>Mastered</td>";}
						  elseif ($selfgradeval == 2){$selfgrade="<td style='background-color:rgba(241,231,103,1);'>Proficient</td>";}
							   elseif ($selfgradeval == 3){$selfgrade="<td style='background-color:rgba(248,80,50,1);'>Developing</td>";}
							          else{$selfgrade="<td style='background-color:rgba(248,80,50,1);'>None</td>";}

		   $returnhtml.=$student.$answer.$selfgrade."</tr>";
		  $i++;
		  }
		}
$returnhtml.="</table>";
}//we have answers for Q2
$returnvars = array(
            "returnhtml" =>$returnhtml,
		);
print json_encode($returnvars);		
}
else if ($_POST['action'] == 'check_answers_exist')	{
$courseid = $_POST['courseid'];
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
$haveanswers="Yes";
$studentsenrolled="Yes";
$coachids = $wpdb->get_var($wpdb->prepare("Select coach_id from wp_wpcw_course_extras where course_id =%d", $courseid));
$totalStudents=$wpdb->get_var($wpdb->prepare("select count(*) from wp_wpcw_user_courses where course_id = %d and user_id not in (". $coachids.")", $courseid));
$q=$wpdb->last_query;
if ($totalStudents <= 0){
$studentsenrolled="No one has enrolled in this LERN topic yet.<BR>";	
}
$postid = $wpdb->get_var("select unit_id from wp_wpcw_units_meta m, wp_posts p where unit_id = ID and post_title like '%engage%' and post_status= 'publish' and parent_course_id =".$courseid);
//now get the data for the first answers
$numanswers=$wpdb->get_var($wpdb->prepare("select count(*) from wp_course_activities where selfgrade > 0 and post_id = %d and user_id not in not in (". $coachids.")",$postid, $courseid));
$q.=$wpdb->last_query;
if ($numanswers <= 0){
$haveanswers="No one has done the Q/A portion of the topic yet. Check back later to see their progress.<BR>";	
}
$returnvars = array(
            "haveanswers" =>$haveanswers,
			"studentsenrolled"=>$studentsenrolled,
			"q" =>$q,
		
		   );
print json_encode($returnvars);		
}
else if ($_POST['action'] =='get_qa_by_question'){
$courseid = $_POST['courseid'];
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
$aFirstQ=array();
$aSecondQ=array();
$totalStudents=0;
$totalFinished=0;
$q1mastered=0;
$q1proficient=0;
$q1developing=0;
$q2mastered=0;
$q2proficient=0;
$q2developing=0;

$totalStudents=$wpdb->get_var($wpdb->prepare("select count(*) from wp_wpcw_user_courses where course_id = %d", $courseid));
$coachids= $wpdb->get_var($wpdb->prepare("select coach_id from wp_wpcw_course_extras where course_id = %d", $courseid)); 
$aCoachIDs=explode(",",$coachids);
$totalStudents-=count($aCoachIDs);
//show results by question
//get the post id with the engage questions on it
$postid = $wpdb->get_var("select unit_id from wp_wpcw_units_meta m, wp_posts p where unit_id = ID and post_title like '%engage%' and post_status= 'publish' and parent_course_id =".$courseid);
//now get the data for the first answers
$q1answers=$wpdb->get_results($wpdb->prepare("select * from wp_course_activities where selfgrade > 0 and post_id = %d and page_order = %d",$postid, 1));

foreach ($q1answers as $answer){
$userid= $answer->user_id;
if (!in_array($userid,$aCoachIDs)){
if ($answer->selfgrade == 1){$q1mastered++;}
  elseif ($answer->selfgrade == 2){$q1proficient++;}
	   elseif ($answer->selfgrade == 3){$q1developing++;}
$q1=$answer->description;
}
}
//now get the data for the second answer
$q2answers=$wpdb->get_results($wpdb->prepare("select * from wp_course_activities where selfgrade > 0 and post_id = %d and page_order = %d",$postid, 2));

foreach ($q2answers as $answer){
$userid= $answer->user_id;
if (!in_array($userid,$aCoachIDs)){
if ($answer->selfgrade == 1){$q2mastered++;}
  elseif ($answer->selfgrade == 2){$q2proficient++;}
	   elseif ($answer->selfgrade == 3){$q2developing++;}
$q2=$answer->description;
$totalFinished++;
}
}
//return true or false.
$returnvars = array(
            "totalstudents" =>$totalStudents,
			"totalfinished"=>$totalFinished,
			"q1mastered"=>$q1mastered,
			"q1proficient"=>$q1proficient,
			"q1developing"=>$q1developing,
			"q2mastered"=>$q2mastered,
			"q2proficient"=>$q2proficient,
			"q2developing"=>$q2developing,
			"q1" =>$q1,
			"q2" =>$q2,
		   );
print json_encode($returnvars);	
}

//save the registration info and register the user for the course.
else if ($_POST['action'] =='addusertocourse'){
global $wpdb;
$coursekey=$_POST['coursekey'];
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
$courseinfo= $wpdb->get_results($wpdb->prepare("select course_title, course_desc, study_guide_path, course_intro_page_path, c.course_id, coach_id,  start_date from wp_wpcw_courses c, wp_wpcw_course_extras x where c.course_id = x.course_id  and enrollment_key=%s",$coursekey,OBJECT));

foreach ($courseinfo as $info){
//get data for email
$coursetitle= $info->course_title;
$startdate=	substr($info->start_date,0,-9);
$date=date_create($startdate);
$startdate =date_format($date,"M. d, Y");
$coach=$info->coach_id;
$course_id = $info->course_id;
$coursedescription = $info->course_desc;
$study_guide_url= $info->study_guide_path;
$course_intro_page= $info->course_intro_page_path;
}
//get the coach info for the email
$aCoaches=explode(",", $coach);
for ($i=0; $i<count($aCoaches); $i++){
//get each coaches' name and avatar
  $user_firstname = get_user_meta( $aCoaches[$i],'first_name', true ); 
  $user_lastname = get_user_meta( $aCoaches[$i],'last_name', true ); 
  $bio= get_user_meta($aCoaches[$i],'description', true );
  $coachtext= "<strong>".strtoupper($user_firstname." ".$user_lastname)." :</strong> ". $bio ;
  $coachinfo.="<p>".$coachtext ."</p>";
}
//add users answers to usermeta
add_user_meta( $current_id, $course_id."_currentknowledge", $_POST['currentknowledge'], false );
add_user_meta( $current_id, $course_id."_currentwork", $_POST['currentwork'], false );
add_user_meta( $current_id, $course_id."_whoserve", $_POST['whoserve'], false );
//add user to course
$inserted=$wpdb->insert("wp_wpcw_user_courses", array(
   "user_id" => $current_id,
   "course_id" => $course_id,
   "course_progress" => '0',
   "course_final_grade_sent" => '',
));
$htmlemail="You have successfully registered for " .$coursetitle."!<br>";
$htmlemail.="Be sure to mark your calendar so you can jump right in on the start date, " . $startdate ."<br><br>";
$htmlemail.="In the meantime, here is a little bit of information about the coach(es) who will be working with you:";
$htmlemail.=$coachinfo;
$htmlemail.="<br>This email was automatically generated, so do not respond to it.<br> If you need assistance, <a href='mailto:transition@ku.edu'>contact Transition Coalition.</a>.<br><br>See you online,<br>The Transition Coalition Team<br><br><img src='http://www.transitioncoalition.org/wp-content/uploads/2015/11/tcLogo.jpg' alt='Transition Coalition Logo'>";
//email user confirmation
$headers = array('Content-Type: text/html; charset=UTF-8');
wp_mail( $current_user->user_email, "Transition Coalition LERN Registration", $htmlemail, $headers);
$htmlsignup="<p>". $current_user->user_email." just signed up for LERN!</p>";
wp_mail ('dlattin@ku.edu',"Transition Coalition LERN Registration", $htmlsignup, $headers);
//return true or false.
$returnvars = array(
            "success" =>true,
			"studyguide"=>$study_guide_url,
			"intropath"=>$course_intro_page,
			);
print json_encode($returnvars);	
}

//get course and user info for course enrollment/registration
else if($_POST['action'] == 'get_registration_info'){
global $wpdb;
$coursekey=$_POST['coursekey'];
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
$coachtext="";
$coachinfo="";
$uploadavatar=0;
$alreadyregistered=0;
if ($current_id < 1){
$current_id = 0;
}
//get the course information
$courseinfo= $wpdb->get_results($wpdb->prepare("select course_title, course_desc, c.course_id, coach_id, start_date, course_intro_page_path from wp_wpcw_courses c, wp_wpcw_course_extras x where c.course_id = x.course_id  and enrollment_key=%s",$coursekey,OBJECT));

foreach ($courseinfo as $info){
$coursetitle= $info->course_title;
$startdate=	substr($info->start_date,0,-9);
$date=date_create($startdate);
$startdate =date_format($date,"M. d, Y");
$coach=$info->coach_id;
$course_id = $info->course_id;
$coursedescription = $info->course_desc;
$courseintropath=$info->course_intro_page_path;
}
//is user registered already?
$alreadyregistered=$wpdb->get_var($wpdb->prepare("select user_id from wp_wpcw_user_courses where user_id=%d and course_id=%d",$current_id,$course_id, OBJECT));
//get each coaches' name and avatar
$aCoaches=explode(",", $coach);
for ($i=0; $i<count($aCoaches); $i++){
  $user_firstname = get_user_meta( $aCoaches[$i],'first_name', true ); 
  $user_lastname = get_user_meta( $aCoaches[$i],'last_name', true ); 
  $bio= get_user_meta($aCoaches[$i],'description', true );
  $user_avatar_url_id=get_user_meta($aCoaches[$i],'wp_user_avatar', true );
  $avatarPost=get_post($user_avatar_url_id);
  $user_avatar_url=$avatarPost->guid;
  $user_avatar="<img src='". $user_avatar_url . "' height='60' width= '60' align='left' class='img-text-top'>";
  $coachtext= "<strong>".strtoupper($user_firstname." ".$user_lastname)." :</strong> ". $bio ;
  $coachinfo.="<p>".$user_avatar .$coachtext ."</p>";
}
$returnvars = array(
            "coursetitle" =>$coursetitle,
			"startdate"=> $startdate,
			"coachtext"=> $coachinfo,
			//"uploadavatar"=>$uploadavatar,
			"uploadavatar"=>1,
			"currentuser" =>$current_id,
			"courseid" =>$course_id,
			"coursedescription"=>$coursedescription,
			"alreadyregistered"=>$alreadyregistered,
			"courseintropath" => $courseintropath,
          );

print json_encode($returnvars);
}
//subcribe user to bbpress forum
else if($_POST['action'] == 'subscribe_user_to_forum'){
	global $wpdb;
	$user_id = $_POST['user_id'];
	$forum_post_id = $_POST['forum_post_id'];
	$wpdb->query($wpdb->prepare( 
	"   INSERT INTO `bbp_topic_subscribe`
		( `topic_post_id`, `user_id`)
		VALUES ( %d, %d)
	    ", 
        array(
		$forum_post_id, 
		$user_id,
		) 
));	
}
else if($_POST['action'] == 'unsubscribe_user_to_forum'){
	global $wpdb;
	$user_id = $_POST['user_id'];
	$forum_post_id = $_POST['forum_post_id'];
	//$wpdb->query("DELETE FROM `bbp_topic_subscribe` WHERE user_id=".$user_id." AND topic_post_id=".$forum_post_id."");
    $wpdb->delete( 'bbp_topic_subscribe', array( 'user_id' => $user_id, 'topic_post_id'=>$forum_post_id ) );
}

else if($_POST['action'] == 'get_school_districts'){
	global $wpdb;
	$state = $_POST['state'];
	$school_districts = $wpdb->get_results($wpdb->prepare("SELECT name FROM `school_districts` WHERE state = '%s' order by name",$state, OBJECT));
	$school_districts_a = array();
	$count = 0;
	foreach($school_districts as $school_district){
		$school_districts_a[$count] = $school_district->name;
		$count++;
	}
	$returnvars = array(
              "school_districts" => $school_districts_a,
	          );
		print json_encode($returnvars);
}
elseif ($_POST['action'] == 'check_exists_pre_post'){
global $wpdb;
$pageName = $_POST['pageName'];
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
//$q="select unit_id from wp_wpcw_user_progress_quizzes where unit_id in (select ID from wp_posts where post_name ='". $pageName."') and user_id =". $current_id;
//$exists = $wpdb->get_var($q);
$exists=$wpdb->get_var($wpdb->prepare("select unit_id from wp_wpcw_user_progress_quizzes where unit_id in (select ID from wp_posts where post_name ='%s') and user_id =%d" , $pageName,$current_id));
if ($exists > 0){
$returnvars = array(
            "exists" => "1",
          );
}
else{
$returnvars = array(
            "exists" => "0",
          );
}
print json_encode($returnvars);
}
//get the link click
else if ($_POST['action']  =='capture_loggedin_linkclick'){
global $wpdb;
$current_user = wp_get_current_user();
$user_id=$current_user->ID;
$simple_link_id=$_POST['post_id'];
$IP=$_SERVER['REMOTE_ADDR'];
$from_page = $_POST['current_page'];
$user_email=$current_user->user_email;
$user_state= get_user_meta( $user_id, 'state', true ); 
$user_role=get_user_meta( $user_id, 'transition_profile_role', true ); 

$wpdb->query($wpdb->prepare( 
	"
		INSERT INTO `wp_captured_links`
		( `user_id`, `simple_link_id`, `IP`, `from_page`, `user_email`, `user_state`, `user_role`)
		VALUES ( %d, %d, %s,%s,%s, %s,%s)
	    ", 
        array(
		$user_id, 
		$simple_link_id,
		$IP,
		$from_page,
		$user_email,
		$user_state,
		$user_role,) 
));	

$url=get_post_meta($simple_link_id, 'web_address');

echo $from_page;
}
//handle if we are getting the student's course summary
else if ($_POST['action']  =='get_student_course_summary'){
global $wpdb;
$course_ids=$_POST['course_ids'];
$user_id=$_POST['user_id'];
$top =$_POST['top'];
$left=$_POST['left'];	
$sql = $wpdb->prepare("select p.user_id, q.quiz_id,quiz_title, parent_course_id,course_title,
									quiz_correct_questions,quiz_question_total, 
									quiz_completed_date as completed_date, parent_unit_id ,course_progress, 
									quiz_data 
									from wp_wpcw_user_progress_quizzes p, 
									wp_wpcw_quizzes q, 
									wp_wpcw_user_courses c,
									wp_wpcw_courses w 
									where q.quiz_id =p.quiz_id and 
									c.course_id = q.parent_course_id 
									and 
									c.course_id = w.course_id
									AND
									q.parent_course_id = w.course_id
									and c.user_id = p.user_id and parent_course_id in (". $course_ids.")
									and p.user_id =%d and quiz_title NOT LIKE '%s'
									order by c.course_id", $user_id,'%survey%', OBJECT);
//get the content into a string 
$roster_module_report_rows= $wpdb->get_results($sql);
//loop through and make a summary

$tableContent.="<table class=basic_table><tr><th>Course</th><th>% Complete</th><th>Pre-test</th><th>Post Test</th></tr>";	
		foreach ($roster_module_report_rows as $item){
        $studentname=get_usermeta($item->user_id,'first_name',true)." " . get_usermeta($item->user_id,'last_name',true);
		$current_course_id=$item->parent_course_id;
         //pretest was on previous record and progress in 100 so we have a full record
		 if ($item->course_progress >=95 && $pretestInfo <> ""){
		 if($item->completed_date <> ""){
		$date = date_create($item->completed_date);
		$complete_date = date_format($date, "m/d/Y");
		}
		 $tableContent.="<tr><td>". $item->course_title . "</td><td>". $item->course_progress." %</td><td>".$pretestInfo."</td><td>".$item->quiz_correct_questions."/".$item->quiz_question_total."<br>Taken on: ".$complete_date."</td></tr>";		 
		 //reset
		  $pretestInfo="";
		 }
		 else if($item->course_progress >=95 && $pretestInfo == ""){
		 $pretestInfo=$item->quiz_correct_questions."/".$item->quiz_question_total;			
		 }
		//not complete no post test
         else if ($item->course_progress == 0 && $pretestInfo == "" ){
		 $tableContent.="<tr><td>". $item->course_title. "</td><td>". $item->course_progress." %</td><td>No Pre-test</td><td>No Post-test</td></tr>";			 
		 }
		 else if ($item->course_progress < 95 && $item->course_progress > 0 && $pretestInfo == ""){
		 $tableContent.="<tr><td>". $item->course_title . "</td><td>". $item->course_progress." %</td><td>".$item->quiz_correct_questions."/".$item->quiz_question_total."</td><td>No Post-test</td></tr>";		 
		 }
        }
$tableContent.="</table>";
//get the other values into a json return
$q=$wpdb->last_query;
$returnvars = array(
            "summary" => $tableContent,
			"top" => $top,
			"left" =>$left,
			"studentname" =>$studentname,
          );
print json_encode($returnvars);
}
//handle if it is the summary sheet pop up
else if ($_POST['action']  =='get_summary_sheet_data'){
$course_id=$_POST['course_id'];
$user_id=$_POST['user_id'];
//get the course information
 $rows = $wpdb->get_results($wpdb->prepare("select quiz_title, quiz_type, quiz_correct_questions,quiz_question_total,  DATE_FORMAT(quiz_completed_date,'%m-%d-%Y') as completed_date, parent_unit_id, quiz_show_answers
                    from 
                    wp_wpcw_user_progress_quizzes p,
                    wp_wpcw_quizzes q
                    where q.quiz_id =p.quiz_id
                    and (q.parent_course_id =%d || q.former_course_id = %d)
                    and user_id = %d
                    and quiz_title not like '%survey%'", $course_id, $course_id, $user_id,OBJECT));				

$course_title = $wpdb->get_var("SELECT course_title FROM `wp_wpcw_courses` where course_id =".$course_id);

$header .= "<strong>". $course_title ." Summary Sheet </strong><br>";
$i = 0;
foreach ($rows as $quiz){

	if ($i == 0){
				$quiz_summary.="<b>Pre-test:</b> ". $quiz->quiz_correct_questions ."/". $quiz->quiz_question_total;
			}else{
				$quiz_summary.="<br><b>Post-test:</b> ". $quiz->quiz_correct_questions ."/". $quiz->quiz_question_total ." taken on: ".$quiz->completed_date;
			}
		$i++;
}
		    
//get the user information for the header
$user_info = get_userdata($user_id);
$header.=$user_info->first_name ." ". $user_info->last_name ."<br>";
$header.=$user_info->user_email ."<br>";
$header.="State: ". $user_info->state ."<br>";
$header .= $quiz_summary."<br><br>";

$activityRows = $wpdb->get_results($wpdb->prepare("select module_title,activity_id, post_id,activity_value,description from 
					wp_course_activities a, 
					wp_wpcw_user_progress u,
					wp_wpcw_units_meta m, 
					wp_wpcw_modules c
					where 
					u.user_id = a.user_id
					and
					u.unit_id = a.post_id
					and
					u.unit_id = m.unit_id
					and
					a.post_id = m.unit_id
					and
					m.parent_course_id and c.parent_course_id
					and 
					m.parent_module_id = c.module_id
					and 
					page_order > 0 
					and
					a.user_id = ". $user_id ."
					and
					m.unit_id in (Select unit_id from wp_wpcw_units_meta where parent_course_id = %d) order by module_id",$course_id, OBJECT)); 
	  $num_activities = $wpdb->num_rows;
	//get the posts that have checkbox items on them from the matrix table
         $checkboxPosts=$wpdb->get_results('select distinct(post_id) as post_id from wp_course_matrix where   post_id in (Select unit_id from wp_wpcw_units_meta where parent_course_id ='.$courseID.') order by matrix_name', OBJECT);	
	 $num_checkbox_activities = $wpdb->num_rows;
	 if ($num_activities > 0){
	 $module_title="";
	 $prev_module_title="";;
	  foreach ($activityRows as $item){
	   $module_title=$item->module_title;
		if ($module_title <> $prev_module_title){
		$activityContent.="<h4 class = 'summary_sheet_session'>". $module_title."</h4>";
		}
		$activityContent.="<p><strong>Question:</strong>" . $item->description ."<br><span style='margin-left: 30px;'><strong>Response:</strong> " . $item->activity_value."</span></p>";
		$prev_module_title=$module_title;
	}
	}
        else{
       $activityContent.="<p>There were no activities with saved responses for ". $moduleName ."</p>";
	}//end if activities
	//see if there are any checkbox activities
	//get the posts that have checkbox items on them from the matrix table
         $check_box_posts = $wpdb->get_results($wpdb->prepare(
			'select distinct(post_id) as post_id from wp_course_matrix where post_id in (Select unit_id from wp_wpcw_units_meta where parent_course_id =%d) 
			order by matrix_name', $course_id),OBJECT);
	$num_checkbox_activities = sizeof($check_box_posts);
	if ($num_checkbox_activities > 0){
		     foreach($check_box_posts as $cbxpost){
					//get the checkbox items per post and the answers per user
					$userCheckboxSelections = $wpdb->get_row($wpdb->prepare("select * from wp_course_activities where post_id =%d and user_id=%d and description=%s",$cbxpost->post_id, $user_id,''));
					$sSelections = $userCheckboxSelections->activity_value;
					$aSelectedValues =  explode(',',$sSelections);
					//get the check box checked by user when doing the activity module
					$checklistItem = $wpdb->get_results($wpdb->prepare("select * from wp_course_matrix where post_id = %d and matrix_type=%s",$cbxpost->post_id, "checklist", OBJECT));
					                $i=0;
							foreach ($checklistItem as $item){
							if (in_array($i,$aSelectedValues)){$bChecked='checked';}
								else {$bChecked='';
								}
							if ($i == 0){
							$checklistContent.="<hr><h4>Checklist items for " .$item->heading ."</h4>";
							}
							$checklistContent.="<p><input type='checkbox' class='cbo_summary_sheet' id='". $i . "' name='compare' ". $bChecked."/><label id='label_" . $item->item_text . "' for='". $i . "'><span></span>". $item->item_text  ."</label>";
							$i++;
							}
					}
		   }//end if checklists
echo $header. $activityContent . $checklistContent;
}
//handle if the user wants to send a summary of their module to someone via email
elseif ($_POST['action'] == 'send_summary_email'){
$to = $_POST['sendto'];
$htmlBody = $_POST['message'];
$courseid = $_POST['courseid'];
//get the name of the course
$coursename=$wpdb->get_var($wpdb->prepare("Select course_title from wp_wpcw_courses where course_id=%d", $courseid));
//get some user information for the heading of the email
$current_user = wp_get_current_user();
$summaryHeading="<p>Module: ". $coursename . " - test and activity results for: " . $current_user->user_firstname . " " . $current_user->user_lastname."</p><p>Email: " . $current_user->user_email."</p>";
$subject = 'Transition Coalition post test and module summary for ' . $current_user->user_firstname . " " . $current_user->user_lastname ;
$headers = "From: transition@transitioncoalition.org\r\n";
$headers .= "Reply-To:transition@transitioncoalition.org\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message = '<html><body>'.$summaryHeading.stripslashes($htmlBody)."</body></html>";
$bMailSent = mail($to, $subject, $message, $headers);
echo "Success!";
}
//handle changing the users group if they change their state or role
else if ($_POST['action'] == 'setGroup'){
$userID = get_current_user_id();
$userstate = $_POST['userstate'];
$userrole = $_POST['userrole'];
if ($userstate <> ""){
//query for deleting user member from all groups term_taxonomy_id 51=KS, 202=GA, 52=MO, 364=VA and term_id 56=KS, 207=GA, 57=MO, 372=VA
$deleteGroups = $wpdb->query("delete from wp_term_relationships where object_id = ". $userID  ." and term_taxonomy_id in (51,202,52,364)");
//now add relationship for the current state.
//if the state is KS add them to KS group
         if ($userstate == 'KS'){
		wp_set_object_terms($userID, 56, 'user-group',true);
         }		 
//if state is GA add them to GA group
         if ($userstate == 'GA'){
		 wp_set_object_terms($userID, 207, 'user-group',true);
         }
//if the state is MO add them to MO group
         if ($userstate == 'MO'){
		 wp_set_object_terms($userID, 57, 'user-group',true);
         }
		 if ($userstate == 'VA'){
		 wp_set_object_terms($userID, 372, 'user-group',true);
         }
}
else if ($userrole <> ""){
$deleteGroups = $wpdb->query("delete from wp_term_relationships where object_id = ". $userID  ." and term_taxonomy_id =38");
//add faculty university as pd hub members
		if ($userrole == 'College/university faculty or instructor'){
		 wp_set_object_terms($userID, 37, 'user-group',true);
		}
}
$returnvars = array(
            "addedtogroups" => "success",
          );
print json_encode($returnvars);
}

//handle sending the qi email
if ($_GET['action'] == 'sendQIEmail'){
    $to  = $_GET['emailTo'];
    $subject = 'Your requested qi survey';
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html'. "\r\n";
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
//get date of survey, user email, state, district,  and user role
if ($_GET['survey_ref'] =='mostrecent'){
//$q='select max(statistic_ref_id) from wp_wp_pro_quiz_statistic_ref where quiz_id = 32 and user_id = ' . $current_id;
	$statisticRef = $wpdb->get_var($wpdb->prepare('select max(statistic_ref_id) from wp_wp_pro_quiz_statistic_ref where quiz_id = %d and user_id = %d', 32, $current_id));
	}
	else{
    $statisticRef=$_GET['survey_ref'];
	}
$statisticRefInfo = $wpdb->get_results( $wpdb->prepare( 
			"select FROM_UNIXTIME(create_time) as created,user_id  from wp_wp_pro_quiz_statistic_ref where statistic_ref_id=%d
			", 
			$statisticRef),OBJECT);
$htmldisplay="";

foreach ($statisticRefInfo as $qi_info){
$ID = $qi_info->user_id;
$statisticRefDate =  $qi_info->created;
$current_user = get_userdata($qi_info->user_id ); 
$email=$current_user->user_email;
$user_meta = get_user_meta($qi_info->user_id);
}
$htmldisplay.="<p>Email address: " . $email."</p><p>Date: ".$statisticRefDate."</p><p>State: ".$user_meta['state'][0] ."</p>";
if ($user_meta['school_district'][0] <> ""){
$htmldisplay.="<p><strong> District: </strong>".$user_meta['school_district'][0] ."</p>";
}
$htmldisplay.= "</p>Role: ".$user_meta['transition_profile_role'][0] ."</p>";

$aCategoryIDs = array(3,4,5,6,7,8,9);
if ($_GET['survey_ref'] =='mostrecent'){
    $q='select max(statistic_ref_id) from wp_wp_pro_quiz_statistic_ref where quiz_id = 32 and user_id = ' . $current_id;
	$statisticRef = $wpdb->get_var( $q);
	}
	else{
    $statisticRef=$_GET['survey_ref'];
	}
$quiz_id = $wpdb->get_var($wpdb->prepare("Select quiz_id from wp_wp_pro_quiz_statistic_ref where statistic_ref_id = %d ",$statisticRef));
if ($quiz_id == 5){$scorerange ="The highest average for each domain is 3 and the lowest is 0.";}
	else{$scorerange ="The highest average for each domain is 4 and the lowest is 1.";}
$htmldisplay.="<table width=900 border=0><tr><td style='font-size: 18px;font-weight:bold;color:#FFF;'>Explanation of Scores</td></tr>	
<tr><td style='text-align:left;'><P>The score for each domain is an average (mean) of your total responses to each quality indicator statement in that domain.". $scorerange."<br></p>
<li style='list-style-type: circle; margin-left: 20px;'>The higher the overall domain score, the more quality indicators you've achieved in that domain.</li>
<li style='list-style-type: circle; margin-left: 20px;'>The low domain scores are the domains you may want to target for change.</li>
<li style='list-style-type: circle; margin-left: 20px;'>The domain average can help you identify which area of transition might be the most critical for you, your district, or state to begin planning around or making changes.</li>
</td></tr></table>";
	
$averageScores = array();
$totalScore=0;
//select points for each category  and load an array of averages per category
for ($i = 0; $i < count($aCategoryIDs); $i++){
	$q = 'Select s.question_id, s.points, question, q.category_id ,category_name from wp_wp_pro_quiz_statistic s,wp_wp_pro_quiz_question q,wp_wp_pro_quiz_category c where  q.id = s.question_id and c.category_id = q.category_id and statistic_ref_id = '. $statisticRef . '  AND q.category_id = ' .$aCategoryIDs[$i].' order by sort';
	$questions = $wpdb->get_results($q, OBJECT);
    $catScore=0;
    $numQuestions = $wpdb->num_rows;
		foreach ($questions as $question){
		$catScore += $question->points;
		$totalScore += $question->points;
        }
        $currentAverage = round($catScore/$numQuestions,2);
        $averageScores[$i] =  number_format((float)$currentAverage, 2, '.', ''); 
}
//select the questions and output the points and summarize the averages in a table
$q = 'Select s.question_id, s.points, question, q.category_id ,category_name from wp_wp_pro_quiz_statistic s,wp_wp_pro_quiz_question q,wp_wp_pro_quiz_category c where  q.id = s.question_id and c.category_id = q.category_id and statistic_ref_id = '. $statisticRef . '  order by sort, q.category_id';
$questions = $wpdb->get_results($q, OBJECT);
$totalAverage = round($totalScore/$wpdb->num_rows,2);
$htmldisplay.="<div class=rounded_table><table width=900><tr><td style='font-size: 18px; font-weight:bold; color:#FFF;'>QI Summary - overall score: ". $totalAverage."</td><td style='font-size:18px;'>SCORE</td></tr>";
$i =0;
		foreach ($questions as $question){
		$currentCategory =  $question->category_name;
		if ($previousCategory <> $currentCategory){ 
		$htmldisplay .= "<tr><td style='font-size: 18px; font-weight:bold; background-color:#3b8dbd; color:#FFF;'> " . $currentCategory ." Domain Score:</td><td style='font-size:18px; font-weight:bold; background-color:#3b8dbd; color:#FFF;'>". $averageScores[$i]."</td></tr>";
		$i++;
		}
		$htmldisplay .= "<tr><td style='text-align:left;'>". strip_tags($question->question) . "</td><td style='text-align:center; font-size:18px;'>" . $question->points . "</td></tr>";
		$previousCategory = $currentCategory;
		}
$htmldisplay.="</table></div>";
// Mail it
$bMailSent = mail($to, $subject, $htmldisplay, $headers);
echo "Success! Your survey results were sent to the email you requested.";	
}
//handle getting the user's qi surveys
else if ($_GET['action'] == 'get_user_qi_quiz_list'){
$htmldisplay1 ="";
$htmldisplay2="";
$htmlcss="";
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
$bBothVersions=0;
//get the most recent surveys (before 2016-08-15)
$q = 'Select statistic_ref_id,  create_time as createdtime from wp_wp_pro_quiz_statistic_ref where user_id=' . $current_id.' and quiz_id = 32  order by statistic_ref_id desc';
$v2surveys = $wpdb->get_results($q, OBJECT);
$numV2surveys = $wpdb->num_rows;
$q = 'Select statistic_ref_id,  create_time as createdtime from wp_wp_pro_quiz_statistic_ref where user_id=' . $current_id.' and quiz_id = 5 order by statistic_ref_id desc';
$v1surveys = $wpdb->get_results($q, OBJECT);
$numV1surveys = $wpdb->num_rows;
$htmlcss='<style>
a:link {color:#537c1b; font-weight: 500;}
a:visited {color:#537c1b; font-weight: 500;}
a:hover{color:#3b8dbd; font-weight: 500; text-decoration:underline; }
.QItooltip{
   			display: inline;
    		position: relative;
		}
		
.QItooltip:hover:after{
    		background: #333;
    		background: rgba(0,0,0,.8);
    		border-radius: 5px;
    		bottom: 26px;
    		color: #fff;
    		content: attr(title);
    		left: 20%;
    		padding: 5px 15px;
    		position: absolute;
    		z-index: 98;
    		width: 220px;
		}
		
.QItooltip:hover:before{
    		border: solid;
    		border-color: #333 transparent;
    		border-width: 6px 6px 0 6px;
    		bottom: 20px;
    		content: "";
    		left: 50%;
    		position: absolute;
    		z-index: 99;
		}

}</style>';
if ($numV2surveys > 0 && $numV1surveys > 0){
$bBothVersions = 1;	
}
if ($numV2surveys <= 0 && $numV1surveys <= 0){
$htmldisplay1 = "<p>Oops! It looks as though you have not yet completed a QI survey.</br><a href='/qi-survey-introduction'>Take the QI Survey</a> now. </br>You can always return to this page to look up your QI survey results.";
$htmldisplay2="<br>";
}
else{
$htmldisplay1 = "<div style='background-image: linear-gradient(to top left, #FFFFFF 0%, #b9cbf3 200%); width: 80%; height: auto; display: block; text-align: left; float: left; font-size: 14px; line-height: 15px; padding: 15px 15px 15px 15px;'><img class='alignleft' src='http://transitioncoalition.org/wp-content/uploads/site icons/icon-info.png' alt='icon-info' width='55' height='55' />
				We regularly improve the QI-2.  It was last updated on August 15, 2016. Consider taking the QI-2 survey again to stay up-to-date.
				<br><br><button onclick=\"window.location.href='/qi-survey-introduction/'\">Take the QI-2</button></div>
				<div style='clear:both;'></div><br><br>";
    $i=0;
    foreach ($v2surveys as $survey){
	$theTime = get_date_from_gmt( date( 'Y-m-d H:i:s', $survey->createdtime ), 'F j, Y H:i:s');
	if ($i == 0 && $bBothVersions ==1){$htmldisplay2 .= "<h5>QI-2 Score 1-4 (for surveys taken after 8/15/2016)</h5>";	}
	$htmldisplay2 .= "<a  href='/qi-results/?surveyref=".$survey->statistic_ref_id."' title='QI2 survey from ". $theTime."' >".$theTime ."</a><br><br>";
    $i++;
    }
	$i=0;
    foreach ($v1surveys as $survey1){
	$theTime = get_date_from_gmt( date( 'Y-m-d H:i:s', $survey1->createdtime ), 'F j, Y H:i:s');
	if ($i == 0 && $bBothVersions ==1){
	 $htmldisplay2 .= "<h5>QI-2 Score 1-3 (for surveys taken before 8/15/2016)</h5>";	
	}
	if ($bBothVersions == 1){
    $htmldisplay2.="<a class='QItooltip' title='Scores on this QI version should not be compared with your most recent QI2' href='/qi-results/?surveyref=".$survey1->statistic_ref_id."'>".$theTime ."</a><br><br>";
	}
	else{
	 $htmldisplay2 .= "<a  href='/qi-results/?surveyref=".$survey1->statistic_ref_id."' title='QI2 survey from ". $theTime."' >".$theTime ."</a><br><br>";
	}
    $i++;
    }
}
$returnvars = array(
              "htmldisplay1" => $htmldisplay1,
	          "htmldisplay2" => $htmlcss.$htmldisplay2,
	          );
print json_encode($returnvars);
}
//handle the qi survey user summary on the results page
else if ($_GET['action'] == 'get_qi_user_summary'){
if ( !is_numeric($_GET['survey_ref']) && $_GET['survey_ref'] <> 'mostrecent'){
die("sorry invalid input");
} 
if($_GET['qi_user'] <> ""){
$current_id = $_GET['qi_user'];
}else{
$current_user = wp_get_current_user();
$current_id = $current_user->ID;
}
//get date of survey, user email, state, district,  and user role
if ($_GET['survey_ref'] =='mostrecent'){
    $q='select max(statistic_ref_id) from wp_wp_pro_quiz_statistic_ref where quiz_id = 32 and user_id = ' . $current_id;
	$statisticRef = $wpdb->get_var( $q);
	}
	else{
        $statisticRef=$_GET['survey_ref'];
	}
$statisticRefInfo = $wpdb->get_results( $wpdb->prepare( 
			"select FROM_UNIXTIME(create_time) as created,user_id  from wp_wp_pro_quiz_statistic_ref where statistic_ref_id=%d", $statisticRef),OBJECT);
$htmldisplay="";

foreach ($statisticRefInfo as $qi_info){
$ID = $qi_info->user_id;
$statisticRefDate =  $qi_info->created;
$current_user = get_userdata($qi_info->user_id ); 
$email=$current_user->user_email;
$user_meta = get_user_meta($qi_info->user_id);
}
$htmldisplay.="<p id='portfolio_qi_message'></p><p><strong>Email address:</strong>   " . $email."</br><strong>Date:  </strong>".$statisticRefDate."</br><strong>State:  </strong>".$user_meta['state'][0];
if ($user_meta['school_district'][0] <> ""){
$htmldisplay.="</br><strong> District: </strong>".$user_meta['school_district'][0];
}
$htmldisplay.= "</br><strong>Role:  </strong>".$user_meta['transition_profile_role'][0] ."<span style='float:right; margin-right:80px;'><a href=\"javascript:send_qi_email(".$statisticRef.",'". $email."','new');\"><img  src='http://www.transitioncoalition.org/wp-content/uploads/2014/07/email-icon-vector.jpg' height=30  width=40 title='Email me these results'></a>&nbsp;&nbsp;<a href='#' onClick='window.print()'><img src='http://www.transitioncoalition.org/wp-content/uploads/2014/06/printer.png' title='print these results'></a></p>";

$returnvars = array(
              "htmldisplay" => $htmldisplay,
	          "success" => "true",
	          );
print json_encode($returnvars);

}
//handle the qi survey results page
/**********************************************/
else if ($_GET['action'] == 'get_qi_quiz_summary'){
$aCategoryIDs = array(3,4,5,6,7,8,9);
$current_user = wp_get_current_user();
$current_id = $current_user->ID;

	if ($_GET['survey_ref'] =='mostrecent'){
    $q='select max(statistic_ref_id) from wp_wp_pro_quiz_statistic_ref where quiz_id = 32 and user_id = ' . $current_id;
	$statisticRef = $wpdb->get_var( $q);
	}
	else{
    $statisticRef=$_GET['survey_ref'];
	}
	//figure out which heading to show based on the quiz id 5 (v2) or 32 (v 2.1)
$quiz_id = $wpdb->get_var($wpdb->prepare("Select quiz_id from wp_wp_pro_quiz_statistic_ref where statistic_ref_id = %d ",$statisticRef));
if ($quiz_id == 5){$scorerange ="The highest average for each domain is 3 and the lowest is 0.";}
	else{$scorerange ="The highest average for each domain is 4 and the lowest is 1.";}
$headerhtml="<div class='rounded_table'>
<table width=900><tr><td style='font-size: 18px;font-weight:bold;color:#FFF;'>Explanation of Scores</td></tr>	
<tr><td style='text-align:left;'><P>The score for each domain is an average (mean) of your total responses to each quality indicator statement in that domain.". $scorerange."<br></p>
<li style='list-style-type: circle; margin-left: 20px;'>The higher the overall domain score, the more quality indicators you've achieved in that domain.</li>
<li style='list-style-type: circle; margin-left: 20px;'>The low domain scores are the domains you may want to target for change.</li>
<li style='list-style-type: circle; margin-left: 20px;'>The domain average can help you identify which area of transition might be the most critical for you, your district, or state to begin planning around or making changes.</li>
<p><br>You can track your QI-2 Survey results under My Portfolio > My Surveys. This section keeps track of each time you’ve taken the QI-2, your scores each time you take it, and gives you access to print or email your QI-2 scores.</p>
</td></tr></table></div>";
$averageScores = array();
$totalScore=0;
//select points for each category  and load an array of averages per category
for ($i = 0; $i < count($aCategoryIDs); $i++){
	$q = 'Select s.question_id, s.points, question, q.category_id ,category_name from wp_wp_pro_quiz_statistic s,wp_wp_pro_quiz_question q,wp_wp_pro_quiz_category c where  q.id = s.question_id and c.category_id = q.category_id and statistic_ref_id = '. $statisticRef . ' AND q.category_id = ' .$aCategoryIDs[$i].' order by sort';
	$questions = $wpdb->get_results($q, OBJECT);
    $catScore=0;
    $numQuestions = $wpdb->num_rows;
		foreach ($questions as $question){
		$catScore += $question->points;
		$totalScore += $question->points;
        }
        $currentAverage = round($catScore/$numQuestions,2);
        $averageScores[$i] =  number_format((float)$currentAverage, 2, '.', ''); 
}

//select the questions and output the points and summarize the averages in a table
$q = 'Select s.question_id, s.points, question, q.category_id ,category_name from wp_wp_pro_quiz_statistic s,wp_wp_pro_quiz_question q,wp_wp_pro_quiz_category c where  q.id = s.question_id and c.category_id = q.category_id and statistic_ref_id = '. $statisticRef . '   order by sort, q.category_id';
$questions = $wpdb->get_results($q, OBJECT);
$totalAverage = round($totalScore/$wpdb->num_rows,2);
$htmldisplay="<div class=rounded_table><table width=900><tr><td style='font-size: 18px; font-weight:bold; color:#FFF;'>Domain</td><td style='font-size:18px;'>SCORE</td></tr>";
$i =0;
		foreach ($questions as $question){
		$currentCategory =  $question->category_name;
		if ($previousCategory <> $currentCategory){ 
		$htmldisplay .= "<tr><td  style='font-size: 18px; font-weight:bold; background-color:#3b8dbd; color:#FFF;'> " . $currentCategory ." Domain Score:</td><td style='font-size:18px; font-weight:bold; background-color:#3b8dbd; color:#FFF;'>". $averageScores[$i]."</td></tr>";
		$i++;
		}
		$htmldisplay .= "<tr><td style='text-align:left;'><p>". $question->question . "</p></td><td style='text-align:center; font-size:18px;'>" . $question->points . "</td></tr>";
		$previousCategory = $currentCategory;
		}
$htmldisplay.="</table></div>";
$returnvars = array(
              "htmldisplay" => $htmldisplay,
			  "headerdisplay"=>$headerhtml,
	          "success" => "true",
	          );
print json_encode($returnvars);
}
//handle tip form submit on tips page
/**********************************************/
else if($_POST['action']=='searchTips'){
        $tip_state = $_POST['tip_state'];
	$tip_category = $_POST['tip_category'];
	$tip_keyword = $_POST['tip_keyword'];
	$tip_state_where="";
	$tip_from = "";
	$parent_id = "134";
        if (strlen(trim($tip_state)) == 2){
	$tip_from = ", wp_postmeta m  ";
	$tip_state_where=" AND   p.ID = m.post_id AND   m.post_id=r.object_id AND (m.meta_key = 'tip_contact_state_1' || m.meta_key = 'tip_contact_state_2' || m.meta_key = 'tip_contact_state_3') and m.meta_value in ('". $tip_state."')";
	}
	if ($tip_keyword <> ''){
	$tip_keyword_where = " AND (post_content like '%".$tip_keyword."%' OR post_title like '%".$tip_keyword."%') ";
	}
	if($tip_category == ''){  
	$query_category = "SELECT term_id FROM wp_term_taxonomy  WHERE parent = ".$parent_id;
	$categories = $wpdb->get_results($query_category, OBJECT);
	$categoryS = "";
               foreach($categories as $category){
			$categoryS .= $category->term_id.", ";
		}
	
	$categoryStr = substr($categoryS, 0, -2);
	$category_where = " AND x.term_id in (".$categoryStr.") ";	
	}else{
	$category_where = " AND x.term_id in (".$tip_category.") ";
	}
	$queryStr = "Select distinct(ID), post_title, post_content,post_excerpt
	FROM wp_posts p, wp_terms t, wp_term_taxonomy x, wp_term_relationships r ". $tip_from ."
	WHERE t.term_id = x.term_id
	AND x.term_taxonomy_id = r.term_taxonomy_id
	AND p.ID = r.object_id " .$tip_state_where . $category_where." ".$tip_keyword_where." AND post_type = 'tip' AND post_status = 'publish'";
	$tip_posts = $wpdb->get_results($queryStr, OBJECT);
	$num_posts = $wpdb->num_rows;
if ($num_posts > 0){
	foreach ($tip_posts as $post){
						$post_id = $post->ID;
						$average_rating = get_post_meta( $post_id, 'crfp-average-rating', true); 
						$numRatings = tc_reviews_get_number_reviews_per_post($post_id);
						$post_title = $post->post_title;
						$post_url = get_site_url();
						$post_url .= "?p=".$post->ID;
		                                $the_excerpt=$post->post_excerpt;
						
                                                  $s .=  "<article id='post-".$post_id."'>
				    	                <div class='post-inner post-hover'>
				                        <div style='padding-left: 30px; padding-right:50px;'>
				                        <span class='post-title'>        
							<a href='".$post_url."' rel='bookmark' title='".$post_title."'><img src='/wp-content/uploads/2014/06/tip-icon.gif'></a>";
							$s.="<a href='".$post_url."' rel='bookmark' title='".$post_title."'>".$post_title."</a></span>";
							
							if ($average_rating > 0){  
	                                                $s.= " <br>Average Rating: &nbsp;&nbsp;<div class='crfp-rating crfp-rating-".$average_rating."'></div><span style='font-size:12px;'>( " . $numRatings; 
						            if ($numRatings==1){
							    $s.= " rating";
							    }else{
							    $s.= " ratings;";
							    } 
							    $s.= ")&nbsp;&nbsp;</span>";
							
							} 
							else{
							$s.= "<p style='color: #c2132f; font-size: 12px;'>No ratings yet! Be the first to rate this tip!</p>";
							}//end if there are any ratings
							
							$s.="<p>". $the_excerpt."</p>
							</span><!--/.post-title-->        
							</div> <!--end padding-->						
					               </div><!--/.post-inner-->	
				                       </article><!--/.post-->";				 
		  
                       }
}
else{
	$s = 'Sorry, no matches please try different search criteria.';
	}

	$returnvars = array(
	  "content_area" => $s,
	  "result_summary" => "Results:  Found ". $num_posts ." tip(s).",
          );
print json_encode($returnvars);
}
//handle get total tips for tips page when it loads
/**********************************************/
else if($_POST['action']=='load_tips'){
$queryStr = "Select distinct(ID), post_title, post_content,post_excerpt
	FROM wp_posts p, wp_terms t, wp_term_taxonomy x, wp_term_relationships r 
	WHERE t.term_id = x.term_id
	AND x.term_taxonomy_id = r.term_taxonomy_id
	AND p.ID = r.object_id  AND post_type = 'tip' AND post_status = 'publish' order by post_title";
	$tip_posts = $wpdb->get_results($queryStr, OBJECT);
	$num_posts = $wpdb->num_rows;
if ($num_posts > 0){
	foreach ($tip_posts as $post){
						$post_id = $post->ID;
						$average_rating = get_post_meta( $post_id, 'crfp-average-rating', true); 
						$numRatings = tc_reviews_get_number_reviews_per_post($post_id);
						$post_title = $post->post_title;
						$post_url = get_site_url();
						$post_url .= "?p=".$post->ID;
		                                $the_excerpt=$post->post_excerpt;
						
                                                  $s .=  "<article id='post-".$post_id."'>
				    	                <div class='post-inner post-hover'>
				                        <div style='padding-left: 30px; padding-right:50px;'>
				                         <span class='post-title'>     
							<a href='".$post_url."' rel='bookmark' title='".$post_title."'><img  class='alignleft'  src='/wp-content/uploads/2014/06/tip-icon.gif'></a>";
							$s.="<div style='margin-left:40px;'><a href='".$post_url."' rel='bookmark' title='".$post_title."'>".$post_title."</a></span>";
							
							if ($average_rating > 0){  
	                                                $s.= " <br>Average Rating: &nbsp;&nbsp;<div class='crfp-rating crfp-rating-".$average_rating."'></div><span style='font-size:12px;'>( " . $numRatings; 
						            if ($numRatings==1){
							    $s.= " rating";
							    }else{
							    $s.= " ratings;";
							    } 
							    $s.= ")&nbsp;&nbsp;</span>";
							
							} 
							else{
							$s.= "<p style='color: #c2132f; font-size: 12px;'>No ratings yet! Be the first to rate this tip!</p>";
							}//end if there are any ratings
							
							$s.="<p>". $the_excerpt." ... <BR><BR></p></div>
							     
							</div> <!--end padding-->						
					               </div><!--/.post-inner-->	
				                       </article><!--/.post-->";				 
		  
                       }
}
else{
	$s = 'Sorry, no matches please try different search criteria.';
	}

	$returnvars = array(
	  "content_area" => $s ,
	  "result_summary" => "Results:   ". $num_posts ." tip(s).",
          );
print json_encode($returnvars);	  
}

//handle tip form submit on tips page
/**********************************************/
else if ($_POST['action']=='searchAgencies'){
    $agency_disability = $_POST['agency_disability'];
	$agency_service = $_POST['agency_service'];
	$agency_county= $_POST['agency_county'];
	$agency_keyword = $_POST['agency_keyword'];
	$term_taxonomy_id = $_POST['term_taxonomy_id'];
	$agency_disability_where="";
	$postemeta_from = "";
    $postmeta_and="";
    if ($agency_disability <> "" && $agency_disability <> "All"){
	$agency_disability_where=" AND  ID IN (select post_id from wp_postmeta where meta_value like '%".$agency_disability."%')";
	}
	if ($agency_service <> "" && $agency_service<>'All'){
	$agency_service_where="  AND ID IN (select post_id from wp_postmeta where meta_value like '%".$agency_service."%')";
	}
	if ($agency_county <> "" && $agency_county <> 'All'){
	$agency_county_where="  AND ID IN (select post_id from wp_postmeta where meta_value like  '%".$agency_county."%')";
	}
	if ($agency_keyword <> ''){
	$agency_keyword_where = " AND (post_content like '%".$agency_keyword."%' OR post_title like '%".$agency_keyword."%') ";
	}
	
	$queryStr = "Select distinct(ID), post_title, post_content,post_excerpt
	FROM wp_posts p, wp_term_relationships t ". $postemeta_from . " WHERE t.object_id = p.ID " . $postmeta_and . $agency_disability_where ." ". $agency_service_where ." ". $agency_county_where." " . $agency_keyword_where . " AND post_status = 'publish' AND term_taxonomy_id = " . $term_taxonomy_id;
	
	
	$agency_posts = $wpdb->get_results($queryStr, OBJECT);
	$num_posts = $wpdb->num_rows;

if ($num_posts > 0){
foreach ($agency_posts  as $post){
						$post_id = $post->ID;
						$post_title = $post->post_title;
						$post_url = get_site_url();
						$post_url .= "?p=".$post->ID;
		                $the_excerpt=$post->post_excerpt;
						$s .=  "<article id='post-".$post_id."'>
				    	                <div class='post-inner post-hover'>
				                        <div style='padding-left: 30px; padding-right:50px;'>
										<img src='/wp-content/uploads/2015/09/misssouri-agency.png' style='width:50px;height:50px;vertical-align:middle'>
				                        <span class='post-title'>        
							            <a href='".$post_url."' rel='bookmark' title='".$post_title."'></a></span>";
							            $s.="<a href='".$post_url."' rel='bookmark' title='".$post_title."'>".$post_title."</a></span>";
					                    $s.="<p>". $the_excerpt."</p>
							</span><!--/.post-title-->        
							</div> <!--end padding-->						
					       </div><!--/.post-inner-->	
				           </article><!--/.post-->";				 
		  
                       }
}
else{
	$s = 'Sorry, no matches please try different search criteria.';
	}

	$returnvars = array(
	  "content_area" => $s,
	  "result_summary" => "Results:  Found ". $num_posts ." agencies.",
          );
print json_encode($returnvars);
}
else if($_POST['action']=='load_agencies'){
	$agency_posts = $wpdb->get_results($wpdb->prepare("Select distinct(ID), post_title, post_content,post_excerpt FROM wp_posts p,wp_term_relationships r 
	                                  WHERE  p.ID = r.object_id  AND term_taxonomy_id = %d AND post_status = '%s' order by post_title",393,'publish', OBJECT));
	$num_posts = $wpdb->num_rows;
if ($num_posts > 0){
	foreach ($agency_posts as $post){
						$post_id = $post->ID;
						$post_title = $post->post_title;
						$post_url = get_site_url();
						$post_url .= "?p=".$post->ID;
		                                $the_excerpt=$post->post_excerpt;
						              $s .=  "<article id='post-".$post_id."'>
				    	                <div class='post-inner post-hover'>
				                        <div style='padding-left: 30px; padding-right:50px;'>
										<img src='/wp-content/uploads/2015/09/misssouri-agency.png' style='width:50px;height:50px;vertical-align:middle'>
				                        <span class='post-title'>        
							            <a href='".$post_url."' rel='bookmark' title='".$post_title."'></a></span>";
							            $s.="<a href='".$post_url."' rel='bookmark' title='".$post_title."'>".$post_title."</a></span>";
					                    $s.="<p>". $the_excerpt."</p>
							     
							</div> <!--end padding-->						
					               </div><!--/.post-inner-->	
				                       </article><!--/.post-->";				 
		  
                       }
}
else{
	$s = 'Sorry, no matches please try different search criteria.';
	}

	$returnvars = array(
	  "content_area" => $s .$queryStr ,
	  "result_summary" => "Results:   ". $num_posts ." agencies.",
          );
print json_encode($returnvars);	  
}
//handle getting the users state to set the school district on the user profile form
/**********************************************/
else if($_POST['action'] == 'get_district_state'){ 
$current_user = wp_get_current_user();
$current_user_state = $current_user->state;
$mo_school_district = get_user_meta($current_user->ID, 'mo_school_district', true);
$ks_school_district = get_user_meta($current_user->ID, 'ks_school_district', true);
$ga_school_district = get_user_meta($current_user->ID, 'ga_school_district', true);

		if($mo_school_district <> ""){
		$school_district = $mo_school_district;
		}
		if($ks_school_district <> ""){
		$school_district = $ks_school_district;
		}
		if($ga_school_district <> ""){
		$school_district = $ga_school_district;
		}

$returnvars = array(
	  "userid" => $current_user->ID,
	  "userstate" => $current_user_state,
	  "schooldistrict" => $school_district,
      );
print json_encode($returnvars);
}
//handle the qi agreement when a user creates a qi survey
/**********************************************/
else if ($_POST['action'] == 'set_qi_agree_meta'){
$current_user = wp_get_current_user();
update_user_meta($current_user->ID, 'qi_survey_permission', '1');
echo "success";
}
?>