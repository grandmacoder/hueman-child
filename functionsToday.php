<?php
/* ------------------------------------------------------------------------- *
 *  Custom functions
/* ------------------------------------------------------------------------- */
global $legacymigrationpw;
$legacymigrationpw = 'PMDEKkBGLBRWVEsinTo5';
//hooks for theme-my-login plugin profile-form.php
//add_action('user_profile_update_errors', 'tml_profile_errors');
add_action( 'personal_options_update', 'tml_profile_update');
add_action( 'edit_user_profile_update', 'tml_profile_update');
//add_action('show_user_profile', 'tml_add_custom_fields');
add_action('edit_user_profile', 'tml_add_custom_fields');
/*theme my login profile edit/update additional fields*/
function tml_add_custom_fields($user){
global $wpdb;
$state_list = $wpdb->get_results($wpdb->prepare("SELECT state, abbreviation FROM states ORDER BY state_id", OBJECT));
$edit_user_id = $_GET['user_id'];
if($edit_user_id > 0){
	$user_id = $edit_user_id;
	}else{
 $user_id = get_current_user_id();
 }
 $geographic_a = array("Urban", "Suburban", "Rural", "Remote");
 $user_area = get_user_meta($user_id, 'geographic_area', true);
 $user_state = get_user_meta($user_id, 'state', true);
 $ks_school_district = get_user_meta($user_id, 'ks_school_district', true);
 $mo_school_district = get_user_meta($user_id, 'mo_school_district', true);
 $ga_school_district = get_user_meta($user_id, 'ga_school_district', true);
 $user_role = get_user_meta($user_id, 'transition_profile_role', true);
 
 foreach ($geographic_a as $geo){
 if($geo == $user_area){
	$geo_option .= "<input type='radio' name='radio_area' value='".$geo."'  checked='checked'> ".$geo."<br>";
 }else{
	$geo_option .= "<input type='radio' name='radio_area' value='".$geo."'> ".$geo."<br>";
	}
 }
foreach($state_list as $state){
	if( $user_state == $state->abbreviation){
		$state_list_str .= "<option value='".$state->abbreviation."' selected>".$state->state."</option>";
	}else{
	$state_list_str .= "<option value='".$state->abbreviation."'>".$state->state."</option>";
	}
}
$mo_school_district_list = $wpdb->get_results($wpdb->prepare("SELECT name FROM school_districts where state = 'MO' ORDER BY name", OBJECT));

foreach($mo_school_district_list as $mo_district){
	if($mo_school_district == $mo_district->name){
		$mo_district_str .= "<option value='".$mo_district->name."' selected>".$mo_district->name."</option>";
	}else{
		$mo_district_str .= "<option value='".$mo_district->name."'>".$mo_district->name."</option>";
	}
}
$ks_school_district_list = $wpdb->get_results($wpdb->prepare("SELECT name FROM `school_districts` where state = 'KS' ORDER BY name", OBJECT));

foreach($ks_school_district_list as $ks_district){
if( $ks_school_district == $ks_district->name){
	$ks_district_str .= "<option value='".$ks_district->name."' selected >".$ks_district->name."</option>";
	}else{
	$ks_district_str .= "<option value='".$ks_district->name."' >".$ks_district->name."</option>";
	}
}
$ga_school_district_list = $wpdb->get_results($wpdb->prepare("SELECT name FROM `school_districts` where state = 'GA' ORDER BY name", OBJECT));
foreach($ga_school_district_list as $ga_district){
if($ga_school_district == $ga_district->name){
	$ga_district_str .= "<option value='".$ga_district->name."' selected >".$ga_district->name."</option>";
}else{
	$ga_district_str .= "<option value='".$ga_district->name."'>".$ga_district->name."</option>";
}
}
$tc_roles = array("State Ed. Agency Rep.", "General Ed. Teacher", "Special Ed. Teacher", "Vocational/Transition Coordinator", "School Administrator", "School Related Services", 
					"State VR Agency Rep.", "Local VR Agency Rep.", "Technical Assistance Provider", "Center for Independent Living Rep.", "Other Community Agency Rep.", "Parent/Guardian",
					"Youth with a Disability", "College/University Student", "College/University Faculty/Instructor", "Other State Agency Rep.", "Other");
foreach ($tc_roles as $tc_role){
	if($user_role == $tc_role){
		$role_options .= "<option value='".$tc_role."' selected>".$tc_role."</option>";
	}else{
	$role_options .= "<option value='".$tc_role."'>".$tc_role."</option>";
	}
}
?>
<h3><?php _e("Extra profile information", "blank"); ?></h3>
<table class="form-table">
<tr>
<th><label class="tc_role" for="role">Role:</label></th>
	<td><select name="tc_role" id="tc_role"  tabindex="2">
			<option value="">Please Select</option>
			<?php echo $role_options ?>
                         </select> 
	</td>
	</tr>
	<tr>
	<th>
	<label class="user_state_register" for="user_state">State: </label></th>
	<td>
	<select name="user_select_state" id="user_select_state"  tabindex="2">
			<option value="">Please Select</option>
			<?php echo $state_list_str ?>
                         </select> 
						 </td></tr>
	<tr>
	<th>
	<label class="mo-school-district" for="mo-school-district">
    Missouri School District </label></th>
	<td><select name="mo_school_district" id="mo-school-district" class="mo-school-district" tabindex="2">
		<option value="">Please Select</option>
                <?php echo $mo_district_str;?>
                </select> 
	</td>
	</tr>
	<tr>
	<th>
	<label class="ks-school-district" for="ks-school-district">
    Kansas School District </label></th>
	<td><select name="ks_school_district" id="ks-school-district" class="ks-school-district" tabindex="2">
		     <option value="">Please Select</option>
                    <?php echo $ks_district_str;?>
                </select> 
				</td></tr>
	<tr>
	<th>
	<label class="ga-school-district" for="ga-school-district">
    Georgia School District</label></th>
	<td>
	<select name="ga_school_district" id="ga-school-district" class="ga-school-district" tabindex="2">
		<option value="">Please Select</option>
                    <?php echo $ga_district_str;?>
                </select>  
	</td></tr>
	</table>
<?php

}
function tml_profile_errors($data){
	if ( empty( $_POST['email'] ) ){
		$errors->add( 'empty_email_address', __('Please enter your email address.') );
		}
	if ( empty( $_POST['user_first_name'] ) ){
		$errors->add( 'empty_first_name', __('Please enter your first name.') );
		}
	if ( empty( $_POST['user_last_name'] ) ){
		$errors->add( 'empty_last_name', __('Please enter your last name.') );
		}
	if ( $_POST['user_select_state'] == "" ){
		$errors->add( 'empty_user_state', __('Please enter your state.') );
		}
	if ( $_POST['mo_school_district'] == "" && $_POST['user_select_state'] == "MO"){
		$errors->add( 'empty_school_district', __('Please enter your school district.') );
		}
	if ( $_POST['ks_school_district'] == "" &&  $_POST['user_select_state'] == "KS"){
		$errors->add( 'empty_school_district', __('Please enter your school district.') );
		}
	if ( $_POST['ga_school_district'] == "" && $_POST['user_select_state'] == "GA"){
		$errors->add( 'empty_school_district', __('Please enter your school district.') );
		}
		return $errors;
}
function tml_profile_update( $user_id ) {
	global $wpdb;
	
	$edit_user_id = $_POST['user_id']; //on edit profile page for admin
	if($edit_user_id > 0){
		$user_id = $edit_user_id;
	}

	if ( !empty( $_POST['user_first_name'] ) ){
		$firstname =  $_POST['user_first_name'];
		$lastname = $_POST['user_last_name'];
		update_user_meta( $user_id, 'first_name', $firstname);
		update_user_meta($user_id, 'nickname', $firstname);
		wp_update_user(array('ID'=>$user_id, 'user_nicename'=>$firstname." ".$lastname, 'display_name'=>$firstname." ".$lastname));
		}
	if ( !empty( $_POST['user_last_name'] ) ){
		$firstname =  $_POST['user_first_name'];
		$lastname = $_POST['user_last_name'];
		update_user_meta( $user_id, 'last_name', $lastname);
		wp_update_user(array('ID'=>$user_id, 'user_nicename'=>$firstname." ".$lastname, 'display_name'=>$firstname." ".$lastname));
		}
	if ( !empty( $_POST['user_select_state'] ) ){
		update_user_meta( $user_id, 'state', $_POST['user_select_state'] );
		}
	if ( !empty( $_POST['ga_school_district'] ) ){
		update_user_meta( $user_id, 'ga_school_district', $_POST['ga_school_district'] );
		}
	if ( !empty($_POST['mo_school_district'] ) ){
		update_user_meta( $user_id, 'mo_school_district', $_POST['mo_school_district'] );
		}
	if ( !empty( $_POST['ks_school_district'] ) ){
		update_user_meta( $user_id, 'ks_school_district', $_POST['ks_school_district'] );
		}
	if ( !empty( $_POST['radio_area'] ) ){
		update_user_meta( $user_id, 'geographic_area', $_POST['radio_area'] );
		}
	if ( !empty( $_POST['tc_role'] ) ){
		$tc_role =  $_POST['tc_role'];
		update_user_meta( $user_id, 'transition_profile_role', $tc_role);
		if ($tc_role == 'College/University Faculty/Instructor'){
		 update_user_meta($user_id, 'transition_trainer', 1);
		 wp_set_object_terms($user_id, 37, 'user-group',true);
         }
		}
	if ( !empty( $_POST['email'] ) ){
		$email = $_POST['email'];
		wp_update_user(array('ID'=>$user_id, 'user_email'=>$email));
		$wpdb->update($wpdb->users, array('user_login' => $email), array('ID' => $user_id));
		}
}

    //before showing posts if the post is restricted by group, make sure the user can view it
    add_action( 'pre_get_posts', 'setup_and_restrict', 1);  
     //change wp_mail from address
    add_filter( 'wp_mail_from', 'my_mail_from' );
    add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
    
    //add_action( 'init', 'redirect_visitors' );
    //only show the admin bar to admins, editors, authors
    if ( current_user_can('publish_posts') ) {
     add_filter( 'show_admin_bar', '__return_true' ); 	
    }
