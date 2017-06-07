<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
global $wpdb;
$state_list = $wpdb->get_results($wpdb->prepare("SELECT state, abbreviation FROM states ORDER BY abbreviation", OBJECT));
$i=0;
foreach($state_list as $state){
    $selected = "";
	if ($i > 0){
	if($_POST['user_select_state'] <> "" && $_POST['user_select_state'] == $state->abbreviation){
		$state_list_str .= "<option value='".$state->abbreviation."' selected>".$state->state."</option>";
	}else{
	$state_list_str .= "<option value='".$state->abbreviation."' ".$selected.">".$state->state."</option>";
	}
	}
$i++;
}
$state_list_str .= "<option value='n/a' ". $selected. ">Outside U.S.</option>";
$tc_roles = array("State Ed. Agency Rep.", "General Ed. Teacher", "Special Ed. Teacher", "Vocational/Transition Coordinator", "School Administrator", "School Related Services", 
					"State VR Agency Rep.", "Local VR Agency Rep.", "Technical Assistance Provider", "Center for Independent Living Rep.", "Other Community Agency Rep.", "Parent/Guardian",
					"Youth with a Disability", "College/University Student", "College/University Faculty/Instructor", "Other State Agency Rep.", "Other");
foreach ($tc_roles as $tc_role){
$role_options .= "<option value='".$tc_role."'>".$tc_role."</option>";
}
?>
<script src="<?php bloginfo('template_directory');?>-child/theme-my-login/tml-js/register-profile.js" type="text/javascript"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js" type="text/javascript"></script>

<h2><?php printf( __( 'Join %s and start learning!' ), $current_site->site_name ); ?></h2>


<form id="tc_register_form">
<fieldset class="user_datails">
<legend>User Details</legend>
	<input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" /> 
	<input type="hidden" name="action" value="register" />
	<input type="hidden" name="stage" value="validate-user-signup" /> 
	<input type='hidden' id="user_school_district" name="user_school_district" value='new_user'>
	<input type="hidden" name="page_referer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" /> 
	<?php do_action( 'signup_hidden_fields' ); ?>
	
			<p>
			<label for="email">E-mail</label>
			<input type="text" name="user_email" id="user_email"  maxlength="60" value="<?php echo $user_data->user_email;  ?>" class="regular-text" />
			</p><br>
	<span class="hint"><?php _e( 'We send your registration email to this address. (Double-check your email address before continuing.)' ); ?></span>

	<label for="first_name">First Name: <abbr title="required" class="required">*</abbr></label>
	<?php if ( $errmsg = $errors->get_error_message( 'empty_first_name' ) ) { ?>
		       <p class="error"><?php echo $errmsg;?></p>
			 <?php } ?>
	<input type="text" name="first_name" id="first_name"  value="<?php echo $_POST['first_name'] ?>" maxlength="60" />
    <label for="last_name">Last Name: <abbr title="required" class="required">*</abbr></label>
	<?php if ( $errmsg = $errors->get_error_message( 'empty_last_name' ) ) { ?>
		       <p class="error"><?php echo $errmsg;?></p><?php } ?>
	<input type="text" name="last_name" id="last_name" value="<?php echo $_POST['last_name'] ?>" maxlength="60"/>
     <br>
	<label class="tc_role" for="role">Role: <abbr title="required" class="required">*</abbr> </label>
	<select name="role" id="role"  tabindex="2">
			<option value="">Please Select</option>
			<?php echo $role_options ?>
    </select> 
	<br>
	<br>
	<?php if ( $errmsg = $errors->get_error_message( 'empty_user_state' ) ) { ?>
		       <p class="error"><?php echo $errmsg;?></p> <?php } ?>
	<label class="user_state_register" for="user_state">State: <abbr title="required" class="required">*</abbr> </label>
	<select name="user_select_state" id="user_select_state"  tabindex="2">
			<option value="">Please Select</option>
			<?php echo $state_list_str ?>
                         </select> 
	</p>
	 <?php if ( $errmsg = $errors->get_error_message( 'empty_school_district' ) ) { ?>
		       <p class="error"><?php echo $errmsg; ?><?php } ?>
	<label class="school_district" for="school_district">
    School District <abbr title="required" class="required">*</abbr></label>
	<select name="school_district" id="school_district" class="school-district" tabindex="2"></select>    </p> 
    <label id="ethnicity" for="ethnicity">Ethnicity/Race: <abbr title="required" class="required">*</abbr><br>
          <input type="radio" name="race" value="American Indian or Alaska Native"> American Indian or Alaska Native<br>
          <input type="radio" name="race" value="Asian"> Asian<br>
          <input type="radio" name="race" value="Black or African American"> Black or African American<br>
          <input type="radio" name="race" value="Hispanic" > Hispanic<br>
	   <input type="radio" name="race" value="Native Hawaiian or Other Pacific Islander"> Native Hawaiian or Other Pacific Islander<br>
	  <input type="radio" name="race" value="White"> White<br>
	  <input type="radio" name="race" value="Two or More Races"> Two or More Races<br>
	  <input type="radio" name="race" value="Decline to state"> Decline to state<br>
	 </label>
