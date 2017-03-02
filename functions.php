<?php
/* ------------------------------------------------------------------------- *
 *  Custom functions
/* ------------------------------------------------------------------------- */
/**
 * Adds a box to the main column on the Post and Page edit screens.

function myplugin_add_meta_box() {

	    add_meta_box(
			'myplugin_sectionid',
			__( 'My Post Section Title', 'wp_courseware' ),
			'myplugin_meta_box_callback', 
			'page', 'side'
		);
		
		
	
}
add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );
 */
/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function myplugin_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'myplugin_meta_box', 'myplugin_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

	echo '<label for="myplugin_new_field">';
	_e( 'Description for this field', 'myplugin_textdomain' );
	echo '</label> ';
	echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}
//****************************************************************************************************Login and Profile ************************************************************************************************
//handle jquery login page***************************************
//AJAX Login scripts *********************************************
add_action( 'wp_enqueue_scripts', 'load_ajax_login_script', 999 );
add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
function load_ajax_login_script() {
	wp_register_script('ajax-login-script', get_stylesheet_directory_uri() . '/js/handle_tc_login.js', array('jquery') );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jqueryui', '//code.jquery.com/ui/1.11.4/jquery-ui.js');
    wp_enqueue_script('ajax-login-script');
	wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
	    'ajaxurl' => admin_url( 'admin-ajax.php' ),
	    'loadingmessage' => __('Signing in, please wait...')
	));
	
}

//********************************* variables *************************************************
//this gets checked if the user was migrated from the old system, we force a pw change when logging in
global $legacymigrationpw;
$legacymigrationpw = 'UrnewPW4TC!';
//hooks for theme-my-login plugin profile-form.php
register_taxonomy_for_object_type( 'post_tag', 'simple_link' );
kses_remove_filters();
//remove_filter('wpmu_signup_user_notification_email','admin_created_user_email');

//filter on userlogin---if pw was set in migration script, redirect to the change password page
add_filter( 'authenticate', 'tc_auth_signon', 30, 3 );
//change wp_mail from address for theme my login variable
add_filter( 'wp_mail_from', 'my_mail_from' );
add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
//only show the admin bar to admins, editors, authors
if ( current_user_can('publish_posts') ) {
add_filter( 'show_admin_bar', '__return_true' ); 	
}


//filter the default search 
add_filter('pre_get_posts', 'filter_search');
//*************************************************actions **************************************
//enqueue scripts for Angular JS
add_action( 'wp_enqueue_scripts', 'angularjs_scripts' );
// When the post is saved, saves our custom data.
add_action( 'save_post', 'myplugin_save_meta_box_data' );
//adds jquery ui to the dashboard --- used for user lookup autopopulate
add_action('admin_enqueue_scripts', 'tc_load_admin_scripts');
//handles updates to user profile -- extension of theme my login
add_action( 'personal_options_update', 'tml_profile_update');
add_action( 'edit_user_profile_update', 'tml_profile_update');
//add_action('edit_user_profile', 'tml_add_custom_fields');
//adds the ajax scripts for logging in    -- extension of theme my login
add_action( 'wp_enqueue_scripts', 'load_ajax_login_script', 999 );
//does the actual login   -- extension of theme my login
add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
//before showing posts if the post is restricted by group, make sure the user can view it --- extension of user-groups plugin
add_action( 'pre_get_posts', 'setup_and_restrict', 1); 
//dynamically add a jquery script to the footer on new post where type is review,webinar, tip, tc_materials post types so that
//the category will be automatically checked
add_action('admin_footer-post-new.php', 'ws_preselect_post_category_by_type');
//on all new posts uncheck comments by default, jquery script added dynamically to footer of edit screen
add_action('admin_footer-post-new.php', 'ws_uncheck_comments_by_default'); 
//when a user registers, override wp default behavior and do not show the wp admin bar 
add_action("user_register", "set_user_admin_bar_false_by_default", 10, 1);
//show the user's progess on modules in their profile for administrator
add_action('edit_user_profile','my_courseware_show_progress_admin_edit');
//sets the user to inactive on the wp-user account. By default, only super admin can delete users
add_action( 'remove_user_from_blog', 'tc_custom_remove_user_from_blog' );
//extend the user search to handle an auto populated value
add_action( 'pre_user_query', 'tc_autocomplete_pre_user_search'  );
//adds except box to a page
add_action( 'edit_page_form', 'tc_add_box');
//adds post property supports to custom post types (this can be done on PODS)
add_action('init', 'tc_init');
//adds basic supports for the hueman theme --- copied from parent theme
add_action( 'after_setup_theme', 'alx_setup' );
//sets a last login date to the usermeta
add_action('wp_login', 'set_last_login');
//add admin action for loading jquery ui on dashboard
add_action( 'admin_enqueue_scripts', 'enqueue_jqueryui' );
function enqueue_jqueryui($hook) {
wp_enqueue_script( 'my_custom_script', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js');
}
function angularjs_scripts() {
	wp_enqueue_style( 'angular-slider', get_stylesheet_directory_uri(). '/AngularCSS/rzslider.css' );
	wp_enqueue_script(
		'angularjs',
		'http://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js'
	);
	wp_enqueue_script(
		'sanitizeangularjs',
		'//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-sanitize.js'
	);
	wp_enqueue_script(
	'slider-bootstrap',
	get_stylesheet_directory_uri() . '/Angular/ui-bootstrap-tpls.js'
	);
	wp_enqueue_script(
		'angularjs-route',
		get_stylesheet_directory_uri() . '/Angular/app.js'
	);
	wp_enqueue_script(
		'rzslider',
		get_stylesheet_directory_uri() . '/Angular/rzslider.js'
	);
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function myplugin_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'myplugin_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['myplugin_new_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['myplugin_new_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_my_meta_value_key', $my_data );
}



function ajax_login(){
     // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );
     // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = $_POST['rememberme'];
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('The username or password is incorrect. Be sure you have an account. If you cannot remember your password please get a new one.')));
    } else {
		if ($info['remember']<>""){
		$user = get_user_by( 'username', $info['user_login'] );
		$user_id = $user->ID;
		if ($user_id){wp_set_auth_cookie($user_id, true);	}
		}
        echo json_encode(array('loggedin'=>true, 'redirect_to'=> $_POST['redirect_to'], 'message'=>__('Login successful, redirecting...')));
    }
 die();
}
//end ajax handling on login form (template) **************************************************

