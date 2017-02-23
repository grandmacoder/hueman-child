<?php
if(!isset($wpdb))
{
    require_once('../../../../wp-config.php');
    require_once('../../../../wp-load.php');
    require_once('../../../../wp-includes/wp-db.php');
	require_once('../../../../wp-content/plugins/wp-courseware/wp-courseware.php');
}
global $wpdb;
$current_user =wp_get_current_user(); 
$userID = $current_user->ID;
$current_page = $_GET['currentpage'];
$current_page=substr_replace($current_page, "", -1);
$pos = strripos ( $current_page , '/');
$slug=substr($current_page, $pos+1, strlen($current_page));
$posts = get_posts( array( 
    'name' => $slug, 
    'post_type' => 'course_unit',
    'post_status' => 'publish',
    'posts_per_page' => 1
) );
if($_GET['action'] == 'getBestAnswers'){
foreach ($posts as $post){
$ID = $post->ID;
//get the text for the answer from the metadata
$postmeta= get_post_meta($ID);
$suggestedAns1 = $postmeta['pop_up_text_content_1'][0];
$suggestedAns2 = $postmeta['pop_up_text_content_2'][0];
}
$returnvars = array(
            "suggestedAns1" => $suggestedAns1,
			"suggestedAns2" => $suggestedAns2,
          );
print json_encode($returnvars);
}

elseif($_GET['action'] =='getUserAnswers'){
global $wpdb;
//$userID
$useranswerarray=array();
$userscorearray=array();
$finished = 0;
foreach ($posts as $post){
$ID = $post->ID;
}
$rows = $wpdb->get_results("Select * from wp_course_activities where post_id =" . $ID ." and user_id =". $userID . " order by page_order", OBJECT);
if ($wpdb->num_rows > 0){
$finished = 1;
$i=0;
	foreach ($rows as $row)	{
	$useranswerarray[$i] = $row->activity_value;
	$userscorearray[$i] = $row->selfgrade;
	$i++;
	}	
}
$returnvars = array(
            "useranswerarray" => $useranswerarray,
			"userselfscore" => $userscorearray,
			"finished" => $finished,
		   );
print json_encode($returnvars);	
}
elseif ($_GET['action'] == 'getAnswers'){
$answerarray=array();
$totalquestions = $_GET['totalquestions'];
foreach ($posts as $post){
$ID = $post->ID;
$postmeta= get_post_meta($ID);
//get the text for the answer rubric from the metadata
for ($i=1; $i<=$totalquestions; $i++){
$bestAns=$postmeta['answer_'.$i.'_best'][0];
$okAns = $postmeta['answer_'.$i.'_ok'][0];
$poorAns= $postmeta['answer_'.$i.'_poor'][0];
$answerarray[$i-1] = array($poorAns,$okAns,$bestAns);	
}
}//end foreach post
$returnvars = array(
            "answerarray" => $answerarray,
			"totalquestions" => $totalquestions,
		   );
print json_encode($returnvars);	
}
elseif ($_GET['action']=='saveAnswers'){
foreach ($posts as $post){
global $wpdb;
$ID = $post->ID;
}
$numInserted=0;
$answers = json_decode(stripslashes($_GET['answers']), true);
$questions = json_decode(stripslashes($_GET['questions']), true);
$selfgrades = json_decode(stripslashes($_GET['selfgrades']), true);
for ($i=0;$i < $_GET['numquestions']; $i++){
$page_order= $i+1;
$now= new DateTime();
//if ($selfgrades[$i] == 1) {$selfgrade =3;}
	//elseif ($selfgrades[$i] == 3){$selfgrade =1;}
	//	else{ $selfgrade =$selfgrades[$i]; }
$selfgrade =$selfgrades[$i];
$insert=$wpdb->prepare("INSERT INTO `wp_course_activities` (`post_id`, `user_id`, `page_order`,`activity_value`, `entry_dt`, `updated_dt`, `description`, `selfgrade`) values (%d, %d,%d, %s, %s, %s, %s, %d)",$ID,  $userID, $page_order, $answers[$i], $now->format('Y-m-d H:i:s'), $now->format('Y-m-d H:i:s'),$questions[$i], $selfgrade);
$inserted=$wpdb->query($insert);
$numInserted=$wpdb->last_query;
if ($inserted){$numInserted++;}

}
//automatically complete the course unit.
$insertcompleted=$wpdb->prepare("INSERT INTO `wp_wpcw_user_progress` (`user_id`, `unit_id`, `unit_completed_date`, `unit_completed_status`) VALUES (%d,%d,%s,%s)", $userID,$ID, $now->format('Y-m-d H:i:s'), 'complete');
$inserted=$wpdb->query($insertcompleted);

$returnvars = array(
            "numinserted" => $numInserted,
		   );
print json_encode($returnvars);	
}
elseif ($_GET['action'] == 'getQuestions'){
$totalquestions = 0;
foreach ($posts as $post){
$ID = $post->ID;
//get the text for the questions from the metadata
$postmeta= get_post_meta($ID);
$q1 =$postmeta['q1'][0];
	if ($q1<> ""){$totalquestions++;}
$q2 =$postmeta['q2'][0];
	if ($q2 <> ""){$totalquestions++;}
$q3 =$postmeta['q3'][0];
	if ($q3 <> ""){$totalquestions++;}
$q4 =$postmeta['q4'][0];
	if ($q4 <> ""){$totalquestions++;}
}
$returnvars = array(
            "q1" => $q1,
			"q2" => $q2,
			"q3" => $q3,
			"q4" => $q4,
			"totalquestions" => $totalquestions,
		   );
print json_encode($returnvars);	
}