//enqueue the theme script
//wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'jquery' ),'', true );
//enqueue tc custom js
wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/custom_tcscript.js', array( 'jquery' ),'', true );

$wp_profile = admin_url().'js/user-profile.js';
$wp_admin_psm_js = admin_url().'js/password-strength-meter.js';
wp_register_script("pass-strength-meter", $wp_admin_psm_js, array("password-strength-meter"), false);
wp_register_script("user-profile", $wp_profile, array("user-profile"), false);
wp_enqueue_script('password-strength-meter');
wp_enqueue_script('user-profile');

//filter on userlogin---if pw was set in migration script, redirect to the change password page

add_filter( 'authenticate', 'tc_auth_signon', 30, 3 );
function tc_auth_signon( $user, $username, $password) {
global $legacymigrationpw;
$userinfo = get_user_by( 'email', $username);
if ($userinfo){
     if ($userinfo->user_pass == $legacymigrationpw){
     header("Location: lostpassword/?pwchange=1");
     exit();
    }
    else{
    return $user;
    }
}
else{
return $user;
}
}

///filters that change the to and from name on emails sent with wp_mail
function my_mail_from( $email ){
	return "transition@transitioncoalition.org";
}
function my_mail_from_name( $name ){
    return "Transition Coalition";
}
//Formats date for favorites list
function favoriteformatDate($date){
		$formatD = strtotime($date);
		$my_format = date("m/d/y", $formatD);
		return $my_format;
}