//handle theme my login profile changes *******************************************************

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
	if ( $_POST['school_district'] == "" ){
		$errors->add( 'empty_school_district', __('Please enter your school district.') );
		}
		return $errors;
}
function tml_profile_update( $user_id ) {
	global $wpdb;
	//these arrays add and remove Mo users from courses--this will no longer be needed when we no longer have MO contracts
    $aMoModuleIDsIn = array(29,17,18,19,21);
	$aMoModuleIDsOut = array(0,4,12,15,10);
	$edit_user_id = $_POST['user_id']; //on edit profile page for admin
	if($edit_user_id > 0){
		$user_id = $edit_user_id;
	}
    //update user on first name and nicename
	if ( !empty( $_POST['user_first_name'] ) ){
		$firstname =  $_POST['user_first_name'];
		$lastname = $_POST['user_last_name'];
		update_user_meta( $user_id, 'first_name', $firstname);
		update_user_meta($user_id, 'nickname', $firstname);
		wp_update_user(array('ID'=>$user_id, 'user_nicename'=>$firstname." ".$lastname, 'display_name'=>$firstname." ".$lastname));
		}
	 //update user on last name and nicename
	if ( !empty( $_POST['user_last_name'] ) ){
		$firstname =  $_POST['user_first_name'];
		$lastname = $_POST['user_last_name'];
		update_user_meta( $user_id, 'last_name', $lastname);
		wp_update_user(array('ID'=>$user_id, 'user_nicename'=>$firstname." ".$lastname, 'display_name'=>$firstname." ".$lastname));
		}
	//update user meta on state
	if ( !empty( $_POST['user_select_state'] ) ){
		//get the current state before changing it
		$current_state=get_user_meta($user_id,'state', true);
		if ($current_state == 'MO' && $_POST['user_select_state'] <> 'MO'){
		//delete MO modules not started and add the user to the national modules
			for ($i=0; $i<count($aMoModuleIDsIn); $i++){
			$wpdb->delete( 'wp_wpcw_user_courses', array( 'user_id' => $user_id, 'course_id'=>$aMoModuleIDsIn[$i],'course_progress'=>0), array( '%d','%d','%d' ) ); 		
		    }
		 //add the user to the national modules 
	       //enroll user in MO modules
			 for ($i =0; $i<=count($aMoModuleIDsOut); $i++){
		      $wpdb->insert( 
	         'wp_wpcw_user_courses', 
	         array( 
				'user_id' => $user_id, 
				'course_id' => $aMoModuleIDsOut[$i],
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
		   
		   }
	 update_user_meta( $user_id, 'state', $_POST['user_select_state'] );
	}
	if ($_POST['user_select_state'] == 'MO'){
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
     }
	 //update schoold district
    if ( !empty( $_POST['school_district'] ) ){
		update_user_meta( $user_id, 'school_district', $_POST['school_district'] );
		}
	//update goegraphic area
	if ( !empty( $_POST['radio_area'] ) ){
		update_user_meta( $user_id, 'geographic_area', $_POST['radio_area'] );
		}
	//update tc role
	if ( !empty( $_POST['tc_role'] ) ){
		$tc_role =  $_POST['tc_role'];
		update_user_meta( $user_id, 'transition_profile_role', $tc_role);
		//add user to PD hub if they are faculty
		if ($tc_role == 'College/University Faculty/Instructor'){
		 update_user_meta($user_id, 'transition_trainer', 1);
		 wp_set_object_terms($user_id, 37, 'user-group',true);
         }
		}
	//update user email and login
	if ( !empty( $_POST['email'] ) ){
		$email = $_POST['email'];
		wp_update_user(array('ID'=>$user_id, 'user_email'=>$email));
		$wpdb->update($wpdb->users, array('user_login' => $email), array('ID' => $user_id));
		}
} //end profile update
//filter on userlogin---if pw was set in migration script, redirect to the change password page
add_filter( 'authenticate', 'tc_auth_signon', 30, 3 );
function tc_auth_signon( $user, $username, $password) {
global $legacymigrationpw;
$userinfo = get_user_by( 'email', $username);
if ($userinfo){
	$bChecked=wp_check_password( $legacymigrationpw, $userinfo->user_pass, $userinfo->ID);
	$wp_hasher = new PasswordHash(8, TRUE);
    $password_hashed = $userinfo->user_pass;
	$plain_password = $legacymigrationpw;
    if($wp_hasher->CheckPassword($plain_password, $password_hashed)) {
    header("Location: lostpassword/?pwchange=1");
     exit();
     } else {
      return $user;
    }
}
else{
return $user;
}
}
//****************************************************************************************************END LOGIN AND PROFILE********************************************************************************************************
    //before showing posts if the post is restricted by group, make sure the user can view it
    add_action( 'pre_get_posts', 'setup_and_restrict', 1);  
     //change wp_mail from address
    add_filter( 'wp_mail_from', 'my_mail_from' );
    add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
    
    
    //only show the admin bar to admins, editors, authors
    if ( current_user_can('publish_posts') ) {
     add_filter( 'show_admin_bar', '__return_true' ); 	
    }
//******************************************************************************************************BEGIN ENQUEUE SCRIPTS *******************************************************************************************************
//enqueue the theme script
//enqueue tc custom js
wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/custom_tcscript.js', array( 'jquery' ),'', true );
$wp_profile = admin_url().'js/user-profile.js';
$wp_admin_psm_js = admin_url().'js/password-strength-meter.js';
wp_register_script("pass-strength-meter", $wp_admin_psm_js, array("password-strength-meter"), false);
wp_register_script("user-profile", $wp_profile, array("user-profile"), false);
wp_enqueue_script('password-strength-meter');
wp_enqueue_script('user-profile');
//*********************************************************************************************************END ENQUEUE SCRIPTS ********************************************************************************************************

//*******************bbpress show lead reply as the topic not as a reply******************
//add quick links to topic post type
// Add to our admin_init function 

//https://codex.bbpress.org/bbp_show_lead_topic/#sample-code-example
add_filter('bbp_show_lead_topic', 'custom_bbp_show_lead_topic' );
function custom_bbp_show_lead_topic( $show_lead ) {
 $show_lead = 'true';
  return $show_lead;
}

function custom_admin_reply_link( $args = array() ) {

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'id'           => 0,
			'link_before'  => '',
			'link_after'   => '',
			'reply_text'   => esc_html__( 'Reply', 'bbpress' ),
		), 'custom_admin_reply_link' );

		// Get the reply to use it's ID and post_parent
	$topic = bbp_get_topic( bbp_get_topic_id( (int) $r['id'] ) );



		$uri = '#new-post';

		// Add $uri to the array, to be passed through the filter
		$r['uri'] = $uri;
		$retval   = $r['link_before'] . '<a href="' . esc_url( $r['uri'] ) . '" class="bbp-topic-reply-link" title="Write a reply">' . $r['reply_text'] . '</a>' . $r['link_after'];

		return apply_filters( 'custom_admin_reply_link', $retval, $r, $args );
	
	}
add_filter( 'bbp_get_topic_reply_link', 'custom_admin_reply_link' );
function custom_get_reply_to_link( $args = array() ) {
        // Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'id'           => 0,
			'link_before'  => '',
			'link_after'   => '',
			'reply_text'   => __( 'Reply', 'bbpress' ),
			'depth'        => 0,
			'add_below'    => 'post',
			'respond_id'   => 'new-reply-' . bbp_get_topic_id(),
		), 'get_reply_to_link' );

		// Get the reply to use it's ID and post_parent
		$reply = bbp_get_reply( bbp_get_reply_id( (int) $r['id'] ) );

		// Bail if no reply or user cannot reply
		if ( empty( $reply ) || ! bbp_current_user_can_access_create_reply_form() )
			return;

		// Build the URI and return value
		$uri = remove_query_arg( array( 'bbp_reply_to' ) );
		$uri = add_query_arg( array( 'bbp_reply_to' => $reply->ID ) );
		$uri = wp_nonce_url( $uri, 'respond_id_' . $reply->ID );
		$uri = $uri . '#new-post';

		// Only add onclick if replies are threaded
		if ( bbp_thread_replies() ) {

			// Array of classes to pass to moveForm
			$move_form = array(
				$r['add_below'] . '-' . $reply->ID,
				$reply->ID,
				$r['respond_id'],
				$reply->post_parent
			);

			// Build the onclick
			$onclick  = ' onclick="return addReply.moveForm(\'' . implode( "','", $move_form ) . '\');"';

		// No onclick if replies are not threaded
		} else {
			$onclick  = '';
		}

		// Add $uri to the array, to be passed through the filter
		$r['uri'] = $uri;
		$retval   = $r['link_before'] . '<a href="' . esc_url( $r['uri'] ) . '" class="bbp-reply-to-link" title="Reply to this item."' . $onclick . '>' . esc_html( $r['reply_text'] ) . '</a>' . $r['link_after'];

		return apply_filters( 'custom_get_reply_to_link', $retval, $r, $args );
	}
