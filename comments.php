<?php if ( post_password_required() ) { return; } ?>

<section id="comments" class="themeform">
<div class="content-column full_width">
	<?php if ( have_comments() ) : global $wp_query;

		global $post;
		
		$categories = get_the_category($post->ID);
	
		$bReview = false;
		
		foreach($categories as $category){
			
		if($category->cat_name == "Assessment Review" || $category->cat_name == "Tip"){
			
			$bReview = true;
		}
	}
		
	?>

	<ul class="comment-tabs group">
			<li class="active"><?php if($bReview){ ?>
			<input type="button" onclick="#reply-title" value="Write a review!"></li>
			<?php } else{
			?><a href="#commentlist-container"><?php echo count($wp_query->comments_by_type['comment']); 
			?> <?php _e( 'Reviews', 'hueman' ); ?></a></li>
			<?php } // end else ?>
		</ul>

		<?php if ( ! empty( $comments_by_type['comment'] ) ) { ?>
		<div id="commentlist-container" class="comment-tab">
			<ol class="commentlist">
			
				<?php if($bReview){
					wp_list_comments( 'avatar_size=96&type=comment&max_depth=1' ); 
					}else{
					wp_list_comments( 'avatar_size=96&type=comment');
					}
					?>
					
			</ol><!--/.commentlist-->
			
			<?php if ( get_comment_pages_count() > 1 && get_option('page_comments') ) : ?>
			<nav class="comments-nav group">
				<div class="nav-previous"><?php previous_comments_link(); ?></div>
				<div class="nav-next"><?php next_comments_link(); ?></div>
			</nav><!--/.comments-nav-->
			<?php endif; ?>
			
		</div>	
		<?php } ?>
		
		<?php if ( ! empty( $comments_by_type['pings'] ) ) { ?>
		
		<?php ?>
		<div id="pinglist-container" class="comment-tab">
			
			<ol class="pinglist">
				<?php // not calling wp_list_comments twice, as it breaks pagination
				$pings = $comments_by_type['pings'];
				foreach ($pings as $ping) { ?>
					<li class="ping">
						<div class="ping-link"><?php comment_author_link($ping); ?></div>
						<div class="ping-meta"><?php comment_date( get_option( 'date_format' ), $ping ); ?></div>
						<div class="ping-content"><?php comment_text($ping); ?></div>
					</li>
				<?php } ?>
			</ol><!--/.pinglist-->
			
		</div>
		<?php } ?>

	<?php else: // if there are no comments yet ?>

		<?php if (comments_open()) : ?>
			<!-- comments open, no comments -->
		<?php else : ?>
			<!-- comments closed, no comments -->
		<?php endif; ?>
	
	<?php endif;?>
	
	<?php 
	
	if($bReview){
	$comments_args = array('title_reply'=>'Write a Review', 'label_submit'=> 'Submit Review');
	if( comments_open() ) { comment_form($comments_args); 
	} 
	}elseif( comments_open() ) { 
	 $comments_args =array('title_reply'=>'Write a Review', 'label_submit'=> 'Submit Review');
	comment_form($comments_args); 
	} 
	?>
</div>
</section><!--/#comments-->
