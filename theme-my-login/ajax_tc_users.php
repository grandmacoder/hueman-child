<?php
if(!isset($wpdb))
{
	require_once('../../../../wp-config.php');
    require_once('../../../../wp-load.php');
    require_once('../../../../wp-includes/wp-db.php');
	require_once('../../../plugins/wp-user-avatar/includes/class-wp-user-avatar.php');
}

if($_POST['action'] == 'edit_profile_email'){
	global $wpdb;
	$user_email = $_POST['user_email'];
	$user_id = $_POST['user_id'];
	$check_email = $wpdb->get_var("SELECT count(*) FROM `wp_users` WHERE user_email = '". $user_email . "' AND ID <> '". $user_id ."'");
	if($check_email > 0){
		echo $wpdb->last_query;
	}else{
		echo "true";
	}
}else if($_POST['action'] == 'register_email'){
	global $wpdb;
	$user_email = $_POST['user_email'];
	$check_email = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM `wp_users` WHERE user_email = '%s'", $user_email));
	if($check_email > 0){
		echo "false";
	}else{
		echo "true";
	}
}else if ($_POST['action'] == 'create_user'){
	$userstate=$_POST['user_select_state'];
	$school_district=$_POST['school_district'];
	$tc_role=$_POST['role'];
	$firstname=$_POST['first_name'];
	$lastname=$_POST['last_name'];
	$password=$_POST['pass1'];  
	$email_address = $_POST['user_email'];
	$collegetraining = $_POST['collegetraining'];
	$page_referer = $_POST['page_referer'];
	$gender = $_POST['gender'];
	$race = $_POST['race'];
	$geographic_area = $_POST['area'];
	$user_id_created = 0;
	$user_id_created = create_a_user( $email_address,$password, $userstate,$school_district,
									$tc_role, $firstname, $lastname, $collegetraining, $gender, $race, $geographic_area);
if($user_id_created > 0){
$creds = array();
$creds['user_login'] =  $email_address;
$creds['user_password'] = $password;
$creds['remember'] = true;
$user = wp_signon( $creds, false );
$cookieset = "no";
if(isset($_COOKIE['new_roster_member'])){						
	$redirectURL = "/blog/joinroster/?".$_COOKIE['new_roster_member'];
	$cookieset = "yes";
			
	}
elseif (isset($_COOKIE['new_course_member'])){
$redirectURL = $_COOKIE['new_course_member'];
//returning the entire url
$cookieset = "no";	
}	
else{
$redirectURL = $page_referer;
}
$returnvars = array(
            "redirectURL" => $redirectURL,
			"cookieset" =>$cookieset,
			"createduser" => "true",
          );

}else{
$returnvars = array(
            "createduser" => "false",
          );
}

print json_encode($returnvars);
}
function create_a_user($email_address, $password,$userstate,$school_district,$tc_role,$firstname, 
					   $lastname, $collegetraining, $gender, $race, $geographic_area){
	global $wpdb;
	$aMoModuleIDsIn = array(29,17,18,19,21);
	$aMoModuleIDsOut = array(4,12,15,10);
	$capFirst = ucfirst($firstname);
	$lastInit = ucfirst($lastname[0]);
    if( null == username_exists( $email_address ) ) {
       // Generate the password and create the user
      // $password = wp_generate_password( 12, false );
	  //create the user
       $user_id = wp_create_user( $email_address, $password, $email_address );
      // $updated= $wpdb->query("update wp_users set user_nicename='". $capFirst ." " . $lastInit."',display_name='" . $firstname ." " . $lastname."' where ID=". $user_id);
		$updated = $wpdb->update ( 
			'wp_users', 
			array( 
				'user_nicename' =>  $capFirst ." " . $lastInit,	
				'display_name' => $firstname ." " . $lastname	
			), 
			array( 'ID' => $user_id ), 
			array( 
				'%s','%s'
			), 
			array( '%d' ) 
		);
		//insert into user meta
	     update_user_meta($user_id, 'first_name', $firstname);
		 update_user_meta($user_id, 'last_name', $lastname);
		 update_user_meta($user_id, 'nickname', $firstname);
		 update_user_meta($user_id, 'state', $userstate);
		 update_user_meta($user_id, 'transition_profile_role', $tc_role);
		 update_user_meta($user_id, 'agreement_terms', 1);
		 update_user_meta($user_id, 'gender', $gender);
		 update_user_meta($user_id, 'race', $race);
		 if($geographic_area <> ""){
		 update_user_meta($user_id, 'geographic_area', $geographic_area);
		 }
		 update_user_meta($user_id, 'primary_blog', 1);
		
		//update usermeta states school districts
		 update_user_meta($user_id, 'school_district', $school_district);
		 
		 
         //if the role is College/university faculty or instructor then add them to PD hub
	     if ($tc_role == 'College/University Faculty/Instructor' || $collegetraining == 'y'){
		 update_user_meta($user_id, 'transition_trainer', 1);
		 wp_set_object_terms($user_id, 37, 'user-group',true);
         }
         //if the state is KS add them to KS group
         if ($userstate == 'KS'){
		 wp_set_object_terms($user_id, 56, 'user-group',true);
         }		 
		 //if state is GA add them to GA group
         if ($userstate == 'GA'){
		 wp_set_object_terms($user_id, 207, 'user-group',true);
         }
		 //if the state is MO add them to MO group
         if ($userstate == 'MO'){
		 wp_set_object_terms($user_id, 57, 'user-group',true);
		    //enroll user in MO modules
			 for ($i =0; $i<=count($aMoModuleIDsIn); $i++){
		      $wpdb->insert( 
	         'wp_wpcw_user_courses', 
	         array( 
				'user_id' => $user_id, 
				'course_id' => $aMoModuleIDsIn[$i],
				'course_progress' => 0,
				'course_final_grade_sent' =>'',
			), 
				array( 
					'%d', 
					'%d',
					'%d',
					'%s',		
				) 
			);
			}
			//delete the user from the national modules
			for ($i=0; $i<count($aMoModuleIDsOut); $i++){
			$wpdb->delete( 'wp_wpcw_user_courses', array( 'user_id' => $user_id, 'course_id'=>$aMoModuleIDsOut[$i],'course_progress'=>0), array( '%d','%d','%d' ) );
			}
		 }//end if the user is from MO

	// Set the role
		 $user = new WP_User( $user_id );
		 $user->set_role( 'subscriber' );
        
      // Email the user
	  $emailer =  Theme_My_Login_Custom_Email::get_object();
	  $emailer->new_user_notification( $user_id, $password) ;
	
	//check for cookie set for new_roster_member
		  
}else{
	$user_id = 0;
} // end if
return $user_id;
}
?>