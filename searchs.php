<?php get_header(); ?>

<section class="content">

	<?php get_template_part('inc/page-title'); ?>
	<?php 
	global $wp_query;
	//$post_tarray = array_merge( $wp_query->query_vars, array( 'post_type' => 'post') );
	//$simple_tarray = array_merge( $wp_query->query_vars, array('post_type'=>'simple_link'));
	
	//$args = array_merge($post_tarray, $simple_tarray);
	//echo "count of array is ".count($post_tarray);
	//get_posts($simple_tarray); ?>
	
	<div class="pad group">
		
		<div class="notebox">
			<?php //_e('For the term','hueman'); ?> "<span><?php echo get_search_query(); ?></span>".
			<?php if ( !have_posts() ): ?>
				<?php _e('Please try another search:','hueman'); ?>
			<?php endif; ?>
			<div class="search-again">
				<?php get_search_form(); ?>
			</div>
		</div>
		
		<?php if ( have_posts() ) : ?>
		
			<div>
				<?php while ( have_posts() ): the_post(); ?>
				
		<?php if(get_post_type($id) == 'post'){ ?>
					<article id="post-<?php the_ID(); ?>">	
					<p>
					<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
					</h3><!--/.post-title-->
					<span class="post-date"><?php the_time('M j, Y'); ?></span>
					</p>
					<?php the_excerpt(); ?>
					<div style="clear:both;"></div>
					</article><!--/.post-->	 
					<br>
				<?php }else if(get_post_type($id) == 'simple_link'){ ?>
				
					<?php	$web_address = get_post_meta( $id, 'web_address', true ); //run second query for simple link posts
							$simple_link_title=$resource_link->post_title; 
					?>
					
							<article id="post-<?php the_ID(); ?>">	
							<p>
								<h3><a href="<?php $web_address; ?>" rel="bookmark" title="<?php $simple_link_title; ?>"><?php $simple_link_title;  ?></a>
								</h3><!--/.post-title-->
							<span class="post-date"><?php the_time('M j, Y'); ?></span>
							</p>
							<?php the_excerpt(); ?>
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