elseif($_GET['action'] =='getreflectqprogress'){
global $wpdb;
$aUserdata=array();
$courseid=$_GET['courseid'];
//get the course unit that the questions are on
//get the questions from the post
$QA= $wpdb->get_results("select user_id, description, activity_value,page_order from wp_course_activities where post_id in (select unit_id from wp_wpcw_units_meta where parent_course_id = ". $courseid .") 
and description <> '". $courseid."' and selfgrade is null order by page_order", OBJECT);
$prevorder=1;
$numrows = $wpdb->num_rows;
$i=1;
foreach ($QA as $item){
$userdata=get_userdata($item->user_id);
$user_email = $userdata->user_email;
$userInfo ="<strong><a class='dashboardlink' href='mailto:". $user_email ."'>Contact ".$userdata->first_name. " " . $userdata->last_name ." </strong></a><br>";
$order =  $item->page_order;
//if order <> prev order, q and answer paragraph to the array before reloading variables
if ($order <> $prevorder || $i == $numrows){
$ar=array('q'=> $q ,'a'=>$sQA);
array_push($aUserdata,$ar);
//reset answer string
$sQA ="";
//keep the last value to add on at the end
$sLastQA = $sQA ;
}
$sQA.= $userInfo . $userdata->first_name. "'s Answer: ";
$q = "<strong>Reflective Q.: " . $item->description ."</strong>";
$sQA.= " " . $item->activity_value."<br><br>";
$prevorder= $order;
$i++;
}
$ar=array('q'=> $q ,'a'=>$sQA);
array_push($aUserdata,$ar);
print json_encode($aUserdata);	
}
elseif ($_GET['action'] == 'getqaprogress'){
global $wpdb;
$aUserdata=array();
$courseid=$_GET['courseid'];
//get the course unit that the questions are on
//get the questions from the post
$unitid = $wpdb->get_var("select post_id from wp_postmeta m, wp_wpcw_units_meta c where c.unit_id = m.post_id and meta_key='q1' and meta_value <> '' and unit_id in (select unit_id from wp_wpcw_units_meta where parent_course_id = ". $courseid .")");
$QA= $wpdb->get_results("select user_id, page_order, description, selfgrade, activity_value from wp_course_activities where post_id=". $unitid ." and user_id in (select user_id from wp_wpcw_user_courses where course_id =". $courseid .") order by page_order, selfgrade", OBJECT);
$prevorder=1;
$numrows = $wpdb->num_rows;
$i=1;
foreach ($QA as $item){
$userdata=get_userdata($item->user_id);

$user_email = $userdata->user_email;
$userInfo ="<strong><a class='dashboardlink' href='mailto:". $user_email ."'>Contact ".$userdata->first_name. " " . $userdata->last_name ." </strong></a><br>";
$order =  $item->page_order;
//if order <> prev order, q and answer paragraph to the array before reloading variables
if ($order <> $prevorder || $i == $numrows){
$ar=array('q'=> $q ,'a'=>$sQA);
array_push($aUserdata,$ar);
//reset answer string
$sQA ="";
}
$sQA.= $userInfo . $userdata->first_name. "'s Answer: ";
$q = $item->description;
	   if ($item->selfgrade == 1){$sQA.= "<span class='questionmastered'>Mastered</span>";   }
	     else if ($item->selfgrade == 2){$sQA.= "<span class='questionimprovement'>Proficient</span>";   }
			 else {$sQA.= "<span class='questionstruggling'>Emerging</span>";   }
$sQA.= " " . $item->activity_value."<br><br>";
$prevorder= $order;
$i++;
}
print json_encode($aUserdata);	
}

elseif($_GET['action'] =='getstudentprofiles'){
global $wpdb;
$aUserdata=array();
$courseid=$_GET['courseid'];
//get the course_id from the postID
$coach_id = $wpdb->get_var("select coach_id from wp_wpcw_course_extras where course_id=".$courseid); 
$users = $wpdb->get_results("select user_id from wp_wpcw_user_courses where user_id not in (". $coach_id .")  and course_id =" . $courseid, OBJECT);

foreach ($users as $user){
//get the avatar and the tiny bio from usermeta
  $user_firstname = get_user_meta( $user->user_id,'first_name', true );   
  $user_lastname = get_user_meta( $user->user_id,'last_name', true ); 
  $user_avatar=get_avatar($user->user_id, 25);
  $state = get_user_meta( $user->user_id,'state', true );
  $PQ1=get_user_meta( $user->user_id, $courseid.'_PQ1', true );
  if ($PQ1==""){$PQ1="N/A";}
  $PQ2=get_user_meta( $user->user_id, $courseid.'_PQ2', true );
   if ($PQ2==""){$PQ2="N/A";}
  $tc_role = get_user_meta( $user->user_id,'transition_profile_role', true );
      if ($tc_role ==""){$tc_role="Other";}
 $usertext= "<strong>".$user_firstname. " " . $user_lastname ."</strong> from " . $state .", Transition role: " . $tc_role."<BR><strong>Personal goal for learning this topic: </strong>". $PQ1."<BR><strong>Personal knowledge of this topic:</strong>". $PQ2;
   $ar=array('userprofile'=> $usertext, 'avatar'=>$user_avatar);
  //create an array and push it onto the userdata array  
   array_push($aUserdata,$ar);
}
//encode json array and return it
echo json_encode($aUserdata);
}