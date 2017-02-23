<?php

/**
 * Single Topic Lead Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<?php do_action( 'bbp_template_before_lead_topic' ); ?>
<?php
$ssParent_forum_ID =  13261;
$topic_id= bbp_get_topic_id();
$topic_post = get_post($topic_id);
$topic_author_id =get_the_author_meta('ID') ;
$forum_title = bbp_get_forum_title( $parent->ID );
$post_parent = $topic_post->post_parent;
$forum_parent_id= wp_get_post_parent_id( $post_parent );
//see if there is a page that is part of this discussion. If so create a link and thumbnail back to the page
$associated_page_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM wp_postmeta where meta_key='page_discussion_forum' AND meta_value =%d", $post_parent));

$webinar_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM wp_postmeta where meta_key='webinar_forum' AND meta_value =%d", $post_parent));
if (is_user_logged_in()) {
$current_user=get_current_user_id();
$subscribedUserID=$wpdb->get_var($wpdb->prepare("select user_id from bbp_topic_subscribe where topic_post_id = %d and user_id = %d",$topic_id, $current_user)); 

	if ($subscribedUserID > 0){
	$subscribe='<span class="post-title">'.$forum_title . ': '. $topic_post->post_title.'</span><span style="float:right"><abbr rel="tooltip" title="Unsubscribe to no longer receive a daily email with replies to the topic each day"><input type="button" id="unsubscribe_to_forum" data-userid='.$current_user.' data-postid='. $topic_id.'  value="Unsubscribe"></abbr></span>';
	}
	else{
	$subscribe='<span class=post-title>'.$forum_title . ': '. $topic_post->post_title.'</span><span style="float:right"><abbr rel="tooltip" title="Subscribe to the topic to receive a daily email with replies to the topic each day"><input type="button" id="subscribe_to_forum" name="subscribe_to_forum" data-userid='.$current_user.' data-postid='. $topic_id.' value="Subscribe"></abbr></span>';	
	}
}
else{
$subscribe='<span class="post-title">'.$forum_title . ': '. $topic_post->post_title.'</span><span style="float:right"><abbr rel="tooltip" title="Please login to subscribe to this topic."><a href="/login/"><input type="button" id="login_to_subscribe" name="subscribe_to_forum" onclick="window.location.href=\''.  get_site_url() .'/login/\'" value="Subscribe"></abbr></span>';	
}
if ($forum_parent_id <> $ssParent_forum_ID){
echo $subscribe;
}	

?>
<p>
<?php if ($webinar_id > 0){
//echo '<a href="/?p='.$webinar_id .'" target=_blank>Watch the webinar</a> that goes with this discussion.</p>';
}
elseif($associated_page_id > 0){
//$returnThumnail=get_the_post_thumbnail( $associated_page_id, array( 80, 80)  ); 
//echo "<a href='".get_site_url(). "/?p=".$associated_page_id ."' title='Return to facilitator community' rel='tooltip'>". $returnThumnail ."</a>";	
}
else{
//echo '<a href="'.wp_get_referer().'">Go back to previous page</a>';	
}
?>
</p>
<ul id="bbp-topic-<?php bbp_topic_id(); ?>-lead" class="bbp-lead-topic">
<li class="bbp-header-lead-topic">
         <div>
		 <span class="bbp-topic-post-date"><?php  bbp_topic_post_date(); ?></span><div class="bbp-lead-topic-content"><?php  echo bbp_get_topic_content(); ?></div>
		 <?php  echo get_avatar($topic_author_id); echo "<br>Started by: " . bbp_get_topic_author();?>
        <?php bbp_topic_admin_links(); ?>
          </div>
</li><!-- .bbp-header -->

</ul><!-- #bbp-topic-<?php bbp_topic_id(); ?>-lead -->

<?php do_action( 'bbp_template_after_lead_topic' ); ?>
