<?php
/*
Template Name: Mailchimp Form 
*/
/**
Strips back the theme for fancybox iframe mailchimp form
 *
 * @package WordPress
 */

//add the autocomplete jquery classes
get_header();
?>
<style>
	html, body{
	overflow:hidden !important;
	}
     #header{display:none !important;}
	.pad.group{margin-left:-40px !important;}
	.page-title {display:none !important;}
	 #wpadminbar{display:none !important;}
     div.main {width: 100%;}
    .mc4wp-alert.mc4wp-notice p{color:red;}
	.breadcrumbs {display:none !important;}
</style>
<div id="tcmailchimp">
<?php echo do_shortcode( '[mc4wp_form id="20735"]' );?>
</div>
</div></div></div>	
</body>
</html>