//Returns an icon for type of link for favorite
function linkIcon($the_link){
	$link_style="";
	$endStr=substr($the_link,(strlen($the_link)-5), strlen($the_link));
		if(strpos($endStr,'doc')){

			$link_style="doc-ico";

		}elseif(strpos($endStr,'pdf')){
				$link_style="pdf-ico";
				
			}elseif(strpos($endStr,'pps')){
				$link_style="pps-ico";

		}else{
			$link_style="web-ico";
		}
	return $link_style;
}
/* Custom actions-------------------------------------*/

/*redirect the user if the resource is assigned to a specific group and the user is not in that group */
function setup_and_restrict($query){
global $wpdb;
if ($query->is_main_query()) {
        $post_name = $query->query['name'];
	if ($post_name ==""){
	//it is a page
	$post_name  = $query->query['pagename'];
	}
	$thepost = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_name = '".$post_name."' and  (post_status='publish' || post_status='private')");
        $ID = $thepost->ID;
	if ($ID == 0  || $ID ==""){
	return;
	}
	$postAuthor = $thepost->post_author;
	$current_user = wp_get_current_user();
        $current_userID =  $current_user->ID;
	
        $aStored_group_posts= array();
      //if there is an id find groups that are associated with it redirect users who do not have access to this item
      $aStored_group_posts = get_post_meta( $ID, 'user-group-content' );
      if (count($aStored_group_posts) >  0 ){
      $group_user_ids = get_objects_in_term( $aStored_group_posts, 'user-group');
     
              if (in_array($current_userID , $group_user_ids) || $current_userID == $postAuthor || is_super_admin($current_userID) ){
		return;
		}
		else{
	        wp_redirect("/content-restricted/");
		exit; 
		}
	}//end if there is group post meta associated with the content we are loading	
	else{
        return;
	}
}
return;
}
/*---------------------------------------
Custom shortcodes
----------------------------------------*/
/*add_shortcode( 'isloggedin', 'isloggedin_check_shortcode' );
//if the user is logged in content is visible, otherwise a message is displayed.
function isloggedin_check_shortcode( $atts, $content = null ) {
global $wpdb;
$userID = get_current_user_id();
$survey_id =$wpdb->get_var("select statistic_ref_id from wp_wp_pro_quiz_statistic_ref where user_id = " . $userID . " and quiz_id = 7"); 
   if ( $userID > 0  && !is_null( $content ) && !is_feed() && $survey_id <= 0){
	//if the user has not completed the demographic survey but is logged in send them to it.
	header("Location: /new-user-survey/");
	exit();
	}
	elseif ( is_user_logged_in() && !is_null( $content ) && !is_feed() ){
	return $content;
	}
	return "You must be logged in to access this page. Use the login form on the left side of the screen to login or create a new account. Thank you.";
}
*/

