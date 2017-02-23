<?php
/**
 * Unit Template Name: LERN no title template
 *
 * Be sure to use the "Unit Template Name:" in the header.
 * To display the course unit content, be sure to inclue the loop.
 */
?>
<?php get_header(); ?>
<script type='text/javascript' src='/wp-content/themes/hueman-child/js/LERN_network.js'></script>
<section class="content">
<div class="pad group">
<?php echo the_content();?>
</div><!--/.pad-->
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>