<br>
<label id="gender" for="gender">Gender: <abbr title="required" class="required">*</abbr><br>
<input type="radio" name="gender" value="male"> Male<br>
<input type="radio" name="gender" value="female"> Female
</label>
<br>
<label id="area" for="area">Identify the geographic area where you work with youth:<br>
<input type="radio" name="area" value="Urban"> Urban<br>
<input type="radio" name="area" value="Suburban"> Suburban<br>
<input type="radio" name="area" value="Rural"> Rural<br>
<input type="radio" name="area" value="Remote"> Remote
</label>
<br>
<label id="collegetraining" for="collegetraining">Do you provide transition training to college students(undergraduates or graduates) OR professionals? <abbr title="required" class="required">*</abbr><br>
          <input type="radio" name="collegetraining" value="y"> Yes<br>
		  <input type="radio" name="collegetraining" value="n"> No
</label>
</fieldset>
<fieldset>
<legend>Agree</legend>
<span style="font-size: 16px;"> Do you agree? </span>
<input type="checkbox" name="user_agreed" id="user_agreed" value="y" class="" tabindex="24">
<label for="user_agreed" class=label_agreed>
I have read the <a href="/blog/informed-consent-2/" target="_blank">Transition Coalition Informed Consent Statement</a> and agree to the uses and disclosure of my information described.
<br>
I also affirm that I am at least 18 years old.
<br>
I have read and agree to the <a href="/blog/privacy-policy/" target="_blank">Transition Coalition Privacy Policy</a>.
<br>
I understand that I will not have access to the training modules without my agreement to the above statements.
<br>
For your convenience, these documents are avail able on the bottom of every Transition Coalition web page.
</label>

</fieldset>
 <fieldset>
<legend>Authentication</legend>                     
        <?php do_action( 'signup_extra_fields', $errors ); ?>
         <p>
	<?php if ( $active_signup == 'blog' ) { ?>
		<input id="signupblog<?php $template->the_instance(); ?>" type="hidden" name="signup_for" value="blog" />
	<?php } elseif ( $active_signup == 'user' ) { ?>
		<input id="signupblog<?php $template->the_instance(); ?>" type="hidden" name="signup_for" value="user" />
	<?php } else { ?>
		<input id="signupblog<?php $template->the_instance(); ?>" type="radio" name="signup_for" value="blog" <?php if ( ! isset( $_POST['signup_for'] ) || $_POST['signup_for'] == 'blog' ) { ?>checked="checked"<?php } ?> />
		<label class="checkbox" for="signupblog"><?php _e( 'Gimme a site!' ); ?></label>
		<br />
		<input id="signupuser<?php $template->the_instance(); ?>" type="radio" name="signup_for" value="user" <?php if ( isset( $_POST['signup_for'] ) && $_POST['signup_for'] == 'user' ) { ?>checked="checked"<?php } ?> />
		<label class="checkbox" for="signupuser"><?php _e( 'Just a username, please.' ); ?></label>
	<?php } ?>
	</p>
</fieldset>
	<p class="submit"><input onClick="ga('send', 'event', { eventCategory: 'Create Account', eventAction: 'Submit'});" type="submit" id="registersubmit" name="submit" class="submit" value="<?php esc_attr_e( 'Create Account' ); ?>" /></p>
</form>
<?php //$template->the_action_links( array( 'register' => false ) ); ?>