add_filter( 'nav_menu_link_attributes', 'contact_link', 10, 3 );
	function contact_link ($atts, $item, $args ) {
	    if ($item->ID == 9759) {
	               $atts['onClick'] = 'usernoise.window.show()';
	               }
	    // $items .= '<li> <a  href="javascript: usernoise.window.show();">Contact</a></li>';
return $atts;	 
	}
add_filter( 'wp_nav_menu_items', 'login_register', 10, 2 );
	function login_register ( $items, $args ) {
	$userID = get_current_user_id();
	$logoutLink = get_site_url()."/logout/";
	$logoutLink = add_query_arg( '_wpnonce', wp_create_nonce( 'log-out' ), $logoutLink);
	

	    if ( $args->theme_location == 'topbar'  && $userID <=0 )  {
	    $items .= '<li id="register_right" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item"><a href="'. get_bloginfo('url').'/register-3" class="login_register_href">Create account</a></li>';
		$items .= '<li id="login_right" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item"> <a href="'. get_bloginfo('url').'/login">Login</a></li>';
		
	    }
	    elseif ( $args->theme_location == 'topbar'  && $userID > 0 )  {
	    $items .= '<li id="logout_right" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item"><a href="'. $logoutLink.'">Logout</a></li>';
	    }
	    return $items;
	}

