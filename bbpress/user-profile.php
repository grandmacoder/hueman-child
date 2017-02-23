<?php

/**
 * User Profile
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php
$url = home_url(add_query_arg(array(),$wp->request));
$ID = substr(strrchr($url, "/"), 1);
$current_user = get_userdata( $ID); 
$referrer =substr_replace(wp_get_referer(),"", -1);
$prevPost=substr(strrchr($referrer, "/"), 1);
global $wpdb;
$topic= $wpdb->get_results("select * from wp_posts where post_name='" . $prevPost."'", OBJECT);
foreach ($topic as $item){
$topic_id= $item->ID;
$topic_title=$item->post_title;
$forum_id= $item->post_parent;
$topic_author = $item->post_author;
}
$replies = $wpdb->get_results("select * from wp_posts where post_parent=" . $topic_id." and post_status='publish' and post_author= " . $ID, OBJECT);
$user_replies = $wpdb->num_rows;
foreach ($replies as $item){
$replystring .= "<div style='width:100%; background-color:#Dc8800;'><p>". $item->post_date . "</p></div><p>". $item->post_content."</p>";	
}
?>

		<h2 class="entry-title"><?php _e( 'Discussion Activity', 'bbpress' ); ?></h2>
		<ul  class="bbp-lead-topic-user-profile">
          <div>
		  	
		   <?php  echo get_avatar($ID) ."<BR>" . $current_user->user_nicename;?>
<?php 
			 if ($topic_author == $ID){
				   echo "<p class=bbp-user-reply-count". $current_user->user_nicename ." started the current topic,  " . $topic_title ."</p>";
			    }
			?>
		    <p class="bbp-user-topic-count"><?php printf( __( '%s started  %s topic(s) on this site.',  'bbpress' ), $current_user->user_nicename,bbp_get_user_topic_count_raw() ); ?></p>
			<p class="bbp-user-reply-count"><?php printf( __( '%s wrote  %s replie(s) on this site.', 'bbpress' ), $current_user->user_nicename,bbp_get_user_reply_count_raw() ); ?></p>
		</div>
		</ul>


		<div>
              <?php echo $current_user->user_nicename ." wrote " . $user_replies. " replies in <a href='".$referrer."'>". $topic_title ."</a><br><br>"; ?>
			 <?php
			if ($user_replies > 0){
		        echo $replystring ;
			}
			?>
		</div>


