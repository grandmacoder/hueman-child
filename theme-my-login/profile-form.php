<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<script src="<?php bloginfo('template_directory');?>-child/theme-my-login/tml-js/register-profile.js" type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js" type="text/javascript"></script>
<div class="login profile" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php $template->the_action_template_message( 'profile' ); ?>
	<?php $template->the_errors(); ?>
	<form id="your-profile" name="your-profile" action="<?php $template->the_action_url( 'profile' ); ?>" method="post">
		<?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
		<p>
			<input type="hidden" name="from" value="profile" />
			<input type="hidden" name="nickname" value="<?php echo $current_user->ID; ?>_nickname" />
			<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
		</p>

		<?php if ( has_action( 'personal_options' ) ) : ?>

		<h3><?php _e( 'Personal Options' ); ?></h3>


		<?php do_action( 'personal_options', $profileuser ); ?>


		<?php endif; ?>
		<?php do_action( 'profile_personal_options', $profileuser ); 
		global $wpdb;
$state_list = $wpdb->get_results($wpdb->prepare("SELECT state, abbreviation FROM states ORDER BY abbreviation", OBJECT));
foreach($state_list as $state){
	if($profileuser->state == $state->abbreviation){
		$state_list_str .= "<option value='".$state->abbreviation."' selected>".$state->state."</option>";
	}else{
	$state_list_str .= "<option value='".$state->abbreviation."'>".$state->state."</option>";
	}
}
$tc_roles = array("State Ed. Agency Rep.", "General Ed. Teacher", "Special Ed. Teacher", "Vocational/Transition Coordinator", "School Administrator", "School Related Services", 
					"State VR Agency Rep.", "Local VR Agency Rep.", "Technical Assistance Provider", "Center for Independent Living Rep.", "Other Community Agency Rep.", "Parent/Guardian",
					"Youth with a Disability", "College/University Student", "College/University Faculty/Instructor", "Other State Agency Rep.", "Other");
foreach ($tc_roles as $tc_role){
	if($profileuser->transition_profile_role == $tc_role){
		$role_options .= "<option value='".$tc_role."' selected>".$tc_role."</option>";
	}else{
	$role_options .= "<option value='".$tc_role."'>".$tc_role."</option>";
	}
}

$user_id = get_current_user_id();
$user_data = get_userdata($user_id);
?>

<fieldset>
<legend>User Details</legend>
<input type='hidden' id="user_school_district" name="user_school_district" value='<?php echo $profileuser->school_district;?>'>
		<p>
		<input type="hidden" name="profile_user_id"  id="profile_user_id" value="<?php echo $user_id; ?>" />
			<label for="first_name">First Name</label>
			<input type="text" name="user_first_name" id="user_first_name" value="<?php echo get_user_meta($user_id, 'first_name', true); ?>" class="regular-text" />
		
</p>
		<p>	
			<label for="last_name">Last Name</label>
			<input type="text" name="user_last_name" id="user_last_name" value="<?php echo get_user_meta($user_id, 'last_name', true);  ?>" class="regular-text" />
	</p>	
			
			<p>
			<label for="email">E-mail</label>
			<input type="text" name="email" id="email" value="<?php echo $user_data->user_email;  ?>" class="regular-text" />
			</p>
		<input type="hidden" name="user_email" id="user_email" value="<?php echo $user_data->user_email; ?>" /> 
		<p>
			<label class="tc_role" for="role">Role:</label>
	<select name="tc_role" id="tc_role"  tabindex="2">
			<option value="">Please Select</option>
			<?php echo $role_options ?>
                         </select> 
						 </p>
						 <p>
	<label class="user_state_register" for="user_state">State: </label>
	<select name="user_select_state" id="user_select_state"  tabindex="2">
			<option value="">Please Select</option>
			<?php echo $state_list_str ?>
                         </select> 
						 </p>
	<p>
	<label class="school_district" for="school_district">
	School District</label>
	<select name="school_district" id="school_district" class="school-district" tabindex="2">
	<option value="">Please Select</option>
			<?php echo $district_list_str; ?>
                         </select> 
	</p>
	<p>
<label id="area" for="area">Identify the geographic area where you work with youth: </label>
 <?php
 $user_ID = get_current_user_id();
 $geographic_a = array("Urban", "Suburban", "Rural", "Remote");
 $user_area = get_user_meta($user_ID, 'geographic_area', true);
 
 foreach ($geographic_a as $geo){
 if($geo == $user_area){
	$geo_option .= "<input type='radio' name='radio_area' value='".$geo."'  checked='checked'> ".$geo."<br>";
 }else{
	$geo_option .= "<input type='radio' name='radio_area' value='".$geo."'> ".$geo."<br>";
	}
 }
 ?>
<?php echo $geo_option; //display html for radio boxes?>
</p>
</fieldset>
 <fieldset>
<legend>Authentication</legend>  
<?php do_action( 'show_user_profile', $profileuser ); ?>
		<?php
		$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
		if ( $show_password_fields ) :
		?>
		
		<?php if ( ! empty( $errors ) ) { ?>
			<p class="error"><?php echo implode( '<br />', $errors ); ?></p>
		<?php } ?>
		<label for="pass1"><?php _e( 'New Password:', 'theme-my-login' ); ?></label>
        <input autocomplete="off" name="pass1" id="pass1" class="input" size="20" value="" type="password" /><br />
		<div style="display:none;"><input autocomplete="off" name="pass2" id="pass2" class="input" size="20" value="" type="password" /></div>	
				<div id="pass-strength-result"><?php _e( 'Strength indicator', 'theme-my-login' ); ?></div>
		
				<p class="description indicator-hint"><?php _e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).' ); ?></p>	
				
		<?php endif; ?>
		
</fieldset>

			<input type="submit" id="update_user"  value="<?php esc_attr_e( 'Update Profile' ); ?>" name="submit" />

	</form>
	
	
</div>