//add category to create edit assessment review in order to preselect the category
function ws_preselect_post_category_by_type() {
        ?>
        <script type="text/javascript">
        jQuery(function() {
	    var current_page = jQuery(location).attr('href');
	        if (current_page.indexOf('post_type=assessment_review') > -1){
                jQuery('#in-category-48').click();
		}
		if (current_page.indexOf('post_type=tc_materials') > -1){
		var catId = 331;        jQuery('#in-category-331').click();
		}
		if (current_page.indexOf('post_type=webinar') > -1){
                jQuery('#in-category-341').click();
		}
		if (current_page.indexOf('post_type=tip') > -1){
                jQuery('#in-category-134').click();
		}
        });
	 </script>
         <?php
}
//deselct the comment checked box
function ws_uncheck_comments_by_default() {
?>
        <script type="text/javascript">
         jQuery(function() {
	    var current_page = jQuery(location).attr('href');
		if (current_page.indexOf('post_type=assessment_review') > -1 || current_page.indexOf('post_type=tip') ){
		jQuery('#comment_status').attr('checked', true);
		}
	    else if (current_page.indexOf('post-new') > -1 || current_page.indexOf('post_type=course_unit') > -1 || current_page.indexOf('post_type=page') > -1){
		jQuery('#comment_status').attr('checked', false);
        }
            });
	</script>
        <?php
}
add_action('admin_footer-post-new.php', 'ws_preselect_post_category_by_type');
add_action('admin_footer-post-new.php', 'ws_uncheck_comments_by_default');

function filter_search($query) {
if (!is_admin())	{
    if ($query->is_search) {
	 $catquery = array(
        array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => array( 260 ),
            'operator'=> 'NOT IN'
        )
    );
    $query->set( 'tax_query', $catquery );
    $query->set('post_type', array('post', 'simple_link','assessment_review','tip','webinar'));
	
    };
    return $query;
}
};
add_filter('pre_get_posts', 'filter_search');

//when a user is created set the view admin bars to false
function set_user_admin_bar_false_by_default($user_id) {
    update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
    update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );
}
add_action("user_register", "set_user_admin_bar_false_by_default", 10, 1);

//when admin goes to single user edit show the users course progress -- alter wp_courseware plugin
function my_courseware_show_progress_admin_edit($user) {
$courseData = WPCW_users_getUserCourseList($user->ID);
	    	if ($courseData)
	    	{
	    		foreach ($courseData as $courseDataItem) {
	    			$course_progress .= WPCW_stats_convertPercentageToBar($courseDataItem->course_progress, $courseDataItem->course_title);
	    		}
	    	} 
	    	
	    	// No courses
	    	else {
	    	$course_progress = "This user cannot access any courses";
	    	}
			//get the actions
			$courseActions = sprintf('<span><a href="%s&user_id=%d" class="button-primary">%s</a></span>',
	    		admin_url('users.php?page=WPCW_showPage_UserProgess'), 
	    		$user->ID,
	    		__('View Detailed Progress', 'wp_courseware')
	    	);
	    	$courseActions .= "<div style='clear:both;'>";
	    	// View the full progress of the user.
	    	$courseActions.= sprintf('<span><a href="%s&user_id=%d" class="button-secondary">%s</a></span>',
	    		admin_url('users.php?page=WPCW_showPage_UserCourseAccess'), 
	    		$user->ID,
	    		__('Update Course Access Permissions', 'wp_courseware')
	    	);
	    	$courseActions .= "<div style='clear:both;'>";

	    	// Allow the user progress to be reset 
	    	$courseData = WPCW_users_getUserCourseList($user->ID);
	    	$courseIDList = array();
	    	if (!empty($courseData)) 
	    	{
	    		// Construct a simple list of IDs that we can use for filtering.
	    		foreach ($courseData as $courseDetails)
	    		{
	    			$courseIDList[] = $courseDetails->course_id;
	    		}
	    	}
	    	
	    	// Construct the mini form for resetting the user progress.
	    	$courseActions .= '<span>';
	    	
	    		$courseActions .= '<form method="get">';
	    	
	    		// Fake the array of user IDs, and just add this user's ID.
	    	$courseActions.= sprintf('<input type="hidden" name="users[]" value="%d" >', $user->ID);
	    		
	    		// The dropdown for this.
	    		$courseActions .= WPCW_courses_getCourseResetDropdown(
	    				'wpcw_user_progress_reset_point_single', 
	    				$courseIDList, 
	    				__('No associated courses.', 'wp_courseware'),  
	    				__('Reset this user to beginning of...', 'wp_courseware'), 
	    				'', 
	    				'wpcw_user_progress_reset_select wpcw_user_progress_reset_point_single'
	    			);
	    		
	    		$courseActions .= '</form>';
	    	$courseActions .= '</span>';
$UserCourseDetailsTable ="<h3 id='user-course-details'>User Course Details</h3>
		<table class='form-table' >
		<tr>
			<th>Course Progress</th><th>Course Actions</th></tr>

			<tr><td>".$course_progress."</td><td>". $courseActions."</td></tr></table>";
			
			
echo $UserCourseDetailsTable;
}
add_action('edit_user_profile','my_courseware_show_progress_admin_edit');