add_filter( 'bbp_get_reply_to_link', 'custom_get_reply_to_link' );

function custom_bbpress_replies_nicename_filter($author_name,$reply_id ){
$author_id = bbp_get_reply_author_id($reply_id);
$author_object = get_userdata( $author_id );
$author_name  = ucfirst($author_object->user_nicename);
return $author_name;
}
add_filter( 'bbp_get_reply_author_display_name','custom_bbpress_replies_nicename_filter',10, 2);
function custom_bbp_get_user_profile_url( $user_id = 0, $user_nicename = '' ) {
		global $wp_rewrite;
        $reply_author_id = get_post_field( 'post_author', bbp_get_reply_id() );
		$topic_id=bbp_get_reply_id();
		$url = $wp_rewrite->root . bbp_get_user_slug() . '/%' . bbp_get_user_rewrite_id() . '%';
		$url = "/".str_replace( '%' . bbp_get_user_rewrite_id() . '%', $reply_author_id , $url );
		//$url = add_query_arg( array( bbp_get_user_rewrite_id() => $reply_author_id ), home_url( '/' ) );
		return apply_filters( 'custom_bbp_get_user_profile_url', $url, $user_id, $user_nicename );
	}
add_filter( 'bbp_get_user_profile_url','custom_bbp_get_user_profile_url',10, 2);
//*******************end bbpress *********************************************************** 
//******************Email functions for wp_mail - filters that change the to and from name on emails sent with wp_mail **************************
function my_mail_from( $email ){
	return "transition@transitioncoalition.org";
}
function my_mail_from_name( $name ){
    return "Transition Coalition";
}
//***************Add to favorites helper functions *********************************
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

