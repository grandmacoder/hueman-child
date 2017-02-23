<?php
//uses a remote post to google to verify the captcha input,returns true or false to jquery
if(!isset($wpdb)){
    require_once('../../../../wp-config.php');
    require_once('../../../../wp-load.php');
    require_once('../../../../wp-includes/wp-db.php');
    require_once('../../../plugins/theme-my-login/modules/recaptcha/recaptcha.php');
}

$private_key = "6Lc2fe0SAAAAAFj8zNj7felcoX_dpD_SS5k5tVmA'";
// reCaptcha looks for the POST to confirm
$resp = recaptcha_validate( $private_key, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field'] );
// If the entered code is correct it returns true (or false)
if ($resp == true) {
echo "true";
} 
else {
echo "false";
}
//echo $resp['body'][0];
function recaptcha_validate( $private_key, $remote_ip, $challenge, $response ) {
		$response = wp_remote_post( 'http://www.google.com/recaptcha/api/verify', array(
			    'body' => array(
				'privatekey' => $private_key,
				'remoteip'   => $remote_ip,
				'challenge'  => $challenge,
				'response'   => $response
			)
		) );
        $response_code=wp_remote_retrieve_response_code( $response );
		$response_message=wp_remote_retrieve_response_message( $response );
		if ( 200 == $response_code ) {
		$aResponseBody= explode( "\n", wp_remote_retrieve_body( $response ));
			if ( 'true' == $aResponseBody[0] ){
				return true;
			}
		}
		
		return false;
}
?>