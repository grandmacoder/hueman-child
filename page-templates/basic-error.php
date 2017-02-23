<?php
/*
Template Name: Basic Error
*/
?>
<?php get_header(); ?>

<section class="content">
	
	<?php get_template_part('inc/page-title'); ?>
	
	<div class="pad group">
	   <?php while ( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?>>	
				<div class="post-inner group">
					<span class="post-title"><?php the_title(); ?></span>
					<!--<p class="post-byline">--><?php //_e('by','hueman'); ?> <?php //the_author_posts_link(); ?> <?php //the_time(get_option('date_format')); ?><!--</p>-->
					<?php if( get_post_format() ) { get_template_part('inc/post-formats'); } ?>
					<div class="clear"></div>
					<div class="entry">	
						<div class="entry-inner">
							<?php the_content(); ?>
							<?php //wp_link_pages(array('before'=>'<div class="post-pages">'.__('Pages:','hueman'),'after'=>'</div>')); ?>
						</div>
						<div class="clear"></div>				
					</div><!--/.entry-->
					
				</div><!--/.post-inner-->	
			</article><!--/.post-->				
		<?php endwhile; ?>
		
		
	</div><!--/.pad-->
	
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>