//***********************Filters*************************************************

//add the login/logout links to the right of the menu ************************************
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
		var catId = 331;        
        jQuery('#in-category-331').click();
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
//deselct the comment checked box on edit forms
function ws_uncheck_comments_by_default() {
?>
        <script type="text/javascript">
         jQuery(function() {
			var current_page = jQuery(location).attr('href');
			if (current_page.indexOf('assessment_review') > -1  ){
			jQuery('#comment_status').attr('checked', true);
			}
			else if (current_page.indexOf('tip') > -1){
			jQuery('#comment_status').attr('checked', true);	
			}
			else if (current_page.indexOf('post-new') > -1 || current_page.indexOf('post_type=course_unit') > -1 || current_page.indexOf('post_type=page') > -1){
			jQuery('#enable-expirationdate').attr('checked',false); //set checked to false on Post Expirator on Forum post type
			jQuery('#comment_status').attr('checked', false);
			}
		});
	</script>
<?php
}
add_action('admin_footer-post-new.php', 'ws_preselect_post_category_by_type');
add_action('admin_footer-post-new.php', 'ws_uncheck_comments_by_default');
//filter the search function to include defined post types***********************************************
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

//add usernoise filter to contact link in menu -- this function is called when the link is created on the menu
add_filter( 'nav_menu_link_attributes', 'contact_link', 10, 3 );
function contact_link ($atts, $item, $args ) {
	  if ($item->ID == 11258) {
	$atts['onClick'] = 'usernoise.window.show()';
}
return $atts;	 
}
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
$updated = $wpdb->update ( 
	'wp_users', 
	array( 
		'deleted' =>  1,	
	), 
	array( 'ID' => $user_id), 
	array( 
		'%d'
	), 
	array( '%d' ) 
);
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
    
