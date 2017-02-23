<!--this is used to output the category posts widget overriding the template.php file in the plugin-->
<section class="articles_posts-by-cat">
<?php if ( $posts->have_posts() ) { ?>
	<?php while ( $posts->have_posts() ) {
		$posts->the_post(); ?>
	<article class="posts-by-cat_article-<?php the_ID(); ?>">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_title(); ?>
			</a>
	</article>
	<?php }
} else { ?>
	<p><?php _e( 'Nothing has been posted in the selected categories.', 'posts_by_cat_widget' ); ?></p>
<?php } ?>

</section>
