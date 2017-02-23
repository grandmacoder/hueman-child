<?php
/**
Template Name: Course Registration Template
 * A template for enrolling in a course with a key
 */
?>
<?php get_header(); ?>
<style>
#registration_avatarupload{
display:none;	
}
.registermsg{
font-weight:bold;
}
.validatemsg{
color: #c2132f;
font-weight:bold;
}
</style>

<div style="margin-top: 30px; margin-right: 100px;">
<div class="container">
<section class="content">
<?php  
the_content();
?>
<div id="messagearea"></div>
<div id="coursedescription"></div>

<div id="registration_message"></div>
<div id="registration_avatarupload">
<?php echo do_shortcode( '[avatar_upload]' ); ?>
</div>
<br><br>
<div id="validatearea"></div>
<div id="registrationform"></div>
<div id="registrationmaterials">
</div>




</section>
</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>