//**************Custom post types add exerpt field  to pages *****************************************
add_action( 'edit_page_form', 'tc_add_box');
add_action('init', 'tc_init');

function tc_init() {
	if(function_exists("add_post_type_support")) //support 3.1 and greater
	{
		add_post_type_support( 'page', 'excerpt' );
		add_post_type_support( 'tc_materials', 'excerpt' );
		add_post_type_support( 'webinar', 'excerpt' );
		add_post_type_support( 'webinar', 'comments' );
		add_post_type_support( 'course_unit', 'comments' );
		add_post_type_support( 'assessment_review', 'excerpt' );
		add_post_type_support('assessment_review','thumbnail');
        post_type_supports( 'projects', 'thumbnail' );
		add_post_type_support('topic','author');
		add_post_type_support('webinar','author');
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
			'template_submenu'=>'Template Submenu',
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


//associating a function to login hook
add_action('wp_login', 'set_last_login');
 
//function for setting the last login
//associating a function to login hook
add_action('wp_login', 'set_last_login');
 
//function for setting the last login
function set_last_login($login) {
   $user = get_userdatabylogin($login);
 
   //add or update the last login value for logged in user
   update_usermeta( $user->ID, 'last_login', current_time('mysql') );
}
/*----------------
if menu conditions
------------------*/
// theme's functions.php or plugin file
add_filter( 'if_menu_conditions', 'if_menu_enrolled_in_course' );
add_filter('if_menu_conditions', 'if_menu_is_user_pdhub');
add_filter('if_menu_conditions', 'if_menu_is_user_advisement');
add_filter('if_menu_conditions', 'if_menu_is_user_loggedin_and_not_mo');
add_filter('if_menu_conditions', 'if_menu_is_user_loggedin_and_mo');
add_filter('if_menu_conditions', 'if_menu_is_user_loggedin_and_ga');
add_filter('if_menu_conditions', 'if_menu_is_user_in_bp1mo');
add_filter('if_menu_conditions', 'if_menu_is_user_in_bp2mo');



function if_menu_is_user_in_bp2mo( $conditions ) {
	$conditions[] = array(
    'name'    =>  'User has access to BP v2 and is from MO', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
	global $current_user;
	global $wpdb;
    $id = $current_user->ID; 
	$user_in_mo = $wpdb->get_var( "SELECT meta_value FROM $wpdb->usermeta where meta_key = 'state' and user_id = " . $id );
    $user_in_bp2 = $wpdb->get_var( "Select user_id from wp_wpcw_user_courses where course_id = 39 and user_id=" . $id );
     if ($user_in_bp2 >0 && $user_in_mo =='MO' ){
		return true;
     }
     else{
     	return false;
     }
     return false;
    }
   );
  return $conditions;
}

function if_menu_is_user_in_bp1mo( $conditions ) {
	$conditions[] = array(
    'name'    =>  'User has access to BP v1 and is from MO', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
	global $current_user;
	global $wpdb;
    $id = $current_user->ID; 
	$user_in_mo = $wpdb->get_var( "SELECT meta_value FROM $wpdb->usermeta where meta_key = 'state' and user_id = " . $id );
    $user_in_bp1 = $wpdb->get_var( "Select user_id from wp_wpcw_user_courses where course_id = 17 and user_id=" . $id );
     if ($user_in_bp1 >0 && $user_in_mo =='MO'){
		return true;
     }
     else{
     	return false;
     }
     return false;
    }
   );
  return $conditions;
}


