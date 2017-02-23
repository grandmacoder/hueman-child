<?php
/*
* Template Name: TC Login Page
*/
?>
<?php get_header(); ?>
<?php $referrer = $_SERVER['HTTP_REFERER']; ?>
<section class="content">	
<?php the_content();?>
<?php
global $wpdb;
if ($_GET['action'] =='activate'){
	if ( ! empty($_GET['key']) || ! empty($_POST['key']) ) {
			$key = !empty($_GET['key']) ? $_GET['key'] : $_POST['key'];
			// Activates the user and send user/pass in an email 
			$result = wpmu_activate_signup($key);
			if ( ! is_wp_error($result) ) {
				$updated = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->users SET deleted = '%d' WHERE ID = '%d'", 0,$result['user_id']));
				//set user deleted =0
				echo "<br><br>Your account has been activated!<br><strong>Since you were added to this system by an administrator you need to reset your password and provide some more information about yourself.<br>
				Please read the New User email you receive carefully.</strong><br>It should be in your inbox momentarily with login instructions.<br>Thank you.";
			}
		}
	else{
		echo "<br><br>Oops, it looks like you don't have an activation key. Please contact the site administrator to help you get one, or re-click the link in the email that was sent to you.";	
		}
}
else{
?>
<p>&nbsp;</p>
<form id="ajaxlogin" action="login" method="post"><h4>LOG IN</h4><br>
<label class="error"></label>
		<p><label for="username">Email </label><br> <input id="username" name="username" type="text" ></p><br>
		<p><label for="password">Password</label><br> <input id="password" name="password" type="password" ></p>
		<p class="login-remember" style="padding-top:10px; padding-bottom: 10px;"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Remember Me</label></p>
        <p style="padding-bottom: 10px;"> <button name="submit" type="submit" value="Login">Login</button></p>
		<input type="hidden" name="redirect_to" id="redirect_to" value="<?php echo $referrer;?>" />	
		<a class="register small" href="<?php bloginfo('url'); ?>/register-3/">Create Account</a><br>
		<a class="lost small" href="<?php bloginfo('url'); ?>/lostpassword/">Reset Password</a>
		
		<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
		
	</form>
	
<?php
}
?>
</section>
<?php 
get_sidebar(); 
get_footer(); 
?>