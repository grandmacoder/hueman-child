<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="post-<?php bbp_reply_id(); ?>" class="bbp-reply-header">

	<div class="bbp-meta">
               <span class="bbp-reply-post-date"><strong>Posted on: </strong><?php bbp_reply_post_date(); ?></span>
          <?php if ( bbp_is_single_user_replies() ) : ?>
                <?php endif; ?>
                <?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>
                <?php //bbp_reply_admin_links(); ?>
                <?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>
        </div><!-- .bbp-meta -->
</div><!-- #post-<?php bbp_reply_id(); ?> -->

<div <?php bbp_reply_class(); ?>>

	<div class="bbp-reply-author">

		<?php  do_action( 'bbp_theme_before_reply_author_details' ); ?>

		<?php //bbp_reply_author_link( array( 'sep' => '<br />', 'show_role' => false ) ); ?>
               <?php 
	       $reply_id = bbp_get_reply_id();
		   $reply_author_id = get_post_field( 'post_author',  $reply_id );
		   $user_data = get_userdata( $reply_author_id );
		    $reply_author = $user_data->user_nicename;
	      echo "<a rel='tooltip' href='#' title='" .$reply_author."'>"; echo bbp_get_reply_author_avatar( $reply_id , '50' ); echo "</a> <br> ";  echo  bbp_reply_author_display_name($reply_id );  echo " <br><br> "; ?>
		     <?php if ( bbp_is_user_keymaster() ) : ?>

			<?php do_action( 'bbp_theme_before_reply_author_admin_details' ); ?>
           
			<div class="bbp-reply-ip"><?php //bbp_author_ip( bbp_get_reply_id() ); ?></div>
  
			<?php do_action( 'bbp_theme_after_reply_author_admin_details' ); ?>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>

	</div><!-- .bbp-reply-author -->
      
	<div class="bbp-reply-content">

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>
<div style="margin-top:-20px; ">
		<?php bbp_reply_content(); ?>
</div>
		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

	</div><!-- .bbp-reply-content -->

</div><!-- .reply -->