function if_menu_is_user_loggedin_and_ga( $conditions ) {
  $conditions[] = array(
    'name'    =>  'LoggedIn and GA', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
	global $current_user;
	global $wpdb;
	if( is_user_logged_in() ) {
	$id = $current_user->ID; 
    $user_in_mo = $wpdb->get_var( "SELECT meta_value FROM $wpdb->usermeta where meta_key = '%s' and user_id = %d" , 'state', $id );
     if ($user_in_mo == 'GA'){
		return true;
     }
     else{
     	return false;
     }
	}
	return false;
    }
  );
  return $conditions;
}



function if_menu_is_user_loggedin_and_mo( $conditions ) {
  $conditions[] = array(
    'name'    =>  'LoggedIn and MO', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
	global $current_user;
	global $wpdb;
	if( is_user_logged_in() ) {
	$id = $current_user->ID; 
    $user_in_mo = $wpdb->get_var($wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta where meta_key = '%s' and user_id = %d", 'state', $id ));
     if ($user_in_mo == 'MO'){
		return true;
     }
     else{
     	return false;
     }
	}
	return false;
    }
  );
  return $conditions;
}

function if_menu_is_user_loggedin_and_not_mo( $conditions ) {
  $conditions[] = array(
    'name'    =>  'LoggedIn and NOT MO', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
	global $current_user;
	global $wpdb;
	if( is_user_logged_in() ) {
	$id = $current_user->ID; 
     $user_in_mo = $wpdb->get_var($wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta where meta_key = '%s' and user_id = %d" ,'state', $id ));
     if ($user_in_mo == 'MO'){
		return false;
     }
     else{
     	return true;
     }
	}
	return false;	
}
   );
  return $conditions;
}

function if_menu_is_user_advisement( $conditions ) {
  $conditions[] = array(
    'name'    =>  'User is Advisement Center', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
	global $current_user;
	global $wpdb;
	if( is_user_logged_in() ) {
	$id = $current_user->ID; 
    $user_in_group = $wpdb->get_var($wpdb->prepare("SELECT object_id FROM $wpdb->term_relationships where term_taxonomy_id = %d and object_id = %d" , 53, $id ));

     if ($user_in_group == $id){
		return true;
     }
     else{
     	return false;
     }
	}
	return false;
    }
   );
  return $conditions;
}
function if_menu_is_user_pdhub( $conditions ) {
  $conditions[] = array(
    'name'    =>  'User is PDHub', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
    global $current_user;
	global $wpdb;
	if( is_user_logged_in() ) {
	$id = $current_user->ID; 
$user_in_group = $wpdb->get_var($wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships where term_taxonomy_id = %d and object_id =%d", 38,$id ));
     if ($user_in_group == $id){
		return true;
     }
     else{
     	return false;
     }
	}
	return false;
    }
   );
  return $conditions;
}
function if_menu_enrolled_in_course( $conditions ) {
  $conditions[] = array(
    'name'    =>  'User is registered for LERN Employment Prompting', // name of the condition
    'condition' =>  function($item) {          // callback - must return TRUE or FALSE
    global $current_user;
	global $wpdb;
	if( is_user_logged_in() ) {
	$id = $current_user->ID; 
    $user_is_coach = $wpdb->get_var($wpdb->prepare("SELECT count(*) from wp_wpcw_course_extras where coach_id like '%". $id."%' and course_id =%d",40));
	if ($user_is_coach > 0)
	{return true;}
     //see if today's date  >= start date
	$start_date = $wpdb->get_var ($wpdb->prepare( "SELECT start_date from wp_wpcw_course_extras where course_id =%d", 40));
	$today = date('Y-m-d hh:mm:ss');
    $user_is_registered = $wpdb->get_var($wpdb->prepare( "SELECT user_id FROM wp_wpcw_user_courses where course_id=%d and user_id = %d", 40, $id ));
     if ( $user_is_registered > 0  && $today >= $start_date){
		return true;
     }
     else{
     	return false;
     }
	}
	return false;
    }
   );
  return $conditions;
}
/*----------------
end if menu conditions
------------------*/