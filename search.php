<?php get_header(); ?>

<section class="content">

	<?php get_template_part('inc/page-title'); ?>
	<?php 
	global $wp_query;
	$args = array_merge( $wp_query->query_vars, array( 'post_type' => 'post') );
        get_posts($args); ?>
	<div class="pad group">
		
		<div class="notebox">
			<?php if ( !have_posts() ): ?>
				<?php _e('Oops, no results. Please try another search.','hueman'); ?>
			<?php endif; ?>
			<div class="search-again">
				<?php get_search_form(); ?>
			</div>
		</div>
		
		<?php if ( have_posts() ) : ?>
		<div>
		  <?php while ( have_posts() ): the_post(); ?>
				
		<?php //handle simple link post display ?>
		<?php if(get_post_type($id) == 'simple_link'){ 
		        $meta_value = get_post_meta( $id, 'web_address', true );
		?>
				<article id="post-<?php the_ID(); ?>">	
							<p>
							<span class="<?php echo linkIcon($meta_value)?>"><a  class=newsitem href="<?php echo $meta_value; ?>" target=_blank rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></span>
							<!--/.post-title-->
							&nbsp;&nbsp;<span class="post-date"><?php the_time('M j, Y'); ?></span>
							</p>
							<span><?php the_excerpt(); ?></span>
							<div style="clear:both;"></div>
							</article><!--/.post-->	 
							<br>
				<?php }else{ //display all other post types?>
					
							<article id="post-<?php the_ID(); ?>">	
								<p>
								<a class=newsitem href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
								<!--/.post-title-->
								&nbsp;&nbsp;<span class="post-date"><?php the_time('M j, Y'); ?></span>
								</p>
								<span><?php the_excerpt(); ?></span>
								<div style="clear:both;"></div>
							</article><!--/.post-->	 
					<br>
				<?php } ?>
				<?php endwhile;  ?>
			</div><!--/.post-list-->
		
			<?php get_template_part('inc/pagination'); ?>
			
		<?php endif; ?>
		
	</div><!--/.pad-->
	
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>