/* reinstate the non multisite capabilities to administrators so they can edit users */
function tc_admin_users_caps( $caps, $cap, $user_id, $args ){
 
    foreach( $caps as $key => $capability ){
 
        if( $capability != 'do_not_allow' )
            continue;
 
        switch( $cap ) {
            case 'edit_user':
            case 'edit_users':
                $caps[$key] = 'edit_users';
                break;
            case 'delete_user':
            case 'delete_users':
                $caps[$key] = 'delete_users';
                break;
            case 'create_users':
                $caps[$key] = $cap;
                break;
        }
    }
 
    return $caps;
}
add_filter( 'map_meta_cap', 'tc_admin_users_caps', 1, 4 );
remove_all_filters( 'enable_edit_any_user_configuration' );
add_filter( 'enable_edit_any_user_configuration', '__return_true');
 
/**
 * Checks that both the editing user and the user being edited are
 * members of the blog and prevents the super admin being edited.
 */
function tc_edit_permission_check() {
    global $current_user, $profileuser;
 
    $screen = get_current_screen();
 
    get_currentuserinfo();
 
    if( ! is_super_admin( $current_user->ID ) && in_array( $screen->base, array( 'user-edit', 'user-edit-network' ) ) ) { // editing a user profile
        if ( is_super_admin( $profileuser->ID ) ) { // trying to edit a superadmin while less than a superadmin
            wp_die( __( 'You do not have permission to edit this user.' ) );
        } elseif ( ! ( is_user_member_of_blog( $profileuser->ID, get_current_blog_id() ) && is_user_member_of_blog( $current_user->ID, get_current_blog_id() ) )) { // editing user and edited user aren't members of the same blog
            wp_die( __( 'You do not have permission to edit this user.' ) );
        }
    }
 
}
add_filter( 'admin_head', 'tc_edit_permission_check', 1, 4 );
//extend the remove user function
//when administrator removes a user set their status to deleted
//user can only be deleted by super admin in Network administration - these show up red in the user listing
//the deleted flag is handled on Theme my login wp_authenticate()
function tc_custom_remove_user_from_blog( $user_id ) {
global $wpdb;
$updated = $wpdb->query("Update wp_users set deleted=1 where ID=" . $user_id);  
}
add_action( 'remove_user_from_blog', 'tc_custom_remove_user_from_blog' );

//extend the search to handle an auto populated value
add_action( 'pre_user_query', 'tc_autocomplete_pre_user_search'  );
    function tc_autocomplete_pre_user_search( $query ) {
        if ($query->query_vars[ 'search' ] <> ""){
			$searchTerm = $query->query_vars[ 'search' ];
			$searchTerm=str_replace("*", "", $searchTerm );
			$searchTerm=trim($searchTerm);
			$searchTermName=substr($searchTerm,0, strpos($searchTerm,"("));
			$searchTermEmail = substr ($searchTerm, strpos($searchTerm,"(")+1,  -1);
			$query->query_where = " WHERE 1=1 AND (user_email LIKE '%" . $searchTermEmail ."%') OR (display_name like '%".$searchTermName ."%') AND (wp_usermeta.meta_key = 'wp_capabilities' )";
		}
    }
    
