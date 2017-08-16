<?php get_header(); ?>
<section class="content">
	<?php 
	get_template_part('inc/page-title'); 
    ?>
	<div class="pad group">
		<?php while ( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?> >	
				<div class="post-inner group">
				    <?php if ( get_post_type() <> 'assessment_review'){  //don't show title on assessment reviews?>
					<span class="post-title"><?php the_title(); ?></span>
					<?php } ?>
					<p class="post-byline"><?php //_e('by','hueman'); ?> <?php //the_author_posts_link(); ?> <?php //the_time(get_option('date_format')); ?></p>
					<?php if( get_post_format() ) { get_template_part('inc/post-formats'); } ?>
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
		
		
		<?php //the_tags('<p class="post-tags"><span>'.__('Tags:','hueman').'</span> ','','</p>'); ?>
		
		<?php if ( ( ot_get_option( 'author-bio' ) != 'off' ) && get_the_author_meta( 'description' ) ): ?>
			<div class="author-bio">
				<div class="bio-avatar"><?php echo get_avatar(get_the_author_meta('user_email'),'128'); ?></div>
				<p class="bio-name"><?php the_author_meta('display_name'); ?></p>
				<p class="bio-desc"><?php the_author_meta('description'); ?></p>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
		<?php if ( ot_get_option( 'post-nav' ) == 'content') { get_template_part('inc/post-nav'); } ?>
		<?php //if ( ot_get_option( 'related-posts' ) != '1' ) {
	         //$post_type =  get_post_type(get_the_ID());
           //if ( $post_type == 'post' || $post_type  == 'tc_materials' ||  $post_type == 'webinar'){
		  // get_template_part('inc/related-posts');
		   //  }
		//}
		?>
		<?php comments_template('/comments.php',true); ?>
		
	</div><!--/.pad-->
	
</section><!--/.content-->

<?php
if ( get_post_type() != 'comment_topic'){ 
get_sidebar(); 
}
?>

<?php get_footer(); ?>