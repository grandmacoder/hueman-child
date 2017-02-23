<?php
/*
Template Name: PdHub Overview
*/
get_header();
?>
<?php wp_nav_menu(array(
    'theme_location' => 'template_submenu',
	'container'       => 'div',
	'container_id'    => 'submenucontainer',
	'menu_id' => 'submenuid',
));
?>
<br><br>
<section class="content">
    <div>
	<article>
	<h3><?php the_title();?></h3>
	<?php the_content(); ?>
	</article>
	</div>
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 