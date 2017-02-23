<?php
/**
 * Unit Template Name: LERN no title with loader template
 *
 * Be sure to use the "Unit Template Name:" in the header.
 * To display the course unit content, be sure to inclue the loop.
 */
?>
<?php get_header(); ?>
<script type='text/javascript'>
jQuery(window).load(function() {
	jQuery(".loader").fadeOut("slow");
});
</script>
<style>
.loader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('/wp-content/uploads/2017/02/page-loader.gif') 50% 50% no-repeat rgb(249,249,249);
}
</style>
<script type='text/javascript' src='/wp-content/themes/hueman-child/js/LERN_network.js'></script>
<section class="content">
<div class="pad group">
<div class="loader"></div>
<?php echo the_content();?>
</div><!--/.pad-->
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>