<?php
/*
Template Name: tc testtemplate

*/


?>

<?php get_header(); ?>
<section class="content">
<?php
global $wpdb;
 echo do_shortcode( '[avatar_upload]' ) 
?>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 