//add exerpt to pages
add_action( 'edit_page_form', 'tc_add_box');
add_action('init', 'tc_init');

function tc_init() {
	if(function_exists("add_post_type_support")) //support 3.1 and greater
	{
		add_post_type_support( 'page', 'excerpt' );
		add_post_type_support( 'tc_materials', 'excerpt' );
		add_post_type_support( 'webinars', 'excerpt' );
		add_post_type_support( 'assessment_review', 'excerpt' );
	}
}

function tc_page_excerpt_meta_box($post) {
?>
<label class="hidden" for="excerpt"><?php _e('Excerpt') ?></label><textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt"><?php echo $post->post_excerpt ?></textarea>
<p><?php _e('Excerpts are optional hand-crafted summaries of your content.'); ?></p>
<?php
}

function tc_add_box()
{
	if(!function_exists("add_post_type_support")) //legacy
	{
	add_meta_box('postexcerpt', __('Page Excerpt'), 'tc_page_excerpt_meta_box', 'page', 'advanced', 'core');
	}
}

/*  Theme setup
/* ------------------------------------ */
if ( ! function_exists( 'alx_setup' ) ) {
	
	function alx_setup() {	
		// Enable automatic feed links
		add_theme_support( 'automatic-feed-links' );
		
		// Enable featured image
		add_theme_support( 'post-thumbnails' );
		
		// Enable post format support
		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'status' ) );
		
		// Declare WooCommerce support
		add_theme_support( 'woocommerce' );
		
		// Thumbnail sizes
		add_image_size( 'thumb-small', 50, 50, true );
		add_image_size( 'thumb-related', 220, 125, true );
		add_image_size( 'thumb-medium', 520, 245, true );
		add_image_size( 'thumb-large', 720, 340, true );

		// Custom menu areas
		register_nav_menus( array(
			'topbar' => 'Topbar',
			'header' => 'Header',
			'footer' => 'Footer',
		) );
	}
	
}
add_action( 'after_setup_theme', 'alx_setup' );

//Overwrite from Hueman Theme

/*  Related posts
/* ------------------------------------ */
if ( ! function_exists( 'alx_related_posts' ) ) {

	function alx_related_posts() {
		wp_reset_postdata();
		global $post;
		// Define shared post arguments
		$args = array(
			'no_found_rows'			=> true,
			'update_post_meta_cache'	=> false,
			'update_post_term_cache'	=> false,
			'ignore_sticky_posts'		=> 1,
			'orderby'			=> 'rand',
			'post__not_in'			=> array($post->ID),
			'posts_per_page'		=> 3
		);
	
		// Related by categories
		if ( ot_get_option('related-posts') == 'categories' ) {
			$catsForThisID = wp_get_post_terms( $post->ID, 'category' ); 
			foreach ($catsForThisID as $cat){
			$sCats .= $cat->term_id  . ",";
			$sCats .= $cat->parent. ",";
			}
			$cats = substr($sCats,0,-1);
			$args['cat'] = $cats;
		
		}
		// Related by tags
		if ( ot_get_option('related-posts') == 'tags' ) {
		
			$tags = get_post_meta($post->ID, 'related-tag', true);
			
			if ( !$tags ) {
				$tags = wp_get_post_tags($post->ID, array('fields'=>'ids'));
				$args['tag__in'] = $tags;
			} else {
				$args['tag_slug__in'] = explode(',', $tags);
			}
			if ( !$tags ) { $break = true; }
		}
		$query = !isset($break)?new WP_Query($args):new WP_Query;
	        return $query;
	}
	
}
/*---------------------
use logic to show or not show a TC custom menu in a widget
----------------------*/
if (include('custom_widgets.php')){
add_action("widgets_init", "load_custom_widgets");
}
function load_custom_widgets() {
unregister_widget("WP_Nav_Menu_Widget");
register_widget("TC_Nav_Menu_Widget");
}

